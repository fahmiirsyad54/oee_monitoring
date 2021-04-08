<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Output_laser extends MY_Controller { 

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('AppModel');
        $this->load->model('Output_laserModel');
        $this->model = $this->Output_laserModel;
        $this->modelapp = $this->AppModel;
    } 

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intgedung = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmesin  = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift  = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from      = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to        = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $dataP     = [];

        $datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intshift == 0) { 
            $date1   = date( "Y-m-d 07:00:00", strtotime( $from) );
            $date2   = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
            $jmldata = $this->model->getjmldata($this->table,$intmesin,$date1,$date2);
            $offset  = ($halaman - 1) * $this->limit;
            $jmlpage = ceil($jmldata[0]->jmldata / $this->limit);
            $dataP   = $this->model->getdatalimit($this->table,$offset,$this->limit,$intmesin,$date1,$date2);
        } elseif ($intshift > 0) {
            $date1   = date( "Y-m-d 07:00:00", strtotime( $from) );
            $date2   = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
            $jmldata = $this->model->getjmldatapershift($this->table,$intmesin,$date1,$date2, $intshift);
            $offset  = ($halaman - 1) * $this->limit;
            $jmlpage = ceil($jmldata[0]->jmldata / $this->limit);
            $dataP   = $this->model->getdatalimitpershift($this->table,$offset,$this->limit,$intmesin,$date1,$date2,$intshift);
        }

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['intgedung']  = $intgedung;
        $data['intmesin']   = $intmesin;
        $data['intshift']   = $intshift;
        $data['from']       = $from;
        $data['to']         = $to;
        $data['from_input'] = ($this->input->get('from')) ? date('m/d/Y', strtotime($from)) : '';
        $data['to_input']   = ($this->input->get('to')) ? date('m/d/Y', strtotime($to)) : '';
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $dataP;
        $data['listgedung']  = $this->modelapp->getdatalistall('m_gedung');
        $data['listmesin']  = $this->model->getdatamesin($intgedung, $intmesin);
        $data['listshift']  = $this->modelapp->getdatalistall('m_shift');
        $data['hideaction'] = $this->hideaction;

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    } 

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){ 
        $kodeunik   = $this->model->buat_kode();
        $data = array(
                    'intid'       => 0,
                    'dttanggal'   => date('m/d/Y H:i'),
                    'intgedung'   => 0,
                    'intcell'     => 0,
                    'intmesin'    => 0,
                    'intoperator' => 0,
                    'intleader'   => 0,
                    'intmodel'    => 0,
                    'intkomponen' => 0,
                    'decct'       => 0,
                    'intlayer'    => 0,
                    'intremark'   => 0,
                    'dtmulai'     => date('H:i:s'),
                    'dtselesai'   => date('H:i:s'),
                    'decdurasi'   => 0,
                    'intpasang'   => 0,
                    'intreject'   => 0,
                    'intadd'      => $this->session->intid,
                    'dtadd'       => date('Y-m-d H:i:s'),
                    'intupdate'   => $this->session->intid,
                    'dtupdate'    => date('Y-m-d H:i:s'),
                    'intstatus'   => 0
                ); 

        $data['title']        = $this->title;
        $data['action']       = 'Add';
        $data['controller']   = $this->controller;
        $data['listshift']    = $this->modelapp->getdatalist('m_shift');
        $data['listmodels']   = $this->modelapp->getdatalist('m_models');
        $data['listremark']   = $this->modelapp->getdatalist('m_output_remark');
        $data['listmesin']    = $this->model->getmesin();
        $data['listoperator'] = $this->model->getkaryawan('m_karyawan',3);
        $data['listleader']   = $this->model->getkaryawan('m_karyawan',1);


        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData         = $this->model->getdatadetail($this->table,$intid);
        $dttanggalcombine   = date('Y-m-d', strtotime($resultData[0]->dttanggal));
        $dtmulaicombine     = $resultData[0]->dtmulai;
        $dtselesaicombine   = $resultData[0]->dtselesai;
        $intmesincombine    = $resultData[0]->intmesin;
        $intoperatorcombine = $resultData[0]->intoperator;
        $datacombine        = $this->model->getdatacombine($intmesincombine, $intoperatorcombine, $dttanggalcombine, $dtmulaicombine, $dtselesaicombine);
        // print_r($datacombine); exit();

        $data = array(
                     'intid'       => $resultData[0]->intid,
                     'dttanggal'   => date('m/d/Y H:i', strtotime($resultData[0]->dttanggal)),
                     'intshift'    => $resultData[0]->intshift,
                     'intmesin'    => $resultData[0]->intmesin,
                     'intoperator' => $resultData[0]->intoperator,
                     'intleader'   => $resultData[0]->intleader,
                     'intmodel'    => $resultData[0]->intmodel,
                     'intkomponen' => $resultData[0]->intkomponen,
                     'dtmulai'     => $resultData[0]->dtmulai,
                     'dtselesai'   => $resultData[0]->dtselesai,
                     'intpasang'   => $resultData[0]->intpasang,
                     'intreject'   => $resultData[0]->intreject,
                     'decct'       => $resultData[0]->decct,
                     'intlayer'    => $resultData[0]->intlayer,
                     'intremark'   => $resultData[0]->intremark,
                     'intupdate'   => $this->session->intid,
                     'dtupdate'    => date('Y-m-d H:i:s')
                );
        
        $data['datacombine']  = $datacombine;
        $data['title']        = $this->title;
        $data['action']       = 'Edit';
        $data['controller']   = $this->controller;
        $data['listshift']    = $this->modelapp->getdatalist('m_shift');
        $data['listmesin']    = $this->model->getmesin();
        $data['listoperator'] = $this->model->getkaryawan('m_karyawan',3);
        $data['listleader']   = $this->model->getkaryawan('m_karyawan',1);
        $data['listmodels']   = $this->modelapp->getdatalist('m_models');
        $data['listremark']   = $this->modelapp->getdatalist('m_output_remark');
        $data['listkomponen'] = $this->model->getkomponen($resultData[0]->intmodel);

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
           $datamesin    = $this->modelapp->getdatadetailcustom('m_mesin',$this->input->post('intmesin'),'intid');

            $dttanggal     = $this->input->post('dttanggal');
            $intgedung     = $datamesin[0]->intgedung;
            $intcell       = $datamesin[0]->intcell;
            $intmesin      = $this->input->post('intmesin');
            $intshift      = $this->input->post('intshift');
            $intoperator   = $this->input->post('intoperator');
            $intleader     = $this->input->post('intleader');
            $intmodel      = $this->input->post('intmodel');
            $intkomponen   = $this->input->post('intkomponen');
            $decct         = $this->input->post('decct');
            $intlayer      = $this->input->post('intlayer');
            $intremark     = $this->input->post('intremark');
            $dtmulai       = $this->input->post('dtmulai');
            $dtselesai     = $this->input->post('dtselesai');
            $decdurasitemp = ceil((strtotime($dtselesai) - strtotime($dtmulai))/60);
            $intpasang     = $this->input->post('intpasang');
            $intreject     = $this->input->post('intreject');
            $countDetail   = count($intkomponen);
            
            if ($decdurasitemp > 1000) {
                $decdurasitempp = 1440 - $decdurasitemp;
                if ($decdurasitempp < 1) {
                    $decdurasi = 1;
                } else {
                    $decdurasi = $decdurasitempp;
                }
            } else {
                $decdurasi = $decdurasitemp;
            }

            $jumlahpasang = 0;
            for ($h=0; $h < $countDetail; $h++) { 
                $jumlahpasang = $jumlahpasang + $intpasang[$h];
            }

            for ($i=0; $i < $countDetail; $i++) {
                //$targetall = ($decdurasi*60) / $decct[$i];
                $persen  = $intpasang[$i]/$jumlahpasang;
                $timeori = ceil($intpasang[$i] * $decct[$i]);

                $tottimeoritemp = 0;
                for ($j=0; $j < $countDetail; $j++) { 
                    $tottimeoritemp = $tottimeoritemp + $timeori;
                    }
                $totctall       = $tottimeoritemp / $jumlahpasang;
                $targetall      = ceil(($decdurasi*60) / $totctall); 
                $target         = ceil($targetall * $persen);
                $max            = $target + ceil($target * 0.15);
                $min            = $target - ceil($target * 0.15);
                
                if ($intpasang[$i] < $min || $intpasang[$i] > $max) {
                    $keterangan = 'Tidak mengikuti SOP';
                } else {
                    $keterangan = '';
                }

                $data = array(
                     'dttanggal'    => date('Y-m-d H:i:s',strtotime($dttanggal)),
                     'intgedung'    => $intgedung,
                     'intcell'      => $intcell,
                     'intmesin'     => $intmesin,
                     'intshift'     => $intshift,
                     'intoperator'  => $intoperator,
                     'intleader'    => $intleader,
                     'intmodel'     => $intmodel,
                     'intkomponen'  => $intkomponen[$i],
                     'decct'        => $decct[$i],
                     'intlayer'     => $intlayer[$i],
                     'intremark'    => $intremark[$i],
                     'dtmulai'      => $dtmulai,
                     'dtselesai'    => $dtselesai,
                     'decdurasi'    => $decdurasi,
                     'intpasang'    => $intpasang[$i],
                     'intreject'    => $intreject[$i],
                     'inttarget'    => $target,
                     'vcketerangan' => $keterangan,
                     'intadd'       => $this->session->intid,
                     'dtadd'        => date('Y-m-d H:i:s'),
                     'intupdate'    => $this->session->intid,
                     'dtupdate'     => date('Y-m-d H:i:s'),
                     'intstatus'    => 1
                );
            $result = $this->modelapp->insertdata($this->table,$data);
           }

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
            
        } elseif ($tipe == 'Edit') {
           $datamesin    = $this->modelapp->getdatadetailcustom('m_mesin',$this->input->post('intmesin'),'intid');

            $intidcombine  = $this->input->post('intidcombine');
            $dttanggal     = $this->input->post('dttanggal');
            $intgedung     = $datamesin[0]->intgedung;
            $intcell       = $datamesin[0]->intcell;
            $intmesin      = $this->input->post('intmesin');
            $intshift      = $this->input->post('intshift');
            $intoperator   = $this->input->post('intoperator');
            $intleader     = $this->input->post('intleader');
            $intmodel      = $this->input->post('intmodel');
            $intkomponen   = $this->input->post('intkomponen');
            $decct         = $this->input->post('decct');
            $intlayer      = $this->input->post('intlayer');
            $intremark     = $this->input->post('intremark');
            $dtmulai       = $this->input->post('dtmulai');
            $dtselesai     = $this->input->post('dtselesai');
            $decdurasitemp = ceil((strtotime($dtselesai) - strtotime($dtmulai))/60);
            $intpasang     = $this->input->post('intpasang');
            $intreject     = $this->input->post('intreject');
            $countDetail   = count($intkomponen);

            if ($decdurasitemp > 1000) {
                $decdurasitempp = 1440 - $decdurasitemp;
                if ($decdurasitempp < 1) {
                    $decdurasi = 1;
                } else {
                    $decdurasi = $decdurasitempp;
                }
            } else {
                $decdurasi = $decdurasitemp;
            }

            $jumlahpasang = 0;
            for ($h=0; $h < $countDetail; $h++) { 
                $jumlahpasang = $jumlahpasang + $intpasang[$h];
            }

            for ($i=0; $i < $countDetail; $i++) { 
                //$targetall = ($decdurasi*60) / $decct[$i];
                $persen  = $intpasang[$i]/$jumlahpasang;
                $timeori = ceil($intpasang[$i] * $decct[$i]);
                $tottimeoritemp = 0;
                for ($j=0; $j < $countDetail; $j++) { 
                    $tottimeoritemp = $tottimeoritemp + $timeori;
                    }
                $totctall       = $tottimeoritemp / $jumlahpasang;
                $targetall      = ceil(($decdurasi*60) / $totctall); 
                $target         = ceil($targetall * $persen);
                $max            = $target + ceil($target * 0.15);
                $min            = $target - ceil($target * 0.15);
                if ($intpasang[$i] < $min || $intpasang[$i] > $max) {
                    $keterangan = 'Tidak mengikuti SOP';
                } else {
                    $keterangan = '';
                }

                $data = array(
                     'dttanggal'    => date('Y-m-d H:i:s',strtotime($dttanggal)),
                     'intgedung'    => $intgedung,
                     'intcell'      => $intcell,
                     'intmesin'     => $intmesin,
                     'intshift'     => $intshift,
                     'intoperator'  => $intoperator,
                     'intleader'    => $intleader,
                     'intmodel'     => $intmodel,
                     'intkomponen'  => $intkomponen[$i],
                     'decct'        => $decct[$i],
                     'intlayer'     => $intlayer[$i],
                     'intremark'    => $intremark[$i],
                     'dtmulai'      => $dtmulai,
                     'dtselesai'    => $dtselesai,
                     'decdurasi'    => $decdurasi,
                     'intpasang'    => $intpasang[$i],
                     'intreject'    => $intreject[$i],
                     'inttarget'    => $target,
                     'vcketerangan' => $keterangan,
                     'intupdate'    => $this->session->intid,
                     'dtupdate'     => date('Y-m-d H:i:s')
                );
                // print_r($data);echo $intidcombine[$i]; echo "<br><br>";
                $result = $this->modelapp->updatedata($this->table,$data,$intidcombine[$i]);
            }
            // $data    = array(
            //              'dttanggal'   => date('Y-m-d H:i:s',strtotime($dttanggal)),
            //              'intgedung'   => $intgedung,
            //              'intcell'     => $intcell,
            //              'intmesin'    => $intmesin,
            //              'intshift'    => $intshift,
            //              'intoperator' => $intoperator,
            //              'intleader'   => $intleader,
            //              'intmodel'    => $intmodel,
            //              'intkomponen' => $intkomponen,
            //              'decct'       => $decct,
            //              'dtmulai'     => $dtmulai,
            //              'dtselesai'   => $dtselesai,
            //              'decdurasi'   => $decdurasi,
            //              'intpasang'   => $intpasang,
            //              'intreject'   => $intreject,
            //              'intupdate'   => $this->session->intid,
            //              'dtupdate'    => date('Y-m-d H:i:s')
            //     );
            
            // $result = $this->modelapp->updatedata($this->table,$data,$intid);
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

    function get_cell_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_cell',$intid,'intgedung');
        echo json_encode($data);
    }

    function getmesinajax($intgedung){
        $data = $this->model->getdatamesin($intgedung);
        echo json_encode($data);
    }

    function get_karyawan_ajax($intgedung,$intjabatan){
        $data = $this->model->getdatakaryawan('m_karyawan',$intgedung,$intjabatan);
        echo json_encode($data);
    }

    function getkomponen_ajax($intid){
        $data = $this->model->getkomponen($intid);

        echo json_encode($data);
    }

    function form_detail_output(){
        // $layer = array(
        //                 '2' => '2 Layer',
        //                 '4' => '4 Layer',
        //                 '6' => '6 Layer',
        //                 '8' => '8 Layer'
        //             );

        $data['listkomponen'] = $this->modelapp->getdatalist('m_komponen');
        $data['listremark']   = $this->modelapp->getdatalist('m_output_remark');
        $data['controller']   = $this->controller;

        $this->load->view('output_view/form_output',$data);
    }

    function getintkomponen($intkomponen){
        $data = $this->model->getintkomponen($intkomponen);
        echo json_encode($data);
    }

    function exportexcel(){
        ini_set('max_execution_time', 123456);
        $intmesin = $this->input->get('intmesin');
        $intshift = $this->input->get('intshift');
        $from     = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to       = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $data     = [];
        $datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intshift == 0) {
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
                $data  = $this->model->getdata($this->table,$intmesin,$date1,$date2);
        } elseif ($intshift > 0) {
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
                $data  = $this->model->getdatapershift($this->table,$intmesin,$date1,$date2,$intshift);
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Output ")
                     ->setSubject("Report Output")
                     ->setDescription("Report Output")
                     ->setKeywords("Report Output");

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

        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Report Output ");
        $excel->getActiveSheet()->mergeCells('B1:Q1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B2', "Report Output, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)));
        $excel->getActiveSheet()->mergeCells('B2:Q2'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(12); // Set font size 15
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Date");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "ID Machine");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Building");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Cell");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Models");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Component");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Cycle Time");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Start");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Finish");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "Duration");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "Target");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Actual");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Reject");
        $excel->setActiveSheetIndex(0)->setCellValue('P3', "Operator");
        $excel->setActiveSheetIndex(0)->setCellValue('Q3', "Leader");
        $excel->setActiveSheetIndex(0)->setCellValue('R3', "Shift");
        $excel->setActiveSheetIndex(0)->setCellValue('S3', "Remark");



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

        $numrow = 4;
        $no = 0;
        foreach ($data as $dataset) {

            if ($dataset->vcketerangan != '') {
                $keterangan = "Not Follow SOP";
            } else {
                $keterangan = "Follow SOP";
            }

           $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ++$no);
           $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, date('d M Y H:i:s', strtotime($dataset->dttanggal)));
           $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $dataset->vcmesin);
           $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->vcgedung);
           $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->vccell);
           $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $dataset->vcmodel);
           $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $dataset->vckomponen);
           $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $dataset->decct);
           $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $dataset->dtmulai);
           $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $dataset->dtselesai);
           $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $dataset->decdurasi);
           $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $dataset->inttarget);
           $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $dataset->intpasang);
           $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $dataset->intreject);
           $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $dataset->vcoperator);
           $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $dataset->vcleader);
           $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $dataset->vcshift);
           $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $keterangan);

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

            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);       
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Report Output");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Output.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    function exportexcelnew(){
        $intgedung = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmesin  = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift  = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from      = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to        = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $datamesin = $this->model->getdatamesin($intgedung, $intmesin);
        $judul     = '';

        if ($intgedung > 0) {
            $dtgedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
            $judul    = $dtgedung[0]->vcnama;
        }
        
        if ($intmesin > 0) {
            $dtmesin = $this->modelapp->getdatadetailcustom('m_mesin',$intmesin,'intid');
            $judul = $dtmesin[0]->vckode . ' - ' . $dtmesin[0]->vcnama;
        }
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Output " . $judul)
                     ->setSubject("Report Output")
                     ->setDescription("Report Output")
                     ->setKeywords("Report Output");

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

        $loop = 0;
        foreach ($datamesin as $mesin) {
            if ($loop > 0) {
                $excel->createSheet();
            }

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report Output " . $mesin->vckode . ' - ' . $mesin->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:P1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B2', "Report Output, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)));
            $excel->getActiveSheet()->mergeCells('B2:P2'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(12); // Set font size 15
            $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B3', "NO");
            $excel->setActiveSheetIndex($loop)->setCellValue('C3', "Date");
            $excel->setActiveSheetIndex($loop)->setCellValue('D3', "ID Machine");
            $excel->setActiveSheetIndex($loop)->setCellValue('E3', "Building");
            $excel->setActiveSheetIndex($loop)->setCellValue('F3', "Cell");
            $excel->setActiveSheetIndex($loop)->setCellValue('G3', "Models");
            $excel->setActiveSheetIndex($loop)->setCellValue('H3', "Component");
            $excel->setActiveSheetIndex($loop)->setCellValue('I3', "Cycle Time");
            $excel->setActiveSheetIndex($loop)->setCellValue('J3', "Layer Standard");
            $excel->setActiveSheetIndex($loop)->setCellValue('K3', "Layer Actual");
            $excel->setActiveSheetIndex($loop)->setCellValue('L3', "Start");
            $excel->setActiveSheetIndex($loop)->setCellValue('M3', "Finish");
            $excel->setActiveSheetIndex($loop)->setCellValue('N3', "Duration");
            $excel->setActiveSheetIndex($loop)->setCellValue('O3', "Target");
            $excel->setActiveSheetIndex($loop)->setCellValue('P3', "Actual");
            $excel->setActiveSheetIndex($loop)->setCellValue('Q3', "Actual Monitoring");
            $excel->setActiveSheetIndex($loop)->setCellValue('R3', "GAP");
            $excel->setActiveSheetIndex($loop)->setCellValue('S3', "Reject");
            $excel->setActiveSheetIndex($loop)->setCellValue('T3', "Operator");
            $excel->setActiveSheetIndex($loop)->setCellValue('U3', "Leader");
            $excel->setActiveSheetIndex($loop)->setCellValue('V3', "Shift");
            $excel->setActiveSheetIndex($loop)->setCellValue('W3', "Remark");



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
            $excel->getActiveSheet()->getStyle('V3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('W3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $excel->setActiveSheetIndex($loop)->getStyle('B3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('C3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('D3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('E3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('F3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('G3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('H3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('I3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('J3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('K3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('L3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('M3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('N3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('O3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('P3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('Q3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('R3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('S3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('T3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('U3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('V3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('W3')->applyFromArray($style_col);

            if ($intshift == 0) {
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
                $data   = $this->model->getdata($this->table,$mesin->intid,$date1,$date2);

            } else if ($intshift > 0) {
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
                $data   = $this->model->getdatapershift($this->table,$mesin->intid,$date1,$date2,$intshift);
            }

            $numrow = 4;
            $no = 0;
            foreach ($data as $dataset) {

                if ($dataset->vcketerangan != '') {
                    $keterangan = "Not Follow SOP";
                } else {
                    $keterangan = "Follow SOP";
                }

                if ($dataset->intlayer == 2) {
                    $layer = "2 Layer";
                } else if ($dataset->intlayer == 4) {
                    $layer = "4 Layer";
                } else if ($dataset->intlayer == 6) {
                    $layer = "6 Layer";
                }
                else if ($dataset->intlayer == 8) {
                    $layer = "8 Layer";
                } else if ($dataset->intlayer == '') {
                    $layer = "";
                }

                if ($dataset->intpasang > $dataset->inttarget) {
                    $monitoring = $dataset->inttarget;
                } else {
                    $monitoring = $dataset->intpasang;
                }

                $gap = $dataset->intpasang - $dataset->inttarget; 

                $excel->setActiveSheetIndex($loop)->setCellValue('B'.$numrow, ++$no);
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, date('d M Y H:i:s', strtotime($dataset->dttanggal)));
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, $dataset->vcmesin);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, $dataset->vcgedung);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, $dataset->vccell);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, $dataset->vcmodel);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, $dataset->vckomponen);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, $dataset->decct);
                $excel->setActiveSheetIndex($loop)->setCellValue('J'.$numrow, $layer);
                $excel->setActiveSheetIndex($loop)->setCellValue('K'.$numrow, $dataset->vclayer);
                $excel->setActiveSheetIndex($loop)->setCellValue('L'.$numrow, $dataset->dtmulai);
                $excel->setActiveSheetIndex($loop)->setCellValue('M'.$numrow, $dataset->dtselesai);
                $excel->setActiveSheetIndex($loop)->setCellValue('N'.$numrow, $dataset->decdurasi);
                $excel->setActiveSheetIndex($loop)->setCellValue('O'.$numrow, $dataset->inttarget);
                $excel->setActiveSheetIndex($loop)->setCellValue('P'.$numrow, $dataset->intpasang);
                $excel->setActiveSheetIndex($loop)->setCellValue('Q'.$numrow, $monitoring);
                $excel->setActiveSheetIndex($loop)->setCellValue('R'.$numrow, $gap);
                $excel->setActiveSheetIndex($loop)->setCellValue('S'.$numrow, $dataset->intreject);
                $excel->setActiveSheetIndex($loop)->setCellValue('T'.$numrow, $dataset->vcoperator);
                $excel->setActiveSheetIndex($loop)->setCellValue('U'.$numrow, $dataset->vcleader);
                $excel->setActiveSheetIndex($loop)->setCellValue('V'.$numrow, $dataset->vcshift);
                $excel->setActiveSheetIndex($loop)->setCellValue('W'.$numrow, $keterangan);
     
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
                $excel->getActiveSheet()->getStyle('V'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('W'.$numrow)->applyFromArray($style_row);

                $numrow++;
            }

            // Set width kolom
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('5');
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
            $excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);

            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $excel->getActiveSheet($loop)->getDefaultRowDimension()->setRowHeight(-1);

            // Set orientasi kertas jadi LANDSCAPE
            $excel->getActiveSheet($loop)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

            // Set judul file excel nya
            $excel->getActiveSheet($loop)->setTitle($mesin->vckode . '-' . $mesin->vcnama);
            $loop++;
        }

        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Output ' .$judul. '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

}
