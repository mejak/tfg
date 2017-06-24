<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Update Class
 *
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Update
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Update extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    /** default function of the controller */
    public function index()
    {
        admin_session_check();
        $this->form_validation->set_rules('code', $this->lang->line('validation_code'), 'trim|required');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('update_script_title');
            $response = curl_browser_request(UPDATE_SERVER_URL.'available_version?cv='.SCRIPT_VERSION);
            $response = json_decode($response);
            if(!is_object($response))
            {
                $data['alert'] = get_alert_html($this->lang->line('error_update_server_error'), ALERT_TYPE_ERROR) ;
            }else if(version_compare(SCRIPT_VERSION, $response->version, '<'))
            {
                $message = $this->lang->line('script_update_available').$response->version;
                if(isset($response->details))
                {
                    $message .= '<br><ul>';
                    foreach ($response->details as $key => $value) {
                       $message .= "<li>$value</li>";
                    }
                    $message .= '</ul>';
                }
                $data['alert'] = get_alert_html($message) ;
            }
            else
                $data['alert'] = get_alert_html($this->lang->line('script_already_updated'));
            if(is_object($response))
                $data['available_version'] = $response->version;
            else
                $data['available_version'] = SCRIPT_VERSION;
            $data['view'] = 'update_script';
            $this->load->view('template', $data);
        }else
        {
            $post['ins_v'] = SCRIPT_VERSION;
            $post['base_url'] = base_url();
            $post['code'] = $this->input->post('code');
            $post['buyer'] = $this->input->post('buyer_username');
            $update_version = $this->input->post('update_version');
            $response = $this->_curl_post(UPDATE_SERVER_URL.'get_update', $post);
            if($response[1] == 'application/zip')
            {
                $update_file = 'update_'.str_replace('.', '-', $update_version).'.zip';
                $fh = fopen($update_file, 'w');
                if(!fwrite($fh, $response[0]))
                {
                    $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_update_not_saved'), ALERT_TYPE_ERROR ) );
                    redirect('update/index');
                }   
                fclose($fh);
                chmod($update_file, 0777);
                create_folder(PATH_BACKUPS);
                $update = zip_open($update_file);
                if(!is_resource($update))
                {
                    $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_update_not_open'), ALERT_TYPE_ERROR ) );
                    redirect('update/index');
                }
                while ( $zip_entry = zip_read($update) )
                {
                    $full_path = zip_entry_name($zip_entry);
                    if(!is_dir($full_path))
                    {
                        if(file_exists($full_path)) //backup old files
                        {
                            create_folder(PATH_BACKUPS.dirname($full_path));
                            if(!rename($full_path, PATH_BACKUPS.$full_path)) //unable to move file
                            {
                                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_update_backup'), ALERT_TYPE_ERROR ) );
                                zip_close($update);
                                unlink($update_file);
                                redirect('update/index');
                            }
                        }
                        create_folder(dirname($full_path));
                        $file = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                        $fh = fopen($full_path, 'w');
                        fwrite($fh, $file);
                        fclose($fh);
                    }
                }
                zip_close($update);
                unlink($update_file); // remove zip update
                if(file_exists('db_update.php'))
                {
                    include('db_update.php');
                    if(isset($queries) && is_array($queries) && !empty($queries))
                        foreach ($queries as $key => $query) {
                            $this->db->query($query);
                        }
                    unlink('db_update.php');
                }
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_script_updated'), ALERT_TYPE_SUCCESS ) );
                redirect('update/index');
            }
            $response = json_decode($response[0], TRUE);
            if(!is_array($response))
            {
                $this->session->set_flashdata('alert', get_alert_html($this->lang->line('error_update_server_error'), ALERT_TYPE_ERROR ) );
                redirect('update/index');
            }else if(isset($response['error']))
            {
                $this->session->set_flashdata('alert', get_alert_html($response['error'], ALERT_TYPE_ERROR ) );
                redirect('update/index');
            }else
                print_r($response);
        }
    }
    
    function _curl_post($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if(is_array($post_data))
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close ($ch);
        return array($server_output, $contentType);
    }
}

/* End of file update.php */
/* Location: ./application/controllers/update.php */