<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oee extends MY_Controller { 

    function __construct(){
        parent::__construct();
        $this->load->model('AppModel');
        $this->load->model('OeeModel');
        $this->model = $this->OeeModel;
        $this->modelapp = $this->AppModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }
 
    function view(){
        $intgedung        = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmesin         = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift         = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from             = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to               = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $plandowntime     = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $startupdt        = $this->modelapp->getappsetting('startup')[0]->vcvalue;
        $shutdowndt       = $this->modelapp->getappsetting('shutdown')[0]->vcvalue;
        
        $datediff = (strtotime($to) - strtotime($from))/(3600*24);

        $dataP = [];

        for ($i=0; $i <= $datediff ; $i++) { 
            $dt                = $i + 1;
            $date1             = date( "Y-m-d 07:00:00", strtotime( $from . ' ' ." +" . $i . " day" ) );
            $date2             = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
            $availabletime1    = 0;
            $availabletime2    = 0;
            $downtime1         = 0;
            $downtime2         = 0;
            $machinebreakdown1 = 0;
            $machinebreakdown2 = 0;
            $idletime1         = 0;
            $idletime2         = 0;
            $plannedstop1      = 0;
            $plannedstop2      = 0;
            $startup1          = 0;
            $startup2          = 0;
            $shutdown1         = 0;
            $shutdown2         = 0;
            $decct             = 0;
            $intactual         = 0;
            $intreject         = 0;
            $changeoverdt      = 0;

            // Get availabletime, Planed stop, startup, shutdown
            if ($intshift == 1) {
                $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                if (count($getjamkerja) == 0) {
                    $availabletime1 = 0;
                    $plannedstop1   = 0;
                    $startup1       = 0;
                    $shutdown1      = 0;
                } else {
                    $time           = $getjamkerja[0]->intjamkerja + $getjamkerja[0]->intjamlembur;
                    $istirahat      = 60;
                    $availabletime1 = $time - $istirahat;
                    $plannedstop1   = $plandowntime;
                    $startup1       = $startupdt;
                    $shutdown1      = $shutdowndt;
                    // Data Downtime
                    $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                    $downtime1         = $datadt[0]->decdurasi;
                    $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                    $idletime1         = $datadt[0]->decprosestime;
                    // Data Output
                    $dataoutput        = $this->model->getdataoutput($date1, $date2, $intmesin, 1);
                    $decct             = $dataoutput[0]->decct2;
                    $intactual         = $dataoutput[0]->intactual;
                    $intreject         = $dataoutput[0]->intreject;
                    // Change Over
                    $changeoverdt      = $this->model->getdataoutputkomponen($date1,$date2,$intmesin,1);
                }

            } elseif ($intshift == 2) {
                $datalogout  = $this->model->getlogout($date1, $date2, $intmesin, 2);
                $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                if (count($getjamkerja) == 0) {
                    $availabletime2 = 0;
                    $plannedstop2   = 0;
                    $startup2       = 0;
                    $shutdown2      = 0;
                } else {
                    // $st             = date( "Y-m-d " . $datalogout[0]->dtmasuk, strtotime( $from . ' ' ." +" . $i . " day" ) );
                    // $fs             = date( "Y-m-d " . $datalogout[0]->dtpulang, strtotime( $from . ' ' ." +" . $dt . " day" ) );
                    // $time        = (strtotime($fs) - strtotime($st))/60;
                    $time           = $getjamkerja[0]->intjamkerja + $getjamkerja[0]->intjamlembur;
                    $istirahat      = ($getjamkerja[0]->intjamlembur >= 180) ? 120 : 60;
                    $availabletime2 = $time - $istirahat;
                    $plannedstop2   = $plandowntime;
                    $startup2       = $startupdt;
                    $shutdown2      = $shutdowndt;
                    // Data Downtime
                    $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 2);
                    $downtime2         = $datadt[0]->decdurasi;
                    $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                    $idletime2         = $datadt[0]->decprosestime;
                    // Data Output
                    $dataoutput        = $this->model->getdataoutput($date1, $date2, $intmesin, 2);
                    $decct             = $dataoutput[0]->decct2;
                    $intactual         = $dataoutput[0]->intactual;
                    $intreject         = $dataoutput[0]->intreject;
                    // Change Over
                    $changeoverdt      = $this->model->getdataoutputkomponen($date1,$date2,$intmesin,2);
                }

            } else {
                $datalogout1 = $this->model->getlogout($date1, $date2, $intmesin, 1);
                $datalogout2 = $this->model->getlogout($date1, $date2, $intmesin, 2);
                $getjamkerja1 = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                $getjamkerja2 = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                if (count($getjamkerja1) == 0) {
                    $availabletime1 = 0;
                    $plannedstop1   = 0;
                    $startup1       = 0;
                    $shutdown1      = 0;
                } else {
                    $time           = $getjamkerja1[0]->intjamkerja + $getjamkerja1[0]->intjamlembur;
                    $istirahat      = 60;
                    $availabletime1 = $time - $istirahat;
                    $plannedstop1   = $plandowntime;
                    $startup1       = $startupdt;
                    $shutdown1      = $shutdowndt;
                    // Data Downtime
                    $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                    $downtime1         = $datadt[0]->decdurasi;
                    $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                    $idletime1         = $datadt[0]->decprosestime;
                }

                if (count($getjamkerja2) == 0) {
                    $availabletime2 = 0;
                    $plannedstop2   = 0;
                    $startup2       = 0;
                    $shutdown2      = 0;
                } else {
                    $time           = $getjamkerja2[0]->intjamkerja + $getjamkerja2[0]->intjamlembur;
                    $istirahat      = ($getjamkerja2[0]->intjamlembur >= 180) ? 120 : 60;
                    $availabletime2 = $time - $istirahat;
                    $plannedstop2   = $plandowntime;
                    $startup2       = $startupdt;
                    $shutdown2      = $shutdowndt;
                    // Data Downtime
                    $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 2);
                    $downtime2         = $datadt[0]->decdurasi;
                    $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                    $idletime2         = $datadt[0]->decprosestime;
                }

                // Data Output
                $dataoutput        = $this->model->getdataoutputall($date1, $date2, $intmesin);
                $decct             = $dataoutput[0]->decct2;
                $intactual         = $dataoutput[0]->intactual;
                $intreject         = $dataoutput[0]->intreject;
                // Change Over
                $changeoverdt      = $this->model->getdataoutputkomponenall($date1,$date2,$intmesin);
            }

            $availabletime      = $availabletime1 + $availabletime2;
            $plannedstop        = $plannedstop1 + $plannedstop2;
            $plannedproduction  = $availabletime - $plannedstop;
            $startup            = $startup1 + $startup2;
            $shutdown           = $shutdown1 + $shutdown2;
            $machinebreakdown   = $machinebreakdown1 + $machinebreakdown2;
            $idletime           = $idletime1 + $idletime2;
            $downtime           = $downtime1 + $downtime2;
            $changeover         = $changeoverdt * 5;
            $totaldowntime      = $downtime +  $startup + $shutdown + $changeover;
            $runtime            = $plannedproduction - $totaldowntime;
            $theoriticalct      = $decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $intactual;
            $defectiveproduct   = $intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($actualoutput - $defectiveproduct)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            // echo $availabletime . '|' . $plannedproduction . '|' . $startup . '|' . $shutdown . '|' . $machinebreakdown . '|' . $idletime . '|' . $downtime . '|' . $changeover . '|' . $totaldowntime . '|' . $runtime . '<br><br>';

            $tempdata = array(
                        'dttanggal'          => $date1,
                        'availabletime'      => $availabletime,
                        'plannedstop'        => $plannedstop,
                        'startup'            => $startup,
                        'shutdown'           => $shutdown,
                        'changeover'         => $changeoverdt,
                        'plannedproduction'  => $plannedproduction,
                        'machinebreakdown'   => $machinebreakdown,
                        'idletime'           => $idletime,
                        'totaldowntime'      => $totaldowntime,
                        'runtime'            => $runtime,
                        'theoriticalct'      => $theoriticalct,
                        'theoriticaloutput'  => $theoriticaloutput,
                        'actualoutput'       => $actualoutput,
                        'defectiveproduct'   => $defectiveproduct,
                        'availabilityfactor' => $availabilityfactor,
                        'performancefactor'  => $performancefactor,
                        'qualityfactor'      => $qualityfactor,
                        'oee'                => $oee
                    );
            array_push($dataP, $tempdata);
        }

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['intgedung']  = $intgedung;
        $data['intmesin']   = $intmesin;
        $data['intshift']   = $intshift;
        $data['from']       = date('m/d/Y',strtotime($from));
        $data['to']         = date('m/d/Y',strtotime($to));

        // $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword,$from,$to);
        $data['dataP']      = $dataP;
        $data['listgedung'] = $this->modelapp->getdatalistall('m_gedung');
        $data['listmesin']  = $this->model->getlistmesin($intgedung);
        $data['listshift']  = $this->modelapp->getdatalistall('m_shift');

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function exportexcel(){
        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','2048M');
        $intgedung        = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmesin         = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift         = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from             = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to               = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $plandowntime     = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $startupdt        = $this->modelapp->getappsetting('startup')[0]->vcvalue;
        $shutdowndt       = $this->modelapp->getappsetting('shutdown')[0]->vcvalue;
        $datamesin        = $this->model->getdatamesin($intgedung);
        $datagedung       = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;

        $datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intmesin > 0) {
            $datamesin = $this->model->getmesin($intmesin);
            $judul2    = $datamesin[0]->vckode . ' - ' . $datamesin[0]->vcnama;
        } else {
            $judul2 = 'All Machine';
        }

        if ($intgedung > 0) {
            $judul = $datagedung[0]->vcnama;
        } else {
            $judul  = 'All Building';
            $judul2 = '';
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();
        $objWorksheet = $excel->getActiveSheet();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report OEE " . $judul . " " . $judul2)
                     ->setSubject("Report OEE" . $judul . " " . $judul2)
                     ->setDescription("Report OEE" . $judul . " " . $judul2)
                     ->setKeywords("Report OEE" . $judul . " " . $judul2);

        // variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
          'font' => array('bold' => true), // Set font nya jadi bold
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $loop = 0;
        foreach ($datamesin as $mesin) {
            if ($loop > 0) {
                $excel->createSheet();
            }
            $intmesin = $mesin->intid;

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report OEE " . $mesin->vckode . ' - ' . $mesin->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:G1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('A3', "Date");
            $excel->setActiveSheetIndex($loop)->setCellValue('B3', "Available Time (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('C3', "Planned Stop (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('D3', "Planned Production Time (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('E3', "Start Up (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('F3', "Shutdown (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('G3', "Change Over (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('H3', "Machine Breakdown (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('I3', "Idle Time (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('J3', "Total Downtime (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('K3', "Operating / runtime (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('L3', "Theoritical CT (pairs/min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('M3', "Theoritical Output (based on CT)");
            $excel->setActiveSheetIndex($loop)->setCellValue('N3', "Actual Output");
            $excel->setActiveSheetIndex($loop)->setCellValue('O3', "Defective Product");
            $excel->setActiveSheetIndex($loop)->setCellValue('P3', "Availability Factor");
            $excel->setActiveSheetIndex($loop)->setCellValue('Q3', "Performance Factor");
            $excel->setActiveSheetIndex($loop)->setCellValue('R3', "Quality Factor");
            $excel->setActiveSheetIndex($loop)->setCellValue('S3', "OEE");

            $excel->getActiveSheet()->getStyle('A3:S3')->getAlignment()->setWrapText(true); 

            $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

            $excel->setActiveSheetIndex($loop)->getStyle('A3')->applyFromArray($style_col);
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

            $numrow = 4;
            $no = 0;

            for ($i=0; $i <= $datediff ; $i++) {
                $intgedungspecial = 0;
                if ($intgedung > 0) {
                    foreach ($datagedung as $dtgedung) {
                        if ($dtgedung->intspesial == 1) {
                            $intgedungspecial = $dtgedung->intid;
                        }

                        if ($intgedung == $intgedungspecial) {
                        $worksift1 = $worksift1special;
                        }
                    }
                }

                $dt                = $i + 1;
                $date1             = date( "Y-m-d " . $worksift1, strtotime( $from . ' ' ." +" . $i . " day" ) );
                $date2             = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
                $availabletime1    = 0;
                $availabletime2    = 0;
                $downtime1         = 0;
                $downtime2         = 0;
                $machinebreakdown1 = 0;
                $machinebreakdown2 = 0;
                $idletime1         = 0;
                $idletime2         = 0;
                $plannedstop1      = 0;
                $plannedstop2      = 0;
                $startup1          = 0;
                $startup2          = 0;
                $shutdown1         = 0;
                $shutdown2         = 0;
                $decct             = 0;
                $intactual         = 0;
                $intreject         = 0;
                $changeoverdt      = 0;

                // Get availabletime, Planed stop, startup, shutdown
                if ($intshift == 1) {
                    $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                    if (count($getjamkerja) == 0) {
                        $availabletime1 = 0;
                        $plannedstop1   = 0;
                        $startup1       = 0;
                        $shutdown1      = 0;
                    } else {
                        $time           = $getjamkerja[0]->intjamkerja + $getjamkerja[0]->intjamlembur;
                        $istirahat      = 60;
                        $availabletime1 = $time - $istirahat;
                        $plannedstop1   = $plandowntime;
                        $startup1       = $startupdt;
                        $shutdown1      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        $downtime1         = $datadt[0]->decdurasi;
                        $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                        $idletime1         = $datadt[0]->decprosestime;
                        // Data Output
                        $dataoutput        = $this->model->getdataoutput($date1, $date2, $intmesin, 1);
                        $decct             = $dataoutput[0]->decct2;
                        $intactual         = $dataoutput[0]->intactual;
                        $intreject         = $dataoutput[0]->intreject;
                        // Change Over
                        $changeoverdt      = $this->model->getdataoutputkomponen($date1,$date2,$intmesin,1);
                    }

                } elseif ($intshift == 2) {
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, 2);
                    $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                    if (count($getjamkerja) == 0) {
                        $availabletime2 = 0;
                        $plannedstop2   = 0;
                        $startup2       = 0;
                        $shutdown2      = 0;
                    } else {
                        $time           = $getjamkerja[0]->intjamkerja + $getjamkerja[0]->intjamlembur;
                        $istirahat      = ($getjamkerja[0]->intjamlembur >= 180) ? 120 : 60;
                        $availabletime2 = $time - $istirahat;
                        $plannedstop2   = $plandowntime;
                        $startup2       = $startupdt;
                        $shutdown2      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 2);
                        $downtime2         = $datadt[0]->decdurasi;
                        $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                        $idletime2         = $datadt[0]->decprosestime;
                        // Data Output
                        $dataoutput        = $this->model->getdataoutput($date1, $date2, $intmesin, 2);
                        $decct             = $dataoutput[0]->decct2;
                        $intactual         = $dataoutput[0]->intactual;
                        $intreject         = $dataoutput[0]->intreject;
                        // Change Over
                        $changeoverdt      = $this->model->getdataoutputkomponen($date1,$date2,$intmesin,2);
                    }

                } else {
                    $getjamkerja1 = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                    $getjamkerja2 = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                    if (count($getjamkerja1) == 0) {
                        $availabletime1 = 0;
                        $plannedstop1   = 0;
                        $startup1       = 0;
                        $shutdown1      = 0;
                    } else {
                        $time           = $getjamkerja1[0]->intjamkerja + $getjamkerja1[0]->intjamlembur;
                        $istirahat      = 60;
                        $availabletime1 = $time - $istirahat;
                        $plannedstop1   = $plandowntime;
                        $startup1       = $startupdt;
                        $shutdown1      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        $downtime1         = $datadt[0]->decdurasi;
                        $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                        $idletime1         = $datadt[0]->decprosestime;
                    }

                    if (count($getjamkerja2) == 0) {
                        $availabletime2 = 0;
                        $plannedstop2   = 0;
                        $startup2       = 0;
                        $shutdown2      = 0;
                    } else {
                        $time           = $getjamkerja2[0]->intjamkerja + $getjamkerja2[0]->intjamlembur;
                        $istirahat      = ($getjamkerja2[0]->intjamlembur >= 180) ? 120 : 60;
                        $availabletime2 = $time - $istirahat;
                        $plannedstop2   = $plandowntime;
                        $startup2       = $startupdt;
                        $shutdown2      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 2);
                        $downtime2         = $datadt[0]->decdurasi;
                        $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                        $idletime2         = $datadt[0]->decprosestime;
                    }

                    // Data Output
                    $dataoutput        = $this->model->getdataoutputall($date1, $date2, $intmesin);
                    $decct             = $dataoutput[0]->decct2;
                    $intactual         = $dataoutput[0]->intactual;
                    $intreject         = $dataoutput[0]->intreject;
                    // Change Over
                    $changeoverdt      = $this->model->getdataoutputkomponenall($date1,$date2,$intmesin);
                }
                
                $availabletime      = $availabletime1 + $availabletime2;
                $plannedstop        = $plannedstop1 + $plannedstop2;
                $plannedproduction  = $availabletime - $plannedstop;
                $startup            = $startup1 + $startup2;
                $shutdown           = $shutdown1 + $shutdown2;
                $machinebreakdown   = $machinebreakdown1 + $machinebreakdown2;
                $idletime           = $idletime1 + $idletime2;
                $downtime           = $downtime1 + $downtime2;
                $changeover         = $changeoverdt * 5;
                $totaldowntime      = $downtime +  $startup + $shutdown + $changeover;
                $runtime            = $plannedproduction - $totaldowntime;
                $theoriticalct      = $decct;
                $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $actualoutput       = $intactual;
                $defectiveproduct   = $intreject;
                $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
                $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($actualoutput - $defectiveproduct)/$actualoutput;
                $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                $excel->setActiveSheetIndex($loop)->setCellValue('A'.$numrow, date('d M Y',strtotime($date1)));
                $excel->setActiveSheetIndex($loop)->setCellValue('B'.$numrow, $availabletime);
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, ($availabletime == 0) ? 0 : $plannedstop);
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, ($availabletime == 0) ? 0 : $plannedproduction);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, ($availabletime == 0) ? 0 : $startup);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, ($availabletime == 0) ? 0 : $shutdown);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, ($availabletime == 0) ? 0 : $changeover);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, ($availabletime == 0) ? 0 : $machinebreakdown);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, ($availabletime == 0) ? 0 : $idletime);
                $excel->setActiveSheetIndex($loop)->setCellValue('J'.$numrow, ($availabletime == 0) ? 0 : $totaldowntime);
                $excel->setActiveSheetIndex($loop)->setCellValue('K'.$numrow, ($availabletime == 0) ? 0 : $runtime);
                $excel->setActiveSheetIndex($loop)->setCellValue('L'.$numrow, ($availabletime == 0) ? 0 : $theoriticalct);
                $excel->setActiveSheetIndex($loop)->setCellValue('M'.$numrow, ($availabletime == 0) ? 0 : $theoriticaloutput);
                $excel->setActiveSheetIndex($loop)->setCellValue('N'.$numrow, ($availabletime == 0) ? 0 : $actualoutput);
                $excel->setActiveSheetIndex($loop)->setCellValue('O'.$numrow, ($availabletime == 0) ? 0 : $defectiveproduct);
                $excel->setActiveSheetIndex($loop)->setCellValue('P'.$numrow, ($availabletime == 0) ? 0 : $availabilityfactor);
                $excel->setActiveSheetIndex($loop)->setCellValue('Q'.$numrow, ($availabletime == 0) ? 0 : $performancefactor);
                $excel->setActiveSheetIndex($loop)->setCellValue('R'.$numrow, ($availabletime == 0) ? 0 : $qualityfactor);
                $excel->setActiveSheetIndex($loop)->setCellValue('S'.$numrow, ($availabletime == 0) ? 0 : $oee);

                $excel->getActiveSheet()->getStyle('P'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );
                $excel->getActiveSheet()->getStyle('Q'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );
                $excel->getActiveSheet()->getStyle('R'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );
                $excel->getActiveSheet()->getStyle('S'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );

                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
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
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('E')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('F')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('G')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('H')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('I')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('J')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('K')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('L')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('M')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('N')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('O')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('P')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('Q')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('R')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('S')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('T')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('U')->setWidth('15');
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

            // Set orientasi kertas jadi LANDSCAPE
            $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

            // Set judul file excel nya
            $excel->getActiveSheet($loop)->setTitle($mesin->vckode . '-' . $mesin->vcnama);
            $loop++;
        }
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report OEE ' . $judul . ' ' . $judul2 . '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');

    }

    function exportexcel2(){
        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','2048M');
        $intgedung        = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmesin         = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift         = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from             = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to               = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $datamesin        = $this->model->getdatamesin($intgedung);
        $datagedung       = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $plandowntime     = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $startupdt        = $this->modelapp->getappsetting('startup')[0]->vcvalue;
        $shutdowndt       = $this->modelapp->getappsetting('shutdown')[0]->vcvalue;
        $break            = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $jamkerjaspesial  = $this->modelapp->getappsetting('jamkerja_spesial')[0]->vcvalue;
        $kalibrasi        = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
        $meeting          = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
        $sm               = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;

        $datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intmesin > 0) {
            $datamesin = $this->model->getmesin($intmesin);
            $judul2    = $datamesin[0]->vckode . ' - ' . $datamesin[0]->vcnama;
        } else {
            $judul2 = 'All Machine';
        }

        if ($intgedung > 0) {
            $judul = $datagedung[0]->vcnama;
        } else {
            $judul  = 'All Building';
            $judul2 = '';
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report OEE Monitoring " . $judul . " " . $judul2)
                     ->setSubject("Report OEE Monitoring " . $judul . " " . $judul2)
                     ->setDescription("Report OEE Monitoring " . $judul . " " . $judul2)
                     ->setKeywords("Report OEE Monitoring " . $judul . " " . $judul2);

        // variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
        'font'      => array('bold' => true),   // Set font nya jadi bold
        'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,   // Set text jadi ditengah secara horizontal (center)
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER      // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $loop = 0;
        foreach ($datamesin as $mesin) {
            if ($loop > 0) {
                $excel->createSheet();
            }
            $intmesin = $mesin->intid;

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report OEE Monitoring " . $mesin->vckode . ' - ' . $mesin->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:U1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('A3', "Date");
            $excel->setActiveSheetIndex($loop)->setCellValue('B3', "Available Time (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('C3', "Planned Stop (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('D3', "Planned Stop Remaining (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('E3', "Planned Stop Over (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('F3', "Planned Production Time (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('G3', "Start Up (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('H3', "Shutdown (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('I3', "Change Over (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('J3', "Machine Breakdown (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('K3', "Idle Time (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('L3', "Total Downtime (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('M3', "Operating / runtime (min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('N3', "Theoritical CT (pairs/min)");
            $excel->setActiveSheetIndex($loop)->setCellValue('O3', "Theoritical Output (based on CT)");
            $excel->setActiveSheetIndex($loop)->setCellValue('P3', "Actual Output");
            $excel->setActiveSheetIndex($loop)->setCellValue('Q3', "Defective Product");
            $excel->setActiveSheetIndex($loop)->setCellValue('R3', "Availability Factor");
            $excel->setActiveSheetIndex($loop)->setCellValue('S3', "Performance Factor");
            $excel->setActiveSheetIndex($loop)->setCellValue('T3', "Quality Factor");
            $excel->setActiveSheetIndex($loop)->setCellValue('U3', "OEE");

            $excel->getActiveSheet()->getStyle('A3:S3')->getAlignment()->setWrapText(true); 

            $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

            $excel->setActiveSheetIndex($loop)->getStyle('A3')->applyFromArray($style_col);
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

            $numrow = 4;
            $no = 0;

            for ($i=0; $i <= $datediff ; $i++) {
                $intgedungspecial = 0;
                if ($intgedung > 0) {
                    foreach ($datagedung as $dtgedung) {
                        if ($dtgedung->intspesial == 1) {
                            $intgedungspecial = $dtgedung->intid;
                        }

                        if ($intgedung == $intgedungspecial) {
                        $worksift1 = $worksift1special;
                        }
                    }
                }

                $dt                = $i + 1;
                $date1             = date( "Y-m-d " . $worksift1, strtotime( $from . ' ' ." +" . $i . " day" ) );
                $date2             = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
                $availabletime1    = 0;
                $availabletime2    = 0;
                $downtime1         = 0;
                $downtime2         = 0;
                $machinebreakdown1 = 0;
                $machinebreakdown2 = 0;
                $idletime1         = 0;
                $idletime2         = 0;
                $plannedstop1      = 0;
                $plannedstop2      = 0;
                $planpd1           = 0;
                $planpd2           = 0;
                $plandt1           = 0;
                $plandt2           = 0;
                $startup1          = 0;
                $startup2          = 0;
                $shutdown1         = 0;
                $shutdown2         = 0;
                $decct             = 0;
                $intactual         = 0;
                $intreject         = 0;
                $inttarget         = 0;
                $changeoverdt      = 0;

                // Get availabletime, Planed stop, startup, shutdown
                if ($intshift == 1) {
                    $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                    if (count($getjamkerja) == 0) {
                        $availabletime1 = 0;
                        $plannedstop1   = 0;
                        $startup1       = 0;
                        $shutdown1      = 0;
                    } else {
                        if ($jamkerjaspesial > 0) {
                            $time = $jamkerjaspesial;
                            $istirahat = 0;
                        } else {
                            $time      = $getjamkerja[0]->intjamkerja + $getjamkerja[0]->intjamlembur;
                            $istirahat = ($getjamkerja[0]->intjamlembur >= 180) ? 120 : $break;
                        }
                        
                        $availabletime1 = $time - $istirahat;
                        $startup1       = $startupdt;
                        $shutdown1      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        //kalibrasi
                        $pdkalibrasi   = 0;
                        $dtkalibrasi   = 0;
                        $plankalibrasi = 0;   
                        $deckalibrasi  = $datadt[0]->deckalibrasi;
                        if ($deckalibrasi > 0) {
                                if ($deckalibrasi <= $kalibrasi) {
                                $kalibrasitemp = $kalibrasi - $deckalibrasi;
                                $pdkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $deckalibrasi;
                            } elseif ($deckalibrasi > $kalibrasi ) {
                                $kalibrasitemp = $deckalibrasi - $kalibrasi;
                                $dtkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $kalibrasi;
                            }
                        }
                        
                        //meeting
                        $pdmeeting   = 0;
                        $dtmeeting   = 0;
                        $planmeeting = 0;   
                        $decmeeting  = $datadt[0]->decmeeting;
                        if ($decmeeting > 0) {
                                if ($decmeeting <= $meeting) {
                                $meetingtemp = $meeting - $decmeeting;
                                $pdmeeting = $meetingtemp;
                                $planmeeting = $decmeeting;
                            } elseif ($decmeeting > $meeting ) {
                                $meetingtemp = $decmeeting - $meeting;
                                $dtmeeting = $meetingtemp;
                                $planmeeting = $meeting;
                            }
                        }
                        
                        //self maintenance
                        $pdsm   = 0;
                        $dtsm   = 0;
                        $plansm = 0;   
                        $decsm  = $datadt[0]->decsm;
                        if ($decsm > 0) {
                                if ($decsm <= $sm) {
                                $smtemp = $sm - $decsm;
                                $pdsm = $smtemp;
                                $plansm = $decsm;
                            } elseif ($decsm > $sm ) {
                                $smtemp = $decsm - $sm;
                                $dtsm = $smtemp;
                                $plansm = $sm;
                            }
                        }
                        
                        $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
                        $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
                        $totplan = $plankalibrasi + $planmeeting + $plansm;

                        $planpd1           = $totpd;
                        $plandt1           = $totdt;
                        $plannedstop1      = $totplan;
                        $downtime1         = $datadt[0]->decdurasi;
                        $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                        $idletime1         = $datadt[0]->decprosestime;
                        // Data Output
                        $dataoutput        = $this->model->getdataoutput2($date1, $date2, $intmesin, 1);
                        $decct             = $dataoutput[0]->decct;
                        $intactual         = $dataoutput[0]->intactual;
                        $intreject         = $dataoutput[0]->intreject;
                        $inttarget         = $dataoutput[0]->inttarget;
                        // Change Over
                        $changeoverdt      = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    }

                } elseif ($intshift == 2) {
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, 2);
                    $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                    if (count($getjamkerja) == 0) {
                        $availabletime2 = 0;
                        $plannedstop2   = 0;
                        $startup2       = 0;
                        $shutdown2      = 0;
                    } else {
                        $time           = $getjamkerja[0]->intjamkerja + $getjamkerja[0]->intjamlembur;
                        $istirahat      = ($getjamkerja[0]->intjamlembur >= 180) ? 120 : $break;
                        $availabletime2 = $time - $istirahat;
                        $startup2       = $startupdt;
                        $shutdown2      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 2);
                        //kalibrasi
                        $pdkalibrasi   = 0;
                        $dtkalibrasi   = 0;
                        $plankalibrasi = 0;   
                        $deckalibrasi  = $datadt[0]->deckalibrasi;
                        if ($deckalibrasi > 0) {
                                if ($deckalibrasi <= $kalibrasi) {
                                $kalibrasitemp = $kalibrasi - $deckalibrasi;
                                $pdkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $deckalibrasi;
                            } elseif ($deckalibrasi > $kalibrasi ) {
                                $kalibrasitemp = $deckalibrasi - $kalibrasi;
                                $dtkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $kalibrasi;
                            }
                        }
                        
                        //meeting
                        $pdmeeting   = 0;
                        $dtmeeting   = 0;
                        $planmeeting = 0;   
                        $decmeeting  = $datadt[0]->decmeeting;
                        if ($decmeeting > 0) {
                                if ($decmeeting <= $meeting) {
                                $meetingtemp = $meeting - $decmeeting;
                                $pdmeeting = $meetingtemp;
                                $planmeeting = $decmeeting;
                            } elseif ($decmeeting > $meeting ) {
                                $meetingtemp = $decmeeting - $meeting;
                                $dtmeeting = $meetingtemp;
                                $planmeeting = $meeting;
                            }
                        }
                        
                        //self maintenance
                        $pdsm   = 0;
                        $dtsm   = 0;
                        $plansm = 0;   
                        $decsm  = $datadt[0]->decsm;
                        if ($decsm > 0) {
                                if ($decsm <= $sm) {
                                $smtemp = $sm - $decsm;
                                $pdsm = $smtemp;
                                $plansm = $decsm;
                            } elseif ($decsm > $sm ) {
                                $smtemp = $decsm - $sm;
                                $dtsm = $smtemp;
                                $plansm = $sm;
                            }
                        }
                        
                        $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
                        $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
                        $totplan = $plankalibrasi + $planmeeting + $plansm;

                        $planpd2           = $totpd;
                        $plandt2           = $totdt;
                        $plannedstop2      = $totplan;
                        $downtime2         = $datadt[0]->decdurasi;
                        $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                        $idletime2         = $datadt[0]->decprosestime;
                        // Data Output
                        $dataoutput        = $this->model->getdataoutput2($date1, $date2, $intmesin, 2);
                        $decct             = $dataoutput[0]->decct;
                        $intactual         = $dataoutput[0]->intactual;
                        $intreject         = $dataoutput[0]->intreject;
                        $inttarget         = $dataoutput[0]->inttarget;
                        // Change Over
                        $changeoverdt      = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,2);
                    }

                } else {
                    $getjamkerja1 = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                    $getjamkerja2 = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                    if (count($getjamkerja1) == 0) {
                        $availabletime1 = 0;
                        $plannedstop1   = 0;
                        $startup1       = 0;
                        $shutdown1      = 0;
                    } else {
                        $time           = $getjamkerja1[0]->intjamkerja + $getjamkerja1[0]->intjamlembur;
                        $istirahat      = ($getjamkerja1[0]->intjamlembur >= 180) ? 120 : $break;
                        $availabletime1 = $time - $istirahat;
                        $startup1       = $startupdt;
                        $shutdown1      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        //kalibrasi
                        $pdkalibrasi   = 0;
                        $dtkalibrasi   = 0;
                        $plankalibrasi = 0;   
                        $deckalibrasi  = $datadt[0]->deckalibrasi;
                        if ($deckalibrasi > 0) {
                                if ($deckalibrasi <= $kalibrasi) {
                                $kalibrasitemp = $kalibrasi - $deckalibrasi;
                                $pdkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $deckalibrasi;
                            } elseif ($deckalibrasi > $kalibrasi ) {
                                $kalibrasitemp = $deckalibrasi - $kalibrasi;
                                $dtkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $kalibrasi;
                            }
                        }
                        
                        //meeting
                        $pdmeeting   = 0;
                        $dtmeeting   = 0;
                        $planmeeting = 0;   
                        $decmeeting  = $datadt[0]->decmeeting;
                        if ($decmeeting > 0) {
                                if ($decmeeting <= $meeting) {
                                $meetingtemp = $meeting - $decmeeting;
                                $pdmeeting = $meetingtemp;
                                $planmeeting = $decmeeting;
                            } elseif ($decmeeting > $meeting ) {
                                $meetingtemp = $decmeeting - $meeting;
                                $dtmeeting = $meetingtemp;
                                $planmeeting = $meeting;
                            }
                        }
                        
                        //self maintenance
                        $pdsm   = 0;
                        $dtsm   = 0;
                        $plansm = 0;   
                        $decsm  = $datadt[0]->decsm;
                        if ($decsm > 0) {
                                if ($decsm <= $sm) {
                                $smtemp = $sm - $decsm;
                                $pdsm = $smtemp;
                                $plansm = $decsm;
                            } elseif ($decsm > $sm ) {
                                $smtemp = $decsm - $sm;
                                $dtsm = $smtemp;
                                $plansm = $sm;
                            }
                        }
                        
                        $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
                        $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
                        $totplan = $plankalibrasi + $planmeeting + $plansm;

                        $planpd1           = $totpd;
                        $plandt1           = $totdt;
                        $plannedstop1      = $totplan;
                        $downtime1         = $datadt[0]->decdurasi;
                        $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                        $idletime1         = $datadt[0]->decprosestime;
                    }

                    if (count($getjamkerja2) == 0) {
                        $availabletime2 = 0;
                        $plannedstop2   = 0;
                        $startup2       = 0;
                        $shutdown2      = 0;
                    } else {
                        $time           = $getjamkerja2[0]->intjamkerja + $getjamkerja2[0]->intjamlembur;
                        $istirahat      = ($getjamkerja2[0]->intjamlembur >= 180) ? 120 : $break;
                        $availabletime2 = $time - $istirahat;
                        $startup2       = $startupdt;
                        $shutdown2      = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 2);
                        //kalibrasi
                        $pdkalibrasi   = 0;
                        $dtkalibrasi   = 0;
                        $plankalibrasi = 0;   
                        $deckalibrasi  = $datadt[0]->deckalibrasi;
                        if ($deckalibrasi > 0) {
                                if ($deckalibrasi <= $kalibrasi) {
                                $kalibrasitemp = $kalibrasi - $deckalibrasi;
                                $pdkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $deckalibrasi;
                            } elseif ($deckalibrasi > $kalibrasi ) {
                                $kalibrasitemp = $deckalibrasi - $kalibrasi;
                                $dtkalibrasi = $kalibrasitemp;
                                $plankalibrasi = $kalibrasi;
                            }
                        }
                        
                        //meeting
                        $pdmeeting   = 0;
                        $dtmeeting   = 0;
                        $planmeeting = 0;   
                        $decmeeting  = $datadt[0]->decmeeting;
                        if ($decmeeting > 0) {
                                if ($decmeeting <= $meeting) {
                                $meetingtemp = $meeting - $decmeeting;
                                $pdmeeting = $meetingtemp;
                                $planmeeting = $decmeeting;
                            } elseif ($decmeeting > $meeting ) {
                                $meetingtemp = $decmeeting - $meeting;
                                $dtmeeting = $meetingtemp;
                                $planmeeting = $meeting;
                            }
                        }
                        
                        //self maintenance
                        $pdsm   = 0;
                        $dtsm   = 0;
                        $plansm = 0;   
                        $decsm  = $datadt[0]->decsm;
                        if ($decsm > 0) {
                                if ($decsm <= $sm) {
                                $smtemp = $sm - $decsm;
                                $pdsm = $smtemp;
                                $plansm = $decsm;
                            } elseif ($decsm > $sm ) {
                                $smtemp = $decsm - $sm;
                                $dtsm = $smtemp;
                                $plansm = $sm;
                            }
                        }
                        
                        $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
                        $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
                        $totplan = $plankalibrasi + $planmeeting + $plansm;

                        $planpd2           = $totpd;
                        $plandt2           = $totdt;
                        $plannedstop2      = $totplan;
                        $downtime2         = $datadt[0]->decdurasi;
                        $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                        $idletime2         = $datadt[0]->decprosestime;
                    }

                    // Data Output
                    $dataoutput        = $this->model->getdataoutputall2($date1, $date2, $intmesin);
                    $decct             = $dataoutput[0]->decct;
                    $intactual         = $dataoutput[0]->intactual;
                    $intreject         = $dataoutput[0]->intreject;
                    $inttarget         = $dataoutput[0]->inttarget;
                    // Change Over
                    $changeoverdt      = $this->model->getdataoutputkomponenall2($date1,$date2,$intmesin);
                }
                
                $availabletime       = ($availabletime1 + $availabletime2);
                $plannedstop         = $plannedstop1 + $plannedstop2;
                $planpd              = $planpd1 + $planpd2;
                $plandt              = $plandt1 + $plandt2;
                $plannedproduction   = $availabletime - $plannedstop;
                $startup             = $startup1 + $startup2;
                $shutdown            = $shutdown1 + $shutdown2;
                $machinebreakdown    = $machinebreakdown1 + $machinebreakdown2;
                $idletime            = $idletime1 + $idletime2;
                $downtime            = ($downtime1 + $downtime2);
                $changeover          = 0;
                //$changeover        = $changeoverdt * 5;
                $totaldowntime       = $downtime + $plandt;
                $runtime             = $plannedproduction - $totaldowntime;
                $theoriticalct       = $decct;
                //$theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput   = $inttarget;
                $actualoutput        = $intactual;
                $defectiveproduct    = $intreject;
                $availabilityfactor  = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
                $performancefactor   = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor       = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($actualoutput - $defectiveproduct)/$actualoutput;
                $oee                 = $availabilityfactor*$performancefactor*$qualityfactor;

                $excel->setActiveSheetIndex($loop)->setCellValue('A'.$numrow, date('d M Y',strtotime($date1)));
                $excel->setActiveSheetIndex($loop)->setCellValue('B'.$numrow, $availabletime);
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, ($availabletime == 0) ? 0 : $plannedstop);
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, ($availabletime == 0) ? 0 : $planpd);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, ($availabletime == 0) ? 0 : $plandt);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, ($availabletime == 0) ? 0 : $plannedproduction);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, ($availabletime == 0) ? 0 : $startup);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, ($availabletime == 0) ? 0 : $shutdown);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, ($availabletime == 0) ? 0 : $changeover);
                $excel->setActiveSheetIndex($loop)->setCellValue('J'.$numrow, ($availabletime == 0) ? 0 : $machinebreakdown);
                $excel->setActiveSheetIndex($loop)->setCellValue('K'.$numrow, ($availabletime == 0) ? 0 : $idletime);
                $excel->setActiveSheetIndex($loop)->setCellValue('L'.$numrow, ($availabletime == 0) ? 0 : $totaldowntime);
                $excel->setActiveSheetIndex($loop)->setCellValue('M'.$numrow, ($availabletime == 0) ? 0 : $runtime);
                $excel->setActiveSheetIndex($loop)->setCellValue('N'.$numrow, ($availabletime == 0) ? 0 : $theoriticalct);
                $excel->setActiveSheetIndex($loop)->setCellValue('O'.$numrow, ($availabletime == 0) ? 0 : $theoriticaloutput);
                $excel->setActiveSheetIndex($loop)->setCellValue('P'.$numrow, ($availabletime == 0) ? 0 : $actualoutput);
                $excel->setActiveSheetIndex($loop)->setCellValue('Q'.$numrow, ($availabletime == 0) ? 0 : $defectiveproduct);
                $excel->setActiveSheetIndex($loop)->setCellValue('R'.$numrow, ($availabletime == 0) ? 0 : $availabilityfactor);
                $excel->setActiveSheetIndex($loop)->setCellValue('S'.$numrow, ($availabletime == 0) ? 0 : $performancefactor);
                $excel->setActiveSheetIndex($loop)->setCellValue('T'.$numrow, ($availabletime == 0) ? 0 : $qualityfactor);
                $excel->setActiveSheetIndex($loop)->setCellValue('U'.$numrow, ($availabletime == 0) ? 0 : $oee);

                $excel->getActiveSheet()->getStyle('R'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );
                $excel->getActiveSheet()->getStyle('S'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );
                $excel->getActiveSheet()->getStyle('T'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );
                $excel->getActiveSheet()->getStyle('U'.$numrow)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );

                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
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
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('E')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('F')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('G')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('H')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('I')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('J')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('K')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('L')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('M')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('N')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('O')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('P')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('Q')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('R')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('S')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('T')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('U')->setWidth('15');

            
            
            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

            // Set orientasi kertas jadi LANDSCAPE
            $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

            // Set judul file excel nya
            $excel->getActiveSheet($loop)->setTitle($mesin->vckode . '-' . $mesin->vcnama);
            $loop++;
        }

        $excel->setActiveSheetIndex(0);

        // $objDrawing = new PHPExcel_Worksheet_Drawing();
        // $objDrawing->setName('Media Kreatif Indonesia');
        // $objDrawing->setDescription('Logo Media Kreatif');
        // $objDrawing->setPath('upload/mesin/MC-CZL.jpg');
        // $objDrawing->setCoordinates('U5'); 
        // $objDrawing->setWorksheet($excel->getActiveSheet());

        // $sheet = $excel->getActiveSheet();

        // $data = array('20', '31', '50', '80', '105', '139', '180', 'k', '256', '308','359','405','449','491','516');
        // $row = 1;
        // foreach($data as $point) {
        //     $sheet->setCellValueByColumnAndRow(1, $row++, $point);
        // }

        // $data = array('20', '31', '50', '80', '105', '139', '180', '219', '256', '308','359','405','449','491','516');
        // $row = 1;
        // foreach($data as $point) {
        //     $sheet->setCellValueByColumnAndRow(0, $row++, $point);
        // }

        // $values = new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$U$4:$U$15');
        // $categories = new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$4:$A$15');

        // $series = new PHPExcel_Chart_DataSeries(
        //     PHPExcel_Chart_DataSeries::TYPE_BARCHART,       // plotType
        //     PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,  // plotGrouping
        //     array(0),                                       // plotOrder
        //     array(),                                        // plotLabel
        //     array($categories),                             // plotCategory
        //     array($values)                                  // plotValues
        // );
        // $series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_VERTICAL);

        // $layout = new PHPExcel_Chart_Layout();
        // $plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
        // $xTitle = new PHPExcel_Chart_Title('xAxisLabel');
        // $yTitle = new PHPExcel_Chart_Title('yAxisLabel');

        // $chart = new PHPExcel_Chart('sample', null, null, $plotarea, true,0,$xTitle,$yTitle);

        // $chart->setTopLeftPosition('W1');
        // $chart->setBottomRightPosition('AI15');

        // $sheet->addChart($chart);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report OEE Monitoring ' . $judul . ' ' . $judul2 . ' - ' . date('Y-m-d H:i:s') . '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->setIncludeCharts(TRUE);
        $write->save('php://output');

    }

    function oee_temp(){
        $intgedung    = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intmesin     = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift     = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from         = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to           = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $startupdt    = $this->modelapp->getappsetting('startup')[0]->vcvalue;
        $shutdowndt   = $this->modelapp->getappsetting('shutdown')[0]->vcvalue;
        $datamesin    = $this->model->getdatamesin($intgedung);
        $datagedung   = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');

        $datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intmesin > 0) {
            $datamesin = $this->model->getmesin($intmesin);
            $judul2    = $datamesin[0]->vckode . ' - ' . $datamesin[0]->vcnama;
        } else {
            $judul2 = 'All Machine';
        }

        if ($intgedung > 0) {
            $judul = $datagedung[0]->vcnama;
        } else {
            $judul  = 'All Building';
            $judul2 = '';
        }

        foreach ($datamesin as $mesin) {
            $intmesin = $mesin->intid;
            $no       = 0;
            for ($i=0; $i <= $datediff ; $i++) {
                $dt                = $i + 1;
                $date1             = date( "Y-m-d 07:00:00", strtotime( $from . ' ' ." +" . $i . " day" ) );
                $date2             = date( "Y-m-d 06:59:59", strtotime( $from . ' ' ." +" . $dt . " day" ) );
                $availabletime1    = 0;
                $availabletime2    = 0;
                $downtime1         = 0;
                $downtime2         = 0;
                $machinebreakdown1 = 0;
                $machinebreakdown2 = 0;
                $idletime1         = 0;
                $idletime2         = 0;
                $plannedstop1      = 0;
                $plannedstop2      = 0;
                $startup1          = 0;
                $startup2          = 0;
                $shutdown1         = 0;
                $shutdown2         = 0;
                $decct             = 0;
                $intactual         = 0;
                $intreject         = 0;
                $changeoverdt      = 0;

                // Get availabletime, Planed stop, startup, shutdown
                if ($intshift == 1) {
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, 1);
                    if (count($datalogout) == 0) {
                        $availabletime1 = 0;
                        $plannedstop1   = 0;
                        $startup1       = 0;
                        $shutdown1      = 0;
                    } else {
                        $time              = (strtotime($datalogout[0]->dtpulang) - strtotime($datalogout[0]->dtmasuk))/60;
                        $availabletime1    = $time - 60;
                        $plannedstop1      = $plandowntime;
                        $startup1          = $startupdt;
                        $shutdown1         = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        $downtime1         = $datadt[0]->decdurasi;
                        $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                        $idletime1         = $datadt[0]->decprosestime;
                        // Data Output
                        $dataoutput        = $this->model->getdataoutput($date1, $date2, $intmesin, 1);
                        $decct             = $dataoutput[0]->decct;
                        $intactual         = $dataoutput[0]->intactual;
                        $intreject         = $dataoutput[0]->intreject;
                        // Change Over
                        $changeoverdt      = $this->model->getdataoutputkomponen($date1,$date2,$intmesin,1);
                    }

                } elseif ($intshift == 2) {
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, 2);
                    if (count($datalogout) == 0) {
                        $availabletime2 = 0;
                        $plannedstop2   = 0;
                        $startup2       = 0;
                        $shutdown2      = 0;
                    } else {
                        $st                = date( "Y-m-d " . $datalogout[0]->dtmasuk, strtotime( $from . ' ' ." +" . $i . " day" ) );
                        $fs                = date( "Y-m-d " . $datalogout[0]->dtpulang, strtotime( $from . ' ' ." +" . $dt . " day" ) );
                        $time              = (strtotime($fs) - strtotime($st))/60;
                        $availabletime2    = $time - 60;
                        $plannedstop2      = $plandowntime;
                        $startup2          = $startupdt;
                        $shutdown2         = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        $downtime2         = $datadt[0]->decdurasi;
                        $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                        $idletime2         = $datadt[0]->decprosestime;
                        // Data Output
                        $dataoutput        = $this->model->getdataoutput($date1, $date2, $intmesin, 2);
                        $decct             = $dataoutput[0]->decct;
                        $intactual         = $dataoutput[0]->intactual;
                        $intreject         = $dataoutput[0]->intreject;
                        // Change Over
                        $changeoverdt      = $this->model->getdataoutputkomponen($date1,$date2,$intmesin,2);
                    }

                } else {
                    $datalogout1 = $this->model->getlogout($date1, $date2, $intmesin, 1);
                    $datalogout2 = $this->model->getlogout($date1, $date2, $intmesin, 2);
                    if (count($datalogout1) == 0) {
                        $availabletime1 = 0;
                        $plannedstop1   = 0;
                        $startup1       = 0;
                        $shutdown1      = 0;
                    } else {
                        $time              = (strtotime($datalogout1[0]->dtpulang) - strtotime($datalogout1[0]->dtmasuk))/60;
                        $availabletime1    = $time - 60;
                        $plannedstop1      = $plandowntime;
                        $startup1          = $startupdt;
                        $shutdown1         = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        $downtime1         = $datadt[0]->decdurasi;
                        $machinebreakdown1 = $datadt[0]->decmachinedowntime;
                        $idletime1         = $datadt[0]->decprosestime;
                    }

                    if (count($datalogout2) == 0) {
                        $availabletime2 = 0;
                        $plannedstop2   = 0;
                        $startup2       = 0;
                        $shutdown2      = 0;
                    } else {
                        $st                = date( "Y-m-d " . $datalogout2[0]->dtmasuk, strtotime( $from . ' ' ." +" . $i . " day" ) );
                        $fs                = date( "Y-m-d " . $datalogout2[0]->dtpulang, strtotime( $from . ' ' ." +" . $dt . " day" ) );
                        $time              = (strtotime($fs) - strtotime($st))/60;
                        $availabletime2    = $time - 60;
                        $plannedstop2      = $plandowntime;
                        $startup2          = $startupdt;
                        $shutdown2         = $shutdowndt;
                        // Data Downtime
                        $datadt            = $this->model->getdatadowntime($date1, $date2, $intmesin, 1);
                        $downtime2         = $datadt[0]->decdurasi;
                        $machinebreakdown2 = $datadt[0]->decmachinedowntime;
                        $idletime2         = $datadt[0]->decprosestime;
                    }

                    // Data Output
                    $dataoutput        = $this->model->getdataoutputall($date1, $date2, $intmesin);
                    $decct             = $dataoutput[0]->decct;
                    $intactual         = $dataoutput[0]->intactual;
                    $intreject         = $dataoutput[0]->intreject;
                    // Change Over
                    $changeoverdt      = $this->model->getdataoutputkomponenall($date1,$date2,$intmesin);
                }

                echo $availabletime      = $availabletime1 + $availabletime2; echo  '<br>';
                echo $plannedstop        = $plannedstop1 + $plannedstop2; echo  '<br>';
                echo $plannedproduction  = $availabletime - $plannedstop; echo  '<br>';
                echo $startup            = $startup1 + $startup2; echo  '<br>';
                echo $shutdown           = $shutdown1 + $shutdown2; echo  '<br>';
                echo $machinebreakdown   = $machinebreakdown1 + $machinebreakdown2; echo  '<br>';
                echo $idletime           = $idletime1 + $idletime2; echo  '<br>';
                echo $downtime           = $downtime1 + $downtime2; echo  '<br>';
                echo $changeover         = $changeoverdt * 5; echo  '<br>';
                echo $totaldowntime      = $downtime +  $startup + $shutdown + $changeover; echo  '<br>';
                echo $runtime            = $plannedproduction - $totaldowntime; echo  '<br>';
                echo $theoriticalct      = $decct; echo  '<br>';
                echo $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime); echo  '<br>';
                echo $actualoutput       = $intactual; echo  '<br>';
                echo $defectiveproduct   = $intreject; echo  '<br>';
                echo $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction; echo  '<br>';
                echo $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput; echo  '<br>';
                echo $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($actualoutput - $defectiveproduct)/$actualoutput; echo  '<br>';
                echo $oee                = $availabilityfactor*$performancefactor*$qualityfactor; echo  '<br> <br>';
            }
        }
        // echo $datediff;
    }

    function getmesinajax($intgedung){
        $data =  $this->model->getdatamesin($intgedung);

        echo json_encode($data);
    }

}


