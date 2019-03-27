<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vcnama, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vcnama, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function get_detail_komponen($intid){
      $this->db->select('a.*, IFNULL(b.vcnama, "") as vckomponen',false);
      $this->db->from('m_models_komponen as a');
      $this->db->join('m_komponen' . ' as b', 'a.intkomponen = b.intid', 'left');
      $this->db->where('intheader',$intid);
      return $this->db->get()->result();
    }

    function get_detail_ct($intid){
      $this->db->select('a.*',false);
      $this->db->from('m_models_komponen_ct as a');
      $this->db->join('m_models_komponen as c', 'a.intheader = c.intid','left');
      $this->db->join('m_komponen' . ' as b', 'c.intkomponen = b.intid', 'left');
      $this->db->where('a.intheader',$intid);
      return $this->db->get()->result();
    }

    function getjmldata($table, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->like('a.vcnama', $keyword);
        return $this->db->get()->result();
    }

    function getteknologimesin(){
        $this->db->select('a.intid as intteknologimesin, a.vcnama as vcteknologimesin, b.intid as intprosesgroup, b.vcnama as vcprosesgroup, 0 as intapplicable, 0 as intcomply');
        $this->db->from('m_teknologimesin_sme2 a');
        $this->db->join('m_prosesgroup_sme2 b','b.intid = a.intprosesgroup');

        return $this->db->get()->result();
    }

    function getmodelteknologimesin($intheader){
        $this->db->select('a.*, b.intid as intprosesgroup, b.vcnama as vcprosesgroup, c.intid as intteknologimesin, c.vcnama as vcteknologimesin');
        // $this->db->form('m_teknologimesin_sme2 a');
        // $this->db->join('m_prosesgroup_sme2 b','b.intid = a.intprosesgroup');
        $this->db->from('m_models_sme2 a');
        $this->db->join('m_prosesgroup_sme2 b', 'b.intid = a.intprosesgroup');
        $this->db->join('m_teknologimesin_sme2 c','c.intid = a.intteknologimesin');
        $this->db->where('intheader',$intheader);

        return $this->db->get()->result();
    }

    function getintkomponen($intkomponen){
      $this->db->from('m_komponen');
      $this->db->where('intid', $intkomponen);
      
      return $this->db->get()->result(); 
    }
    
}
