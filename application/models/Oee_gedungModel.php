<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OperatorModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getdatadowntimeD($datestart,$datefinish,$intmesin){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, a.dtmulai, a.dtselesai, a.intsparepart, a.intjumlah, a.intstatus, a.intleader, a.dtupdate, SUM(a.decdurasi) as jmldowntime',false);
        $this->db->from('pr_downtime2 as a');
        $this->db->join();
        $this->db->where("a.dttanggal >= '" . $datestart . "'");
        $this->db->where("a.dttanggal <= '" . $datefinish . "'");
        $this->db->where('a.intmesin', $intmesin);

        return $this->db->get()->result();
    }

    function getdataoutputD($datestart,$datefinish,$intmesin){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, a.intleader, a.intshift, intmodel, a.intkomponen,
                           a.decct, a.dtmulai, a.dtselesai, a.decdurasi, a.intpasang, a.intreject, a.inttarget, a.dtupdate, a.intstatus,
                           a.vcketerangan, count(a.intid) as jmlid, SUM(a.decct) as jmlct, SUM(a.intpasang) as jmlpasang, SUM(a.intreject) as jmlreject',false);
        $this->db->from('pr_output as a');
        $this->db->where("dttanggal >= '" . $datestart . "'");
        $this->db->where("dttanggal <= '" . $datefinish . "'");
        $this->db->where('intmesin', $intmesin);

        return $this->db->get()->result();
    }

    function getdataloginD() {
        $this->db->select('a.intid, a.dtlogin', false);
        $this->db->from('a_log_login as a');
        $this->db->join('app_muser as b','b.intid = a.intuser','left');
        $this->db->where('a.intuser = b.intid');
        $this->db->order_by('a.dtlogin','DESC');
        $this->db->limit(1);

        return $this->db->get()->result();
    }

    function getkomponen($intid){
        $this->db->select('a.intid, a.intheader, a.intkomponen, a.deccycle_time, a.intlayer, b.vcnama as vckomponen',false);
        $this->db->from('m_models_komponen as a');
        $this->db->join('m_komponen as b','b.intid = a.intkomponen');
        $this->db->where('a.intheader',$intid);

        return $this->db->get()->result();
    }
   
}