<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendencetype_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

   
    public function getStudentAttendence($date, $student_session_id) {
        $sql = "SELECT attendence_type.type FROM `student_attendences`
INNER JOIN attendence_type ON attendence_type.id=student_attendences.attendence_type_id where  student_attendences.`student_session_id`=" . $this->db->escape($student_session_id) . " and student_attendences.date=" . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    public function getAttendencePercentage($date_from,$date_to, $student_session_id) {
        $sql = "SELECT IFNULL(count(*), 0) as `total_count`,IFNULL((SELECT count(*) as `total_count` FROM `student_attendences` as a WHERE (date BETWEEN " . $this->db->escape($date_from) . " and " . $this->db->escape($date_to) . ") and student_session_id=" . $this->db->escape($student_session_id) . "  and attendence_type_id !=4 ),0) as `present_attendance` FROM `student_attendences` as a WHERE (date BETWEEN " . $this->db->escape($date_from) . " and " . $this->db->escape($date_to) . ") and student_session_id=" . $this->db->escape($student_session_id);

        $query = $this->db->query($sql);
        return $query->row();
    }

}
