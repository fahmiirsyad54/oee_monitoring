<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downtime_non_autocutting extends MY_Controller { 

    function __construct(){
        parent::__construct();
        $this->load->model('DowntimeModel');
        $this->model = $this->DowntimeModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword = $this->input->get('key');
        $from    = date('Y-m-d',strtotime($this->input->get('from')));
        $to      = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));

        $jmldata            = $this->model->getjmldata($this->table,$from,$to);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['from']       = $from;
        $data['to']         = $to;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword,$from,$to);
        $data['hideaction'] = $this->hideaction;

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataDowntime'] = $this->model->getdowntime($intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $kodeunik   = $this->DowntimeModel->buat_kode();
        $data = array(
                    'intid'              => 0,
                    'vckode'             => '',
                    'dttanggal'          => date('d-m-Y'),
                    'intgedung'          => 0,
                    'intcell'            => 0,
                    'intmesin'           => 0,
                    'intoperator'        => '',
                    'intleader'          => '',
                    'intdtmesin_type'    => 0,
                    'inttype_downtime'   => '',
                    'inttype_list'       => '',
                    'dtstop'             => '',
                    'dtstart'            => '',
                    'dtfinish'           => '',
                    'dtrun'              => '',
                    'dtmaterialkosong'   => '',
                    'dtmaterialtersedia' => '',
                    'vcmasalah'          => '',
                    'vcsolusi'           => '',
                    'intmekanik'         => '',
                    'intsparepart'       => '',
                    'intjumlahsparepart' => '',
                    'intadd'             => $this->session->intid,
                    'dtadd'              => date('Y-m-d H:i:s'),
                    'intupdate'          => $this->session->intid,
                    'dtupdate'           => date('Y-m-d H:i:s'),
                    'intstatus'          => 0
                ); 

        $data['title']            = $this->title;
        $data['action']           = 'Add';
        $data['controller']       = $this->controller;
        $data['dataDowntime']     = [];
        $data['listgedung']       = $this->modelapp->getdatalist('m_gedung');
        $data['listshift']        = $this->modelapp->getdatalist('m_shift');
        $data['listsparepart']    = $this->modelapp->getdatalist('m_sparepart');
        $data['listdtmesin_type'] = $this->modelapp->getdatalist('m_dtmesin_type');
        $data['listtype']         = $this->modelapp->getdatalist('m_type_downtime');
        $data['listtypelist']     = [];
        $data['listcell']         = [];
        $data['listmesin']        = [];
        $data['listoperator']     = $this->model->getkaryawan('m_karyawan',3);
        $data['listleader']       = $this->model->getkaryawan('m_karyawan',1);
        $data['listmekanik']      = $this->model->getkaryawan('m_karyawan',2);


        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData   = $this->model->getdatadetail($this->table,$intid);
        $dataDowntime = $this->model->getdowntime($intid);
        $downtimetype = [];
        $listdowntime = [];

        foreach ($dataDowntime as $dt) {
            array_push($downtimetype, $this->modelapp->getdatalist('m_type_downtime'));
            array_push($listdowntime, $this->modelapp->getdatalistall('m_type_downtime_list',$dt->inttype_downtime,'intheader'));
        }

        $data = array(
                    'intid'              => $resultData[0]->intid,
                    'vckode'             => $resultData[0]->vckode,
                    'dttanggal'          => date('m/d/Y', strtotime($resultData[0]->dttanggal)),
                    'intgedung'          => $resultData[0]->intgedung,
                    'intcell'            => $resultData[0]->intcell,
                    'intmesin'           => $resultData[0]->intmesin,
                    'intoperator'        => $resultData[0]->intoperator,
                    'intleader'          => $resultData[0]->intleader,
                    'intdtmesin_type'    => $resultData[0]->intdtmesin_type,
                    'inttype_downtime'   => $resultData[0]->inttype_downtime,
                    'inttype_list'       => $resultData[0]->inttype_list,
                    'dtstop'             => $resultData[0]->dtstop,
                    'dtstart'            => $resultData[0]->dtstart,
                    'dtfinish'           => $resultData[0]->dtfinish,
                    'dtrun'              => $resultData[0]->dtrun,
                    'dtmaterialkosong'   => $resultData[0]->dtmaterialkosong,
                    'dtmaterialtersedia' => $resultData[0]->dtmaterialtersedia,
                    'vcmasalah'          => $resultData[0]->vcmasalah,
                    'vcsolusi'           => $resultData[0]->vcsolusi,
                    'intmekanik'         => $resultData[0]->intmekanik,
                    'intsparepart'       => $resultData[0]->intsparepart,
                    'intjumlahsparepart' => $resultData[0]->intjumlahsparepart,
                    'intupdate'          => $this->session->intid,
                    'dtupdate'           => date('Y-m-d H:i:s')
                );

        $data['title']            = $this->title;
        $data['action']           = 'Edit';
        $data['controller']       = $this->controller;
        $data['dataDowntime']     = $dataDowntime;
        $data['listtype']         = $this->modelapp->getdatalist('m_type_downtime');
        $data['listtypelist']     = $this->modelapp->getdatalistall('m_type_downtime_list',$resultData[0]->inttype_downtime,'intheader');
        $data['listgedung']       = $this->modelapp->getdatalist('m_gedung');
        $data['listjenis']        = $this->modelapp->getdatalist('m_jenis');
        $data['listsparepart']    = $this->modelapp->getdatalist('m_sparepart');
        $data['listdtmesin_type'] = $this->modelapp->getdatalist('m_dtmesin_type');
        $data['listshift']        = $this->modelapp->getdatalist('m_shift');
        $data['listcell']         = $this->modelapp->getdatalistall('m_cell', $resultData[0]->intgedung,'intgedung');
        $data['listmesin']        = $this->model->getdatamesin('m_mesin');
        $data['listoperator']     = $this->model->getdatakaryawan('m_karyawan', $resultData[0]->intgedung,3);
        $data['listleader']       = $this->model->getdatakaryawan('m_karyawan', $resultData[0]->intgedung,1);
        $data['listmekanik']      = $this->model->getdatakaryawan('m_karyawan', $resultData[0]->intgedung,2);

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
                    $error = ($front == 'vc' || $front == 'dt') ? $end : $end2 ;
                    $array[]['error'] = $error . ' Sudah ada !';
                }
            }
        } elseif ($tipe == 'required') {
            foreach ($data as $key => $value) {
                $front = substr($key,0,2);
                if ($front == 'vc' || $front == 'dt') {
                    if ($value == '') {
                        $front = substr($key,0,2);
                        $end   = substr($key,2);
                        $end2  = substr($key,3);
                        $error = ($front == 'vc' || $front == 'dt') ? $end : $end2 ;
                        $array[]['error'] = 'Column ' . $error . ' can not be empty !';
                    }
                } else {
                    if ($value == 0) {
                        $front = substr($key,0,2);
                        $end   = substr($key,2);
                        $end2  = substr($key,3);
                        $error = ($front == 'vc') ? $end : $end2 ;
                        $array[]['error'] = 'Column ' . $error . ' can not be empty !';
                    }
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe      == 'Add') {
            $kodeunik           = $this->DowntimeModel->buat_kode();
            $vckode             = $kodeunik;
            $dttanggal          = $this->input->post('dttanggal');
            $intgedung          = $this->input->post('intgedung');
            $intcell            = $this->input->post('intcell');
            $intmesin           = $this->input->post('intmesin');
            $intoperator        = $this->input->post('intoperator');
            $intleader          = $this->input->post('intleader');
            $intdtmesin_type    = $this->input->post('intdtmesin_type');
            $inttype_downtime   = $this->input->post('inttype_downtime');
            $inttype_list       = $this->input->post('inttype_list');
            $dtstop             = $this->input->post('dtstop');
            $dtstart            = $this->input->post('dtstart');
            $dtfinish           = $this->input->post('dtfinish');
            $dtrun              = $this->input->post('dtrun');
            $dtmaterialkosong   = $this->input->post('dtmaterialkosong');
            $dtmaterialtersedia = $this->input->post('dtmaterialtersedia');
            $vcmasalah          = $this->input->post('vcmasalah');
            $vcsolusi           = $this->input->post('vcsolusi');
            $intmekanik         = $this->input->post('intmekanik');
            $intsparepart       = $this->input->post('intsparepart');
            $intjumlahsparepart = $this->input->post('intjumlahsparepart');

            $inttunggumekanik  = ($dtstart == '') ? 0 : (strtotime($dtstart) - strtotime($dtstop))/60;
            $intperbaikan      = ($dtstart == '') ? 0 : (strtotime($dtfinish) - strtotime($dtstart))/60;
            $inttungguoperator = ($dtstart == '') ? 0 : (strtotime($dtrun) - strtotime($dtfinish))/60;
            $inttunggumaterial = ($dtmaterialtersedia == '') ? 0 : (strtotime($dtmaterialtersedia) - strtotime($dtmaterialkosong))/60;

            $data         = array(
                        'vckode'             => $vckode,
                        'dttanggal'          => date('Y-m-d',strtotime($dttanggal)),
                        'intgedung'          => $intgedung,
                        'intcell'            => $intcell,
                        'intmesin'           => $intmesin,
                        'intoperator'        => $intoperator,
                        'intleader'          => $intleader,
                        'intdtmesin_type'    => $intdtmesin_type,
                        'inttype_downtime'   => $inttype_downtime,
                        'inttype_list'       => $inttype_list,
                        'dtstop'             => $dtstop,
                        'dtstart'            => $dtstart,
                        'dtfinish'           => $dtfinish,
                        'dtrun'              => $dtrun,
                        'dtmaterialkosong'   => $dtmaterialkosong,
                        'dtmaterialtersedia' => $dtmaterialtersedia,
                        'vcmasalah'          => $vcmasalah,
                        'vcsolusi'           => $vcsolusi,
                        'intmekanik'         => $intmekanik,
                        'intsparepart'       => $intsparepart,
                        'intjumlahsparepart' => $intjumlahsparepart,
                        'inttunggumekanik'   => $inttunggumekanik,
                        'intperbaikan'       => $intperbaikan,
                        'inttungguoperator'  => $inttungguoperator,
                        'inttunggumaterial'  => $inttunggumaterial,
                        'intadd'             => $this->session->intid,
                        'dtadd'              => date('Y-m-d H:i:s'),
                        'intupdate'          => $this->session->intid,
                        'dtupdate'           => date('Y-m-d H:i:s'),
                        'intstatus'          => 1
                );


            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
            
        } elseif ($tipe == 'Edit') {
            $dttanggal          = $this->input->post('dttanggal');
            $intgedung          = $this->input->post('intgedung');
            $intcell            = $this->input->post('intcell');
            $intmesin           = $this->input->post('intmesin');
            $intoperator        = $this->input->post('intoperator');
            $intleader          = $this->input->post('intleader');
            $intdtmesin_type    = $this->input->post('intdtmesin_type');
            $inttype_downtime   = $this->input->post('inttype_downtime');
            $inttype_list       = $this->input->post('inttype_list');
            $dtstop             = $this->input->post('dtstop');
            $dtstart            = $this->input->post('dtstart');
            $dtfinish           = $this->input->post('dtfinish');
            $dtrun              = $this->input->post('dtrun');
            $dtmaterialkosong   = $this->input->post('dtmaterialkosong');
            $dtmaterialtersedia = $this->input->post('dtmaterialtersedia');
            $vcmasalah          = $this->input->post('vcmasalah');
            $vcsolusi           = $this->input->post('vcsolusi');
            $intmekanik         = $this->input->post('intmekanik');
            $intsparepart       = $this->input->post('intsparepart');
            $intjumlahsparepart = $this->input->post('intjumlahsparepart');

            $inttunggumekanik  = ($dtstart == '') ? 0 : (strtotime($dtstart) - strtotime($dtstop))/60;
            $intperbaikan      = ($dtstart == '') ? 0 : (strtotime($dtfinish) - strtotime($dtstart))/60;
            $inttungguoperator = ($dtstart == '') ? 0 : (strtotime($dtrun) - strtotime($dtfinish))/60;
            $inttunggumaterial = ($dtmaterialtersedia == '') ? 0 : (strtotime($dtmaterialtersedia) - strtotime($dtmaterialkosong))/60;

            $data    = array(
                        'dttanggal'          => date('Y-m-d',strtotime($dttanggal)),
                        'intgedung'          => $intgedung,
                        'intcell'            => $intcell,
                        'intmesin'           => $intmesin,
                        'intoperator'        => $intoperator,
                        'intleader'          => $intleader,
                        'intdtmesin_type'    => $intdtmesin_type,
                        'inttype_downtime'   => $inttype_downtime,
                        'inttype_list'       => $inttype_list,
                        'dtstop'             => $dtstop,
                        'dtstart'            => $dtstart,
                        'dtfinish'           => $dtfinish,
                        'dtrun'              => $dtrun,
                        'dtmaterialkosong'   => $dtmaterialkosong,
                        'dtmaterialtersedia' => $dtmaterialtersedia,
                        'vcmasalah'          => $vcmasalah,
                        'vcsolusi'           => $vcsolusi,
                        'intmekanik'         => $intmekanik,
                        'intsparepart'       => $intsparepart,
                        'intjumlahsparepart' => $intjumlahsparepart,
                        'inttunggumekanik'   => $inttunggumekanik,
                        'intperbaikan'       => $intperbaikan,
                        'inttungguoperator'  => $inttungguoperator,
                        'inttunggumaterial'  => $inttunggumaterial,
                        'intupdate'          => $this->session->intid,
                        'dtupdate'           => date('Y-m-d H:i:s')
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

        } elseif ($tipe == 'AddMesin') {
            $vckode       = $this->input->post('vckode');
            $vcnama       = $this->input->post('vcnama');
            $intbrand     = $this->input->post('intbrand');
            $vcjenis      = $this->input->post('vcjenis');
            $vcserial     = $this->input->post('vcserial');
            $vcpower      = $this->input->post('vcpower');
            $intgedung    = $this->input->post('intgedung');
            $intcell      = $this->input->post('intcell');
            $intdeparture = $this->input->post('intdeparture');

            $data = array(
                    'vckode'       => $vckode,
                    'vcnama'       => $vcnama,
                    'intbrand'     => $intbrand,
                    'vcjenis'      => $vcjenis,
                    'vcserial'     => $vcserial,
                    'vcpower'      => $vcpower,
                    'intgedung'    => $intgedung,
                    'intcell'      => $intcell,
                    'intdeparture' => $intdeparture,
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 1
                );

            $resultmesin = $this->modelapp->insertdata('m_mesin',$data);

            $mesin = $this->model->getdetailmesin();

            $dataresult = array(
                        'datamesin' => $mesin,
                        'intmesin'  => $resultmesin
                    );
            echo json_encode($dataresult);

        } elseif ($tipe == 'AddKaryawan') {
            $vckode     = $this->input->post('vckode');
            $vcnama     = $this->input->post('vcnama');
            $intjabatan = $this->input->post('intjabatan');
            $intgedung  = $this->input->post('intgedung');
            
            $data       = array(
                    'vckode'     => $vckode,
                    'vcnama'     => $vcnama,
                    'intjabatan' => $intjabatan,
                    'intgedung'  => $intgedung,
                    'intadd'     => $this->session->intid,
                    'dtadd'      => date('Y-m-d H:i:s'),
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s'),
                    'intstatus'  => 1
                );

            $ceknama        = $this->model->ceknamakaryawan($vcnama, $intjabatan);
            $resultkaryawan = 0;

            if (count($ceknama) == 0) {
                $resultkaryawan = $this->modelapp->insertdata('m_karyawan',$data);
            }

            $karyawan = $this->model->getdetailkaryawan();

            $dataresult2 = array(
                        'datakaryawan' => $karyawan,
                        'intkaryawan'  => $resultkaryawan,
                        'intjabatan'   => $intjabatan,
                        'datanamasama' => $ceknama
                    );
            
            echo json_encode($dataresult2);

        } elseif ($tipe = 'EditKaryawan') {
            $vckode     = $this->input->post('vckode');
            $vcnama     = $this->input->post('vcnama');
            $intjabatan = $this->input->post('intjabatan');
            $intgedung  = $this->input->post('intgedung');
            
            $data       = array(
                    'vckode'     => $vckode,
                    'vcnama'     => $vcnama,
                    'intjabatan' => $intjabatan,
                    'intgedung'  => $intgedung,
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s'),
                    'intstatus'  => 1
                );
            
            $resultkaryawan = $this->modelapp->updatedata('m_karyawan',$data, $intid);

            $karyawan = $this->modelapp->getdatadetailcustom('m_karyawan');

            $dataresult2 = array(
                        'datakaryawan' => $karyawan,
                        'intkaryawan'  => $intid,
                        'intjabatan'   => $intjabatan
                    );
            
            echo json_encode($dataresult2);

        }

    }

    function addKaryawanBaru(){
        $vckode     = $this->input->post('vckode');
        $vcnama     = $this->input->post('vcnama');
        $intjabatan = $this->input->post('intjabatan');
        $intgedung  = $this->input->post('intgedung');
        $data       = array(
                'vckode'     => $vckode,
                'vcnama'     => $vcnama,
                'intjabatan' => $intjabatan,
                'intgedung'  => $intgedung,
                'intadd'     => $this->session->intid,
                'dtadd'      => date('Y-m-d H:i:s'),
                'intupdate'  => $this->session->intid,
                'dtupdate'   => date('Y-m-d H:i:s'),
                'intstatus'  => 1
            );

        $resultkaryawan = $this->modelapp->insertdata('m_karyawan',$data);
        
        $karyawan       = $this->modelapp->getdatadetailcustom('m_karyawan');

        $dataresult2 = array(
                    'datakaryawan' => $karyawan,
                    'intkaryawan'  => $resultkaryawan,
                    'intjabatan'   => $intjabatan
                );
        echo json_encode($dataresult2);
    }

    function addMesin(){
        $data = array(
                    'intid'        => 0,
                    'vckode'       => '',
                    'vcnama'       => '',
                    'intbrand'     => 0,
                    'vcjenis'      => '',
                    'vcserial'     => '',
                    'vcpower'      => '',
                    'intgedung'    => 0,
                    'intcell'      => 0,
                    'intdeparture' => 0,
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 0
                );

        $data['title']      = $this->title;
        $data['action']     = 'AddMesin';
        $data['controller'] = $this->controller;
        $data['listbrand']  = $this->modelapp->getdatalist('m_brand');
        $data['listarea']   = $this->modelapp->getdatalist('m_area');
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listcell']   = [];

       $this->load->view($this->view . '/addMesin',$data);
    }

    function addKaryawan($intjabatan=0){
        $data = array(
                    'intid'      => 0,
                    'vckode'     => '',
                    'vcnama'     => '',
                    'intjabatan' => $intjabatan,
                    'intgedung'  => 0,
                    'intadd'     => $this->session->intid,
                    'dtadd'      => date('Y-m-d H:i:s'),
                    'intupdate'  => $this->session->intid,
                    'dtupdate'   => date('Y-m-d H:i:s'),
                    'intstatus'  => 0
                );

        $data['title']       = $this->title;
        $data['action']      = 'AddKaryawan';
        $data['controller']  = $this->controller;
        $data['listjabatan'] = $this->modelapp->getdatalist('m_jabatan');
        $data['listgedung']  = $this->modelapp->getdatalist('m_gedung');

        $this->load->view($this->view . '/addKaryawan',$data);
    }

    function get_cell_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_cell',$intid,'intgedung');
        echo json_encode($data);
    }

     function get_mesin_ajax($intid){
        $data = $this->model->getdatamesin('m_mesin');
        echo json_encode($data);
    }

    function get_typelist_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_type_downtime_list',$intid,'intheader');
        echo json_encode($data);
    }

    function get_karyawan_ajax($intgedung,$intjabatan){
        $data = $this->model->getdatakaryawan('m_karyawan',$intgedung,$intjabatan);
        echo json_encode($data);
    }

    function form_detail_downtime(){
        $data['listtype']      = $this->modelapp->getdatalist('m_type_downtime');
        $data['listtypelist']  = [];
        $data['listmekanik']   = $this->modelapp->getdatalistall('m_karyawan',2,'intjabatan');
        $data['listsparepart'] = $this->modelapp->getdatalist('m_sparepart');
        $data['controller']    = $this->controller;
        $this->load->view('downtime_view/form_downtime',$data);
    }

    function exportexcel(){
        $keyword = $this->input->get('key');
        $from    = date('Y-m-d',strtotime($this->input->get('from')));
        $to      = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $data    = $this->model->getdata($this->table,$keyword,$from,$to);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Downtime ")
                     ->setSubject("Report Downtime")
                     ->setDescription("Report Downtime")
                     ->setKeywords("Report Downtime");

        // variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
        'font'       => array('bold' => true), // Set font nya jadi bold
        'alignment'  => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
            'borders' => array(
            'top'     => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // variabel untuk menampung pengaturan style dari isi tabel
            $style_row  = array(
            'alignment' => array(
            'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
        'borders' => array(
        'top'     => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
        'right'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
        'bottom'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
        'left'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Report Downtime ");
        $excel->getActiveSheet()->mergeCells('B1:T1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B2', "Report Downtime, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)));
        $excel->getActiveSheet()->mergeCells('B2:T2'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(12); // Set font size 15
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "TIPE DOWNTIME");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "TIPE MESIN");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA MESIN");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "ID MESIN");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "GEDUNG");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "CELL");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "KERUSAKAN / MASALAH");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "TINDAKAN");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "TNAGGAL");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "MULAI BERHENTI");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "MULAI DIPERBIKI");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "SELESAI DIPERBAIKI");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "MESIN DIGUNAKAN");
        $excel->setActiveSheetIndex(0)->setCellValue('P3', "MATERIAL KOSONG");
        $excel->setActiveSheetIndex(0)->setCellValue('Q3', "MATERIAL TERSEDIA");
        $excel->setActiveSheetIndex(0)->setCellValue('R3', "WAKTU MENUNGGU MEKANIK");
        $excel->setActiveSheetIndex(0)->setCellValue('S3', "WAKTU PERBAIKAN");
        $excel->setActiveSheetIndex(0)->setCellValue('T3', "WAKTU MENUNGGU OPERATOR");
        $excel->setActiveSheetIndex(0)->setCellValue('U3', "WAKTU MENUNGGU MATERIAL");


        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('P3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('Q3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('S3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('T3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('U3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('B3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('C3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('D3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('E3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('F3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('G3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('H3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('I3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('J3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('K3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('L3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('M3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('N3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('O3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('P3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('Q3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('R3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('S3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('T3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('U3')->applyFromArray($style_col);

        $numrow = 4;
        $no = 0;
        foreach ($data as $dataset) {
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ++$no);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vctype_downtime);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $dataset->vcdtmesin_type);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->vcmesin);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->vckodemesin);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, substr($dataset->vcgedung, -1));
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, substr($dataset->vccell, 6));
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $dataset->vcmasalah);
            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $dataset->vcsolusi);
            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, date('d-M-Y',strtotime($dataset->dttanggal)));
            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, date('H:i',strtotime($dataset->dtstop)));
            $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, date('H:i',strtotime($dataset->dtstart)));
            $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, date('H:i',strtotime($dataset->dtfinish)));
            $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, date('H:i',strtotime($dataset->dtrun)));
            $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, date('H:i',strtotime($dataset->dtmaterialkosong)));
            $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, date('H:i',strtotime($dataset->dtmaterialtersedia)));
            $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $dataset->inttunggumekanik);
            $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $dataset->intperbaikan);
            $excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $dataset->inttungguoperator);
            $excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $dataset->inttunggumaterial);

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('U'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }
        
        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Report Downtime");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Downtime.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

}
