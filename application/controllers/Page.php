<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Page Class
 * 
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Page
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Page extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        session_check();
        $this->load->model('pages_model');
    }

    /**
    * Get List of Posts of a node, click on view button in node list page
    * @param $node_id integer, ID of the node primary key
    * @param $offset integer, Pagination paramter
    */
    public function index($page_id = 0, $offset=0)
    {
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
        
        $data['page'] = $page;
        $data['page_title'] = $data['page']->page_name.' - '.$this->lang->line('node_post_list_title');
        $data['view'] = 'node_post_list';
        $this->load->view('template', $data);
    }

    /**
    * pages list 
    */
    public function list_all()
    {
        $data['page_title'] = $this->lang->line('page_list_title');
        $data['view'] = 'page_list';    
        $this->load->view('template', $data);
    }
    /**
    * delete a page
    * @param $page_id integer, ID of the page primary key
    */
    public function delete($page_id = 0)
    {
        $page = validate_page($page_id);
        $this->load->model('profiles_model');
        $profile = $this->profiles_model->get_profile($page->profile_id);
        $this->pages_model->delete($page_id);
        $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_page_deleted'), ALERT_TYPE_SUCCESS));
        /*if($this->session->userdata('page_id') == $page_id)
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
        if($profile->user_id != $this->session->userdata('user_id'))
                redirect('users/profile/'.$profile->user_id);
        redirect('page/list_all');
    }

    /**
    * Manage a page
    * @param $page_id integer, ID of the page primary key
    */
    public function manage($page_id = 0)
    {
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
        set_page_in_session($page);
        redirect('home/index');
    }

    /**
    * Insights page
    */
    public function insights($page_id = 0)
    {
        if($this->session->userdata('user_login') === TRUE && !$this->session->userdata('insights_allowed'))                
            redirect('home/index');
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
        $data['page'] = $page;
        $this->load->model('profiles_model');
        $profile = $this->profiles_model->get_profile($page->profile_id);
        $data['page_app_id'] = get_app_id($profile->profile_app);
        $data['page_title'] = $this->lang->line('insights_title').' - '.$page->page_name;
        $data['view'] = 'insights/insights_main';    
        $this->load->view('template', $data);
    }    
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */