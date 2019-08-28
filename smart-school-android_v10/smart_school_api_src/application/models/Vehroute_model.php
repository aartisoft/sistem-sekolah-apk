<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vehroute_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getVechileByRoute($route_id) {
        $this->db->select('vehicle_routes.id as vec_route_id,vehicles.*')->from('vehicle_routes');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id');

        $this->db->where('vehicle_routes.route_id', $route_id);
        $this->db->order_by('vehicle_routes.id', 'DESC');
        $query = $this->db->get();
        return $vehicle_routes = $query->result();
    }

   
    public function listroute() {

  
        $this->db->select()->from('transport_route');
        $listtransport = $this->db->get();
        
   

        $listroute = $listtransport->result_array();
        if (!empty($listroute)) {
            foreach ($listroute as $route_key => $route_value) {
                $vehicles = $this->getVechileByRoute($route_value['id']);
                $listroute[$route_key]['vehicles'] = $vehicles;
            }
        }
        return $listroute;
    }

}
