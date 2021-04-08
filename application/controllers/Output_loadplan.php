<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Output_loadplan extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Output_loadplanModel');
        $this->model = $this->Output_loadplanModel;
        $this->load->library('excel');
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intgedung   = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmodel    = ($this->input->get('intmodel') == '') ? 0 : $this->input->get('intmodel');
        $intkomponen = ($this->input->get('intkomponen') == '') ? 0 : $this->input->get('intkomponen');
        $vcpo        = ($this->input->get('vcpo') == '') ? '' : $this->input->get('vcpo');

        $jmldata              = $this->model->getjmldata($this->table,$intgedung, $intmodel, $intkomponen, $vcpo);
        $offset               = ($halaman - 1) * $this->limit;
        $jmlpage              = ceil($jmldata[0]->jmldata / $this->limit);
        
        $data['title']        = $this->title;
        $data['controller']   = $this->controller;
        $data['halaman']      = $halaman;
        $data['jmlpage']      = $jmlpage;
        $data['firstnum']     = $offset;
        $data['intgedung']    = $intgedung;
        $data['intmodel']     = $intmodel;
        $data['intkomponen']  = $intkomponen;
        $data['vcpo']         = $vcpo;
        $data['dataP']        = $this->model->getdatalimit($this->table,$offset,$this->limit,$intgedung, $intmodel, $intkomponen, $vcpo);
        $data['listgedung']   = $this->modelapp->getdatalistall('m_gedung');
        $data['listmodel']    = $this->model->getdatamodel();
        $data['listkomponen'] = $this->model->getdatakomponen();
        //$data['listartikel']  = $this->model->getdataartikel();
        $data['listpo']       = $this->model->getdatapo();

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add($intparent=0,$intpmroom=0){
        $data = array(
                    'intid'          => '',
                    'dttanggal'      => date('d-m-Y'),
                    'decactive_ed'   => '',
                    'decreactive_ed' => '',
                    'decapparent_ed' => '',
                    'introom'        => 0,
                    'intpmroom'      => 0,
                    'intadd'         => $this->session->intid,
                    'dtadd'          => date('Y-m-d H:i:s'),
                    'intupdate'      => $this->session->intid,
                    'dtupdate'       => date('Y-m-d H:i:s'),
                    'intstatus'      => 0
                );

        $parentlist         = $this->modelapp->getdatalist('m_pmroom',$intparent,'intparent');
        $data['listparent'] = $this->model->getdataparent();
        $data['namaroom']   = ($intparent == 0) ? 'All Trafo' : $this->modelapp->getdatadetail('m_pmroom',$intparent)[0]->vcnama;
        $data['intparent']  = $intparent;
        $data['listpmroom'] = (count($parentlist) > 0) ? $parentlist : [];
        $data['intpmroom']  = $intpmroom;
        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail($this->table,$intid);
        $intparent = ($resultData[0]->intparent == 0) ? $resultData[0]->intpmroom : $resultData[0]->intparent ; 
        $intpmroom = ($resultData[0]->intparent == 0) ? 0 : $resultData[0]->intpmroom ;
        $data = array(
                    'intid'          => $resultData[0]->intid,
                    'dttanggal'      => $resultData[0]->dttanggal,
                    'decactive_ed'   => $resultData[0]->decactive_ed,
                    'decreactive_ed' => $resultData[0]->decreactive_ed,
                    'decapparent_ed' => $resultData[0]->decapparent_ed,
                    'introom'        => $resultData[0]->introom,
                    'intparent'      => $intparent,
                    'intpmroom'      => $intpmroom,
                    'intupdate'      => $this->session->intid,
                    'dtupdate'       => date('Y-m-d H:i:s')
                );

        $parentlist = $this->modelapp->getdatalist('m_pmroom',$resultData[0]->intparent,'intparent');
        $data['listparent'] = $this->model->getdataparent();
        $data['namaroom']   = ($resultData[0]->intparent == 0) ? 'All Trafo' : $this->modelapp->getdatadetail('m_pmroom',$resultData[0]->intparent)[0]->vcnama;
        $data['listpmroom'] = (count($parentlist) > 0) ? $parentlist : [];
        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
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
             $dttanggal      = $this->input->post('dttanggal');
             $decactive_ed   = $this->input->post('decactive_ed');
             $decreactive_ed = $this->input->post('decreactive_ed');
             $decapparent_ed = $this->input->post('decapparent_ed');
             $introom        = $this->input->post('introom');
             $intparent      = $this->input->post('intparent');
             $intpmroom      = $this->input->post('intpmroom');
             $intpanel       = ($intpmroom == 0) ? $intparent : $intpmroom;
            $data    = array(
                    'dttanggal'      => date('Y-m-d H:i:s',strtotime($dttanggal)),
                    'decactive_ed'   => $decactive_ed,
                    'decreactive_ed' => $decreactive_ed,
                    'decapparent_ed' => $decapparent_ed,
                    'intpmroom'      => $intpanel,
                    'introom'        => $introom,
                    'intadd'         => $this->session->intid,
                    'dtadd'          => date('Y-m-d H:i:s'),
                    'intupdate'      => $this->session->intid,
                    'dtupdate'       => date('Y-m-d H:i:s'),
                    'intstatus'      => 1
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
             $dttanggal      = $this->input->post('dttanggal');
             $decactive_ed   = $this->input->post('decactive_ed');
             $decreactive_ed = $this->input->post('decreactive_ed');
             $decapparent_ed = $this->input->post('decapparent_ed');
             $introom        = $this->input->post('introom');
             $intparent      = $this->input->post('intparent');
             $intpmroom      = $this->input->post('intpmroom');
             $intpanel       = ($intpmroom == 0) ? $intparent : $intpmroom;
           
            $data    = array(
                    'dttanggal'      => date('Y-m-d H:i:s',strtotime($dttanggal)),
                    'decactive_ed'   => $decactive_ed,
                    'decreactive_ed' => $decreactive_ed,
                    'decapparent_ed' => $decapparent_ed,
                    'intpmroom'      => $intpanel,
                    'introom'        => $introom,
                    'intupdate'      => $this->session->intid,
                    'dtupdate'       => date('Y-m-d H:i:s')
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
                $intmodel    = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $vcpo        = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $intqty      = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                $datalog = array (
                    'dttanggal'   => $datenow,
                    'intmodel'    => $intmodel,
                    'vcpo'        => strtoupper($vcpo),
                    'intqty'      => $intqty
                );
                array_push($dataimport, $datalog);
            }
        }
        $result = $this->model->insert_multiple($dataimport);
        $this->model->delete_multiple();
        
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
    
    function getmodelajax($intgedung){
        $data = $this->model->getmodel($intgedung);
        echo json_encode($data);
    }

    function getkomponenajax($intmodel, $intgedung){
        $data['listkomponen'] = $this->model->getkomponen($intmodel, $intgedung);
        $data['listpo']       = $this->model->getpo($intmodel, $intgedung);
        echo json_encode($data);
    }

    

}
