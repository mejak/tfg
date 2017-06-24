<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * post Class
 * 
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  post
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Post extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('post_model');
    }
    /** default function of the controller */
    public function index()
    {
        redirect('post/create');
    }
    /** List All posts */
    public function post_list()
    {
        session_check();
        $data['page_title'] = $this->lang->line('post_list_title');
        $data['view'] = 'post_list';
        $this->load->view('template', $data);
    }
    /**
    * Create New campaign and target multiple nodes for the campaign
    */
    public function create()
    {
        session_check();
        $this->form_validation->set_rules('type', $this->lang->line('validation_post_type'), 'trim|required');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('create_post_title');
            $data['view'] = 'create_post';
            $this->load->view('template', $data);
        }else
        {
            //check user access to nodes
            header('Content-Type: application/json'); //set response type to be json
            //set image of the post
            create_folder(PATH_POST_IMAGES);
            if(isset($_POST['image_url']))
            {
                $image_url = $this->input->post('image_url', TRUE);
            }else if(isset($_POST['canvas_data']))
            {
                $image_name = Upload_fb_canvas();
                $image_url =  base_url().PATH_POST_IMAGES.$image_name;
            }else if(isset($_FILES['image']))
            {
                $path = $_FILES['image']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $config['upload_path'] = PATH_POST_IMAGES;
                $config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
                $config['max_size']    = '0';
                $config['file_name']    = uniqid().'.'.$ext;
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('image'))
                {
                    $ajax_response = array('type' => AJAX_RESPONSE_TYPE_ERROR, 'message' => get_alert_html($this->upload->display_errors(), ALERT_TYPE_ERROR));
                    echo json_encode($ajax_response);
                    exit();
                }
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
                $image_url =  base_url().PATH_POST_IMAGES.$config['file_name'];
            }else
            {
                $image_url = false;
            }
            $post_id = $this->post_model->insert($image_url);
            $this->post_model->insert_post_nodes($post_id);
            $this->publish_to_facebook($post_id);
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_campaign_created'), ALERT_TYPE_SUCCESS));
            $ajax_response = array('type' => AJAX_RESPONSE_TYPE_REDIRECT, 'message' => base_url().'index.php/post/post_list');
            echo json_encode($ajax_response);
            exit();
        }
    }

    public function repost_campaign($campaign_id = 0)
    {
        session_check();
        if(!$campaign_id) show_404();
        if(!is_numeric($campaign_id)) show_404();
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $campaign = $this->post_model->get_campaign($campaign_id, $user_id);
        if(!isset($campaign->post_id)) show_404();
        $data['page_title'] = $this->lang->line('repost_title');
        $data['view'] = 'repost';
        $this->load->view('template', $data);
    }
    /**
    * Edit campaign content only
    * @param $campaign_id integer, The id of the campaign
    */
    public function edit($campaign_id = 0)
    {
        session_check();
        if(!$campaign_id) show_404();
        if(!is_numeric($campaign_id)) show_404();
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $campaign = $this->post_model->get_campaign($campaign_id, $user_id);
        if(!isset($campaign->post_id)) show_404();
        $this->form_validation->set_rules('type', $this->lang->line('validation_post_type'), 'trim|required');
        $this->form_validation->set_message('required','%s '.$this->lang->line('validation_required'));
        if(!$this->form_validation->run())
        {
            $data['page_title'] = $this->lang->line('edit_post_title');
            $data['view'] = 'edit_post';
            $this->load->view('template', $data);
        }else
        {
            //check user access to nodes
            header('Content-Type: application/json'); //set response type to be json
            $this->post_model->update_campaign($campaign_id);
            $this->post_model->insert_post_nodes($campaign_id);
            $this->post_model->update_post_nodes($campaign_id);
            $this->update_to_facebook($campaign_id);
            $this->publish_to_facebook($campaign_id);
            $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_post_updated'), ALERT_TYPE_SUCCESS));
            $ajax_response = array('type' => AJAX_RESPONSE_TYPE_REDIRECT, 'message' => base_url().'index.php/post/post_list');
            echo json_encode($ajax_response);
            exit();
        }
    }
    
    /**
    * AJAX call to get stats(likes, comments, shares) of sigle facebook post
    * Post scheduled posts to facebook
    */
    public function stats($post_to_nodes_id = 0)
    {
        header('Content-Type: application/json'); //set response type to be json
        session_check();
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $post = $this->post_model->get_post($post_to_nodes_id, $user_id);
        $this->load->library('facebook/myfacebook');
        $response = $this->myfacebook->get_post_stats($post->post_fb_id, $post);
        if(isset($response['error']))
        {
            $ajax_response = array('type' => AJAX_RESPONSE_TYPE_ERROR, 'message' => get_alert_html($response['error']['type'].' : '.$response['error']['message'].' Error Code: '.$response['error']['code'], ALERT_TYPE_ERROR));
            echo json_encode($ajax_response);
            exit();
        }else
        {
            if(isset($response['likes']))
                $likes = $response['likes']->getTotalCount();
            else 
                $likes = 0;
            if(isset($response['comments']))
                $comments = $response['comments']->getTotalCount();
            else 
                $comments = 0;
            if(isset($response['sharedposts']))
                $shares = $response['sharedposts']->count();
            else 
                $shares = 0;
            $this->post_model->update_post_stats($post->post_fb_id, $likes, $comments, $shares);
            $stats = array(
                'likes' => $likes,
                'comments' => $comments,
                'shares' =>  $shares
            );
            echo json_encode($stats);
            exit();
        }
    }
    /**
    * Delete a facebook post from database and facebook(optional)
    * @param $post_to_node_id integer, The database id of the post
    * @param $del_from_facebook boolean, The optional parameter to delete post from facebook
    * @param $ref_page boolean, after delete redirct to this page
    */
    public function delete($post_to_node_id = 0, $del_from_facebook =  false, $ref_page = false)
    {
        session_check();
        if(!$post_to_node_id) show_404();
        if(!is_numeric($post_to_node_id)) show_404();
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $post = $this->post_model->get_post($post_to_node_id, $user_id);
        if(!isset($post->post_to_nodes_id)) show_404();
        $from_fb = "";
        if($del_from_facebook && $post->post_status == POST_STATUS_POSTED)
        {
            $this->load->library('facebook/myfacebook');
            $response = $this->myfacebook->delete($post);
            if(isset($response['error']) OR (isset($response['success']) AND $response['success'] === FALSE))
            {
                $this->session->set_flashdata('alert', get_alert_html($response['error']['type'].' : '.$response['error']['message'], ALERT_TYPE_ERROR));
                if($ref_page)
                    redirect('post/detail/'.$post->post_id);
                else
                    redirect('page/index/'.$post->page_id);
            }else if($response === true OR (isset($response['success']) AND $response['success'] === TRUE))
                $from_fb = $this->lang->line('success_post_deleted_facebook');
        }

        $this->post_model->delete($post_to_node_id);
        $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_post_deleted').$from_fb, ALERT_TYPE_SUCCESS));
        if($ref_page)
            redirect('post/detail/'.$post->post_id);
        else
            redirect('page/index/'.$post->page_id);
    }
    /**
    * get campaign's all its post from database
    * @param $campaign_id integer, The id of the campaign
    */
    public function detail($campaign_id = 0)
    {
        session_check();
        if(!$campaign_id) show_404();
        if(!is_numeric($campaign_id)) show_404();
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $campaign = $this->post_model->get_campaign($campaign_id, $user_id);
        if(!isset($campaign->post_id)) show_404();
        $data['campaign'] =  $campaign;
        $data['page_title'] = $this->lang->line('campaign_post_list_title');
        $data['view'] = 'campaign_post_list';
        $this->load->view('template', $data);
    }
    /**
    * delete a campaign and all its posts from database
    * @param $campaign_id integer, The id of the campaign
    */
    public function delete_campaign($campaign_id = 0, $del_from_facebook =  false)
    {
        session_check();
        if(!$campaign_id) show_404();
        if(!is_numeric($campaign_id)) show_404();
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $campaign = $this->post_model->get_campaign($campaign_id, $user_id);
        if(!isset($campaign->post_id)) show_404();
        if($del_from_facebook)
        {
            $posts = $this->post_model->get_published_posts($campaign_id);
            $this->load->library('facebook/myfacebook');
            $batch = array();

            for($i = 0; $i < count($posts); $i++)
            {
                $batch[] = $posts[$i];
                if( ( ( isset($posts[$i + 1]) && $posts[$i + 1]->profile_app != $posts[$i]->profile_app) ||
                      ($i + 1 == count($posts) ) || ( $i + 1 ) % 50 == 0) && !empty($batch))
                {
                    $result = $this->myfacebook->delete_batch($batch);
                    $batch = [];
                } //batch section
            } // post loop ends
        }
        $this->post_model->delete_campaign($campaign_id);
        $this->session->set_flashdata('alert', get_alert_html($this->lang->line('success_campaign_deleted'), ALERT_TYPE_SUCCESS));
        if($campaign->user_id != $this->session->userdata('user_id'))
            redirect('users/profile/'.$campaign->user_id);
        redirect('post/post_list');
    }

    /**
    * publish a post to Facebook
    * @param $post_id integer, The id of the post
    */
    public function publish_to_facebook($post_id = 0)
    {
        $post = $this->post_model->get_campaign($post_id);
        $posts = $this->post_model->get_pending_posts($post_id);
        if($post->post_type == POST_TYPE_VIDEO) // video post get video URL
        {
            $response = get_video_download_url($post->link_url);
            if($response['type'] == true)
                $video_url = $response['message'];
            else if($response['type'] == false && isset($response['message']) ){ // invalid video url
                $response['error'] = strip_tags($response['message']);
                unset($response['type']);
                unset($response['message']);
                foreach ($posts as $key => $item) 
                    $this->post_model->update_post($item->page_fb_id, $item->post_to_nodes_id, $response);
                $this->post_model->update_campaign_status($post->post_id);
                return ; 
            }
        }
        $schedule_post = $this->input->post('schedule_post');
        $this->load->library('facebook/myfacebook');
        $batch = array();

        for($i = 0; $i < count($posts); $i++)
        {
            if($this->config->item('spintax'))
            {
                $posts[$i]->post_message =     spintax($posts[$i]->post_message);
                $posts[$i]->link_url =         spintax($posts[$i]->link_url);
                $posts[$i]->link_title =       spintax($posts[$i]->link_title);
                $posts[$i]->link_description = spintax($posts[$i]->link_description);
                $posts[$i]->link_caption =     spintax($posts[$i]->link_caption);
                $posts[$i]->image_url =        spintax($posts[$i]->image_url);
            }
            if($post->post_type == POST_TYPE_VIDEO && isset($video_url))
                $posts[$i]->link_url = $video_url;
            $batch[] = $posts[$i];
            if( ( ( isset($posts[$i + 1]) && $posts[$i + 1]->profile_app != $posts[$i]->profile_app) ||
                  ($i + 1 == count($posts) ) || ( $i + 1 ) % 50 == 0) && !empty($batch))
            {
                $result = $this->myfacebook->publish_batch($post->post_type, $batch);
                if ($result->isError()) 
                {
                    $e = $result->getThrownException();
                    $this->session->set_flashdata('alert', get_alert_html($e->getResponse(), ALERT_TYPE_ERROR));
                    //empty batch for next request
                    $batch = [];
                    continue;
                }
                $responses = $result->getResponses();
                foreach ($responses as $key => $obj) 
                {
                    $ids = explode('_', $key);
                    $response = $obj->getDecodedBody();
                    $this->post_model->update_post($ids[0], $ids[1], $response);
                }
                $batch = [];
            } //batch section
        } // post loop ends
        $this->post_model->update_campaign_status($post_id);
    }
    /**
    * publish a post to Facebook
    * @param $post_id integer, The id of the post
    */
    public function update_to_facebook($post_id = 0)
    {
        $post = $this->post_model->get_campaign($post_id);
        $posts = $this->post_model->get_published_posts($post_id);

        $this->load->library('facebook/myfacebook');
        $batch = array();

        for($i = 0; $i < count($posts); $i++)
        {
            if($this->config->item('spintax'))
            {
                $posts[$i]->post_message =     spintax($posts[$i]->post_message);
                $posts[$i]->link_title =       spintax($posts[$i]->link_title);
                $posts[$i]->link_description = spintax($posts[$i]->link_description);
            }
            $batch[] = $posts[$i];
            if( ( ( isset($posts[$i + 1]) && $posts[$i + 1]->profile_app != $posts[$i]->profile_app) ||
                  ($i + 1 == count($posts) ) || ( $i + 1 ) % 50 == 0) && !empty($batch))
            {
                $result = $this->myfacebook->update_batch($post->post_type, $batch);
                $batch = [];
            } //batch section
        } // post loop ends
    }

    /**
    * post Insights
    */
    public function insights($post_to_node_id = 0)
    {
        session_check();
        if(!$post_to_node_id) show_404();
        if(!is_numeric($post_to_node_id)) show_404();
        $user_id = $this->session->userdata('admin_login') === TRUE ? 0 : $this->session->userdata('user_id');
        $post = $this->post_model->get_post($post_to_node_id, $user_id);
        if(!isset($post->post_to_nodes_id)) show_404();
        $user_zone = $this->session->userdata('time_zone');
        $user_zone = new DateTimeZone($user_zone);
        $schedule_time = new DateTime($post->post_datetime);
        $schedule_time->setTimezone($user_zone);
        $data['post'] = $post;
        $data['schedule_time'] = $schedule_time->format('Y-m-d');
        $data['post_app_id'] = get_app_id($post->profile_app);
        $data['page_title'] = $this->lang->line('post_insights_title').' - '.$post->post_name.' - '.$post->page_name;
        $data['view'] = 'insights/post_insights_main';    
        $this->load->view('template', $data);
    }  
   
}
/* End of file post.php */
/* Location: ./application/controllers/post.php */