<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KaryawanModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        return $this->db->get()->result();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, IFNULL(c.vcnama, "") as vcjabatan, IFNULL(d.vcnama, "") as vcgedung, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_jabatan' . ' as c', 'a.intjabatan = c.intid', 'left');
        $this->db->join('m_gedung' . ' as d', 'a.intgedung = d.intid', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, IFNULL(c.vcnama, "") as vcjabatan, IFNULL(d.vcnama, "") as vcgedung, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_jabatan' . ' as c', 'a.intjabatan = c.intid', 'left');
        $this->db->join('m_gedung' . ' as d', 'a.intgedung = d.intid', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->or_like('c.vcnama', $keyword);
        $this->db->or_like('d.vcnama', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.*, IFNULL(c.vcnama, "") as vcjabatan, IFNULL(d.vcnama, "") as vcgedung, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_jabatan' . ' as c', 'a.intjabatan = c.intid', 'left');
        $this->db->join('m_gedung' . ' as d', 'a.intgedung = d.intid', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    function ceknamakaryawan($vcnama, $intjabatan){
        $this->db->where('intjabatan',$intjabatan);
        $this->db->like('vcnama',$vcnama);
        return $this->db->get('m_karyawan')->result();
    }

}