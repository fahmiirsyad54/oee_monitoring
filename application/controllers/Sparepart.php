<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('SparepartModel');
        $this->model = $this->SparepartModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword = $this->input->get('key');

        $jmldata            = $this->modelapp->getjmldata($this->table,$keyword);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword);

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $data = array(
                    'intid'         => 0,
                    'vcnama'        => '',
                    'vcspesifikasi' => '',
                    'vcpart'        => '',
                    'intjenis'      => 0,
                    'intunit'       => 0,
                    'intadd'        => $this->session->intid,
                    'dtadd'         => date('Y-m-d H:i:s'),
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s'),
                    'intstatus'     => 0
                );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listunit']   = $this->modelapp->getdatalist('m_unit');
        $data['listjenis']  = $this->modelapp->getdatalist('m_jenis_sparepart');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'         => $resultData[0]->intid,
                    'vcnama'        => $resultData[0]->vcnama,
                    'vcspesifikasi' => $resultData[0]->vcspesifikasi,
                    'vcpart'        => $resultData[0]->vcpart,
                    'intjenis'      => $resultData[0]->intjenis,
                    'intunit'       => $resultData[0]->intunit,
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s')
                );

        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
        $data['listunit']   = $this->modelapp->getdatalist('m_unit');
        $data['listjenis']  = $this->modelapp->getdatalist('m_jenis_sparepart');
        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function validasiform($tipe){
        $array = array();
        $data = $this->input->post();
        if ($tipe == 'data') {
            foreach ($data as $key => $value) {
                $result = $this->modelapp->getdatadetailcustom($this->table,$value,$key);
                if (count($result) > 0 && $value != '') {
                    $front = substr($key,0,2);
                    $end   = substr($key,2);
                    $end2  = substr($key,3);
                    $error = ($front == 'vc') ? $end : $end2 ;
                    $array[]['error'] = $error . ' Sudah ada !';
                }
            }
        } elseif ($tipe == 'required') {
            foreach ($data as $key => $value) {
                if ($value == '') {
                    $front = substr($key,0,2);
                    $end   = substr($key,2);
                    $end2  = substr($key,3);
                    $error = ($front == 'vc') ? $end : $end2 ;
                    $array[]['error'] = 'Column ' . $error . ' can not be empty !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe      == 'Add') {
            $jenis         = ($this->input->post('intjenis') == 1) ? 'CM' : 'YM' ;
            $kodeunik      = $this->model->buat_kode($jenis);
            $vckode        = $kodeunik;
            $vcnama        = $this->input->post('vcnama');
            $vcspesifikasi = $this->input->post('vcspesifikasi');
            $vcpart        = $this->input->post('vcpart');
            $intunit       = $this->input->post('intunit');
            $intjenis      = $this->input->post('intjenis');
            $data    = array(
                    'vckode'        => $vckode,
                    'vcnama'        => $vcnama,
                    'vcspesifikasi' => $vcspesifikasi,
                    'vcpart'        => $vcpart,
                    'intunit'       => $intunit,
                    'intjenis'      => $intjenis,
                    'intadd'        => $this->session->intid,
                    'dtadd'         => date('Y-m-d H:i:s'),
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s'),
                    'intstatus'     => 1
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
            
        } elseif ($tipe == 'Edit') {
             $jenis         = ($this->input->post('intjenis') == 1) ? 'CM' : 'YM' ;
             $kodeunik      = $this->model->buat_kode($jenis);
             $vckode        = $kodeunik;
             $vcnama        = $this->input->post('vcnama');
             $vcspesifikasi = $this->input->post('vcspesifikasi');
             $vcpart        = $this->input->post('vcpart');
             $intunit       = $this->input->post('intunit');
             $intjenis      = $this->input->post('intjenis');
            $data    = array(
                    'vckode'        => $vckode,
                    'vcnama'        => $vcnama,
                    'vcspesifikasi' => $vcspesifikasi,
                    'vcpart'        => $vcpart,
                    'intunit'       => $intunit,
                    'intjenis'      => $intjenis,
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Hapus') {
            # code...
        } elseif ($tipe == 'ubahstatus') {
            $intstatus = 0;
            if ($status == 1) {
                $intstatus = 0;
            } elseif ($status == 0) {
                $intstatus = 1;
            }
            $data = array(
                'intstatus' => $intstatus,
                'intupdate' => $this->session->intid,
                'dtupdate'  => date('Y-m-d H:i:s')
            );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        }
    }

    function getmahasiswaajax($intid){
        $data = $this->modelapp->getdatadetailcustom('mmahasiswa',$intid,'intid');

        echo json_encode($data);
    }

}
