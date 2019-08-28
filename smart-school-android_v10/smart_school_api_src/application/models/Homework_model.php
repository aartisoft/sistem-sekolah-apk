<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Homework_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStudentHomeworkPercentage($student_id, $class_id, $section_id)
    {
        $sql = "SELECT count(*) as total_homework,(SELECT count(*) from homework_evaluation WHERE homework_evaluation.homework_id =homework.id AND homework_evaluation.student_id=" . $this->db->escape($student_id) . ") as `completed`  FROM `homework` WHERE class_id=" . $this->db->escape($class_id) . " AND section_id=" . $this->db->escape($section_id) . " AND evaluated_by=1";

        $query = $this->db->query($sql);
        return $query->row();
    }

//  public function getStudentHomework($class_id, $section_id,$student_id) {

// $sql="SELECT `homework`.*, `subjects`.`name`, `sections`.`section`, `classes`.`class`,homework_evaluation.id as homework_evaluation_id,homework_evaluation.date as evaluation_date,homework_evaluation.student_id as student_id, staff.name as `staff_created`, ev.name as `evaluate_name` FROM `homework` JOIN `classes` ON `classes`.`id` = `homework`.`class_id` JOIN `sections` ON `sections`.`id` = `homework`.`section_id`
    // JOIN `subjects` ON `subjects`.`id` = `homework`.`subject_id` LEFT JOIN staff on staff.id=homework.created_by LEFT JOIN staff as ev on ev.id=homework.evaluated_by LEFT JOIN homework_evaluation on homework_evaluation.homework_id=homework.id and homework_evaluation.student_id=" . $this->db->escape($student_id) . "  WHERE `homework`.`class_id` = " . $this->db->escape($class_id) . " AND `homework`.`section_id` = " . $this->db->escape($section_id);

//         $query = $this->db->query($sql);
    //         return $query->result();
    //       }

    // public function getStudentHomework($class_id, $section_id) {

    //     $query = $this->db->select("homework.*,subjects.name,sections.section,classes.class")->join("classes", "classes.id = homework.class_id")->join("sections", "sections.id = homework.section_id")->join("subjects", "subjects.id = homework.subject_id")->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id))->get("homework");
    //     return $query->result_array();
    // }

    public function getStudentHomework($class_id, $section_id)
    {

        $this->db->select("homework.*,subjects.name,sections.section,classes.class,create_staff.name as `staff_created`,evaluate_staff.name as `staff_evaluated`");
        $this->db->join("classes", "classes.id = homework.class_id");
        $this->db->join("sections", "sections.id = homework.section_id");
        $this->db->join("subjects", "subjects.id = homework.subject_id");
        $this->db->join("staff create_staff", "create_staff.id = homework.created_by",'left');
        $this->db->join("staff evaluate_staff", "evaluate_staff.id = homework.evaluated_by",'left');
        $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id));
        $query = $this->db->get("homework");
        return $query->result_array();
    }

    public function getEvaluationReportForStudent($id, $student_id)
    {

        $query = $this->db->select("homework.*,homework_evaluation.student_id,homework_evaluation.id as evalid,homework_evaluation.date,homework_evaluation.status,homework_evaluation.student_id,classes.class,subjects.name,sections.section")->join("classes", "classes.id = homework.class_id")->join("sections", "sections.id = homework.section_id")->join("subjects", "subjects.id = homework.subject_id")->join("homework_evaluation", "homework.id = homework_evaluation.homework_id")->where("homework.id", $id)->get("homework");
        //->where("homework_evaluation.student_id", $student_id)
        $result = $query->result_array();

        foreach ($result as $key => $value) {

            if ($value["student_id"] == $student_id) {

                return $value;
            } else {

                $data = array('date' => $value["date"], 'status' => 'Incomplete');
                return $data;
            }
        }

    }

}
