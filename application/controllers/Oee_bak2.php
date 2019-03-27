<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oee extends MY_Controller { 

    function __construct(){
        parent::__construct();
        $this->load->model('OeeModel');
        $this->model = $this->OeeModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }
 
    function view(){
        $intmesin     = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift     = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from         = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to           = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $startupdt    = $this->modelapp->getappsetting('startup')[0]->vcvalue;
        $shutdowndt   = $this->modelapp->getappsetting('shutdown')[0]->vcvalue;
        
        $datediff = (strtotime($to) - strtotime($from))/(3600*24);

        $dataP = [];

        for ($i=0; $i <= $datediff ; $i++) { 
            $shift2     = $i + 1;
            $timestart  = '07:00:00';
            $timefinish = '06:59:59';
            $datestart  = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timestart ." +" . $i . " day" ) );
            $datefinish = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timefinish . " +" . $shift2 . " day" ) );

            $datatemp     = $this->model->getworkinghours($datestart,$datefinish,$intmesin);
            $changeoverdt = $this->model->getdataoutputkomponen($datestart,$datefinish,$intmesin);

            if (count($datatemp) > 0) {
                $availableshift1 = 0;
                $availableshift2 = 0;
                if (($datatemp[0]) && $datatemp[0]->intshift == 1) {
                    $availableshift1temp =  ($datatemp[0]) ? ceil((strtotime($datatemp[0]->dtpulang) - strtotime($datatemp[0]->dtmasuk)) / 60) : 0;
                    $availableshift1 = ($availableshift1temp == 0 && $availableshift1temp < 60) ? 0 : ($availableshift1temp - 60);
                }

                if (($datatemp[0]) && $datatemp[0]->intshift == 2 && count($datatemp) == 1) {
                    $worktimetemp = ceil((strtotime($from . ' ' . $datatemp[0]->dtpulang ." +" . $shift2 . " day") - strtotime($from . ' ' . $datatemp[0]->dtmasuk . " +" . $i . " day")) / 60);
                    $availableshift2temp = ($datatemp[0]) ? $worktimetemp : 0;
                    $availableshift2 = ($availableshift2temp == 0 && $availableshift2temp < 60) ? 0 : ($availableshift2temp - 60);
                } elseif (count($datatemp) > 1) {
                    $worktimetemp = ceil((strtotime($from . ' ' . $datatemp[1]->dtpulang ." +" . $shift2 . " day") - strtotime($from . ' ' . $datatemp[1]->dtmasuk . " +" . $i . " day")) / 60);
                    $availableshift2temp = ($datatemp[0]) ? $worktimetemp : 0;
                    $availableshift2 = ($availableshift2temp == 0 && $availableshift2temp < 60) ? 0 : ($availableshift2temp - 60);
                }
            } else {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            if ($intshift == 0) {
                $downtime = $this->model->getdatadowntimeall($datestart,$datefinish,$intmesin);
                $output   = $this->model->getdataoutputall($datestart,$datefinish,$intmesin);
                $login    = $this->model->getdataloginall($datestart,$datefinish,$intmesin);

                // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            } else if ($intshift > 0) {
                $downtime = $this->model->getdatadowntime($datestart,$datefinish,$intmesin,$intshift);
                $output   = $this->model->getdataoutput($datestart,$datefinish,$intmesin,$intshift);
                $login    = $this->model->getdatalogin($datestart,$datefinish,$intmesin);

                if ($intshift == 1) {
                    // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                    $availableshift2 = 0;
                    
                } else if ($intshift == 2) {
                    $availableshift1 = 0;
                    // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);
                }
            }

            if (date('Y-m-d H:i:s', strtotime($datestart)) >= date('Y-m-d 07:00:00')) {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            $plandowntime22 = $plandowntime;
            
            if ($availableshift2 > 0) {
                $plandowntime22 = $plandowntime * 2;
            }

            $availabletime      = $availableshift1 + $availableshift2;
            // $plannedstop        = $plandowntime22 + $downtime[0]->decplanned;
            $plannedstop        = $plandowntime22;
            $plannedproduction  = $availabletime - $plannedstop;
            $startup            = ($availableshift2 == 0) ? $startupdt : $startupdt * 2;
            $shutdown           = ($availableshift2 == 0) ? $shutdowndt : $shutdowndt * 2;
            $machinebreackdown  = $downtime[0]->decmachinedowntime;
            $idletime           = $downtime[0]->decprosestime;
            $totaldowntime      = $downtime[0]->decdurasi +  $startup + $shutdown;
            $runtime            = $plannedproduction - $totaldowntime;
            $theoriticalct      = $output[0]->decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            $tempdata = array(
                        'dttanggal'          => $datestart,
                        'availabletime'      => $availabletime,
                        'plannedstop'        => $plannedstop,
                        'startup'            => $startup,
                        'shutdown'           => $shutdown,
                        'changeover'         => $changeoverdt,
                        'plannedproduction'  => $plannedproduction,
                        'machinebreackdown'  => $machinebreackdown,
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
        $data['intmesin']   = $intmesin;
        $data['intshift']   = $intshift;
        $data['from']       = date('m/d/Y',strtotime($from));
        $data['to']         = date('m/d/Y',strtotime($to));

        // $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword,$from,$to);
        $data['dataP']      = $dataP;
        $data['listmesin']  = $this->modelapp->getdatalistall('m_mesin',1,'intautocutting');
        $data['listshift']  = $this->modelapp->getdatalistall('m_shift');

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function exportexcel(){
        $intmesin     = ($this->input->get('intmesin') == '') ? 0 : $this->input->get('intmesin');
        $intshift     = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from         = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to           = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $startupdt    = $this->modelapp->getappsetting('startup')[0]->vcvalue;
        $shutdowndt   = $this->modelapp->getappsetting('shutdown')[0]->vcvalue;

        $datediff = (strtotime($to) - strtotime($from))/(3600*24);

        $mesin = $this->model->getmesin($intmesin);

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report OEE " . $mesin[0]->vcbrand . ' ' . $mesin[0]->vcserial . ' ' . $mesin[0]->vcgedung)
                     ->setSubject("Report OEE")
                     ->setDescription("Report OEE")
                     ->setKeywords("Report OEE");

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

        // ++++++++++++++++++++++++++++ SHEET 1 OEE +++++++++++++++++++++++++++++++++++++++++++
        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Report OEE " . $mesin[0]->vcbrand . ' ' . $mesin[0]->vcserial . ' ' . $mesin[0]->vcgedung);
        $excel->getActiveSheet()->mergeCells('B1:G1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('A3', "Date");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Available Time (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Planned Stop (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Planned Production Time (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Start Up (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Shutdown (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Change Over (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Machine Breakdown (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Idle Time (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Total Downtime (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Operating / runtime (min)");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "Theoritical CT (pairs/min)");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "Theoritical Output (based on CT)");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "Actual Output");
        $excel->setActiveSheetIndex(0)->setCellValue('O3', "Defective Product");
        $excel->setActiveSheetIndex(0)->setCellValue('P3', "Availability Factor");
        $excel->setActiveSheetIndex(0)->setCellValue('Q3', "Performance Factor");
        $excel->setActiveSheetIndex(0)->setCellValue('R3', "Quality Factor");
        $excel->setActiveSheetIndex(0)->setCellValue('S3', "OEE");

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

        $excel->setActiveSheetIndex()->getStyle('A3')->applyFromArray($style_col);
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
        for ($i=0; $i <= $datediff ; $i++) { 
            $shift2     = $i + 1;
            $timestart  = '07:00:00';
            $timefinish = '06:59:59';
            $datestart  = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timestart ." +" . $i . " day" ) );
            $datefinish = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timefinish . " +" . $shift2 . " day" ) );

            $changeoverdt = $this->model->getdataoutputkomponen($datestart,$datefinish,$intmesin);

            if ($intshift == 0) {
                $downtime = $this->model->getdatadowntimeall($datestart,$datefinish,$intmesin);
                $output   = $this->model->getdataoutputall($datestart,$datefinish,$intmesin);
                $login    = $this->model->getdataloginall($datestart,$datefinish,$intmesin);

                $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            } else if ($intshift > 0) {
                $downtime = $this->model->getdatadowntime($datestart,$datefinish,$intmesin,$intshift);
                $output   = $this->model->getdataoutput($datestart,$datefinish,$intmesin,$intshift);
                $login    = $this->model->getdatalogin($datestart,$datefinish,$intmesin);

                if ($intshift == 1) {
                    $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                    $availableshift2 = 0;
                } else if ($intshift == 2) {
                    $availableshift1 = 0;
                    $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);
                }
            }

            // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
            // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            if (date('Y-m-d', strtotime($datestart)) >= date('Y-m-d')) {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            $plandowntime22 = $plandowntime;
            
            if ($availableshift2 > 0) {
                $plandowntime22 = $plandowntime * 2;
            }

            $shift2     = $i + 1;
            $timestart  = '07:00:00';
            $timefinish = '06:59:59';
            $datestart  = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timestart ." +" . $i . " day" ) );
            $datefinish = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timefinish . " +" . $shift2 . " day" ) );

            $datatemp = $this->model->getworkinghours($datestart,$datefinish,$intmesin);

            if (count($datatemp) > 0) {
                $availableshift1 = 0;
                $availableshift2 = 0;
                if (($datatemp[0]) && $datatemp[0]->intshift == 1) {
                    $availableshift1temp =  ($datatemp[0]) ? ceil((strtotime($datatemp[0]->dtpulang) - strtotime($datatemp[0]->dtmasuk)) / 60) : 0;
                    $availableshift1 = ($availableshift1temp == 0 && $availableshift1temp < 60) ? 0 : ($availableshift1temp - 60);
                }

                if (($datatemp[0]) && $datatemp[0]->intshift == 2 && count($datatemp) == 1) {
                    $worktimetemp = ceil((strtotime($from . ' ' . $datatemp[0]->dtpulang ." +" . $shift2 . " day") - strtotime($from . ' ' . $datatemp[0]->dtmasuk . " +" . $i . " day")) / 60);
                    $availableshift2temp = ($datatemp[0]) ? $worktimetemp : 0;
                    $availableshift2 = ($availableshift2temp == 0 && $availableshift2temp < 60) ? 0 : ($availableshift2temp - 60);
                } elseif (count($datatemp) > 1) {
                    $worktimetemp = ceil((strtotime($from . ' ' . $datatemp[1]->dtpulang ." +" . $shift2 . " day") - strtotime($from . ' ' . $datatemp[1]->dtmasuk . " +" . $i . " day")) / 60);
                    $availableshift2temp = ($datatemp[0]) ? $worktimetemp : 0;
                    $availableshift2 = ($availableshift2temp == 0 && $availableshift2temp < 60) ? 0 : ($availableshift2temp - 60);
                }
            } else {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            if ($intshift == 0) {
                $downtime = $this->model->getdatadowntimeall($datestart,$datefinish,$intmesin);
                $output   = $this->model->getdataoutputall($datestart,$datefinish,$intmesin);
                $login    = $this->model->getdataloginall($datestart,$datefinish,$intmesin);

                // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            } else if ($intshift > 0) {
                $downtime = $this->model->getdatadowntime($datestart,$datefinish,$intmesin,$intshift);
                $output   = $this->model->getdataoutput($datestart,$datefinish,$intmesin,$intshift);
                $login    = $this->model->getdatalogin($datestart,$datefinish,$intmesin);

                if ($intshift == 1) {
                    // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                    $availableshift2 = 0;
                    
                } else if ($intshift == 2) {
                    $availableshift1 = 0;
                    // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);
                }
            }

            if (date('Y-m-d H:i:s', strtotime($datestart)) >= date('Y-m-d 07:00:00')) {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            $plandowntime22 = $plandowntime;
            
            if ($availableshift2 > 0) {
                $plandowntime22 = $plandowntime * 2;
            }

            $availabletime      = $availableshift1 + $availableshift2;
            // $plannedstop        = $plandowntime22 + $downtime[0]->decplanned;
            $plannedstop        = $plandowntime22;
            $plannedproduction  = $availabletime - $plannedstop;
            $startup            = ($availableshift2 == 0) ? $startupdt : $startupdt * 2;
            $shutdown           = ($availableshift2 == 0) ? $shutdowndt : $shutdowndt * 2;
            $changeover         = $changeoverdt * 5;
            $machinebreackdown  = $downtime[0]->decmachinedowntime;
            $idletime           = $downtime[0]->decprosestime;
            $totaldowntime      = $downtime[0]->decdurasi +  $startup + $shutdown + $changeover;
            $runtime            = $plannedproduction - $totaldowntime;
            $theoriticalct      = $output[0]->decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, date('d M Y',strtotime($datestart)));
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $availabletime);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, ($availabletime == 0) ? 0 : $plannedstop);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, ($availabletime == 0) ? 0 : $plannedproduction);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, ($availabletime == 0) ? 0 : $startup);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, ($availabletime == 0) ? 0 : $shutdown);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, ($availabletime == 0) ? 0 : $changeover);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, ($availabletime == 0) ? 0 : $machinebreackdown);
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, ($availabletime == 0) ? 0 : $idletime);
            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, ($availabletime == 0) ? 0 : $totaldowntime);
            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, ($availabletime == 0) ? 0 : $runtime);
            $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, ($availabletime == 0) ? 0 : $theoriticalct);
            $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, ($availabletime == 0) ? 0 : $theoriticaloutput);
            $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, ($availabletime == 0) ? 0 : $actualoutput);
            $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, ($availabletime == 0) ? 0 : $defectiveproduct);
            $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, ($availabletime == 0) ? 0 : $availabilityfactor);
            $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, ($availabletime == 0) ? 0 : $performancefactor);
            $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, ($availabletime == 0) ? 0 : $qualityfactor);
            $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, ($availabletime == 0) ? 0 : $oee);

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
        $excel->getActiveSheet(0)->setTitle('OEE');

        // ++++++++++++++++++++++++++++ SHEET 1 OEE +++++++++++++++++++++++++++++++++++++++++++
        $excel->createSheet();
        $excel->setActiveSheetIndex(1)->setCellValue('B1', "Report OEE " . $mesin[0]->vcbrand . ' ' . $mesin[0]->vcserial . ' ' . $mesin[0]->vcgedung);
        $excel->getActiveSheet()->mergeCells('B1:G1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(1)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(1)->setCellValue('C3', "Date");
        $excel->setActiveSheetIndex(1)->setCellValue('D3', "Availability Factor");
        $excel->setActiveSheetIndex(1)->setCellValue('E3', "Performance Factor");
        $excel->setActiveSheetIndex(1)->setCellValue('F3', "Quality Factor");
        $excel->setActiveSheetIndex(1)->setCellValue('G3', "OEE");

        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('B3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('C3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('D3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('E3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('F3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('G3')->applyFromArray($style_col);

        $numrow = 4;
        $no = 0;
        for ($i=0; $i <= $datediff ; $i++) { 
            $shift2     = $i + 1;
            $timestart  = '07:00:00';
            $timefinish = '06:59:59';
            $datestart  = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timestart ." +" . $i . " day" ) );
            $datefinish = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timefinish . " +" . $shift2 . " day" ) );

            if ($intshift == 0) {
                $downtime = $this->model->getdatadowntimeall($datestart,$datefinish,$intmesin);
                $output   = $this->model->getdataoutputall($datestart,$datefinish,$intmesin);
                $login    = $this->model->getdataloginall($datestart,$datefinish,$intmesin);

                $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            } else if ($intshift > 0) {
                $downtime = $this->model->getdatadowntime($datestart,$datefinish,$intmesin,$intshift);
                $output   = $this->model->getdataoutput($datestart,$datefinish,$intmesin,$intshift);
                $login    = $this->model->getdatalogin($datestart,$datefinish,$intmesin);

                if ($intshift == 1) {
                    $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                    $availableshift2 = 0;
                } else if ($intshift == 2) {
                    $availableshift1 = 0;
                    $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);
                }
            }

            // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
            // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            if (date('Y-m-d', strtotime($datestart)) >= date('Y-m-d')) {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            $plandowntime22 = $plandowntime;
            
            if ($availableshift2 > 0) {
                $plandowntime22 = $plandowntime * 2;
            }

            $availabletime      = $availableshift1 + $availableshift2;
            $plannedstop        = $plandowntime22 + $downtime[0]->decplanned;
            $plannedproduction  = $availabletime - $plannedstop;
            $machinebreackdown  = $downtime[0]->decmachinedowntime;
            $idletime           = $downtime[0]->decprosestime;
            $totaldowntime      = $downtime[0]->decdurasi;
            $runtime            = $plannedproduction - $totaldowntime;
            $theoriticalct      = $output[0]->decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            $excel->setActiveSheetIndex(1)->setCellValue('B'.$numrow, ++$no);
            $excel->setActiveSheetIndex(1)->setCellValue('C'.$numrow, date('d M Y',strtotime($datestart)));
            $excel->setActiveSheetIndex(1)->setCellValue('D'.$numrow, $availabilityfactor);
            $excel->setActiveSheetIndex(1)->setCellValue('E'.$numrow, $performancefactor);
            $excel->setActiveSheetIndex(1)->setCellValue('F'.$numrow, $qualityfactor);
            $excel->setActiveSheetIndex(1)->setCellValue('G'.$numrow, $oee);

            $excel->getActiveSheet()->getStyle('D'.$numrow)->getNumberFormat()->applyFromArray( 
                array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
            );
            $excel->getActiveSheet()->getStyle('E'.$numrow)->getNumberFormat()->applyFromArray( 
                array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
            );
            $excel->getActiveSheet()->getStyle('F'.$numrow)->getNumberFormat()->applyFromArray( 
                array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
            );
            $excel->getActiveSheet()->getStyle('G'.$numrow)->getNumberFormat()->applyFromArray( 
                array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
            );

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);       
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(1)->setTitle('OEE');


        // ++++++++++++++++++++++++++++++++++++++++++ SHEET 2 Downtime ++++++++++++++++++++++++++++++++++++++++++++++++++
        $excel->createSheet();
        $excel->setActiveSheetIndex(2)->setCellValue('B1', "Report Downtime " . $mesin[0]->vcbrand . ' ' . $mesin[0]->vcserial . ' ' . $mesin[0]->vcgedung);
        $excel->getActiveSheet()->mergeCells('B1:J1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(2)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(2)->setCellValue('C3', "Date");
        $excel->setActiveSheetIndex(2)->setCellValue('D3', "Available Time");
        $excel->setActiveSheetIndex(2)->setCellValue('E3', "Planned Stop");
        $excel->setActiveSheetIndex(2)->setCellValue('F3', "Planned Production Time");
        $excel->setActiveSheetIndex(2)->setCellValue('G3', "Machine Breakdown");
        $excel->setActiveSheetIndex(2)->setCellValue('H3', "Idle Time");
        $excel->setActiveSheetIndex(2)->setCellValue('I3', "Total Downtime");
        $excel->setActiveSheetIndex(2)->setCellValue('J3', "Operting / Run Time");

        $excel->getActiveSheet(1)->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(1)->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(2)->getStyle('B3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('C3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('D3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('E3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('F3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('G3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('H3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('I3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(2)->getStyle('J3')->applyFromArray($style_col);

        $numrow = 4;
        $no = 0;
        for ($i=0; $i <= $datediff ; $i++) { 
            $shift2     = $i + 1;
            $timestart  = '07:00:00';
            $timefinish = '06:59:59';
            $datestart  = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timestart ." +" . $i . " day" ) );
            $datefinish = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timefinish . " +" . $shift2 . " day" ) );

            if ($intshift == 0) {
                $downtime = $this->model->getdatadowntimeall($datestart,$datefinish,$intmesin);
                $output   = $this->model->getdataoutputall($datestart,$datefinish,$intmesin);
                $login    = $this->model->getdataloginall($datestart,$datefinish,$intmesin);

                $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            } else if ($intshift > 0) {
                $downtime = $this->model->getdatadowntime($datestart,$datefinish,$intmesin,$intshift);
                $output   = $this->model->getdataoutput($datestart,$datefinish,$intmesin,$intshift);
                $login    = $this->model->getdatalogin($datestart,$datefinish,$intmesin);

                if ($intshift == 1) {
                    $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                    $availableshift2 = 0;
                } else if ($intshift == 2) {
                    $availableshift1 = 0;
                    $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);
                }
            }


            // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
            // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            if (date('Y-m-d', strtotime($datestart)) >= date('Y-m-d')) {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            $plandowntime22 = $plandowntime;
            
            if ($availableshift2 > 0) {
                $plandowntime22 = $plandowntime * 2;
            }

            $availabletime      = $availableshift1 + $availableshift2;
            $plannedstop        = $plandowntime22 + $downtime[0]->decplanned;
            $plannedproduction  = $availabletime - $plannedstop;
            $machinebreackdown  = $downtime[0]->decmachinedowntime;
            $idletime           = $downtime[0]->decprosestime;
            $totaldowntime      = $downtime[0]->decdurasi;
            $runtime            = $plannedproduction - $totaldowntime;
            $theoriticalct      = $output[0]->decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            if ((date('Y-m-d', strtotime($datestart)) >= date('Y-m-d')) || count($login) == 0) {
                $availabletime      = 0;
                $plannedstop        = 0;
                $plannedproduction  = 0;
                $machinebreackdown  = 0;
                $idletime           = 0;
                $totaldowntime      = 0;
                $runtime            = 0;
                $theoriticalct      = 0;
                $theoriticaloutput  = 0;
                $actualoutput       = 0;
                $defectiveproduct   = 0;
                $availabilityfactor = 0;
                $performancefactor  = 0;
                $qualityfactor      = 0;
                $oee                = 0;
            }


            $excel->setActiveSheetIndex(2)->setCellValue('B'.$numrow, ++$no);
            $excel->setActiveSheetIndex(2)->setCellValue('C'.$numrow, date('d M Y', strtotime($datestart)));
            $excel->setActiveSheetIndex(2)->setCellValue('D'.$numrow, $availabletime);
            $excel->setActiveSheetIndex(2)->setCellValue('E'.$numrow, $plannedstop);
            $excel->setActiveSheetIndex(2)->setCellValue('F'.$numrow, $plannedproduction);
            $excel->setActiveSheetIndex(2)->setCellValue('G'.$numrow, $machinebreackdown);
            $excel->setActiveSheetIndex(2)->setCellValue('H'.$numrow, $idletime);
            $excel->setActiveSheetIndex(2)->setCellValue('I'.$numrow, $totaldowntime);
            $excel->setActiveSheetIndex(2)->setCellValue('J'.$numrow, $runtime);

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20); 
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(2)->setTitle('Downtime');


        // ++++++++++++++++++++++++++++++++++++++++++ SHEET 3 Output ++++++++++++++++++++++++++++++++++++++++++++++++++
        $excel->createSheet();
        $excel->setActiveSheetIndex(3)->setCellValue('B1', "Report Output " . $mesin[0]->vcbrand . ' ' . $mesin[0]->vcserial . ' ' . $mesin[0]->vcgedung);
        $excel->getActiveSheet()->mergeCells('B1:G1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(3)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(3)->setCellValue('C3', "Date");
        $excel->setActiveSheetIndex(3)->setCellValue('D3', "Theoritical Cycle Time");
        $excel->setActiveSheetIndex(3)->setCellValue('E3', "Theoritical Output");
        $excel->setActiveSheetIndex(3)->setCellValue('F3', "Actual Output");
        $excel->setActiveSheetIndex(3)->setCellValue('G3', "Defective Product");

        $excel->getActiveSheet(2)->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(2)->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(2)->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(2)->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(2)->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet(2)->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(3)->getStyle('B3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(3)->getStyle('C3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(3)->getStyle('D3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(3)->getStyle('E3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(3)->getStyle('F3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(3)->getStyle('G3')->applyFromArray($style_col);

        $numrow = 4;
        $no = 0;
        for ($i=0; $i <= $datediff ; $i++) { 
            $shift2     = $i + 1;
            $timestart  = '07:00:00';
            $timefinish = '06:59:59';
            $datestart  = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timestart ." +" . $i . " day" ) );
            $datefinish = date( "Y-m-d H:i:s", strtotime( $from . ' ' . $timefinish . " +" . $shift2 . " day" ) );

            if ($intshift == 0) {
                $downtime = $this->model->getdatadowntimeall($datestart,$datefinish,$intmesin);
                $output   = $this->model->getdataoutputall($datestart,$datefinish,$intmesin);
                $login    = $this->model->getdataloginall($datestart,$datefinish,$intmesin);

                $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            } else if ($intshift > 0) {
                $downtime = $this->model->getdatadowntime($datestart,$datefinish,$intmesin,$intshift);
                $output   = $this->model->getdataoutput($datestart,$datefinish,$intmesin,$intshift);
                $login    = $this->model->getdatalogin($datestart,$datefinish,$intmesin);

                if ($intshift == 1) {
                    $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
                    $availableshift2 = 0;
                } else if ($intshift == 2) {
                    $availableshift1 = 0;
                    $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);
                }
            }

            // $availableshift1 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift1) - strtotime($login[0]->loginshift1)) / 60);
            // $availableshift2 = (count($login) == 0 ) ? 0 : ceil((strtotime($login[0]->logoutshift2) - strtotime($login[0]->loginshift2)) / 60);

            if (date('Y-m-d', strtotime($datestart)) >= date('Y-m-d')) {
                $availableshift1 = 0;
                $availableshift2 = 0;
            }

            $plandowntime22 = $plandowntime;
            
            if ($availableshift2 > 0) {
                $plandowntime22 = $plandowntime * 2;
            }

            $availabletime      = $availableshift1 + $availableshift2;
            $plannedstop        = $plandowntime22 + $downtime[0]->decplanned;
            $plannedproduction  = $availabletime - $plannedstop;
            $machinebreackdown  = $downtime[0]->decmachinedowntime;
            $idletime           = $downtime[0]->decprosestime;
            $totaldowntime      = $downtime[0]->decdurasi;
            $runtime            = $plannedproduction - $totaldowntime;
            $theoriticalct      = $output[0]->decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            if ((date('Y-m-d', strtotime($datestart)) >= date('Y-m-d')) || count($login) == 0) {
                $availabletime      = 0;
                $plannedstop        = 0;
                $plannedproduction  = 0;
                $machinebreackdown  = 0;
                $idletime           = 0;
                $totaldowntime      = 0;
                $runtime            = 0;
                $theoriticalct      = 0;
                $theoriticaloutput  = 0;
                $actualoutput       = 0;
                $defectiveproduct   = 0;
                $availabilityfactor = 0;
                $performancefactor  = 0;
                $qualityfactor      = 0;
                $oee                = 0;
            }

            $excel->setActiveSheetIndex(3)->setCellValue('B'.$numrow, ++$no);
            $excel->setActiveSheetIndex(3)->setCellValue('C'.$numrow, date('d M Y', strtotime($datestart)));
            $excel->setActiveSheetIndex(3)->setCellValue('D'.$numrow, round($theoriticalct,2));
            $excel->setActiveSheetIndex(3)->setCellValue('E'.$numrow, $theoriticaloutput);
            $excel->setActiveSheetIndex(3)->setCellValue('F'.$numrow, $actualoutput);
            $excel->setActiveSheetIndex(3)->setCellValue('G'.$numrow, $defectiveproduct);

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20); 
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(3)->setTitle('Output');


        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report OEE ' . $mesin[0]->vcbrand . ' ' . $mesin[0]->vcserial . ' ' . $mesin[0]->vcgedung .'.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $write->save('php://output');

    }

}
