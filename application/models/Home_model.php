<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Home_model Class
 * 
 * @package   PHP_FMT
 * @subpackage  Models
 * @category  Home_model
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Home_model extends CI_Model{
    
    function __construct()
    {
        parent :: __construct();
    }
    /**
    * get posts for dashboard
    * @return void
    */
    public function get_latest_posts($user_id = 0)
    {
        $this->db->select('fb_posts.*')->from('fb_posts');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('post_id', 'desc');
        $this->db->limit(DASHBOARD_POST_LIMIT);
        return $this->db->get()->result();
    }

    /**
    * get pages for dashboard
    * @return void
    */
    public function get_pages($user_id = 0, $limit)
    {
        $this->db->select('page_id, page_name, page_fb_id, profile_name, profile_fb_id, page_likes, permissions, pages.profile_id')->from('pages');
        $this->db->join('profiles', 'profiles.profile_id=pages.profile_id', 'inner');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('pages.page_id ASC');
        $lim = DASHBOARD_PAGE_LIMIT > $limit ? $limit : DASHBOARD_PAGE_LIMIT ;
        $this->db->limit($lim);
        return $this->db->get()->result();
    }
}

/* End of file canvas_model.php */
/* Location: ./application/models/canvas_model.php */
