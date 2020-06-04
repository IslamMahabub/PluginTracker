<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $header     = false;
        $pluginName = '';
        $startDate  = '';
        $endDate    = '';
        $plugins    = DB::select(DB::raw("SELECT plugin FROM plugin_tracking GROUP BY plugin"));
        $search_string    = '';

        return view('pages.tracker', compact('header', 'pluginName', 'plugins', 'startDate', 'endDate', 'search_string'));
    }

    public function tracklist(Request $request)
    {
        $postData       = $request->all();
        $startDate      = ($postData['start_date']) ? date('Y-m-d', strtotime($postData['start_date'])) : '';
        $endDate        = $postData['end_date'] ? date('Y-m-d', strtotime($postData['end_date'])) : '';
        $plugin         = $postData['plugins'];
        $search_string  = $postData['search_string'];

        if($startDate > $endDate){
            $this->index();
        }
        if($plugin) {
            $pluginInfo                 = $this->pluginsInfo($plugin, $startDate, $endDate, $search_string);
            $trackingData               = $pluginInfo['pluginWiseTrackingData'];
        }else{
            $pluginInfo                 = $this->pluginsInfo('', $startDate, $endDate, $search_string);
            $trackingData               = $pluginInfo['trackingData'];
        }

        $reasonList = array();
        foreach ($trackingData as $result){
            $reasonList['data'][] = [
                'details'       => '<a style="width:25px" target="_blank" title="Details" href="'.route("tracker").'/'.$result->id.'"> <img src="'. asset("images/details.png") .'" alt="Details" /> </a>',
                'created_at'    => date('d-m-Y', strtotime($result->created_at)),
                'plugin'        => $result->plugin,
                'site'          => $result->site,
                'admin_email'   => $result->admin_email,
                'url'           => $result->url,
                'first_name'    => $result->first_name,
                'last_name'     => $result->last_name
            ];
        }
        return json_encode($reasonList);
    }

    public function search(Request $request){
        $postData       = $request->all();
        $startDate      = ($postData['start_date']) ? date('Y-m-d', strtotime($postData['start_date'])) : '';
        $endDate        = $postData['end_date'] ? date('Y-m-d', strtotime($postData['end_date'])) : '';
        $plugin         = $postData['plugins'];
        $plugins        = DB::select(DB::raw("SELECT plugin FROM plugin_tracking GROUP BY plugin"));
        $search_string  = $postData['search_string'];

        if($startDate > $endDate){
            $this->index();
        }

        $header     = true;
        $pluginName = ($plugin) ? $plugin : 'All Plugins';
        $startDate  = ($postData['start_date']) ? date('m/d/Y', strtotime($postData['start_date'])) : '';
        $endDate    = $postData['end_date'] ? date('m/d/Y', strtotime($postData['end_date'])) : '';

        return view('pages.tracker', compact('header', 'pluginName', 'plugins', 'startDate', 'endDate', 'search_string'));
    }

    public function pluginsInfo($plugin, $startDate = '', $endDate = '', $search_string = ''){

        //SQL condition
        $cond       = '';
        if($startDate != '' && $endDate != ''){
            $cond                   = " WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
            $condPlugin             = " WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND plugin = '".$plugin."'";
        }
        else{
            $condPlugin             = " WHERE plugin = '".$plugin."'";
        }

        if($search_string){
            $condPlugin = $condPlugin." AND (url like '%$search_string%' 
                                        OR site  like '%$search_string%' 
                                        OR admin_email like '%$search_string%' 
                                        OR first_name like '%$search_string%' 
                                        OR last_name like '%$search_string%')";
            $cond       = ($cond) ? $cond." AND (url like '%$search_string%' 
                                            OR site  like '%$search_string%' 
                                            OR admin_email like '%$search_string%' 
                                            OR first_name like '%$search_string%' 
                                            OR last_name like '%$search_string%')" :
                                    "WHERE (url like '%$search_string%' 
                                            OR site  like '%$search_string%' 
                                            OR admin_email like '%$search_string%' 
                                            OR first_name like '%$search_string%' 
                                            OR last_name like '%$search_string%')";
        }

        //Plugin Tracking
        $trackData                          = DB::select(DB::raw("SELECT * FROM plugin_tracking ".$cond." ORDER BY id DESC" ));
        $pluginWiseTrackingData             = DB::select(DB::raw("SELECT * FROM plugin_tracking ".$condPlugin." ORDER BY id DESC" ));

        return array(
            'trackingData'                  => $trackData,
            'pluginWiseTrackingData'        => $pluginWiseTrackingData,
        );
    }

    public function details($id)
    {
        $tracker_details = DB::select(DB::raw("SELECT * FROM plugin_tracking_details WHERE tracking_id = $id ORDER BY id DESC"));
        $header     = false;
        return view('pages.tracker_details',compact('tracker_details', 'header'));
    }



}
