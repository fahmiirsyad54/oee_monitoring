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
          $this->db->where('CONVERT(varchar(26),a.dttanggal,23) >= ', $from);
          $this->db->where('CONVERT(varchar(26),a.dttanggal,23) <= ', $to);
        }

        if ($intgedung > 0) {
          $this->db->where('b.intgedung',$intgedung); 
        }
        return $this->db->get()->result();
    }
 
    function getdata($table, $intmesin=0,$from=null, $to=null){
        $this->db->select('a.intid, a.dttanggal, a.vcpesan, a.intkaryawan, a.intmesin, a.dtupdate, a.intstatus, a.vckodemesin, 
                            ISNULL(c.vcnama, 0) as vcmodel, 
                            ISNULL(d.vcnama, 0) as vccell, 
                            ISNULL(f.vcnama, 0) as vcshift, 
                            ISNULL(e.vckode, 0) as vcmesin, 
                            ISNULL(g.vcnama, 0) as vcgedung,
                            ISNULL(h.vcnama, 0) as vcoperator, 
                            ISNULL(i.vcnama, 0) as vcleader,
                            ISNULL(j.vcnama, 0) as vcmodel,
                            ISNULL(k.vcnama, 0) as vckomponen,
                            ISNULL(b.vcnama, 0) as vcstatus, 
                            ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
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
          $this->db->where('(a.dttanggal) >=', $from);
          $this->db->where('(a.dttanggal) <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intgedung=0,$from=null, $to=null){
        $this->db->select('a.intid, a.dttanggal, a.vcpesan, a.intkaryawan, a.intmesin, a.dtupdate, a.intstatus, a.vckodemesin,
                            ISNULL(e.vckode, 0) as vcmesin,
                            ISNULL(c.vcnama, 0) as vcgedung,
                            ISNULL(d.vcnama, 0) as vcoperator,  
                            ISNULL(b.vcnama, 0) as vcstatus, 
                            ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_gedung as c', 'e.intgedung = c.intid', 'left');
        $this->db->join('m_karyawan as d', 'd.intid = a.intkaryawan', 'left');
        if ($from) {
          $this->db->where('CONVERT(varchar(26),a.dttanggal,23) >= ', $from);
          $this->db->where('CONVERT(varchar(26),a.dttanggal,23) <= ', $to);
        }

        if ($intgedung > 0) {
          $this->db->where('e.intgedung',$intgedung); 
        }
        $this->db->order_by('a.dttanggal','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
         $this->db->select('a.intid, a.dttanggal, a.vcpesan, a.intkaryawan, a.intmesin, a.dtupdate, a.intstatus, a.vckodemesin,
                            ISNULL(e.vckode, 0) as vcmesin,
                            ISNULL(c.vcnama, 0) as vcgedung,
                            ISNULL(d.vcnama, 0) as vcoperator,  
                            ISNULL(b.vcnama, 0) as vcstatus, 
                            ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_gedung as c', 'e.intgedung = c.intid', 'left');
        $this->db->join('m_karyawan as d', 'd.intid = a.intkaryawan', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

}