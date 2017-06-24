<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Siteconfig Class
 * 
 * @package   PHP_FMT
 * @subpackage  Models
 * @category  Siteconfig
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Siteconfig extends CI_Model{


public function __construct()
{
    parent::__construct();
}


public function get_all()
{
    return $this->db->get('config_data');
}


public function update_config($data)
{
    $success = true;
    foreach($data as $key => $value)
    {
        if($key == $this->config->item('csrf_token_name'))
            continue;
        if(!$this->save($key, $this->input->post($key, TRUE)))
        {
            $success = false;
            break;  
        }
    }
    return $success;
}


public function save($key, $value)
{
    $config_data = array(
        'key' => $key,
        'value' => $value
    );
    $this->db->where('key', $key);
    return $this->db->update('config_data', $config_data); 
}

public function get_list()
{
    $this->db->order_by('type desc, order asc');
    return $this->db->get('config_data')->result();
}

public function insert()
{
    $config = array(
        'key' =>        $this->input->post('key', TRUE),
        'value' =>      $this->input->post('value', TRUE),
        'type' =>       $this->input->post('type', TRUE),
        'required' =>   $this->input->post('is_required', TRUE),
        'label_text' => $this->input->post('label_text', TRUE),
        'order' =>      $this->input->post('order', TRUE)
    );
    $this->db->insert('config_data', $config);
    return $this->db->insert_id();
}

}