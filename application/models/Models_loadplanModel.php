<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Models_loadplanModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    function getjmldata($table, $intmodel=0, $vcpo=''){
      $this->db->select('count(a.intid) as jmldata',false);
      $this->db->from($table . ' as a');
      $this->db->join('m_models' . ' as b', 'a.intmodel = b.intid', 'left');
      if ($intmodel > 0) {
          $this->db->where('a.intmodel',$intmodel);
      }
      if ($vcpo > 0) {
          $this->db->where('a.vcpo',$vcpo);
      }

      return $this->db->get()->result();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.*',false);
        $this->db->from($table . ' as a');
        $this->db->order_by('a.dttanggal','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $intmodel=0, $vcpo=''){
        $this->db->select('a.intid, ISNULL(b.vcnama, 0) as vcmodel,a.vcpo, a.intqty, a.dttanggal, a.sdd',false);
        $this->db->from($table . ' as a');
        $this->db->join('m_models as b', 'a.intmodel = b.intid', 'left');

        if ($intmodel > 0) {
          $this->db->where('a.intmodel',$intmodel);   
        }

        if ($vcpo > 0) {
          $this->db->where('a.vcpo',$vcpo);   
        }
        $this->db->order_by('a.intid','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
       $this->db->select('a.intid, a.intmodel, a.vcpo, a.sdd, a.intqty, a.intqtyadd',false);
       $this->db->from($table . ' as a');
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
      $this->db->select('intid, vcnama');
      return $this->db->get('m_models')->result();
    }

    function getdatapo(){
      $this->db->select('intid, vcpo');
      $this->db->group_by('vcpo, intid');
      return $this->db->get('m_models_loadplan')->result();
    }

    function insert_multiple($dataimport){
      $this->db->insert_batch('m_models_loadplan', $dataimport);
      return $this->db->insert_id();
    }

    function getpo($intmodel=0){
     $this->db->select('a.vcpo',false);
     $this->db->from('m_models_loadplan as a');
     
      if ($intmodel > 0) {
        $this->db->where('a.intmodel',$intmodel);   
      }

     $this->db->group_by('a.vcpo');
     return $this->db->get()->result();
    }

    function delete_multiple(){
     $this->db->query("delete tbl
                      from (
                        select  row_number() over (partition by intmodel, vcpo, sdd, intqty 
                        order by intid desc) as rn
                        , *
                        from m_models_loadplan
                        ) tbl
                      where rn > 1
                        ");
    }

    function deletesdd($date){
      $this->db->where("sdd < '$date'");
      $this->db->delete('m_models_loadplan');
    }

    function delete_all(){
      $this->db->empty_table('m_models_loadplan');
    }
    
}