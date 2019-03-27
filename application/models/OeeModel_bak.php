<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OeeModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->like('.a.vckode', $keyword);
        return $this->db->get()->result();
    }
 
    function getdata($table, $keyword='',$from=null, $to=null){
        $this->db->select('a.*, IFNULL(c.dttanggal, "") as dttanggal, IFNULL(d.vcnama, "") as vcgedung, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('pr_downtime' . ' as c', 'a.intdowntime = c.intid', 'left');
        $this->db->join('m_gedung' . ' as d', 'c.intgedung = d.intid', 'left');
        if ($from) {
          $this->db->where('a.dttanggal >= "' . $from . '"');
          $this->db->where('a.dttanggal <= "' . $to . '"');
        }
        $this->db->like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword='',$from=null, $to=null){
        $this->db->select('a.*,  IFNULL(c.dttanggal, "") as dttanggal, IFNULL(d.vcnama, "") as vcgedung, IFNULL(e.vcnama, "") as vccell, IFNULL(f.vckode, "") as vcmesin, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('pr_downtime' . ' as c', 'a.intdowntime = c.intid', 'left');
        $this->db->join('m_gedung' . ' as d', 'c.intgedung = d.intid', 'left');
        $this->db->join('m_cell' . ' as e', 'c.intcell = e.intid', 'left');
        $this->db->join('m_mesin' . ' as f', 'c.intmesin = f.intid', 'left');
        if ($from) {
        $this->db->where('a.dttanggal >= "' . $from . '"');
        $this->db->where('a.dttanggal <= "' . $to . '"');
        }
        $this->db->like('a.vckode', $keyword);
        $this->db->or_like('c.dttanggal', $keyword);
        $this->db->or_like('d.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.*,  IFNULL(c.dttanggal, "") as dttanggal, IFNULL(d.vcnama, "") as vcgedung, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
       $this->db->from($table . ' as a');
       $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
       $this->db->join('pr_downtime' . ' as c', 'a.intdowntime = c.intid', 'left');
       $this->db->join('m_gedung' . ' as d', 'c.intgedung = d.intid', 'left');
       $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    public function buat_kode()   {
          $this->db->select('RIGHT(pr_oee.vckode,4) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('pr_oee');      //cek dulu apakah ada sudah ada kode di tabel.    
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
          $kodejadi = "OEE".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodejadi;  
    }

    function getdatadowntime(){
      $this->db->select('a.intid, a.dttanggal, IFNULL(b.vcnama, "") as vcshift, IFNULL(c.vckode, "") as vcmesin, a.decdurasi_proses, a.decdurasi_mesin', FALSE);
      $this->db->from('pr_downtime as a');
      $this->db->join('m_mesin as c', 'a.intmesin = c.intid', 'left');
      $this->db->join('m_shift as b', 'a.intshift = b.intid', 'left');
      $this->db->order_by('a.dttanggal','desc');

      return $this->db->get()->result();
    }

    function getdatadowntime2(){
      $this->db->select('a.intid, a.decdurasi_proses, a.decdurasi_mesin', FALSE);
      $this->db->from('pr_downtime as a');
      $this->db->where('a.intid');
      $this->db->order_by('a.intid');

      return $this->db->get()->result();
    }

    function getdatalembur() {
      $this->db->select('a.intid, a.vcnama, a.intnilai', FALSE);
      $this->db->from('m_lembur as a');
      $this->db->order_by('a.intid');

      return $this->db->get()->result();
    }

}