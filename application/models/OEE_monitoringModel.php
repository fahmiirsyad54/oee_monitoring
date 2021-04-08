<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OEE_monitoringModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    // Login & Operator
    function getlogin_logout($intmesin, $dttanggal, $intshift){
      $this->db->select('a.intid, a.intuser, a.intkaryawan, a.intshift, a.intlogin, a.dtlogin, c.vcnama as vcoperator');
      $this->db->from('a_log_history a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
      $this->db->where('DATE(a.dtlogin)', $dttanggal);
      $this->db->where('a.intshift', $intshift);
      $this->db->where('b.intmesin', $intmesin);

      return $this->db->get()->result();
    }

    function getlogin($intmesin, $date1, $date2, $intshift){
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

    function getdataloginall($datestart,$datefinish,$intmesin){
      $query = "SELECT c.loginshift1, d.logoutshift1, e.loginshift2, f.logoutshift2
                FROM a_log_login a
                JOIN app_muser b ON b.intid = a.intuser
                LEFT JOIN (SELECT intuser, MIN(a.dtlogin) AS loginshift1
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 1 AND a.intshift = 1 AND
                    b.intmesin = " . $intmesin . "
                  ) c ON c.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(a.dtlogin) AS logoutshift1
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 2 AND a.intshift = 1 AND
                    b.intmesin = " . $intmesin . "
                  ) d ON d.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MIN(a.dtlogin)  AS loginshift2
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 1 AND a.intshift = 2 AND
                    b.intmesin = " . $intmesin . "
                  ) e ON e.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(a.dtlogin) AS logoutshift2
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 2 AND a.intshift = 2 AND
                    b.intmesin = " . $intmesin . "
                  ) f ON f.intuser = a.intuser
                WHERE a.dtlogin >= '" . $datestart . "' AND
                  a.dtlogin <= '" . $datefinish . "' AND
                  b.intmesin = " . $intmesin . "
                  GROUP BY a.intuser";

      return $this->db->query($query)->result();
    }

    function getdatalogin($datestart,$datefinish,$intmesin){
      $query = "SELECT c.loginshift1, d.logoutshift1, e.loginshift2, f.logoutshift2
                FROM a_log_login a
                JOIN app_muser b ON b.intid = a.intuser
                LEFT JOIN (SELECT intuser, MIN(a.dtlogin) AS loginshift1
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 1 AND a.intshift = 1 AND
                    b.intmesin = " . $intmesin . "
                  ) c ON c.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(a.dtlogin) AS logoutshift1
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 2 AND a.intshift = 1 AND
                    b.intmesin = " . $intmesin . "
                  ) d ON d.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MIN(a.dtlogin)  AS loginshift2
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 1 AND a.intshift = 2 AND
                    b.intmesin = " . $intmesin . "
                  ) e ON e.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(a.dtlogin) AS logoutshift2
                  FROM a_log_login a
                  JOIN app_muser b ON b.intid = a.intuser
                  WHERE a.dtlogin >= '" . $datestart . "' AND
                    a.dtlogin <= '" . $datefinish . "' AND
                    a.intlogin = 2 AND a.intshift = 2 AND
                    b.intmesin = " . $intmesin . "
                  ) f ON f.intuser = a.intuser
                WHERE a.dtlogin >= '" . $datestart . "' AND
                  a.dtlogin <= '" . $datefinish . "' AND
                  b.intmesin = " . $intmesin . "
                  GROUP BY a.intuser";

      return $this->db->query($query)->result();
    }

    //tanpa login
    //======================================================================================================================
    // function getdatadowntimeall($datestart,$datefinish,$intmesin){
    //   $this->db->select('SUM(CASE WHEN b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decdurasi,
    //                       SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decmachinedowntime,
    //                       SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decprosestime,
    //                       SUM(CASE WHEN b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decplanned');
    //   $this->db->from('pr_downtime2 a');
    //   $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
    //   $this->db->where("a.dttanggal >= '" . $datestart . "'");
    //   $this->db->where("a.dttanggal <= '" . $datefinish . "'");
    //   $this->db->where('a.intmesin', $intmesin);
    //   return $this->db->get()->result();
    // }

    // function getdataoutputall($datestart,$datefinish,$intmesin,$intshift=0){
    //   $this->db->select('ISNULL(SUM(CASE WHEN intpasang > inttarget THEN inttarget ELSE intpasang END),0) as intactual,
    //                     ISNULL(SUM(intpasang),0) as intactual2, 
    //                     ISNULL(SUM(intreject),0) as intreject, 
    //                     ISNULL(AVG(decct), 0) as decct,
    //                     SUM(CASE WHEN inttarget = 0 THEN ISNULL(((decdurasi * 60) / decct),0) ELSE inttarget END) as inttarget,
    //                     ISNULL(SUM(intpasang * decct) - SUM(intpasang),0) as intlosses,
    //                     SUM(decct * (intpasang + intreject))/SUM(intpasang + intreject) AS decct2');
    //   $this->db->from('pr_output');
    //   $this->db->where("dttanggal >= '" . $datestart . "'");
    //   $this->db->where("dttanggal <= '" . $datefinish . "'");
    //   $this->db->where('intmesin', $intmesin);
    //   if ($intshift > 0) {
    //     $this->db->where('intshift', $intshift);
    //   }
    //   return $this->db->get()->result();
    // }

    //============================================================================================================================

    //dengan login
    //============================================================================================================================
    // for OEE
    function getdatadowntimeall($datestart,$datefinish,$intmesin){
      $this->db->select('SUM(CASE WHEN b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decdurasi,
                          SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decmachinedowntime,
                          SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decprosestime,
                          SUM(CASE WHEN a.inttype_list = 1 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as deckalibrasi,
                          SUM(CASE WHEN a.inttype_list = 14 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decmeeting,
                          SUM(CASE WHEN a.inttype_list = 34 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decsm,
                          SUM(CASE WHEN a.inttype_list = 42 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as dectrial,
                          SUM(CASE WHEN b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decplanned');
      $this->db->from('pr_downtime2 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->join('app_muser as c', 'c.intmesin = a.intmesin');
      $this->db->join('a_log_login as d', 'c.intid = d.intuser');
      $this->db->where('d.intuser > 0');
      $this->db->where("d.dtlogin >= '" . $datestart . "'");
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      return $this->db->get()->result();
    }

    function getdatadowntime($datestart,$datefinish,$intmesin, $intshift=0){
      $this->db->select('SUM(CASE WHEN b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decdurasi,
                          SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decmachinedowntime,
                          SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decprosestime,
                          SUM(CASE WHEN a.inttype_list = 1 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as deckalibrasi,
                          SUM(CASE WHEN a.inttype_list = 14 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decmeeting,
                          SUM(CASE WHEN a.inttype_list = 34 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decsm,
                          SUM(CASE WHEN a.inttype_list = 42 AND b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as dectrial,
                          SUM(CASE WHEN b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decplanned');
      $this->db->from('pr_downtime2 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      if ($intshift > 0) {
        $this->db->where('a.intshift', $intshift);
      }
      return $this->db->get()->result();
    }

    function getdataoutputall($datestart,$datefinish,$intmesin,$intshift=0){
      $this->db->select('ISNULL(SUM(CASE WHEN a.intpasang > a.inttarget THEN a.inttarget ELSE a.intpasang END),0) as intactual,
                        ISNULL(SUM(a.intpasang),0) as intactual2, 
                        ISNULL(SUM(a.intreject),0) as intreject, 
                        ISNULL(AVG(a.decct), 0) as decct,
                        SUM(CASE WHEN a.inttarget = 0 THEN ISNULL(((a.decdurasi * 60) / a.decct),0) ELSE a.inttarget END) as inttarget,
                        ISNULL(SUM(a.intpasang * a.decct) - SUM(a.intpasang),0) as intlosses,
                        SUM(a.decct * (a.intpasang + a.intreject))/SUM(a.intpasang + a.intreject) AS decct2');
      $this->db->from('pr_output as a');
      $this->db->join('app_muser as b', 'b.intmesin = a.intmesin');
      $this->db->join('a_log_login as c', 'b.intid = c.intuser');
      $this->db->where("c.dtlogin >= '" . $datestart . "'");
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      if ($intshift > 0) {
        $this->db->where('a.intshift', $intshift);
      }
      return $this->db->get()->result();
    }

    function getdataoutput($datestart,$datefinish,$intmesin,$intshift=0){
      $this->db->select('ISNULL(SUM(CASE WHEN intpasang > inttarget THEN inttarget ELSE intpasang END),0) as intactual,
                        ISNULL(SUM(intpasang),0) as intactual2, 
                        ISNULL(SUM(intreject),0) as intreject, 
                        ISNULL(AVG(decct), 0) as decct,
                        SUM(CASE WHEN inttarget = 0 THEN ISNULL(((decdurasi * 60) / decct),0) ELSE inttarget END) as inttarget,
                        ISNULL(SUM(intpasang * decct) - SUM(intpasang),0) as intlosses,
                        SUM(decct * (intpasang + intreject))/SUM(intpasang + intreject) AS decct2');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      if ($intshift > 0) {
        $this->db->where('intshift', $intshift);
      }
      
      return $this->db->get()->result();
    }

    function getdatadowntimeall2($datestart,$datefinish,$intmesin){
      $this->db->select('SUM(CASE WHEN b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decdurasi,
                          SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decmachinedowntime,
                          SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decprosestime,
                          SUM(CASE WHEN b.intplanned = 1 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as decplanned');
      $this->db->from('pr_downtime3 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->join('app_muser as c', 'c.intmesin = a.intmesin');
      $this->db->join('a_log_login as d', 'c.intid = d.intuser');
      $this->db->where('d.intuser > 0');
      $this->db->where("d.dtlogin >= '" . $datestart . "'");
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      return $this->db->get()->result();
    }

    function getdataoutputall2($datestart,$datefinish,$intmesin,$intshift=0){
      $this->db->select('ISNULL(SUM(CASE WHEN a.intpasang > a.inttarget THEN a.inttarget ELSE a.intpasang END),0) as intactual,
                        ISNULL(SUM(a.intpasang),0) as intactual2, 
                        ISNULL(SUM(a.intreject),0) as intreject, 
                        ISNULL(AVG(a.decct), 0) as decct,
                        SUM(CASE WHEN a.inttarget = 0 THEN ISNULL(((a.decdurasi * 60) / a.decct),0) ELSE a.inttarget END) as inttarget,
                        ISNULL(SUM(a.intpasang * a.decct) - SUM(a.intpasang),0) as intlosses,
                        SUM(a.decct * (a.intpasang + a.intreject))/SUM(a.intpasang + a.intreject) AS decct2');
      $this->db->from('pr_output3 as a');
      $this->db->join('app_muser as b', 'b.intmesin = a.intmesin');
      $this->db->join('a_log_login as c', 'b.intid = c.intuser');
      $this->db->where("c.dtlogin >= '" . $datestart . "'");
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      if ($intshift > 0) {
        $this->db->where('a.intshift', $intshift);
      }
      return $this->db->get()->result();
    }

    //================================================================================================================================

    function getoperatorall($datestart, $datefinish, $intmesin, $intlogin){
      $this->db->select('a.intid, a.intuser, a.intkaryawan, a.intshift, a.intlogin, a.dtlogin, a.intjamkerja, a.intjamlembur, c.vcnama as vcoperator, c.vckode as vcnik');
      $this->db->from('a_log_login a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
      $this->db->where("a.dtlogin >= '" . $datestart . "'");
      $this->db->where("a.dtlogin <= '" . $datefinish . "'");
      $this->db->where('b.intmesin', $intmesin);
      $this->db->where('a.intlogin', $intlogin);
      $this->db->where('b.intmesin', $intmesin);

      if ($intlogin == 1) {
        $this->db->order_by('a.intid','ASC');
        $this->db->limit(1);
      } else {
        $this->db->order_by('a.intid','DESC');
      }

      return $this->db->get()->result();
    }

    function getoperator($datestart, $datefinish, $intmesin, $intshift, $intlogin){
      $this->db->select('a.intid, a.intuser, a.intkaryawan, a.intshift, a.intlogin, a.dtlogin, a.intjamkerja, a.intjamlembur, c.vcnama as vcoperator, c.vckode as vcnik');
      $this->db->from('a_log_login a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
      $this->db->where("a.dtlogin >= '" . $datestart . "'");
      $this->db->where("a.dtlogin <= '" . $datefinish . "'");
      $this->db->where('b.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      $this->db->where('a.intlogin', $intlogin);
      $this->db->where('b.intmesin', $intmesin);

      if ($intlogin == 1) {
        $this->db->order_by('a.intid','ASC');
        $this->db->limit(1);
      } else {
        $this->db->order_by('a.intid','DESC');
      }

      return $this->db->get()->result();
    }

    function getdataoutputkomponen($datestart,$datefinish,$intmesin,$intshift){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, a.intleader, a.intshift, intmodel, a.intkomponen,
                           a.decct, CONVERT(varchar(8),a.dtmulai,108) as dtmulai, CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.decdurasi, a.intpasang, a.intreject, a.inttarget, a.dtupdate, a.intstatus,
                           a.vcketerangan');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      $this->db->where('intshift', $intshift);
      $this->db->group_by('intkomponen');
      return $this->db->get()->num_rows();
    }

    function getdataoutputkomponen2($datestart,$datefinish,$intmesin,$intshift){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, a.intshift, a.intmodel, a.intkomponen, a.decct, CONVERT(varchar(8),a.dtmulai,108) as dtmulai, CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.decdurasi, a.vcketerangan, a.inttarget, a.intpasang, a.intreject,
                        (a.intpasang * a.decct) - a.intpasang as intlosses,
                        b.vcnama as vcmodel, a.vcpo,
                        c.vcnama as vckomponen,
                        a.intremark, a.intlayer');
      $this->db->from('pr_output a');
      $this->db->join('m_models b','b.intid = a.intmodel');
      $this->db->join('m_komponen c','c.intid = a.intkomponen');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      return $this->db->get()->result();
    }

    function getdataoutputkomponen22($datestart,$datefinish,$intmesin,$intshift){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, a.intshift, a.intmodel, a.intkomponen, a.decct, a.dtmulai, a.dtselesai, a.decdurasi, a.vcketerangan, a.inttarget, a.intpasang, a.intreject,
                        (a.intpasang * a.decct) - a.intpasang as intlosses,
                        b.vcnama as vcmodel,
                        c.vcnama as vckomponen');
      $this->db->from('pr_output3 a');
      $this->db->join('m_models b','b.intid = a.intmodel');
      $this->db->join('m_komponen c','c.intid = a.intkomponen');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      return $this->db->get()->result();
    }

    // for data list downtime
    function getdatadowntime2($datestart,$datefinish,$intmesin, $intshift){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, CONVERT(varchar(8),a.dtmulai,108) as dtmulai, CONVERT(varchar(8),a.dtselesai,108) as dtselesai, a.intsparepart, a.intjumlah, b.vcnama as vcdowntime');
      $this->db->from('pr_downtime2 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      return $this->db->get()->result();
    }

    function getdatadowntime22($datestart,$datefinish,$intmesin, $intshift){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.decdurasi, a.intshift, a.intoperator, a.inttype_downtime, a.inttype_list, a.intmekanik, a.dtmulai, a.dtselesai, a.intsparepart, a.intjumlah, b.vcnama as vcdowntime');
      $this->db->from('pr_downtime3 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      return $this->db->get()->result();
    }

    function getmesin($intid){
      $this->db->select('a.intid, a.vckode, a.vcnama, a.intbrand, a.intarea, a.vcjenis, a.vcserial, a.vcpower, a.intgedung, a.intcell,
                            a.intdeparture, a.intgroup, a.intautocutting, a.vclocation, a.vcgambar, a.dtupdate, a.intstatus, a.vcfile, a.intsort,
                            a.intsortall, ISNULL(b.vcnama, 0) as vcbrand, ISNULL(c.vcnama, 0) as vcgedung');
      $this->db->from('m_mesin as a');
      $this->db->join('m_brand as b', 'b.intid = a.intbrand','left');
      $this->db->join('m_gedung as c','c.intid = a.intgedung','left');
      $this->db->where('a.intid',$intid);
      return $this->db->get()->result();
    }

    function getworkinghours($datestart,$datefinish,$intmesin){
      $this->db->from('a_log_history as a');
      $this->db->join('app_muser as b','b.intid = a.intuser');
      $this->db->where('a.dtlogin >=',$datestart);
      $this->db->where('a.dtlogin <=',$datefinish);
      $this->db->where('b.intmesin',$intmesin);

      return $this->db->get()->result();
    }

    function getdatamesin($intgedung=0,$intcell=0){
      $query = "SELECT a.intid, a.vckode, a.vcnama, a.intgedung, a.intcell, a.intautocutting, a.intsort, b.vcnama as vcgedung
                FROM m_mesin a
                JOIN m_gedung b ON b.intid = a.intgedung
                WHERE (0 = $intgedung OR a.intgedung = $intgedung) AND 
                      (0 = $intcell OR a.intcell = $intcell) AND 
                      (a.intautocutting = 1 OR a.intautocutting = 3)
                ORDER BY a.intsort ASC";

      return $this->db->query($query)->result();
    }

    function getdatamesin2($intgedung=0,$intcell=0){
      $query = "SELECT a.intid, a.vckode, a.vcnama, a.intgedung, a.intcell, a.intautocutting, a.intsort, b.vcnama as vcgedung
                FROM m_mesin a
                JOIN m_gedung b ON b.intid = a.intgedung
                WHERE (0 = $intgedung OR a.intgedung = $intgedung) AND 
                      (0 = $intcell OR a.intcell = $intcell) AND 
                      a.intautocutting = 2
                ORDER BY a.intsort ASC";

      return $this->db->query($query)->result();
    }

    function getdatagedung(){
      $this->db->where('intoeemonitoring > 0');
      return $this->db->get('m_gedung')->result();
    }

    // Login Logout operator
    function getlogout($date1, $date2, $intmesin, $intshift=1){
      $this->db->select('a.intid, a.intuser, a.intkaryawan, a.intshift, a.intlogin, a.dtlogin');
      $this->db->from('a_log_history a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->where('a.dtlogin >= ', $date1);
      $this->db->where('a.dtlogin <= ', $date2);
      $this->db->where('a.intshift',$intshift);
      $this->db->where('b.intmesin', $intmesin);

      return $this->db->get()->result();
    }

    function getjamkerja($date1, $date2, $intmesin, $intshift=0){
        $this->db->select('a.intid, a.intuser, a.intkaryawan, a.intshift, a.intlogin, a.dtlogin, a.intjamkerja, a.intjamlembur, 
                        c.vcnama as vcoperator');
        $this->db->from('a_log_login a');
        $this->db->join('app_muser b', 'b.intid = a.intuser');
        $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
        $this->db->where('a.dtlogin >=', $date1);
        $this->db->where('a.dtlogin <=', $date2);
        if ($intshift > 0) {
          $this->db->where('a.intshift', $intshift);
        }
        $this->db->where('b.intmesin', $intmesin);
        $this->db->where('a.intlogin', 1);

        return $this->db->get()->result();
    }

    function getwaktukerja($date1, $date2, $intmesin, $intshift=0){
      $this->db->select('sum(a.intjamkerja) as intjamkerja, sum(a.intjamlembur) as intjamlembur');
      $this->db->from('a_log_login a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->where('a.dtlogin >=', $date1);
      $this->db->where('a.dtlogin <=', $date2);
      $this->db->where('b.intmesin', $intmesin);
      $this->db->where('a.intlogin', 1);
      if ($intshift > 0) {
        $this->db->where('a.intshift', $intshift);
      }

      return $this->db->get()->result();
  }

    function getcentralcutting($intgedung){
      $this->db->where('intgedung',$intgedung);
      $this->db->where('inttype',2);
      return $this->db->get('m_cell')->result();
    }

    function getdtgedung($intgedung){
      $this->db->where(('intid'),$intgedung);
      return $this->db->get('m_gedung')->result();
    }

    function validasi_password ($intid, $vcpassword) {
      $this->db->select('count(intid) as intpasswordcek');
      $this->db->where('intid',$intid);
      $this->db->where('vcpassword',$vcpassword);
      return $this->db->get('app_muser')->result();
  }

  function getdatatotaloutput($table, $intgedung=0,$from=null, $to=null){
    $this->db->select('ISNULL(SUM(CASE WHEN a.intpasang > a.inttarget THEN a.inttarget ELSE a.intpasang END),0) as intaktual,
                      sum(a.intpasang) as intpasang, sum(a.inttarget) as inttarget,
                      ISNULL(b.vcnama, 0) as vcmodel, ISNULL(c.vcnama, 0) as vckomponen, a.intmodel, a.intkomponen',false);
    $this->db->from($table . ' as a');
    $this->db->join('m_models as b', 'a.intmodel = b.intid', 'left');
    $this->db->join('m_komponen as c', 'a.intkomponen = c.intid', 'left');
    
    if ($from) {
      $this->db->where('a.dttanggal >= ', $from);
      $this->db->where('a.dttanggal <= ', $to);
    }

    if ($intgedung > 0) {
      $this->db->where('a.intgedung',$intgedung); 
    }
    $this->db->group_by('a.intgedung, a.intmodel, a.intkomponen, b.vcnama, c.vcnama');
    $this->db->having('sum(a.intpasang) > 0');
    $this->db->order_by('a.intmodel','asc');
    $this->db->order_by('a.intkomponen','asc');
    return $this->db->get()->result();
  }

  function getdatatotaloutputpershift($table, $intgedung=0,$from=null, $to=null, $intshift){
    $this->db->select('ISNULL(SUM(CASE WHEN a.intpasang > a.inttarget THEN a.inttarget ELSE a.intpasang END),0) as intaktual,
                      sum(a.intpasang) as intpasang, sum(a.inttarget) as inttarget,
                      ISNULL(b.vcnama, 0) as vcmodel, ISNULL(c.vcnama, 0) as vckomponen, a.intmodel, a.intkomponen',false);
    $this->db->from($table . ' as a');
    $this->db->join('m_models as b', 'a.intmodel = b.intid', 'left');
    $this->db->join('m_komponen as c', 'a.intkomponen = c.intid', 'left');
    
    if ($from) {
      $this->db->where('a.dttanggal >= ', $from);
      $this->db->where('a.dttanggal <= ', $to);
    }

    if ($intgedung > 0) {
      $this->db->where('a.intgedung',$intgedung); 
    }
    $this->db->where('a.intshift',$intshift);

    $this->db->group_by('a.intgedung, a.intmodel, a.intkomponen, b.vcnama, c.vcnama');
    $this->db->having('sum(a.intpasang) > 0');
    $this->db->order_by('a.intmodel','asc');
    $this->db->order_by('a.intkomponen','asc');
    return $this->db->get()->result();
  }

  function getdatatotaldowntime($table, $intgedung=0,$from=null, $to=null){
    $this->db->select('a.inttype_list, sum(a.decdurasi) as jmldurasi, count(a.intid) as jmlcount, ISNULL(b.vcnama, 0) as vcdowntime',false);
    $this->db->from($table . ' as a');
    $this->db->join('m_type_downtime_list as b','b.intid = a.inttype_list','left');
    
    if ($from) {
      $this->db->where('a.dttanggal >= ', $from);
      $this->db->where('a.dttanggal <= ', $to);
    }

    if ($intgedung > 0) {
      $this->db->where('a.intgedung',$intgedung); 
    }
    $this->db->group_by('a.intgedung, a.inttype_list, b.vcnama');
    $this->db->having('sum(a.decdurasi) > 0');
    $this->db->order_by('b.vcnama','asc');
    return $this->db->get()->result();
  }

  function getdatatotaldowntimepershift($table, $intgedung=0,$from=null, $to=null, $intshift){
    $this->db->select('a.inttype_list, sum(a.decdurasi) as jmldurasi, count(a.intid) as jmlcount, ISNULL(b.vcnama, 0) as vcdowntime',false);
    $this->db->from($table . ' as a');
    $this->db->join('m_type_downtime_list as b','b.intid = a.inttype_list','left');
    
    if ($from) {
      $this->db->where('a.dttanggal >= ', $from);
      $this->db->where('a.dttanggal <= ', $to);
    }

    if ($intgedung > 0) {
      $this->db->where('a.intgedung',$intgedung); 
    }
    $this->db->where('a.intshift',$intshift);

    $this->db->group_by('a.intgedung, a.inttype_list, b.vcnama');
    $this->db->having('sum(a.decdurasi) > 0');
    $this->db->order_by('b.vcnama','asc');
    return $this->db->get()->result();
  }

  function getdtmesin($intgedung=0){
    $this->db->select('a.intid, a.vcnama',false);
    $this->db->from('m_mesin as a');
    if ($intgedung > 0) {
      $this->db->where('a.intgedung',$intgedung);
    }
    
    $this->db->where('a.intautocutting',1);
    $this->db->order_by('a.intsortall','asc');
    return $this->db->get()->result();
  }

  function getmesinoutput($intgedung, $intmodel, $intkomponen, $from=null, $to=null){
    $where = "((a.intid = b.intmesin) AND (b.intmodel = '".$intmodel."') AND (b.intkomponen = '".$intkomponen."') AND (b.dttanggal >= '".$from."') AND (b.dttanggal <= '".$to."'))";
    $this->db->select('a.intid, a.vcnama, sum(b.intpasang) as intpasangmesin',false);
    $this->db->from('m_mesin as a');
    $this->db->join('pr_output as b', $where, 'left');

    $this->db->where('a.intgedung',$intgedung); 
    $this->db->where('a.intautocutting',1); 
    
    $this->db->order_by('a.intsortall','asc');
    $this->db->group_by('a.intid, a.vcnama, a.intsortall');
    return $this->db->get()->result();
  }

  function getmesinoutputpershift($intgedung, $intmodel, $intkomponen, $from=null, $to=null, $intshift){
    $where = "((a.intid = b.intmesin) AND (b.intmodel = '".$intmodel."') AND (b.intkomponen = '".$intkomponen."') AND (b.dttanggal >= '".$from."') AND (b.dttanggal <= '".$to."') AND (b.intshift = '".$intshift."'))";
    $this->db->select('a.intid, a.vcnama, sum(b.intpasang) as intpasangmesin',false);
    $this->db->from('m_mesin as a');
    $this->db->join('pr_output as b', $where, 'left');

    $this->db->where('a.intgedung',$intgedung);
    //$this->db->where('b.intshift',$intshift); 
    $this->db->where('a.intautocutting',1); 
    
    $this->db->order_by('a.intsortall','asc');
    $this->db->group_by('a.intid, a.vcnama, a.intsortall');
    return $this->db->get()->result();
  }

  function getmesindowntime($intgedung, $inttype_list, $from=null, $to=null){
    $where = "((a.intid = b.intmesin) AND (b.inttype_list = '".$inttype_list."') AND (b.dttanggal >= '".$from."') AND (b.dttanggal <= '".$to."'))";
    $this->db->select('a.intid, a.vcnama, sum(b.decdurasi) as jmldurasimesin, count(b.intid) as jmlcountmesin',false);
    $this->db->from('m_mesin as a');
    $this->db->join('pr_downtime2 as b', $where, 'left');

    $this->db->where('a.intgedung',$intgedung); 
    $this->db->where('a.intautocutting',1); 
    
    $this->db->order_by('a.intsortall','asc');
    $this->db->group_by('a.intid, a.vcnama, a.intsortall');
    return $this->db->get()->result();
  }

  function getmesindowntimepershift($intgedung, $inttype_list, $from=null, $to=null, $intshift){
    $where = "((a.intid = b.intmesin) AND (b.inttype_list = '".$inttype_list."') AND (b.dttanggal >= '".$from."') AND (b.dttanggal <= '".$to."') AND (b.intshift = '".$intshift."'))";
    $this->db->select('a.intid, a.vcnama, sum(b.decdurasi) as jmldurasimesin, count(b.intid) as jmlcountmesin',false);
    $this->db->from('m_mesin as a');
    $this->db->join('pr_downtime2 as b', $where, 'left');

    $this->db->where('a.intgedung',$intgedung);
    $this->db->where('a.intautocutting',1); 
    
    $this->db->order_by('a.intsortall','asc');
    $this->db->group_by('a.intid, a.vcnama, a.intsortall');
    return $this->db->get()->result();
  }

  function getgrafikdowntime($table, $intgedung ,$from=null, $to=null, $intshift=0){
    $this->db->select('a.inttype_list, ISNULL(b.vcnama, 0) as vcdowntime,
                      SUM(CASE WHEN a.inttype_list != 35 THEN ISNULL(a.decdurasi,0) ELSE 0 END) as jmldurasi',false);
    $this->db->from($table . ' as a');
    $this->db->join('m_type_downtime_list as b','b.intid = a.inttype_list','left');
    $this->db->where('a.inttype_list != 35');
    if ($from) {
      $this->db->where('a.dttanggal >= ', $from);
      $this->db->where('a.dttanggal <= ', $to);
    }

    
    $this->db->where('a.intgedung',$intgedung); 
    
    if ($intshift > 0) {
      $this->db->where('a.intshift',$intshift);
    }
    

    $this->db->group_by('a.intgedung, a.inttype_list, b.vcnama');
    $this->db->having('sum(a.decdurasi) > 0');
    $this->db->order_by('b.vcnama','asc');
    return $this->db->get()->result();
  }
  
}