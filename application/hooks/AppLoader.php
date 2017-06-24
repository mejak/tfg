<?php
class AppLoader
{
    var $ci;
    function initialize()
    {
        $this->ci =& get_instance();
        $this->check_user_status();
        $this->remember_me();
        $this->loadConfig();
        $this->loadLanguage();
        $this->setCookies();
    }

    function loadLanguage()
    {
        
        $this->ci->load->helper('language');
        $site_lang = $this->ci->config->item('default_language');
        if($this->ci->session->userdata('current_lang'))
            $site_lang = $this->ci->session->userdata('current_lang');
        else if(get_cookie('current_lang'))
            $site_lang = get_cookie('current_lang');
        $this->ci->lang->load('alerts', $site_lang);
        $this->ci->lang->load('content', $site_lang);
        $this->ci->lang->load('user_help', $site_lang);
        $this->ci->lang->load('admin_help', $site_lang);
        $this->ci->lang->load('email', $site_lang);
    }

    function loadConfig()
    {
        foreach($this->ci->Siteconfig->get_all()->result() as $site_config)
        {
            $this->ci->config->set_item($site_config->key, $site_config->value);
        }
    }

    function setCookies()
    {
        if($this->ci->session->userdata('current_lang'))
            set_cookie('current_lang', $this->ci->session->userdata('current_lang'), 15 * 24 * 60 * 60);
        if($this->ci->session->userdata('profile_image'))
            set_cookie('profile_image', $this->ci->session->userdata('profile_image'), 15 * 24 * 60 * 60);
    }

    function check_user_status()
    {
        if($this->ci->session->userdata('user_id'))
        {
            $this->ci->db->select('*')->from('users');
            $this->ci->db->where('user_id', $this->ci->session->userdata('user_id'));
            $user = $this->ci->db->get()->row();
            if(!isset($user->user_id) || $user->user_status == USER_STATUS_INACTIVE)
            {   
                $this->ci->session->sess_destroy();
                delete_cookie('remember');
                redirect('admin/login');
            }
            $this->ci->session->unset_userdata('admin_login');
            $this->ci->session->unset_userdata('user_login');
            if($user->user_role == USER_TYPE_ADMIN)
                $this->ci->session->set_userdata('admin_login', TRUE);
            else
                $this->ci->session->set_userdata('user_login', TRUE);
            $this->ci->session->set_userdata('user_name', $user->user_name);
            $this->ci->session->set_userdata('user_email', $user->user_email);
            $this->ci->load->model('users_model');
            $setting = $this->ci->users_model->get_settings($user->user_id);
            foreach ($setting as $key => $value) 
            {
                $this->ci->session->set_userdata($key, $value);
            }
        }
    }

    function remember_me()
    {
        $this->ci =& get_instance();
        if(!$this->ci->session->userdata('user_id')) //session not set
        {
            $email = get_cookie('remember');
            if(is_null($email) || empty($email)) return false;
            $this->ci->load->library('encryption');
            $email = $this->ci->encryption->decrypt($email);
            $this->ci->db->select('*')->from('users');
            $this->ci->db->where('user_email', $email);
            $user = $this->ci->db->get()->row();
            if(!isset($user->user_id) || $user->user_status == USER_STATUS_INACTIVE)
            {   
                return false;
            }

            if($user->user_role == USER_TYPE_ADMIN)
                $this->ci->session->set_userdata('admin_login', TRUE);
            else
                $this->ci->session->set_userdata('user_login', TRUE);
            $this->ci->session->set_userdata('user_id', $user->user_id);
            $this->ci->session->set_userdata('user_name', $user->user_name);
            $this->ci->session->set_userdata('user_email', $user->user_email);
            $this->ci->load->model('users_model');
            $setting = $this->ci->users_model->get_settings($user->user_id);
            foreach ($setting as $key => $value) 
            {
                $this->ci->session->set_userdata($key, $value);
            }
            /*$this->ci->load->model('pages_model');
            $limit = $this->ci->session->userdata('admin_login') === TRUE ? 0 : $this->ci->session->userdata('page_limit');
            $records = $this->ci->pages_model->get_list($user->user_id, $limit);
            if(is_array($records) && !empty($records))
            {
                $page = $this->ci->pages_model->get_record($records[0]->page_id);
                set_page_in_session($page);
            }*/
        }
    }
}