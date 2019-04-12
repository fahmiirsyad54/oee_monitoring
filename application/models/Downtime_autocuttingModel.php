<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downtime_autocuttingModel extends CI_Model {

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
        $this->db->select('a.*, 
                          IFNULL(c.vcnama, "") as vcgedung, 
                          IFNULL(d.vcnama, "") as vccell,  
                          IFNULL(e.vckode, "") as vcmesin, 
                          IFNULL(f.vcnama, "") as vcoperator, 
                          IFNULL(g.vcnama, "") as vcleader,
                          IFNULL(h.vcnama, "") as vcsparepart, 
                          IFNULL(h.vcspesifikasi, "") as vcsparepartspek, 
                          IFNULL(i.vcnama, "") as vcmekanik,
                          IFNULL(j.vcnama, "") as vcdowntime,
                          IFNULL(k.vcnama, "") as vcshift, 
                          IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                          IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c', 'c.intid = a.intgedung', 'left');
        $this->db->join('m_cell as d', 'd.intid = a.intcell', 'left');
        $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        $this->db->join('m_karyawan as f', 'f.intid = a.intoperator', 'left');
        $this->db->join('m_karyawan as g', 'g.intid = a.intleader', 'left');
        $this->db->join('m_sparepart as h', 'h.intid = a.intsparepart', 'left');
        $this->db->join('m_karyawan as i', 'i.intid = a.intmekanik', 'left');
        $this->db->join('m_type_downtime_list as j','j.intid = a.inttype_list','left');
        $this->db->join('m_shift as k','k.intid = a.intshift','left');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->where('j.intplanned',0);
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intmesin=0,$from=null, $to=null){
        $this->db->select('a.*, 
              IFNULL(c.vcnama, "") as vcgedung, 
              IFNULL(d.vcnama, "") as vccell,  
              IFNULL(e.vckode, "") as vcmesin, 
              IFNULL(f.vcnama, "") as vcoperator, 
              IFNULL(g.vcnama, "") as vcleader,
              IFNULL(h.vcnama, "") as vcsparepart, 
              IFNULL(h.vcspesifikasi, "") as vcsparepartspek, 
              IFNULL(i.vcnama, "") as vcmekanik,
              IFNULL(j.vcnama, "") as vcdowntime,
              IFNULL(k.vcnama, "") as vcshift, 
              IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
              IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c', 'c.intid = a.intgedung', 'left');
        $this->db->join('m_cell as d', 'd.intid = a.intcell', 'left');
        $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        $this->db->join('m_karyawan as f', 'f.intid = a.intoperator', 'left');
        $this->db->join('m_karyawan as g', 'g.intid = a.intleader', 'left');
        $this->db->join('m_sparepart as h', 'h.intid = a.intsparepart', 'left');
        $this->db->join('m_karyawan as i', 'i.intid = a.intmekanik', 'left');
        $this->db->join('m_type_downtime_list as j','j.intid = a.inttype_list','left');
        $this->db->join('m_shift as k','k.intid = a.intshift','left');
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
        $this->db->select('a.*, 
                          IFNULL(c.vcnama, "") as vcgedung, 
                          IFNULL(d.vcnama, "") as vccell,  
                          IFNULL(e.vckode, "") as vcmesin, 
                          IFNULL(f.vcnama, "") as vcoperator, 
                          IFNULL(g.vcnama, "") as vcleader,
                          IFNULL(h.vcnama, "") as vcsparepart, 
                          IFNULL(h.vcspesifikasi, "") as vcsparepartspek, 
                          IFNULL(i.vcnama, "") as vcmekanik,
                          IFNULL(j.vcnama, "") as vcdowntime,
                          IFNULL(k.vcnama, "") as vcshift, 
                          IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                          IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c', 'c.intid = a.intgedung', 'left');
        $this->db->join('m_cell as d', 'd.intid = a.intcell', 'left');
        $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        $this->db->join('m_karyawan as f', 'f.intid = a.intoperator', 'left');
        $this->db->join('m_karyawan as g', 'g.intid = a.intleader', 'left');
        $this->db->join('m_sparepart as h', 'h.intid = a.intsparepart', 'left');
        $this->db->join('m_karyawan as i', 'i.intid = a.intmekanik', 'left');
        $this->db->join('m_type_downtime_list as j','j.intid = a.inttype_list','left');
        $this->db->join('m_shift as k','k.intid = a.intshift','left');
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
      $this->db->select('a.*, 
            IFNULL(c.vcnama, "") as vcgedung, 
            IFNULL(d.vcnama, "") as vccell,  
            IFNULL(e.vckode, "") as vcmesin, 
            IFNULL(f.vcnama, "") as vcoperator, 
            IFNULL(g.vcnama, "") as vcleader,
            IFNULL(h.vcnama, "") as vcsparepart, 
            IFNULL(h.vcspesifikasi, "") as vcsparepartspek, 
            IFNULL(i.vcnama, "") as vcmekanik,
            IFNULL(j.vcnama, "") as vcdowntime,
            IFNULL(k.vcnama, "") as vcshift, 
            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
      $this->db->from($table . ' as a');
      $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
      $this->db->join('m_gedung as c', 'c.intid = a.intgedung', 'left');
      $this->db->join('m_cell as d', 'd.intid = a.intcell', 'left');
      $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
      $this->db->join('m_karyawan as f', 'f.intid = a.intoperator', 'left');
      $this->db->join('m_karyawan as g', 'g.intid = a.intleader', 'left');
      $this->db->join('m_sparepart as h', 'h.intid = a.intsparepart', 'left');
      $this->db->join('m_karyawan as i', 'i.intid = a.intmekanik', 'left');
      $this->db->join('m_type_downtime_list as j','j.intid = a.inttype_list','left');
      $this->db->join('m_shift as k','k.intid = a.intshift','left');
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
        $this->db->select('a.*, 
                          IFNULL(c.vcnama, "") as vcgedung, 
                          IFNULL(d.vcnama, "") as vccell, 
                          IFNULL(e.vckode, "") as vckodemesin, 
                          IFNULL(e.vcnama, "") as vcmesin, 
                          IFNULL(f.vcnama, "") as vcoperator, 
                          IFNULL(g.vcnama, "") as vcleader,
                          IFNULL(h.vcnama, "") as vcsparepart, 
                          IFNULL(h.vcspesifikasi, "") as vcsparepartspek, 
                          IFNULL(i.vcnama, "") as vcmekanik, 
                          IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                          IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c', 'c.intid = a.intgedung', 'left');
        $this->db->join('m_cell as d', 'd.intid = a.intcell', 'left');
        $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        $this->db->join('m_karyawan as f', 'f.intid = a.intoperator', 'left');
        $this->db->join('m_karyawan as g', 'g.intid = a.intleader', 'left');
        $this->db->join('m_sparepart as h', 'h.intid = a.intsparepart', 'left');
        $this->db->join('m_karyawan as i', 'i.intid = a.intmekanik', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    function getdowntime($intid){
      $this->db->select('a.*, IFNULL(b.vcnama, "") as vcdowntime_type, IFNULL(c.vcnama, "") as vclist_type, IFNULL(d.vcnama, "") as vcmekanik, IFNULL(e.vcnama, "") as vcsparepart',false);
      $this->db->from('pr_downtime_detail as a');
      $this->db->join('m_type_downtime as b', 'b.intid = a.inttype_downtime', 'left');
      $this->db->join('m_type_downtime_list as c', 'c.intid = a.inttype_list', 'left');
      $this->db->join('m_karyawan as d', 'd.intid = a.intmekanik', 'left');
      $this->db->join('m_sparepart as e', 'b.intid = a.intsparepart', 'left');
      $this->db->where('a.intheader',$intid);
      return $this->db->get()->result(); 
    }

    function getdowntimelist(){
      $this->db->where('intautocutting',1);

      return $this->db->get('m_type_downtime_list')->result();
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

    function getmasterdowntime(){
      $this->db->where('intautocutting',1);
      $this->db->where('intplanned',0);

      return $this->db->get('m_type_downtime_list')->result();
    }

    function getgedungautocutting(){
      $this->db->where('intoeemonitoring > ', 0);

      return $this->db->get('m_gedung')->result();
    }

    function getcountdowntime($intmesin=0, $inttype_list, $from=null, $to=null){
      $this->db->select('COUNT(intid) as decjumlahdt');

      $this->db->where('intmesin', $intmesin);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('DATE(dttanggal) >=', $from);
      $this->db->where('DATE(dttanggal) <=', $to);

      return $this->db->get('pr_downtime2')->result();
    }

    function getcountdowntimegedung($intgedung=0, $inttype_list, $from=null, $to=null){
      $this->db->select('COUNT(intid) as decjumlahdt');

      $this->db->where('intgedung', $intgedung);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('DATE(dttanggal) >=', $from);
      $this->db->where('DATE(dttanggal) <=', $to);

      return $this->db->get('pr_downtime2')->result();
    }

}