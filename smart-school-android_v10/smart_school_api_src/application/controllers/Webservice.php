<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webservice extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mailer');
        $this->load->library(array('customlib', 'enc_lib'));
        $this->load->model(array('auth_model', 'route_model', 'student_model', 'setting_model', 'attendencetype_model', 'studentfeemaster_model', 'feediscount_model', 'teachersubject_model', 'timetable_model', 'user_model', 'examschedule_model', 'webservice_model','grade_model','librarymember_model','bookissue_model','homework_model','event_model','vehroute_model','timeline_model','module_model','paymentsetting_model'));
    }

    public function verifyUrl() {
        $data['status'] = 200;
        $data['apiUrl'] = "http://qdocs.in/ssapi/";
        echo json_encode($data);
    }

    public function login()
    {
        $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $params   = json_decode(file_get_contents('php://input'), true);
                $username = $params['username'];
                $password = $params['password'];
                $app_key = $params['deviceToken'];
                $response = $this->auth_model->login($username, $password,$app_key);

                json_output($response['status'], $response);

            }
        }
    }


       public function getSchoolDetails()
    {
        $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
               $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {                  

        $result=  $this->setting_model->getSchoolDisplay();
        $result->start_month_name=ucfirst($this->customlib->getMonthList($result->start_month));
      
                    

                    json_output($response['status'], $result);
                    // json_output($response['status'], $response);
                }
            }
          
        }
    }





        public function getModuleStatus()
    {
        $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
               $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {                  
                    $resp['module_list'] =  $this->module_model->get();
                    json_output($response['status'], $resp);
                }
            }
          
        }
    }

  public function addTask()
    {

         $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {

           $_POST = json_decode(file_get_contents("php://input"), true);
                $this->form_validation->set_data($_POST);
        $this->form_validation->set_error_delimiters('', ''); 
        $this->form_validation->set_rules('event_title', 'Title', 'required|trim');
        $this->form_validation->set_rules('date', 'Date', 'required|trim');
        $this->form_validation->set_rules('user_id', 'user login id', 'required|trim');

        if ($this->form_validation->run() == false) {

            $sss = array(
                'event_title' => form_error('event_title'),
                'date' => form_error('date'),
                'user_id' => form_error('user_id'),
            );
            $array = array('status' => '0', 'error' => $sss);
            // echo json_encode($array);

   
        } else {
            //==================
            $data=array(
            'event_title'=>$this->input->post('event_title'),
            'start_date'=>$this->input->post('date'),
            'end_date'=>$this->input->post('date'),
            'event_type'=>'task',
            'is_active'=>'no',
            'event_for'=>$this->input->post('user_id'),
            'event_color'=>'#000'
          
        );
       $this->event_model->saveEvent($data);
       $array = array('status' => '1', 'msg' => 'Success');
        }
           json_output(200, $array);
    }

     }
        }

    public function updateTask()
    {

         $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {



           $_POST = json_decode(file_get_contents("php://input"), true);
                $this->form_validation->set_data($_POST);
        $this->form_validation->set_error_delimiters('', ''); 
      $this->form_validation->set_rules('task_id', 'Task ID', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() == false) {

            $errors = array(
              'task_id' => form_error('task_id'),
                'status' => form_error('status'),
            );
            $array = array('status' => '0', 'error' => $errors);
            // echo json_encode($array);

   
        } else {
            //==================
            $data=array(
              'id'=>$this->input->post('task_id'),
            'is_active'=>$this->input->post('status'),
          
        );
       $this->event_model->saveEvent($data);
       $array = array('status' => '1', 'msg' => 'Success');
        }
           json_output(200, $array);
    }

     }
        }

     public function deleteTask()
    {

         $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {



           $_POST = json_decode(file_get_contents("php://input"), true);
                $this->form_validation->set_data($_POST);
        $this->form_validation->set_error_delimiters('', ''); 
      $this->form_validation->set_rules('task_id', 'Task ID', 'required|trim');


        if ($this->form_validation->run() == false) {

            $errors = array(
              'task_id' => form_error('task_id'),
            
            );
            $array = array('status' => '0', 'error' => $errors);
            // echo json_encode($array);

   
        } else {
            //==================
          
        $id=$this->input->post('task_id');
    
       $this->event_model->deleteEvent($id);
       $array = array('status' => '1', 'msg' => 'Success');
        }
           json_output(200, $array);
    }

     }
        }

    

    public function logout()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            // $check_auth_client = $this->auth_model->check_auth_client();
            // if ($check_auth_client == true) {
                $response = $this->auth_model->logout();
                json_output($response['status'], $response);
            // }
        }
    }

    public function forgot_password()
    {

        $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
          

                $_POST = json_decode(file_get_contents("php://input"), true);
                $this->form_validation->set_data($_POST);
                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('usertype', 'User Type', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $errors = validation_errors();
                  }

                if (isset($errors)) {
                    $respStatus = 400;
                    $resp       = array('status' => 400, 'message' => $errors);
                } else {
                    $email    = $this->input->post('email');
                    $usertype = $this->input->post('usertype');
                    $site_url = $this->input->post('site_url');

                    $result = $this->user_model->forgotPassword($usertype, $email);

                    if ($result && $result->email != "") {

                        $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                        $update_record     = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
                        $this->user_model->updateVerCode($update_record);
                        if ($usertype == "student") {
                            $name = $result->firstname . " " . $result->lastname;
                        } else {
                            $name = $result->name;
                        }
                        $resetPassLink = $site_url.'/user/resetpassword' . '/' . $usertype . "/" . $verification_code;

                        $body       = $this->forgotPasswordBody($name, $resetPassLink);
                        $body_array = json_decode($body);

                        if (!empty($this->mail_config)) {
                            
                            $result = $this->mailer->send_mail($result->email, $body_array->subject, $body_array->body);
                            if($result){
                            $respStatus = 200;
                        $resp       = array('status' => 200, 'message' => "Please check your email to recover your password");
                            }else{
                                     $respStatus = 200;
                        $resp       = array('status' => 200, 'message' => "Sending of message failed, Please contact to Admin.");   
                            }
                        }
                       
                

                    } else {
                        $respStatus = 401;
                        $resp       = array('status' => 401, 'message' => "Invalid Email or User Type");

                    }
                }
                json_output($respStatus, $resp);

          
        }

    }

    public function forgotPasswordBody($name, $resetPassLink)
    {
        //===============
        $subject = "Password Update Request";
        $body    = 'Dear ' . $name . ',
                <br/>Recently a request was submitted to reset password for your account. If you didn\'t make the request, just ignore this email. Otherwise you can reset your password using this link <a href="' . $resetPassLink . '"><button>Click here to reset your password</button></a>';
        $body .= '<br/><hr/>if you\'re having trouble clicking the password reset button, copy and paste the URL below into your web browser';
        $body .= '<br/>' . $resetPassLink;
        $body .= '<br/><br/>Regards,
                <br/>' . $this->customlib->getSchoolName();

        //======================
        return json_encode(array('subject' => $subject, 'body' => $body));
    }

    public function getStudentProfile()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params   = json_decode(file_get_contents('php://input'), true);
                    $studentId = $params['studentId'];
                    $resp = $this->webservice_model->getStudentProfile($studentId);
                   
                    json_output($response['status'], $resp);
                }
            }
        }
    }

 public function dashboard()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $date_list=array();
                    $params   = json_decode(file_get_contents('php://input'), true);
      $student_id = $params['student_id'];
      $resp                    = array();
      $date_from     = $params['date_from'];
      $date_to       = $params['date_to'];
      $student                 = $this->student_model->get($student_id);
      $student_login=$this->user_model->getUserLoginDetails($student_id);
      $attendence_percentage=0;
   
      $student_session_id = $student['student_session_id'];
      $student_attendence      = $this->attendencetype_model->getAttendencePercentage($date_from,$date_to, $student_session_id);
      $student_homework      = $this->homework_model->getStudentHomeworkPercentage($student_id,$student['class_id'],$student['section_id']);
      if($student_attendence->present_attendance > 0 && $student_attendence->total_count > 0){

      $attendence_percentage=$student_attendence->present_attendance/$student_attendence->total_count*100;
      }
      $resp['class_id']=$student['class_id'];
      $resp['section_id']=$student['section_id'];
      $resp['student_attendence_percentage']=round($attendence_percentage);
     $resp['student_homework_incomplete']=round($student_homework->total_homework-$student_homework->completed);
     $resp['student_incomplete_task'] = $this->event_model->incompleteStudentTaskCounter($student_login['id']);
     // $resp['public_events'] = $this->event_model->getPublicEvents($student_login['id']);
        $resp['public_events'] = $this->event_model->getPublicEvents($student_login['id'],$date_from,$date_to);

       foreach ($resp['public_events'] as &$ev_tsk_value) {
            $evt_array = array();
            if ($ev_tsk_value->event_type == "public") {
                $start = strtotime($ev_tsk_value->start_date);
                $end   = strtotime($ev_tsk_value->end_date);

                for ($st = $start; $st <= $end; $st += 86400) {
                     $date_list[date('Y-m-d', $st)] = date('Y-m-d', $st);
                     $evt_array[]=date('Y-m-d', $st);
                }
               
                $ev_tsk_value->events_lists = implode(",",$evt_array);
            } elseif ($ev_tsk_value->event_type == "task") {

                $date_list[date('Y-m-d', strtotime($ev_tsk_value->start_date))]  = date('Y-m-d', strtotime($ev_tsk_value->start_date));
                   $evt_array[]=date('Y-m-d', strtotime($ev_tsk_value->start_date));
                $ev_tsk_value->events_lists = implode(",",$evt_array);
               

            }
        }
   $resp['date_lists'] = implode(",",$date_list);

                    json_output($response['status'], $resp);
                }
            }
        }
    }


 public function getTask()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params   = json_decode(file_get_contents('php://input'), true);
                    $user_id = $params['user_id'];
                    $resp                    = array();                
                    // $student                 = $this->student_model->get($student_id);
                    // $student_login=$this->user_model->getUserLoginDetails($student_id);
                   
                     $resp['tasks'] = $this->event_model->getTask($user_id);

                    json_output($response['status'], $resp);
                }
            }
        }
    }




    // public function getHomework1()
    // {
    //     $method = $this->input->server('REQUEST_METHOD');
    //     if ($method != 'POST') {
    //         json_output(400, array('status' => 400, 'message' => 'Bad request.'));
    //     } else {
    //         $check_auth_client = $this->auth_model->check_auth_client();
    //         if ($check_auth_client == true) {
    //             $response = $this->auth_model->auth();
    //             if ($response['status'] == 200) {
    //                 $params      = json_decode(file_get_contents('php://input'), true);
    //                 $className   = $params['classId'];
    //                 $sectionName = $params['sectionId'];
    //                 if (isset($params['homeworkId'])) {
    //                     $resp = $this->webservice_model->getHomeworkDetails($params['homeworkId'],3);
    //                     json_output($response['status'], $resp);
    //                 } else {
    //                     $resp = $this->webservice_model->getHomework($className, $sectionName);
    //                     json_output($response['status'], $resp);
    //                 }
    //             }
    //         }
    //     }
    // }

     public function getDocument()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                   $_POST = json_decode(file_get_contents("php://input"), true);
                    $student_id  = $this->input->post('student_id');
                    $student_doc = $this->student_model->getstudentdoc($student_id);                   
                   json_output($response['status'], $student_doc);
                }
            }
        }
    }

    
     public function getHomework()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                   $_POST = json_decode(file_get_contents("php://input"), true);
                    $student_id = $this->input->post('student_id');
        $result = $this->student_model->getRecentRecord($student_id);
        $class_id = $result["class_id"];
        $section_id = $result["section_id"];
        $homeworklist = $this->homework_model->getStudentHomework($class_id, $section_id);
        $data["homeworklist"] = $homeworklist;
        $data["class_id"] = $class_id;
        $data["section_id"] = $section_id;
        $data["subject_id"] = "";
        foreach ($homeworklist as $key => $value) {

            $report = $this->homework_model->getEvaluationReportForStudent($value["id"], $student_id);

        $data["homeworklist"][$key]["report"] = $report;

        }

                    // $student                 = $this->student_model->get($student_id);
                    // $student_session_id      = $student['student_session_id'];   
                    // $student_homework        = $this->homework_model->getStudentHomework($student['class_id'],$student['section_id'],$student_id);
                   json_output($response['status'], $data);
                }
            }
        }
    }



      


   public function getTimeline()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params             = json_decode(file_get_contents('php://input'), true);
                    $student_id         = $params['studentId'];
                    $timeline           =$this->timeline_model->getTimeline($student_id);
                    json_output($response['status'], $timeline);
                }
            }
        }
    }



    public function getExamListByClassandSection()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params    = json_decode(file_get_contents('php://input'), true);
                    $classId   = $params['classId'];
                    $sectionId = $params['sectionId'];
                    $sessionId = $params['sessionId'];
                    $resp      = $this->webservice_model->getExamListByClassandSection($classId, $sectionId, $sessionId);
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getExamSchedule()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params = json_decode(file_get_contents('php://input'), true);
                    $examId = $params['examId'];
                    $resp   = $this->webservice_model->getExamSchedule($examId);
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getNotifications()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'GET') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $resp = $this->webservice_model->getNotifications();
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getSubjectList()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {

                    $params     = json_decode(file_get_contents('php://input'), true);
                    $class_id   = $params['classId'];
                    $section_id = $params['sectionId'];
                    $resp       = $this->webservice_model->getSubjectList($class_id, $section_id);
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getTeachersList()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params    = json_decode(file_get_contents('php://input'), true);
                    $sectionId = $params['sectionId'];
                    $resp      = $this->webservice_model->getTeachersList($sectionId);
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getLibraryBooks()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'GET') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {

                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $resp = $this->webservice_model->getLibraryBooks();
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getLibraryBookIssued()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {

                   $params    = json_decode(file_get_contents('php://input'), true);
                    $studentId = $params['studentId'];
                    $member_type = "student";
                    $resp = $this->librarymember_model->checkIsMember($member_type, $studentId);
              
                    json_output($response['status'], $resp);
                }
            }
        }
    }
    // public function getTransportRoute()
    // {
    //     $method = $this->input->server('REQUEST_METHOD');
    //     if ($method != 'GET') {
    //         json_output(400, array('status' => 400, 'message' => 'Bad request.'));
    //     } else {
    //         $check_auth_client = $this->auth_model->check_auth_client();
    //         if ($check_auth_client == true) {
    //             $response = $this->auth_model->auth();
    //             if ($response['status'] == 200) {
    //                 $resp = $this->webservice_model->getTransportRoute();
    //                 json_output($response['status'], $resp);
    //             }
    //         }
    //     }
    // }

 public function getTransportroute()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {

                    $params     = json_decode(file_get_contents('php://input'), true);
                    $student_id = $params['student_id'];
                    $student    = $this->student_model->get($student_id);
                    $vec_route_id = $student['vehroute_id'];
                    $listroute    = $this->vehroute_model->listroute();
                   
                    if ($vec_route_id != "") {
                        if (!empty($listroute)) {
                            foreach ($listroute as $listroute_key => $listroute_value) {

                                if (!empty($listroute_value['vehicles'])) {
                                    foreach ($listroute_value['vehicles'] as $route_key => $route_value) {
                                        if ($route_value->vec_route_id == $vec_route_id) {
                                            $route_value->assigned = "yes";
                                            break;
                                        } else {
                                            $route_value->assigned = "no";
                                        }

                                    }
                                }
                            }

                        }

                    }

                    json_output($response['status'], $listroute);
                }
            }
        }
    }


    public function getHostelList()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'GET') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $resp = $this->webservice_model->getHostelList();
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getHostelDetails()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params   = json_decode(file_get_contents('php://input'), true);
                    $hostelId = $params['hostelId'];
                    $resp     = $this->webservice_model->getHostelDetails($hostelId);
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getDownloadsLinks()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params    = json_decode(file_get_contents('php://input'), true);
                    $tag       = $params['tag'];
                    $classId   = $params['classId'];
                    $sectionId = $params['sectionId'];
                    $resp      = $this->webservice_model->getDownloadsLinks($classId, $sectionId, $tag);
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getTransportVehicleDetails()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $params    = json_decode(file_get_contents('php://input'), true);
                    $vehicleId = $params['vehicleId'];
                    $resp      = $this->webservice_model->getTransportVehicleDetails($vehicleId);
                    json_output($response['status'], $resp);
                }
            }
        }
    }

    public function getAttendenceRecords()
    {
        $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    ///===================
                    $_POST = json_decode(file_get_contents("php://input"), true);

                    $year       = $this->input->post('year');
                    $month      = $this->input->post('month');
                    $student_id = $this->input->post('student_id');
                    $student    = $this->student_model->get($student_id);

                    $student_session_id = $student['student_session_id'];
                    $result             = array();
                    $new_date           = "01-" . $month . "-" . $year;

                    $totalDays            = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    $first_day_this_month = date('01-m-Y');
                    $fst_day_str          = strtotime(date($new_date));
                    $array                = array();
                    for ($day = 2; $day <= $totalDays; $day++) {
                        $fst_day_str        = ($fst_day_str + 86400);
                        $date               = date('Y-m-d', $fst_day_str);
                        $student_attendence = $this->attendencetype_model->getStudentAttendence($date, $student_session_id);
                        if (!empty($student_attendence)) {
                            $s         = array();
                            $s['date'] = $date;
                            $type      = $student_attendence->type;
                            $s['type'] = $type;
                            $array[]   = $s;
                        }
                    }
                    $data['status'] = 200;
                    $data['data'] = $array; 
                    json_output($response['status'], $data);

                    //======================
                }
            }
        }
    }

    public function examSchedule()
    {

        $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $_POST = json_decode(file_get_contents("php://input"), true);

                    $student_id           = $this->input->post('student_id');
                    $data                 = array();
                    $stu_record           = $this->student_model->getRecentRecord($student_id);
                    $data['status']       = "200";
                    $data['class_id']     = $stu_record['class_id'];
                    $data['section_id']   = $stu_record['section_id'];
                    $examSchedule         = $this->examschedule_model->getExamByClassandSection($data['class_id'], $data['section_id']);
                    $data['examSchedule'] = $examSchedule;
                    json_output($response['status'], $data);
                }
            }
        }
    }

    public function getexamscheduledetail()
    {

        $method = $this->input->server('REQUEST_METHOD');

        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $_POST = json_decode(file_get_contents("php://input"), true);
                    $this->form_validation->set_data($_POST);
                    $exam_id      = $this->input->post('exam_id');
                    $section_id   = $this->input->post('section_id');
                    $class_id     = $this->input->post('class_id');
                    $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
                    json_output($response['status'], $examSchedule);
                }
            }
        }

    }


    // public function fees()
    // {
    //     $method = $this->input->server('REQUEST_METHOD');

    //     if ($method != 'POST') {
    //         json_output(400, array('status' => 400, 'message' => 'Bad request.'));
    //     } else {

    //         $check_auth_client = $this->auth_model->check_auth_client();
    //         if ($check_auth_client == true) {
    //             $response = $this->auth_model->auth();
    //             if ($response['status'] == 200) {

    //                 $_POST      = json_decode(file_get_contents("php://input"), true);
    //                 $student_id = $this->input->post('student_id');

    //                 $student = $this->student_model->get($student_id);
    //                 // $studentSession     = $this->student_model->getStudentSession($student_id);
    //                 // $student_session_id = $studentSession["student_session_id"];
    //                 // $student_session    = $studentSession["session"];

    //                 $student_due_fee              = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
    //                 $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
    //                 $data['student_due_fee']      = $student_due_fee;
    //                 $data['student_discount_fee'] = $student_discount_fee;
    //                 json_output($response['status'], $data);
    //             }
    //         }

    //     }
    // }

        public function fees() {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $data = array();
                    $pay_method     = $this->paymentsetting_model->getActiveMethod();
                    $_POST = json_decode(file_get_contents("php://input"), true);
                    $student_id = $this->input->post('student_id');

                    $student = $this->student_model->get($student_id);
                    $student_due_fee = $this->studentfeemaster_model->getStudentFees($student['student_session_id']);
                    $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
                            $init_amt=0;
                            $grand_amt = 0;
                            $grand_total_paid = 0;
                            $grand_total_discount = 0;
                            $grand_total_fine = 0;
            

                    if(!empty($student_due_fee)){

                   
                    foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {

                        foreach ($student_due_fee_value->fees as $each_fees_key => $each_fees_value) {

                            $amt = 0;
                            $total_paid = 0;
                            $total_discount = 0;
                            $total_fine = 0;
                            $each_fees_value->total_amount_paid = number_format((float) $amt, 2, '.', '');
                            $each_fees_value->total_amount_discount = number_format((float) $amt, 2, '.', '');
                            $each_fees_value->total_amount_fine = number_format((float) $amt, 2, '.', '');
                            $each_fees_value->total_amount_display = number_format((float) $amt, 2, '.', '');
                            $each_fees_value->total_amount_remaining = number_format((float) $each_fees_value->amount, 2, '.', '');
                            $each_fees_value->status = 'unpaid';

                                    $grand_amt=$grand_amt+$each_fees_value->amount;
                                
                            if (is_string($each_fees_value->amount_detail) && is_array(json_decode($each_fees_value->amount_detail, true)) && (json_last_error() == JSON_ERROR_NONE)) {
                                $fess_list = json_decode($each_fees_value->amount_detail);

                                foreach ($fess_list as $fee_key => $fee_value) {

                                    $grand_total_paid = $grand_total_paid + $fee_value->amount;
                                    $total_paid = $total_paid + $fee_value->amount;

                                    $grand_total_discount = $grand_total_discount + $fee_value->amount_discount;
                                    $total_discount = $total_discount + $fee_value->amount_discount;

                                    $grand_total_fine = $grand_total_fine + $fee_value->amount_fine;
                                    $total_fine = $total_fine + $fee_value->amount_fine;

                                }

                                $each_fees_value->total_amount_paid = number_format((float) $total_paid, 2, '.', '');
                                $each_fees_value->total_amount_discount = number_format((float) $total_discount, 2, '.', '');
                                $each_fees_value->total_amount_fine = number_format((float) $total_fine, 2, '.', '');
                               
                                $each_fees_value->total_amount_display = number_format((float) ($total_paid + $total_discount), 2, '.', '');
                                $each_fees_value->total_amount_remaining = number_format((float) ($each_fees_value->amount-(($total_paid + $total_discount))), 2, '.', '');

                                if($each_fees_value->total_amount_remaining <= '0.00'){
                                    $each_fees_value->status = 'paid';
                                }elseif ($each_fees_value->total_amount_remaining == number_format((float) $each_fees_value->amount, 2, '.', '')) {
                                    $each_fees_value->status = 'unpaid';
                                }else{
                                    $each_fees_value->status = 'partial';

                                }
                            }
                        }
                    }
                    }

        $grand_fee=array('amount'=>number_format((float) $grand_amt, 2, '.', ''),'amount_discount'=>number_format((float) $grand_total_discount, 2, '.', ''),'amount_fine'=>number_format((float) $grand_total_fine, 2, '.', ''),'amount_paid'=>number_format((float) $grand_total_paid, 2, '.', ''),'amount_remaining'=>number_format((float) ($grand_amt-($grand_total_paid+$grand_total_discount)), 2, '.', ''));

                    $data['pay_method']=empty($pay_method)?0:1;
                    $data['student_due_fee'] = $student_due_fee;
                    $data['student_discount_fee'] =$student_discount_fee;
                    $data['grand_fee'] =$grand_fee;
                    json_output($response['status'], $data);
                }
            }
        }
    }



    public function class_schedule()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $_POST                   = json_decode(file_get_contents("php://input"), true);
                    $student_id              = $this->input->post('student_id');
                    $student                 = $this->student_model->get($student_id);
                    $class_id                = $student['class_id'];
                    $section_id              = $student['section_id'];
                    $data['class_id']        = $class_id;
                    $data['section_id']      = $section_id;
                    $result_subjects         = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
                    $getDaysnameList         = $this->customlib->getDaysname();
                    $data['getDaysnameList'] = $getDaysnameList;
                    $dayListArray=array();

                    foreach ($getDaysnameList as $Day_key => $Day_value) {
                      $dayListArray[$Day_value]=array();  
                    }
                  
                    $final_array             = array();
                    if (!empty($result_subjects)) {
                        foreach ($result_subjects as $subject_k => $subject_v) {
                          
                            foreach ($getDaysnameList as $day_key => $day_value) {
                                $where_array = array(
                                    'teacher_subject_id' => $subject_v['id'],
                                    'day_name'           => $day_value,
                                );
                                $obj                      = new stdClass();
                                $result = $this->timetable_model->get($where_array);
                                if (!empty($result)) {
                                    $obj->status              = "Yes";
                                    $obj->start_time          = $result[0]['start_time'];
                                    $obj->end_time            = $result[0]['end_time'];
                                    $obj->room_no             = $result[0]['room_no'];
                                    $obj->subject= $subject_v['name'];
                                } else {
                                    
                                    $obj->status              = "No";
                                    $obj->start_time          = "N/A";
                                    $obj->end_time            = "N/A";
                                    $obj->room_no             = "N/A";
                                    $obj->subject= $subject_v['name'];
                                    
                                }

                            $dayListArray[$day_value][] = $obj;
                            }
                        }
                    }
                 
                    $data['status'] = "200";
                    $data['result_array'] = array();
                    $data['result_array'] = $dayListArray;
                    json_output($response['status'], $data);
                }
            }
        }
    }


    // public function getExamResultList()
    // {
    //     $method = $this->input->server('REQUEST_METHOD');
    //     if ($method != 'POST') {
    //         json_output(400, array('status' => 400, 'message' => 'Bad request.'));
    //     } else {
    //         $check_auth_client = $this->auth_model->check_auth_client();
    //         if ($check_auth_client == true) {
    //             $response = $this->auth_model->auth();
    //             if ($response['status'] == 200) {
    //                 $_POST      = json_decode(file_get_contents("php://input"), true);
    //                 $student_id = $this->input->post('student_id');
    //                 $student    = $this->student_model->get($student_id);
    //                 $examList   = $this->examschedule_model->getExamByClassandSection($student['class_id'], $student['section_id']);
    //                 $resp['status'] = 200;
    //                 $resp['examList'] = $examList;
    //                 json_output(200, $resp);
    //             }
    //         }
    //     }
    // }

 public function getExamResultList()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $_POST            = json_decode(file_get_contents("php://input"), true);
                    $student_id       = $this->input->post('student_id');
                    $student          = $this->student_model->get($student_id);
                    $examList         = $this->examschedule_model->getExamByClassandSection($student['class_id'], $student['section_id']);
                    $resp['status']   = 200;
                 
        $resp['examList'] = array();
        if (!empty($examList)) {
            $new_array = array();
            foreach ($examList as $ex_key => $ex_value) {
                $array = array();
                $x = array();
                $exam_id = $ex_value['exam_id'];
                $student['id'];
                $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student['id']);
                $total_marks=0;
                $get_marks=0;
                $result="Pass";

                foreach ($exam_subjects as $key => $value) {
              
                    $total_marks=$total_marks+$value['full_marks'];
                    $get_marks=$get_marks+$value['get_marks'];
                    
                    if(($value['get_marks'] < $value['passing_marks']) || ($value['attendence'] != 'pre')){
                        $result='Fail';
                    }
                  
                }

                $exam_result = new stdClass();
                $exam_result->total_marks=$total_marks;
                $exam_result->get_marks=number_format($get_marks, 2 );
                $exam_result->percentage= number_format((($get_marks * 100) / $total_marks), 2 ) . '%';
                $exam_result->grade =$this->getGradeByMarks(number_format((($get_marks * 100) / $total_marks), 2 ));
                $exam_result->result= $result;
                $exam_result->exam_id= $ex_value['exam_id'];
                $array['exam_name'] = $ex_value['name'];
                $array['exam_result'] = $exam_result;
                $new_array[] = $array;
            }
              $resp['examList'] = $new_array;
        }


                    json_output(200, $resp);
                }
            }
        }
    }


