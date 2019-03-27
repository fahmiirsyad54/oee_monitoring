<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AksesModel extends CI_Model {

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdata($table){
        return $this->db->get($table)->result();
    }

    function auth($vcusername,$vcpassword){
    	$this->db->where('vcusername',$vcusername);
    	$this->db->where('vcpassword',$vcpassword);
        $this->db->where('intstatus',1);
    	return $this->db->get('app_muser')->result();
    }

    function authoee($vcusername,$vcpassword){
        $this->db->select('a.*, b.vckode as vckodehakakses');
        $this->db->join('app_mhakakses b','b.intid = a.inthakakses');
        $this->db->where('a.vcusername',$vcusername);
        $this->db->where('a.vcpassword',$vcpassword);
        $this->db->where('a.intstatus',1);
        $this->db->where('(b.vckode = "SU" OR b.vckode = "TD" OR b.vckode = "OEEME" OR b.vckode = "OEEBD")');
        return $this->db->get('app_muser as a')->result();
    }

    function karyawanvalidasi($intmesin,$nik){
        $this->db->select('a.intid as intmesin, a.vckode as vckodemesin, a.intgedung as intgedung, a.intcell as intcell, b.intid as intkaryawan, b.vcnama as vckaryawan, b.vckode as vcnik',false);
        $this->db->from('m_mesin as a');
        $this->db->join('m_karyawan as b','a.intgedung = b.intgedung');
        $this->db->where('a.intid',$intmesin);
        $this->db->where('b.vckode',$nik);

        return $this->db->get()->result();
    }
}