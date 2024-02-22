<?php
// controllers/ImageController.php

class ImageController extends BaseController {
    
    public function upload() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1000; // max size in KB
        $config['encrypt_name'] = TRUE; // encrypts filename
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            //$this->load->view('upload_form', $error);
        } else {
            $data = $this->upload->data();
            $image_data = array(
                'filename' => $data['file_name'],
                'filepath' => $data['file_path']
            );
            $this->db->insert('images', $image_data);
            $this->load->view('upload_success', $data);
        }
    }
}
?>
