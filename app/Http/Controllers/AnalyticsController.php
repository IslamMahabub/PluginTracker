<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
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

        $pluginInfo                 = $this->pluginsInfo($plugin, $limit);

        $activeInactiveRate         = $pluginInfo['activeInactiveRate'];
        $versionDownload            = $pluginInfo['versionDownloadData'];

        if( $pluginInfo['languageWiseUninstall'] ){
            $languageWiseUninstall      = $pluginInfo['languageWiseUninstall'];
        }

        if( $pluginInfo['reasonWiseUninstall'] ){
            $reasonWiseUninstall        = $pluginInfo['reasonWiseUninstall'];
        }

        $multiSiteWiseUninstall     = $pluginInfo['multiSiteWiseUninstallData'];
        $wordpressVersionStatsData  = $pluginInfo['wordpressVersionStatsData'];
        $mysqlVersionStatsData      = $pluginInfo['mysqlVersionStatsData'];

        $header     = true;
        $pluginName = $plugin;
        $startDate  = '';
        $endDate    = '';
        $plugins    = DB::select(DB::raw("SELECT plugin FROM uninstall_tracking GROUP BY plugin"));
        return view('pages.analytics', compact('header', 'pluginName', 'plugins', 'startDate', 'endDate',
            'activeInactiveRate',
            'versionDownload',
            'multiSiteWiseUninstall',
            'reasonWiseUninstall',
            'wordpressVersionStatsData',
            'mysqlVersionStatsData',
            'languageWiseUninstall'));
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

        $pluginInfo = $this->pluginsInfo($plugin, $limit, $startDate, $endDate);

        $activeInactiveRate         = $pluginInfo['activeInactiveRate'];
        $versionDownload            = $pluginInfo['versionDownloadData'];

        if( $pluginInfo['languageWiseUninstall'] ){
            $languageWiseUninstall      = $pluginInfo['languageWiseUninstall'];
        }

        if( $pluginInfo['reasonWiseUninstall'] ){
            $reasonWiseUninstall        = $pluginInfo['reasonWiseUninstall'];
        }

        $multiSiteWiseUninstall     = $pluginInfo['multiSiteWiseUninstallData'];
        $wordpressVersionStatsData  = $pluginInfo['wordpressVersionStatsData'];
        $mysqlVersionStatsData      = $pluginInfo['mysqlVersionStatsData'];

        $header     = true;
        $pluginName = ($plugin) ? $plugin : 'All Plugins';
        $startDate  = ($postData['start_date']) ? date('m/d/Y', strtotime($postData['start_date'])) : '';
        $endDate    = $postData['end_date'] ? date('m/d/Y', strtotime($postData['end_date'])) : '';
        $plugins    = DB::select(DB::raw("SELECT plugin FROM uninstall_tracking GROUP BY plugin"));

        return view('pages.analytics', compact('header', 'pluginName', 'plugins', 'startDate', 'endDate',
            'activeInactiveRate',
            'versionDownload',
            'multiSiteWiseUninstall',
            'reasonWiseUninstall',
            'wordpressVersionStatsData',
            'mysqlVersionStatsData',
            'languageWiseUninstall'));
    }

    public function pluginsInfo($plugin, $limit, $startDate = '', $endDate = ''){
        $client = new \GuzzleHttp\Client();


        $allplugins     = DB::select(DB::raw("SELECT plugin_slag FROM settings WHERE plugin_name = '$plugin'"));
        $pluginSlag     = (count($allplugins) > 0) ? $allplugins[0]->plugin_slag : '';

        try{
            $client->get('https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='.$pluginSlag);
        }
        catch (\Exception $e){
            $message = $e->getMessage();
            echo 'API Not Response';
            die();
        }

        //Wordpress Version Download
        $wordpressVersionStatsResponse          = $client->get('https://api.wordpress.org/stats/wordpress/1.0/');
        $wordpressVersionStats                  = (array) json_decode($wordpressVersionStatsResponse->getBody()->getContents());
        $wordpressVersionStatsData['color'][]   = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
        $wordpressVersionStatsData['version'][] = 0;
        $wordpressVersionStatsData['data'][]    = 0;
        foreach ($wordpressVersionStats as $key => $value){
            $wordpressVersionStatsData['color'][]   = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
            $wordpressVersionStatsData['version'][] = "'".$key."'";
            $wordpressVersionStatsData['data'][]    = number_format($value, 2);
        }
        (count($wordpressVersionStatsData['color']) > 1) ? array_shift($wordpressVersionStatsData['color']) : $wordpressVersionStatsData['color'];
        (count($wordpressVersionStatsData['color']) > 1) ? array_shift($wordpressVersionStatsData['version']) : $wordpressVersionStatsData['version'];
        (count($wordpressVersionStatsData['color']) > 1) ? array_shift($wordpressVersionStatsData['data']) : $wordpressVersionStatsData['data'];

        //Mysql Version Use
        $mysqlVersionUseResponse            = $client->get('https://api.wordpress.org/stats/mysql/1.0/');
        $mysqlVersionUseStats               = (array) json_decode($mysqlVersionUseResponse->getBody()->getContents());
        $mysqlVersionStatsData['color'][]   = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
        $mysqlVersionStatsData['version'][] = 0;
        $mysqlVersionStatsData['data'][]    = 0;
        foreach ($mysqlVersionUseStats as $key => $value){
            $mysqlVersionStatsData['color'][]   = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
            $mysqlVersionStatsData['version'][] = "'".$key."'";
            $mysqlVersionStatsData['data'][]    = number_format($value, 2);
        }
        (count($mysqlVersionStatsData['color']) > 1) ? array_shift($mysqlVersionStatsData['color']) : $mysqlVersionStatsData['color'];
        (count($mysqlVersionStatsData['color']) > 1) ? array_shift($mysqlVersionStatsData['version']) : $mysqlVersionStatsData['version'];
        (count($mysqlVersionStatsData['color']) > 1) ? array_shift($mysqlVersionStatsData['data']) : $mysqlVersionStatsData['data'];

        //Version Wise Plugin Download
        $versionDownloadResponse            = $client->get('https://api.wordpress.org/stats/plugin/1.0/'.$pluginSlag);
        $versionDownload                    = (array) json_decode($versionDownloadResponse->getBody()->getContents());
        $versionDownloadData['color'][]     = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
        $versionDownloadData['version'][]   = 0;
        $versionDownloadData['data'][]      = 0;
        foreach ($versionDownload as $key => $value){
            $versionDownloadData['color'][]     = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
            $versionDownloadData['version'][]   = "'".$key."'";
            $versionDownloadData['data'][]      = number_format($value, 2);
        }
        (count($versionDownloadData['color']) > 1) ? array_shift($versionDownloadData['color']) : $versionDownloadData['color'];
        (count($versionDownloadData['color']) > 1) ? array_shift($versionDownloadData['version']) : $versionDownloadData['version'];
        (count($versionDownloadData['color']) > 1) ? array_shift($versionDownloadData['data']) : $versionDownloadData['data'];

        //Current Date Plugin Download
        $todayDownloadResponse  = $client->get('https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='.$pluginSlag.'&limit=1');
        $todayDownload          = (array) json_decode($todayDownloadResponse->getBody()->getContents());

        //Date Wise Plugin Download
        $logDownloadResponse    = $client->get('https://api.wordpress.org/stats/plugin/1.0/downloads.php?slug='.$pluginSlag.'&limit='.$limit);
        $logDownload            = (array) json_decode($logDownloadResponse->getBody()->getContents());
        $logDownload            = array_merge($logDownload, $todayDownload);

        //Date Wise Download
        $newLogDownload = array();
        if($startDate != '' && $endDate != ''){
            foreach ($logDownload as $key => $val) {
                if (($key >= $startDate) && ($key <= $endDate)) {
                    $newLogDownload[$key] = $val;
                }
            }
            $logDownload    = $newLogDownload;
        }

        //Average Download Growth
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
        $avgDownloadGrowth = (count($dailyGrowth) > 0) ? array_sum($dailyGrowth)/count($dailyGrowth) : 0;

        //SQL condition
        if($startDate != '' && $endDate != ''){
            $condPlugin             = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND plugin = '".$plugin."'";
            $uninstallCondPlugin    = "WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate' AND plugin = '".$plugin."'";
        }
        else{
            $condPlugin             = "WHERE plugin = '".$plugin."'";
            $uninstallCondPlugin    = "WHERE DATE(created_at) BETWEEN '".date('Y-m-d', strtotime('-1 month'))."' AND '".date('Y-m-d')."' AND plugin = '".$plugin."'";
        }

        //Average Uninstall Growth
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
        $avgUninstallGrowth = (count($dailyGrowth) > 0) ? array_sum($dailyGrowth)/count($dailyGrowth) : 0;

        //Active Inactive Rate
        $activeInactiveRate ['color'][] = "'#20c997'";
        $activeInactiveRate ['level'][] = "'".'Active'."'";
        $activeInactiveRate ['data'][]  = number_format($avgDownloadGrowth, 2);
        $activeInactiveRate ['color'][] = "'#dc3545'";
        $activeInactiveRate ['level'][] = "'".'Inactive'."'";
        $activeInactiveRate ['data'][]  = number_format($avgUninstallGrowth, 2);


        //Plugin Uninstall Reason
        $reasonWiseUninstall['color'][]     = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
        $reasonWiseUninstall['level'][]     = 0;
        $reasonWiseUninstall['data'][]      = 0;
        $pluginReasonWiseUninstallData      = DB::select(DB::raw("SELECT reason_id, count(id) total_unindtall FROM uninstall_tracking 
                                                        ".$condPlugin."                                                        
                                                        GROUP BY reason_id"));
        for($i = 0; $i < count($pluginReasonWiseUninstallData); $i++){
            $reasonWiseUninstall['color'][]     = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
            $reasonWiseUninstall['level'][]     =  "'".ucfirst(str_replace('-', ' ', $pluginReasonWiseUninstallData[$i]->reason_id))."'";
            $reasonWiseUninstall['data'][]      =  $pluginReasonWiseUninstallData[$i]->total_unindtall;
        }
        (count($reasonWiseUninstall['color']) > 1) ? array_shift($reasonWiseUninstall['color']) : $reasonWiseUninstall['color'];
        (count($reasonWiseUninstall['color']) > 1) ? array_shift($reasonWiseUninstall['level']) : $reasonWiseUninstall['level'];
        (count($reasonWiseUninstall['color']) > 1) ? array_shift($reasonWiseUninstall['data']) : $reasonWiseUninstall['data'];

        //Language Wise Plugin Uninstall
        $languageWiseUninstall['color'][]     = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
        $languageWiseUninstall['language'][]     = 0;
        $languageWiseUninstall['data'][]      = 0;
        $languageWiseUninstallData   = DB::select(DB::raw("SELECT count(id) total_unindtall, locale FROM uninstall_tracking 
                                                        $condPlugin
                                                        GROUP BY locale"));
        for($i = 0; $i < count($languageWiseUninstallData); $i++){
            $languageWiseUninstall['color'][]     = "'".'#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."'";
            $languageWiseUninstall['language'][]  =  "'".$this->language($languageWiseUninstallData[$i]->locale)."'";
            $languageWiseUninstall['data'][]      =  $languageWiseUninstallData[$i]->total_unindtall;
        }
        (count($languageWiseUninstall['color']) > 1) ? array_shift($languageWiseUninstall['color']) : $languageWiseUninstall['color'];
        (count($languageWiseUninstall['color']) > 1) ? array_shift($languageWiseUninstall['language']) : $languageWiseUninstall['language'];
        (count($languageWiseUninstall['color']) > 1) ? array_shift($languageWiseUninstall['data']) : $languageWiseUninstall['data'];

        //Multi site Wise Plugin Uninstall
        $multiSiteWiseUninstallData   = DB::select(DB::raw("SELECT Count(CASE 
                                                                           WHEN multisite = 'No' THEN 1 
                                                                         end) AS TotalNoMultisite, 
                                                                   Count(CASE 
                                                                           WHEN multisite = 'Yes' THEN 1 
                                                                         end) AS TotalYesMultisite 
                                                            FROM   uninstall_tracking 
                                                            $condPlugin"));
        $multiSiteWiseUninstall = array();
        foreach ($multiSiteWiseUninstallData as $result){
            $multiSiteWiseUninstall ['color'][] = "'#fd7e14'";
            $multiSiteWiseUninstall ['level'][] = "'".'No'."'";
            $multiSiteWiseUninstall ['data'][]  = $result->TotalNoMultisite;
            $multiSiteWiseUninstall ['color'][] = "'#17a2b8'";
            $multiSiteWiseUninstall ['level'][] = "'".'Yes'."'";
            $multiSiteWiseUninstall ['data'][]  = $result->TotalYesMultisite;
        }

        return array(
            'versionDownloadData'               => $versionDownloadData,
            'languageWiseUninstall'             => $languageWiseUninstall,
            'multiSiteWiseUninstallData'        => $multiSiteWiseUninstall,
            'reasonWiseUninstall'               => $reasonWiseUninstall,
            'activeInactiveRate'                => $activeInactiveRate,
            'wordpressVersionStatsData'         => $wordpressVersionStatsData,
            'mysqlVersionStatsData'             => $mysqlVersionStatsData,
        );
    }

    function language($code = ''){
        $code           = explode('_', $code);
        $language_code  = $code[0];
        $country_code   = (array_key_exists(1, $code)) ? $code[1] : 0;
        $languageArray  = array
        (
            'aa' => 'Afar',
            'ab' => 'Abkhaz',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan Standard, Tibetan, Central',
            'br' => 'Breton',
            'bs' => 'Bosnian',
            'ca' => 'Catalan; Valencian',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cu' => 'Old Church Slavonic',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'dv' => 'Maldivian;',
            'dz' => 'Dzongkha',
            'ee' => 'Ewe',
            'el' => 'Greek, Modern',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish; Castilian',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'ff' => 'Fula; Fulah; ',
            'fi' => 'Finnish',
            'fj' => 'Fijian',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Western Frisian',
            'ga' => 'Irish',
            'gd' => 'Scottish Gaelic',
            'gl' => 'Galician',
            'gn' => 'GuaranÃƒÂ­',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'he' => 'Hebrew (modern)',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',
            'ia' => 'Interlingua',
            'id' => 'Indonesian',
            'ie' => 'Interlingue',
            'ig' => 'Igbo',
            'ii' => 'Nuosu',
            'ik' => 'Inupiaq',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',
            'ja' => 'Japanese (ja)',
            'jv' => 'Javanese (jv)',
            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Kikuyu, Gikuyu',
            'kj' => 'Kwanyama',
            'kk' => 'Kazakh',
            'kl' => 'Kalaallisut, Greenlandic',
            'km' => 'Khmer',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kirghiz',
            'la' => 'Latin',
            'lb' => 'Luxembourgish',
            'lg' => 'Luganda',
            'li' => 'Limburgish',
            'ln' => 'Lingala',
            'lo' => 'Lao',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian',
            'mg' => 'Malagasy',
            'mh' => 'Marshallese',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'nb' => 'Norwegian',
            'nd' => 'North Ndebele',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch',
            'nn' => 'Norwegian Nynorsk',
            'no' => 'Norwegian',
            'nr' => 'South Ndebele',
            'nv' => 'Navajo, Navaho',
            'ny' => 'Chichewa; Nyanja',
            'oc' => 'Occitan',
            'oj' => 'Ojibwe, Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian, Ossetic',
            'pa' => 'Panjabi, Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pashto, Pushto',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Kirundi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit ',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'si' => 'Sinhala, Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovene',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'st' => 'Southern Sotho',
            'su' => 'Sundanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Tswana',
            'to' => 'Tonga (Tonga Islands)',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',
            'ug' => 'Uighur, Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'VolapÃƒÂ¼k',
            'wa' => 'Walloon',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang, Chuang',
            'zh' => 'Chinese',
            'zu' => 'Zulu',
        );
        $countriesArray = array
        (
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );
        $language_name = (array_key_exists($language_code, $languageArray)) ? $languageArray[$language_code] : '';
        $country_name  = (array_key_exists($country_code, $countriesArray)) ? $countriesArray[$country_code] : '';
        return ($country_name) ? $language_name.', '.$country_name : $language_name;
    }

}
