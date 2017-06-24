<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin Class
 *
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Admin
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Admin extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }


    /** default function of the controller */
    public function index()
    {
        $this->login();
    }


    /**
    * Login page for application
    */
    public function login()
    {
        if($this->session->userdata('admin_login') === TRUE || $this->session->userdata('user_login') === TRUE )
            redirect('home/index');
        $this->form_validation->set_rules('username', $this->lang->line('validation_username_email'), 'trim|required');
        $this->form_validation->set_rules('password', $this->lang->line('validation_password'), 'trim|required');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('login_title');
            if($this->config->item('default_app_id') && $this->config->item('default_app_secret'))
            {
                $this->load->library('facebook/myfacebook');
                $this->myfacebook->init($this->config->item('default_app_id'), $this->config->item('default_app_secret'));
                $data['fb_login_url'] = $this->myfacebook->get_login_url(base_url().'index.php/admin/fblogin');
            }
            $this->load->view('login', $data);
        }else
        {
            $this->load->model('users_model');
            $user = $this->users_model->validate_user();
            if(isset($user->user_id) && $user->user_status == USER_STATUS_ACTIVE)
            {
                if(($this->input->post('remember', TRUE) == 1))
                {
                    $this->load->library('encryption');
                    set_cookie('remember', $this->encryption->encrypt($user->user_email), 15 * 24 * 60 * 60);
                }
                if($user->user_role == USER_TYPE_ADMIN)
                    $this->session->set_userdata('admin_login', TRUE);
                else
                    $this->session->set_userdata('user_login', TRUE);
                $this->session->set_userdata('user_id', $user->user_id);
                $this->session->set_userdata('user_name', $user->user_name);
                $this->session->set_userdata('user_email', $user->user_email);
                $setting = $this->users_model->get_settings($user->user_id);
                foreach ($setting as $key => $value) 
                {
                    $this->session->set_userdata($key, $value);
                }
                $this->load->model('pages_model');
                $limit = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('page_limit');
                $records = $this->pages_model->get_list($user->user_id, $limit);
                if(is_array($records) && !empty($records))
                    redirect('page/manage/'.$records[0]->page_id);
                else
                    redirect('home/index');
            }else if(isset($user->user_id) && $user->user_status == USER_STATUS_INACTIVE)
            {
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_login_disabled'), ALERT_TYPE_ERROR));
                redirect('admin/login', 'refresh');
            }else
            {
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_login_error'), ALERT_TYPE_ERROR));
                redirect('admin/login', 'refresh');
            }
        }
    }
    /**
    * Login with Facebook
    */
    public function fblogin()
    {
        if(!isset($_GET['code']))
            redirect('admin/login');
        $this->load->library('facebook/myfacebook');
        $this->myfacebook->init($this->config->item('default_app_id'), $this->config->item('default_app_secret'));
        $token = $this->myfacebook->get_access_token();
        if($token !== true)
        {
            $this->session->set_flashdata('alert', get_alert_html($token, ALERT_TYPE_ERROR));
            redirect('admin/login', 'refresh');
        }
        $profile = $this->myfacebook->get_user_profile();
        if(!is_object($profile))
        {
            $this->session->set_flashdata('alert', get_alert_html($profile, ALERT_TYPE_ERROR));
            redirect('admin/login', 'refresh');
        }
        $email = $profile->getField('email');
        $picture = $profile->getPicture()->getUrl();
        $this->load->model('users_model');
        $user = $this->users_model->get_user($email);
        if(isset($user->user_id) && $user->user_status == USER_STATUS_ACTIVE)
        {
            if($user->user_role == USER_TYPE_ADMIN)
                $this->session->set_userdata('admin_login', TRUE);
            else
                $this->session->set_userdata('user_login', TRUE);
            $this->session->set_userdata('user_id', $user->user_id);
            $this->session->set_userdata('user_name', $user->user_name);
            $this->session->set_userdata('user_email', $user->user_email);
            $this->session->set_userdata('profile_image', $picture);
            $setting = $this->users_model->get_settings($user->user_id);
            foreach ($setting as $key => $value) 
            {
                $this->session->set_userdata($key, $value);
            }
            $this->load->model('pages_model');
            $limit = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('page_limit');
            $records = $this->pages_model->get_list($user->user_id, $limit);
            if(is_array($records) && !empty($records))
                redirect('page/manage/'.$records[0]->page_id);
            else
                redirect('home/index');
        }else if(isset($user->user_id) && $user->user_status == USER_STATUS_INACTIVE)
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_login_disabled'), ALERT_TYPE_ERROR));
            redirect('admin/login', 'refresh');
        }else
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_email_not_exist'), ALERT_TYPE_ERROR));
            redirect('admin/login', 'refresh');
        }
    }
    /**
    * Signup page for application
    */
    public function signup()
    {
        if($this->session->userdata('admin_login') === TRUE || $this->session->userdata('user_login') === TRUE )
            redirect('home/index');
        if(!($this->config->item('signup')))
            redirect('admin/login');
        $this->form_validation->set_rules('username', $this->lang->line('validation_username'), 'trim|required|callback_username_check');
        $this->form_validation->set_rules('email', $this->lang->line('validation_email_address'), 'trim|required|valid_email|callback_email_check');
        $this->form_validation->set_rules('password', $this->lang->line('validation_password'), 'trim|required|callback_password_check');
        $this->form_validation->set_rules('confirm_password', $this->lang->line('validation_confirm_password'), 'trim|required');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        $this->form_validation->set_message('valid_email', $this->lang->line('validation_invalid_email'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('signup_title');
            if($this->config->item('default_app_id') && $this->config->item('default_app_secret'))
            {
                $this->load->library('facebook/myfacebook');
                $this->myfacebook->init($this->config->item('default_app_id'), $this->config->item('default_app_secret'));
                $data['fb_login_url'] = $this->myfacebook->get_login_url(base_url().'index.php/admin/fbsignup');
            }
            $this->load->view('signup', $data);
        }else
        {
            $this->load->model('users_model');
            $user_id = $this->users_model->signup();
            if($user_id)
            {
                $this->session->set_userdata('user_login', TRUE);
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('user_name', $this->input->post('username', TRUE));
                $this->session->set_userdata('user_email', $this->input->post('email', TRUE));
                $setting = $this->users_model->get_settings($user_id);
                foreach ($setting as $key => $value) 
                {
                    $this->session->set_userdata($key, $value);
                }
                $user_data = array(
                    'email' => $this->input->post('email', TRUE),
                    'username' => $this->input->post('username', TRUE),
                    'password' => $this->input->post('password', TRUE)
                );
                send_email($user_data['email'], $this->lang->line('email_signup_welcome').' '.$this->config->item('site_name'), 'signup', $user_data );
                redirect('home/index');
            }else
            {
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_something_wrong'), ALERT_TYPE_ERROR));
                redirect('admin/signup');
            }
        }
    }
    /**
    * signup with Facebook
    */
    public function fbsignup()
    {
        if(!isset($_GET['code']))
            redirect('admin/signup');
        $this->load->library('facebook/myfacebook');
        $this->myfacebook->init($this->config->item('default_app_id'), $this->config->item('default_app_secret'));
        $token = $this->myfacebook->get_access_token();
        if($token !== true)
        {
            $this->session->set_flashdata('alert', get_alert_html($token, ALERT_TYPE_ERROR));
            redirect('admin/signup');
        }
        $profile = $this->myfacebook->get_user_profile();
        if(!is_object($profile))
        {
            $this->session->set_flashdata('alert', get_alert_html($profile, ALERT_TYPE_ERROR));
            redirect('admin/signup');
        }else if( !$profile->getField('email') )
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_email_not_returned'), ALERT_TYPE_ERROR));
            redirect('admin/signup');
        }
        $email = $profile->getField('email');
        $this->load->model('users_model');
        $user = $this->users_model->get_user($email);
        if(isset($user->user_id))
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('validation_email_exist'), ALERT_TYPE_ERROR));
            redirect('admin/signup');
        }
        $username = substr($email, 0, strpos($email, '@'));
        $password = substr(uniqid(), 0, 6);
        $user_data = compact('email', 'username', 'password');
        $picture = $profile->getPicture()->getUrl();
        $user_id = $this->users_model->fbsignup($user_data);
        if(isset($user_id))
        {
            $this->session->set_userdata('user_login', TRUE);
            $this->session->set_userdata('user_id', $user_id);
            $this->session->set_userdata('user_name', $username);
            $this->session->set_userdata('user_email', $email);
            $this->session->set_userdata('profile_image', $picture);
            $setting = $this->users_model->get_settings($user_id);
            foreach ($setting as $key => $value) 
            {
                $this->session->set_userdata($key, $value);
            }
            send_email($user_data['email'], $this->lang->line('email_signup_welcome').' '.$this->config->item('site_name'), 'signup', $user_data );
            redirect('home/index');
        }else
        {
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_something_wrong'), ALERT_TYPE_ERROR));
            redirect('admin/login');
        }
    }
    
    /**
    * Logout from application link
    */
    public function logout()
    {
        $this->session->sess_destroy();
        delete_cookie('remember');
        redirect('admin/login');
    }
    /**
    * Forgot password page to enter email address
    */
    public function forgot_password()
    {
        if($this->session->userdata('admin_login') === TRUE || $this->session->userdata('user_login') === TRUE)
            redirect('home/index');
        $this->form_validation->set_rules('email', $this->lang->line('validation_email_address'), 'trim|required');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        if(!$this->form_validation->run())
        {
             $data['page_title'] = $this->lang->line('forgot_title');
             $this->load->view('forgot_password', $data);
        }else
        {
            $this->load->model('users_model');
            $user = $this->users_model->get_user($this->input->post('email', TRUE));
            if(!isset($user->user_id))
            {
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_email_not_exist'), ALERT_TYPE_ERROR));
                redirect('admin/forgot_password');
            }else if($user->user_status == USER_STATUS_INACTIVE)
            {
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_login_disabled'), ALERT_TYPE_ERROR));
                redirect('admin/forgot_password');
            }
            $data['hash'] = md5($user->user_password);
            $this->users_model->save_reset_password_request($user->user_id, $data['hash']);
            $sent = send_email($this->input->post('email', TRUE), $this->lang->line('email_reset_password'), 'reset_password', $data);
            if($sent)
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_reset_password_mail_sent'), ALERT_TYPE_SUCCESS));
            else
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_something_wrong'), ALERT_TYPE_ERROR));
            redirect('admin/forgot_password');    
        }
    }
    /**
    * Reset password link sent in email
    * @param $hash string, The hash string used to validate a user
    */
    public function reset_password($hash = '')
    {
        if($this->session->userdata('admin_login') === TRUE || $this->session->userdata('user_login') === TRUE)
            redirect('home/index');
        if(empty($hash))
            redirect('admin/login');
        $this->load->model('users_model');
        $request = $this->users_model->validate_reset_password_request($hash);
        if(!isset($request->request_id))
            redirect('admin/login');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_password_check');
        $this->form_validation->set_rules('confirm_password', 'Password', 'trim|required');
        $this->form_validation->set_message('required', '%s '.$this->lang->line('validation_required'));
         if(!$this->form_validation->run())
         {
            $data['page_title'] = $this->lang->line('reset_title');
            $data['hash'] = $hash;
            $this->load->view('reset_password', $data);
         }else
         {
            $this->users_model->update_password($request->user_id, $this->input->post('password', TRUE));
            $this->users_model->delete_reset_password_requests($request->user_id);
            $user = $this->users_model->get_record($request->user_id);
            send_email($user->user_email, $this->lang->line('email_password_changed'), 'password_changed');
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_password_reset'), ALERT_TYPE_SUCCESS));
            redirect('admin/login');
         }
    }

    /**
    * custom function for form_validation library to check if email address already exsist in database
    * @param $email string, email address of the user
    */
    public function email_check($email){
        $user_id = $this->uri->segment(3);
        $this->load->model('users_model');
        $user_id = empty($user_id) ? $this->session->userdata('user_id') : $user_id;
        if($this->users_model->isemailexist($email, $user_id)){
            $this->form_validation->set_message('email_check', $this->lang->line('validation_email_exist'));
            return FALSE;
        }
        return TRUE; 
    }

    /**
    * custom function for form_validation library to check if username already exsist in database
    * @param $user_name string, username of the user to be checked
    */
    public function username_check($user_name){
        $user_id = $this->uri->segment(3);
        $this->load->model('users_model');
        $user_id = empty($user_id) ? $this->session->userdata('user_id') : $user_id;
        if($this->users_model->isusernameexist($user_name, $user_id)){
            $this->form_validation->set_message('username_check', $this->lang->line('validation_username_exist'));
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
    * match password and confirm password fields
    * custom function for form_validation library
    * @param $password string, password of the user
    */
    public function password_check($password){
        $confirm_password = $this->input->post('confirm_password', TRUE);
        if($password != $confirm_password){
            $this->form_validation->set_message('password_check', $this->lang->line('validation_password_not_same'));
            return FALSE;
        }
        return TRUE;
    }

}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */