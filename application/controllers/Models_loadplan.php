<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Models_loadplan extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Models_loadplanModel');
        $this->model = $this->Models_loadplanModel;
        $this->load->library('excel');
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        //$this->model->deletesdd(date('Y-m-d'));

        $intmodel    = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $vcpo        = ($this->input->get('vcpo') == '') ? '' : $this->input->get('vcpo');

        $jmldata              = $this->model->getjmldata($this->table, $intmodel, $vcpo);
        $offset               = ($halaman - 1) * $this->limit;
        $jmlpage              = ceil($jmldata[0]->jmldata / $this->limit);
        
        
        $data['title']        = $this->title;
        $data['controller']   = $this->controller;
        $data['halaman']      = $halaman;
        $data['jmlpage']      = $jmlpage;
        $data['firstnum']     = $offset;
        $data['intmodel']     = $intmodel;
        $data['vcpo']         = $vcpo;
        $data['dataP']        = $this->model->getdatalimit($this->table,$offset,$this->limit, $intmodel, $vcpo);
        $data['listmodel']    = $this->model->getdatamodel();
        $data['listpo']       = $this->model->getdatapo();

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
                    'intid'          => '',
                    'intmodel'       => 0,
                    'vcpo'           => '',
                    'sdd'            => date('m-d-Y'),
                    'intqty'         => 0,
                    'intqtyadd'      => ''
                );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listmodels'] = $this->modelapp->getdatalistall('m_models');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'     => $resultData[0]->intid,
                    'intmodel'  => $resultData[0]->intmodel,
                    'vcpo'      => $resultData[0]->vcpo,
                    'sdd'       => date('m/d/Y', strtotime($resultData[0]->sdd)),
                    'intqty'    => $resultData[0]->intqty,
                    'intqtyadd' => $resultData[0]->intqtyadd
                );
        
        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
        $data['listmodels'] = $this->modelapp->getdatalistall('m_models');

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

    function aksi($tipe,$intid=0,$status=0){
        if ($tipe == 'Add') {
             $intmodel  = $this->input->post('intmodel');
             $vcpo      = $this->input->post('vcpo');
             $sdd       = $this->input->post('sdd');
             $intqty    = $this->input->post('intqty');
             $intqtyadd = $this->input->post('intqtyadd');
            $data    = array(
                    'dttanggal' => date('Y-m-d H:i:s'),
                    'intmodel'  => $intmodel,
                    'vcpo'      => $vcpo,
                    'sdd'       => date('Y-m-d',strtotime($sdd)),
                    'intqty'    => $intqty,
                    'intqtyadd' => $intqtyadd
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
            $intmodel  = $this->input->post('intmodel');
            $vcpo      = $this->input->post('vcpo');
            $sdd       = $this->input->post('sdd');
            $intqty    = $this->input->post('intqty');
            $intqtyadd = $this->input->post('intqtyadd');
            $data    = array(
                'dttanggal' => date('Y-m-d H:i:s'),
                'intmodel'  => $intmodel,
                'vcpo'      => $vcpo,
                'sdd'       => date('Y-m-d',strtotime($sdd)),
                'intqty'    => $intqty,
                'intqtyadd' => $intqtyadd
            );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Hapus') {
            $result = $this->modelapp->deletedata($this->table, $intid, 'intid');
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
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

     function importdata(){
        $path        = $_FILES["dataimport"]["tmp_name"];
        // $intmodel    = $this->input->post('intmodel');
        // $intkomponen = $this->input->post('intkomponen');
        $datenow     = date('Y-m-d H:i:s');
        $object      = PHPExcel_IOFactory::load($path);
        $dataimport  = array();

        foreach($object->getWorksheetIterator() as $worksheet){
            $highestRow    = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            
            for($row=2; $row<=$highestRow; $row++){
                $intmodel = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $vcpo     = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $sdd      = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $intqty   = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

                $datalog = array (
                    'dttanggal' => $datenow,
                    'intmodel'  => $intmodel,
                    'vcpo'      => strtoupper($vcpo),
                    'sdd'       => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($sdd)),
                    // 'sdd'       => date('Y-m-d'),
                    'intqty'    => $intqty
                );
                array_push($dataimport, $datalog);
            }
        }
        $this->model->delete_all();
        $result = $this->model->insert_multiple($dataimport);
        //$this->model->delete_multiple();
        //$this->model->deletesdd(date('Y-m-d'));
        if ($result) {
            echo "<script>
                    alert('Import Data Success');
                    window.location.href='" . base_url('models_loadplan/view') . "';
                    </script>";
        } else {
            echo "<script>
                    alert('Import Data Failed');
                    window.location.href='" . base_url('models_loadplan/view') . "';
                    </script>";
        }
    }

    function getpoajax($intmodel){
        $data = $this->model->getpo($intmodel);
        echo json_encode($data);
    }

}
