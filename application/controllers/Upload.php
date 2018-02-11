<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: uls
 * Date: 11/02/18
 * Time: 21.51
 */
class Upload extends CI_Controller
{
    var $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encryption');
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
        //models connection back-end
        $this->load->model('M_upload', 'settings');
        //end models connection back-end
    }

    // redirect if needed, otherwise display the menu list
    public function index()
    {
        $this->data['title_site'] = 'tabbing upload';
        $this->data['users'] = $this->settings->get_users();
        //content view
        $this->data['content'] = $this->load->view('content/view-upload', $this->data, true);
        //end content view
        //main content
        $this->load->view('main/view-main', $this->data);
        //end main content
    }

    public function add() {
        if(!$this->input->is_ajax_request()) redirect(base_url('404'));

        $this->validate(); // validasi

        $users = $this->data_users();
        $document = $this->data_document();

        if ($this->form_validation->run() == TRUE) {
            $this->settings->save_users($users);
            $this->settings->save_document($document);
        }

        echo json_encode(array("status" => TRUE));
    }

    public function data_users(){
        $data = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('email')
        );

        return $data;
    }

    public function data_document(){
        if (empty($_FILES['document'])) {

        } else {
            $this->image_path = realpath(APPPATH . '../assets/upload-file');
            $this->image_path_url = base_url() . 'assets/upload-file';

            $replace = array(" ","&");
            $title = str_replace($replace,'_',$this->input->post('title')).'_'.'document'.'_';

            $key = $this->encryption->encrypt(time());

            $varkey = substr($key, 0, 10);

            $path = $_FILES['document']['name'];
            $newName = $title.time().'_'.$varkey.".".pathinfo($path, PATHINFO_EXTENSION);

            $config = array('allowed_types' => 'jpg|jpeg|JPG|JPEG', 'upload_path' => $this->image_path, 'overwrite' => TRUE, 'encrypt_name' => FALSE, 'file_name' => $newName);

            $this->upload->initialize($config);
            $this->upload->do_upload('document');
            // resize image
            $data_upload = $this->upload->data();

            $config_resize['image_library'] = 'gd2';
            $config_resize['create_thumb'] = FALSE;
            $config_resize['maintain_ratio'] = TRUE;
            $config_resize['source_image'] = $this->image_path.'/'.$data_upload['file_name'];
            $config_resize['new_image'] = $this->image_path.'/'.$data_upload['file_name'];
            $config_resize['height'] = 320;
            $config_resize['width'] = 480;
            $dim = (intval($data_upload["image_width"]) / intval($data_upload["image_height"])) - ($config_resize['width'] / $config_resize['height']);
            $config_resize['master_dim'] = ($dim > 0)? "height" : "width";
            $config_resize['quality'] = "75";

            //for 16:9 width is 640 and height is 360(check link 2 below)
            $this->load->library('image_lib');
            $this->image_lib->initialize($config_resize);
            $this->image_lib->resize();
            $this->image_lib->clear();

            $data["file_name_url"] = $this->image_path . '/' .  $data_upload['file_name'];

        }

        if (empty($data_upload['file_name'])) {
            $image='';
        } else {
            $image = $data_upload['file_name'];
        }

        $data = array(
            'users_id' => $this->settings->users_id(),
            'title' => $this->input->post('title'),
            'file' => $image
        );

        return $data;
    }

    public function validate(){
        $data = array();
        $data['error_string'] = array();
        $data['error_id'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $this->form_validation->set_rules('firstname', 'firstname', 'required|trim');
        $this->form_validation->set_rules('lastname', 'lastname', 'required|trim');
        $this->form_validation->set_rules('email', 'email', 'required|trim');
        $this->form_validation->set_rules('title', 'title', 'required|trim');
        $this->form_validation->set_rules('document', 'document', 'trim|callback_file_check|callback_file_size_3mb');
        $this->form_validation->run();

        if ((form_error('title') !== '')) {
            $data['inputerror'][] = 'title';
            $string = form_error('title');
            $data['error_id'][] = 'title';
            $result = str_replace(array('</p>', '<p>'), '', $string);
            $data['error_string'][] = $result;
            $data['status'] = FALSE;
        }

        if ((form_error('email') !== '')) {
            $data['inputerror'][] = 'email';
            $string = form_error('email');
            $data['error_id'][] = 'email';
            $result = str_replace(array('</p>', '<p>'), '', $string);
            $data['error_string'][] = $result;
            $data['status'] = FALSE;
        }

        if ((form_error('firstname') !== '')) {
            $data['inputerror'][] = 'firstname';
            $string = form_error('firstname');
            $data['error_id'][] = 'firstname';
            $result = str_replace(array('</p>', '<p>'), '', $string);
            $data['error_string'][] = $result;
            $data['status'] = FALSE;
        }

        if ((form_error('lastname') !== '')) {
            $data['inputerror'][] = 'lastname';
            $string = form_error('lastname');
            $data['error_id'][] = 'lastname';
            $result = str_replace(array('</p>', '<p>'), '', $string);
            $data['error_string'][] = $result;
            $data['status'] = FALSE;
        }

        $allowed = array('jpg', 'jpeg', 'JPG', 'JPEG');
        if (isset($_FILES['document'])) {
            $new = $_FILES['document']['name'];
            $ext = pathinfo($new, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed)) {
                $data['inputerror'][] = 'document';
                $data['error_id'][] = 'document';
                $string = 'JPG only';
                $result = str_replace(array('</p>', '<p>'), '', $string);
                $data['error_string'][] = $result;
                $data['status'] = FALSE;
            }
        }
        if ((form_error('document') !== '')) {
            $data['inputerror'][] = 'document';
            $data['error_id'][] = 'document';
            $string = form_error('document');
            $result = str_replace(array('</p>', '<p>'), '', $string);
            $data['error_string'][] = $result;
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function file_check($str){
        if(empty($_FILES['document']['type'])) {
            $this->form_validation->set_message('file_check', 'file is required');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function file_size_3mb(){
        $this->form_validation->set_message('file_size_3mb', 'file max is 3mb');
        if(!empty($_FILES['document']['name'])){
            if ($_FILES['document']['size'] >= 3024000) {
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }

}