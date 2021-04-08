<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Output_cc extends MY_Controller { 

    function __construct(){
        parent::__construct();
        $this->load->model('OutputModel');
        $this->model = $this->OutputModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intmesin = $this->input->get('intmesin');
        $intshift = $this->input->get('intshift');
        $from    = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to      = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $dataP = [];

        // if ($intshift == 0) {
        //     $jmldata = $this->model->getjmldata($this->table,$intmesin,$from,$to);
        //     $offset  = ($halaman - 1) * $this->limit;
        //     $jmlpage = ceil($jmldata[0]->jmldata / $this->limit);
        //     $dataP   = $this->model->getdatalimit($this->table,$offset,$this->limit,$intmesin,$from,$to);

        // } else if ($intshift > 0) {
        //     $jmldata = $this->model->getjmldatapershift($this->table,$intmesin,$from,$to, $intshift);
        //     $offset  = ($halaman - 1) * $this->limit;
        //     $jmlpage = ceil($jmldata[0]->jmldata / $this->limit);
        //     $dataP   = $this->model->getdatalimitpershift($this->table,$offset,$this->limit,$intmesin,$from,$to,$intshift);
        // }

        $datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intshift == 0) {
            for ($i=0; $i <= $datediff; $i++) { 
                $dt       = $i + 1;
                $date1    = date( "Y-m-d 07:00:00", strtotime( $from . ' ' ." +" . $i . " day" ) );
                $date2    = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
                $jmldata  = $this->model->getjmldata($this->table,$intmesin,$date1,$date2);
                $offset   = ($halaman - 1) * $this->limit;
                $jmlpage  = ceil($jmldata[0]->jmldata / $this->limit);
                $dataP = $this->model->getdatalimit($this->table,$offset,$this->limit,$intmesin,$date1,$date2);
            }
        } elseif ($intshift > 0) {
            for ($i=0; $i <= $datediff; $i++) {
                $dt      = $i + 1;
                $date1   = date( "Y-m-d 07:00:00", strtotime( $from . ' ' ." +" . $i . " day" ) );
                $date2   = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
                $jmldata = $this->model->getjmldatapershift($this->table,$intmesin,$date1,$date2, $intshift);
                $offset  = ($halaman - 1) * $this->limit;
                $jmlpage = ceil($jmldata[0]->jmldata / $this->limit);
                $dataP   = $this->model->getdatalimitpershift($this->table,$offset,$this->limit,$intmesin,$date1,$date2,$intshift);
            }
        }

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
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
        $data['listmesin']  = $this->modelapp->getdatalistall('m_mesin');
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
        $data['listmesin']    = $this->modelapp->getdatalistall('m_mesin');
        $data['listoperator'] = $this->modelapp->getdatalistall('m_karyawan',3,'intjabatan');
        $data['listleader']   = $this->modelapp->getdatalistall('m_karyawan',1,'intjabatan');


        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function getkomponen_ajax($intid){
        $data = $this->model->getkomponen($intid);

        echo json_encode($data);
    }

    function edit($intid){
        $resultData   = $this->model->getdatadetail($this->table,$intid);
        // $dataKomponen = $this->model->getkomponen($intid);
        // $listkomponen = [];

        // foreach ($dataKomponen as $dk) {
        //     array_push($listkomponen, $this->modelapp->getdatalistall('m_models_komponen',$dk->intmodel,'intheader'));
        // }

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
                     'intupdate'   => $this->session->intid,
                     'dtupdate'    => date('Y-m-d H:i:s')
                );

        $data['title']        = $this->title;
        $data['action']       = 'Edit';
        $data['controller']   = $this->controller;
        $data['listshift']    = $this->modelapp->getdatalist('m_shift');
        $data['listmesin']    = $this->modelapp->getdatalistall('m_mesin');
        $data['listoperator'] = $this->modelapp->getdatalistall('m_karyawan',3,'intjabatan');
        $data['listleader']   = $this->modelapp->getdatalistall('m_karyawan',1,'intjabatan');
        $data['listmodels']   = $this->modelapp->getdatalist('m_models');
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

           $dttanggal   = $this->input->post('dttanggal');
           $intgedung   = $datamesin[0]->intgedung;
           $intcell     = $datamesin[0]->intcell;
           $intmesin    = $this->input->post('intmesin');
           $intshift    = $this->input->post('intshift');
           $intoperator = $this->input->post('intoperator');
           $intleader   = $this->input->post('intleader');
           $intmodel    = $this->input->post('intmodel');
           $intkomponen = $this->input->post('intkomponen');
           $decct       = $this->input->post('decct');
           $dtmulai     = $this->input->post('dtmulai');
           $dtselesai   = $this->input->post('dtselesai');
           $decdurasi   = ceil((strtotime($dtselesai) - strtotime($dtmulai))/60);
           $intpasang   = $this->input->post('intpasang');
           $intreject   = $this->input->post('intreject');

            $data         = array(
                     'dttanggal'   => date('Y-m-d H:i:s',strtotime($dttanggal)),
                     'intgedung'   => $intgedung,
                     'intcell'     => $intcell,
                     'intmesin'    => $intmesin,
                     'intshift'    => $intshift,
                     'intoperator' => $intoperator,
                     'intleader'   => $intleader,
                     'intmodel'    => $intmodel,
                     'intkomponen' => $intkomponen,
                     'decct'       => $decct,
                     'dtmulai'     => $dtmulai,
                     'dtselesai'   => $dtselesai,
                     'decdurasi'   => $decdurasi,
                     'intpasang'   => $intpasang,
                     'intreject'   => $intreject,
                     'intadd'      => $this->session->intid,
                     'dtadd'       => date('Y-m-d H:i:s'),
                     'intupdate'   => $this->session->intid,
                     'dtupdate'    => date('Y-m-d H:i:s'),
                     'intstatus'   => 1
                );
            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
            
        } elseif ($tipe == 'Edit') {
           $datamesin    = $this->modelapp->getdatadetailcustom('m_mesin',$this->input->post('intmesin'),'intid');

            $dttanggal   = $this->input->post('dttanggal');
            $intgedung   = $datamesin[0]->intgedung;
            $intcell     = $datamesin[0]->intcell;
            $intmesin    = $this->input->post('intmesin');
            $intshift    = $this->input->post('intshift');
            $intoperator = $this->input->post('intoperator');
            $intleader   = $this->input->post('intleader');
            $intmodel    = $this->input->post('intmodel');
            $intkomponen = $this->input->post('intkomponen');
            $decct       = $this->input->post('decct');
            $dtmulai     = $this->input->post('dtmulai');
            $dtselesai   = $this->input->post('dtselesai');
            $decdurasi   = ceil((strtotime($dtselesai) - strtotime($dtmulai))/60);
            $intpasang   = $this->input->post('intpasang');
            $intreject   = $this->input->post('intreject');

            $data    = array(
                         'dttanggal'   => date('Y-m-d H:i:s',strtotime($dttanggal)),
                         'intgedung'   => $intgedung,
                         'intcell'     => $intcell,
                         'intmesin'    => $intmesin,
                         'intshift'    => $intshift,
                         'intoperator' => $intoperator,
                         'intleader'   => $intleader,
                         'intmodel'    => $intmodel,
                         'intkomponen' => $intkomponen,
                         'decct'       => $decct,
                         'dtmulai'     => $dtmulai,
                         'dtselesai'   => $dtselesai,
                         'decdurasi'   => $decdurasi,
                         'intpasang'   => $intpasang,
                         'intreject'   => $intreject,
                         'intupdate'   => $this->session->intid,
                         'dtupdate'    => date('Y-m-d H:i:s')
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

    function get_cell_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_cell',$intid,'intgedung');
        echo json_encode($data);
    }

     function get_mesin_ajax($intid){
        $data = $this->modelapp->getdatadetailcustom('m_mesin',$intid,'intcell');
        echo json_encode($data);
    }

    function get_karyawan_ajax($intgedung,$intjabatan){
        $data = $this->model->getdatakaryawan('m_karyawan',$intgedung,$intjabatan);
        echo json_encode($data);
    }

     function exportexcel(){
        $intmesin = $this->input->get('intmesin');
        $intshift = $this->input->get('intshift');
        $from     = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to       = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $data     = [];
        $datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intshift == 0) {
            for ($i=0; $i <= $datediff; $i++) { 
                $dt    = $i + 1;
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from . ' ' ." +" . $i . " day" ) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
                $data  = $this->model->getdata($this->table,$intmesin,$date1,$date2);
            }
        } elseif ($intshift > 0) {
            for ($i=0; $i <= $datediff; $i++) {
                $dt    = $i + 1;
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from . ' ' ." +" . $i . " day" ) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
                $data  = $this->model->getdatapershift($this->table,$intmesin,$date1,$date2,$intshift);
            }
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
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "Actual");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Reject");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Operator");
        $excel->setActiveSheetIndex(0)->setCellValue('P3', "Leader");
        $excel->setActiveSheetIndex(0)->setCellValue('Q3', "Shift");



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

        $numrow = 4;
        $no = 0;
        foreach ($data as $dataset) {

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
           $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $dataset->intpasang);
           $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $dataset->intreject);
           $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $dataset->vcoperator);
           $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $dataset->vcleader);
           $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $dataset->vcshift);

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

}
