<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Profiles extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        session_check();
        $this->load->model("profiles_model");
    }

    function debug_get($profile_id = 0)
    {
        if(!$profile_id) show_404();
        $profile = $this->profiles_model->get_profile($profile_id);
        if(!isset($profile->profile_id) || $profile->user_id != $this->session->userdata('user_id'))
        {
            die($this->lang->line('error_invalid_request'));
        }
        $this->load->library('facebook/myfacebook');
        $app_id = get_app_id($profile->profile_app);
        $app_secret = get_app_secret($profile->profile_app);
        $this->myfacebook->init($app_id, $app_secret);
        $this->myfacebook->set_token($app_id.'|'.$app_secret);
        $response = $this->myfacebook->debug_token($profile->profile_token);
        if(isset($response['error']))
        {
            $response = array(
                'type' => AJAX_RESPONSE_TYPE_ERROR,
                'message' => get_alert_html(get_fb_request_error_string($response), ALERT_TYPE_ERROR)
            );
        }
		$this->response($response,200);
    }

    function pages_get($profile_id = 0)
    {
        if(!$profile_id) show_404();
        $profile = $this->profiles_model->get_profile($profile_id);
        if(!isset($profile->profile_id) || $profile->user_id != $this->session->userdata('user_id'))
        {
            die($this->lang->line('error_invalid_request'));
        }

        $before = $this->get('before', TRUE);
        $after = $this->get('after', TRUE);
        $app_id = get_app_id($profile->profile_app);
        $app_secret = get_app_secret($profile->profile_app);

        $this->load->library('facebook/myfacebook');
        $this->myfacebook->init($app_id, $app_secret, 'v2.6');
        $this->myfacebook->set_token($profile->profile_token);
        
        $fb_response = $this->myfacebook->get_manage_pages($before, $after);
        $nodes = is_object($fb_response) ? $fb_response->asArray() : $fb_response;
        $message = $this->lang->line('error_no_manage_pages_found').'<br>'.
                   $this->lang->line('error_perms')."'manage_pages'".'<br>'.
                   $this->lang->line('error_app_user');        
        if(isset($nodes['error']))
        {
            $response = array(
                'type' => AJAX_RESPONSE_TYPE_ERROR,
                'message' => get_alert_html(get_fb_request_error_string($nodes), ALERT_TYPE_ERROR)
            );
        }else if(empty($nodes))
        {
            $response = array(
                'type' => AJAX_RESPONSE_TYPE_ERROR,
                'message' => get_alert_html($message, ALERT_TYPE_ERROR)
            );
        }else
        {
            $length = count($nodes);
            for($key = 0; $key < $length; $key++) 
                $nodes[$key]['selected'] = false;
            $response = array('nodes' => array_values($nodes));
            $metaData = $fb_response->getMetaData();
            if(isset($metaData['paging']['next'])) // next page url
                $response['next'] = $fb_response->getNextCursor();
            if(isset($metaData['paging']['previous'])) // previous page url
                $response['previous'] = $fb_response->getPreviousCursor();
        }
        $this->response($response,200);
    }

    function save_pages_post($profile_id = 0)
    {
        if(!$profile_id) show_404();
        $profile = $this->profiles_model->get_profile($profile_id);
        if(!isset($profile->profile_id) || $profile->user_id != $this->session->userdata('user_id'))
        {
            die($this->lang->line('error_invalid_request'));
        }

        $this->load->model('pages_model');
        $data = $this->post('data');
        $inserted = 0;
        $updated = 0;
        $response = array();
        if($this->session->userdata('user_login') === TRUE)
        {
            $this->load->model('pages_model');
            $saved_pages = $this->pages_model->get_user_page_count($this->session->userdata('user_id'));
            $remain_pages = (int) $this->session->userdata('page_limit') - (int) $saved_pages ; 
            $remain_pages = $remain_pages < 0 ? 0 : $remain_pages;
        }
        foreach ($data as $node) {
           if($node['selected']){
                if( ( $this->pages_model->is_page_exist($node['id'], $profile_id) ) )
                {
                    $this->pages_model->update_page($node, $profile_id);
                    $updated ++;
                }else if( $this->session->userdata('admin_login') === TRUE || $remain_pages > 0 )
                {
                    $this->pages_model->insert_page($node, $profile_id);
                    $inserted ++;
                    if(isset($remain_pages))
                        $remain_pages--;
                }   
           }
        }
        $response['type'] = AJAX_RESPONSE_TYPE_SUCCESS;
        $response['message'] = get_alert_html(sprintf($this->lang->line('success_pages_saved'), $inserted, $updated), ALERT_TYPE_SUCCESS);
        $this->response($response,200);
    }

    function all_get($user_id = 0)
    {
        check_user_id($user_id);
        $records = $this->profiles_model->get_list(GET_RECORDS, $user_id);
        $this->response($records,200);
    }

}
?>