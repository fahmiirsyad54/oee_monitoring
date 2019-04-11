<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CellModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, IFNULL(c.vcnama, "") as vcgedung, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'a.intgedung = c.intid', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, IFNULL(c.vcnama, "") as vcgedung, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'a.intgedung = c.intid', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('c.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->order_by('a.intid','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }
     function getdatadetail($table,$intid){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcgedung, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung' . ' as c', 'a.intgedung = c.intid', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }
        public function buat_kode()   {
          $this->db->select('RIGHT(m_brand.intid,3) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('m_brand');      //cek dulu apakah ada sudah ada kode di tabel.    
          if($query->num_rows() <> 0){      
           //jika kode ternyata sudah ada.      
           $data = $query->row();      
           $kode = intval($data->kode) + 1;    
          }
          else {      
           //jika kode belum ada      
           $kode = 1;    
          }
          $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
          $kodejadi = "BRND".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodejadi;  
    }
          public function buat_kode_cell()   {
          $this->db->select('RIGHT(m_cell.vckode,4) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('m_cell');      //cek dulu apakah ada sudah ada kode di tabel.    
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
          $kodejadi = "C".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodejadi;  
    }

    public function get_last_name($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 7) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_cutting($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 18) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_stitching($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 20) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_training($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 11) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_standby($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 10) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_nosew($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 8) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_emboss($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 17) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_hotpress($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 12) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_compoundrolling($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 20) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_uv($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 4) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_stockfit($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 10) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

    public function get_last_name_coating($intgedung,$inttype)   {
          $this->db->select ('substr(vcnama, 9) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->where('inttype',$inttype);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.       
    }

}