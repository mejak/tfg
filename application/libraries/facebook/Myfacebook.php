<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Facebook Class
 *
 * @package   PHP_FMT
 * @subpackage  Libraries
 * @category  Facebook
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
 
// Autoload the required files
require_once( APPPATH . 'libraries/facebook/Facebook/autoload.php' );
 

 
 
class Myfacebook {
    var $ci;
    var $fb;
    var $helper;
    var $access_token;
    var $permissions;
    var $api_version ;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->permissions = $this->ci->config->item('permissions', 'facebook');
    }
    /**
    * init Facebook SDK
    * @param $app_id string, facebook id string of app
    * @param $app_secret string, facebook secret of the app
    * @return void
    */
    public function init($app_id, $app_secret, $version ='')
    {   
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $config = array(
            'app_id' => $app_id,
          'app_secret' => $app_secret
        );
        if($version)
            $config['default_graph_version'] = $version;
        $this->fb = new Facebook\Facebook($config);
    }
    /**
    * get Login url for redirect to Facebook
    * @return string , Login URL
    */
    public function get_login_url($redirect_url)
    {
        if($this->fb)
        {
            $this->helper = $this->fb->getRedirectLoginHelper();
            $loginUrl = $this->helper->getLoginUrl($redirect_url, $this->permissions);
            return $loginUrl;
        }
    }
    /**
    * get access token from Facebook for login into script
    * @return boolean
    */
    public function get_access_token()
    {
        if($this->fb)
        {
            $this->helper = $this->fb->getRedirectLoginHelper();

            try {
              $accessToken = $this->helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              // When Graph returns an error
              return $e->getMessage();
              
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              // When validation fails or other local issues
              return $e->getMessage();
            }

            if(isset($accessToken))
            {
                $this->access_token = $accessToken->getValue();
                return true;
            }
        }
    }

    /**
    * get user profile for login into script
    * @return string , User data
    */
    public function get_user_profile()
    {
        if($this->fb && $this->access_token)
        {
            try {
              // Returns a `Facebook\FacebookResponse` object
              $response = $this->fb->get('/me?fields=id,name, first_name, email,picture.type(large)', $this->access_token);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              return $e->getMessage();
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                return  $e->getMessage();
            }

            $user = $response->getGraphUser();
            return $user;
        }
    }

    /**
    * get facebook app details to validate a app before adding it to database
    * @return object
    */
    public function get_app_details()
    {
        $this->set_token($this->fb->getApp()->getAccessToken()->getValue());
        return $this->get_request('/app');
    }

    /**
    * return access token
    * @return string
    */
    public function get_token()
    {
        return $this->access_token;
    }

    /**
    * set access token
    * @return void
    */
    public function set_token($token)
    {
        return $this->access_token = $token;
    }
    /**
    * make get request to graph api
    * @return array
    */
    public function get_request($edge)
    {
        try
        {
          $response = $this->fb->get($edge, $this->access_token);
          return $response->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        }
    }
    /**
    * make post request to graph api
    * @return array
    */
    public function post_request($edge, $post_params)
    {
        try
        {
          $response = $this->fb->post($edge, $post_params, $this->access_token);
          return $response->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        }
    }
    /**
    * debug access token
    * @return array
    */
    public function debug_token($token = '')
    {
        return $this->get_request('/debug_token?input_token='.$token);
    }


    /**
    * Format Facebook SDK exception object
    */
    public function format_facebook_SDK_exception($e)
    {
        $err['error']['message'] = $e->getMessage();
        $err['error']['code'] = $e->getCode();
        $err['error']['type'] = 'FacebookSDKException';
        return  $err;
    }
    /**
    * get mange/owned pages from facebook account 
    * @param $limit integer, application limit to retrive n records from facebooks
    * @return array api call response
    */
    public function get_manage_pages($before = '', $after = '')
    {
        $q = '&';
        if($before)
            $q .= "before=$before&";
        if($after)
            $q .= "after=$after";
        try
        {
          $response = $this->fb->get('/me/accounts?fields=access_token,category,name,id,perms,fan_count,picture.type(large)&limit='.PAGE_LIMIT.$q, $this->access_token);
          return $response->getGraphEdge();
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        }
    }
    /**
    * get user profile data
    * @return array api call response
    */
    public function get_profile()
    {
        try
        {
          $response = $this->fb->get('/me?fields=id,name', $this->access_token);
          return $response->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        }  
    }
    /**
    * set schedule params of post
    * @param $post_data object, data of scheduled post
    * @param $params array, params of post
    * @return params array
    */
    public function set_schedule_params($post_data, $params)
    {
        if(!is_array($params))
            $params = array();
        if(!($post_data->post_schedule)){
            //$params['published'] = true;
            return $params;
        }
        $schedule_time = $post_data->post_datetime;
        $schedule_stamp = strtotime($schedule_time);

        $user_zone = $this->ci->session->userdata('time_zone');
        $user_zone = new DateTimeZone($user_zone);
        $schedule_time = new DateTime($schedule_time);
        $schedule_time->setTimezone($user_zone);

        if( $schedule_stamp > time() )
        {
            $params['scheduled_publish_time'] = $schedule_time->getTimestamp();
            if(!($post_data->post_fb_id !='' && $post_data->post_type == POST_TYPE_VIDEO))
            $params['published'] = false;
        }else if( $schedule_stamp < time() )
        {
            $params['backdated_time'] = $schedule_time->getTimestamp();
        }
        return $params;      
    }

    /**
    * prepare post request
    * @param $post_data object, data of scheduled post
    * @return Post Request object
    */
    public function status_request($post_data)
    {
        $edge = '/'.$post_data->page_fb_id.'/feed';
        $params = array(
            'message' => $post_data->post_message
        );
        $params = $this->set_schedule_params($post_data, $params);
        return $this->fb->request('POST', $edge, $params, $post_data->page_token);        
    }
   /**
    * prepare post request
    * @param $post_data object, data of scheduled post
    * @return Post Request object
    */
    public function link_request($post_data)
    {
        $edge = '/'.$post_data->page_fb_id.'/feed';
        $params = array(
            'link' => $post_data->link_url
        );
        if(!empty($post_data->post_message))
            $params['message'] = $post_data->post_message;
        if(!empty($post_data->link_title))
            $params['name'] = $post_data->link_title;
        if(!empty($post_data->link_description))
            $params['description'] = $post_data->link_description;
        if(!empty($post_data->link_caption))
            $params['caption'] = $post_data->link_caption;
        if(!empty($post_data->image_url))
            $params['picture'] = $post_data->image_url;
        $params = $this->set_schedule_params($post_data, $params);
        return $this->fb->request('POST', $edge, $params, $post_data->page_token);        
    }
    /**
    * prepare post request
    * @param $post_data object, data of scheduled post
    * @return Post Request object
    */
    public function photo_request($post_data)
    {
        $edge = '/'.$post_data->page_fb_id.'/photos';
        $params = array(
            'url' => $post_data->image_url
        );
        if(!empty($post_data->post_message))
            $params['message'] = $post_data->post_message;
        $params = $this->set_schedule_params($post_data, $params);
        return $this->fb->request('POST', $edge, $params, $post_data->page_token);        
    }
    /**
    * prepare post request
    * @param $post_data object, data of scheduled post
    * @return Post Request object
    */
    public function video_request($post_data)
    {
        $edge = '/'.$post_data->page_fb_id.'/videos';
        $params = array(
            'file_url' => $post_data->link_url
        );
        if(!empty($post_data->link_title))
            $params['title'] = $post_data->link_title;
        if(!empty($post_data->link_description))
            $params['description'] = $post_data->link_description;
        $params = $this->set_schedule_params($post_data, $params);
        return $this->fb->request('POST', $edge, $params, $post_data->page_token);        
    }

    /**
    * get a facebook post fresh insights from facebook i.e. likes, coments, shares
    * @param $post_id string, Facebook id string of post
    * @param $app object, app data which was used to post this post
    * @return array api call response
    */
    public function get_post_stats($post_id, $app)
    {
        $this->init(get_app_id($app->profile_app), get_app_secret($app->profile_app));
        $this->access_token = $app->profile_token;
        //if($app->post_type == POST_TYPE_VIDEO)
            $edge = '/'.$post_id.'/?fields=likes.limit(1).summary(true),comments.limit(1).summary(true),sharedposts.limit(10000000)';
        //else
          //  $edge = '/'.$post_id.'/?fields=likes.limit(1).summary(true),comments.limit(1).summary(true),shares';
        try
        {
          $response = $this->fb->get($edge, $this->access_token);
          return $response->getGraphNode();
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        } 
        //$this->get_request($edge);
    }
    /**
    * delete a post from facebook
    * @param $post_id string, Facebook id string of post
    * @param $post object, post data which also includ post facebook app information
    * @return array api call response
    */
    public function delete($post)
    {
        $this->init(get_app_id($post->profile_app), get_app_secret($post->profile_app));
        $this->access_token = $post->page_token;
        $edge = '/'.$post->post_fb_id;
        try
        {
          $response = $this->fb->delete($edge, array(), $this->access_token);
          return $response->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        }
    }

    /**
    * publish a batch of posts
    * @param $batch array, array of post
    * @return array api call response
    */
    public function publish_batch($post_type, $batch)
    {
        $this->init( get_app_id($batch[0]->profile_app), get_app_secret($batch[0]->profile_app) );
        $this->fb->setDefaultAccessToken($batch[0]->profile_token);
        $request_batch = array();
        foreach ($batch as $post) 
        {
            if($post_type == POST_TYPE_STATUS)
                $request_batch[$post->page_fb_id.'_'.$post->post_to_nodes_id] = $this->status_request($post);
            else if ($post_type == POST_TYPE_LINK)
                $request_batch[$post->page_fb_id.'_'.$post->post_to_nodes_id] = $this->link_request($post);
            else if ($post_type == POST_TYPE_PHOTO)
                $request_batch[$post->page_fb_id.'_'.$post->post_to_nodes_id] = $this->photo_request($post);
            else if ($post_type == POST_TYPE_VIDEO)
                $request_batch[$post->page_fb_id.'_'.$post->post_to_nodes_id] = $this->video_request($post);
        }
        try
        {
          $responses = $this->fb->sendBatchRequest($request_batch);
          return $responses;
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        } 
    }

    /**
    * publish a batch of posts
    * @param $batch array, array of post
    * @return array api call response
    */
    public function update_batch($post_type, $batch)
    {
        $this->init( get_app_id($batch[0]->profile_app), get_app_secret($batch[0]->profile_app) );
        $this->fb->setDefaultAccessToken($batch[0]->profile_token);
        $request_batch = array();
        foreach ($batch as $post) 
        {
            $params = array();
            $edge = '/'.$post->post_fb_id;
            if($post_type == POST_TYPE_VIDEO)
            {
                $params['name'] = $post->link_title;
                $params['description'] = $post->link_description;
            }else //status, link, photo
            {
                $params['message'] = $post->post_message;
            }
            if($post_type == POST_TYPE_PHOTO && strpos($post->post_fb_id, '_') === FALSE)
                $edge = '/'.$post->page_fb_id . '_' .$post->post_fb_id;
            $params = $this->set_schedule_params($post, $params);

            $request_batch[$post->page_fb_id.'_'.$post->post_to_nodes_id] = $this->fb->request('POST', $edge, $params, $post->page_token);
        }
        try
        {
          $responses = $this->fb->sendBatchRequest($request_batch);
          return $responses;
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        } 
    }

    /**
    * delete a batch of posts
    * @param $batch array, array of post
    * @return array api call response
    */
    public function delete_batch($batch)
    {
        $this->init( get_app_id($batch[0]->profile_app), get_app_secret($batch[0]->profile_app) );
        $this->fb->setDefaultAccessToken($batch[0]->profile_token);
        $request_batch = array();
        foreach ($batch as $post) 
        {
            $request_batch[$post->page_fb_id.'_'.$post->post_to_nodes_id] = $this->fb->request('DELETE', '/'.$post->post_fb_id, array(), $post->page_token);
        }
        try
        {
          $responses = $this->fb->sendBatchRequest($request_batch);
          return $responses;
        } catch(Facebook\Exceptions\FacebookResponseException $e) 
        {
            return $e->getResponse()->getGraphObject()->asArray();
        } catch(Facebook\Exceptions\FacebookSDKException $e)
        {
            return  $this->format_facebook_SDK_exception($e);
        } 
    }
       

}