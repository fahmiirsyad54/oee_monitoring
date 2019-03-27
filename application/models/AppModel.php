<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppModel extends CI_Model {

    public function __construct(){
            // Call the CI_Model constructor
            parent::__construct();
    }

    // function default

    function getdatamenu($kode){
        $this->db->where('vccontroller', $kode);
        return $this->db->get('app_mmenu')->result();
    }

    function hakaksesmenu($inthakakses,$controller){
        $this->db->select();
        $this->db->from('app_mhakakses_menu as a');
        $this->db->join('app_mmenu as b', 'b.intid = a.intmenu');
        $this->db->where(array('b.intstatus' => 1, 'a.intheader' => $inthakakses, 'b.vccontroller' => $controller));
        return $this->db->get()->result();
    }

    function getdatalist($table, $keyword='',$parameter=''){
        $this->db->select('intid, ifnull(vcnama,"") as vcnama',false);
        if ($keyword != '' && $parameter != '') {
            $this->db->where($parameter, $keyword);
        }
        return $this->db->get($table)->result();
    }

    function getdatalistall($table, $keyword='',$parameter=''){
        if ($keyword != '' && $parameter != '') {
            $this->db->where($parameter, $keyword);
        }
        return $this->db->get($table)->result();
    }

    function getjmldata($table, $keyword=''){
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from($table . ' as a');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        return $this->db->get()->result();
    }

    function getdata($table, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        return $this->db->get()->result();
    }
    
    function getdatalimit($table,$halaman=0, $limit=5, $keyword=''){
        $this->db->select('a.intid, a.vckode, a.vcnama, a.intstatus, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->like('a.vcnama', $keyword);
        $this->db->or_like('a.vckode', $keyword);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit, $halaman);
        return $this->db->get()->result();
    }

    function getdatadetail($table,$intid){
        $this->db->select('a.*, IFNULL(b.vcnama, "Tidak Ada Status") as vcstatus, IFNULL(b.vcwarna, "") as vcstatuswarna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_mstatus' . ' as b', 'a.intstatus = b.intstatus', 'left');
        $this->db->where('a.intid',$intid);
        return $this->db->get()->result();
    }

    function getdatadetailcustom($table,$keyword="",$parameter=""){
        if ($keyword != '' && $parameter != '') {
            $this->db->where($parameter, $keyword);
        }
        return $this->db->get($table)->result();
    }

    function getdatahistory($table,$intid,$limit=10){
        $this->db->select('a.dtupdate, b.vcnama as user, c.vcnama as aksi, c.vcwarna as warna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_muser as b', 'a.intupdate = b.intid');
        $this->db->join('app_maction as c', 'a.intaction = c.intid');
        $this->db->where('a.intheader', $intid);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    function getdatahistory2($table,$intid,$limit=10){
        $this->db->select('a.dtupdate, b.vcnama as user, c.vcnama as aksi, c.vcwarna as warna',false);
        $this->db->from($table . ' as a');
        $this->db->join('app_muser as b', 'a.intuser = b.intid');
        $this->db->join('app_maction as c', 'a.intaction = c.intid');
        $this->db->where('a.intheader', $intid);
        $this->db->order_by('a.dtupdate','desc');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    function getappsetting($vcnama){
        $this->db->select('a.vcnama, a.vcvalue');
        $this->db->from('app_setting as a');
        $this->db->where('a.vcnama',$vcnama);
        $this->db->where('a.intstatus',1);
        return $this->db->get()->result();
    }

    function getcountdata($table,$keyword='',$parameter=''){
        if ($keyword != '' && $parameter != '') {
            $this->db->where($parameter, $keyword);
        }
        return $this->db->count_all_results($table);
    }

    function insertdata($table,$data){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    function updatedata($table,$data,$intid){
        $this->db->where('intid',$intid);
        return $this->db->update($table,$data);
    }

    function deletedata($table,$keyword,$parameter){
        $this->db->where($parameter,$keyword);
        $this->db->delete($table);
    }

    function datacheck($table,$wherevalue){
        $this->db->where($wherevalue);
        return $this->db->get($table)->num_rows();
    }

    function getjmlnotes(){
        $datenow = date('Y-m-d');
        $this->db->select('count(a.intid) as jmldata',false);
        $this->db->from('pr_pesan as a');
        $this->db->where('date(a.dttanggal) = "' . $datenow . '"');

        return $this->db->get()->result();
    }

    function getnotesin(){
        $datenow = date('Y-m-d');
        $this->db->select('count(a.intid) as notesin',false);
        $this->db->from('pr_pesan as a');
        $this->db->where('date(a.dttanggal) = "' . $datenow . '"');
        $this->db->where('a.intstatus = 0');

        return $this->db->get()->result();
    }
 
    function getdatanotes(){
        $datenow = date('Y-m-d');
        $this->db->select('a.*, IFNULL(b.vckode, "") as vcmesin',false);
        $this->db->from('pr_pesan as a');
        $this->db->join('m_mesin as b', 'a.intmesin = b.intid', 'left');
        $this->db->where('date(a.dttanggal) = "' . $datenow . '"');
        $this->db->order_by('a.intstatus','asc');
        $this->db->limit(20);
        
        return $this->db->get()->result();
    }

}