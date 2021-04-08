<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeknologimesinModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_prosesgroup_sme2 c', 'c.intid = a.intprosesgroup', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus,
                          ISNULL(c.vcnama, 0) as vcprosesgroup,
                          ISNULL(b.vcnama, 0) as vcstatus,
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
         $this->db->join('m_prosesgroup_sme2 c', 'c.intid = a.intprosesgroup', 'left');
         $this->db->like('a.vcnama', $keyword);
         $this->db->group_by('a.intid, a.vckode, a.vcnama, a.intstatus, c.vcnama, b.vcnama, b.vcwarna, a.dtupdate');
         $this->db->order_by('a.dtupdate','desc');
         $this->db->order_by('a.intid','desc');
         $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.intid, a.intprosesgroup, a.vckode, a.vcnama, a.dtupdate, a.intstatus, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
       $this->db->from($table . ' as a');
       $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
       $this->db->where('a.intid',$intid);
       return $this->db->get()->result();
    }
}