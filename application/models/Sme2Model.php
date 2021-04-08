<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sme2Model extends CI_Model {

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
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate,
                          ISNULL(b.vcnama, 0) as vcgedung,
                          ISNULL(c.vcnama, 0) as vccell,
                          ISNULL(d.vcnama, 0) as vcmodel,
                        SUM(e.intapplicable) as intsumapplicable,
                        SUM(e.intcomply) as intsumcomply,
                        (SUM(e.intcomply)/SUM(e.intapplicable)) * 100 as decscore',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
        $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
        $this->db->join('m_models as d', 'd.intid = a.intmodel', 'left');
        $this->db->join('pr_sme2_detail as e','e.intheader = a.intid');
        $this->db->where('MONTH(a.dttanggal)',$intbulan);
        $this->db->where('YEAR(a.dttanggal)',$inttahun);
        if ($intgedung > 0) {
          $this->db->where('a.intgedung',$intgedung);
        }
        if ($intcell > 0) {
          $this->db->where('a.intcell',$intcell);
        }
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intbulan=0, $inttahun=0, $intgedung=0, $intcell=0){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate,
                          ISNULL(b.vcnama, 0) as vcgedung,
                          ISNULL(c.vcnama, 0) as vccell,
                          ISNULL(d.vcnama, 0) as vcmodel,
                        SUM(e.intapplicable) as intsumapplicable,
                        SUM(e.intcomply) as intsumcomply,
                        (SUM(e.intcomply)/SUM(e.intapplicable)) * 100 as decscore',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
        $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
        $this->db->join('m_models as d', 'd.intid = a.intmodel', 'left');
        $this->db->join('pr_sme2_detail as e','e.intheader = a.intid');
        $this->db->where('MONTH(a.dttanggal)',$intbulan);
        $this->db->where('YEAR(a.dttanggal)',$inttahun);
        if ($intgedung > 0) {
          $this->db->where('a.intgedung',$intgedung);
        }
        if ($intcell > 0) {
          $this->db->where('a.intcell',$intcell);
        }
        $this->db->group_by('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate, b.vcnama, c.vcnama, d.vcnama');
        $this->db->order_by('a.dtupdate','desc');
        $this->db->order_by('a.intid','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate,
                        ISNULL(b.vcnama, 0) as vcgedung,
                        ISNULL(c.vcnama, 0) as vccell,
                        ISNULL(d.vcnama, 0) as vcmodel',false);
      $this->db->from($table . ' as a');
      $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
      $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
      $this->db->join('m_models as d', 'd.intid = a.intmodel', 'left');
      $this->db->where('a.intid',$intid);
      return $this->db->get()->result();
    }

    function getdatadetail2($table,$intid){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate,
                        ISNULL(b.vcnama, 0) as vcgedung,
                        ISNULL(c.vcnama, 0) as vccell,
                        ISNULL(d.vcnama, 0) as vcmodel,
                        SUM(e.intapplicable) as intsumapplicable,
                        SUM(e.intcomply) as intsumcomply,
                        (SUM(e.intcomply)/SUM(e.intapplicable)) * 100 as decscore',false);
      $this->db->from($table . ' as a');
      $this->db->join('m_gedung as b', 'b.intid = a.intgedung', 'left');
      $this->db->join('m_cell as c', 'c.intid = a.intcell', 'left');
      $this->db->join('m_models as d', 'd.intid = a.intmodel', 'left');
      $this->db->join('pr_sme2_detail as e','e.intheader = a.intid');
      $this->db->where('a.intid',$intid);
      $this->db->group_by('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate,');
      return $this->db->get()->result();
    }

    function getteknologimesin(){
      $this->db->select('a.intid as intteknologimesin, b.intid as intprosesgroup, a.vcnama as vcteknologimesin, b.vcnama as vcprosesgroup, 0 as intapplicable, 0 as intcomply');
      $this->db->from('m_teknologimesin_sme2 as a');
      $this->db->join('m_prosesgroup_sme2 as b','b.intid = a.intprosesgroup');
      return $this->db->get()->result();
    }

    function getteknologimesin2($intheader){
      $this->db->select('a.intid, a.intheader, a.intprosesgroup, a.intteknologimesin, a.intapplicable, a.intcomply, a.vcketerangan,
                        ISNULL(b.vcnama, 0) as vcteknologimesin,
                        ISNULL(c.vcnama, 0) as vcprosesgroup');
      $this->db->from('pr_sme2_detail as a');
      $this->db->join('m_teknologimesin_sme2 as b','b.intid = a.intteknologimesin');
      $this->db->join('m_prosesgroup_sme2 as c','c.intid = a.intprosesgroup');
      $this->db->where('a.intheader',$intheader);
      return $this->db->get()->result();
    }

    function getcell(){
      $this->db->where('inttype',1);
      $this->db->order_by('intgedung');
      $this->db->order_by('intid');

      return $this->db->get('m_cell')->result();
    }

    function getsme2($intcell){
      $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate,
                        ISNULL(b.vcnama, 0) as vcmodel,
                        SUM(e.intapplicable) as intsumapplicable,
                        SUM(e.intcomply) as intsumcomply,
                        (SUM(e.intcomply)/SUM(e.intapplicable)) * 100 as decscore',false);
      $this->db->from('pr_sme2 a');
      $this->db->join('m_models b','b.intid = a.intmodel','left');
      $this->db->join('pr_sme2_detail as e','e.intheader = a.intid');
      $this->db->where('a.intcell',$intcell);
      $this->db->group_by('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmodel, a.intweek, a.intapplicable, a.intcomply, a.decpercent, a.vcartikel, a.dtupdate');
      return $this->db->get()->result();
    }

    function getdetailsme2($intheader,$intprosesgroup, $intteknologimesin){
      $this->db->select('a.intid, a.intheader, a.intprosesgroup, a.intteknologimesin, a.intapplicable, a.intcomply, a.vcketerangan,
                        ISNULL(b.vcnama, 0) as vcteknologimesin,
                        ISNULL(c.vcnama, 0) as vcprosesgroup');
      $this->db->from('pr_sme2_detail as a');
      $this->db->join('m_teknologimesin_sme2 as b','b.intid = a.intteknologimesin');
      $this->db->join('m_prosesgroup_sme2 as c','c.intid = a.intprosesgroup');
      $this->db->where('a.intheader',$intheader);
      $this->db->where('a.intprosesgroup',$intprosesgroup);
      $this->db->where('a.intteknologimesin',$intteknologimesin);
      return $this->db->get()->result();
    }

    function getmodelteknologimesin($intheader){
      $this->db->select('a.intid, a.intheader, a.intprosesgroup, a.intteknologimesin, a.intapplicable, a.intcomply, b.intid as intprosesgroup, b.vcnama as vcprosesgroup, c.intid as intteknologimesin, c.vcnama as vcteknologimesin, "" as vcketerangan');
      // $this->db->form('m_teknologimesin_sme2 a');
      // $this->db->join('m_prosesgroup_sme2 b','b.intid = a.intprosesgroup');
      $this->db->from('m_models_sme2 a');
      $this->db->join('m_prosesgroup_sme2 b', 'b.intid = a.intprosesgroup');
      $this->db->join('m_teknologimesin_sme2 c','c.intid = a.intteknologimesin');
      $this->db->where('intheader',$intheader);

      return $this->db->get()->result();
    }

}