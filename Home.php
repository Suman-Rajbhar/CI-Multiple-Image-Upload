<?php
/**
 * Created by PhpStorm.
 * User: SR
 * Date: 10/20/2016
 * Time: 4:02 PM
 */

class Home extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Blogger', 'b_model', true);

        $email = $this->session->userdata('email');
        if($email){
            redirect("admin_dashboard");
        }
    }

    public function index(){
        $data = array();
        $this->load->helper('captcha');
        $capt = rand(00,99);
        $cap_values = array(
            'word' => $capt,
            'img_path'  => './captcha/',
            'img_url'  => base_url().'captcha/',
            'font_path' => './cap_fonts/impact.ttf',
            'img_width' => '180',
            'img_height' => '50',
            'expiration' => 7200
        );
        $data['cap'] = create_captcha($cap_values);
        $data['mid_content'] = true;
        $data['title'] = "HOME";
        $data['blogs'] = $this->b_model->select_blogs();
        $data['home_content'] = $this->load->view('home', $data, true);
        $this->load->view('masterpage', $data);
    }

    public function about(){
        $data = array();
        $data['mid_content'] = true;
        $data['title'] = "ABOUT";
        $data['home_content'] = $this->load->view('aboutpage', '', true);
        $this->load->view('masterpage', $data);
    }

    public function contact(){
        $data = array();
        $data['title'] = "CONTACT";
        $data['mid_content'] = false;
        $data['home_content'] = $this->load->view('contactpage', '', true);
        $this->load->view('masterpage',$data);
    }

    public function new_registration()
    {

        $data = array();
        $data['full_name'] = $this->input->post('f_name');
        $data['email'] = $this->input->post('email');
        $data['mobile'] = $this->input->post('mobile');
        $data['password'] = md5($this->input->post('password'));

        $this->load->model('Blogger');
        $this->Blogger->inser_new_blogger($data);

        redirect("/");


//        echo "<pre>";
//        print_r($data);
    }

    public function admin_login()
    {
        $word = $this->input->post('word', true);
        $captcha = $this->input->post('captcha', true);

        if($word != $captcha){
            $this->session->set_flashdata('error', 'Your Captcha is not correct!');
            redirect("home");
        }else{
            $email = $this->input->post('email', true);
            $password = md5($this->input->post('password', true));
            $result = $this->b_model->check_blogger($email,$password);

            if($result){
                $sdata = array();
                $sdata['email'] = $result->email;
                $sdata['mobile'] = $result->mobile;
                $this->session->set_userdata($sdata);
                redirect("admin_dashboard");
            }else{
                $this->session->set_flashdata('error', 'Your Password or Email invalid!');
                redirect("home");
            }
        }


    }

    public function blog_details_home($id)
    {
        $data = array();
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $data['mid_content'] = true;
        $data['title'] = "Protfolio";
        $data['details'] = $this->b_model->select_blog_details($id);
        $data['comments'] = $this->b_model->select_blog_comments($id);
        $data['likes'] = $this->b_model->select_blog_likes($id);
        $data['user_like'] = $this->b_model->select_blog_user_like($id, $user_ip);
        $data['home_content'] = $this->load->view('blog_details', $data, true);
        $this->load->view('masterpage', $data);
    }

    public function save_public_comment()
    {
        $id = $this->input->post('b_id', true);
        $data = array(
            'commenter' => $this->input->post('commenter', true),
            'comment' => $this->input->post('comment', true),
            'blog_id' => $id,
            'created_at' => date("Y:m:d H:i:s")
        );

        $this->b_model->save_a_comment($data);

        redirect("home/blog_details_home/$id");
//
//        echo "<pre>";
//        print_r($data);
    }

    public function check_like()
    {
        $b_id = $this->input->post('b_id');
        $this->b_model->insert_new_like($b_id);
    }




} 