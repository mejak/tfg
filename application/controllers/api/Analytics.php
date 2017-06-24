<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Analytics extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        session_check();
        if($this->session->userdata('user_login') === TRUE && !$this->session->userdata('insights_allowed') )
            redirect('home/index');
        $this->load->model("analytics_model");
    }

    function all_get()
    {
        $records = $this->analytics_model->get_list();
        foreach ($records as $key => $metric) {
            $metric->metric_period = explode(', ', $metric->metric_period);
        }
        $this->response($records,200);
    }
}
?>