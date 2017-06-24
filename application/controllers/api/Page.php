<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Page extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        session_check();
        $this->load->model("pages_model");
    }

    function all_get($user_id = 0, $post_page = false)
    {
        if(!$user_id)
        {
            $this->response([], 200);
            exit();
        }
        check_user_id($user_id);
        $limit = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('page_limit');
        $records = $this->pages_model->get_list($user_id, $limit);
        foreach ($records as $key => $record) 
        {
            if($post_page && !can_user_post($record->permissions))
                unset($records[$key]);
            else
                unset($record->permissions);
        }
        $records = array_values($records);   
        $this->response($records,200);
    }

    function delete_post()
    {
        $pages = $this->post('pages');
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $count = 0;
        $is_session_page = false;
        foreach ($pages as $page) {
            if($user_id && !$this->pages_model->is_user_allowed($user_id, $page['page_id']))
            {
                $response = array('type' => AJAX_RESPONSE_TYPE_ERROR, 'message' => get_alert_html($this->lang->line('invalid_request'), ALERT_TYPE_ERROR));
                break;
            }
            $this->pages_model->delete($page['page_id']);
            if($this->session->userdata('page_id') == $page['page_id'])
                $is_session_page = true;
            $count++;
        }
        /*if($is_session_page)
        {
            $limit = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('page_limit');
            $records = $this->pages_model->get_list($this->session->userdata('user_id'), $limit);
            if(is_array($records) && !empty($records))
            {
                $page = $this->pages_model->get_record($records[0]->page_id);
                set_page_in_session($page);
            }
            else
                unset_page_in_session();
        }*/
        $this->session->set_flashdata('alert', get_alert_html(sprintf($this->lang->line('success_pages_deleted'), $count), ALERT_TYPE_SUCCESS));
        $response = array('type' => AJAX_RESPONSE_TYPE_REDIRECT, 'message' => base_url().'index.php/page/list_all' );
        $this->response($response,200);
    }

    function token_get($page_id = 0)
    {
        $page = validate_page($page_id);
        $response['token'] = $page->page_token;
        $this->response($response,200);
    }
}
?>