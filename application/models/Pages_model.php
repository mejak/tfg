<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * pages_model Class
 * 
 * @package   PHP_FMT
 * @subpackage  Models
 * @category  pages_model
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Pages_model extends CI_Model{
    
    function __construct(){
        parent :: __construct();
    }

    /**
    * check if a facebook page already exsist in database
    * @param $id string, Facebook id of the page
    * @return boolean 
    */
    function is_page_exist($id, $profile_id)
    {
        $this->db->where('page_fb_id', $id);
        $this->db->where('profile_id', $profile_id);
        $sql = $this->db->get('pages');
        return $sql->num_rows();
    }
    /**
    * insert page in database
    * @param $row object, page data imported from facebook
    * @param $profile_id integer, primary key profiles table
    * @return void
    */
    function insert_page($row, $profile_id)
    {
        $page =  array(
            'page_fb_id' => $row['id'],
            'page_name' => $row['name'],
            'page_category' => $row['category'] ? $row['category'] : '',
            'page_likes' => !empty($row['fan_count']) ? $row['fan_count'] : 0,
            'page_token' => $row['access_token'],
            'profile_picture' => $row['picture']['url'] ? $row['picture']['url'] : '',
            'permissions' => implode(',', $row['perms']),
            'profile_id' => $profile_id,
            'date_added' => date('y-m-d H:i:s')
        );
        $this->db->insert('pages', $page);
        return $this->db->insert_id();
    }
    /**
    * update page in db
    * @param $row object, page data imported from facebook
    * @param $profile_id integer, primary key profiles table
    * @return void
    */
    function update_page($row, $profile_id)
    {
        $page =  array(
            'page_fb_id' => $row['id'],
            'page_name' => $row['name'],
            'page_category' => $row['category'] ? $row['category'] : '',
            'page_likes' => $row['fan_count'] ? $row['fan_count'] : 0,
            'page_token' => $row['access_token'],
            'profile_picture' => $row['picture']['url'] ? $row['picture']['url'] : '',
            'permissions' => implode(',', $row['perms']),
            'profile_id' => $profile_id
        );
        $this->db->where('page_fb_id', $row['id']);
        $this->db->where('profile_id', $profile_id);
        $this->db->update('pages', $page);
    }

    /**
    * get count of all saved pages by user
    * @param $user_id integer, id of the user
    * @return array of object
    */  
    public function get_user_page_count($user_id)
    {
        $this->db->select('count(page_id) as page_count')->from('pages');
        $this->db->join('profiles', 'profiles.profile_id=pages.profile_id', 'inner');
        $this->db->where('profiles.user_id', $user_id);
        $record = $this->db->get()->row();
        return $record->page_count;
    }

    /**
    * get pages List
    * @param $user_id integer, user id if logged in person in not admin
    * @param $limit int, page limit for user
    * @return int or array
    */
    public function get_list($user_id=0, $limit=0)
    {
        $this->db->select('page_id, page_name, page_fb_id, profile_name, profile_fb_id, page_likes, permissions, pages.profile_id')->from('pages');
        $this->db->join('profiles', 'profiles.profile_id=pages.profile_id', 'inner');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('pages.page_id ASC');
        if($limit)
            $this->db->limit($limit);
        return $this->db->get()->result();
    }
    /**
    * get a single page
    * @param $page_id integer, id of the page primary key
    * @return object
    */ 
    public function get_record($page_id)
    {
        return $this->db->get_where('pages', array('page_id'=>$page_id))->row();
    }
    /**
    * delete a page
    * @param $page_id integer, id of the page primary key
    * @return voide
    */ 
    public function delete($page_id)
    {
        $this->db->where('page_id', $page_id);
        $this->db->delete('pages');
    }

    /**
    * check whether a user is allowed to access this page
    * @param $user_id integer, id of the user primary key
    * @param $page_id integer, id of the page primary key
    * @return boolean
    */
    public function is_user_allowed($user_id= 0, $page_id=0)
    {
        $this->db->select('*')->from('pages');
        $this->db->join('profiles', 'profiles.profile_id=pages.profile_id', 'inner');
        $this->db->where('page_id', $page_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        return $this->db->get()->num_rows();
    }

}

/* End of file pages_model.php */
/* Location: ./application/models/pages_model.php */
