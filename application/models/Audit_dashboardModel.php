<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_dashboardModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    // Autonomus Part
    function totalautonomus(){
     $this->db->select('COUNT(intid) AS decjumlahmesin,
                        SUM(CASE WHEN intformterisi = 100 THEN 1 ELSE 0 END) AS decjumlahdisiplin,
                        SUM(CASE WHEN intimplementasi = 100 THEN 1 ELSE 0 END) AS decjumlahpeduli,
                        ROUND(SUM(CASE WHEN intformterisi = 100 THEN 1 ELSE 0 END), 1) AS persendisiplin,
                        ROUND(SUM(CASE WHEN intimplementasi = 100 THEN 1 ELSE 0 END), 1) AS persenpeduli
                      ');
      $this->db->from('pr_am');

      return $this->db->get()->result();
    }

    function grafikautonomus($intbulan,$intgedung,$inttahun=0){
      $dttahun = ($inttahun == 0) ? date('Y') : $inttahun;
      $this->db->select('COUNT(intid) AS decjumlahmesin,
                          SUM(CASE WHEN intformterisi = 100 THEN 1 ELSE 0 END) AS decjumlahdisiplin,
                          SUM(CASE WHEN intimplementasi = 100 THEN 1 ELSE 0 END) AS decjumlahpeduli,
                          ROUND(SUM(CASE WHEN intformterisi = 100 THEN 1 ELSE 0 END) / COUNT(intid) * 100, 1) AS persendisiplin,
                          ROUND(SUM(CASE WHEN intimplementasi = 100 THEN 1 ELSE 0 END) / COUNT(intid) * 100, 1) AS persenpeduli
                      ');
      $this->db->from('pr_am');
      $this->db->where('MONTH(dttanggal)',$intbulan);
      $this->db->where('YEAR(dttanggal)',$dttahun);
      $this->db->where('intgedung',$intgedung);
      return $this->db->get()->result();
    }

    // function grafikammonthpercell($intbulan,$intcell){
    //   $this->db->select('b.vcnama as vccell,
    //                       COUNT(a.intid) AS decjumlahmesin,
    //                       SUM(CASE WHEN a.intformterisi = 100 THEN 1 ELSE 0 END) AS decjumlahdisiplin,
    //                       SUM(CASE WHEN a.intimplementasi = 100 THEN 1 ELSE 0 END) AS decjumlahpeduli,
    //                       ROUND(SUM(CASE WHEN a.intformterisi = 100 THEN 1 ELSE 0 END) / COUNT(a.intid) * 100, 2) AS persendisiplin,
    //                       ROUND(SUM(CASE WHEN a.intimplementasi = 100 THEN 1 ELSE 0 END) / COUNT(a.intid) * 100, 2) AS persenpeduli
    //                   ');
    //   $this->db->from('pr_am a');
    //   $this->db->join('m_cell b','b.intid = a.intcell');
    //   $this->db->where('MONTH(dttanggal)',$intbulan);
    //   $this->db->where('intcell',$intcell);
    //   return $this->db->get()->result();
    // }

    function grafikammonthpercell($intbulan,$intcell){
      $this->db->select('ISNULL(a.vcnama, 0) as vccell,
                          COUNT(b.intid) AS decjumlahmesin,
                          SUM(CASE WHEN b.intformterisi = 100 THEN 1 ELSE 0 END) AS decjumlahdisiplin,
                          SUM(CASE WHEN b.intimplementasi = 100 THEN 1 ELSE 0 END) AS decjumlahpeduli,
                          ROUND(SUM(CASE WHEN b.intformterisi = 100 THEN 1 ELSE 0 END), 1) AS persendisiplin,
                          ROUND(SUM(CASE WHEN b.intimplementasi = 100 THEN 1 ELSE 0 END), 1) AS persenpeduli
                      ');
      $this->db->from('m_cell a');
      $this->db->join('pr_am b','b.intcell = a.intid','left');
      $this->db->where('MONTH(b.dttanggal)',$intbulan);
      $this->db->where('a.intid',$intcell);
      $this->db->group_by('a.vcnama');
      return $this->db->get()->result();
    }

    function grafikammonthallcell($intgedung,$intbulan=0,$inttahun=0){
      $dttahun = ($inttahun == 0) ? date('Y') : $inttahun;
      $dtbulan = ($intbulan == 0) ? date('Y') : $intbulan;

      $this->db->select('a.vcnama as vccell,
                          COUNT(b.intid) AS decjumlahmesin,
                          SUM(CASE WHEN b.intformterisi = 100 THEN 1 ELSE 0 END) AS decjumlahdisiplin,
                          SUM(CASE WHEN b.intimplementasi = 100 THEN 1 ELSE 0 END) AS decjumlahpeduli,
                          ROUND(SUM(CASE WHEN b.intformterisi = 100 THEN 1 ELSE 0 END), 1) AS persendisiplin,
                          ROUND(SUM(CASE WHEN b.intimplementasi = 100 THEN 1 ELSE 0 END), 1) AS persenpeduli
                      ');
      $this->db->from('m_cell a');
      $this->db->join('pr_am b','b.intcell = a.intid','left');
      $this->db->where('MONTH(b.dttanggal)',$dtbulan);
      $this->db->where('YEAR(b.dttanggal)',$dttahun);
      $this->db->where('a.intgedung',$intgedung);
      $this->db->group_by('a.intid, a.vcnama');
      return $this->db->get()->result();
    }

    // SME2 Part
    function grafiksme2($intgedung, $intweek, $intbulan, $inttahun){
      $this->db->select('ROUND(AVG(decpercent), 0) as averagesme');
      $this->db->from('pr_sme2');
      $this->db->where('MONTH(dttanggal)',$intbulan);
      $this->db->where('YEAR(dttanggal)',$inttahun);
      // $this->db->where('intgedung',$intgedung);
      $this->db->where('intweek',$intweek);
      return $this->db->get()->result();
    }

    function grafiksme2monthpercell($intbulan, $intweek, $intcell){
      $this->db->select('a.vcnama as vccell,
                        c.vcnama as vcmodel,
                        ROUND(AVG(b.intapplicable), 0) as intapplicable,
                        ROUND(AVG(b.intcomply), 0) as intcomply,
                        ROUND(AVG(b.decpercent), 0) as decpercent');
      $this->db->from('m_cell a');
      $this->db->join('pr_sme2 b','b.intcell = a.intid','left');
      $this->db->join('m_models c','c.intid = b.intmodel');
      $this->db->where('MONTH(b.dttanggal)',$intbulan);
      $this->db->where('a.intid',$intcell);
      $this->db->where('(b.intweek = '.$intweek.' OR 0 = '.$intweek.')');
      $this->db->group_by('b.intcell, a.vcnama, c.vcnama');
      return $this->db->get()->result();
    }

    function grafiksme2monthall($intbulan, $intweek){
      $this->db->select('ROUND(AVG(a.intapplicable), 0) as intapplicable,
                        ROUND(AVG(a.intcomply), 0) as intcomply,
                        ROUND(AVG(a.decpercent), 0) as decpercent');
      $this->db->from('pr_sme2 a');
      $this->db->where('MONTH(a.dttanggal)',$intbulan);
      $this->db->where('(a.intweek = '.$intweek.' OR 0 = '.$intweek.')');
      return $this->db->get()->result();
    }

    function getgedung(){
      return $this->db->get('m_gedung')->result();
    }

    function getcell($intgedung){
      $this->db->where('intgedung',$intgedung);
      $this->db->where('inttype',1);
      return $this->db->get('m_cell')->result();
    }

    // function grafikammonthpercell($intbulan,$intcell){
    //   $this->db->select('a.*, d.vcnama as vccell, IFNULL(b.vckode,"") as vckodemesin, IFNULL(c.vcnama,a.vcoperator)');
    //   $this->db->from('pr_am a');
    //   $this->db->join('m_mesin b','b.intid = a.intmesin','left');
    //   $this->db->join('m_karyawan c','c.intid = a.intoperator','left');
    //   $this->db->join('m_cell d','d.intid = a.intcell');
    //   $this->db->where('MONTH(a.dttanggal)',$intbulan);
    //   $this->db->where('a.intcell',$intcell);
    //   return $this->db->get()->result();
    // }
}