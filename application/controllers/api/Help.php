<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Help extends REST_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function user_get()
    {
        session_check();
        $have_next_faq = true;
        $faq_index  = 1;
        $records = array();
    	while ($have_next_faq) {
            if($this->lang->line('user_faq_'.$faq_index.'_question')){
                $faq =  array(
                    'question' => $this->lang->line('user_faq_'.$faq_index.'_question'),
                    'video' => $this->lang->line('user_faq_'.$faq_index.'_video_url'),
                    'answer' => $this->lang->line('user_faq_'.$faq_index.'_answer')
                );
                $records[] = $faq;
                $faq_index ++;
            }else
                $have_next_faq = false;
        }
		$this->response($records,200);
    }
    function admin_get()
    {
        admin_session_check();
        $have_next_faq = true;
        $faq_index  = 1;
        $records = array();
        while ($have_next_faq) {
            if($this->lang->line('admin_faq_'.$faq_index.'_question')){
                $faq =  array(
                    'question' => $this->lang->line('admin_faq_'.$faq_index.'_question'),
                    'video' => $this->lang->line('admin_faq_'.$faq_index.'_video_url'),
                    'answer' => $this->lang->line('admin_faq_'.$faq_index.'_answer')
                );
                $records[] = $faq;
                $faq_index ++;
            }else
                $have_next_faq = false;
        }
        $this->response($records,200);
    }
}
?>