<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Common function used in application
*/

/**
* get HTMl string for different types of alert messages
* @param $message, string, The message to be shown to users
* @param $type CONSTANT, The type of alert, info, error, warning, success
* @return string html string
*/
function get_alert_html($message='', $type = ALERT_TYPE_INFO)
{
    return "<div class=\"alert alert-$type\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">×</span>
            </button>
            $message
            </div>";
}
/**
* check if any admin is logged in application
* @return void
*/
function admin_session_check()
{
    $CI = get_instance();
    check_remember_me();
    if(!($CI->session->userdata('admin_login') === TRUE)){
        if($CI->input->is_ajax_request()){
            $response =  array('type'=>AJAX_RESPONSE_TYPE_REDIRECT, 'message'=>base_url().'index.php/admin/login');
            echo json_encode($response);
            exit();
        }
        redirect('admin/login');
    }
}
/**
* check if any user or admin is logged in application
* @return void
*/
function session_check()
{
    $CI = get_instance();
    check_remember_me();
    $user_id = $CI->session->userdata('user_id');
    if(empty($user_id)){
        if($CI->input->is_ajax_request()){
            $response =  array('type'=>AJAX_RESPONSE_TYPE_REDIRECT, 'message'=>base_url().'index.php/admin/login');
            echo json_encode($response);
            exit();
        }
        redirect('admin/login', 'refresh');
    }
}
/**
* validate user id before accessing protected info
* @return void
*/
function check_user_id($user_id)
{
    $CI = get_instance();
    check_remember_me();
    if($CI->session->userdata('admin_login') === TRUE)
        return;
    else if ($CI->session->userdata('user_id') == $user_id)
        return;
    else
        die($CI->lang->line('error_invalid_request'));
}

