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
        $this->load->library('upload'); // library init
        // next we pass the upload path for the images
        $config['upload_path'] = './temp_pic/';
        // also, we make sure we allow only certain type of images
        $config['allowed_types'] = 'gif|jpg|png';
        
        $count_files = array_filter($_FILES['slide_img']['size']); // count all file input in total
        
        $files = $_FILES['slide_img'];
        
        // first make sure that there is no error in uploading the files
        for($i=0;$i<$count_files;$i++)
        {
            if($_FILES['slide_img']['error'][$i] != 0) 
                $error = array('error_file' => $this->upload->display_errors());
                $this->session->set_flashdata('error',$error['error_file']);
                redirect("/");                
        }
        
        for($i=0; $i<$count_files; $i++) // loop for each file indexing with properties
       {
           $_FILES['slide_img']['name'] = $files['slide_img']['name'][$i];
           $_FILES['slide_img']['type'] = $files['slide_img']['type'][$i];
           $_FILES['slide_img']['tmp_name'] = $files['slide_img']['tmp_name'][$i];
           $_FILES['slide_img']['error'] = $files['slide_img']['error'][$i];
           $_FILES['slide_img']['size'] = $files['slide_img']['size'][$i];
           
           $this->upload->initialize($config); // initial config

           if ($this->upload->do_upload('slide_img'))
            {
                $up_image = array('uploads' => $this->upload->data());
                
                $image_data_source = "slider_pictures/" . $up_image['uploads']['raw_name'] . '_thumb' . $up_image['uploads']['file_ext'];                    
                $data['pro_pic'] = $image_data_source;                
                $this->dev_m->save_product_pic($data);
                
                $config['image_library'] = 'gd2';
                $config['source_image'] = "./temp_pic/" . $up_image['uploads']['file_name'];
                $config['new_image'] = './slider_pictures/';
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 800;
                $config['height'] = 600;    
                $this->load->library('image_lib');
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            }
            else
            {
                $data['upload_errors'][$i] = $this->upload->display_errors();
            }
       }

    }

} 
