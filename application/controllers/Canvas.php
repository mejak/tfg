<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Canvas Class
 *
 * @package   PHP_FMT
 * @subpackage  Controllers
 * @category  Canvas
 * @author    alrazamc
 * @link    http://phpfm.jcatpk.com
 */
class Canvas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    /** default function of the controller */
    public function index()
    {
        redirect('home/index');
    }
    
    /**
    * AJAX call to fetch image from URL via CURL
    * fetch image from web and save as image on local server
    * Send back the image Path or URL
    */
    public function google_image()
    {
        session_check();
        $image_url = $this->input->post('image_url', TRUE);
        $image = curl_fetch_image($image_url);
        $dest_image_name = uniqid();
        $dest_image_name .= '.png'; //png extension for canves images
        create_folder(PATH_CANVAS_GOOGLE_IMAGES);
        $fp = fopen(PATH_CANVAS_GOOGLE_IMAGES.$dest_image_name, 'wb');
        fwrite( $fp, $image);
        fclose( $fp );
        $return_url = base_url().substr(PATH_CANVAS_GOOGLE_IMAGES, 2).$dest_image_name;
        $ajax_response = array('type' => AJAX_RESPONSE_TYPE_SUCCESS, 'message' => $return_url);
        echo json_encode($ajax_response);
        exit();
    }

    /**
    * upload an image from local computer to server to use in Canvas
    */
    public function upload_canvas_image()
    {
        session_check();
        $path = $_FILES['canvas_img_file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $config['upload_path'] = PATH_CANVAS_GOOGLE_IMAGES;
        $config['allowed_types'] = 'gif|jpg|png|bmp|GIF|JPEG|jpeg|JPG|PNG';
        $config['max_size']    = '0';
        $config['file_name']    = uniqid().'.'.$ext;
        $this->load->library('upload', $config);
        create_folder(PATH_CANVAS_GOOGLE_IMAGES);
        if(!$this->upload->do_upload('canvas_img_file'))
        {
            $ajax_response = array('type' => AJAX_RESPONSE_TYPE_ERROR, 'message' => $this->upload->display_errors());
             echo json_encode($ajax_response);
             exit();
        }
        $upload_data = $this->upload->data();
        $image_name = $upload_data['file_name'];
        $image_url =  base_url().PATH_CANVAS_GOOGLE_IMAGES.$config['file_name'];
        $ajax_response = array('type' => AJAX_RESPONSE_TYPE_SUCCESS, 'message' => $image_url);
        echo json_encode($ajax_response);
        exit();
    }

}

/* End of file canvas.php */
/* Location: ./application/controllers/canvas.php */