/**
* get facebook api call error in single string
* @param $error_response array, The facebook api response if error occured
* @return string
*/
function get_fb_request_error_string($error_response){
    $message = 'Error Code: '.$error_response['error']['code'].'<br>';
    $message .= 'Type: '.$error_response['error']['type'].'<br>';
    $message .= 'Message: '.$error_response['error']['message'];
    return $message;
}
/**
* display facebook api call error in html table format while adding new facebook app
* @param $response array, The facebook api response if error occured
* @return html table string
*/
function get_fb_response_as_html_table($response){
    $html = '<table>';
    foreach ($response as $key => $value) {
       $html .= '<tr>';
       $html .= '<td>';
       $html .= $key;
       $html .= '</td>';
       $html .= '<td>';
       $html .= $value;
       $html .= '</td>';
       $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

/**
* Save Canvas as png image
* @return string new create PNG file name
*/
function Upload_fb_canvas(){
    $CI = get_instance();
    $fitered_image = substr($CI->input->post('canvas_data'), strpos($CI->input->post('canvas_data'), ",")+1);
    $decoded_image = base64_decode($fitered_image);
    $file_name = uniqid().'.png';
    $fp = fopen(PATH_POST_IMAGES.$file_name, FOPEN_WRITE_CREATE_DESTRUCTIVE);
    fwrite( $fp, $decoded_image);
    fclose( $fp );
    return $file_name;
}


/**
* spin text
* @param $text string, The string to spinned
* @return string , spinned text
*/
function spintax($text = ''){
    if(empty($text)) return '';
    $CI = get_instance();
    $CI->load->library('spintax');
    return $CI->spintax->process($text);
}

/**
* get video download URL from youtube or dailymotion
* @param $url string, The input URL 
* @return string , download url
*/
function get_video_download_url($url = ''){
    $response = get_youtube_url($url);
    if($response['type'] === false && !isset($response['message'])) // not youtube URL
        $response = get_fb_video_url($url);
    return $response;
    
}
/**
* if youtube url get youtube video download URL
* @param $url string, The input URL 
* @return string , download url
*/
function get_youtube_url($url){
    $CI = get_instance();
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
        $result = preg_match($pattern, $url, $matches);
        if (isset($matches[1])) {
            $video_id = $matches[1];
        }else return array('type'=>false); // if not youtube url
        $response = curl_request("http://www.youtube.com/get_video_info?video_id=" . $video_id);
        parse_str($response, $video_data);
        if(isset($video_data['errorcode']))
            return array('type'=>false, 'message'=>$video_data[ 'reason' ]);
        $streams = $video_data[ 'url_encoded_fmt_stream_map' ];             
        $streams = explode( ',', $streams );
        foreach ( $streams as $stream ) {
            parse_str( urldecode( $stream ), $data ); 
            if ( isset($data[ 'type' ]) && isset($data['url']) && isset($data['signature']) ){
                $url   = $data[ 'url' ];
                $signature   = $data[ 'signature' ];
                $video_url = str_replace( '%2C', ',', $url . '&' . http_build_query( $data ) . '&signature=' . $signature );
                return array('type'=>true, 'message'=>$video_url);
                unset( $data );                
                break;
            }
        }
        return array('type'=>false , 'message'=> $CI->lang->line('error_something_wrong'));
}

/**
* if facebook url get facebook video download URL
* @param $url string, The input URL 
* @return string , download url
*/
function get_fb_video_url($url){
    $CI = get_instance();
    $pattern = '%^https?://(www)?\.facebook\.com/(?:video\.php\?v=|.*?/videos/)(\d+)/?$%';
        $result = preg_match($pattern, $url, $matches);
        if (isset($matches[2])) {
            $video_id = $matches[2];
        }else return array('type'=>false); // if not youtube url
        $response = curl_browser_request("https://www.facebook.com/video/embed?video_id=" . $video_id);
        preg_match_all('/src_no_ratelimit":"([^"]+)"/', $response, $regex);
        if(empty($regex[0]))
            return array('type'=>false, 'message'=> '');
        $sd_src = isset($regex[1][0]) ? stripslashes( str_replace ('\u0025', '%', $regex[1][0] ) ) : '';
        $hd_src = isset($regex[1][1]) ? stripslashes( str_replace ('\u0025', '%', $regex[1][1] ) ) : '';
        if($hd_src)
            return array('type' => true, 'message' => $hd_src);
        else if($sd_src)
            return array('type' => true, 'message' => $sd_src);
        return array('type'=>false , 'message'=> $CI->lang->line('error_something_wrong'));
}

/**
* curl request to any url
* @param $url string, The input URL 
* @return string , request's response
*/
function curl_request( $url ){
    $ch      = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 60 );
    $file_contents = curl_exec( $ch );
    curl_close( $ch );
    return $file_contents;
}
/**
* curl request to any url using broser agent
* @param $url string, The input URL 
* @return string , request's response
*/
function curl_browser_request($url){
    $agent= 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_URL,$url);
    $result=curl_exec($ch);
    curl_close( $ch );
    return $result;
}

/**
* send Email 
* @param $data array, Email variables
* @return void 
*/

function send_email($to, $subject, $email_view, $data = '')
{
    $ci =& get_instance();
    if($ci->config->item('emails_enabled') == '0') return true;
    $ci->load->library('email');
    $config['mailtype'] = 'html';
    $config['charset'] = 'utf-8';
    $config['wordwrap'] = TRUE;
    $ci->email->initialize($config);
    $ci->email->from($ci->config->item('admin_email'));
    $ci->email->to($to);
    $ci->email->subject($subject);
    $ci->email->message($ci->load->view('email/'.$email_view, $data, true));
    return $ci->email->send(False);
}

/**
* create a writable folder
* @param $directory_path string, directory to be created
* @return void 
*/

function create_folder($directory_path = '')
{
    if(is_dir($directory_path))
        return true;
    else
      return mkdir($directory_path, 0777, true);
}

