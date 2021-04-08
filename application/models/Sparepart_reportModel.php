<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart_reportModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function jumlahreportall($dtmonth,$intyear){
      $query = "SELECT 
                  b.vckode AS vckodesparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  b.vcnama AS vcsparepart,
                  c.vcnama as vcunit,
                  ISNULL(d.awal,0) AS awal,
                  SUM(a.decqtymasuk) AS masuk,
                  SUM(a.decqtykeluar) AS keluar,
                  (ISNULL(d.awal,0) + SUM(a.decqtymasuk)) - SUM(.decqtykeluar) AS akhir
                FROM pr_sparepart a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                LEFT JOIN (SELECT intsparepart,
                    (SUM(decqtymasuk) - SUM(decqtykeluar)) AS awal
                  FROM pr_sparepart
                  WHERE MONTH(dtinout) <= " . $dtmonth . " - 1 AND YEAR(dtinout) = " . $intyear . " AND intstatus = 1) d ON d.intsparepart = a.intsparepart
                WHERE a.intstatus = 1 AND MONTH(a.dtinout) = " . $dtmonth . "
                AND YEAR(a.dtinout) = " . $intyear . "
                GROUP BY a.intsparepart";
      return $this->db->query($query)->num_rows();
    }
    
    function getreportall($dtmonth,$intyear){
      $query = 'SELECT 
                  b.vckode AS vckodesparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  b.vcnama AS vcsparepart,
                  c.vcnama as vcunit,
                  ISNULL(d.awal,0) AS awal,
                  SUM(decqtymasuk) AS masuk,
                  SUM(decqtykeluar) AS keluar,
                  (ISNULL(d.awal,0) + SUM(decqtymasuk)) - SUM(decqtykeluar) AS akhir
                FROM pr_sparepart a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                LEFT JOIN (SELECT intsparepart,
                    (SUM(decqtymasuk) - SUM(decqtykeluar)) AS awal
                  FROM pr_sparepart
                  WHERE MONTH(dtinout) <= '.$dtmonth.' - 1 AND YEAR(dtinout) = '.$intyear.' AND intstatus = 1) d ON d.intsparepart = a.intsparepart
                WHERE a.intstatus = 1 AND MONTH(a.dtinout) = '.$dtmonth.'
                AND YEAR(a.dtinout) = '.$intyear.'
                GROUP BY a.intsparepart';
      return $this->db->query($query)->result();
    }

    function getreportalllimit($dtmonth,$intyear,$halaman=0, $limit=5){
      $query = 'SELECT 
                  b.vckode AS vckodesparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  b.vcnama AS vcsparepart,
                  c.vcnama as vcunit,
                  ISNULL(d.awal,0) AS awal,
                  SUM(decqtymasuk) AS masuk,
                  SUM(decqtykeluar) AS keluar,
                  (ISNULL(d.awal,0) + SUM(decqtymasuk)) - SUM(decqtykeluar) AS akhir
                FROM pr_sparepart a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                LEFT JOIN (SELECT intsparepart,
                    (SUM(decqtymasuk) - SUM(decqtykeluar)) AS awal
                  FROM pr_sparepart
                  WHERE MONTH(a.dtinout) <= '.$dtmonth.' - 1 AND YEAR(dtinout) = '.$intyear.' AND intstatus = 1 GROUP BY intsparepart) d ON d.intsparepart = a.intsparepart
                WHERE a.intstatus = 1 AND MONTH(a.dtinout) = '.$dtmonth.'
                AND YEAR(a.dtinout) = '.$intyear.'
                GROUP BY a.intsparepart
                LIMIT ' . $limit . ' OFFSET ' . $halaman;
      return $this->db->query($query)->result();

    }

    function jumlahreportpersparepart($intsparepart,$dtmonth,$intyear){
      $query = "SELECT
                  a.intid, a.intsparepart, a.intinout, a.vcnomor_po, a.intgedung, a.dtorder, a.dtinout, a.intsuplier, a.decqtymasuk, a.decqtykeluar, a.decharga, a.dectotal, a.dtupdate, a.vckode, a.vcketerangan, 
                  b.vcnama AS vcsparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  c.vcnama AS vcunit
                FROM pr_sparepart a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                WHERE a.intsparepart = " .$intsparepart . " AND MONTH(a.dtinout) = " . $dtmonth ." AND YEAR(a.dtinout) = " . $intyear ." AND a.intstatus = 1 
                ORDER BY a.dtinout ASC";
      return $this->db->query($query)->num_rows();
    }
    
    function getreportpersparepat($intsparepart,$dtmonth,$intyear){
      $query = "SELECT
                  a.intid, a.intsparepart, a.intinout, a.vcnomor_po, a.intgedung, a.dtorder, a.dtinout, a.intsuplier, a.decqtymasuk, a.decqtykeluar, a.decharga, a.dectotal, a.dtupdate, a.vckode, a.vcketerangan, 
                  b.vcnama AS vcsparepart,
                  b.vcspesifikasi AS vcspesifikasi,
                  b.vcpart AS vcpart,
                  c.vcnama AS vcunit
                FROM pr_sparepart a
                JOIN m_sparepart b ON b.intid = a.intsparepart
                JOIN m_unit c ON c.intid = b.intunit
                WHERE a.intsparepart = " . $intsparepart . " AND MONTH(a.dtinout) = " . $dtmonth . " AND YEAR(a.dtinout) = " . $intyear . " AND a.intstatus = 1
                ORDER BY a.dtinout ASC";
      return $this->db->query($query)->result();
    }

    function getreportpersparepatlimit($intsparepart,$dtmonth,$intyear,$halaman=0, $limit=5){
      $this->db->select('a.intid, a.intsparepart, a.intinout, a.vcnomor_po, a.intgedung, a.dtorder, a.dtinout, a.intsuplier,
                          a.decqtymasuk,a.decqtykeluar, a.decharga, a.dectotal, a.dtupdate, a.vckode, a.vcketerangan, 
                          b.vcnama AS vcsparepart,
                          b.vcspesifikasi AS vcspesifikasi,
                          b.vcpart AS vcpart,
                          c.vcnama AS vcunit',false);
      $this->db->from('pr_sparepart as a');
      $this->db->join('m_sparepart' . ' as b', 'b.intid = a.intsparepart', 'left');
      $this->db->join('m_unit' . ' as c', 'c.intid = b.intunit', 'left');
      $this->db->where('a.intsparepart', $intsparepart);
      $this->db->where('MONTH(a.dtinout)', $dtmonth);
      $this->db->where('YEAR(a.dtinout)', $intyear);
      $this->db->where('a.intstatus', 1);
      $this->db->order_by('a.dtinout','asc');
      $this->db->limit($limit, $halaman);
      return $this->db->get()->result();
    }
}