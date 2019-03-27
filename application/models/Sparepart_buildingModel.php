<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart_buildingModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function jumlahreportall($dtmonth,$intyear,$intgedung=0){
      $query = 'SELECT 
                  b.vckode AS vckodesparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  b.vcnama AS vcsparepart,
                  c.vcnama as vcunit,
                  IFNULL(d.awal,0) AS awal,
                  SUM(decqtymasuk) AS masuk,
                  SUM(decqtykeluar) AS keluar,
                  (IFNULL(d.awal,0) + SUM(decqtymasuk)) - SUM(decqtykeluar) AS akhir
                FROM pr_sparepart_gedung a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                LEFT JOIN (SELECT intsparepart,
                    (SUM(decqtymasuk) - SUM(decqtykeluar)) AS awal
                  FROM pr_sparepart_gedung
                  WHERE MONTH(`dtinout`) <= '.$dtmonth.' - 1 AND YEAR(`dtinout`) = '.$intyear.' AND (0 = '.$intgedung.' || intgedung = '.$intgedung.') ) d ON d.intsparepart = a.`intsparepart`
                WHERE MONTH(a.`dtinout`) = '.$dtmonth.'
                AND YEAR(a.`dtinout`) = '.$intyear.'
                AND (0 = '.$intgedung.' || a.intgedung = '.$intgedung.')
                GROUP BY a.intsparepart';
      return $this->db->query($query)->num_rows();
    }
    
    function getreportall($dtmonth,$intyear,$intgedung=0){
      $query = 'SELECT 
                  b.vckode AS vckodesparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  b.vcnama AS vcsparepart,
                  c.vcnama as vcunit,
                  IFNULL(d.awal,0) AS awal,
                  SUM(decqtymasuk) AS masuk,
                  SUM(decqtykeluar) AS keluar,
                  (IFNULL(d.awal,0) + SUM(decqtymasuk)) - SUM(decqtykeluar) AS akhir
                FROM pr_sparepart_gedung a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                LEFT JOIN (SELECT intsparepart,
                    (SUM(decqtymasuk) - SUM(decqtykeluar)) AS awal
                  FROM pr_sparepart_gedung
                  WHERE MONTH(`dtinout`) <= '.$dtmonth.' - 1 AND YEAR(`dtinout`) = '.$intyear.' AND (0 = '.$intgedung.' || intgedung = '.$intgedung.') AND intstatus = 1) d ON d.intsparepart = a.`intsparepart`
                WHERE MONTH(a.`dtinout`) = '.$dtmonth.'
                AND YEAR(a.`dtinout`) = '.$intyear.' 
                AND (0 = '.$intgedung.' || a.intgedung = '.$intgedung.')
                GROUP BY a.intsparepart';
      return $this->db->query($query)->result();
    }

    function getreportalllimit($dtmonth,$intyear,$intgedung=0,$halaman=0, $limit=5){
      $query = 'SELECT 
                  b.vckode AS vckodesparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  b.vcnama AS vcsparepart,
                  c.vcnama as vcunit,
                  IFNULL(d.awal,0) AS awal,
                  SUM(decqtymasuk) AS masuk,
                  SUM(decqtykeluar) AS keluar,
                  (IFNULL(d.awal,0) + SUM(decqtymasuk)) - SUM(decqtykeluar) AS akhir
                FROM pr_sparepart_gedung a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                LEFT JOIN (SELECT intsparepart,
                    (SUM(decqtymasuk) - SUM(decqtykeluar)) AS awal
                  FROM pr_sparepart_gedung
                  WHERE MONTH(`dtinout`) <= '.$dtmonth.' - 1 AND YEAR(`dtinout`) = '.$intyear.' AND (0 = '.$intgedung.' || intgedung = '.$intgedung.') GROUP BY intsparepart) d ON d.intsparepart = a.`intsparepart`
                WHERE MONTH(a.`dtinout`) = '.$dtmonth.'
                AND YEAR(a.`dtinout`) = '.$intyear.' 
                AND (0 = '.$intgedung.' || a.intgedung = '.$intgedung.')
                GROUP BY a.intsparepart
                LIMIT ' . $limit . ' OFFSET ' . $halaman;
      return $this->db->query($query)->result();
    }

    function jumlahreportpersparepart($intsparepart,$dtmonth,$intyear,$intgedung=0){
      $query = 'SELECT
                  a.*,
                  b.vcnama AS vcsparepart,
                  b.`vcspesifikasi` AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  c.vcnama AS vcunit
                FROM pr_sparepart_gedung a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                WHERE a.intsparepart = '.$intsparepart.' AND MONTH(a.dtinout) = '.$dtmonth.' AND YEAR(a.dtinout) = '.$intyear.' 
                AND (0 = '.$intgedung.' || a.intgedung = '.$intgedung.')  
                ORDER BY dtinout ASC';
      return $this->db->query($query)->num_rows();
    }
    
    function getreportpersparepat($intsparepart,$dtmonth,$intyear,$intgedung=0){
      $query = 'SELECT
                  a.*,
                  b.vcnama AS vcsparepart,
                  b.`vcspesifikasi` AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  c.vcnama AS vcunit
                FROM pr_sparepart_gedung a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                WHERE a.intsparepart = '.$intsparepart.' AND MONTH(a.dtinout) = '.$dtmonth.' AND YEAR(a.dtinout) = '.$intyear.' 
                AND (0 = '.$intgedung.' || a.intgedung = '.$intgedung.') 
                ORDER BY dtinout ASC';
      return $this->db->query($query)->result();
    }

    function getreportpersparepatlimit($intsparepart,$dtmonth,$intyear,$intgedung=0,$halaman=0, $limit=5){
      $query = 'SELECT
                  a.*,
                  b.vcnama AS vcsparepart,
                  b.`vcspesifikasi` AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  c.vcnama AS vcunit
                FROM pr_sparepart_gedung a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                WHERE a.intsparepart = '.$intsparepart.' AND MONTH(a.dtinout) = '.$dtmonth.' AND YEAR(a.dtinout) = '.$intyear.' 
                AND (0 = '.$intgedung.' || a.intgedung = '.$intgedung.')
                ORDER BY dtinout ASC
                LIMIT ' . $limit . ' OFFSET ' . $halaman;
      return $this->db->query($query)->result();
    }
}