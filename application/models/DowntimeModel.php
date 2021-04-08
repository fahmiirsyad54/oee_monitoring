<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DowntimeModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $from, $to){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }
        return $this->db->get()->result();
    }
 
    function getdata($table, $keyword='',$from=null, $to=null){
        $this->db->select('a.intid, a.vckode, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, 
                           a.intleader, a.inttype_downtime, a.inttype_list, a.intdtmesin_type, a.dtstart, a.dtstop, a.dtfinish,
                           a.dtrun, a.dtmaterialkosong, a.dtmaterialtersedia, a.vcmasalah, a.vcsolusi, a.intmekanik,
                           a.intsparepart, a.intjumlahsparepart, a.inttunggumekanik, a.intperbaikan, a.inttungguoperator, a.inttunggumaterial,
                           a.dtupdate, a.intstatus, a.vckodemesin,
                            ISNULL(f.vcnama, 0) as vctype_downtime,
                            ISNULL(g.vcnama, 0) as vcdtmesin_type,
                            ISNULL(c.vcnama, 0) as vcgedung,
                            ISNULL(d.vcnama, 0) as vccell,
                            ISNULL(e.vcnama, 0) as vcmesin,
                            ISNULL(e.vckode, 0) as vckodemesin,
                            ISNULL(b.vcnama, 0) as vcstatus,
                            ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'a.intgedung = c.intid', 'left');
        $this->db->join('m_cell' . ' as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_mesin' . ' as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_type_downtime' . ' as f', 'a.inttype_downtime = f.intid', 'left');
        $this->db->join('m_dtmesin_type' . ' as g', 'a.intdtmesin_type = g.intid', 'left');
        if ($from) {
          $this->db->where('a.dttanggal >=', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }
        // $this->db->or_like('a.dttanggal', $keyword);
        // $this->db->or_like('c.vcnama', $keyword);
        // $this->db->or_like('d.vcnama', $keyword);
        $this->db->order_by('a.dttanggal','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword='',$from=null, $to=null){
        $this->db->select('a.intid, a.vckode, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, 
                           a.intleader, a.inttype_downtime, a.inttype_list, a.intdtmesin_type, a.dtstart, a.dtstop, a.dtfinish,
                           a.dtrun, a.dtmaterialkosong, a.dtmaterialtersedia, a.vcmasalah, a.vcsolusi, a.intmekanik,
                           a.intsparepart, a.intjumlahsparepart, a.inttunggumekanik, a.intperbaikan, a.inttungguoperator, a.inttunggumaterial,
                           a.dtupdate, a.intstatus, a.vckodemesin,
                           ISNULL(c.vcnama, 0) as vcgedung, 
                           ISNULL(d.vcnama, 0) as vccell, 
                           ISNULL(e.vckode, 0) as vcmesin, 
                           ISNULL(b.vcnama, 0) as vcstatus, 
                           ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'a.intgedung = c.intid', 'left');
        $this->db->join('m_cell' . ' as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_mesin' . ' as e', 'a.intmesin = e.intid', 'left');
        if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }
        $this->db->like('a.vckode', $keyword);
        // $this->db->or_like('a.dttanggal', $keyword);
        // $this->db->or_like('c.vcnama', $keyword);
        // $this->db->or_like('d.vcnama', $keyword);
        $this->db->order_by('a.dttanggal','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.intid, a.vckode, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, 
                           a.intleader, a.inttype_downtime, a.inttype_list, a.intdtmesin_type, 
                           CONVERT(varchar(8),a.dtstop,108) as dtstop, CONVERT(varchar(8),a.dtstart,108) as dtstart,
                           CONVERT(varchar(8),a.dtfinish,108) as dtfinish, CONVERT(varchar(8),a.dtrun,108) as dtrun, 
                           CONVERT(varchar(8),a.dtmaterialkosong,108) as dtmaterialkosong, CONVERT(varchar(8),a.dtmaterialtersedia,108) as dtmaterialtersedia, 
                           a.vcmasalah, a.vcsolusi, a.intmekanik,
                           a.intsparepart, a.intjumlahsparepart, a.inttunggumekanik, a.intperbaikan, a.inttungguoperator, a.inttunggumaterial,
                           a.dtupdate, a.intstatus, a.vckodemesin, 
                          ISNULL(c.vcnama, 0) as vcgedung, 
                          ISNULL(d.vcnama, 0) as vccell, 
                          ISNULL(e.vckode, 0) as vckodemesin,
                          ISNULL(e.vcnama, 0) as vcmesin, 
                          ISNULL(f.vcnama, 0) as vcoperator, 
                          ISNULL(g.vcnama, 0) as vcleader,
                          ISNULL(h.vcnama, 0) as vctype_downtime,
                          ISNULL(i.vcnama, 0) as vcdowntime, 
                          ISNULL(j.vcnama, 0) as vcmekanik, 
                          ISNULL(k.vcnama, 0) as vcsparepart, 
                          ISNULL(b.vcnama, 0) as vcstatus, 
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'c.intid = a.intgedung', 'left');
        $this->db->join('m_cell' . ' as d', 'd.intid = a.intcell', 'left');
        $this->db->join('m_mesin' . ' as e', 'e.intid = a.intmesin', 'left');
        $this->db->join('m_karyawan' . ' as f', 'f.intid = a.intoperator', 'left');
        $this->db->join('m_karyawan' . ' as g', 'g.intid = a.intleader', 'left');
        $this->db->join('m_type_downtime' . ' as h', 'h.intid = a.inttype_downtime', 'left');
        $this->db->join('m_type_downtime_list' . ' as i', 'i.intid = a.inttype_list', 'left');
        $this->db->join('m_karyawan' . ' as j', 'j.intid = a.intmekanik', 'left');
        $this->db->join('m_sparepart' . ' as k', 'k.intid = a.intsparepart', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    function getdowntime($intid){
      $this->db->select('a.intid, a.intheader, a.inttype_downtime, a.inttype_list, a.intmekanik, a.dtmulai, a.dtselesai, a.decdurasi, a.vcmasalah,
                         a.vctindakan, a.intsparepart, a.intjumlah, 
                         ISNULL(b.vcnama, 0) as vcdowntime_type, 
                         ISNULL(c.vcnama, 0) as vclist_type, 
                         ISNULL(d.vcnama, 0) as vcmekanik, 
                         ISNULL(e.vcnama, 0) as vcsparepart',false);
      $this->db->from('pr_downtime_detail as a');
      $this->db->join('m_type_downtime' . ' as b', 'b.intid = a.inttype_downtime', 'left');
      $this->db->join('m_type_downtime_list' . ' as c', 'c.intid = a.inttype_list', 'left');
      $this->db->join('m_karyawan' . ' as d', 'd.intid = a.intmekanik', 'left');
      $this->db->join('m_sparepart' . ' as e', 'b.intid = a.intsparepart', 'left');
      $this->db->where('a.intheader',$intid);
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
        $this->db->select('intid, vckode, vcnama');
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getdatamesin($table){
        $this->db->select('intid, vckode, vcnama, intgedung, intcell');
        return $this->db->get($table)->result();
    }

    function ceknamakaryawan($vcnama, $intjabatan){
        $this->db->where('intjabatan',$intjabatan);
        $this->db->like('vcnama',$vcnama);
        return $this->db->get('m_karyawan')->result();
    }

    function getkaryawan($table,$intjabatan){
        $this->db->select('intid, vckode, vcnama');
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getdetailkaryawan(){
        $this->db->select('intid, vckode, vcnama');
        return $this->db->get('m_karyawan')->result();
    }

    function getdetailmesin(){
        $this->db->select('intid, vckode, vcnama');
        return $this->db->get('m_mesin')->result();
    }

}