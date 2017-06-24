<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Users extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model("users_model");
    }

    function all_get($offset = 0)
    {
        admin_session_check();
    	$users = $this->users_model->get_list($offset);
		$this->response($users,200);
    }
}
?>