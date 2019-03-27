<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downtime_list extends MY_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('Downtime_listModel');
        $this->model = $this->Downtime_listModel;
    }

    function index(){
    	redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword = $this->input->get('key');

        $jmldata            = $this->model->getjmldata($this->table,$keyword);
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
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $data = array(
                    'intid'          => 0,
                    'vcnama'         => '',
                    'inttype'        => 0,
                    'intautocutting' => 0,
                    'intplanned'     => 0
                );
        $autocutting = array(
                        '0' => 'Non Autocutting',
                        '1' => 'Autocutting'
                        );
        $planned = array(
                        '0' => 'Non Planned',
                        '1' => 'Planned'
                    );

        $data['title']           = $this->title;
        $data['action']          = 'Add';
        $data['controller']      = $this->controller;
        $data['listtype']        = $this->modelapp->getdatalist('m_type_downtime');
        $data['listmachine'] = $autocutting;
        $data['listplanned']     = $planned;

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail($this->table,$intid);
        $autocutting = array(
                        '0' => 'Non Autocutting',
                        '1' => 'Autocutting'
                        );
        $planned = array(
                        '0' => 'Non Planned',
                        '1' => 'Planned'
                    );
        $data = array(
                    'intid'          => $resultData[0]->intid,
                    'vcnama'         => $resultData[0]->vcnama,
                    'inttype'        => $resultData[0]->intheader,
                    'intautocutting' => $resultData[0]->intautocutting,
                    'intplanned'     => $resultData[0]->intplanned
                );

        $data['title']       = $this->title;
        $data['action']      = 'Edit';
        $data['controller']  = $this->controller;
        $data['listtype']    = $this->modelapp->getdatalist('m_type_downtime');
        $data['listmachine'] = $autocutting;
        $data['listplanned'] = $planned;

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
                    $array[]['error'] = 'Kolom ' . $error . ' tidak boleh kosong !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Add') {
            $vcnama         = $this->input->post('vcnama');
            $intheader      = $this->input->post('inttype');
            $intautocutting = $this->input->post('intautocutting');
            $intplanned     = $this->input->post('intplanned');
            $data    = array(
                    'vcnama'         => $vcnama,
                    'intheader'      => $intheader,
                    'intautocutting' => $intautocutting,
                    'intplanned'     => $intplanned
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
            $vcnama         = $this->input->post('vcnama');
            $intheader      = $this->input->post('inttype');
            $intautocutting = $this->input->post('intautocutting');
            $intplanned     = $this->input->post('intplanned');
            $data    = array(
                    'vcnama'         => $vcnama,
                    'intheader'      => $intheader,
                    'intautocutting' => $intautocutting,
                    'intplanned'     => $intplanned
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

    function ceknamakaryawan(){
        $vcnama     = $this->input->post('vcnama');
        $intjabatan = $this->input->post('intjabatan');
        $data       = $this->model->ceknamakaryawan($vcnama, $intjabatan);

        echo json_encode($data);
    }

}