public function getGradeByMarks($marks=0){
     $gradeList = $this->grade_model->get();
    if(empty($gradeList)){
     return "empty list";
    }else{

        foreach ($gradeList as $grade_key => $grade_value) {
          if(round($marks) >= $grade_value['mark_from'] && round($marks) <= $grade_value['mark_upto']){
            return $grade_value['name'];
            break;
          }
        }
        return "no record found";
    }
 }

     public function getExamResultDetails()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $array      = array();
                    $x          = array();
                    $_POST      = json_decode(file_get_contents("php://input"), true);
                    $student_id = $this->input->post('student_id');
                    $exam_id    = $this->input->post('exam_id');
                    $total_marks=0;
                    $get_marks=0;
                    $result="Pass";
                    $exam_subjects = $this->examschedule_model->getresultByStudentandExam($exam_id, $student_id);
                    foreach ($exam_subjects as $key => $value) {
                        $exam_array                     = array();
                        $exam_array['exam_schedule_id'] = $value['exam_schedule_id'];
                        $exam_array['exam_id']          = $value['exam_id'];
                        $exam_array['full_marks']       = $value['full_marks'];
                        $exam_array['passing_marks']    = $value['passing_marks'];
                        $exam_array['exam_name']        = $value['name'];
                        $exam_array['exam_type']        = $value['type'];
                        $exam_array['attendence']       = $value['attendence'];
                        $exam_array['get_marks']        = $value['get_marks'];
                            $total_marks=$total_marks+$value['full_marks'];
                        $get_marks=$get_marks+$value['get_marks'];
                    if(($value['get_marks'] < $value['passing_marks']) || ($value['attendence'] != 'pre')){
                          $exam_array['status']       = "Fail";
                          $result="Fail";
                    } else {
                            $exam_array['status']       = "Pass";
                    }
                        $x[]                            = $exam_array;
                    }
                    $array['status'] = 200;
                    $array['total_marks'] = $total_marks;
                    $array['get_marks'] = $get_marks;
                    $array['percentage'] = number_format((($get_marks * 100) / $total_marks), 2 ) . '%';
                    $array['grade'] = $this->getGradeByMarks(number_format((($get_marks * 100) / $total_marks), 2 ));
                    $array['result']= $result;
                    $array['exam_result'] = $x;
                    json_output($response['status'], $array);
                }
            }
        }

    }




    public function Parent_GetStudentsList()
    {
        $method = $this->input->server('REQUEST_METHOD');
        if ($method != 'POST') {
            json_output(400, array('status' => 400, 'message' => 'Bad request.'));
        } else {
            $check_auth_client = $this->auth_model->check_auth_client();
            if ($check_auth_client == true) {
                $response = $this->auth_model->auth();
                if ($response['status'] == 200) {
                    $array      = array();
                   
                    $_POST      = json_decode(file_get_contents("php://input"), true);
                    $parent_id = $this->input->post('parent_id');
                    $students_array = $this->student_model->read_siblings_students($parent_id);
                    $array['childs']=$students_array;
                    json_output($response['status'], $array);
                }
            }
        }

    }

}
