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
        $email = $this->session->userdata('email');
        if(!$email){
            redirect("home");
        }
    }


    public function index(){
        $data = array();
        $data['title'] = "Home";
        $data['mid_content'] = true;
        $data['blogs'] = $this->b_model->select_blogs();
        $data['home_content'] = $this->load->view('home', $data, true);
        $this->load->view('adminpage', $data);
    }

    public function bloger_list()
    {
        $data = array();
        $data['mid_content'] = true;
        $data['title'] = "Blogger List";
        $data['bloggers'] = $this->b_model->select_all_bloggers();
        $data['home_content'] = $this->load->view('blogger_page', $data, true);
        $this->load->view('adminpage', $data);
    }

    public function blogger_info($id)
    {
        $data = array();
        $data['mid_content'] = true;
        $data['title'] = "Blogger Info";
        $data['info'] = $this->b_model->select_blogger_info($id);
        $data['home_content'] = $this->load->view('blogger_info', $data, true);
        $this->load->view('adminpage', $data);


//
//        echo "<pre>";
//        print_r($data['info']);
    }

    public function create_new_blog()
    {
        $data = array();
        $data['mid_content'] = true;
        $data['title'] = "Create Blog";
        $data['home_content'] = $this->load->view('new_blog', '', true);
        $this->load->view('adminpage', $data);
    }

    public function new_blog_up()
    {

//        pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url().'admin_dashboard/select_all_blogs/';
        $count = $this->b_model->count_all_blogs();
        $config['total_rows'] = $count;
        $config['per_page'] = 3;
        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $data = array();
//        $data['blogs'] = $this->b_model->select_all_blogs($config['per_page'], $this->uri->segment(3));
//        pagination end


//        step1
        echo "<pre>";
        $file = $this->input->post('image', true);
        print_r($this->input->post(null, true));
        print_r($_FILES['image']);
        print_r($file);

//        step2
        $this->load->library('upload');
        $config['upload_path'] = './temp/';
        $config['allowed_types'] = 'png|jpg|jpeg';
//        $config['max_size'] = '2000';
//        $config['file_name'] = 'file_name';
//        $config['max_width'] = '600';
//        $config['min_width'] = '50';
//        $config['max_height'] = '600';
//        $config['min_height'] = '50';
//        $error = '';
//        $udata = '';
        $this->upload->initialize($config);


        if (!$this->upload->do_upload('image')) {
//            $error = array('error' => $this->upload->display_errors());
//            $this->session->set_flashdata('error',$error['error']);
//            redirect("admin_dashboard/create_new_blog");
        }else {
            array('upload_data' => $this->upload->data());

        }

//        step3

        $config['image_library'] = 'gd2';
        $config['source_image'] = $_FILES['image']['tmp_name'];
        $config['new_image'] = './blog_image/';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = false;
        $config['width'] = 200;
        $config['height'] = 200;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }


    public function create_new_blog_up()
    {
        $data = array();
        $data['title'] = $this->input->post('title', true);
        $data['video'] = $this->input->post('blog_video', true);
        $data['description'] = $this->input->post('blog_desc', true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Blog Title', 'required|min_length[10]|max_length[200]|is_unique[blogs.title]');
        $this->form_validation->set_rules('blog_desc', 'Blog Description', 'required|min_length[40]');
        if ($this->form_validation->run() == FALSE)
        {
            $data = array();
            $data['mid_content'] = true;
            $data['title'] = "New Blog";
            $data['home_content'] = $this->load->view('new_blog', '', true);
            $this->load->view('adminpage', $data);
        }
        else
        {
        $this->load->library('upload');
        $config['upload_path'] = './temporary/';
        $config['allowed_types'] = 'jpg|bmp|gif|jpeg';
        $config['max_size'] = '2000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';
//        $error = '';
        $udata = '';
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image')) {
            $error = array('error_file' => $this->upload->display_errors());
            $this->session->set_flashdata('error',$error['error_file']);
            redirect("admin_dashboard/create_new_blog");
        }else {
//            array('upload_data' => $this->upload->data());

            $udata = array('upload_data' => $this->upload->data());

//            echo "<pre>";
//            print_r($udata['upload_data']['file_ext']);
//            exit;
            $thumb_data_source = "temporary/" . $udata['upload_data']['file_name'];
            $image_data_source = "company_logo/" . $udata['upload_data']['raw_name'] . '_thumb' . $udata['upload_data']['file_ext'];
            $data['image_path'] = $image_data_source;
            $data['author'] = $this->session->userdata('email');
            $data['created_at'] = date("Y:m:d H:i:s");

            $fconfig['image_library'] = 'gd2';
            $fconfig['source_image'] = $thumb_data_source;
            $fconfig['new_image'] = './company_logo/';
            $fconfig['create_thumb'] = TRUE;
            $fconfig['maintain_ratio'] = false;
            $fconfig['width'] = 1200;
            $fconfig['height'] = 750;
            $this->load->library('image_lib', $fconfig);
            $this->image_lib->resize();

            $this->b_model->insert_new_blog($data);
            $this->session->set_flashdata('success',"Your Blog successfully uploaded!");
            redirect("admin_dashboard/create_new_blog");
        }

        }


    }

    public function select_all_blogs()
    {

//        start pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url().'my-blogs-all';
        $count = $this->b_model->count_all_blogs();
        $config['total_rows'] = $count;
        $config['per_page'] = 2;

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['blogs'] = $this->b_model->select_all_blogs($config['per_page'], $this->uri->segment(2));

        $data['mid_content'] = true;
        $data['title'] = "My Blogs";
        $data['bloggers'] = $this->b_model->select_all_bloggers();
        $data['home_content'] = $this->load->view('all_blog_page', $data, true);
        $this->load->view('adminpage', $data);
    }

    public function set_blog_disable($id)
    {
        $this->b_model->set_blog_inactive($id);
        redirect("admin_dashboard/select_all_blogs");
    }

    public function set_blog_enable($id)
    {
        $this->b_model->set_blog_active($id);
        redirect("admin_dashboard/select_all_blogs");
    }

    public function get_all_blogs_blogger($email)
    {
        $data['mid_content'] = true;
        $data['title'] = "My Blogs";
        $data['blogs'] = $this->b_model->select_blogs_all_user($email);
        $data['home_content'] = $this->load->view('user_blogs', $data, true);
        $this->load->view('adminpage', $data);
    }

    public function edit_this_blog($b_id)
    {
        $data = array();
        $data['mid_content'] = true;
        $data['title'] = "Edit Blog";
        $data['blog'] = $this->b_model->select_blog($b_id);
        $data['home_content'] = $this->load->view('edit_blog', $data, true);
        $this->load->view('adminpage', $data);
    }

    public function update_blog()
    {
        $data = array();
        $data['title'] = $this->input->post('title', true);
        $data['description'] = $this->input->post('blog_desc', true);
        $id = $this->input->post('b_id', true);
        $this->b_model->update_blog_info($id, $data);

        $this->session->set_flashdata('success',"Your Blog successfully Updated!");
        redirect("my-blogs-all");

    }

    public function edit_blog_image($b_id)
    {
        $data = array();
        $data['mid_content'] = true;
        $data['title'] = "Edit Blog";
        $data['blog'] = $this->b_model->select_blog($b_id);
        $data['home_content'] = $this->load->view('edit_blog_image', $data, true);
        $this->load->view('adminpage', $data);
    }

    public function update_blog_image()
    {
        $id = $this->input->post('b_id', true);
        $blog = $this->b_model->select_blog($id);

        $this->load->library('upload');
        $config['upload_path'] = './temporary/';
        $config['allowed_types'] = 'jpg|bmp|gif|jpeg';
        $config['max_size'] = '2000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1024';
        $udata = '';
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image')) {
            $error = array('error_file' => $this->upload->display_errors());
            $this->session->set_flashdata('error',$error['error_file']);
            redirect("admin_dashboard/edit_blog_image/$id");
        }else {

            $udata = array('upload_data' => $this->upload->data());
            $thumb_data_source = "temporary/" . $udata['upload_data']['file_name'];
            $image_data_source = "company_logo/" . $udata['upload_data']['raw_name'] . '_thumb' . $udata['upload_data']['file_ext'];
            $data['image_path'] = $image_data_source;

            $fconfig['image_library'] = 'gd2';
            $fconfig['source_image'] = $thumb_data_source;
            $fconfig['new_image'] = './company_logo/';
            $fconfig['create_thumb'] = TRUE;
            $fconfig['maintain_ratio'] = false;
            $fconfig['width'] = 1200;
            $fconfig['height'] = 750;
            $this->load->library('image_lib', $fconfig);
            $this->image_lib->resize();

            unlink($blog->image_path);

            $this->b_model->update_blog_info($id, $data);
            $this->session->set_flashdata('success',"Your Blog successfully uploaded!");
            redirect("edit-this-blog/$id");
        }
    }

    public function multiple_slider()
    {
        $data = array();
        $data['mid_content'] = true;
        $data['title'] = "Slider Add";
        $data['home_content'] = $this->load->view('new_slider', '', true);
        $this->load->view('adminpage', $data);
    }

    public function create_multiple_image()
    {
        $this->load->library('upload');
        $img = $this->input->post('slide_img', true);
        $key = array_search('', $_FILES['slide_img']['name']);
        $number_of_files = sizeof($_FILES['slide_img']['tmp_name']);

        $files = array_filter($_FILES['slide_img']['size']);
//        echo $number_of_files;
        echo "<pre>";
        echo count($files);
//        print_r(count($_FILES['slide_img']['size']));
//        print_r($key);
        exit;
//        for($i=0; $i<$cpt; $i++)
//        {
//            $_FILES['userfile']['name']= $files['userfile']['name'][$i];
//            $_FILES['userfile']['type']= $files['userfile']['type'][$i];
//            $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
//            $_FILES['userfile']['error']= $files['userfile']['error'][$i];
//            $_FILES['userfile']['size']= $files['userfile']['size'][$i];
//
//            $this->upload->initialize($this->set_upload_options());
//            $this->upload->do_upload();
//        }

    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('mobile');
        redirect("home", "refresh");
    }
} 