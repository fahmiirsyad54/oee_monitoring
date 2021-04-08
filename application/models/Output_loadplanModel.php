<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Output_loadplanModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $intgedung=0, $intmodel=0, $intkomponen=0, $vcpo=''){
        $this->db->select('count(e.intid) as jmldata',false);
        $this->db->from('pr_output as a');
        $this->db->join('m_models as b', 'a.intmodel = b.intid', 'left');
        $this->db->join('m_komponen as c', 'a.intkomponen = c.intid', 'left');
        $this->db->join('m_gedung as d', 'a.intgedung = d.intid', 'left');
        $this->db->join('m_models_loadplan as e', 'a.intmodel = e.intmodel AND a.vcpo = e.vcpo', 'left');

        if ($intgedung > 0) {
        $this->db->where('a.intgedung',$intgedung);
        }

        if ($intmodel > 0) {
          $this->db->where('a.intmodel',$intmodel);   
        }

        if ($intkomponen > 0) {
          $this->db->where('a.intkomponen',$intkomponen);   
        }

        if ($vcpo != "") {
          $this->db->where('a.vcpo',$vcpo);   
        }

        // $this->db->group_by('intid');
        // $this->db->having('e.intqty > 0');
        // $this->db->order_by('e.intid','desc');
        return $this->db->get()->result();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.*',false);
        $this->db->from($table . ' as a');
        $this->db->order_by('a.dttanggal','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intgedung=0, $intmodel=0, $intkomponen=0, $vcpo=''){
        $this->db->select('e.intid, sum(a.intpasang) as intpasang, ISNULL(b.vcnama, 0) as vcmodel, a.intkomponen, e.vcpo, 
                          ISNULL(c.vcnama, 0) as vckomponen, ISNULL(d.vcnama, 0) as vcgedung, e.intqty, e.dttanggal',false);
        $this->db->from('pr_output as a');
        $this->db->join('m_models as b', 'a.intmodel = b.intid', 'left');
        $this->db->join('m_komponen as c', 'a.intkomponen = c.intid', 'left');
        $this->db->join('m_gedung as d', 'a.intgedung = d.intid', 'left');
        $this->db->join('m_models_loadplan as e', 'a.intmodel = e.intmodel AND a.vcpo = e.vcpo', 'left');

        if ($intgedung > 0) {
        $this->db->where('a.intgedung',$intgedung);
        }
        if ($intmodel > 0) {
          $this->db->where('a.intmodel',$intmodel);   
        }

        if ($intkomponen > 0) {
          $this->db->where('a.intkomponen',$intkomponen);   
        }

        if ($vcpo != "") {
          $this->db->where('a.vcpo',$vcpo);   
        }

        $this->db->group_by('e.intid, e.dttanggal, a.intkomponen, e.vcpo, e.intqty, b.vcnama, c.vcnama, d.vcnama');
        $this->db->having('e.intqty > 0');
        $this->db->order_by('e.intid','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.*, c.intparent, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
       $this->db->from($table . ' as a');
       $this->db->join('m_pmroom as c', 'a.intpmroom = c.intid', 'left');
       $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
       $this->db->where('a.intid',$intid);
       return $this->db->get()->result();
    }

    public function buat_kode(){
      $this->db->select('RIGHT(m_room.vckode,4) as kode', FALSE);
      $this->db->order_by('intid','DESC');    
      $this->db->limit(1);    
      $query = $this->db->get('m_room');      //cek dulu apakah ada sudah ada kode di tabel.    
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
      $kodejadi = "RM".$kodemax;    // hasilnya ODJ-9921-0001 dst.
      return $kodejadi;  
    }

    public function buat_kode_cell(){
      $this->db->select('RIGHT(m_cell.vckode,4) as kode', FALSE);
      $this->db->order_by('intid','DESC');    
      $this->db->limit(1);    
      $query = $this->db->get('m_cell');      //cek dulu apakah ada sudah ada kode di tabel.    
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
      $kodejadi = "C".$kodemax;    // hasilnya ODJ-9921-0001 dst.
      return $kodejadi;  
    }

    function importdata($table,$data){
      $this->db->insert_batch($table,$data);
      return $this->db->insert_id();
    }

    function getlastdata($introom,$intpmroom){
      $table = 'm_pm';
      $this->db->where('introom',$introom);
      $this->db->where('intpmroom',$intpmroom);
      return $this->db->order_by('dttanggal',"desc")->limit(1)->get($table)->result();
    }

    function getdatamodel(){
      $this->db->select('a.*');
      return $this->db->get('m_models as a')->result();
    }

    function getdatakomponen(){
      $this->db->select('a.*');
      return $this->db->get('m_komponen as a')->result();
    }

    function getdataartikel(){
      $this->db->select('vcartikel');
      $this->db->group_by('vcartikel');

      return $this->db->get('m_models_loadplan')->result();
    }

    function getdatapo(){
      $this->db->select('vcpo');
      $this->db->group_by('vcpo');
      return $this->db->get('m_models_loadplan')->result();
    }

    function insert_multiple($dataimport){
    $this->db->insert_batch('m_models_loadplan', $dataimport);
    return $this->db->insert_id();
    }

    function getmodel($intgedung){
     $this->db->select('a.intmodel, b.vcnama as vcmodel',false);
     $this->db->from('pr_output as a');
     $this->db->join('m_models b','b.intid = a.intmodel','left');
     $this->db->where('a.intgedung',$intgedung);
     $this->db->where('a.intpasang > 0');
     $this->db->group_by('a.intmodel, b.vcnama');
     return $this->db->get()->result();
    }

    function getkomponen( $intmodel=0, $intgedung=0){
     $this->db->select('a.intkomponen, b.vcnama as vckomponen',false);
     $this->db->from('pr_output as a');
     $this->db->join('m_komponen b','b.intid = a.intkomponen','left');
     if ($intgedung > 0) {
        $this->db->where('a.intgedung',$intgedung);
      }
      if ($intmodel > 0) {
        $this->db->where('a.intmodel',$intmodel);   
      }

     $this->db->group_by('a.intkomponen, b.vcnama');
     return $this->db->get()->result();
    }

    function getpo( $intmodel=0, $intgedung=0){
      $this->db->select('vcpo',false);
      $this->db->from('pr_output');
      if ($intgedung > 0) {
         $this->db->where('intgedung',$intgedung);
       }
       if ($intmodel > 0) {
         $this->db->where('intmodel',$intmodel);   
       }
 
      $this->db->group_by('vcpo');
      return $this->db->get()->result();
     }    

    function delete_multiple(){
     $this->db->query("delete tbl
                      from (
                        select  row_number() over (partition by intmodel, vcpo 
                        order by intid desc) as rn
                        , *
                        from m_models_loadplan
                        ) tbl
                      where rn > 1
                        ");
    }
    
}