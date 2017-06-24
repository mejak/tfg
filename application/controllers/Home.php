<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Home Class
 *
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Home
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Home extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        session_check();
        $this->load->model('home_model');
    }


    /** default function of the controller */
    public function index()
    {
        $posts = $this->home_model->get_latest_posts($this->session->userdata('user_id'));
        $data['posts'] = $posts;
        $data['page_title'] = $this->lang->line('dashboard_title');
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
        $limit = $this->session->userdata('admin_login') === TRUE ? DASHBOARD_PAGE_LIMIT : $this->session->userdata('page_limit');
        $data['pages'] = $this->home_model->get_pages($this->session->userdata('user_id'), $limit);;
        $data['view'] = 'dashboard';
        $this->load->view('template', $data);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */