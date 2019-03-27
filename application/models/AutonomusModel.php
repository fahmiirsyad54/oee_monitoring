<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AutonomusModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $intbulan=0, $inttahun=0, $intgedung=0, $intcell=0){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->where('MONTH(a.dttanggal)',$intbulan);
        $this->db->where('YEAR(a.dttanggal)',$inttahun);
        if ($intgedung > 0) {
          $this->db->where('a.intgedung',$intgedung);
        }
        if ($intcell > 0) {
          $this->db->where('a.intcell',$intcell);
        }
        return $this->db->get()->result();
    }

    function getdata($table, $intbulan=0, $inttahun=0, $intgedung=0, $intcell=0){
        $this->db->select('a.*,
                          IFNULL(b.vcnama, "") as vcgedung,
                          IFNULL(c.vcnama, "") as vccell,
                          IFNULL(d.vckode, "") as vckodeoperator,
                          IFNULL(d.vcnama, "") as vcoperator,
                          IFNULL(e.vckode, "") as vckodemesin,
                          IFNULL(e.vcnama, "") as vcmesin',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
        $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
        $this->db->join('m_karyawan as d', 'd.intid = a.intoperator', 'left');
        $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        $this->db->where('MONTH(a.dttanggal)',$intbulan);
        $this->db->where('YEAR(a.dttanggal)',$inttahun);
        if ($intgedung > 0) {
          $this->db->where('a.intgedung',$intgedung);
        }
        if ($intcell > 0) {
          $this->db->where('a.intcell',$intcell);
        }
        $this->db->group_by('a.intid');
        $this->db->order_by('a.dtupdate','desc');
        $this->db->order_by('a.intid','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intbulan=0, $inttahun=0, $intgedung=0, $intcell=0){
        $this->db->select('a.*,
                          IFNULL(b.vcnama, "") as vcgedung,
                          IFNULL(c.vcnama, "") as vccell,
                          IFNULL(d.vckode, "") as vckodeoperator,
                          IFNULL(d.vcnama, a.vcoperator) as vcoperator,
                          IFNULL(e.vckode, "") as vckodemesin,
                          IFNULL(e.vcnama, "") as vcmesin',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
        $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
        $this->db->join('m_karyawan as d', 'd.intid = a.intoperator', 'left');
        $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        $this->db->where('MONTH(a.dttanggal)',$intbulan);
        $this->db->where('YEAR(a.dttanggal)',$inttahun);
        if ($intgedung > 0) {
          $this->db->where('a.intgedung',$intgedung);
        }
        if ($intcell > 0) {
          $this->db->where('a.intcell',$intcell);
        }
        $this->db->group_by('a.intid');
        $this->db->order_by('a.dtupdate','desc');
        $this->db->order_by('a.intid','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.*,
                          IFNULL(b.vcnama, "") as vcgedung,
                          IFNULL(c.vcnama, "") as vccell,
                          IFNULL(d.vckode, "") as vckodeoperator,
                          IFNULL(d.vcnama, "") as vcoperator,
                          IFNULL(e.vckode, "") as vckodemesin,
                          IFNULL(e.vcnama, "") as vcmesin',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
        $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
        $this->db->join('m_karyawan as d', 'd.intid = a.intoperator', 'left');
        $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    function getdataam($table, $intbulan=0, $inttahun=0, $intgedung=0, $intcell=0){
        // $this->db->select('a.*,
        //                   IFNULL(b.vcnama, "") as vcgedung,
        //                   IFNULL(c.vcnama, "") as vccell,
        //                   IFNULL(d.vckode, "") as vckodeoperator,
        //                   IFNULL(d.vcnama, a.vcoperator) as vcoperator,
        //                   IFNULL(e.vckode, "") as vckodemesin,
        //                   IFNULL(e.vcnama, "") as vcmesin',false);
        // $this->db->from($table . ' as a');
        // $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
        // $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
        // $this->db->join('m_karyawan as d', 'd.intid = a.intoperator', 'left');
        // $this->db->join('m_mesin as e', 'e.intid = a.intmesin', 'left');
        // $this->db->where('MONTH(a.dttanggal)',$intbulan);
        // $this->db->where('YEAR(a.dttanggal)',$inttahun);
        // if ($intgedung > 0) {
        //   $this->db->where('a.intgedung',$intgedung);
        // }
        // if ($intcell > 0) {
        //   $this->db->where('a.intcell',$intcell);
        // }
        // $this->db->group_by('a.intid');
        // $this->db->order_by('a.intcell','asc');
        $query = "SELECT  0 AS intorder,
                  a.dttanggal AS dttanggal,
                  a.intcell AS intcell,
                  IFNULL(b.vcnama,'') AS vcgedung,
                  IFNULL(c.vcnama,'') AS vccell,
                  IFNULL(d.vckode,'') AS vckodeoperator,
                  IFNULL(d.vcnama,a.vcoperator) AS vcoperator,
                  IFNULL(e.vckode,'') AS vckodemesin,
                  IFNULL(e.vcnama,'') AS vcmesin,
                  a.intformterisi AS intformterisi,
                  a.intimplementasi AS intimplementasi,
                  0 AS intjumlahmesin,
                  a.vcketerangan
                FROM pr_am a
                LEFT JOIN m_gedung b ON b.intid = a.intgedung
                LEFT JOIN m_cell c ON c.intid = a.intcell
                LEFT JOIN m_karyawan d ON d.intid = a.intoperator
                LEFT JOIN m_mesin e ON e.intid = a.intmesin
                WHERE MONTH(a.dttanggal) = $intbulan AND
                  YEAR(a.dttanggal) = $inttahun AND
                  (0 = $intgedung OR a.intgedung = $intgedung) AND
                  (0 = $intcell OR a.intcell = $intcell)
                UNION ALL
                SELECT  1 AS intorder,
                  '' AS dttanggal,
                  a.intcell AS intcell,
                  '' AS vcgedung,
                  IFNULL(b.vcnama, '') AS vccell,
                  '' AS vckodeoperator,
                  '' AS vcoperator,
                  '' AS vckodemesin,
                  '' AS vcmesin,
                  SUM(CASE WHEN a.intformterisi = 100 THEN 1 ELSE 0 END) AS intformterisi,
                  SUM(CASE WHEN a.intimplementasi = 100 THEN 1 ELSE 0 END) AS intimplementasi,
                  COUNT(a.intid) AS intjumlahmesin,
                  '' AS vcketerangan
                FROM pr_am a
                LEFT JOIN m_cell b ON b.intid = a.intcell
                GROUP BY intcell
                ORDER BY intcell ASC, intorder ASC";

        return $this->db->query($query)->result();
    }

}