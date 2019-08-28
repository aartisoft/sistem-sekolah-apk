<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
       
        $this->load->library('mailer');
        $this->load->library(array('customlib', 'enc_lib'));
    }
    // public function login()
    // {
    //     $method = $this->input->server('REQUEST_METHOD');

    //     if ($method != 'POST') {
    //         json_output(400, array('status' => 400, 'message' => 'Bad request.'));
    //     } else {
    //         $check_auth_client = $this->auth_model->check_auth_client();
    //         if ($check_auth_client == true) {
    //             $params   = json_decode(file_get_contents('php://input'), true);
    //             $username = $params['username'];
    //             $password = $params['password'];
    //             $response = $this->auth_model->login($username, $password);

    //             json_output($response['status'], $response);

    //         }
    //     }
    // }

    // public function logout()
    // {
    //     $method = $this->input->server('REQUEST_METHOD');
    //     if ($method != 'POST') {
    //         json_output(400, array('status' => 400, 'message' => 'Bad request.'));
    //     } else {
    //         $check_auth_client = $this->auth_model->check_auth_client();
    //         if ($check_auth_client == true) {
    //             $response = $this->auth_model->logout();
    //             json_output($response['status'], $response);
    //         }
    //     }
    // }

    // public function forgot_password()
    // {

    //     $method = $this->input->server('REQUEST_METHOD');

    //     if ($method != 'POST') {
    //         json_output(400, array('status' => 400, 'message' => 'Bad request.'));
    //     } else {
    //         $check_auth_client = $this->auth_model->check_auth_client();
    //         if ($check_auth_client == true) {

    //             $_POST = json_decode(file_get_contents("php://input"), true);
    //             $this->form_validation->set_data($_POST);
    //             $this->form_validation->set_rules('email', 'Email', 'trim|required');
    //             $this->form_validation->set_rules('usertype', 'User Type', 'trim|required');
    //             if ($this->form_validation->run() == false) {
    //                 $errors = validation_errors();
    //               }

    //             if (isset($errors)) {
    //                 $respStatus = 400;
    //                 $resp       = array('status' => 400, 'message' => $errors);
    //             } else {
    //                 $email    = $this->input->post('email');
    //                 $usertype = $this->input->post('usertype');

    //                 $result = $this->user_model->forgotPassword($usertype, $email);

    //                 if ($result && $result->email != "") {

    //                     $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
    //                     $update_record     = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
    //                     $this->user_model->updateVerCode($update_record);
    //                     if ($usertype == "student") {
    //                         $name = $result->firstname . " " . $result->lastname;
    //                     } else {
    //                         $name = $result->name;
    //                     }
    //                     $resetPassLink = site_url('user/resetpassword') . '/' . $usertype . "/" . $verification_code;

    //                     $body       = $this->forgotPasswordBody($name, $resetPassLink);
    //                     $body_array = json_decode($body);

    //                     if (!empty($this->mail_config)) {
    //                         $result = $this->mailer->send_mail($result->email, $body_array->subject, $body_array->body);
    //                     }
    //                     $respStatus = 200;
    //                     $resp       = array('status' => 200, 'message' => "Please check your email to recover your password");

    //                 } else {
    //                     $respStatus = 401;
    //                     $resp       = array('status' => 401, 'message' => "Invalid Email or User Type");

    //                 }
    //             }
    //             json_output($respStatus, $resp);

    //         }
    //     }

    // }

    // public function forgotPasswordBody($name, $resetPassLink)
    // {
    //     //===============
    //     $subject = "Password Update Request";
    //     $body    = 'Dear ' . $name . ',
    //             <br/>Recently a request was submitted to reset password for your account. If you didn\'t make the request, just ignore this email. Otherwise you can reset your password using this link <a href="' . $resetPassLink . '"><button>Click here to reset your password</button></a>';
    //     $body .= '<br/><hr/>if you\'re having trouble clicking the password reset button, copy and paste the URL below into your web browser';
    //     $body .= '<br/>' . $resetPassLink;
    //     $body .= '<br/><br/>Regards,
    //             <br/>' . $this->customlib->getSchoolName();

    //     //======================
    //     return json_encode(array('subject' => $subject, 'body' => $body));
    // }
}
