<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_operatorModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
    function getdata($table, $keyword='', $intgedung=0, $intshift=0, $intlogin=0){
        $this->db->select('a.intid, a.intstatus, a.intlogin, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        //$this->db->like('a.vcnama', $keyword);
        $this->db->order_by('a.dtlogin','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword='', $intgedung=0, $intshift=0, $intlogin=0){
        $this->db->select('a.intid, IFNULL(c.vcusername, "") as vcmesin, IFNULL(d.vcnama, "") as vcoperator, IFNULL(e.vcnama, "") as vcshift, IFNULL(f.vcnama, "") as vcgedung, a.intlogin, a.dtlogin',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_muser' . ' as c', 'a.intuser = c.intid', 'left');
         $this->db->join('m_karyawan' . ' as d', 'a.intkaryawan = d.intid', 'left');
         $this->db->join('m_shift' . ' as e', 'a.intshift = e.intid', 'left');
         $this->db->join('m_gedung' . ' as f', 'd.intgedung = f.intid', 'left');
          if ($intgedung > 0) {
                $this->db->where('f.intid',$intgedung);
            }

            if ($intshift > 0) {
                $this->db->where('a.intshift',$intshift);
            }

            if ($intlogin > 0) {
                $this->db->where('a.intlogin',$intlogin);
            }

            $this->db->where("(c.vcusername LIKE '%".$keyword."%' OR d.vcnama LIKE '%".$keyword."%' OR e.vcnama LIKE '%".$keyword."%' OR f.vcnama LIKE '%".$keyword."%')");
            
         // $this->db->like('c.vcusername', $keyword);
         // $this->db->like('d.vcnama', $keyword);
         // $this->db->like('e.vcnama', $keyword);
         // $this->db->like('f.vcnama', $keyword);
         // $this->db->group_by('a.intid');
         $this->db->order_by('a.dtlogin','desc');
         $this->db->order_by('a.intid','desc');
         $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
         $this->db->select('a.*, IFNULL(c.vcusername, "") as vcmesin, IFNULL(d.vcnama, "") as vcoperator, IFNULL(e.vcnama, "") as vcshift',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_muser' . ' as c', 'a.intuser      = c.intid', 'left');
         $this->db->join('m_karyawan' . ' as d', 'a.intkaryawan = d.intid', 'left');
         $this->db->join('m_shift' . ' as e', 'a.intshift       = e.intid', 'left');
         $this->db->where('a.intid',$intid);
       return $this->db->get()->result();
    }

    function getjmldata($table, $keyword='', $intgedung=0, $intshift=0, $intlogin=0){
         $this->db->select('count(a.intid) as jmldata, a.intlogin',false);
         $this->db->from($table . ' as a');
         $this->db->join('app_muser' . ' as c', 'a.intuser = c.intid', 'left');
         $this->db->join('m_karyawan' . ' as d', 'a.intkaryawan = d.intid', 'left');
         $this->db->join('m_shift' . ' as e', 'a.intshift = e.intid', 'left');
         $this->db->join('m_gedung' . ' as f', 'd.intgedung = f.intid', 'left');
            if ($intgedung > 0) {
                $this->db->where('f.intid',$intgedung);
            }

            if ($intshift > 0) {
                $this->db->where('a.intshift',$intshift);
            }
            if ($intlogin > 0) {
                $this->db->where('a.intlogin',$intlogin);
            }
            $this->db->where("(c.vcusername LIKE '%".$keyword."%' OR d.vcnama LIKE '%".$keyword."%' OR e.vcnama LIKE '%".$keyword."%' OR f.vcnama LIKE '%".$keyword."%')");

        return $this->db->get()->result();
    }

    function getuser () {
        $this->db->select('a.*',false);
        $this->db->from('app_muser as a');
        return $this->db->get()->result();
    }

    function getkaryawan () {
        $this->db->select('a.*',false);
        $this->db->from('m_karyawan as a');
        return $this->db->get()->result();
    }
}