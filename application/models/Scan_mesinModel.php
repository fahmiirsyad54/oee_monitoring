<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scan_mesinModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $keyword='', $intgedung=0, $intcell=0){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        if ($intgedung > 0) {
            $this->db->where('a.intgedung',$intgedung);
        }

        if ($intcell > 0) {
            $this->db->where('a.intcell',$intcell);
        }
        $this->db->where("(a.vcjenis LIKE '%".$keyword."%' OR a.vckode LIKE '%".$keyword."%' OR a.vcnama LIKE '%".$keyword."%' OR c.vcnama LIKE '%".$keyword."%')");
        // $this->db->like('a.vcnama', $keyword);
        // $this->db->or_like('a.vckode', $keyword);
        // $this->db->or_like('c.vcnama', $keyword);
        // $this->db->or_like('a.vcjenis', $keyword);
        return $this->db->get()->result();
    }

    function getdata($table, $keyword='', $intgedung=0, $intcell=0){
        $this->db->select('a.*, 
                            IFNULL(e.vcnama, "") as vcgedung,
                            IFNULL(f.vcnama, "") as vccell,
                            IFNULL(c.vcnama, "") as vcbrand,
                            IFNULL(d.vcnama, "") as vcarea,
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        $this->db->join('m_area' . ' as d', 'a.intarea = d.intid', 'left');
        $this->db->join('m_gedung' . ' as e', 'a.intgedung = e.intid', 'left');
        $this->db->join('m_cell' . ' as f', 'a.intcell = f.intid', 'left');
        if ($intgedung > 0) {
            $this->db->where('a.intgedung',$intgedung);
        }

        if ($intcell > 0) {
            $this->db->where('a.intcell',$intcell);
        }
        $this->db->where("(a.vcjenis LIKE '%".$keyword."%' OR a.vckode LIKE '%".$keyword."%' OR a.vcnama LIKE '%".$keyword."%' OR c.vcnama LIKE '%".$keyword."%')");
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword='', $intgedung=0, $intcell=0){
        $this->db->select('a.*, 
                            IFNULL(e.vcnama, "") as vcgedung,
                            IFNULL(f.vcnama, "") as vccell,
                            IFNULL(c.vcnama, "") as vcbrand,
                            IFNULL(d.vcnama, "") as vcarea,
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        $this->db->join('m_area' . ' as d', 'a.intarea = d.intid', 'left');
        $this->db->join('m_gedung' . ' as e', 'a.intgedung = e.intid', 'left');
        $this->db->join('m_cell' . ' as f', 'a.intcell = f.intid', 'left');
        if ($intgedung > 0) {
            $this->db->where('a.intgedung',$intgedung);
        }

        if ($intcell > 0) {
            $this->db->where('a.intcell',$intcell);
        }
        $this->db->where("(a.vcjenis LIKE '%".$keyword."%' OR a.vckode LIKE '%".$keyword."%' OR a.vcnama LIKE '%".$keyword."%' OR c.vcnama LIKE '%".$keyword."%')");
        // $this->db->like('a.vcnama', $keyword);
        // $this->db->or_like('a.vckode', $keyword);
        // $this->db->or_like('c.vcnama', $keyword);
        // $this->db->or_like('a.vcjenis', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcbrand, IFNULL(d.vcnama, "") as vcarea, IFNULL(e.vcnama, "") as vcgedung, IFNULL(f.vcnama, "") as vccell, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        $this->db->join('m_area' . ' as d', 'a.intarea = d.intid', 'left');
        $this->db->join('m_gedung' . ' as e', 'a.intgedung = e.intid', 'left');
        $this->db->join('m_cell' . ' as f', 'a.intcell = f.intid', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    function getdatadetail2($vckode){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcbrand, IFNULL(d.vcnama, "") as vcarea, IFNULL(e.vcnama, "") as vcgedung, IFNULL(f.vcnama, "") as vccell, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from('m_mesin as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        $this->db->join('m_area' . ' as d', 'a.intarea = d.intid', 'left');
        $this->db->join('m_gedung' . ' as e', 'a.intgedung = e.intid', 'left');
        $this->db->join('m_cell' . ' as f', 'a.intcell = f.intid', 'left');
        $this->db->where('a.vckode',$vckode);
        return $this->db->get()->result();
    }

    function getdataedit($intid){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcbrand, IFNULL(d.vcnama, "") as vcarea, IFNULL(e.vcnama, "") as vcgedung, IFNULL(f.vcnama, "") as vccell, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from('m_mesin as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        $this->db->join('m_area' . ' as d', 'a.intarea = d.intid', 'left');
        $this->db->join('m_gedung' . ' as e', 'a.intgedung = e.intid', 'left');
        $this->db->join('m_cell' . ' as f', 'a.intcell = f.intid', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }



    function getlastkode(){
        $this->db->select('vckode');
        $this->db->order_by('RIGHT(vckode,6) DESC');
        $this->db->limit(1);

        return $this->db->get('m_mesin')->result();
    }

    function _uploadImage($vcjenis) {
      $config['upload_path']   = './upload/mesin/';
      $config['allowed_types'] = 'gif|jpg|png';
      $config['file_name']     = 'MC-'.$vcjenis;
      $config['overwrite']     = true;
      $config['max_size']      = 1024; // 1MB
      // $config['max_width']            = 1024;
      // $config['max_height']           = 768;

      $this->load->library('upload', $config);

      if ($this->upload->do_upload('vcgambar')) {
          return $this->upload->data("file_name");
      }
  }

}