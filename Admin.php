<?php
/**
 * Created by PhpStorm.
 * User: SR
 * Date: 11/24/2016
 * Time: 2:32 PM
 */

class Admin_dashboard extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Blogger', 'b_model', true);       
    }


    public function create_multiple_image()
    {
        $this->load->library('upload');        
        $count_files = array_filter($_FILES['slide_img']['size']);        
        for($i=0; $i<$count_files; $i++)
       {
           $_FILES['slide_img']['name'] = $files['slide_img']['name'][$i];
           $_FILES['slide_img']['type'] = $files['slide_img']['type'][$i];
           $_FILES['slide_img']['tmp_name'] = $files['slide_img']['tmp_name'][$i];
           $_FILES['slide_img']['error'] = $files['slide_img']['error'][$i];
           $_FILES['slide_img']['size'] = $files['slide_img']['size'][$i];

           $this->upload->initialize($this->set_upload_options());
           $this->upload->do_upload();
       }

    }

} 
