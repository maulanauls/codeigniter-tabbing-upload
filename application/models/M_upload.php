<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: uls
 * Date: 11/02/18
 * Time: 21.53
 */

class M_upload extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_users(){
        $this->db->from('users as a');
        $this->db->select('a.*,b.title,b.file');
        $this->db->join('document as b', 'a.users_id = b.users_id','left');
        $this->db->order_by('a.users_id', 'DESC');
        return $this->db->get()->result();
    }

    function users_id(){
        $query 	= $this->db->query("SELECT MAX(users_id) AS id FROM `users`");
        $kd = "";
        if($query->num_rows()>0) {
            foreach($query->result() as $k){
                $kd = ((int)$k->id)+1;
            }
        }else{
            $kd = 1;
        }
        return $kd;
    }

    function save_users($data){
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    function save_document($data){
        $this->db->insert('document', $data);
        return $this->db->insert_id();
    }

}