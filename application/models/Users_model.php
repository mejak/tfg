<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * users_model Class
 * 
 * @package   PHP_FMT
 * @subpackage  Models
 * @category  users_model
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Users_model extends CI_Model{
    
    function __construct()
    {
        parent :: __construct();
    }
    /**
    * validate user through password
    * @return object
    */
    public function validate_user()
    {
        $email_username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $this->db->select('*')->from('users');
        $this->db->where("(user_name='$email_username' OR user_email='$email_username')");
        $this->db->where("user_password = MD5('$password')");
        $result = $this->db->get();
        return $result->row();
    }
    /**
    * get a user record
    * @param $email string, Email address of user
    * @return object
    */
    public function get_user($email)
    {
        $this->db->select('*')->from('users');
        $this->db->where('user_email', $email);
        $result = $this->db->get();
        return $result->row();
    }
    /**
    * store a password reset request of a valid user
    * @param $user_id integer, user_id of the user
    * @param $hash string, random hash string
    * @return void
    */
    public function save_reset_password_request($user_id, $hash)
    {
        $request = array(
          'user_id' => $user_id,
          'hash' => $hash
        );
        $this->db->insert('reset_password_requests', $request);
    }
    /**
    * validate a hash string for password reset request 
    * @param $hash string, random hash string
    * @return object
    */
    public function validate_reset_password_request($hash)
    {
        $this->db->select('*')->from('reset_password_requests');
        $this->db->where('hash', $hash);
        $result =  $this->db->get();
        return $result->row();
    }
    /**
    * update password of a user
    * @param $user_id integer, The id of the user
    * @param $user_password string, The password of the user
    * @return void
    */
    public function update_password($user_id, $user_password)
    {
        $query = "update users set user_password = MD5('$user_password') where user_id=$user_id";
        $this->db->query($query);
    }
    /**
    * delete reset password requests of a user i.e hash etc
    * @param $user_id integer, The id of the user
    * @return void
    */
    public function delete_reset_password_requests($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete('reset_password_requests');
    }
    /**
    * delete a user and all is related data from database
    * @param $user_id integer, The id of the user
    * @return void
    */
    public function delete($user_id = 0)
    {
        $query = "DELETE tbl FROM pages as tbl
                  INNER JOIN profiles on tbl.profile_id=profiles.profile_id  
                  WHERE profiles.user_id = $user_id";
        $this->db->query($query);
        // delete posts of camapaign
        $query = "DELETE tbl FROM post_to_nodes as tbl
                  INNER JOIN fb_posts on fb_posts.post_id=tbl.post_id 
                  WHERE fb_posts.user_id = $user_id";
        $this->db->query($query);
        // delete user profiles
        $this->db->where('user_id', $user_id);
        $this->db->delete('profiles');
        // delete user campaigns
        $this->db->where('user_id', $user_id);
        $this->db->delete('fb_posts');
        // delete user settings
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_settings');
        // delete user
        $this->db->where('user_id', $user_id);
        $this->db->delete('users');
        // delete user password resets request
        $this->db->where('user_id', $user_id);
        $this->db->delete('reset_password_requests');
    }
    /**
    * get a user from database
    * @param $user_id integer, The id of the user
    * @return object
    */
    public function get_record($user_id)
    {
        return $this->db->get_where('users', array('user_id'=>$user_id))->row();
    }
    /**
    * get List of users for admin
    * @param $count boolean, get count of records or get records
    * @param $offset int, offset for pagination
    * @param $limit int, limit for pagination
    * @return int or array
    */
    public function get_list($offset = 0)
    {
        $this->db->select('user_id, user_name, user_email, user_role, user_status');
        $this->db->select("DATE_FORMAT(date_created, '%d %M, %Y') AS created_at", FALSE);
        $this->db->from('users');
        $this->db->order_by('date_created desc, user_id desc');
        $this->db->limit($this->config->item('records_per_page'), $offset);
        return $this->db->get()->result();
    }
    /**
    * update status of a user
    * @param $user_id integer, The id of the user
    * @param $status CONSTANT, The new status of the user
    * @return void
    */
    public function update_user_status($user_id, $status)
    {
        $user =  array(
          'user_status' => $status
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('users', $user);
    }
    /**
    * check if user name already exsist in database
    * @param $username string, The username to be checked
    * @param $user_id integer, The id of the user whose username to be exculded from check
    * @return boolean
    */
    public function isusernameexist($username, $user_id = 0)
    {
        if($user_id)
            return $this->db->get_where('users', array('user_name'=>$username, 'user_id !='=> $user_id))->num_rows();
        return $this->db->get_where('users', array('user_name'=>$username))->num_rows();
    }
    /**
    * check if email address already exsist in database
    * @param $email string, The email to be checked
    * @param $user_id integer, The id of the user whose email to be exculded from check
    * @return boolean
    */
    public function isemailexist($email, $user_id = 0)
    {
        if($user_id)
            return $this->db->get_where('users', array('user_email'=>$email, 'user_id !='=> $user_id))->num_rows();
        return $this->db->get_where('users', array('user_email'=>$email))->num_rows();
    }
    /**
    * insert a new user
    * @return void
    */
    public function insert()
    {
        $this->db->set('user_name', $this->input->post('username', TRUE));
        $this->db->set('user_email', $this->input->post('email', TRUE));
        $password = $this->db->escape($this->input->post('password', TRUE));
        $this->db->set('user_password',"MD5($password)", false);
        $this->db->set('user_role', $this->input->post('usertype', TRUE));
        $this->db->set('user_status', USER_STATUS_ACTIVE);
        $this->db->set('date_created', date('Y-m-d H:i:s'));
        $this->db->insert('users');
        $user_id = $this->db->insert_id();
        $settings = array(
          'user_id' => $user_id,
          'time_zone'=> date_default_timezone_get(),
          'current_lang' => $this->config->item('default_language'),
          'insights_allowed' => $this->input->post('insights_allowed', true),
          'page_limit' => $this->input->post('page_limit', true)
        );
        $this->db->insert('user_settings', $settings);
        return $user_id;
    }
    /**
    * signup
    * @return void
    */
    public function signup()
    {
        $this->db->set('user_name', $this->input->post('username', TRUE));
        $this->db->set('user_email', $this->input->post('email', TRUE));
        $password = $this->db->escape($this->input->post('password', TRUE));
        $this->db->set('user_password',"MD5($password)", false);
        $this->db->set('user_role', USER_TYPE_USER);
        $this->db->set('user_status', 1);
        $this->db->set('date_created', date('Y-m-d H:i:s'));
        $this->db->insert('users');
        $user_id = $this->db->insert_id();
        $settings = array(
          'user_id' => $user_id,
          'time_zone'=> date_default_timezone_get(),
          'current_lang' => $this->config->item('default_language'),
          'app_id' => '',
          'app_secret' => '',
          'page_limit' => $this->config->item('default_user_page_limit'),
          'insights_allowed' => $this->config->item('new_user_insight_allowed')
        );
        $this->db->insert('user_settings', $settings);
        return $user_id;
    }
    /**
    * Facebook signup
    * @return void
    */
    public function fbsignup($user_data)
    {
        $this->db->set('user_name', $user_data['username']);
        $this->db->set('user_email', $user_data['email']);
        $password = $this->db->escape($user_data['password']);
        $this->db->set('user_password',"MD5($password)", false);
        $this->db->set('user_role', USER_TYPE_USER);
        $this->db->set('user_status', 1);
        $this->db->set('date_created', date('Y-m-d H:i:s'));
        $this->db->insert('users');
        $user_id = $this->db->insert_id();
        $settings = array(
          'user_id' => $user_id,
          'time_zone'=> date_default_timezone_get(),
          'current_lang' => $this->config->item('default_language'),
          'app_id' => '',
          'app_secret' => '',
          'page_limit' => $this->config->item('default_user_page_limit'),
          'insights_allowed' => $this->config->item('new_user_insight_allowed')
        );
        $this->db->insert('user_settings', $settings);
        return $user_id;
    }
    /**
    * update a user record
    * @param $user_id integer, The id of the user
    * @return void
    */
    public function update($user_id)
    {
        $this->db->set('user_name', $this->input->post('username', TRUE));
        $this->db->set('user_email', $this->input->post('email', TRUE));
        if($this->input->post('password', TRUE)){
            $password = $this->db->escape($this->input->post('password', TRUE));
            $this->db->set('user_password',"MD5($password)", false);
        }
        $this->db->set('user_role', $this->input->post('usertype', TRUE));
        $this->db->where('user_id', $user_id);
        $this->db->update('users');
        $settings = array(
          'insights_allowed' => $this->input->post('insights_allowed', true),
          'page_limit' => $this->input->post('page_limit', true)
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_settings', $settings);
    }
    /**
    * get settings of a user
    * @param $user_id integer, The id of the user
    * @return object
    */
    public function get_settings($user_id)
    {
        return $this->db->get_where('user_settings', array('user_id'=>$user_id))->row();
    }
    /**
    * validate old password of logged in user for changing password
    * @param $password string, The password of the user
    * @param $user_id integer, The id of the user
    * @return boolean
    */
    public function validate_password($password, $user_id)
    {
        $this->db->where('user_password = MD5("'.$password.'")');
        $this->db->where('user_id', $user_id);
        return $this->db->get('users')->num_rows();
    }
    /**
    * get settings of a user
    * @param $user_id integer, The id of the user
    * @return object
    */
    public function update_user_setting($user_id)
    {
        if($this->config->item('username_email_change'))
        {
            $this->db->set('user_name', $this->input->post('username', TRUE));
            $this->db->set('user_email', $this->input->post('email', TRUE));
            $this->session->set_userdata('user_name', $this->input->post('username', TRUE));
            $this->session->set_userdata('user_email', $this->input->post('email', TRUE));
            $this->db->where('user_id', $user_id);
            $this->db->update('users');
        }
        if($this->input->post('password', TRUE) && $this->input->post('current_password', TRUE))
        {
            $password = $this->db->escape($this->input->post('password', TRUE));
            $this->db->set('user_password',"MD5($password)", false);
            $this->db->where('user_id', $user_id);
            $this->db->update('users');
        }
        
        $setting = array(
          'time_zone' => $this->input->post('time_zone', TRUE),
          'current_lang' => $this->input->post('language', TRUE),
          'app_id' => $this->input->post('app_id', TRUE),
          'app_secret' => $this->input->post('app_secret', TRUE)
        );
        $this->db->where('user_id', $user_id);
        $this->db->update('user_settings', $setting);
        $this->session->set_userdata('time_zone', $this->input->post('time_zone', TRUE));
        $this->session->set_userdata('current_lang', $this->input->post('language', TRUE));
        $this->session->set_userdata('app_id', $this->input->post('app_id', TRUE));
        $this->session->set_userdata('app_secret', $this->input->post('app_secret', TRUE));
    }
}

/* End of file users_model.php */
/* Location: ./application/models/users_model.php */