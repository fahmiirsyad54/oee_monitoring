<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downtime extends MY_Controller { 

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

        $jmldata            = $this->model->getjmldata($this->table,$keyword);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword,$from,$to);
        // print_r($to);exit(); 

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
                    'intid'            => 0,
                    'vckode'           => '',
                    'dttanggal'        => date('m/d/Y'),
                    'intgedung'        => 0,
                    'intcell'          => 0,
                    'intmesin'         => 0,
                    'vcmasalah'        => '',
                    'dtmulai'          => date('H:i:s'),
                    'dtselesai'        => date('H:i:s'),
                    'decdurasi'        => 0,
                    'vcpenyebab'       => '',
                    'inttype_downtime' => 0,
                    'inttype_list'     => 0,
                    'vctindakan'       => '',
                    'intsparepart'     => 0,
                    'intjumlah'        => 0,
                    'intoperator'      => 0,
                    'intleader'        => 0,
                    'intmekanik'       => 0,
                    'vcketerangan'     => '',
                    'intadd'           => $this->session->intid,
                    'dtadd'            => date('Y-m-d H:i:s'),
                    'intupdate'        => $this->session->intid,
                    'dtupdate'         => date('Y-m-d H:i:s'),
                    'intstatus'        => 0
                ); 

        $data['title']         = $this->title;
        $data['action']        = 'Add';
        $data['controller']    = $this->controller;
        $data['dataDowntime']  = [];
        $data['listgedung']    = $this->modelapp->getdatalist('m_gedung');
        $data['listshift']     = $this->modelapp->getdatalist('m_shift');
        $data['listsparepart'] = $this->modelapp->getdatalist('m_sparepart');
        $data['listtype']      = $this->modelapp->getdatalist('m_type_downtime');
        $data['listtypelist']  = [];
        $data['listcell']      = [];
        $data['listmesin']     = [];
        $data['listoperator']  = $this->modelapp->getdatalistall('m_karyawan',3,'intjabatan');
        $data['listleader']    = $this->modelapp->getdatalistall('m_karyawan',1,'intjabatan');
        $data['listmekanik']   = $this->modelapp->getdatalistall('m_karyawan',2,'intjabatan');


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
                    'intid'            => $resultData[0]->intid,
                    'vckode'           => $resultData[0]->vckode,
                    'dttanggal'        => date('m/d/Y', strtotime($resultData[0]->dttanggal)),
                    'intgedung'        => $resultData[0]->intgedung,
                    'intcell'          => $resultData[0]->intcell,
                    'intmesin'         => $resultData[0]->intmesin,
                    'intoperator'      => $resultData[0]->intoperator,
                    'intleader'        => $resultData[0]->intleader,
                    'intshift'        => $resultData[0]->intshift,
                    'intupdate'        => $this->session->intid,
                    'dtupdate'         => date('Y-m-d H:i:s')
                );

        $data['title']         = $this->title;
        $data['action']        = 'Edit';
        $data['controller']    = $this->controller;
        $data['dataDowntime']  = $dataDowntime;
        $data['listtype']      = (count($dataDowntime) == 0) ? $this->modelapp->getdatalist('m_type_downtime') : $downtimetype;
        $data['listtypelist']  = $listdowntime;
        $data['listgedung']    = $this->modelapp->getdatalist('m_gedung');
        $data['listjenis']     = $this->modelapp->getdatalist('m_jenis');
        $data['listsparepart'] = $this->modelapp->getdatalist('m_sparepart');
        $data['listshift']     = $this->modelapp->getdatalist('m_shift');
        $data['listcell']      = $this->modelapp->getdatalistall('m_cell', $resultData[0]->intgedung,'intgedung');
        $data['listmesin']     = $this->modelapp->getdatalistall('m_mesin', $resultData[0]->intcell,'intcell');
        $data['listoperator']  = $this->model->getdatakaryawan('m_karyawan', $resultData[0]->intgedung,3);
        $data['listleader']    = $this->model->getdatakaryawan('m_karyawan', $resultData[0]->intgedung,1);
        $data['listmekanik']   = $this->model->getdatakaryawan('m_karyawan', $resultData[0]->intgedung,2);

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
           $kodeunik    = $this->DowntimeModel->buat_kode();
           $vckode      = $kodeunik;
           $dttanggal   = $this->input->post('dttanggal');
           $intgedung   = $this->input->post('intgedung');
           $intcell     = $this->input->post('intcell');
           $intshift    = $this->input->post('intshift');
           $intoperator = $this->input->post('intoperator');
           $intleader   = $this->input->post('intleader');
           $intmesin    = $this->input->post('intmesin');

           $inttype_downtime = $this->input->post('inttype_downtime');
           $inttype_list     = $this->input->post('inttype_list');
           $dtmulai          = $this->input->post('dtmulai');
           $dtselesai        = $this->input->post('dtselesai');
           $vcmasalah        = $this->input->post('vcmasalah');
           $vctindakan       = $this->input->post('vctindakan');
           $intmekanik       = $this->input->post('intmekanik');
           $intsparepart     = $this->input->post('intsparepart');
           $intjumlah        = $this->input->post('intjumlah');
           $decjumlahdurasi  = 0;
           $decdurasi_mesin  = 0;
           $decdurasi_proses = 0;
           for ($i=0; $i < count($inttype_downtime); $i++) { 
               $decjumlahdurasi = $decjumlahdurasi + (abs(strtotime($dtselesai[$i]) - strtotime($dtmulai[$i])) / 60);
               if ($inttype_downtime[$i] == 1) {
                   $decdurasi_mesin  = $decdurasi_mesin + (abs(strtotime($dtselesai[$i]) - strtotime($dtmulai[$i])) / 60);
               } elseif ($inttype_downtime[$i] == 2) {
                   $decdurasi_proses  = $decdurasi_proses + (abs(strtotime($dtselesai[$i]) - strtotime($dtmulai[$i])) / 60);
               }
           }

            $data         = array(
                        'vckode'           => $vckode,
                        'dttanggal'        => date('Y-m-d',strtotime($dttanggal)),
                        'intgedung'        => $intgedung,
                        'intcell'          => $intcell,
                        'intshift'         => $intshift,
                        'intmesin'         => $intmesin,
                        'intoperator'      => $intoperator,
                        'intleader'        => $intleader,
                        'decjumlah_durasi' => $decjumlahdurasi,
                        'decdurasi_mesin'  => $decdurasi_mesin,
                        'decdurasi_proses' => $decdurasi_proses,
                        'intadd'           => $this->session->intid,
                        'dtadd'            => date('Y-m-d H:i:s'),
                        'intupdate'        => $this->session->intid,
                        'dtupdate'         => date('Y-m-d H:i:s'),
                        'intstatus'        => 1
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                for ($j=0; $j < count($inttype_downtime); $j++) { 
                    $decdurasi = (abs(strtotime($dtselesai[$j]) - strtotime($dtmulai[$j])) / 60); 
                    $data         = array(
                                    'intheader'        => $result,
                                    'inttype_downtime' => $inttype_downtime[$j],
                                    'inttype_list'     => $inttype_list[$j],
                                    'intmekanik'       => $intmekanik[$j],
                                    'dtmulai'          => $dtmulai[$j],
                                    'dtselesai'        => $dtselesai[$j],
                                    'decdurasi'        => $decdurasi,
                                    'vcmasalah'        => $vcmasalah[$j],
                                    'vctindakan'       => $vctindakan[$j],
                                    'intjumlah'        => $intjumlah[$j]
                                );

                    $this->modelapp->insertdata($this->table.'_detail',$data);
                }
                redirect(base_url($this->controller . '/view'));
            }
            
        } elseif ($tipe == 'Edit') {
            $dttanggal   = $this->input->post('dttanggal');
            $intgedung   = $this->input->post('intgedung');
            $intcell     = $this->input->post('intcell');
            $intshift    = $this->input->post('intshift');
            $intoperator = $this->input->post('intoperator');
            $intleader   = $this->input->post('intleader');
            $intmesin    = $this->input->post('intmesin');

            $inttype_downtime = $this->input->post('inttype_downtime');
            $inttype_list     = $this->input->post('inttype_list');
            $dtmulai          = $this->input->post('dtmulai');
            $dtselesai        = $this->input->post('dtselesai');
            $vcmasalah        = $this->input->post('vcmasalah');
            $vctindakan       = $this->input->post('vctindakan');
            $intmekanik       = $this->input->post('intmekanik');
            $intsparepart     = $this->input->post('intsparepart');
            $intjumlah        = $this->input->post('intjumlah');
            $decjumlahdurasi  = 0;
            $decdurasi_mesin  = 0;
            $decdurasi_proses = 0;
            for ($i=0; $i < count($inttype_downtime); $i++) { 
               $decjumlahdurasi = $decjumlahdurasi + (abs(strtotime($dtselesai[$i]) - strtotime($dtmulai[$i])) / 60);
               if ($inttype_downtime[$i] == 1) {
                   $decdurasi_mesin  = $decdurasi_mesin + (abs(strtotime($dtselesai[$i]) - strtotime($dtmulai[$i])) / 60);
               } elseif ($inttype_downtime[$i] == 2) {
                   $decdurasi_proses  = $decdurasi_proses + (abs(strtotime($dtselesai[$i]) - strtotime($dtmulai[$i])) / 60);
               }
            }
            $data    = array(
                    'dttanggal'        => date('Y-m-d',strtotime($dttanggal)),
                    'intgedung'        => $intgedung,
                    'intcell'          => $intcell,
                    'intshift'         => $intshift,
                    'intmesin'         => $intmesin,
                    'intoperator'      => $intoperator,
                    'intleader'        => $intleader,
                    'decjumlah_durasi' => $decjumlahdurasi,
                    'decdurasi_mesin'  => $decdurasi_mesin,
                    'decdurasi_proses' => $decdurasi_proses,
                    'intupdate'        => $this->session->intid,
                    'dtupdate'         => date('Y-m-d H:i:s')
                );
            
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                $this->modelapp->deletedata($this->table.'_detail',$intid,'intheader');
                for ($j=0; $j < count($inttype_downtime); $j++) { 
                    $decdurasi = (abs(strtotime($dtselesai[$j]) - strtotime($dtmulai[$j])) / 60); 
                    $data         = array(
                                    'intheader'        => $intid,
                                    'inttype_downtime' => $inttype_downtime[$j],
                                    'inttype_list'     => $inttype_list[$j],
                                    'intmekanik'       => $intmekanik[$j],
                                    'dtmulai'          => $dtmulai[$j],
                                    'dtselesai'        => $dtselesai[$j],
                                    'decdurasi'        => $decdurasi,
                                    'vcmasalah'        => $vcmasalah[$j],
                                    'vctindakan'       => $vctindakan[$j],
                                    'intjumlah'        => $intjumlah[$j]
                                );

                    $this->modelapp->insertdata($this->table.'_detail',$data);
                }
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
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Code");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Date");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Building");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Cell");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Downtime type");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Id Machine");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Problem");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Reason");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Solution");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "Stop Time");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "Restart Machine");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Duration");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Sparepart");
        $excel->setActiveSheetIndex(0)->setCellValue('P3', "Quantity");
        $excel->setActiveSheetIndex(0)->setCellValue('Q3', "Mechanic");
        $excel->setActiveSheetIndex(0)->setCellValue('R3', "Operator");
        $excel->setActiveSheetIndex(0)->setCellValue('S3', "Leader");
        $excel->setActiveSheetIndex(0)->setCellValue('T3', "Remark");



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

        $numrow = 4;
        $no = 0;
        foreach ($data as $dataset) {

           $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ++$no);
           $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vckode);
           $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date('d M Y', strtotime($dataset->dttanggal)));
           $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->vcgedung);
           $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->vccell);
           $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $dataset->vcjenis);
           $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $dataset->vcmesin);
           $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $dataset->vcmasalah);
           $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $dataset->vcpenyebab);
           $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $dataset->vctindakan);
           $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $dataset->tmulai_berhenti);
           $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $dataset->tmesin_siap);
           $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $dataset->decduration);
           $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $dataset->vcsparepart);
           $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $dataset->intjumlah);
           $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $dataset->vcmekanik);
           $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $dataset->vcoperator);
           $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $dataset->vcleader);
           $excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $dataset->vcketerangan);

           $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO");
           $excel->setActiveSheetIndex(0)->setCellValue('C3', "Code");
           $excel->setActiveSheetIndex(0)->setCellValue('D3', "Date");
           $excel->setActiveSheetIndex(0)->setCellValue('E3', "Building");
           $excel->setActiveSheetIndex(0)->setCellValue('F3', "Cell");
           $excel->setActiveSheetIndex(0)->setCellValue('G3', "Downtime type");
           $excel->setActiveSheetIndex(0)->setCellValue('H3', "Id Machine");
           $excel->setActiveSheetIndex(0)->setCellValue('I3', "Problem");
           $excel->setActiveSheetIndex(0)->setCellValue('J3', "Reason");
           $excel->setActiveSheetIndex(0)->setCellValue('K3', "Solution");
           $excel->setActiveSheetIndex(0)->setCellValue('L3', "Stop Time");
           $excel->setActiveSheetIndex(0)->setCellValue('M3', "Restart Machine");
           $excel->setActiveSheetIndex(0)->setCellValue('N3', "Duration");
           $excel->setActiveSheetIndex(0)->setCellValue('O3', "Sparepart");
           $excel->setActiveSheetIndex(0)->setCellValue('P3', "Quantity");
           $excel->setActiveSheetIndex(0)->setCellValue('Q3', "Mechanic");
           $excel->setActiveSheetIndex(0)->setCellValue('R3', "Operator");
           $excel->setActiveSheetIndex(0)->setCellValue('S3', "Leader");
           $excel->setActiveSheetIndex(0)->setCellValue('T3', "Remark");

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
