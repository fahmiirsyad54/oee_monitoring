<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SortModel extends CI_Model {

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
        $this->db->select('a.intid, a.vcnama, a.intstatus, a.intsort, a.intsortall, a.intgedung, ISNULL(c.vcnama, 0) as vcgedung, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
         $this->db->join('m_gedung as c', 'a.intgedung = c.intid', 'left');
         $this->db->like('a.vcnama', $keyword);
         $this->db->where('a.intautocutting',1);
         $this->db->group_by('a.intid, a.vcnama,a.intstatus, a.intsort, a.intsortall, a.intgedung, c.vcnama, b.vcnama, b.vcwarna');
         //$this->db->order_by('a.dtupdate','desc');
         $this->db->order_by('a.intsortall','asc');
         //$this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatalimit2($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vcnama, a.intstatus, a.intsort, a.intsortall, a.intgedung, ISNULL(c.vcnama, 0) as vcgedung, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
         $this->db->join('m_gedung as c', 'a.intgedung = c.intid', 'left');
         $this->db->like('a.vcnama', $keyword);
         $this->db->where('a.intautocutting',2);
         $this->db->group_by('a.intid, a.vcnama,a.intstatus, a.intsort, a.intsortall, a.intgedung, c.vcnama, b.vcnama, b.vcwarna');
         //$this->db->order_by('a.dtupdate','desc');
         $this->db->order_by('a.intsortall','asc');
         //$this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.*, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
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