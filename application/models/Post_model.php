<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * post_model Class
 * 
 * @package   PHP_FMT
 * @subpackage  Models
 * @category  post_model
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Post_model extends CI_Model{
    
    function __construct(){
        parent :: __construct();
    }
    /**
    * insert new Post(compaign) in database
    * @param $image_url string, The url of image linked wit post
    * @return void
    */
	public function insert($image_url = false)
	{
		if($this->input->post('schedule_post'))
		{
		    $server_timezone = date_default_timezone_get();
		    date_default_timezone_set($this->session->userdata('time_zone'));
		    $schedule_time = new DateTime($this->input->post('schedule_time', TRUE));
		    $server_zone = new DateTimeZone($server_timezone);
		    $schedule_time->setTimezone($server_zone);
		    date_default_timezone_set($server_timezone);
		}
	    $post =  array(
	      'post_type' =>        $this->input->post('type', TRUE),
	      'post_name' =>     	$this->input->post('post_name', TRUE) ? $this->input->post('post_name', TRUE) : '',
	      'post_message' =>     $this->input->post('fb_message', TRUE) ? $this->input->post('fb_message', TRUE) : '',
	      'link_url' =>         $this->input->post('link_url', TRUE) ? $this->input->post('link_url', TRUE) : '' ,
	      'link_title' =>       $this->input->post('link_title', TRUE) ? $this->input->post('link_title', TRUE) : '',
	      'link_description' => $this->input->post('link_description', TRUE) ? $this->input->post('link_description', TRUE) : '',
	      'link_caption' =>     $this->input->post('link_caption', TRUE) ? $this->input->post('link_caption', TRUE) : '',
	      'user_id' =>          $this->session->userdata('user_id'),
	      'date_started' =>     isset($schedule_time) ? $schedule_time->format('Y-m-d H:i:s') : date('Y-m-d H:i:s') ,
	      'status' => 			CAMPAIGN_STATUS_PENDING
	    );
	    $post['image_url'] = $image_url ? $image_url : '';
	    $this->db->insert('fb_posts', $post);
	    return $this->db->insert_id();
	}
	/**
    * update Post(compaign)
    * @param $campaign_id integer, The id of post to be update
    * @param $image_url string, The url of image linked wit post
    * @return void
    */
	public function update_campaign($campaign_id = 0)
	{
	    $post =  array(
	      'post_name' =>     	$this->input->post('post_name', TRUE) ? $this->input->post('post_name', TRUE) : '',
	      'post_message' =>     $this->input->post('fb_message', TRUE) ? $this->input->post('fb_message', TRUE) : '',
	      'link_title' =>       $this->input->post('link_title', TRUE) ? $this->input->post('link_title', TRUE) : '',
	      'link_description' => $this->input->post('link_description', TRUE) ? $this->input->post('link_description', TRUE) : '',
	    );
	    $this->db->where('post_id', $campaign_id);
	    $this->db->update('fb_posts', $post);
	    return $campaign_id;
	}
	/**
    * insert records for all targeted nodes of post(compaign)
    * @param $post_id integer, The id of post(compaign)
    * @return void
    */
	public function insert_post_nodes($post_id)
	{
		$selected_nodes = $this->input->post('nodes', TRUE);
	    $selected_nodes =  json_decode($selected_nodes, true);
	    $schedule_post = $this->input->post('schedule_post');

	    $current_server_time = date('Y-m-d H:i:s');
	    $server_timezone = date_default_timezone_get();
	    date_default_timezone_set($this->session->userdata('time_zone'));
	    $server_zone = new DateTimeZone($server_timezone);	    
	    $master_time = new DateTime($this->input->post('schedule_time', TRUE));
	    $master_time->setTimezone($server_zone);
	    $master_time = $master_time->format('Y-m-d H:i:s');

	    $insert_records = array();

	    foreach ($selected_nodes as $key => $page)
	    {
	    	if($page['schedule'])
	    	{
	    		if($page['time'])
	    		{
	    			$schedule_time = new DateTime($page['time']);
			    	$schedule_time->setTimezone($server_zone);
			    	$schedule_time = $schedule_time->format('Y-m-d H:i:s');
	    		}else
	    			$schedule_time = $master_time;
	    	}else
	    		$schedule_time = $current_server_time;
			$record = array(
			'post_id' =>  $post_id, 
			'node_id' => $page['page_id'],
			'post_status' => POST_STATUS_PENDING,
			'post_datetime' => $schedule_time,
			'post_schedule' => $page['schedule']
			);
			$insert_records[] = $record;
	    }
	    if(!empty($insert_records))
	    	$this->db->insert_batch('post_to_nodes', $insert_records);
	    date_default_timezone_set($server_timezone);
	}
	/**
    * update records for all targeted nodes of post(compaign)
    * @param $post_id integer, The id of post(compaign)
    * @return void
    */
	public function update_post_nodes($post_id)
	{
		$selected_nodes = $this->input->post('prev_nodes', TRUE);
	    $selected_nodes =  json_decode($selected_nodes, true);
	    $schedule_post = $this->input->post('schedule_post');

	    $current_server_time = date('Y-m-d H:i:s');
	    $server_timezone = date_default_timezone_get();
	    date_default_timezone_set($this->session->userdata('time_zone'));
	    $server_zone = new DateTimeZone($server_timezone);	    
	    $master_time = new DateTime($this->input->post('schedule_time', TRUE));
	    $master_time->setTimezone($server_zone);
	    $master_time = $master_time->format('Y-m-d H:i:s');

	    foreach ($selected_nodes as $key => $page)
	    {
	    	if($page['schedule'])
	    	{
	    		if($page['time'])
	    		{
	    			$schedule_time = new DateTime($page['time']);
			    	$schedule_time->setTimezone($server_zone);
			    	$schedule_time = $schedule_time->format('Y-m-d H:i:s');
	    		}else
	    			$schedule_time = $master_time;
	    	}else
	    		$schedule_time = $current_server_time;
			$record = array(
			'post_datetime' => $schedule_time,
			'post_schedule' => $page['schedule']
			);
			$this->db->where('post_to_nodes_id', $page['post_to_nodes_id']);
			$this->db->update('post_to_nodes', $record);
	    }

	    date_default_timezone_set($server_timezone);
	}
	/**
    * get List of posts of a compaign
    * @param $node_id integer, id of the facebook node primary key
    * @param $user_id integer, user id if logged in person is not admin
    * @param $count boolean, get count of records or get records
    * @param $offset int, offset for pagination
    * @param $limit int, limit for pagination
    * @return int or array
    */
	public function get_node_post_list($node_id, $offset=0)
	{
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$this->db->select('post_to_nodes.*, posts.*')->from('post_to_nodes');
		$this->db->join('fb_posts as posts', 'posts.post_id=post_to_nodes.post_id', 'inner');
		$this->db->where('post_to_nodes.node_id', $node_id);
	    $this->db->order_by('post_to_nodes_id asc');
		$this->db->limit($this->config->item('records_per_page'), $offset);
		return $this->db->get()->result();
	}
	/**
    * get list of pages's post to be published, 
    * @return array
    */
	public function get_pending_posts($post_id = 0)
	{
		$this->db->query('SET SQL_BIG_SELECTS=1');
	    $this->db->select('post_to_nodes.*, posts.*, nodes.*, profiles.*')->from('post_to_nodes');
	    $this->db->join('fb_posts as posts', 'posts.post_id=post_to_nodes.post_id', 'inner');
	    $this->db->join('pages as nodes', 'nodes.page_id=post_to_nodes.node_id', 'inner');
	    $this->db->join('profiles', 'profiles.profile_id=nodes.profile_id', 'inner');
	    $this->db->where('post_to_nodes.post_status', POST_STATUS_PENDING);
	    $this->db->where('post_to_nodes.post_id', $post_id);
	    $this->db->order_by('profiles.profile_app ASC');
	    return $this->db->get()->result();
	}
	
	/**
    * get list of pages's published post
    * @return array
    */
	public function get_published_posts($post_id = 0)
	{
		$this->db->query('SET SQL_BIG_SELECTS=1');
	    $this->db->select('post_to_nodes.*, posts.*, nodes.*, profiles.*')->from('post_to_nodes');
	    $this->db->join('fb_posts as posts', 'posts.post_id=post_to_nodes.post_id', 'inner');
	    $this->db->join('pages as nodes', 'nodes.page_id=post_to_nodes.node_id', 'inner');
	    $this->db->join('profiles', 'profiles.profile_id=nodes.profile_id', 'inner');
	    $this->db->where('post_to_nodes.post_status', POST_STATUS_POSTED);
	    $this->db->where('post_to_nodes.post_id', $post_id);
	    $this->db->order_by('profiles.profile_app ASC');
	    return $this->db->get()->result();
	}
	/**
    * Delete a post of a compaign
    * @param $post_to_nodes_id integer, The id of the post
    * @return void
    */
	public function delete($post_to_nodes_id = 0)
	{
	    //delete posts
	    $this->db->where('post_to_nodes_id', $post_to_nodes_id);
	    $this->db->delete('post_to_nodes');
	}
	/**
    * Update a post status after facebook api call by cron job
    * @param $post_data object, The data of post
    * @param $api_response array, The response of API call
    * @return void
    */
	public function update_post($page_fb_id, $post_to_nodes_id, $api_response)
	{
	    if(isset($api_response['id'])) // success
	    { 
			if(isset($api_response['post_id']))
			{
				$post_ids = explode('_', $api_response['post_id']);
				$post_fb_url = 'https://www.facebook.com/'.$post_ids[0]."/posts/".$post_ids[1];
			}
			else if(strpos($api_response['id'], '_') !== FALSE)
			{
				$post_ids = explode('_', $api_response['id']);
				$post_fb_url = 'https://www.facebook.com/'.$post_ids[0]."/posts/".$post_ids[1];
			}else
			{
				$post_fb_url = 'https://www.facebook.com/'.$page_fb_id."/posts/".$api_response['id'];
			}
			
			$post_fb_id = isset($api_response['post_id']) ? $api_response['post_id'] : $api_response['id'];
			$post_update = array(
				'post_fb_id' => $post_fb_id,
				'post_status' => POST_STATUS_POSTED,
				'post_fb_url' => $post_fb_url
			);
			$this->db->where('post_to_nodes_id', $post_to_nodes_id);
			$this->db->update('post_to_nodes', $post_update);
	    }else if(isset($api_response['error'])){
			$post_update = array(
				'post_status' => POST_STATUS_ERROR,
				'post_error' => is_string($api_response['error']) ? $api_response['error'] : $api_response['error']['type'].' : '.$api_response['error']['message']
			);
			if(!is_string($api_response['error']) &&  isset($api_response['error']['error_user_title']) )
			{
				$post_update['post_error'] = $api_response['error']['code'].'('.$api_response['error']['error_subcode'].'): ';
				$post_update['post_error'] .= $api_response['error']['error_user_title'].' - ' . $api_response['error']['error_user_msg'];
			}
			$this->db->where('post_to_nodes_id', $post_to_nodes_id);
			$this->db->update('post_to_nodes', $post_update);
	    }
	}
	/**
    * Update a post insights after facebook api call
    * @param $post_fb_id string, The facebook id of the post
    * @param $likes integer, The likes count of the post
    * @param $comments integer, The comments count of the post
    * @param $shares integer, The shares count of the post
    * @return void
    */
	public function update_post_stats($post_fb_id, $likes, $comments, $shares)
	{
	    $post_update = array(
	      'post_likes' => $likes,
	      'post_comments' => $comments,
	      'post_shares' => $shares
	    );
	    $this->db->where("post_fb_id", $post_fb_id);
	    $this->db->update('post_to_nodes', $post_update);
	}
	/**
    * get a post
    * @param $post_to_nodes_id integer, The id of the post
    * @param $user_id integer, The user id if the person logged in is not admin
    * @return void
    */
	public function get_post($post_to_nodes_id, $user_id = 0)
	{
		$this->db->query('SET SQL_BIG_SELECTS=1');
	    $this->db->select('p2n.*, fb_posts.*, nodes.*, profiles.*')->from('post_to_nodes as p2n');
	    $this->db->join('fb_posts', 'fb_posts.post_id=p2n.post_id', 'inner');
	    $this->db->join('pages as nodes', 'nodes.page_id=p2n.node_id', 'inner');
	    $this->db->join('profiles', 'profiles.profile_id=nodes.profile_id', 'inner');
	    $this->db->where('p2n.post_to_nodes_id', $post_to_nodes_id);
	    if($user_id)
	    	$this->db->where('fb_posts.user_id', $user_id);
	    return $this->db->get()->row();
	}
	/**
    * get List of compaigns(post) 
    * @param $user_id integer, user id if logged in person in not admin
    * @param $count boolean, get count of records or get records
    * @param $offset int, offset for pagination
    * @param $limit int, limit for pagination
    * @return int or array
    */
	public function get_list($user_id = 0,  $count = false, $offset=0)
	{
		$this->db->query('SET SQL_BIG_SELECTS=1');
	    $this->db->select('fb_posts.*, count(post_to_nodes_id) as node_count, sum(post_likes) as post_likes, sum(post_comments) as post_comments, sum(post_shares) as post_shares')->from('fb_posts');
	    $this->db->join('post_to_nodes as p2n', 'p2n.post_id=fb_posts.post_id', 'left');
	    $this->db->where('user_id', $user_id);
	    $this->db->group_by('fb_posts.post_id');
	    if($count)
	    	return $this->db->get()->num_rows();
	    $this->db->order_by('post_id desc');
	    $this->db->limit($this->config->item('records_per_page'), $offset);
	    return $this->db->get()->result();
	}
	/**
    * get a single campaign
    * @param $campaign_id integer, The id of the campaign
    * @param $user_id integer, The user id if the person logged in is not admin
    * @return void
    */
	public function get_campaign($campaign_id= 0, $user_id = 0)
	{
	    $this->db->where('post_id', $campaign_id);
	    if($user_id)
	    	$this->db->where('user_id', $user_id);
	    return $this->db->get('fb_posts')->row();
	}
	/**
    * get List of compaigns's post for dashboard
    * @param $campaign_id integer, The id of the compaign primary key of fb_posts table
    * @param $count boolean, get count of records or get records
    * @param $offset int, offset for pagination
    * @param $limit int, limit for pagination
    * @return int or array
    */
	public function get_campaign_posts($campaign_id = 0, $count=false, $offset=0)
	{
		$this->db->query('SET SQL_BIG_SELECTS=1');
	    $this->db->select('posts.*, nodes.page_name')->from('post_to_nodes as posts');
	    $this->db->join('pages as nodes', 'nodes.page_id=posts.node_id', 'inner');
	    $this->db->where('posts.post_id', $campaign_id);
	    if($count)
		    return $this->db->get()->num_rows();
	    $this->db->order_by('post_to_nodes_id asc');
	    $this->db->limit($this->config->item('records_per_page'), $offset);
	    return $this->db->get()->result();
	}
	/**
    * delete a single campaign
    * @param $campaign_id integer, The id of the campaign
    * @return void
    */
	public function delete_campaign($campaign_id = 0)
	{
        // delete posts
	    $this->db->where('post_id', $campaign_id);
	    $this->db->delete('post_to_nodes');
	    //delete campaign
	    $this->db->where('post_id', $campaign_id);
	    $this->db->delete('fb_posts');
	}
	/**
    * update a campaign status to completed
    * @param $campaign_id integer, The id of the campaign
    * @return void
    */
	public function update_campaign_status($campaign_id = 0)
	{
		$this->db->where('post_id', $campaign_id);
        $this->db->where('post_status', POST_STATUS_PENDING);
        $pending_posts = $this->db->get('post_to_nodes')->num_rows();
        $campaign = array(
			'status' => CAMPAIGN_STATUS_INPROGRESS
		);
	    if($pending_posts == 0) //no pending post for this campaign update status to completed
			$campaign['status']	= CAMPAIGN_STATUS_COMPLETED;		
		$this->db->where('post_id', $campaign_id);
		$this->db->update('fb_posts', $campaign);
	}

	// get list of nodes for a campaign for edit campaign
	public function get_campaign_nodes($campaign_id = 0)
	{
		$this->db->select('*')->from('post_to_nodes');
        $this->db->where('post_to_nodes.post_id', $campaign_id);
        $this->db->order_by('post_to_nodes.post_to_nodes_id asc');
        return $this->db->get()->result();
	}

}


/* End of file post_model.php */
/* Location: ./application/models/post_model.php */
