<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Users Class
 * 
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Users
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
    }
    /** default function of the controller */
    public function index()
    {
        redirect('users/list_users');
    }
    /**
    * List of users
    */
    public function list_users()
    {
        admin_session_check();
        $data['view'] = 'list_users';
        $data['page_title'] = $this->lang->line('user_list_title');
        $this->load->view('template', $data);
    }
    /**
    * make a user active
    * @param $user_id integer, id of the user
    */
    public function enable($user_id = 0 )
    {
        header('Content-Type: application/json'); //set response type to be json
        admin_session_check();
        if(!$user_id) show_404();
        $user = $this->users_model->get_record($user_id);
        if(!isset($user->user_id))
        {
            $ajax_response = array('type' => AJAX_RESPONSE_TYPE_ERROR, 'message' => get_alert_html($this->lang->line('error_invalid_request'), ALERT_TYPE_ERROR));
            echo json_encode($ajax_response);
            exit();
        }
        $this->users_model->update_user_status($user_id, USER_STATUS_ACTIVE);
        $ajax_response = array('type' => AJAX_RESPONSE_TYPE_SUCCESS, 'message' => get_alert_html($this->lang->line('success_user_enabled') , ALERT_TYPE_SUCCESS));
        echo json_encode($ajax_response);
    }
    /**
    * make a user inactive, inactive users cannot login into system
    * @param $user_id integer, id of the user
    */
    public function disable($user_id = 0)
    {
        header('Content-Type: application/json'); //set response type to be json
        admin_session_check();
        if(!$user_id) show_404();
        $user = $this->users_model->get_record($user_id);
        if(!isset($user->user_id))
        {
            $ajax_response = array('type' => AJAX_RESPONSE_TYPE_ERROR, 'message' => get_alert_html($this->lang->line('error_invalid_request'), ALERT_TYPE_ERROR));
            echo json_encode($ajax_response);
            exit();
        }
        if($user->user_id == $this->session->userdata('user_id'))
        {
            $ajax_response = array('type' => AJAX_RESPONSE_TYPE_ERROR, 'message' => get_alert_html($this->lang->line('error_disable_self'), ALERT_TYPE_ERROR));
            echo json_encode($ajax_response);
            exit();
        }
        $this->users_model->update_user_status($user_id, USER_STATUS_INACTIVE);
        $ajax_response = array('type' => AJAX_RESPONSE_TYPE_SUCCESS, 'message' => get_alert_html($this->lang->line('success_user_disabled'), ALERT_TYPE_SUCCESS));
        echo json_encode($ajax_response);
    }
    /**
    * delete a user
    * @param $user_id integer, id of the user
    */
    public function delete($user_id = 0)
    {
        admin_session_check();
        if(!$user_id) show_404();
        $user = $this->users_model->get_record($user_id);
        if(!isset($user->user_id))
            show_404();
        if($user->user_role == USER_TYPE_USER)
        {
            $this->users_model->delete($user_id);
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_user_deleted'), ALERT_TYPE_SUCCESS));
        }else
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_admin_delete'), ALERT_TYPE_ERROR));
        }
        redirect('users/list_users');
    }
    /**
    * Add a new user in database
    */
    public function add()
    {
        admin_session_check();
        $this->form_validation->set_rules('username', $this->lang->line('validation_username'), 'trim|required|callback_username_check');
        $this->form_validation->set_rules('email', $this->lang->line('validation_email_address'), 'trim|required|valid_email|callback_email_check');
        $this->form_validation->set_rules('usertype', $this->lang->line('validation_usertype'), 'trim|required');
        $this->form_validation->set_rules('password', $this->lang->line('validation_password'), 'trim|required|callback_password_check');
        $this->form_validation->set_rules('confirm_password', $this->lang->line('validation_confirm_password'), 'trim|required');
        $this->form_validation->set_rules('page_limit', $this->lang->line('validation_page_limit'), 'trim|required|numeric');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        $this->form_validation->set_message('valid_email', $this->lang->line('validation_invalid_email'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('add_user_title');
            $data['view'] = 'add_user';
            $this->load->view('template', $data);
        }else
        {
            $this->users_model->insert();
            $user_data = array(
                'email' => $this->input->post('email', TRUE),
                'username' => $this->input->post('username', TRUE),
                'password' => $this->input->post('password', TRUE)
            );
            send_email($user_data['email'], $this->lang->line('email_signup_welcome').' '.$this->config->item('site_name'), 'signup', $user_data );
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_user_created'), ALERT_TYPE_SUCCESS));
            redirect('users/list_users');
        }
    }
    /**
    * edit a user
    * @param $user_id integer, id of the user
    */
    public function edit($user_id = 0)
    {
        admin_session_check();
        if(!$user_id) show_404();
        $data['user'] = $this->users_model->get_record($user_id);
        if(!isset($data['user']->user_id)) show_404();
        $data['user_setting'] = $this->users_model->get_settings($user_id);
        if($this->session->userdata('user_id') == $user_id) redirect('users/settings');
        $this->form_validation->set_rules('username', $this->lang->line('validation_username'), 'trim|required|callback_username_check');
        $this->form_validation->set_rules('email', $this->lang->line('validation_email_address'), 'trim|required|valid_email|callback_email_check');
        $this->form_validation->set_rules('usertype', $this->lang->line('validation_usertype'), 'trim|required');
        $this->form_validation->set_rules('password', $this->lang->line('validation_password'), 'trim|callback_password_check');
        $this->form_validation->set_rules('confirm_password', $this->lang->line('validation_confirm_password'), 'trim');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        $this->form_validation->set_message('valid_email', $this->lang->line('validation_invalid_email'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('edit_user_heading');
            $data['view'] = 'edit_user';
            $this->load->view('template', $data);
        }else
        {
            $this->users_model->update($user_id);
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_user_updated'), ALERT_TYPE_SUCCESS));
            redirect('users/list_users');
        }
    }
    /**
    * AJAX call for bootstrap to check if username already exist
    * @param $user_id integer, id of the user
    */
    public function isusernameexist($user_id=0)
    {
        //session_check();
        $user_name = $this->input->get('username', TRUE);
        if($this->users_model->isusernameexist($user_name, $user_id))
            echo 'false';
        else
            echo 'true'; 
    }
    /**
    * custom function for form_validation library to check if username already exsist in database
    * @param $user_name string, username of the user to be checked
    */
    public function username_check($user_name)
    {
        session_check();
        $user_id = $this->uri->segment(3);
        $user_id = empty($user_id) ? $this->session->userdata('user_id') : $user_id;
        if($this->uri->segment(2) == 'add' || $this->uri->segment(2) == 'Add')
            $user_id = 0;
        if($this->users_model->isusernameexist($user_name, $user_id))
        {
            $this->form_validation->set_message('username_check', $this->lang->line('validation_username_exist'));
            return FALSE;
        }else
        {
            return TRUE;
        }
    }
    /**
    * AJAX call for bootstrap to check if email address already exist
    * @param $user_id integer, id of the user
    */
    public function isemailexist($user_id=0)
    {
        //session_check();
        $email = $this->input->get('email', TRUE);
        if($this->users_model->isemailexist($email, $user_id))
            echo 'false';
        else
            echo 'true'; 
    }
    /**
    * custom function for form_validation library to check if email address already exsist in database
    * @param $email string, email address of the user
    */
    public function email_check($email)
    {
        session_check();
        $user_id = $this->uri->segment(3);
        $user_id = empty($user_id) ? $this->session->userdata('user_id') : $user_id;
        if($this->uri->segment(2) == 'add' || $this->uri->segment(2) == 'Add')
            $user_id = 0;
        if($this->users_model->isemailexist($email, $user_id))
        {
            $this->form_validation->set_message('email_check', $this->lang->line('validation_email_exist'));
            return FALSE;
        }
        return TRUE; 
    }
    /**
    * match password and confirm password fields
    * custom function for form_validation library
    * @param $password string, password of the user
    */
    public function password_check($password)
    {
        session_check();
        $confirm_password = $this->input->post('confirm_password', TRUE);
        if($password != $confirm_password)
        {
            $this->form_validation->set_message('password_check', $this->lang->line('validation_password_not_same'));
            return FALSE;
        }
        return TRUE;
    }
    /**
    * Edit user specific setting
    */
    public function settings()
    {
        session_check();
        $user_id = $this->session->userdata('user_id');
        $this->form_validation->set_rules('username', 'User Name', 'trim|callback_username_check');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_email_check');
        $this->form_validation->set_rules('current_password', 'Password', 'trim|callback_validate_password');
        $this->form_validation->set_rules('password', 'Password', 'trim|callback_password_check');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim');
        $this->form_validation->set_rules('canvas_width', 'Canvas width', 'trim|numeric');
        $this->form_validation->set_rules('canvas_height', 'Canvas height', 'trim|numeric');
        $this->form_validation->set_rules('time_zone', 'Time zone', 'trim|required');
        $this->form_validation->set_message('required','%s is required');
        $this->form_validation->set_message('valid_email','invalid email address');
        $this->form_validation->set_message('numeric','%s should be numeric');
        if(!$this->form_validation->run())
        {
            $data['user'] = $this->users_model->get_record($user_id);
            $data['user_setting'] = $this->users_model->get_settings($user_id);
            $data['time_zones'] = timezone_identifiers_list();
            $data['languages'] = scandir(APPPATH.'language');
            $data['page_title'] = $this->lang->line('user_settings_title');
            $data['view'] = 'user_settings';
            $this->load->view('template', $data);
        }else
        {
            $app_id = $this->input->post('app_id', TRUE);
            $app_secret = $this->input->post('app_secret', TRUE);
            if( !empty($app_id) && !empty($app_secret) )
            {
                $this->load->library('facebook/myfacebook');
                $this->myfacebook->init($app_id, $app_secret);
                $response = $this->myfacebook->get_app_details();
                if(isset($response['error']))
                {
                    $this->session->set_flashdata('alert', get_alert_html(get_fb_request_error_string($response), ALERT_TYPE_ERROR));
                    redirect('users/settings');
                }
            }
            $this->users_model->update_user_setting($user_id);
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_user_setting_saved'), ALERT_TYPE_SUCCESS));
            redirect('users/settings');
        }
    }
    /**
    * validate the current password of the user
    * $password string, current password of the users
    */
    public function validate_password($password)
    {
        session_check();
        $new_password =  $this->input->post('password', TRUE);
        if(empty($new_password))
            return TRUE;
        if(empty($password) && empty($new_password))
            return TRUE;
        $user_id =  $this->session->userdata('user_id');
        if($this->users_model->validate_password($password, $user_id))
            return TRUE;
        else
        {
            $this->form_validation->set_message('validate_password', $this->lang->line('validattion_invalid_current_password'));
            return FALSE;
        }
    }

    /**
    * user profile
    **/
    public function profile($user_id = 0)
    {
        admin_session_check();
        if($this->session->userdata('user_id')  ==  $user_id)
            redirect('home/index');
        if(!$user_id) show_404();
        $data['user'] = $this->users_model->get_record($user_id);
        if(!isset($data['user']->user_id)) show_404();
        $this->load->model('profiles_model');
        $data['profiles'] = $this->profiles_model->get_list(GET_RECORDS, $user_id);
        $data['page_title'] = $data['user']->user_name.' - '.$this->lang->line('profile_title');
        $data['view'] = 'profile/main';
        $this->load->view('template', $data);
    }

}

/* End of file users.php */
/* Location: ./application/controllers/users.php */