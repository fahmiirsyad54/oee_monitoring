<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OutputModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
    function getjmldata($table, $intmesin=0, $from=null, $to=null){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
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
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intmesin=0,$from=null, $to=null){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcmodel, IFNULL(d.vcnama, "") as vccell, IFNULL(f.vcnama, "") as vcshift, IFNULL(e.vckode, "") as vcmesin, IFNULL(g.vcnama, "") as vckomponen, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_models as c', 'a.intmodel = c.intid', 'left');
        $this->db->join('m_cell as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_shift as f', 'a.intshift = f.intid', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_komponen as g', 'a.intkomponen = g.intid', 'left');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getjmldatapershift($table, $intmesin=0, $from=null, $to=null, $intshift){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->where('a.intshift', $intshift);

        return $this->db->get()->result();
    }
 
    function getdatapershift($table, $intmesin=0,$from=null, $to=null, $intshift){
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
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->where('a.intshift', $intshift);
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimitpershift($table,$halaman=0, $limit=5, $intmesin=0,$from=null, $to=null, $intshift){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcmodel, IFNULL(d.vcnama, "") as vccell, IFNULL(f.vcnama, "") as vcshift, IFNULL(e.vckode, "") as vcmesin, IFNULL(g.vcnama, "") as vckomponen, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_models as c', 'a.intmodel = c.intid', 'left');
        $this->db->join('m_cell as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_shift as f', 'a.intshift = f.intid', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_komponen as g', 'a.intkomponen = g.intid', 'left');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->where('a.intshift', $intshift);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
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
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    public function buat_kode()   {
          $this->db->select('RIGHT(pr_downtime.vckode,4) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('pr_downtime');      //cek dulu apakah ada sudah ada kode di tabel.    
          if($query->num_rows() <> 0){      
           //jika kode ternyata sudah ada.      
           $data = $query->row();      
           $kode = intval($data->kode) + 1;    
          }
          else {      
           //jika kode belum ada      
           $kode = 1;    
          }
          $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
          $kodejadi = "DT".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodejadi;  
    }

    function getdatakaryawan($table,$intgedung,$intjabatan){
        $this->db->where('intgedung',$intgedung);
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getdatamesin($intgedung=0, $intmesin=0){
      if ($intgedung > 0) {
        $this->db->where('intgedung',$intgedung);
      }

      if ($intmesin > 0) {
        $this->db->where('intid',$intmesin);  
      }
      
      $this->db->where('intautocutting',1);
      $this->db->order_by('vcnama');
      return $this->db->get('m_mesin')->result();
    }

    function getkomponen($intid){
        $this->db->select('a.*, b.vcnama as vckomponen',false);
        $this->db->from('m_models_komponen as a');
        $this->db->join('m_komponen as b','b.intid = a.intkomponen');
        $this->db->where('a.intheader',$intid);

        return $this->db->get()->result();
    }

    function getintkomponen($intkomponen){
        $this->db->select('*',false);
        $this->db->from('m_models_komponen');
        $this->db->where('intkomponen',$intkomponen);
      
      return $this->db->get()->result(); 
    }

    function getdatacombine($intmesincombine, $intoperatorcombine, $dttanggalcombine, $dtmulaicombine, $dtselesaicombine){
      $this->db->where('intmesin',$intmesincombine);
      $this->db->where('intoperator',$intoperatorcombine);
      $this->db->where('dtmulai',$dtmulaicombine);
      $this->db->where('dtselesai',$dtselesaicombine);
      $this->db->where('DATE(dttanggal)',$dttanggalcombine);

      return $this->db->get('pr_output')->result();
    }

}