<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_operator extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Login_operatorModel');
        $this->model = $this->Login_operatorModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword   = $this->input->get('key');
        $intshift  = $this->input->get('intshift');
        $intgedung = $this->input->get('intgedung');
        $intlogin  = $this->input->get('intlogin');
        $log   = array(
                    '1' => 'Login',
                    '2' => 'Logout'
                );

        $jmldata            = $this->model->getjmldata($this->table,$keyword, $intgedung, $intshift, $intlogin);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['intgedung']  = $intgedung;
        $data['intshift']   = $intshift;
        $data['intlogin']   = $intlogin;
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listlog']    = $log;
        $data['listshift']  = $this->modelapp->getdatalist('m_shift');
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword, $intgedung, $intshift, $intlogin);
        
        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $data = array(
                    'intid'       => 0,
                    'intuser'     => 0,
                    'intkaryawan' => 0,
                    'intshift'    => 0,
                    'intlogin'    => 0,
                    'dtlogin'     => date('m/d/Y H:i:s')
                   
                );

         $data['title']        = $this->title;
         $data['action']       = 'Add';
         $data['controller']   = $this->controller;
         $data['listuser']     = $this->model->getuser('app_muser');
         $data['listkaryawan'] = $this->model->getkaryawan('m_karyawan');
         $data['listshift']     = $this->modelapp->getdatalist('m_shift');
         $data['listlog']      = array('1' => 'Login', '2' => 'Logout');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'       => $resultData[0]->intid,
                    'intuser'     => $resultData[0]->intuser,
                    'intkaryawan' => $resultData[0]->intkaryawan,
                    'intshift'    => $resultData[0]->intshift,
                    'intlogin'    => $resultData[0]->intlogin,
                    'dtlogin'     => date('m/d/Y H:i:s', strtotime($resultData[0]->dtlogin))
                );

         $data['title']        = $this->title;
         $data['action']       = 'Edit';
         $data['controller']   = $this->controller;
         $data['listuser']     = $this->model->getuser('app_muser');
         $data['listkaryawan'] = $this->model->getkaryawan('m_karyawan');
         $data['listshift']    = $this->modelapp->getdatalist('m_shift');
         $data['listlog']      = array('1' => 'Login', '2' => 'Logout');

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
        if ($tipe == 'Add') {
            $intuser     = $this->input->post('intuser');
            $intkaryawan = $this->input->post('intkaryawan');
            $intshift    = $this->input->post('intshift');
            $intlogin    = $this->input->post('intlogin');
            $dtlogin     = $this->input->post('dtlogin');
            $data    = array(
                    'intuser'     => $intuser,
                    'intkaryawan' => $intkaryawan,
                    'intshift'    => $intshift,
                    'intlogin'    => $intlogin,
                    'dtlogin'     => date('Y-m-d H:i:s',strtotime($dtlogin)),
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
             $intuser     = $this->input->post('intuser');
             $intkaryawan = $this->input->post('intkaryawan');
             $intshift    = $this->input->post('intshift');
             $intlogin    = $this->input->post('intlogin');
             $dtlogin     = $this->input->post('dtlogin');
           
            $data    = array(
                     'intuser'     => $intuser,
                     'intkaryawan' => $intkaryawan,
                     'intshift'    => $intshift,
                     'intlogin'    => $intlogin,
                     'dtlogin'     => date('Y-m-d H:i:s',strtotime($dtlogin)),
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

}
