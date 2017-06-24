<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * profiles_model Class
 * 
 * @package   PHP_FMT
 * @subpackage  Models
 * @category  profiles_model
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Profiles_model extends CI_Model{

    function __construct(){
        parent :: __construct();
    }
    /**
    * Get ALL profiles in database
    * @param $count boolean, 
    * @return integer if count = true
    * @return array  if count = false
    */
    public function get_list($count=false, $user_id = 0)
    {
        $this->db->select('profiles.*, count(pages.page_id) as page_count')->from('profiles');
        $this->db->join('pages', 'pages.profile_id=profiles.profile_id', 'left');
        if($user_id)
            $this->db->where('profiles.user_id',  $user_id);
        $this->db->group_by('profiles.profile_id');
        $result = $this->db->get();
        if($count)
            return $result->num_rows();
        return $result->result();
    }
    /**
    * Delete a profile and all its pages from db
    * @param $profile_id integer, primary key of profiles table
    * @return void 
    */
    public function delete($profile_id = 0)
    {
        $query = "DELETE tbl FROM post_to_nodes as tbl
                  INNER JOIN pages on tbl.node_id=pages.page_id 
                  WHERE profile_id = $profile_id";
        $this->db->query($query);
        $this->db->where('profile_id', $profile_id);
        $this->db->delete('pages');
        $this->db->where('profile_id', $profile_id);
        $this->db->delete('profiles');
    }
    /**
    * checks whether a user is allowed to access a profile
    * @param $profile_id integer, primary key of profiles table
    * @param $user_id integer, primary key of users table
    * @return object array of profile objects
    */
    public function is_user_allowed($profile_id, $user_id){
        if($this->session->userdata('admin_login') === TRUE)
            return true;
        $this->db->select('*')->from('profiles');
        $this->db->where('profile_id', $profile_id);
        $this->db->where('user_id', $user_id);
        return $this->db->get()->num_rows();
    }
    /**
    * insert fb user in database or update token if already exist
    * @param $row object, page data imported from facebook
    * @param $user_id integer, id of logged in users
    * @param $token string, Token of fb user
    * @param $profile_app boolean, user own app of default app
    * @return void
    */
    function insert_update_fb_user($row, $user_id, $token, $profile_app)
    {
        $profile_record = array(
          'profile_fb_id' => $row->getField('id'),
          'profile_name' => $row->getField('name'),
          'user_id' => $user_id,
          'profile_app' => $profile_app,
          'profile_token' => $token,
          'profile_picture' => $row->getPicture()->getUrl()
        );
        $resource = $this->db->get_where('profiles', array('profile_fb_id'=> $row->getField('id')));
        if($resource->num_rows())
        {
            $profile = $resource->row();
            $this->db->where('profile_fb_id', $row->getField('id'));
            $this->db->update('profiles', $profile_record);
            return $profile->profile_id;
        }else
        {
            $this->db->insert('profiles', $profile_record);
            $profile_id = $this->db->insert_id();
            return $profile_id;
        }
    }
    
    /**
    * get a profile from db
    * @param $profile_id integer, primary key profiles table
    * @return object
    */
    function get_profile($profile_id)
    {
        $this->db->select('*')->from('profiles');
        $this->db->where('profile_id', $profile_id);
        return $this->db->get()->row();
    }


}

/* End of file profiles_model.php */
/* Location: ./application/models/profiles_model.php */
