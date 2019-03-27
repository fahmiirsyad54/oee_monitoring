<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class notesModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
    function getjmldata($table, $intgedung=0, $from=null, $to=null){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_mesin as b', 'a.intmesin = b.intid');
        $this->db->join('m_gedung as c', 'b.intgedung = c.intid');
        if ($from) {
          $this->db->where('date(a.dttanggal) >= "' . $from . '"');
          $this->db->where('date(a.dttanggal) <= "' . $to . '"');
        }

        if ($intgedung > 0) {
          $this->db->where('b.intgedung',$intgedung); 
        }
        return $this->db->get()->result();
    }
 
    function getdata($table, $intmesin=0,$from=null, $to=null){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcmodel, 
                                IFNULL(d.vcnama, "") as vccell, 
                                IFNULL(f.vcnama, "") as vcshift, 
                                IFNULL(e.vckode, "") as vcmesin, 
                                IFNULL(g.vcnama, "") as vcgedung,
                                IFNULL(h.vcnama, "") as vcoperator, 
                                IFNULL(i.vcnama, "") as vcleader,
                                IFNULL(j.vcnama, "") as vcmodel,
                                IFNULL(k.vcnama, "") as vckomponen,
                                IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                                IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_models as c', 'a.intmodel = c.intid', 'left');
        $this->db->join('m_cell as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_shift as f', 'a.intshift = f.intid', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_gedung as g', 'a.intgedung = g.intid', 'left');
        $this->db->join('m_karyawan as h', 'h.intid = a.intoperator', 'left');
        $this->db->join('m_karyawan as i', 'i.intid = a.intleader', 'left');
        $this->db->join('m_models as j', 'j.intid = a.intmodel', 'left');
        $this->db->join('m_komponen as k', 'k.intid = a.intkomponen', 'left');
        if ($from) {
          $this->db->where('date(a.dttanggal) >= "' . $from . '"');
          $this->db->where('date(a.dttanggal) <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intgedung=0,$from=null, $to=null){
        $this->db->select('a.*,IFNULL(e.vckode, "") as vcmesin,
                            IFNULL(c.vcnama, "") as vcgedung,
                            IFNULL(d.vcnama, "") as vcoperator,  
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_gedung as c', 'e.intgedung = c.intid', 'left');
        $this->db->join('m_karyawan as d', 'd.intid = a.intkaryawan', 'left');
        if ($from) {
          $this->db->where('date(a.dttanggal) >= "' . $from . '"');
          $this->db->where('date(a.dttanggal) <= "' . $to . '"');
        }

        if ($intgedung > 0) {
          $this->db->where('e.intgedung',$intgedung); 
        }
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
         $this->db->select('a.*,IFNULL(e.vckode, "") as vcmesin,
                            IFNULL(c.vcnama, "") as vcgedung,
                            IFNULL(d.vcnama, "") as vcoperator,  
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_gedung as c', 'e.intgedung = c.intid', 'left');
        $this->db->join('m_karyawan as d', 'd.intid = a.intkaryawan', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

}