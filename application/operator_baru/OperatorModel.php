<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OperatorModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function stoksparepart(){
        $query = 'SELECT SUM(`decqtymasuk`) - SUM(`decqtykeluar`) AS jumlah FROM pr_sparepart';
        return $this->db->query($query)->result();
    }

    function getdatalistdowntime(){
      $this->db->where('intautocutting',1);
      $this->db->where('intcomelz',1);
      
      return $this->db->get('m_type_downtime_list')->result();
    }

   function getdatadowntime($date1,$date2,$intmesin,$intshift){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, CONVERT(varchar(8),a.dtmulai,108) as dtmulai, CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.intsparepart, a.intjumlah, a.intstatus, a.intleader, a.dtupdate, b.vcnama as vcdowntime, ISNULL(c.vcnama,0) as vcmekanik, ISNULL(d.vcnama,0) as vcsparepart',false);
        $this->db->from('pr_downtime2 as a');
        $this->db->join('m_type_downtime_list as b','b.intid = a.inttype_list');
        $this->db->join('m_karyawan as c','c.intid = a.intmekanik','left');
        $this->db->join('m_sparepart as d','d.intid = a.intsparepart', 'left');
        $this->db->where("a.dttanggal >= '$date1' AND a.dttanggal <= '$date2' AND a.intmesin = $intmesin AND a.intshift = " . $intshift);
        $this->db->order_by('a.intid','DESC');
        return $this->db->get()->result();
    }

    function getdataoutput($date1,$date2,$intmesin,$intshift){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, a.intleader, a.intshift, intmodel, a.intkomponen,
                           a.decct, CONVERT(varchar(8),a.dtmulai,108) as dtmulai, CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.decdurasi, a.intpasang, a.intreject, a.inttarget, a.dtupdate, a.intstatus,
                           a.vcketerangan, ISNULL(b.vcnama,0) as vcmodel, ISNULL(c.vcnama,0) as vckomponen',false);
        $this->db->from('pr_output as a');
        $this->db->join('m_models as b','b.intid = a.intmodel');
        $this->db->join('m_komponen as c','c.intid = a.intkomponen','left');
        $this->db->where("a.dttanggal >= '$date1' AND a.dttanggal <= '$date2' AND a.intmesin = $intmesin AND a.intshift = " . $intshift);
        $this->db->order_by('a.intid','DESC');
        return $this->db->get()->result();
    }

    function getdatadowntimeD(){
        $shift1start   = strtotime('07:00:00');
        $shift1finish  = strtotime('20:00:00');
        
        $shift2start1  = strtotime('19:00:01');
        $shift2start2  = strtotime('00:00:00');
        $shift2finish1 = strtotime('23:59:59');
        $shift2finish2 = strtotime('07:00:00');
        $timenow       = time(date("H:i:s"));
        $intshift      = 0;
        
        $timestart  = '07:00:01';
        $datestart  = date('Y-m-d') . ' ' .$timestart;
        $timefinish = '07:00:00';
        $datefinish = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')) . ' ' .$timefinish;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
            $intshift = 1;
        } elseif (($timenow >= $shift2start1 && $timenow <= $shift2finish1) || ($timenow >= $shift2start2 && $timenow <= $shift2finish2)) {
            $intshift = 2;
            $timestart  = '19:00:00';
            $datestart  = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days')) . ' ' .$timestart;
        }
        
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, a.intoperator, a.inttype_downtime,
                             a.inttype_list, a.intmekanik, a.dtmulai, a.dtselesai, a.intsparepart, a.intjumlah, a.intstatus, a.intleader, a.dtupdate, 
                            SUM(ISNULL(a.decdurasi,0)) as decdurasi,
                            SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decmachinedowntime,
                            SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decprosestime,
                            SUM(CASE WHEN b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decplanned',false);
        $this->db->from('pr_downtime2 as a');
        $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
        $this->db->where("a.dttanggal >= '" . date('Y-m-d H:i:s', strtotime($datestart)) . "' AND a.dttanggal <= '" . date('Y-m-d H:i:s', strtotime($datefinish)) . "' AND a.intmesin = "  . $this->session->intmesinop . " AND a.intshift = " . $intshift);

        return $this->db->get()->result();
    }

    function getdataoutputD(){
        $shift1start   = strtotime('07:00:00');
        $shift1finish  = strtotime('20:00:00');
        
        $shift2start1  = strtotime('19:00:01');
        $shift2start2  = strtotime('00:00:00');
        $shift2finish1 = strtotime('23:59:59');
        $shift2finish2 = strtotime('07:00:00');
        $timenow       = time(date("H:i:s"));
        $intshift      = 0;
        
        $timestart  = '07:00:01';
        $datestart  = date('Y-m-d') . ' ' .$timestart;
        $timefinish = '07:00:00';
        $datefinish = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')) . ' ' .$timefinish;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
            $intshift = 1;
        } elseif (($timenow >= $shift2start1 && $timenow <= $shift2finish1) || ($timenow >= $shift2start2 && $timenow <= $shift2finish2)) {
            $intshift = 2;
            $timestart  = '19:00:00';
            $datestart  = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days')) . ' ' .$timestart;
        }

        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, a.intleader, a.intshift, intmodel, a.intkomponen,
                           a.decct, a.dtmulai, a.dtselesai, a.decdurasi, a.intpasang, a.intreject, a.inttarget, a.dtupdate, a.intstatus,
                           a.vcketerangan, count(a.intid) as jmlid, SUM(a.decct) as jmlct, SUM(a.intpasang) as jmlpasang, SUM(a.intreject) as jmlreject',false);
        $this->db->from('pr_output as a');
        $this->db->where("a.dttanggal >= '" . date('Y-m-d H:i:s', strtotime($datestart)) . "' AND a.dttanggal <= '" . date('Y-m-d H:i:s', strtotime($datefinish)) . "' AND a.intmesin = "  . $this->session->intmesinop . " AND a.intshift = " . $intshift);

        return $this->db->get()->result();
    }

    function getdataloginD() {
        $shift1start   = strtotime('07:00:00');
        $shift1finish  = strtotime('20:00:00');
        
        $shift2start1  = strtotime('19:00:01');
        $shift2start2  = strtotime('00:00:00');
        $shift2finish1 = strtotime('23:59:59');
        $shift2finish2 = strtotime('07:00:00');
        $timenow       = time(date("H:i:s"));
        $intshift      = 0;
        
        $timestart  = '07:00:01';
        $datestart  = date('Y-m-d') . ' ' .$timestart;
        $timefinish = '07:00:00';
        $datefinish = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')) . ' ' .$timefinish;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
            $intshift = 1;
        } elseif (($timenow >= $shift2start1 && $timenow <= $shift2finish1) || ($timenow >= $shift2start2 && $timenow <= $shift2finish2)) {
            $intshift = 2;
            $timestart  = '19:00:00';
            $datestart  = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days')) . ' ' .$timestart;
        }

        $this->db->select('a.intid, a.dtlogin,MIN(a.dtlogin) AS loginawal, MAX(a.dtlogin) as loginakhir', false);
        $this->db->from('a_log_login as a');
        $this->db->join('app_muser as b','b.intid = a.intuser','left');
        $this->db->where("a.intuser = b.intid AND a.dtlogin >= '" . $datestart . "' AND a.dtlogin <= NOW() AND a.intlogin = 1 AND a.intshift = '" . $intshift . "' AND b.intmesin = " . $this->session->intmesinop);
        

        return $this->db->get()->result();
    }

    function getkomponen($intgedung,$intid){
        $this->db->select('a.intkomponen, c.deccycle_time, b.vcnama as vckomponen',false);
        $this->db->from('m_models_loadplan as a');
        $this->db->join('m_models_komponen as c','c.intkomponen = a.intkomponen');
        $this->db->join('m_komponen as b','b.intid = a.intkomponen');
        $this->db->where('c.intheader',$intid);
        $this->db->where('a.intgedung',$intgedung);
        $this->db->group_by('a.intkomponen, c.deccycle_time, b.vcnama');

        return $this->db->get()->result();
    }

    function getjamkerja($date1, $date2, $intmesin, $intshift){
        $this->db->select('a.intid, a.intuser, a.intkaryawan, a.intshift, a.intlogin, a.dtlogin, a.intjamkerja, a.intjamlembur, c.vcnama as vcoperator');
        $this->db->from('a_log_login a');
        $this->db->join('app_muser b', 'b.intid = a.intuser');
        $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
        $this->db->where('a.dtlogin >=', $date1);
        $this->db->where('a.dtlogin <=', $date2);
        $this->db->where('a.intshift', $intshift);
        $this->db->where('b.intmesin', $intmesin);

        return $this->db->get()->result();
    }

    function updatelembur($date1, $date2, $intkaryawan, $intshift, $intjamlembur){
        $sql = "UPDATE a_log_login
                SET intjamlembur = '" . $intjamlembur . "'
                WHERE dtlogin >= '" . $date1 . "' AND
                    dtlogin <= '" . $date2 . "' AND
                    intshift >= '" . $intshift . "' AND
                    intkaryawan >= '" . $intkaryawan . "'";

        $this->db->query($sql);
    }

    function getkomponenct($intheader){
        $this->db->where('intheader',$intheader);
        $this->db->where('deccycle_time >', 0);

        return $this->db->get('m_models_komponen_ct')->result();
    }

    function get_countmessage($date1, $date2, $intmesin, $intkaryawan){
        $this->db->where('dttanggal >=', $date1);
        $this->db->where('dttanggal <=', $date2);
        $this->db->where('intmesin', $intmesin);
        $this->db->where('intkaryawan',$intkaryawan);

        return $this->db->get('pr_pesan')->num_rows();
    }

    function getdatamodel($intgedung){
     $this->db->select('a.intmodel, b.vcnama as vcmodel',false);
     $this->db->from('m_models_loadplan as a');
     $this->db->join('m_models b','b.intid = a.intmodel','left');
     $this->db->where('a.intgedung',$intgedung);
     $this->db->group_by('a.intmodel, b.vcnama');
     return $this->db->get()->result();
    }

    function getartikel($intgedung=0, $intmodel=0, $intkomponen=0){
     $this->db->select('a.vcartikel',false);
     $this->db->from('m_models_loadplan as a');
     if ($intgedung > 0) {
        $this->db->where('a.intgedung',$intgedung);
      }
      if ($intmodel > 0) {
        $this->db->where('a.intmodel',$intmodel);   
      }
      if ($intkomponen > 0) {
        $this->db->where('a.intkomponen',$intkomponen);   
      }

     $this->db->group_by('a.vcartikel');
     return $this->db->get()->result();
    }

    function getpo($intgedung=0, $intmodel=0, $intkomponen=0, $vcartikel=''){
     $this->db->select('a.vcpo',false);
     $this->db->from('m_models_loadplan as a');
     if ($intgedung > 0) {
        $this->db->where('a.intgedung',$intgedung);
      }
      if ($intmodel > 0) {
        $this->db->where('a.intmodel',$intmodel);   
      }
      if ($intkomponen > 0) {
        $this->db->where('a.intkomponen',$intkomponen);   
      }

      if ($vcartikel != "") {
        $this->db->where('a.vcartikel',$vcartikel);   
      }

     $this->db->group_by('a.vcpo');
     return $this->db->get()->result();
    }
}