<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OeeModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
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

      // $this->db->select('MIN(a.dtlogin) as dtlogin, MAX(a.dtlogin) as dtlogout');
      // $this->db->from('a_log_login a');
      // $this->db->join('app_muser as b','b.intid = a.intuser');
      // $this->db->where("a.dtlogin >= '" . $datestart . "'");
      // $this->db->where("a.dtlogin <= '" . $datefinish . "'");
      // $this->db->where('b.intmesin', $intmesin);
      // return $this->db->get()->result();
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

    function getmesin($intid){
      $this->db->select('a.*, IFNULL(b.vcnama, "") as vcbrand, IFNULL(c.vcnama, "") as vcgedung');
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

    function getdatamesin($intgedung){
      $query = "SELECT a.*, b.vcnama as vcgedung
                FROM m_mesin a
                JOIN m_gedung b ON b.intid = a.intgedung
                WHERE (0 = $intgedung OR a.intgedung = $intgedung) AND a.intautocutting = 1
                ORDER BY a.vcnama";

      return $this->db->query($query)->result();
    }

    // Jam Kerja
    function getjamkerja($date1, $date2, $intmesin, $intshift){
        $this->db->select('a.*, c.vcnama as vcoperator');
        $this->db->from('a_log_login a');
        $this->db->join('app_muser b', 'b.intid = a.intuser');
        $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
        $this->db->where('a.dtlogin >=', $date1);
        $this->db->where('a.dtlogin <=', $date2);
        $this->db->where('a.intshift', $intshift);
        $this->db->where('b.intmesin', $intmesin);

        return $this->db->get()->result();
    }

    // Login Logout Operator
    function getlogout($date1, $date2, $intmesin, $intshift=1){
      $this->db->select('a.*');
      $this->db->from('a_log_history a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->where('a.dtlogin >= ', $date1);
      $this->db->where('a.dtlogin <= ', $date2);
      $this->db->where('a.intshift',$intshift);
      $this->db->where('b.intmesin', $intmesin);

      return $this->db->get()->result();
    }


    // Data Downtime
    function getdatadowntime($datestart,$datefinish,$intmesin, $intshift){
      $this->db->select('SUM(CASE WHEN b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decdurasi,
                          SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decmachinedowntime,
                          SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decprosestime,
                          SUM(CASE WHEN b.intplanned = 1 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decplanned');
      $this->db->from('pr_downtime2 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      return $this->db->get()->result();
    }

    function getdatadowntimeall($datestart,$datefinish,$intmesin){
      $this->db->select('SUM(CASE WHEN b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decdurasi,
                          SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decmachinedowntime,
                          SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decprosestime,
                          SUM(CASE WHEN b.intplanned = 1 THEN IFNULL(a.decdurasi,0) ELSE 0 END) as decplanned');
      $this->db->from('pr_downtime2 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      return $this->db->get()->result();
    }

    // Data output
    function getdataoutput($datestart,$datefinish,$intmesin,$intshift){
      $this->db->select('IFNULL(SUM(intpasang),0) as intactual,
                          IFNULL(SUM(intreject),0) as intreject, 
                          IFNULL(AVG(decct), 0) as decct, 
                          SUM(decct * (intpasang))/SUM(intpasang) AS decct2');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      $this->db->where('intshift', $intshift);
      return $this->db->get()->result();
    }

    function getdataoutputall($datestart,$datefinish,$intmesin){
      $this->db->select('IFNULL(SUM(intpasang),0) as intactual, IFNULL(SUM(intreject),0) as intreject, IFNULL(AVG(decct), 0) as decct, SUM(decct * (intpasang + intreject))/SUM(intpasang + intreject) AS decct2');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      return $this->db->get()->result();
    }

    function getdataoutputkomponen($datestart,$datefinish,$intmesin,$intshift){
      $this->db->select('*');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      $this->db->where('intshift', $intshift);
      $this->db->group_by('intkomponen');
      return $this->db->get()->num_rows();
    }

    function getdataoutputkomponenall($datestart,$datefinish,$intmesin){
      $this->db->select('*');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      $this->db->group_by('intkomponen');
      return $this->db->get()->num_rows();
    }

}