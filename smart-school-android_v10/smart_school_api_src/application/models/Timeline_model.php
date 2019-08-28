<?php

class Timeline_model extends CI_Model {

    
    public function getTimeline($id) {

        $query = $this->db->where("student_id", $id)->order_by("timeline_date", "asc")->get("student_timeline");
        return $query->result_array();
    }

  

}

?>