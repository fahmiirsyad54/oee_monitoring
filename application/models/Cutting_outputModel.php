<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cutting_outputModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }
 
    function getjmldata($table, $from=null, $to=null){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from('pr_cutting_output as a');
        if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        } 
        //$this->db->group_by('a.intkomponen');
        return $this->db->get()->result();


    }
 
    function getdata($table, $intgedung=0,$from=null, $to=null){
        $this->db->select('sum(a.intpasang) as intpasang, sum(a.inttarget) as inttarget,
                          ISNULL(b.vcnama, 0) as vcmodel,
                          ISNULL(c.vcnama, 0) as vckomponen',false);
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
        return $this->db->get()->result();
    }
    
    function getdatalimit($table, $intgedung=0, $halaman=0, $limit=5,$from=null, $to=null){
          $this->db->select('a.intid, a.dttanggal, ISNULL(b.vcnama, 0) as vcgedung, 
                            ISNULL(c.vcnama, 0) as vcmodel, a.intcell, a.inttarget',false);
          $this->db->from($table. ' as a');
          $this->db->join('m_gedung as b', 'a.intgedung = b.intid', 'left');
          $this->db->join('m_models as c', 'a.intmodel = c.intid', 'left');

          if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
          }

          if ($intgedung > 0) {
          $this->db->where('a.intgedung',$intgedung);
          }
          // $this->db->group_by('a.intgedung, a.intmodel, a.intkomponen, b.vcnama, c.vcnama, a.dttanggal, 
          //                     e.vcnama, d.intcell, d.inttarget, a.dtupdate ');
          // $this->db->having('sum(d.inttarget) > 0');
          $this->db->order_by('a.dtupdate','desc');
          $this->db->limit($limit, $halaman);
          return $this->db->get()->result();
    }

    function getjmldatapershift($table, $intmesin=0, $from=null, $to=null, $intshift){
        $this->db->select('count(a.intmesin) as jmldata',false);
        $this->db->from($table . ' as a');
        if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->where('a.intshift', $intshift);

        return $this->db->get()->result();
    }
 
    function getdatapershift($table, $intmesin=0,$from=null, $to=null, $intshift){
        $this->db->select('min(a.dttanggal) as dttanggal, min(d.dtlogin) as dtlogin, 
                          min(CONVERT(varchar(8),a.dtmulai,108)) as dtmulai,
                          ISNULL(e.vcnama, 0) as vccell, 
                          ISNULL(f.vcnama, 0) as vcshift,
                          ISNULL(b.vcnama, 0) as vcnamamesin, 
                          ISNULL(b.vckode, 0) as vcmesin',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_mesin as b', 'a.intmesin = b.intid', 'left');
        $this->db->join('app_muser as c', 'a.intmesin = c.intmesin', 'left');
        $this->db->join('a_log_login as d', 'c.intid = d.intuser', 'left');
        $this->db->join('m_cell as e', 'a.intcell = e.intid', 'left');
        $this->db->join('m_shift as f', 'a.intshift = f.intid', 'left');
        
        if ($from) {
          $this->db->where('a.dttanggal >= ', $from);
          $this->db->where('a.dttanggal <= ', $to);
          $this->db->where('d.dtlogin >= ', $from);
          $this->db->where('d.dtlogin <= ', $to);
        }

        if ($intmesin > 0) {
          $this->db->where('a.intmesin',$intmesin); 
        }
        $this->db->where('a.intshift', $intshift);
        $this->db->where('d.intshift', $intshift);
        $this->db->group_by('b.vckode, a.intmesin, b.vcnama, b.intsortall, e.vcnama, f.vcnama');
        $this->db->order_by('b.intsortall','asc');
        return $this->db->get()->result();
    }
    
    function getdatalimitpershift($table, $intgedung=0, $halaman=0, $limit=5, $from=null, $to=null, $intshift){
        $this->db->select('sum(a.intpasang) as intpasang, sum(a.inttarget) as inttarget,
              a.dttanggal, a.intcell, ISNULL(c.vcnama, 0) as vcgedung, 
              ISNULL(d.vcnama, 0) as vcmodel, ISNULL(e.vcnama, 0) as vckomponen, 
              ISNULL(f.vcnama, 0) as vcshift, ISNULL(b.vcnama, 0) as vcstatus, 
              ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_gedung as c', 'a.intgedung = c.intid', 'left');
        $this->db->join('m_models as d', 'a.intmodel = d.intid', 'left');
        $this->db->join('m_komponen as e', 'a.intkomponen = e.intid', 'left');
        $this->db->join('m_shift as f', 'a.intshift = f.intid', 'left');
        $this->db->join('pr_cutting_output as g', 'g.intgedung = a.intgedung', 'left');
        if ($from) {
        $this->db->where('a.dttanggal >= ', $from);
        $this->db->where('a.dttanggal <= ', $to);
        $this->db->where('g.dttanggal >= ', $from);
        $this->db->where('g.dttanggal <= ', $to);
        }

        if ($intgedung > 0) {
        $this->db->where('a.intgedung',$intgedung); 
        }
        $this->db->where('a.intshift',$intshift); 
        $this->db->group_by('a.intgedung, a.intmodel, a.intkomponen, a.dttanggal, a.dtupdate, a.intcell, 
                        b.vcnama, c.vcnama, d.vcnama, e.vcnama, f.vcnama, b.vcwarna ');
        $this->db->having('sum(a.intpasang) > 0');
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.intid, a.dttanggal, a.intgedung, a.intcell, a.intmesin, a.intoperator, a.intleader, a.intshift, intmodel, a.intkomponen, a.decct, a.intlayer, a.intremark, a.dtmulai, a.dtselesai, a.decdurasi, a.intpasang, a.intreject, a.inttarget, a.dtupdate, a.intstatus, a.vcketerangan, 
                                ISNULL(c.vcnama, 0) as vcmodel, 
                                ISNULL(d.vcnama, 0) as vccell, 
                                ISNULL(f.vcnama, 0) as vcshift, 
                                ISNULL(e.vckode, 0) as vcmesin, 
                                ISNULL(g.vcnama, 0) as vcgedung,
                                ISNULL(h.vcnama, 0) as vcoperator, 
                                ISNULL(i.vcnama, 0) as vcleader,
                                ISNULL(j.vcnama, 0) as vcmodel,
                                ISNULL(k.vcnama, 0) as vckomponen,
                                ISNULL(b.vcnama, 0) as vcstatus, 
                                ISNULL(b.vcwarna, 0) as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->join('m_models as c', 'a.intmodel = c.intid', 'left');
        $this->db->join('m_cell as d', 'a.intcell = d.intid', 'left');
        $this->db->join('m_shift as f', 'a.intshift = f.intid', 'left');
        $this->db->join('m_mesin as e', 'a.intmesin = e.intid', 'left');
        $this->db->join('m_gedung as g', 'a.intgedung = g.intid', 'left');
        $this->db->join('m_karyawan as h', 'h.intid = a.intoperator', 'left');
        $this->db->join('m_karyawan as i', 'i.intid = a.intleader', 'left');
        $this->db->join('m_models as j', 'j.intid = a.intmodel', 'left');
        $this->db->join('m_komponen as k', 'k.intid = a.intkomponen', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    public function buat_kode()   {
          $this->db->select('RIGHT(pr_downtime.vckode,4) as kode', FALSE);
          $this->db->order_by('intid','DESC');    
          $this->db->limit(1);    
          $query = $this->db->get('pr_downtime');      //cek dulu apakah ada sudah ada kode di tabel.    
          if($query->num_rows() <> 0){      
           //jika kode ternyata sudah ada.      
           $data = $query->row();      
           $kode = intval($data->kode) + 1;    
          }
          else {      
           //jika kode belum ada      
           $kode = 1;    
          }
          $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
          $kodejadi = "DT".$kodemax;    // hasilnya ODJ-9921-0001 dst.
          return $kodejadi;  
    }

    function getdatakaryawan($table,$intgedung,$intjabatan){
        $this->db->where('intgedung',$intgedung);
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getdatamesin($intgedung=0, $intmesin=0){
      if ($intgedung > 0) {
        $this->db->where('intgedung',$intgedung);
      }

      if ($intmesin > 0) {
        $this->db->where('intid',$intmesin);  
      }
      
      $this->db->where('intautocutting',1);
      $this->db->order_by('intsortall','asc');
      return $this->db->get('m_mesin')->result();
    }

    function getkomponen($intid){
        $this->db->select('a.intid, a.intheader, a.intkomponen, a.deccycle_time, a.intlayer, b.vcnama as vckomponen',false);
        $this->db->from('m_models_komponen as a');
        $this->db->join('m_komponen as b','b.intid = a.intkomponen');
        $this->db->where('a.intheader',$intid);

        return $this->db->get()->result();
    }

    function getintkomponen($intkomponen){
        $this->db->select('intid, intheader, intkomponen, deccycle_time, intlayer',false);
        $this->db->from('m_models_komponen');
        $this->db->where('intkomponen',$intkomponen);
      
      return $this->db->get()->result(); 
    }

    function getdatacombine($intmesincombine, $intoperatorcombine, $dttanggalcombine, $dtmulaicombine, $dtselesaicombine){
      $this->db->where('intmesin',$intmesincombine);
      $this->db->where('intoperator',$intoperatorcombine);
      $this->db->where('dtmulai',$dtmulaicombine);
      $this->db->where('dtselesai',$dtselesaicombine);
      $this->db->where('convert(varchar(26),dttanggal,23)',$dttanggalcombine);

      return $this->db->get('pr_output')->result();
    }

    function getmesin(){
      $this->db->select('intid, vckode, vcnama');
      $this->db->where('intautocutting',1);
      return $this->db->get('m_mesin')->result();
    }

    function getkaryawan($table,$intjabatan){
        $this->db->select('intid, vckode, vcnama');
        $this->db->where('intjabatan',$intjabatan);
        return $this->db->get($table)->result();
    }

    function getgedungautocutting(){
      $this->db->where('intoeemonitoring > ', 0);

      return $this->db->get('m_gedung')->result();
    }

    function getdatatotal($table, $intgedung=0,$from=null, $to=null){
      $this->db->select('sum(a.intpasang) as intpasang, sum(a.inttarget) as inttarget,
                        ISNULL(b.vcnama, 0) as vcmodel, ISNULL(c.vcnama, 0) as vckomponen,
                        d.intcell, d.inttarget as targetcell',false);
      $this->db->from('pr_output as a');
      $this->db->join('m_models as b', 'a.intmodel = b.intid', 'left');
      $this->db->join('m_komponen as c', 'a.intkomponen = c.intid', 'left');
      $this->db->join($table. ' as d', 'a.intmodel = d.intmodel', 'left');
      
      if ($from) {
        $this->db->where('a.dttanggal >= ', $from);
        $this->db->where('a.dttanggal <= ', $to);
        $this->db->where('d.dttanggal >= ', $from);
        $this->db->where('d.dttanggal <= ', $to);
      }

      if ($intgedung > 0) {
        $this->db->where('a.intgedung',$intgedung);
        $this->db->where('d.intgedung',$intgedung);  
      }
      $this->db->group_by('a.intgedung, a.intmodel, a.intkomponen, b.vcnama, c.vcnama, d.intcell, d.inttarget');
      $this->db->having('sum(a.intpasang) > 0');
      $this->db->order_by('a.intmodel','asc');
      return $this->db->get()->result();
    }

    function getdatatotalpershift($table, $intgedung=0,$from=null, $to=null, $intshift){
      $this->db->select('sum(a.intpasang) as intpasang, sum(a.inttarget) as inttarget,
                        ISNULL(b.vcnama, 0) as vcmodel,
                        ISNULL(c.vcnama, 0) as vckomponen',false);
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
      return $this->db->get()->result();
    }

    function insert_multiple($dataimport){
      $this->db->insert_batch('pr_cutting_output', $dataimport);
      return $this->db->insert_id();
    }

    function delete_multiple(){
      $this->db->query("delete tbl
                       from (
                         select  row_number() over (partition by intgedung, intmodel, intkomponen, vcartikel, vcpo 
                         order by intid desc) as rn
                         , *
                         from m_models_loadplan
                         ) tbl
                       where rn > 1
                         ");
     }

}