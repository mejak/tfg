<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Help Class
 *
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Help
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Help extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        session_check();
    }


    /** default function of the controller */
    public function index()
    {
        redirect('help/user');
    }

    /**
    * User Help
    */
    public function user()
    {
        $data['page_title'] = $this->lang->line('help_user_title');
        $data['view'] = 'user_help';
        $this->load->view('template', $data); 
    }
    /**
    * User Help
    */
    public function admin()
    {
        admin_session_check();
        $data['page_title'] = $this->lang->line('help_admin_title');
        $data['view'] = 'admin_help';
        $this->load->view('template', $data); 
    }

}

/* End of file Config.php */
/* Location: ./application/controllers/Config.php */