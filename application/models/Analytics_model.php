<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Analytics_model Class
 * 
 * @package   PHP_FMT
 * @subpackage  Models
 * @category  Analytics_model
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Analytics_model extends CI_Model{
    
    function __construct(){
        parent :: __construct();
    }
   
    /**
    * get List records
    */
    public function get_list(){
        return $this->db->get('insights_metrics')->result();
    }
}

/* End of file Analytics_model.php */
/* Location: ./application/models/Analytics_model.php */
