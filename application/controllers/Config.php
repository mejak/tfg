<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * config Class
 *
 * @package   FBPM
 * @subpackage  Controllers
 * @category  config
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Config extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        admin_session_check();
    }


    /** default function of the controller */
    public function index()
    {
        redirect('config/update');
    }
    /**
    * Update scrip configuration
    */
    public function update()
    {
        $config_data = $this->Siteconfig->get_list();
        foreach ($config_data as $config_item) 
        {
            if($config_item->required)
                $required = '|required';
            else
                $required = '';
            $this->form_validation->set_rules($config_item->key, $config_item->label_text, 'trim'.$required);

        }
        reset($config_data);
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('app_configuration_title');
            $data['languages'] = scandir(APPPATH.'language');
            $data['config_data'] = $config_data;
            $data['view'] = 'app_configuration';
            $this->load->view('template', $data); 
        }else
        {
            $app_id = $this->input->post('default_app_id', TRUE);
            $app_secret = $this->input->post('default_app_secret', TRUE);
            if( !empty($app_id) && !empty($app_secret) )
            {
                $this->load->library('facebook/myfacebook');
                $this->myfacebook->init($app_id, $app_secret);
                $response = $this->myfacebook->get_app_details();
                if(isset($response['error']))
                {
                    $this->session->set_flashdata('alert', get_alert_html(get_fb_request_error_string($response), ALERT_TYPE_ERROR));
                    redirect('config/update');
                }
            }
            $this->Siteconfig->update_config($_POST);
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_app_configuration'), ALERT_TYPE_SUCCESS));
            redirect('config/update');

        }

    }
    /**
    * Add new configuration item
    */
    public function add()
    {
        $this->form_validation->set_rules('label_text', 'Label text', 'trim|required');
        $this->form_validation->set_rules('key', 'Key', 'trim|required');
        $this->form_validation->set_rules('value', 'Value', 'trim');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('order', 'Order', 'trim|required');
        $this->form_validation->set_rules('is_required', 'required field', 'trim|required');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = 'Add New Config Item';
            $data['languages'] = scandir(APPPATH.'language');
            $data['config_data'] = $this->Siteconfig->get_list();
            $data['view'] = 'add_new_config';
            $this->load->view('template', $data);
        }else
        {
            $this->Siteconfig->insert();
            $this->session->set_flashdata('alert', get_alert_html(SUCCESS_CONFIG_ADDED, ALERT_TYPE_SUCCESS));
            redirect('config/add');
        }

    }

}

/* End of file Config.php */
/* Location: ./application/controllers/Config.php */