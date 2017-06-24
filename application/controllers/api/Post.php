<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Post extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model("post_model");
    }
    //get campaign list
    function all_get($user_id = 0, $offset = 0)
    {
        session_check();
        check_user_id($user_id);
    	$records = $this->post_model->get_list($user_id, GET_RECORDS, $offset);
        $user_zone = new DateTimeZone($this->session->userdata('time_zone'));
        foreach ($records as $record) {
            $start_time = new DateTime($record->date_started);
            $start_time->setTimezone($user_zone);
            $record->date_started = $start_time->format('d M, Y  h:i A');
        }
		$this->response($records,200);
    }
    //get campaign posts list
    function node_list_get($campaign_id = 0, $offset = 0)
    {
        session_check();
        $campaign = $this->post_model->get_campaign($campaign_id);
        check_user_id($campaign->user_id);
        $records = $this->post_model->get_campaign_posts($campaign_id, GET_RECORDS, $offset);
        $user_zone = new DateTimeZone($this->session->userdata('time_zone'));
        foreach ($records as $record) {
            $post_datetime = new DateTime($record->post_datetime);
            $post_datetime->setTimezone($user_zone);
            $record->post_datetime = $post_datetime->format('d M, Y  h:i A');
        }
        $this->response($records,200);
    }

    //get node posts list
    function post_list_get($page_id = 0, $offset = 0)
    {
        session_check();
        $page = validate_page($page_id);
        // check if page exist under user page limit
        $limit = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('page_limit');
        $records = $this->pages_model->get_list($this->session->userdata('user_id'), $limit);
        $found = false;
        foreach ($records as $record) 
        {
            if($record->page_id == $page_id)
            {
                $found = true;
                break;
            }
        }
        if( !$found )
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('invalid_request'), ALERT_TYPE_ERROR));
            redirect('home/index');
        }
        $records = $this->post_model->get_node_post_list($page_id, $offset);
        $user_zone = new DateTimeZone($this->session->userdata('time_zone'));
        foreach ($records as $record) {
            $post_datetime = new DateTime($record->post_datetime);
            $post_datetime->setTimezone($user_zone);
            $record->post_datetime = $post_datetime->format('d M, Y  h:i A');
        }
        $this->response($records,200);
    }

    //get single post
    function item_get($post_id = 0)
    {
        session_check();
        $campaign = $this->post_model->get_campaign($post_id);
        check_user_id($campaign->user_id);
        $this->response($campaign, 200);
    }

    // get campaign nodes list for edit page
    function campaign_nodes_get($campaign_id = 0)
    {
        session_check();
        $campaign = $this->post_model->get_campaign($campaign_id);
        check_user_id($campaign->user_id);
        $records = $this->post_model->get_campaign_nodes($campaign_id);
        $user_zone = new DateTimeZone($this->session->userdata('time_zone'));
        foreach ($records as $record) {
            $post_datetime = new DateTime($record->post_datetime);
            $post_datetime->setTimezone($user_zone);
            $record->post_datetime = $post_datetime->format('Y-m-d  h:i A');
        }
        $response = array('records'=>$records);
        $this->response($response,200);
    }
    

}
?>