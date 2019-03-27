<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SparepartModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.vcspesifikasi, a.vcpart, IFNULL(c.vcnama, "") as vcunit, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_unit' . ' as c', 'a.intunit = c.intid', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.vcspesifikasi, a.vcpart, IFNULL(c.vcnama, "") as vcunit, IFNULL(d.vcnama, "") as vcjenis, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_unit' . ' as c', 'a.intunit = c.intid', 'left');
        $this->db->join('m_jenis_sparepart' . ' as d', 'a.intjenis = d.intid', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('c.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->or_like('a.vcspesifikasi', $keyword);
        $this->db->or_like('a.vcpart', $keyword);
        $this->db->or_like('c.vcnama', $keyword);
        $this->db->or_like('d.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->order_by('a.intid','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }
     function getdatadetail($table,$intid){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcunit, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
         $this->db->join('m_unit' . ' as c', 'a.intunit = c.intid', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    public function buat_kode($jenis)   {
          $this->db->select('SUBSTRING(vckode,3) as kode', FALSE);
          $this->db->where('SUBSTRING(vckode,1,2)',$jenis);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('m_sparepart'); 
          if($query->num_rows() <> 0){
           $data = $query->row();
           $kode = intval($data->kode) + 1;    
          }
          else {
           $kode = 1;
          }
          $kodejadi = $jenis.$kode;
          return $kodejadi;  
    }

    public function get_last_name($intgedung)   {
          $this->db->select ('substr(vcnama, 7) as vcnama', FALSE);
          $this->db->where('intgedung',$intgedung);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          return $this->db->get('m_cell')->result();      //cek dulu apakah ada sudah ada kode di tabel.    
         
    }

}