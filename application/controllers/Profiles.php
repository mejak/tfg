<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profiles Class
 * 
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Profiles
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Profiles extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        session_check();
        $this->load->model('profiles_model');
    }

    /** default function of the controller */
    public function index()
    {
        redirect('profiles/list_all');
    }
    /**
    * Facebook accounts list page
    */
    public function list_all()
    {
        $data['page_title'] = $this->lang->line('fb_accounts_title');
        $data['alert'] = get_facebook_app_alert();
        if(empty($data['alert']))
        {
            $this->load->library('facebook/myfacebook');
            if($this->config->item('use_default_app') )
                $this->myfacebook->init( $this->config->item('default_app_id'), $this->config->item('default_app_secret') );
            else
                $this->myfacebook->init( $this->session->userdata('app_id'), $this->session->userdata('app_secret') );
            $redirect_url = base_url().'index.php/profiles/save_token';
            $data['login_url'] = $this->myfacebook->get_login_url($redirect_url);
        }
        $data['view'] = 'list_accounts';
        $this->load->view('template', $data);
    }
    /**
    * create or update user profile with token
    */
    public function save_token()
    {
        $this->load->library('facebook/myfacebook');
        if($this->config->item('use_default_app') )
            $this->myfacebook->init( $this->config->item('default_app_id'), $this->config->item('default_app_secret') );
        else
            $this->myfacebook->init( $this->session->userdata('app_id'), $this->session->userdata('app_secret') );
        $token = $this->myfacebook->get_access_token();
        if($token !== true || $this->input->get('error') || $this->input->get('error_code'))
        {
            $message = $token ? $token : get_fb_response_as_html_table($_GET);  
            $this->session->set_flashdata('alert', get_alert_html($message, ALERT_TYPE_ERROR));
            redirect('profiles/list_all');            
        }
        $profile = $this->myfacebook->get_user_profile();
        if(!is_object($profile))
        {
            $this->session->set_flashdata('alert', get_alert_html($profile, ALERT_TYPE_ERROR));
            redirect('profiles/list_all');            
        }
        $access_token =  $this->myfacebook->get_token();
        $profile_id = $this->profiles_model->insert_update_fb_user($profile, $this->session->userdata('user_id'), $access_token, $this->config->item('use_default_app'));
        redirect('profiles/import_pages/'.$profile_id);
    }

    /**
    * Deltes an profile and its pages data from database, 
    */
    public function delete($profile_id = 0)
    {
        if(!($this->profiles_model->is_user_allowed($profile_id, $this->session->userdata('user_id'))))
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_invalid_request'), ALERT_TYPE_ERROR)); 
        }else
        {
            $profile = $this->profiles_model->get_profile($profile_id);
            $this->profiles_model->delete($profile_id);
            //reset page in session if session page deleted
            /*$this->load->model('pages_model');
            $limit = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('page_limit');
            $records = $this->pages_model->get_list($this->session->userdata('user_id'), $limit);
            $found = false;
            $page_id = $this->session->userdata('page_id');
            foreach ($records as $record) 
            {
                if($record->page_id == $page_id)
                {
                    $found = true;
                    break;
                }
            }
            if(!$found) //sesssion page deleted with profile
            {
                reset($records);
                if(is_array($records) && !empty($records))
                {
                    $page = $this->pages_model->get_record($records[0]->page_id);
                    set_page_in_session($page);
                }
                else
                    unset_page_in_session();
            }*/
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_account_deleted'), ALERT_TYPE_SUCCESS)); 
            if($profile->user_id != $this->session->userdata('user_id'))
                redirect('users/profile/'.$profile->user_id);
        }
        redirect('profiles/list_all');            
    }
    /**
    * AJAX calls from popup here to get data(nodes) from facebook 
    */
    public function import_pages($profile_id)
    {
        if(!$profile_id) show_404();
        $profile = $this->profiles_model->get_profile($profile_id);
        if(!isset($profile->profile_id) || $profile->user_id != $this->session->userdata('user_id'))
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_invalid_request'), ALERT_TYPE_ERROR));
            redirect('profiles/list_all');
        }
        if($this->session->userdata('user_login') === TRUE)
        {
            $this->load->model('pages_model');
            $saved_pages = $this->pages_model->get_user_page_count($this->session->userdata('user_id'));
            $remain_pages = (int) $this->session->userdata('page_limit') - (int) $saved_pages ; 
            $remain_pages = $remain_pages < 0 ? 0 : $remain_pages;
            $data['remaining_pages'] = get_alert_html( sprintf ( $this->lang->line('info_can_save_pages'), $remain_pages ) );
        }
        $data['page_title'] = $this->lang->line('import_pages_title');
        $data['view'] = 'import_pages';
        $data['profile'] = $profile;
        $this->load->view('template', $data);        
    }

}

/* End of file Profiles.php */
/* Location: ./application/controllers/Profiles.php */

?>
