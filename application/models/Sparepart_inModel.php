<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart_inModel extends CI_Model { 

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
     function getjmldata($table, $intsparepart=0, $from='', $to=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($intsparepart != 0) {
          $this->db->where('a.intsparepart',$intsparepart);
        }

        if ($from != '') {
          $this->db->where('a.dtorder >=', $from);
          $this->db->where('a.dtorder <=', $to);
        }

        $this->db->where('a.intinout',1);

        return $this->db->get()->result();
    }
 
    function getdata($table, $intsparepart=0, $from='', $to=''){
        $this->db->select('a.intid, a.vckode, a.vcnomor_po, a.dtorder, a.dtinout, a.decqtymasuk, a.decharga, a.dectotal, a.intstatus,
                          ISNULL(c.vcnama, 0) as vcsparepart,  
                          ISNULL(c.vcspesifikasi, 0) as vcspesifikasi, 
                          ISNULL(c.vcpart, 0) as vcpart, 
                          ISNULL(d.vcnama, 0) as vcunit, 
                          ISNULL(e.vcnama, 0) as vcsuplier,  
                          ISNULL(b.vcnama, 0) as vcstatus, 
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left'); 
        $this->db->join('m_sparepart' . ' as c', 'a.intsparepart = c.intid', 'left');
        $this->db->join('m_unit' . ' as d', 'c.intunit = d.intid', 'left');
        $this->db->join('m_suplier' . ' as e', 'a.intsuplier = e.intid', 'left');
        $this->db->where('a.intinout',1);
        if ($intsparepart != 0) {
          $this->db->where('a.intsparepart',$intsparepart);
        }

        if ($from != '') {
          $this->db->where('a.dtorder >=',$from);
          $this->db->where('a.dtorder <=',$to);
        }
        $this->db->order_by('a.intstatus','desc');
        $this->db->order_by('a.dtorder','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intsparepart=0, $from='', $to=''){
        $this->db->select('a.intid, a.vckode,
                          ISNULL(c.vcnama, 0) as vcsparepart,
                          ISNULL(c.vcspesifikasi, 0) as vcspesifikasi,
                          ISNULL(c.vcpart, 0) as vcpart,
                          ISNULL(d.vcnama, 0) as vcunit,
                          a.vcnomor_po,
                          a.dtorder,
                          a.dtinout,
                          a.intinout,
                          ISNULL(e.vcnama, 0) as vcsuplier,
                          a.intstatus,
                          a.decqtymasuk,
                          a.decharga,
                          a.dectotal,
                          ISNULL(b.vcnama, 0) as vcstatus,
                          ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_sparepart' . ' as c', 'a.intsparepart = c.intid', 'left');
        $this->db->join('m_unit' . ' as d', 'c.intunit = d.intid', 'left');
        $this->db->join('m_suplier' . ' as e', 'a.intsuplier = e.intid', 'left');
        $this->db->where('a.intinout',1);
        if ($intsparepart != 0) {
          $this->db->where('intsparepart',$intsparepart);
        }

        if ($from != '') {
          $this->db->where('a.dtorder >=',$from);
          $this->db->where('a.dtorder <=',$to);
        }
        $this->db->order_by('a.intstatus','desc');
        $this->db->order_by('a.dtorder','desc');
        $this->db->order_by('a.intid','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }
     function getdatadetail($table,$intid){
        $this->db->select('a.*, ISNULL(c.vcnama, 0) as vcsparepart, ISNULL(d.vcnama, 0) as vcsuplier, ISNULL(b.vcnama, 0) as vcstatus, ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_sparepart' . ' as c', 'a.intsparepart = c.intid', 'left');
        $this->db->join('m_suplier' . ' as d', 'a.intsuplier = d.intid', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }
        public function buat_kode()   {
          $this->db->select('RIGHT(pr_sparepart.intid,3) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('pr_sparepart');      //cek dulu apakah ada sudah ada kode di tabel.    
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
          $kodejadi = "SPI".$kodemax;    // hasilnya ODJ-9921-0001 dst.
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