<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allplugins     = DB::select(DB::raw("SELECT plugin FROM uninstall_tracking GROUP BY plugin"));
        $plugin         = (count($allplugins) > 0) ? $allplugins[0]->plugin : '';
        $limit          = 30;

        $pluginInfo                         = $this->pluginsInfo($plugin, $limit);
        $reasonWiseUninstall                = $pluginInfo['pluginReasonWiseUninstallData'];
        $totalUninstall                     = $pluginInfo['pluginWiseTotalUninstall'];
        $totalActivate                      = $pluginInfo['totalActivate'];
        $deactivateRate                     = $pluginInfo['deactivateRate'];
        $activateRate                       = $pluginInfo['activateRate'];

        $totalActiveInstall = array(
            $plugin       => $pluginInfo['totalActiveInstall']
        );
        $totalDownload = array(
            $plugin       => $pluginInfo['totalDownload']
        );

        $uninstallArray =  array(
            'date'      => array(),
            'install'   => array(),
            'uninstall' => array()
        );
        $installUninstallGraph = ($pluginInfo) ? $pluginInfo['installUninstallGraph'] : $uninstallArray;

        $header     = true;
        $pluginName = $plugin;
        $startDate  = '';
        $endDate    = '';
        $plugins    = DB::select(DB::raw("SELECT plugin FROM uninstall_tracking GROUP BY plugin"));
        return view('pages.home', compact('header', 'pluginName', 'plugins', 'startDate', 'endDate',
            'totalDownload',
            'totalActiveInstall',
            'totalActivate',
            'totalUninstall',
            'reasonWiseUninstall',
            'installUninstallGraph',
            'deactivateRate',
            'activateRate'));
    }

    public function search(Request $request){
        $postData       = $request->all();
        $startDate      = ($postData['start_date']) ? date('Y-m-d', strtotime($postData['start_date'])) : '';
        $endDate        = $postData['end_date'] ? date('Y-m-d', strtotime($postData['end_date'])) : '';
        $plugin         = $postData['plugins'];

        if($startDate > $endDate){
            $this->index();
        }

        if($startDate != '' && $endDate != ''){
            $limit = (new \DateTime($startDate))->diff(new \DateTime(date('Y-m-d')))->days + 1;
        }
        else{
            $limit = 30;
        }

        $pluginInfo                         = $this->pluginsInfo($plugin, $limit, $startDate, $endDate);
        $reasonWiseUninstall                = $pluginInfo['pluginReasonWiseUninstallData'];
        $totalUninstall                     = $pluginInfo['pluginWiseTotalUninstall'];
        $totalActivate                      = $pluginInfo['totalActivate'];
        $deactivateRate                     = $pluginInfo['deactivateRate'];
        $activateRate                       = $pluginInfo['activateRate'];

        $totalActiveInstall = array(
            $plugin       => $pluginInfo['totalActiveInstall']
        );
        $totalDownload = array(
            $plugin       => $pluginInfo['totalDownload']
        );

        $uninstallArray =  array(
            'date'      => array(),
            'install'   => array(),
            'uninstall' => array()
        );
        $installUninstallGraph = ($pluginInfo) ? $pluginInfo['installUninstallGraph'] : $uninstallArray;

        $header     = true;
        $pluginName = ($plugin) ? $plugin : 'All Plugins';
        $startDate  = ($postData['start_date']) ? date('m/d/Y', strtotime($postData['start_date'])) : '';
        $endDate    = $postData['end_date'] ? date('m/d/Y', strtotime($postData['end_date'])) : '';
        $plugins    = DB::select(DB::raw("SELECT plugin FROM uninstall_tracking GROUP BY plugin"));

        return view('pages.home', compact('header', 'pluginName', 'plugins', 'startDate', 'endDate',
                                            'totalDownload',
                                            'totalActiveInstall',
                                            'totalActivate',
                                            'totalUninstall',
                                            'reasonWiseUninstall',
                                            'installUninstallGraph',
                                            'deactivateRate',
                                            'activateRate'));
    }

    public function pluginsInfo($plugin, $limit, $startDate = '', $endDate = ''){
        $client = new \GuzzleHttp\Client();

        $allplugins     = DB::select(DB::raw("SELECT plugin_slag FROM settings WHERE plugin_name = '$plugin'"));
        $pluginSlug     = (count($allplugins) > 0) ? $allplugins[0]->plugin_slag : '';

        try{
            $client->get('https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='.$pluginSlug);
        }
        catch (\Exception $e){
            $message = $e->getMessage();
            echo 'API Not Response';
            die();
        }

        //Current Date Plugin Download
        $todayDownloadResponse  = $client->get('https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='.$pluginSlug.'&limit=1');
        $todayDownload          = (array) json_decode($todayDownloadResponse->getBody()->getContents());

        //Date Wise Plugin Download
        $logDownloadResponse    = $client->get('https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='.$pluginSlug.'&limit='.$limit);
        $logDownload            = (array) json_decode($logDownloadResponse->getBody()->getContents());
        $logDownload            = array_merge($logDownload, $todayDownload);

        //Plugin Info Data
        $pluginInfo             = ($client->get('https://api.wordpress.org/plugins/info/1.0/'.$pluginSlug.'.json?fields[active_installs]=true'));
        $pluginData             = (array) json_decode($pluginInfo->getBody()->getContents());

        $totalDownload          = 0;
        $totalActiveInstall     = 0;
        if(count($pluginData) > 1){
            $totalDownload          = $pluginData['downloaded'];
            $totalActiveInstall     = $pluginData['active_installs'];
        }

        //Date Wise Download
        $totalActivate = $totalDownload;
        $newLogDownload = array();
        if($startDate != '' && $endDate != ''){
            foreach ($logDownload as $key => $val) {
                if (($key >= $startDate) && ($key <= $endDate)) {
                    $newLogDownload[$key] = $val;
                }
            }
            $logDownload    = $newLogDownload;
            $totalActivate  = array_sum($newLogDownload);
        }

        //Activate Rate Calculation
        $pValue         = 0;
        $dailyGrowth    = array();
        $status         = false;
        foreach ($logDownload as $key => $value){
            if($status){
                $dailyGrowth[]  = (($value - $pValue)/$pValue) * 100;
            }
            $pValue         = $value;
            $status         = true;
        }
        $activateRate = (count($dailyGrowth) > 0) ? array_sum($dailyGrowth)/count($dailyGrowth) : 0;

        //SQL condition
        if($startDate != '' && $endDate != ''){
            $cond                   = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
            $condPlugin             = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND plugin = '".$plugin."'";
            $uninstallCondPlugin    = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND plugin = '".$plugin."'";
        }
        else{
            $startDate              = date('Y-m-d', strtotime('-30 days'));
            $endDate                = date('Y-m-d');
            $cond                   = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'";
            $condPlugin             = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND plugin = '".$plugin."'";
            $uninstallCondPlugin    = "WHERE DATE(created_at) BETWEEN '".date('Y-m-d', strtotime('-1 month'))."' AND '".date('Y-m-d')."' AND plugin = '".$plugin."'";
        }

        //Plugin Wise Uninstall
        $pluginWiseTotalUninstallData   = DB::select(DB::raw("SELECT id FROM uninstall_tracking $condPlugin"));
        $pluginWiseTotalUninstall       = count($pluginWiseTotalUninstallData);

        //Monthly Plugin Uninstall
        $monthlyUninstallData   = DB::select(DB::raw("SELECT count(id) total_unindtall, DATE(created_at) uninstall_date FROM uninstall_tracking 
                                                        $condPlugin
                                                        GROUP BY uninstall_date"));
        $logUninstall = array();
        foreach ($monthlyUninstallData as $resultData){
            $logUninstall[$resultData->uninstall_date] = $resultData->total_unindtall;
        }

        //Plugin Uninstall Reason
        $reasonWiseUninstallData            = DB::select(DB::raw("SELECT reason_id, count(id) total_unindtall FROM uninstall_tracking ".$cond." GROUP BY reason_id"));
        $pluginReasonWiseUninstallData      = DB::select(DB::raw("SELECT reason_id, count(id) total_unindtall FROM uninstall_tracking 
                                                        ".$condPlugin."                                                        
                                                        GROUP BY reason_id"));

        //Deactivate Rate Calculation
        $dateWiseUninstallData      = DB::select(DB::raw("SELECT count(id) total_unindtall, DATE(created_at) uninstall_date FROM uninstall_tracking 
                                                        $uninstallCondPlugin
                                                        GROUP BY uninstall_date"));
        $pValue         = 0;
        $dailyGrowth    = array();
        $status         = false;
        foreach ($dateWiseUninstallData as $resultData){
            if($status){
                $dailyGrowth[]  = (($resultData->total_unindtall - $pValue)/$pValue) * 100;
            }
            $pValue         = $resultData->total_unindtall;
            $status         = true;
        }
        $deactivateRate = (count($dailyGrowth) > 0) ? array_sum($dailyGrowth)/count($dailyGrowth) : 0;

        //download Per Month Calculation
        $downloadPerMonth = array();
        foreach ($logDownload as $key => $val) {
            $month = substr($key, 0, 7);
            if (!isset($downloadPerMonth[$month])) {
                $downloadPerMonth[$month] = 0;
                if($month == substr(date('Y-m-d'), 0, 7)){
                    $downloadPerMonth[$month] = (array_key_exists(date('Y-m-d'), $todayDownload)) ? $todayDownload[date('Y-m-d')] : 0;
                }
            }
            $downloadPerMonth[$month] += $val;
        }

        //Activation Deactivation Graph
        $installUninstallGraph['date'][]        = '';
        $installUninstallGraph['install'][]     = 0;
        $installUninstallGraph['uninstall'][]   = 0;
        if(31 < ((new \DateTime($startDate))->diff(new \DateTime(date($endDate)))->days + 1)){
            //Monthly Activation Deactivation Graph
            $uninstallPerMonth = array();
            foreach ($logUninstall as $key => $val) {
                $month = substr($key, 0, 7);
                if (!isset($uninstallPerMonth[$month])) {
                    $uninstallPerMonth[$month] = 0;
                }
                $uninstallPerMonth[$month] += $val;
            }
            foreach ($downloadPerMonth as $key => $value) {
                $installUninstallGraph['date'][]      = "'".date('M-y', strtotime($key))."'";
                $installUninstallGraph['install'][]   = ($value) ? $value : 0;
                $installUninstallGraph['uninstall'][] = (array_key_exists(date('Y-m', strtotime($key)) ,$uninstallPerMonth)) ? $uninstallPerMonth[date('Y-m', strtotime($key))] : 0;
            }
        }else{
            //Daily Activation Deactivation Graph
            if($startDate != '' && $endDate != ''){
                $interval               = new \DateInterval('P1D');
                $realEnd                = new \DateTime($endDate);
                $realEnd->add($interval);
                $period                 = new \DatePeriod(new \DateTime($startDate), $interval, $realEnd);
            }else{
                $startDate              = date('Y-m-d', strtotime("-1 month"));
                $endDate                = date('Y-m-d');
                $interval               = new \DateInterval('P1D');
                $realEnd                = new \DateTime($endDate);
                $realEnd->add($interval);
                $period                 = new \DatePeriod(new \DateTime($startDate), $interval, $realEnd);
            }
            foreach($period as $date) {
                $checkMonth                           = $date->format('Y-m-d');
                $installUninstallGraph['date'][]      = "'".date('d-M', strtotime($checkMonth))."'";
                $installUninstallGraph['install'][]   = (array_key_exists($checkMonth ,$logDownload)) ? $logDownload[$checkMonth] : 0;
                $installUninstallGraph['uninstall'][] = (array_key_exists($checkMonth ,$logUninstall)) ? $logUninstall[$checkMonth] : 0;
            }
        }
        (count($installUninstallGraph['date']) > 1) ? array_shift($installUninstallGraph['date']) : $installUninstallGraph['date'];
        (count($installUninstallGraph['date']) > 1) ? array_shift($installUninstallGraph['install']) : $installUninstallGraph['install'];
        (count($installUninstallGraph['date']) > 1) ? array_shift($installUninstallGraph['uninstall']) : $installUninstallGraph['uninstall'];

        return array(
            'totalDownload'                     => str_replace(',', '', $totalDownload),
            'totalActiveInstall'                => $totalActiveInstall,
            'totalActivate'                     => $totalActivate,
            'pluginWiseTotalUninstall'          => $pluginWiseTotalUninstall,
            'reasonWiseUninstallData'           => $reasonWiseUninstallData,
            'pluginReasonWiseUninstallData'     => $pluginReasonWiseUninstallData,
            'installUninstallGraph'             => $installUninstallGraph,
            'deactivateRate'                    => $deactivateRate,
            'activateRate'                      => $activateRate
        );
    }
}
