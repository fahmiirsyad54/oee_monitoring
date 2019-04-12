<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function stoksparepart(){
        $query = 'SELECT SUM(`decqtymasuk`) - SUM(`decqtykeluar`) AS jumlah FROM pr_sparepart WHERE intstatus = 1';
        return $this->db->query($query)->result();
    }

    function totalmesin(){
      $query = 'SELECT COUNT(intid) as jumlah FROM m_mesin';
      return $this->db->query($query)->result();
    }

    function totaldowntime(){
      $date = date('m');
      $this->db->select('SUM(inttunggumekanik+intperbaikan+inttungguoperator+inttunggumaterial) as jumlah');
      $this->db->from('pr_downtime');
      $this->db->where('MONTH(dttanggal) = '.$date);
      return $this->db->get()->result();
    }

    function grafikdowntime($date){
      // $date = date('m');
      $this->db->select('*, SUM(inttunggumekanik+intperbaikan+inttungguoperator+inttunggumaterial) as jumlah');
      $this->db->from('pr_downtime');
      $this->db->where('MONTH(dttanggal) = '.$date);
      return $this->db->get()->result();
    }

    function grafiktopdowntime(){
      $query = "SELECT COUNT(a.intid) AS jmldowntime, b.vcnama AS vcdowntime
                FROM pr_downtime a
                JOIN m_type_downtime_list b ON b.intid = a.`inttype_list`
                GROUP BY a.`inttype_list`
                ORDER BY jmldowntime DESC
                LIMIT 5";
      return $this->db->query($query)->result();
    }

    function getmonthly($month, $year){
      $this->db->where('TIME(dttanggal)','00:00:00');
      $this->db->where('MONTH(dttanggal) = ',$month);
      $this->db->where('YEAR(dttanggal) = ',$year);

      $this->db->order_by('dttanggal','asc');
      $this->db->limit(3);
      return $this->db->get('pr_downtime2')->result();
    }

    function getgedung(){
      return $this->db->get('m_gedung')->result();
    }
    
    function getmesin($intgedung){
      $this->db->select('COUNT(b.intid) as jumlah, a.vcnama as vccell, a.intid');
      $this->db->from('m_cell as a');
      $this->db->join('m_mesin as b', 'a.intid = b.intcell','left');
      $this->db->where('a.intgedung',$intgedung);
      $this->db->group_by('a.intid');

      return $this->db->get()->result();
    }

    function getjmldata($intgedung=0, $intcell=0){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from('m_mesin as a');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        if ($intgedung > 0) {
            $this->db->where('a.intgedung',$intgedung);
        }

        if ($intcell > 0) {
            $this->db->where('a.intcell',$intcell);
        }
        

        return $this->db->get()->result();
    }

    function getdatadetail($intgedung=0, $intcell=0){
        $this->db->select('a.*, 
                            IFNULL(e.vcnama, "") as vcgedung,
                            IFNULL(f.vcnama, "") as vccell,
                            IFNULL(c.vcnama, "") as vcbrand,
                            IFNULL(d.vcnama, "") as vcarea,
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from('m_mesin as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_brand' . ' as c', 'a.intbrand = c.intid', 'left');
        $this->db->join('m_area' . ' as d', 'a.intarea = d.intid', 'left');
        $this->db->join('m_gedung' . ' as e', 'a.intgedung = e.intid', 'left');
        $this->db->join('m_cell' . ' as f', 'a.intcell = f.intid', 'left');
        $this->db->where('a.intgedung',$intgedung);
        $this->db->where('a.intcell',$intcell);

        return $this->db->get()->result();
    }

    function getdatatitle ($intgedung, $intcell) {
        $this->db->select('IFNULL(a.vcnama, "") as vcgedung,
                           IFNULL(b.vcnama, "") as vccell', false);
        $this->db->from('m_gedung as a');
        $this->db->join('m_cell as b', 'a.intid = b.intgedung','left');
        $this->db->where('a.intid', $intgedung);
        $this->db->where('b.intid', $intcell);

        return $this->db->get()->result();
    }

    function getdatadetail2($intid){
        $this->db->select('a.*, 
                            IFNULL(e.vcnama, "") as vcgedung,
                            IFNULL(f.vcnama, "") as vccell,
                            IFNULL(c.vcnama, "") as vcbrand,
                            IFNULL(d.vcnama, "") as vcarea,
                            IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, 
                            IFNULL(b.vcwarna, "") as vcstatuswarna',false);
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

    function getstoksparepart(){
      $this->db->select('SUM(a.decqtymasuk) - SUM(a.decqtykeluar) AS jumlah, b.vcnama as vcsparepart, b.vckode as vckodesparepart, b.vcspesifikasi as vcspesifikasi, b.vcpart as vcpart, c.vcnama as vcunit',false);
      $this->db->from('pr_sparepart as a');
      $this->db->join('m_sparepart as b', 'b.intid = a.intsparepart');
      $this->db->join('m_unit as c', 'c.intid = b.intunit');
      $this->db->group_by('a.intsparepart');
      return $this->db->get()->result();
    }

    function getstoksparepartpergedung($intgedung=0){
      $this->db->select("SUM(IFNULL(c.decqtymasuk,0)) - SUM(IFNULL(c.decqtykeluar,0)) as jumlah,
                        a.vcnama as vcsparepart,
                        a.vckode as vckodesparepart,
                        a.vcspesifikasi as vcspesifikasi,
                        a.vcpart as vcpart,
                        b.vcnama as vcunit",false);
      $this->db->from('m_sparepart as a');
      $this->db->join('m_unit as b','b.intid = a.intunit');
      $this->db->join('pr_sparepart_gedung as c', 'c.intsparepart = a.intid', 'left');
      $this->db->where('c.intgedung',$intgedung);
      $this->db->group_by('a.intid');

      return $this->db->get()->result();
    }

    function getdatalogin(){
      $shift1start  = strtotime('07:00:00');
      $shift1finish = strtotime('20:00:00');
      
      $shift2start1  = strtotime('20:00:01');
      $shift2start2  = strtotime('00:00:00');
      $shift2finish1 = strtotime('23:59:59');
      $shift2finish2 = strtotime('06:00:00');
      $timenow      = time(date("H:i:s"));
      $intshift = 0;

      $timestart  = '07:00:00';
      $datestart  = date('Y-m-d') . ' ' .$timestart;
      $timefinish = '06:00:00';
      $datefinish = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')) . ' ' .$timefinish;

      if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $timestart  = '07:00:00';
          $datestart  = date('Y-m-d') . ' ' .$timestart;
      } elseif ($timenow >= $shift2start2 && $timenow <= $shift2finish2) {
          $timestart  = '07:00:00';
          $datestart  = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days')) . ' ' .$timestart;
          $timefinish = '06:00:00';
          $datefinish = date('Y-m-d') . ' ' .$timefinish;
      }

      $query = "SELECT b.intmesin, c.loginshift1, d.logoutshift1, e.loginshift2, f.logoutshift2
                FROM a_log_login a
                JOIN app_muser b ON b.intid = a.intuser
                LEFT JOIN (SELECT intuser, MIN(dtlogin) AS loginshift1
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 1 AND intshift = 1
                  GROUP BY intuser
                  ) c ON c.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(dtlogin) AS logoutshift1
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 2 AND intshift = 1
                  GROUP BY intuser
                  ) d ON d.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MIN(dtlogin)  AS loginshift2
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 1 AND intshift = 2
                  GROUP BY intuser
                  ) e ON e.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(dtlogin) AS logoutshift2
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 2 AND intshift = 2
                  GROUP BY intuser
                  ) f ON f.intuser = a.intuser
                WHERE a.dtlogin >= '" . $datestart . "' AND
                  a.dtlogin <= '" . $datefinish . "'
                GROUP BY a.intuser";

      return $this->db->query($query)->result();
    }

    function getdataloginby($intgedung=0,$intmesin=0){
      $shift1start  = strtotime('07:00:00');
      $shift1finish = strtotime('20:00:00');
      
      $shift2start1  = strtotime('20:00:01');
      $shift2start2  = strtotime('00:00:00');
      $shift2finish1 = strtotime('23:59:59');
      $shift2finish2 = strtotime('06:00:00');
      $timenow      = time(date("H:i:s"));
      $intshift = 0;

      $timestart  = '07:00:00';
      $datestart  = date('Y-m-d') . ' ' .$timestart;
      $timefinish = '06:00:00';
      $datefinish = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')) . ' ' .$timefinish;

      if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $timestart  = '07:00:00';
          $datestart  = date('Y-m-d') . ' ' .$timestart;
      } elseif ($timenow >= $shift2start2 && $timenow <= $shift2finish2) {
          $timestart  = '07:00:00';
          $datestart  = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days')) . ' ' .$timestart;
          $timefinish = '06:00:00';
          $datefinish = date('Y-m-d') . ' ' .$timefinish;
      }

      $query = "SELECT b.intmesin, g.vckode as vckodemesin, g.vcnama as vcmesin, c.loginshift1, d.logoutshift1, e.loginshift2, f.logoutshift2
                FROM a_log_login a
                JOIN app_muser b ON b.intid = a.intuser
                JOIN m_mesin g ON g.intid = b.intmesin
                LEFT JOIN (SELECT intuser, MIN(dtlogin) AS loginshift1
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 1 AND intshift = 1
                  GROUP BY intuser
                  ) c ON c.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(dtlogin) AS logoutshift1
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 2 AND intshift = 1
                  GROUP BY intuser
                  ) d ON d.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MIN(dtlogin)  AS loginshift2
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 1 AND intshift = 2
                  GROUP BY intuser
                  ) e ON e.intuser = a.intuser
                LEFT JOIN (SELECT intuser, MAX(dtlogin) AS logoutshift2
                  FROM a_log_login
                  WHERE dtlogin >= '" . $datestart . "' AND
                    dtlogin <= '" . $datefinish . "' AND
                    intlogin = 2 AND intshift = 2
                  GROUP BY intuser
                  ) f ON f.intuser = a.intuser
                WHERE a.dtlogin >= '" . $datestart . "' AND
                  a.dtlogin <= '" . $datefinish . "' AND
                  (" . $intgedung . " = 0 OR g.intgedung = " . $intgedung . ") AND
                  (" . $intmesin . " = 0 OR g.intid = " . $intmesin . ")
                GROUP BY a.intuser";

      return $this->db->query($query)->result();
    }

    function getdatadowntime($datestart,$datefinish,$intmesin){
      $this->db->select($intmesin . ' as intmesin, SUM(IFNULL(a.decdurasi,0)) as decdurasi,
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
    
    function getdataoutput($datestart,$datefinish,$intmesin){
      $this->db->select($intmesin . ' as intmesin, IFNULL(SUM(intpasang),0) as intactual, IFNULL(SUM(intreject),0) as intreject, IFNULL(AVG(decct), 0) as decct');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      return $this->db->get()->result();
    }

    function getcell($intgedung){
      $this->db->where('intgedung',$intgedung);
      $this->db->where('inttype',1);
      return $this->db->get('m_cell')->result();
    }

    function getreportdowntime($intcell,$month){
      $sql = "SELECT  a.*,
              CASE WHEN b.intdtmesin_type = 3 THEN SUM(b.inttunggumekanik) + SUM(b.intperbaikan) + SUM(b.inttungguoperator) ELSE 0 END AS dtmachinestitching,
              CASE WHEN b.intdtmesin_type = 3 THEN SUM(b.inttunggumaterial) ELSE 0 END AS dtprocesstitching,
              CASE WHEN b.intdtmesin_type = 1 THEN SUM(b.inttunggumekanik) + SUM(b.intperbaikan) + SUM(b.inttungguoperator) ELSE 0 END AS dtmachineassembly,
              CASE WHEN b.intdtmesin_type = 1 THEN SUM(b.inttunggumaterial) ELSE 0 END AS dtprocesassembly
            FROM m_cell a
            LEFT JOIN pr_downtime b ON b.intcell = a.intid
            WHERE a.intid = " . $intcell . " AND MONTH(b.dttanggal) = " . $month;
      return $this->db->query($sql)->result();
    }

    function getdatagedung(){
      $this->db->where('intoeemonitoring > 0');
      return $this->db->get('m_gedung')->result();
    }

    function getdatamesin($intgedung=0, $intcell=0){
      $query = "SELECT a.*, b.vcnama as vcgedung
                FROM m_mesin a
                JOIN m_gedung b ON b.intid = a.intgedung
                WHERE (0 = $intgedung OR a.intgedung = $intgedung) AND 
                      (0 = $intcell OR a.intcell = $intcell) AND 
                      a.intautocutting = 1
                ORDER BY a.vcnama";

      return $this->db->query($query)->result();
    }

    // OEE Part
    function getoperator($intmesin, $dttanggal, $intshift, $intlogin){
      $this->db->select('a.*, c.vcnama as vcoperator, c.vckode as vcnik');
      $this->db->from('a_log_login a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
      $this->db->where('DATE(a.dtlogin)', $dttanggal);
      $this->db->where('a.intshift', $intshift);
      $this->db->where('a.intlogin', $intlogin);
      $this->db->where('b.intmesin', $intmesin);

      if ($intlogin == 1) {
        $this->db->order_by('a.intid','ASC');
      } else {
        $this->db->order_by('a.intid','DESC');
      }

      return $this->db->get()->result();
    }

    function getoperator2($intmesin, $date1, $date2, $intshift, $intlogin){
      $this->db->select('a.*, c.vcnama as vcoperator, c.vckode as vcnik');
      $this->db->from('a_log_login a');
      $this->db->join('app_muser b', 'b.intid = a.intuser');
      $this->db->join('m_karyawan c', 'c.intid = a.intkaryawan');
      $this->db->where('a.dtlogin >= ', $date1);
      $this->db->where('a.dtlogin <= ', $date2);
      $this->db->where('a.intshift', $intshift);
      $this->db->where('a.intlogin', $intlogin);
      $this->db->where('b.intmesin', $intmesin);

      if ($intlogin == 1) {
        $this->db->order_by('a.intid','ASC');
      } else {
        $this->db->order_by('a.intid','DESC');
      }

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

    function getdataoutputall($datestart,$datefinish,$intmesin){
      $this->db->select('IFNULL(SUM(intpasang),0) as intactual, 
                        IFNULL(SUM(intreject),0) as intreject, 
                        IFNULL(AVG(decct), 0) as decct,
                        SUM(CASE WHEN inttarget = 0 THEN IFNULL(((decdurasi * 60) / decct),0) ELSE inttarget END) as inttarget,
                        IFNULL(SUM(intpasang * decct) - SUM(intpasang),0) as intlosses,
                        SUM(decct * (intpasang + intreject))/SUM(intpasang + intreject) AS decct2');
      $this->db->from('pr_output');
      $this->db->where("dttanggal >= '" . $datestart . "'");
      $this->db->where("dttanggal <= '" . $datefinish . "'");
      $this->db->where('intmesin', $intmesin);
      return $this->db->get()->result();
    }

    function getdataoutputkomponen2($datestart,$datefinish,$intmesin,$intshift){
      $this->db->select('a.*, b.vcnama as vcmodel, c.vcnama as vckomponen');
      $this->db->from('pr_output a');
      $this->db->join('m_models b','b.intid = a.intmodel');
      $this->db->join('m_komponen c','c.intid = a.intkomponen');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      return $this->db->get()->result();
    }

    function getdatadowntime2($datestart,$datefinish,$intmesin, $intshift){
      $this->db->select('a.*, b.vcnama as vcdowntime');
      $this->db->from('pr_downtime2 a');
      $this->db->join('m_type_downtime_list as b', 'b.intid = a.inttype_list');
      $this->db->where("a.dttanggal >= '" . $datestart . "'");
      $this->db->where("a.dttanggal <= '" . $datefinish . "'");
      $this->db->where('a.intmesin', $intmesin);
      $this->db->where('a.intshift', $intshift);
      return $this->db->get()->result();
    }

    function getowntimeoutput($datestart,$datefinish,$intmesin){
      $query = "SELECT SUM(CASE WHEN b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) AS decdurasi,
                  SUM(CASE WHEN a.inttype_downtime = 1 AND b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) AS decmachinedowntime,
                        SUM(CASE WHEN a.inttype_downtime = 2 AND b.intplanned = 0 THEN IFNULL(a.decdurasi,0) ELSE 0 END) AS decprosestime,
                        SUM(CASE WHEN b.intplanned = 1 THEN IFNULL(a.decdurasi,0) ELSE 0 END) AS decplanned,
                        c.intactual AS intactual,
                        c.intreject AS intreject,
                        c.decct AS decct,
                        c.decct2 AS decct2
                FROM pr_downtime2 a
                JOIN m_type_downtime_list b ON b.intid = a.inttype_list
                LEFT JOIN (SELECT intmesin,
                      IFNULL(SUM(intpasang),0) AS intactual, 
                                  IFNULL(SUM(intreject),0) AS intreject, 
                                  IFNULL(AVG(decct), 0) AS decct,
                                  SUM(CASE WHEN inttarget = 0 THEN IFNULL(((decdurasi * 60) / decct),0) ELSE inttarget END) AS inttarget,
                                  IFNULL(SUM(intpasang * decct) - SUM(intpasang),0) AS intlosses,
                                  SUM(decct * (intpasang + intreject))/SUM(intpasang + intreject) AS decct2
                        FROM pr_output
                  WHERE dttanggal >= '$datestart' AND
                  dttanggal <= '$datefinish' AND
                  intmesin = $intmesin) AS c ON c.intmesin = a.intmesin
                WHERE a.dttanggal >= '$datestart' AND
                  a.dttanggal <= '$datefinish' AND
                  a.intmesin = $intmesin";
      return $this->db->query($query)->result();
    }

    function getcentralcutting($intgedung){
      $this->db->where('intgedung',$intgedung);
      $this->db->where('inttype',2);
      return $this->db->get('m_cell')->result();
    }
}