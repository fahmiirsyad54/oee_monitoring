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
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }
        return $this->db->get()->result();
    }
 
    function getdata($table, $keyword='',$from=null, $to=null){
        $this->db->select('a.*,
                            IFNULL(f.vcnama, "") as vctype_downtime,
                            IFNULL(g.vcnama, "") as vcdtmesin_type,
                            IFNULL(c.vcnama, "") as vcgedung,
                            IFNULL(d.vcnama, "") as vccell,
                            IFNULL(e.vcnama, "") as vcmesin,
                            IFNULL(e.vckode, "") as vckodemesin,
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus,
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'a.intgedung = c.intid', 'left');
        $this->db->join('m_cell' . ' as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_mesin' . ' as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_type_downtime' . ' as f', 'a.inttype_downtime = f.intid', 'left');
        $this->db->join('m_dtmesin_type' . ' as g', 'a.intdtmesin_type = g.intid', 'left');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }
        // $this->db->or_like('a.dttanggal', $keyword);
        // $this->db->or_like('c.vcnama', $keyword);
        // $this->db->or_like('d.vcnama', $keyword);
        $this->db->order_by('a.dttanggal','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword='',$from=null, $to=null){
        $this->db->select('a.*,  IFNULL(c.vcnama, "") as vcgedung, IFNULL(d.vcnama, "") as vccell, IFNULL(e.vckode, "") as vcmesin, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'a.intgedung = c.intid', 'left');
        $this->db->join('m_cell' . ' as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_mesin' . ' as e', 'a.intmesin = e.intid', 'left');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
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
        $this->db->select('a.*, 
                          IFNULL(c.vcnama, "") as vcgedung, 
                          IFNULL(d.vcnama, "") as vccell, 
                          IFNULL(e.vckode, "") as vckodemesin,
                          IFNULL(e.vcnama, "") as vcmesin, 
                          IFNULL(f.vcnama, "") as vcoperator, 
                          IFNULL(g.vcnama, "") as vcleader,
                          IFNULL(h.vcnama, "") as vctype_downtime,
                          IFNULL(i.vcnama, "") as vcdowntime, 
                          IFNULL(j.vcnama, "") as vcmekanik, 
                          IFNULL(k.vcnama, "") as vcsparepart, 
                          IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                          IFNULL(b.vcwarna, "") as vcstatuswarna',false);
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
      $this->db->select('a.*, IFNULL(b.vcnama, "") as vcdowntime_type, IFNULL(c.vcnama, "") as vclist_type, IFNULL(d.vcnama, "") as vcmekanik, IFNULL(e.vcnama, "") as vcsparepart',false);
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
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getdatamesin($table,$intgedung,$intcell){
        $this->db->where('intgedung',$intgedung);
        $this->db->where('intcell',$intcell);
        return $this->db->get($table)->result();
    }

    function ceknamakaryawan($vcnama, $intjabatan){
        $this->db->where('intjabatan',$intjabatan);
        $this->db->like('vcnama',$vcnama);
        return $this->db->get('m_karyawan')->result();
    }

}