/**
* curl request to fetch an image in binary form
* @param $url string, The input URL 
* @return string , request's response
*/
function curl_fetch_image($image_url)
{
    $ch = curl_init ($image_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $image=curl_exec($ch);
    curl_close ($ch);
    return $image;
}
/**
* create session if remember me set
*/
function check_remember_me(){
    include_once(APPPATH.'hooks/AppLoader.php');
    $app_loader = new AppLoader();
    $app_loader->remember_me();
}

function get_facebook_app_alert()
{
    $ci = get_instance();
    $config_app_id = $ci->config->item('default_app_id');
    $config_app_secret = $ci->config->item('default_app_secret');
    $user_app_id = $ci->session->userdata('app_id');
    $user_app_secret = $ci->session->userdata('app_secret');
    if($ci->config->item('use_default_app') && ( empty( $config_app_id ) || empty( $config_app_secret  ) ) )
    {
        $alert = get_alert_html($ci->lang->line('info_no_default_app'));
    }else if(!$ci->config->item('use_default_app') && ( empty( $user_app_id ) || empty( $user_app_secret ) ) )
    {
        $alert = get_alert_html($ci->lang->line('info_no_user_app'));
    }else
    {
        $alert = '';
    }
    return $alert;

}

function get_app_id($app_type)
{
    $ci = get_instance();
    if($app_type == TOKEN_USER_APP)
        return $ci->session->userdata('app_id');
    else if($app_type == TOKEN_DEFAULT_APP)
        return $ci->config->item('default_app_id');
    else 
        return '';
}

function get_app_secret($app_type)
{
    $ci = get_instance();
    if($app_type == TOKEN_USER_APP)
        return $ci->session->userdata('app_secret');
    else if($app_type == TOKEN_DEFAULT_APP)
        return $ci->config->item('default_app_secret');
    else 
        return '';
}

function set_page_in_session($page)
{
    $ci = get_instance();
    $ci->session->set_userdata('page_id', $page->page_id);
    $ci->session->set_userdata('page_fb_id', $page->page_fb_id);
    $ci->session->set_userdata('page_name', $page->page_name);
    $ci->session->set_userdata('page_category', $page->page_category);
    $ci->session->set_userdata('page_likes', $page->page_likes);
    $ci->session->set_userdata('page_token', $page->page_token);
    $ci->session->set_userdata('page_profile_picture', $page->profile_picture);
    $ci->session->set_userdata('page_permissions', explode(',', $page->permissions) );
    $ci->session->set_userdata('page_profile', $page->profile_id);
    $ci->session->set_userdata('page_date_added', $page->date_added);
    $ci->load->model('profiles_model');
    $profile = $ci->profiles_model->get_profile($page->profile_id);
    $ci->session->set_userdata('page_app_id', get_app_id($profile->profile_app));
    $ci->session->set_userdata('page_app_secret', get_app_secret($profile->profile_app));
}

function unset_page_in_session()
{
    $ci = get_instance();
    foreach ($_SESSION as $key => $value) {
        if($key == 'page_limit')
            continue;
        else if(strpos($key, 'page_') === 0)
            $ci->session->unset_userdata($key);
    }
}

function validate_page($page_id = 0)
{
    $ci = get_instance();
    if(!$page_id) show_404();
    if(!is_numeric($page_id)) show_404();
    $ci->load->model('pages_model');
    $page = $ci->pages_model->get_record($page_id);
    if(!isset($page->page_id)) show_404();
    $user_id = $ci->session->userdata('admin_login') === TRUE ? 0 : $ci->session->userdata('user_id');
    if($user_id && !$ci->pages_model->is_user_allowed($user_id, $page_id))
    {
        $ci->session->set_flashdata('alert', get_alert_html($ci->lang->line('invalid_request'), ALERT_TYPE_ERROR));
        redirect('home/index');
    }
    return $page;
}
/*
* check if a Facebook user can post as page
*/
function can_user_post($permissions)
{
    $permissions = explode(',', $permissions);
    if(in_array('CREATE_CONTENT', $permissions))
        return true;
    return false;
}