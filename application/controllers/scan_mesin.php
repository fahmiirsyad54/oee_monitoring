<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class scan_mesin extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Scan_mesinModel');
        $this->model = $this->Scan_mesinModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->appname != 'tpm') {
            redirect(base_url('akses/loginsm'));
        } else {
            $this->datanotes     = $this->AppModel->getdatanotes();
            $this->jmlnotes      = $this->AppModel->getjmlnotes()[0]->jmldata;
            $this->notesin       = $this->AppModel->getnotesin()[0]->notesin;
        }
    }

    function index(){
        redirect(base_url($this->controller . '/scan'));
    }

    function detail_scan($vckode){
        $data['title']  = 'Detail Scan';
        $data['controller']  = 'scan_mesin';
        $data['dataMain']    = $this->model->getdatadetail2($vckode);
        //$data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$data['dataMain'][0]->intid);
        $this->template->set_layout('default')->build('scan_mesin_view/detail_scan',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdataedit($intid);
        $data = array(
                    'intid'        => $resultData[0]->intid,
                    'vckode'       => $resultData[0]->vckode,
                    'vcnama'       => $resultData[0]->vcnama,
                    'intbrand'     => $resultData[0]->intbrand,
                    'vcjenis'      => $resultData[0]->vcjenis,
                    'vcserial'     => $resultData[0]->vcserial,
                    'vcpower'      => $resultData[0]->vcpower,
                    'intgedung'    => $resultData[0]->intgedung,
                    'intcell'      => $resultData[0]->intcell,
                    'intdeparture' => $resultData[0]->intdeparture,
                    'intgroup'     => $resultData[0]->intgroup,
                    'vclocation'   => $resultData[0]->vclocation,
                    'vcgambar'     => $resultData[0]->vcgambar,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );

        $data['title']      = 'Data Machine';
        $data['action']     = 'Edit';
        $data['controller'] = 'scan_mesin';
        $data['listbrand']  = $this->modelapp->getdatalist('m_brand');
        $data['listarea']   = $this->modelapp->getdatalist('m_area');
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listgroup']  = $this->modelapp->getdatalist('m_mesin_group');
        $data['listcell']   = $this->modelapp->getdatalistall('m_cell', $resultData[0]->intgedung,'intgedung');

        $this->template->set_layout('default')->build('scan_mesin_view/form_edit',$data);
    }

    function get_cell_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_cell',$intid,'intgedung');

        echo json_encode($data);
    }

    function getkode($intgroup, $action, $vckode=''){
        $datagroup  = $this->modelapp->getdatadetailcustom('m_mesin_group',$intgroup,'intid');
        $vckodelast = $this->model->getlastkode()[0]->vckode;
        $kodetemp   = ($action == 'Edit') ? substr($vckode, 4) : substr($vckodelast, 4) + 1;
        $kodetemp2  = str_pad($kodetemp, 6, 0, STR_PAD_LEFT);
        echo $datagroup[0]->vckode . $kodetemp2;
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Edit') {
            $vckode       = $this->input->post('vckode');
            $vcnama       = $this->input->post('vcnama');
            $intbrand     = $this->input->post('intbrand');
            $vcjenis      = $this->input->post('vcjenis');
            $vcserial     = $this->input->post('vcserial');
            $vcpower      = $this->input->post('vcpower');
            $intgedung    = $this->input->post('intgedung');
            $intcell      = $this->input->post('intcell');
            $intdeparture = $this->input->post('intdeparture');
            $intgroup     = $this->input->post('intgroup');
            $vclocation   = $this->input->post('vclocation');

            if (!empty($_FILES["vcgambar"]["name"])) {
                $vcgambar = $this->model->_uploadImage($vcjenis);
            } else {
                $vcgambar = $this->input->post('oldfile');
            }

            $data    = array(
                    'vckode'       => $vckode,
                    'vcnama'       => $vcnama,
                    'intbrand'     => $intbrand,
                    'vcjenis'      => $vcjenis,
                    'vcserial'     => $vcserial,
                    'vcpower'      => $vcpower,
                    'intgedung'    => $intgedung,
                    'intcell'      => $intcell,
                    'intdeparture' => $intdeparture,
                    'intgroup'     => $intgroup,
                    'vclocation'   => $vclocation,
                    'vcgambar'     => $vcgambar,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata('m_mesin',$data,$intid);
            if ($result) {
                redirect(base_url('scan_mesin/after_save'));
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
                    $array[]['error'] = 'Kolom ' . $error . ' tidak boleh kosong !';
                }
            }
        }
        echo json_encode($array);
    }

    function after_save(){
        $data['title']      = 'Machine Scan';
        $data['action']     = 'Edit';
        $data['controller'] = 'scan_mesin';
        $this->template->set_layout('default')->build('scan_mesin_view/after_save',$data);
    }

    

}
