<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ComponentModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vcnama, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vcnama, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
         $this->db->like('a.vcnama', $keyword);
         $this->db->group_by('a.intid, a.vcnama, a.intstatus, b.vcnama, b.vcwarna, a.dtupdate, a.intid');
         $this->db->order_by('a.dtupdate','desc');
         $this->db->order_by('a.intid','desc');
         $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.intid, a.vcnama, a.dtupdate, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
       $this->db->from($table . ' as a');
       $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
       $this->db->where('a.intid',$intid);
       return $this->db->get()->result();
    }

    function getjmldata($table, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->like('a.vcnama', $keyword);
        return $this->db->get()->result();
    }
}