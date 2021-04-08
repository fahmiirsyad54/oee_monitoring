<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downtime_laserModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $intmesin=0, $from=null, $to=null, $intdowntime=0){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        if ($intdowntime > 0) {
          $this->db->where('a.inttype_list',$intdowntime); 
        }

        return $this->db->get()->result();
    }
 
    function getdata($table, $intmesin=0,$from=null, $to=null, $intdowntime=0){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, 
                          a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, CONVERT(varchar(8),a.dtmulai,108) as dtmulai,
                          CONVERT(varchar(8),a.dtselesai,108) as dtselesai, 
                           a.intsparepart, a.intjumlah, a.intstatus, a.intleader, a.dtupdate, 
                          ISNULL(c.vcnama, 0) as vcgedung, 
                          ISNULL(d.vcnama, 0) as vccell,  
                          ISNULL(e.vckode, 0) as vcmesin, 
                          ISNULL(f.vcnama, 0) as vcoperator, 
                          ISNULL(g.vcnama, 0) as vcleader,
                          ISNULL(h.vcnama, 0) as vcsparepart, 
                          ISNULL(h.vcspesifikasi, 0) as vcsparepartspek, 
                          ISNULL(i.vcnama, 0) as vcmekanik,
                          ISNULL(j.vcnama, 0) as vcdowntime,
                          ISNULL(k.vcnama, 0) as vcshift, 
                          ISNULL(b.vcnama, 0) as vcstatus, 
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
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
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        if ($intdowntime > 0) {
          $this->db->where('a.inttype_list',$intdowntime); 
        }

        //$this->db->where('j.intplanned',0);
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intmesin=0,$from=null, $to=null, $intdowntime=0){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, 
                        a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, CONVERT(varchar(8),a.dtmulai,108) as dtmulai,
                        CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.intsparepart, a.intjumlah, 
                        a.intstatus, a.intleader, a.dtupdate, 
                          ISNULL(c.vcnama, 0) as vcgedung, 
                          ISNULL(d.vcnama, 0) as vccell,  
                          ISNULL(e.vckode, 0) as vcmesin, 
                          ISNULL(f.vcnama, 0) as vcoperator, 
                          ISNULL(g.vcnama, 0) as vcleader,
                          ISNULL(h.vcnama, 0) as vcsparepart, 
                          ISNULL(h.vcspesifikasi, 0) as vcsparepartspek, 
                          ISNULL(i.vcnama, 0) as vcmekanik,
                          ISNULL(j.vcnama, 0) as vcdowntime,
                          ISNULL(k.vcnama, 0) as vcshift, 
                          ISNULL(b.vcnama, 0) as vcstatus, 
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
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
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        if ($intdowntime > 0) {
          $this->db->where('a.inttype_list',$intdowntime); 
        }

        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

     function getjmldatapershift($table, $intmesin=0, $from=null, $to=null, $intshift, $intdowntime=0){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        if ($intdowntime > 0) {
          $this->db->where('a.inttype_list',$intdowntime); 
        }

        $this->db->where('a.intshift', $intshift);
        return $this->db->get()->result();
    }
 
    function getdatapershift($table, $intmesin=0,$from=null, $to=null, $intshift, $intdowntime=0){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, 
                          a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, 
                          CONVERT(varchar(8),a.dtmulai,108) as dtmulai,
                          CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.intsparepart, a.intjumlah, 
                          a.intstatus, a.intleader, a.dtupdate, 
                          ISNULL(c.vcnama, 0) as vcgedung, 
                          ISNULL(d.vcnama, 0) as vccell,  
                          ISNULL(e.vckode, 0) as vcmesin, 
                          ISNULL(f.vcnama, 0) as vcoperator, 
                          ISNULL(g.vcnama, 0) as vcleader,
                          ISNULL(h.vcnama, 0) as vcsparepart, 
                          ISNULL(h.vcspesifikasi, 0) as vcsparepartspek, 
                          ISNULL(i.vcnama, 0) as vcmekanik,
                          ISNULL(j.vcnama, 0) as vcdowntime,
                          ISNULL(k.vcnama, 0) as vcshift, 
                          ISNULL(b.vcnama, 0) as vcstatus, 
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
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
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        if ($intdowntime > 0) {
          $this->db->where('a.inttype_list',$intdowntime); 
        }

        $this->db->where('a.intshift', $intshift);
        $this->db->order_by('a.dtupdate','desc');

        return $this->db->get()->result();
    }
    
    function getdatalimitpershift($table,$halaman=0, $limit=5, $intmesin=0,$from=null, $to=null, $intshift, $intdowntime=0){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, 
                        a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, 
                        CONVERT(varchar(8),a.dtmulai,108) as dtmulai, 
                        CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.intsparepart, a.intjumlah, 
                        a.intstatus, a.intleader, a.dtupdate,
                        ISNULL(c.vcnama, 0) as vcgedung, 
                        ISNULL(d.vcnama, 0) as vccell,  
                        ISNULL(e.vckode, 0) as vcmesin, 
                        ISNULL(f.vcnama, 0) as vcoperator, 
                        ISNULL(g.vcnama, 0) as vcleader,
                        ISNULL(h.vcnama, 0) as vcsparepart, 
                        ISNULL(h.vcspesifikasi, 0) as vcsparepartspek, 
                        ISNULL(i.vcnama, 0) as vcmekanik,
                        ISNULL(j.vcnama, 0) as vcdowntime,
                        ISNULL(k.vcnama, 0) as vcshift, 
                        ISNULL(b.vcnama, 0) as vcstatus, 
                        ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
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
      $this->db->where('a.dttanggal >= ', $from);
      $this->db->where('a.dttanggal <= ', $to);
      }

      if ($intmesin > 0) {
      $this->db->where('a.intmesin',$intmesin); 
      }
      if ($intdowntime > 0) {
        $this->db->where('a.inttype_list',$intdowntime); 
      }

      $this->db->where('a.intshift', $intshift);
      $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }


    function getdatadetail($table,$intid){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, 
                          a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, 
                          CONVERT(varchar(8),a.dtmulai,108) as dtmulai, 
                          CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.intsparepart, a.intjumlah, 
                          a.intstatus, a.intleader, a.dtupdate,
                          ISNULL(c.vcnama, 0) as vcgedung, 
                          ISNULL(d.vcnama, 0) as vccell, 
                          ISNULL(e.vckode, 0) as vckodemesin, 
                          ISNULL(e.vcnama, 0) as vcmesin, 
                          ISNULL(f.vcnama, 0) as vcoperator, 
                          ISNULL(g.vcnama, 0) as vcleader,
                          ISNULL(h.vcnama, 0) as vcsparepart, 
                          ISNULL(h.vcspesifikasi, 0) as vcsparepartspek, 
                          ISNULL(i.vcnama, 0) as vcmekanik, 
                          ISNULL(b.vcnama, 0) as vcstatus, 
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
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
      $this->db->select('a.intid, a.intheader, a.inttype_downtime, a.inttype_list, a.intmekanik, a.dtmulai, a.dtselesai, a.decdurasi, a.intsparepart, a.intjumlah, ISNULL(b.vcnama, 0) as vcdowntime_type, ISNULL(c.vcnama, 0) as vclist_type, ISNULL(d.vcnama, 0) as vcmekanik, ISNULL(e.vcnama, 0) as vcsparepart',false);
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
      $this->db->where('intlaser',1);

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

      function getkaryawan($table,$intjabatan){
        $this->db->select('intid, vckode, vcnama');
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getdatakaryawan($table,$intgedung,$intjabatan){
        $this->db->where('intgedung',$intgedung);
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getdatamesin($intgedung=0, $intmesin=0){
        $this->db->select('intid, vckode, vcnama');
        if ($intgedung > 0) {
          $this->db->where('intgedung',$intgedung);
        }

        if ($intmesin > 0) {
          $this->db->where('intid',$intmesin);  
        }
        
        $this->db->where('intautocutting',2);
        $this->db->order_by('intsortall','asc');
        return $this->db->get('m_mesin')->result();
    }

    function getmesin(){
        $this->db->select('intid, vckode, vcnama');
        $this->db->where('intautocutting',2);
        return $this->db->get('m_mesin')->result();
    }

    function getmasterdowntime(){
      $this->db->where('intautocutting',1);
      //$this->db->where('intplanned',0);
      $this->db->where('intlaser',1);

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
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);

      return $this->db->get('pr_downtime3')->result();
    }

    function getcountdowntimeshift($intmesin=0, $inttype_list, $from=null, $to=null, $intshift){
      $this->db->select('COUNT(intid) as decjumlahdt');
      $this->db->where('intmesin', $intmesin);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);
      $this->db->where('intshift', $intshift);

      return $this->db->get('pr_downtime3')->result();
    }

    function getcountdowntimegedung($intgedung=0, $inttype_list, $from=null, $to=null){
      $this->db->select('COUNT(intid) as decjumlahdt');
      $this->db->where('intgedung', $intgedung);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);

      return $this->db->get('pr_downtime3')->result();
    }

    function getcountdowntimegedungshift($intgedung=0, $inttype_list, $from=null, $to=null, $intshift){
      $this->db->select('COUNT(intid) as decjumlahdt');
      $this->db->where('intgedung', $intgedung);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);
      $this->db->where('intshift', $intshift);

      return $this->db->get('pr_downtime3')->result();
    }

    function getdurasidowntime($intmesin=0, $inttype_list, $from=null, $to=null){
      $this->db->select('SUM(decdurasi) as decdurasidt');
      $this->db->where('intmesin', $intmesin);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);

      return $this->db->get('pr_downtime3')->result();
    }

    function getdurasidowntimeshift($intmesin=0, $inttype_list, $from=null, $to=null, $intshift){
      $this->db->select('SUM(decdurasi) as decdurasidt');
      $this->db->where('intmesin', $intmesin);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);
      $this->db->where('intshift', $intshift);

      return $this->db->get('pr_downtime3')->result();
    }

    function getdurasidowntimegedung($intgedung=0, $inttype_list, $from=null, $to=null){
      $this->db->select('SUM(decdurasi) as decdurasidt');
      $this->db->where('intgedung', $intgedung);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);

      return $this->db->get('pr_downtime3')->result();
    }

    function getdurasidowntimegedungshift($intgedung=0, $inttype_list, $from=null, $to=null, $intshift){
      $this->db->select('SUM(decdurasi) as decdurasidt');
      $this->db->where('intgedung', $intgedung);
      $this->db->where('inttype_list', $inttype_list);
      $this->db->where('convert(varchar(26),dttanggal,23) >=', $from);
      $this->db->where('convert(varchar(26),dttanggal,23) <=', $to);
      $this->db->where('intshift', $intshift);

      return $this->db->get('pr_downtime2')->result();
    }

}