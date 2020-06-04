<?php

namespace App\Http\Controllers;

use App\UninstallTracker;
use App\Tracker;
use App\TrackerDetails;
use App\Response;
use Illuminate\Http\Request;
use Validator;
use Mail;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = 'Welcome to WebAppick';
        return response()->json($response, 200);
    }

    /**
     * Store a newly created track reason api v1 data in storage.
     *
     * @param Request $request
     * @return json data and response code
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'reason_id' => 'required',
            'plugin' => 'required'
        ]);

        if( $validator->fails() ){
            $response = [
                'status'    => false,
                'message'   => 'Validation Error',
                'data'      => $validator->errors()
            ];
            return response()->json($response, 404);
        }

        $uninstallTracker = UninstallTracker::create([
            'reason_id'     => (isset($input['reason_id'])) ? $input['reason_id'] : '',
            'plugin'        => (isset($input['plugin'])) ? $input['plugin'] : '',
            'url'           => (isset($input['url'])) ? $input['url'] : '',
            'user_email'    => (isset($input['user_email'])) ? $input['user_email'] : '',
            'user_name'     => (isset($input['user_name'])) ? $input['user_name'] : '',
            'reason_info'   => (isset($input['reason_info'])) ? $input['reason_info'] : '',
            'server_info'   => serialize([
                'software'      => (isset($input['software'])) ? $input['software'] : '',
                'php_version'   => (isset($input['php_version'])) ? $input['php_version'] : '',
                'mysql_version' => (isset($input['mysql_version'])) ? $input['mysql_version'] : '',
                'wp_version'    => (isset($input['wp_version'])) ? $input['wp_version'] : '',
            ]),
            'locale'        => (isset($input['locale'])) ? $input['locale'] : '',
            'multisite'     => (isset($input['multisite'])) ? $input['multisite'] : ''
        ]);

        $responseData = Response::where('reason', $input['reason_id'])->first();
        if( $responseData && $responseData->status == 1 ){
            $data = array(
                'name'          => $input['user_name'],
                'message_body'  => $responseData->message
            );
            $name       = $input['user_name'];
            $email      = $input['user_email'];
            $subject    = $responseData->subject;
            Mail::send('mail', $data, function($message) use ($name, $email, $subject) {
                $message->to( $email, $name)
                        ->subject($subject)
                        ->from('webappick@gmail.com','Webappick');
            });
        }

        $response = [
          'status'  => true,
          'message' => 'Thank you for your Feedback'
        ];
        return response()->json($response, 200);
    }

    /**
     * Store a newly created track reason api v2 data in storage.
     *
     * @param Request $request
     * @return json data and response code
     */
    public function reasonTrackerV2(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'reason_id' => 'required',
            'plugin' => 'required'
        ]);

        if( $validator->fails() ){
            $response = [
                'status'    => false,
                'message'   => 'Validation Error',
                'data'      => $validator->errors()
            ];
            return response()->json($response, 404);
        }

        /*$uninstallTracker = UninstallTracker::create([
            'reason_id'     => (isset($input['reason_id'])) ? $input['reason_id'] : '',
            'hash'          => (isset($input['hash'])) ? $input['hash'] : '',                   //New for v2
            'site'          => (isset($input['site'])) ? $input['site'] : '',                   //New for v2
            'admin_email'   => (isset($input['admin_email'])) ? $input['admin_email'] : '',     //New for v2
            'first_name'    => (isset($input['first_name'])) ? $input['first_name'] : '',       //New for v2
            'last_name'     => (isset($input['last_name'])) ? $input['last_name'] : '',         //New for v2
            'plugin'        => (isset($input['plugin'])) ? $input['plugin'] : '',
            'url'           => (isset($input['url'])) ? $input['url'] : '',
            'user_email'    => (isset($input['user_email'])) ? $input['user_email'] : '',
            'user_name'     => (isset($input['user_name'])) ? $input['user_name'] : '',
            'reason_info'   => (isset($input['reason_info'])) ? $input['reason_info'] : '',
            'server_info'   => serialize([
                'ip_address'    => (isset($input['ip_address'])) ? $input['ip_address'] : '',
                'server'        => (isset($input['server'])) ? $input['server'] : '',
                'wp'            => (isset($input['wp'])) ? $input['wp'] : '',
                'software'      => (isset($input['software'])) ? $input['software'] : '',
                'php_version'   => (isset($input['php_version'])) ? $input['php_version'] : '',
                'mysql_version' => (isset($input['mysql_version'])) ? $input['mysql_version'] : '',
                'wp_version'    => (isset($input['wp_version'])) ? $input['wp_version'] : '',
                'extra'         => (isset($input['extra'])) ? $input['extra'] : '' ,
            ]),
            'locale'        => (isset($input['locale'])) ? $input['locale'] : '',
            'multisite'     => (isset($input['multisite'])) ? $input['multisite'] : '',
            'version'       => (isset($input['version'])) ? $input['version'] : ''              //New for v2
        ]);*/

        $uninstallTracker = UninstallTracker::create([
            'reason_id'     => (isset($input['reason_id'])) ? $input['reason_id'] : '',
            'plugin'        => (isset($input['plugin'])) ? $input['plugin'] : '',
            'url'           => (isset($input['url'])) ? $input['url'] : '',
            'user_email'    => (isset($input['user_email'])) ? $input['user_email'] : '',
            'user_name'     => (isset($input['user_name'])) ? $input['user_name'] : '',
            'reason_info'   => (isset($input['reason_info'])) ? $input['reason_info'] : '',
            'server_info'   => serialize([
                'ip_address'    => (isset($input['ip_address'])) ? $input['ip_address'] : '',
                'server'        => (isset($input['server'])) ? $input['server'] : '',
                'wp'            => (isset($input['wp'])) ? $input['wp'] : '',
                'software'      => (isset($input['software'])) ? $input['software'] : '',
                'php_version'   => (isset($input['php_version'])) ? $input['php_version'] : '',
                'mysql_version' => (isset($input['mysql_version'])) ? $input['mysql_version'] : '',
                'wp_version'    => (isset($input['wp_version'])) ? $input['wp_version'] : '',
                'extra'         => (isset($input['extra'])) ? $input['extra'] : '' ,

                'version'       => (isset($input['version'])) ? $input['version'] : '',

                'hash'          => (isset($input['hash'])) ? $input['hash'] : '',
                'site'          => (isset($input['site'])) ? $input['site'] : '',
                'admin_email'   => (isset($input['admin_email'])) ? $input['admin_email'] : '',
                'first_name'    => (isset($input['first_name'])) ? $input['first_name'] : '',
                'last_name'     => (isset($input['last_name'])) ? $input['last_name'] : '',
            ]),
            'locale'        => (isset($input['locale'])) ? $input['locale'] : '',
            'multisite'     => (isset($input['multisite'])) ? $input['multisite'] : ''
        ]);




        $responseData = Response::where('reason', $input['reason_id'])->first();
        if( $responseData && $responseData->status == 1 ){
            $data = array(
                'name'          => $input['user_name'],
                'message_body'  => $responseData->message
            );
            $name       = $input['user_name'];
            $email      = $input['user_email'];
            $subject    = $responseData->subject;
            Mail::send('mail', $data, function($message) use ($name, $email, $subject) {
                $message->to( $email, $name)
                        ->subject($subject)
                        ->from('webappick@gmail.com','WebAppick');
            });
        }

        $response = [
          'status'  => true,
          'message' => 'Thank you for your Feedback'
        ];
        return response()->json($response, 200);
    }

    /**
     * Store a newly created track api v1 data in storage.
     *
     * @param Request $request
     * @return json data and response code
     */
    public function track(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'plugin' => 'required',
        ]);

        if( $validator->fails() ){
            $response = [
                'status'    => false,
                'message'   => 'Validation Error',
                'data'      => $validator->errors()
            ];
            return response()->json($response, 404);
        }

        $tracking_id    = '';
        $url            = (isset($input['url'])) ? $input['url'] : '';
        $plugin         = (isset($input['plugin'])) ? $input['plugin'] : '';
        /*$tracker        = Tracker::where('url', $url)->first();*/
        $tracker        = Tracker::where(['url'=> $url, 'plugin'=> $plugin])->first();
        if( $tracker ){
            $tracking_id = $tracker->id;
        }else{
            $uninstallTracker = Tracker::create([
                'site'              => (isset($input['site'])) ? $input['site'] : '' ,
                'url'               => (isset($input['url'])) ? $input['url'] : '' ,
                'admin_email'       => (isset($input['admin_email'])) ? $input['admin_email'] : '' ,
                'first_name'        => (isset($input['first_name'])) ? $input['first_name'] : '' ,
                'last_name'         => (isset($input['last_name'])) ? $input['last_name'] : '' ,
                'hash'              => (isset($input['hash'])) ? $input['hash'] : '' ,
                'plugin'            => (isset($input['plugin'])) ? $input['plugin'] : ''
            ]);
            $tracking_id = $uninstallTracker->id;
        }

        if( $tracking_id ){
            $log = json_encode([
                'server'            => (isset($input['server'])) ? $input['server'] : '',
                'wp'                => (isset($input['wp'])) ? $input['wp'] : '',
                'active_plugins'    => (isset($input['active_plugins'])) ? $input['active_plugins'] : '',
                'inactive_plugins'  => (isset($input['inactive_plugins'])) ? $input['inactive_plugins'] : '',
                'ip_address'        => (isset($input['ip_address'])) ? $input['ip_address'] : '',
                'theme'             => (isset($input['theme'])) ? $input['theme'] : '',
                'client'            => (isset($input['client'])) ? $input['client'] : '',
                'version'           => (isset($input['version'])) ? $input['version'] : '' ,
                'extra'             => (isset($input['extra'])) ? $input['extra'] : '' ,
            ]);

            TrackerDetails::create([
                'tracking_id'   => $tracking_id,
                'log'           => $log
            ]);
        }

        $response = [
          'status'  => true,
          'message' => 'Thank you for your Feedback'
        ];
        return response()->json($response, 200);
    }

    public function testMail(){
        $data = array(
            'name'          => "Test",
            'message_body'  => "Test Body"
        );
        $name       = "Test Name";
        $email      = "mahabub.webappick@gmail.com";
        $subject    = "Test Subject";
        Mail::send('mail', $data, function($message) use ($name, $email, $subject) {
            $message->to( $email, $name)
                ->subject($subject)
                ->from('webappick@gmail.com','WebAppick');
        });

        echo 'Send';
    }
}
