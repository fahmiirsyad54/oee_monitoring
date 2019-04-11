<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oee_monitoring extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('AppModel');
        $this->load->model('OEE_monitoringModel');
        $this->model = $this->OEE_monitoringModel;
        $this->modelapp = $this->AppModel;

        if (!$this->session->intidoee || $this->session->appname != 'oee_monitoring') {
            redirect(base_url('akses/loginoee'));
        }
    }

    function index(){
        redirect(base_url('oee_monitoring/dashboard'));
    }

    function dashboard($datest='',$datefs=''){
        $this->load->view('monitoring_view/index');
    }

    function dashboard_ajax($datest='',$datefs=''){
        $intshift = getshift(strtotime(date('H:i:s')));
        if ($datest == '' && $datefs == '') {
            $intrealtime = 1;
            $btnreal     = 'btn-success';
            $btnhistory  = 'btn-default';
            $datest      = date('m/d/Y');
            $datefs      = date('m/d/Y');
            $hidedate    = 'hidden';
        } else {
            $intrealtime = 0;
            $btnreal     = 'btn-default';
            $btnhistory  = 'btn-success';
            $datest      = date('m/d/Y',strtotime($datest));
            $datefs      = date('m/d/Y',strtotime($datefs));
            $hidedate    = '';
        }

        $datenow        = date('Y-m-d');
        $timenow        = date('H:is');
        $intshift       = 1;
        $worksift1      = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2      = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $availabletime1 = 0;
        $availabletime2 = 0;
        $istirahat1     = 0;
        $istirahat2     = 0;
        $listgedung     = $this->model->getdatagedung('m_gedung');
        $datapergedung  = array();
        $avgtotaf       = 0;
        $avgtotpf       = 0;
        $avgtotqf       = 0;
        $loopgedung     = 0;

        foreach ($listgedung as $dtgedung) {
            $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
            $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
            $totalaf         = 0;
            $totalpf         = 0;
            $totalqf         = 0;
            $totaloee         = 0;
            $totalmesin       = 0;
            $listmesin        = $this->model->getdatamesin($dtgedung->intid);
            
            if ($dtgedung->intid == $intgedungspecial) {
                $worksift1 = $worksift1special;
            }

            foreach ($listmesin as $dtmesin) {
                $intmesin = $dtmesin->intid;
                if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1);
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                }

                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';

                $availabletime      = $availabletime1 + $availabletime2 - ($istirahat1 + $istirahat2);
                $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                $idletime           = $datadowntime[0]->decprosestime;
                $totaldowntime      = $datadowntime[0]->decdurasi;
                $runtime            = $availabletime - $totaldowntime;
                $theoriticalct      = $output[0]->decct;
                // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput  = round($output[0]->inttarget);
                $actualoutput       = $output[0]->intactual;
                $defectiveproduct   = $output[0]->intreject;
                $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
                $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                    $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                    $af                   = 0;
                    $pf                   = 0;
                    $qf                   = 0;
                    $totruntime           = 0;
                    $totavailabletime     = 0;
                    $totactualoutput      = 0;
                    $tottheoriticaloutput = 0;
                    $totgoodoutput        = 0;
                    $loop                 = 0;
                    for ($i=0; $i <= $datediff ; $i++) {
                        $dt = $i + 1;
                        $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                        $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                        // echo $date1. ' -  '.$date2 . '<br>';
                        $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                        $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                        $jamkerja1          = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                        $jamkerja2          = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                        $jam1               = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                        $jam2               = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                        $availabletime      = $jam1 + $jam2;
                        $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                        $idletime           = $datadowntime[0]->decprosestime;
                        $totaldowntime      = $datadowntime[0]->decdurasi;
                        $runtime            = $availabletime - $totaldowntime;
                        $theoriticalct      = $output[0]->decct;
                        $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                        $actualoutput       = $output[0]->intactual;
                        $defectiveproduct   = $output[0]->intreject;
                        $goodoutput         = $actualoutput - $defectiveproduct;
                        $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                        $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                        $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
                        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                        $loop++;
                        $af                   = $af + $availabilityfactor;
                        $pf                   = $pf + $performancefactor;
                        $qf                   = $qf + $qualityfactor;
                        $totruntime           = $totruntime + $runtime;
                        $totavailabletime     = $totavailabletime + $availabletime;
                        $totactualoutput      = $totactualoutput + $actualoutput;
                        $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                        $totgoodoutput        = $totgoodoutput + $goodoutput;
                        // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                    }

                    $avgaf = $af/$loop;
                    $avgpf = $pf/$loop;
                    $avgqf = $qf/$loop;

                    $availabletime      = $totavailabletime;
                    $machinebreackdown  = 0;
                    $idletime           = 0;
                    $totaldowntime      = 0;
                    $runtime            = $totruntime;
                    $theoriticalct      = 0;
                    $theoriticaloutput  = $tottheoriticaloutput;
                    $actualoutput       = $totactualoutput;
                    $defectiveproduct   = 0;
                    $goodoutput         = $totgoodoutput;
                    $availabilityfactor = $avgaf;
                    $performancefactor  = $avgpf;
                    $qualityfactor      = $avgqf;
                    $oee                = $avgaf * $avgpf * $avgqf;
                }

                $totalaf  = $totalaf + $availabilityfactor;
                $totalpf  = $totalpf + $performancefactor;
                $totalqf  = $totalqf + $qualityfactor;
                // $totaloee = $totaloee + $oee;
                $totalmesin++;
            }

            $avgaf = ($totalmesin == 0) ? 0 : $totalaf/$totalmesin;
            $avgpf = ($totalmesin == 0) ? 0 : $totalpf/$totalmesin;
            $avgqf = ($totalmesin == 0) ? 0 : $totalqf/$totalmesin;
            // $avgoee = ($totalmesin == 0) ? 0 : round($totaloee/$totalmesin,2);
            $avgoee = $avgaf * $avgpf * $avgqf;

            $avgtotaf = $avgtotaf + $avgaf;
            $avgtotpf = $avgtotpf + $avgpf;
            $avgtotqf = $avgtotqf + $avgqf;
            $loopgedung++;
            array_push($datapergedung, array('intgedung' => $dtgedung->intid,'vcgedung' => $dtgedung->vcnama,'avgoee' => round(($avgoee*100),2)));
        }
        $avgaftot = ($loopgedung == 0) ? 0 : $avgtotaf/$loopgedung;
        $avgpftot = ($loopgedung == 0) ? 0 : $avgtotpf/$loopgedung;
        $avgqftot = ($loopgedung == 0) ? 0 : $avgtotqf/$loopgedung;
        $avgtotoee = $avgaftot * $avgpftot * $avgqftot;
       
        $data['oee']         = $datapergedung;
        $data['datest']      = $datest;
        $data['datefs']      = $datefs;
        $data['intrealtime'] = $intrealtime;
        $data['btnreal']     = $btnreal;
        $data['btnhistory']  = $btnhistory;
        $data['hidedate']    = $hidedate;
        $data['avgoee']      = round(($avgtotoee*100),2);
        $data['avgaf']       = round(($avgaftot*100),2);
        $data['avgpf']       = round(($avgpftot*100),2);
        $data['avgqf']       = round(($avgqftot*100),2);
        $this->load->view('monitoring_view/index',$data);
    }

    function building($intgedung=0,$datest='',$datefs=''){
        $data['intgedung'] = $intgedung;
        $this->load->view('monitoring_view/index',$data);
    }

    function building_ajax($intgedung=0,$datest='',$datefs=''){
        $dtcell = $this->model->getcentralcutting($intgedung);
        $intshift = getshift(strtotime(date('H:i:s')));
        if ($datest == '' && $datefs == '') {
            $intrealtime = 1;
            $btnreal     = 'btn-success';
            $btnhistory  = 'btn-default';
            $datest      = date('m/d/Y');
            $datefs      = date('m/d/Y');
            $hidedate    = 'hidden';
        } else {
            $intrealtime = 0;
            $btnreal     = 'btn-default';
            $btnhistory  = 'btn-success';
            $datest      = date('m/d/Y',strtotime($datest));
            $datefs      = date('m/d/Y',strtotime($datefs));
            $hidedate    = '';
        }

        $datenow          = date('Y-m-d');
        $timenow          = date('H:is');
        // $intshift         = 1;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $availabletime1   = 0;
        $availabletime2   = 0;
        $istirahat1       = 0;
        $istirahat2       = 0;
        $totaf            = 0;
        $totpf            = 0;
        $totqf            = 0;
        $totoee           = 0;
        $jmlmesin         = 0;
        $datagedung       = $this->modelapp->getdatadetail('m_gedung',$intgedung);

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        $datacell = [];
        $loopcell = 0;
        foreach ($dtcell as $cell) {
            $intcell      = $cell->intid;
            $datapermesin = array();
            $listmesin    = $this->model->getdatamesin($intgedung,$intcell);
            $totafcell    = 0;
            $totpfcell    = 0;
            $totqfcell    = 0;
            $totoeecell   = 0;
            $jmlmesincell = 0;
            foreach ($listmesin as $dtmesin) {
                $intmesin = $dtmesin->intid;
                if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1);
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                }

                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator1  = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                    $availabletime1 = (count($dataoperator1) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $vcoperator  = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
                $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

                $availabletime      = $availabletime1 + $availabletime2 - ($istirahat1 + $istirahat2);
                $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                $idletime           = $datadowntime[0]->decprosestime;
                $totaldowntime      = $datadowntime[0]->decdurasi;
                $runtime            = $availabletime - $totaldowntime;
                $theoriticalct      = $output[0]->decct;
                // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput  = round($output[0]->inttarget);
                $actualoutput       = $output[0]->intactual;
                $defectiveproduct   = $output[0]->intreject;
                $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
                $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                    $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                    $af                   = 0;
                    $pf                   = 0;
                    $qf                   = 0;
                    $totruntime           = 0;
                    $totavailabletime     = 0;
                    $totactualoutput      = 0;
                    $tottheoriticaloutput = 0;
                    $totgoodoutput        = 0;
                    $loop                 = 0;
                    for ($i=0; $i <= $datediff ; $i++) {
                        $dt = $i + 1;
                        $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                        $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                        // echo $date1. ' -  '.$date2 . '<br>';
                        $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                        $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                        $jamkerja1          = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                        $jamkerja2          = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                        $jam1               = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                        $jam2               = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                        $availabletime      = $jam1 + $jam2;
                        $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                        $idletime           = $datadowntime[0]->decprosestime;
                        $totaldowntime      = $datadowntime[0]->decdurasi;
                        $runtime            = $availabletime - $totaldowntime;
                        $theoriticalct      = $output[0]->decct;
                        $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                        $actualoutput       = $output[0]->intactual;
                        $defectiveproduct   = $output[0]->intreject;
                        $goodoutput         = $actualoutput - $defectiveproduct;
                        $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                        $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                        $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
                        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                        $loop++;
                        $af                   = $af + $availabilityfactor;
                        $pf                   = $pf + $performancefactor;
                        $qf                   = $qf + $qualityfactor;
                        $totruntime           = $totruntime + $runtime;
                        $totavailabletime     = $totavailabletime + $availabletime;
                        $totactualoutput      = $totactualoutput + $actualoutput;
                        $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                        $totgoodoutput        = $totgoodoutput + $goodoutput;
                        // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                    }

                    $avgaf = $af/$loop;
                    $avgpf = $pf/$loop;
                    $avgqf = $qf/$loop;

                    $availabletime      = $totavailabletime;
                    $machinebreackdown  = 0;
                    $idletime           = 0;
                    $totaldowntime      = 0;
                    $runtime            = $totruntime;
                    $theoriticalct      = 0;
                    $theoriticaloutput  = $tottheoriticaloutput;
                    $actualoutput       = $totactualoutput;
                    $defectiveproduct   = 0;
                    $goodoutput         = $totgoodoutput;
                    $availabilityfactor = $avgaf;
                    $performancefactor  = $avgpf;
                    $qualityfactor      = $avgqf;
                    $oee                = $avgaf * $avgpf * $avgqf;
                }

                array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                                                'vckodemesin' => $dtmesin->vckode,
                                                'vcmesin' => $dtmesin->vcnama,
                                                'statusmesin' => $statusmesin,
                                                'avgoee' => round(($oee * 100),2)));

                $totafcell  = $totafcell + $availabilityfactor;
                $totpfcell  = $totpfcell + $performancefactor;
                $totqfcell  = $totqfcell + $qualityfactor;
                $totoeecell = $totoeecell + $oee;
                $jmlmesincell++;
            }

            $avgafcell  = ($jmlmesincell == 0) ? 0 : $totafcell/$jmlmesincell;
            $avgpfcell  = ($jmlmesincell == 0) ? 0 : $totpfcell/$jmlmesincell;
            $avgqfcell  = ($jmlmesincell == 0) ? 0 : $totqfcell/$jmlmesincell;
            // $avgoeecell = ($jmlmesincell == 0) ? 0 : round(($totoeecell/$jmlmesincell),2);
            $avgoeecell = $avgafcell * $avgpfcell * $avgqfcell;

            ++$loopcell;
            $celltemp = array(
                        'vccell'             => 'Cutting '.substr($datagedung[0]->vcnama,-1).$loopcell,
                        'availabilityfactor' => round(($avgafcell * 100),2),
                        'performancefactor'  => round(($avgpfcell * 100),2),
                        'qualityfactor'      => round(($avgqfcell * 100),2),
                        'oee'                => round(($avgoeecell * 100),2),
                        'datamesin'          => $datapermesin
                    );

            array_push($datacell, $celltemp);
        }

        $datapermesin = array();
        $listmesin    = $this->model->getdatamesin($intgedung);
        foreach ($listmesin as $dtmesin) {
            $intmesin = $dtmesin->intid;
            if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                $date1      = date('Y-m-d ' . $worksift1);
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            }

            if ($intshift == 1) {
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                $dataoutput2    = [];
                $listdowntime2  = [];
            } else {
                $dataoperator1   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            }

            $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
            $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

            $availabletime      = $availabletime1 + $availabletime2 - ($istirahat1 + $istirahat2);
            $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
            $idletime           = $datadowntime[0]->decprosestime;
            $totaldowntime      = $datadowntime[0]->decdurasi;
            $runtime            = $availabletime - $totaldowntime;
            $theoriticalct      = $output[0]->decct;
            // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $theoriticaloutput  = round($output[0]->inttarget);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor * $performancefactor * $qualityfactor;

            if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                $af                   = 0;
                $pf                   = 0;
                $qf                   = 0;
                $totruntime           = 0;
                $totavailabletime     = 0;
                $totactualoutput      = 0;
                $tottheoriticaloutput = 0;
                $totgoodoutput        = 0;
                $loop                 = 0;
                for ($i=0; $i <= $datediff ; $i++) {
                    $dt = $i + 1;
                    $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                    $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                    // echo $date1. ' -  '.$date2 . '<br>';
                    $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $jamkerja1          = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                    $jamkerja2          = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                    $jam1               = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                    $jam2               = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                    $availabletime      = $jam1 + $jam2;
                    $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                    $idletime           = $datadowntime[0]->decprosestime;
                    $totaldowntime      = $datadowntime[0]->decdurasi;
                    $runtime            = $availabletime - $totaldowntime;
                    $theoriticalct      = $output[0]->decct;
                    $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                    $actualoutput       = $output[0]->intactual;
                    $defectiveproduct   = $output[0]->intreject;
                    $goodoutput         = $actualoutput - $defectiveproduct;
                    $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                    $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                    $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
                    $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                    $loop++;
                    $af                   = $af + $availabilityfactor;
                    $pf                   = $pf + $performancefactor;
                    $qf                   = $qf + $qualityfactor;
                    $totruntime           = $totruntime + $runtime;
                    $totavailabletime     = $totavailabletime + $availabletime;
                    $totactualoutput      = $totactualoutput + $actualoutput;
                    $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                    $totgoodoutput        = $totgoodoutput + $goodoutput;
                    // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                }

                $avgaf = $af/$loop;
                $avgpf = $pf/$loop;
                $avgqf = $qf/$loop;

                $availabletime      = $totavailabletime;
                $machinebreackdown  = 0;
                $idletime           = 0;
                $totaldowntime      = 0;
                $runtime            = $totruntime;
                $theoriticalct      = 0;
                $theoriticaloutput  = $tottheoriticaloutput;
                $actualoutput       = $totactualoutput;
                $defectiveproduct   = 0;
                $goodoutput         = $totgoodoutput;
                $availabilityfactor = $avgaf;
                $performancefactor  = $avgpf;
                $qualityfactor      = $avgqf;
                $oee                = $avgaf * $avgpf * $avgqf;
            }

            array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                                            'vckodemesin' => $dtmesin->vckode,
                                            'vcmesin' => $dtmesin->vcnama,
                                            'statusmesin' => $statusmesin,
                                            'avgoee' => round(($oee * 100),2)));

            $totaf  = $totaf + $availabilityfactor;
            $totpf  = $totpf + $performancefactor;
            $totqf  = $totqf + $qualityfactor;
            $totoee = $totoee + $oee;
            $jmlmesin++;
        }

        $avgaf  = ($jmlmesin == 0) ? 0 : $totaf/$jmlmesin;
        $avgpf  = ($jmlmesin == 0) ? 0 : $totpf/$jmlmesin;
        $avgqf  = ($jmlmesin == 0) ? 0 : $totqf/$jmlmesin;
        // $avgoee = ($jmlmesin == 0) ? 0 : round(($totoee/$jmlmesin),2);
        $avgoee = $avgaf * $avgpf * $avgqf;

        $data['avgaf']       = round(($avgaf*100),2);
        $data['avgpf']       = round(($avgpf*100),2);
        $data['avgqf']       = round(($avgqf*100),2);
        $data['avgoee']      = round(($avgoee*100),2);
        $data['oee']         = $datapermesin;
        $data['datest']      = $datest;
        $data['datefs']      = $datefs;
        $data['intrealtime'] = $intrealtime;
        $data['btnreal']     = $btnreal;
        $data['btnhistory']  = $btnhistory;
        $data['hidedate']    = $hidedate;
        $data['intgedung']   = $intgedung;
        $data['gedung']      = $datagedung;
        $data['jumlahcell']  = count($dtcell);
        $data['cell']        = $datacell;
        $this->load->view('monitoring_view/index',$data);
    }

    function building_($intgedung=0,$datest='',$datefs=''){
        $data['intgedung'] = $intgedung;
        $this->load->view('monitoring_view/index',$data);
    }

    function building__ajax($intgedung=0,$datest='',$datefs=''){
        $dtcell = $this->model->getcentralcutting($intgedung);
        $intshift = getshift(strtotime(date('H:i:s')));
        if ($datest == '' && $datefs == '') {
            $intrealtime = 1;
            $btnreal     = 'btn-success';
            $btnhistory  = 'btn-default';
            $datest      = date('m/d/Y');
            $datefs      = date('m/d/Y');
            $hidedate    = 'hidden';
        } else {
            $intrealtime = 0;
            $btnreal     = 'btn-default';
            $btnhistory  = 'btn-success';
            $datest      = date('m/d/Y',strtotime($datest));
            $datefs      = date('m/d/Y',strtotime($datefs));
            $hidedate    = '';
        }

        $datenow          = date('Y-m-d');
        $timenow          = date('H:is');
        // $intshift         = 1;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $availabletime1   = 0;
        $availabletime2   = 0;
        $istirahat1       = 0;
        $istirahat2       = 0;
        $totaf            = 0;
        $totpf            = 0;
        $totqf            = 0;
        $totoee           = 0;
        $jmlmesin         = 0;
        $datagedung       = $this->modelapp->getdatadetail('m_gedung',$intgedung);

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        $datacell = [];
        $loopcell = 0;
        foreach ($dtcell as $cell) {
            $intcell      = $cell->intid;
            $datapermesin = array();
            $listmesin    = $this->model->getdatamesin($intgedung,$intcell);
            $totafcell    = 0;
            $totpfcell    = 0;
            $totqfcell    = 0;
            $totoeecell   = 0;
            $jmlmesincell = 0;
            foreach ($listmesin as $dtmesin) {
                $intmesin = $dtmesin->intid;
                if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1);
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                }

                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator1  = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                    $availabletime1 = (count($dataoperator1) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $vcoperator  = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
                $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

                $availabletime      = $availabletime1 + $availabletime2 - ($istirahat1 + $istirahat2);
                $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                $idletime           = $datadowntime[0]->decprosestime;
                $totaldowntime      = $datadowntime[0]->decdurasi;
                $runtime            = $availabletime - $totaldowntime;
                $theoriticalct      = $output[0]->decct;
                // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput  = round($output[0]->inttarget);
                $actualoutput       = $output[0]->intactual;
                $defectiveproduct   = $output[0]->intreject;
                $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
                $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                    $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                    $af                   = 0;
                    $pf                   = 0;
                    $qf                   = 0;
                    $totruntime           = 0;
                    $totavailabletime     = 0;
                    $totactualoutput      = 0;
                    $tottheoriticaloutput = 0;
                    $totgoodoutput        = 0;
                    $loop                 = 0;
                    for ($i=0; $i <= $datediff ; $i++) {
                        $dt = $i + 1;
                        $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                        $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                        // echo $date1. ' -  '.$date2 . '<br>';
                        $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                        $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                        $jamkerja1          = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                        $jamkerja2          = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                        $jam1               = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                        $jam2               = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                        $availabletime      = $jam1 + $jam2;
                        $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                        $idletime           = $datadowntime[0]->decprosestime;
                        $totaldowntime      = $datadowntime[0]->decdurasi;
                        $runtime            = $availabletime - $totaldowntime;
                        $theoriticalct      = $output[0]->decct;
                        $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                        $actualoutput       = $output[0]->intactual;
                        $defectiveproduct   = $output[0]->intreject;
                        $goodoutput         = $actualoutput - $defectiveproduct;
                        $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                        $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                        $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
                        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                        $loop++;
                        $af                   = $af + $availabilityfactor;
                        $pf                   = $pf + $performancefactor;
                        $qf                   = $qf + $qualityfactor;
                        $totruntime           = $totruntime + $runtime;
                        $totavailabletime     = $totavailabletime + $availabletime;
                        $totactualoutput      = $totactualoutput + $actualoutput;
                        $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                        $totgoodoutput        = $totgoodoutput + $goodoutput;
                        // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                    }

                    $avgaf = $af/$loop;
                    $avgpf = $pf/$loop;
                    $avgqf = $qf/$loop;

                    $availabletime      = $totavailabletime;
                    $machinebreackdown  = 0;
                    $idletime           = 0;
                    $totaldowntime      = 0;
                    $runtime            = $totruntime;
                    $theoriticalct      = 0;
                    $theoriticaloutput  = $tottheoriticaloutput;
                    $actualoutput       = $totactualoutput;
                    $defectiveproduct   = 0;
                    $goodoutput         = $totgoodoutput;
                    $availabilityfactor = $avgaf;
                    $performancefactor  = $avgpf;
                    $qualityfactor      = $avgqf;
                    $oee                = $avgaf * $avgpf * $avgqf;
                }

                array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                                                'vckodemesin' => $dtmesin->vckode,
                                                'vcmesin' => $dtmesin->vcnama,
                                                'statusmesin' => $statusmesin,
                                                'avgoee' => round(($oee * 100),2)));

                $totafcell  = $totafcell + $availabilityfactor;
                $totpfcell  = $totpfcell + $performancefactor;
                $totqfcell  = $totqfcell + $qualityfactor;
                $totoeecell = $totoeecell + $oee;
                $jmlmesincell++;
            }

            $avgafcell  = ($jmlmesincell == 0) ? 0 : $totafcell/$jmlmesincell;
            $avgpfcell  = ($jmlmesincell == 0) ? 0 : $totpfcell/$jmlmesincell;
            $avgqfcell  = ($jmlmesincell == 0) ? 0 : $totqfcell/$jmlmesincell;
            // $avgoeecell = ($jmlmesincell == 0) ? 0 : round(($totoeecell/$jmlmesincell),2);
            $avgoeecell = $avgafcell * $avgpfcell * $avgqfcell;

            ++$loopcell;
            $celltemp = array(
                        'vccell'             => 'Cutting '.substr($datagedung[0]->vcnama,-1).$loopcell,
                        'availabilityfactor' => round(($avgafcell * 100),2),
                        'performancefactor'  => round(($avgpfcell * 100),2),
                        'qualityfactor'      => round(($avgqfcell * 100),2),
                        'oee'                => round(($avgoeecell * 100),2),
                        'datamesin'          => $datapermesin
                    );

            array_push($datacell, $celltemp);
        }

        $datapermesin = array();
        $listmesin    = $this->model->getdatamesin($intgedung);
        foreach ($listmesin as $dtmesin) {
            $intmesin = $dtmesin->intid;
            if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                $date1      = date('Y-m-d ' . $worksift1);
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            }

            if ($intshift == 1) {
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                $dataoutput2    = [];
                $listdowntime2  = [];
            } else {
                $dataoperator1   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            }

            $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
            $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

            $availabletime      = $availabletime1 + $availabletime2 - ($istirahat1 + $istirahat2);
            $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
            $idletime           = $datadowntime[0]->decprosestime;
            $totaldowntime      = $datadowntime[0]->decdurasi;
            $runtime            = $availabletime - $totaldowntime;
            $theoriticalct      = $output[0]->decct;
            // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $theoriticaloutput  = round($output[0]->inttarget);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;
            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor * $performancefactor * $qualityfactor;

            if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                $af                   = 0;
                $pf                   = 0;
                $qf                   = 0;
                $totruntime           = 0;
                $totavailabletime     = 0;
                $totactualoutput      = 0;
                $tottheoriticaloutput = 0;
                $totgoodoutput        = 0;
                $loop                 = 0;
                for ($i=0; $i <= $datediff ; $i++) {
                    $dt = $i + 1;
                    $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                    $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                    // echo $date1. ' -  '.$date2 . '<br>';
                    $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $jamkerja1          = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                    $jamkerja2          = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                    $jam1               = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                    $jam2               = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                    $availabletime      = $jam1 + $jam2;
                    $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                    $idletime           = $datadowntime[0]->decprosestime;
                    $totaldowntime      = $datadowntime[0]->decdurasi;
                    $runtime            = $availabletime - $totaldowntime;
                    $theoriticalct      = $output[0]->decct;
                    $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                    $actualoutput       = $output[0]->intactual;
                    $defectiveproduct   = $output[0]->intreject;
                    $goodoutput         = $actualoutput - $defectiveproduct;
                    $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                    $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                    $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
                    $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                    $loop++;
                    $af                   = $af + $availabilityfactor;
                    $pf                   = $pf + $performancefactor;
                    $qf                   = $qf + $qualityfactor;
                    $totruntime           = $totruntime + $runtime;
                    $totavailabletime     = $totavailabletime + $availabletime;
                    $totactualoutput      = $totactualoutput + $actualoutput;
                    $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                    $totgoodoutput        = $totgoodoutput + $goodoutput;
                    // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                }

                $avgaf = $af/$loop;
                $avgpf = $pf/$loop;
                $avgqf = $qf/$loop;

                $availabletime      = $totavailabletime;
                $machinebreackdown  = 0;
                $idletime           = 0;
                $totaldowntime      = 0;
                $runtime            = $totruntime;
                $theoriticalct      = 0;
                $theoriticaloutput  = $tottheoriticaloutput;
                $actualoutput       = $totactualoutput;
                $defectiveproduct   = 0;
                $goodoutput         = $totgoodoutput;
                $availabilityfactor = $avgaf;
                $performancefactor  = $avgpf;
                $qualityfactor      = $avgqf;
                $oee                = $avgaf * $avgpf * $avgqf;
            }

            array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                                            'vckodemesin' => $dtmesin->vckode,
                                            'vcmesin' => $dtmesin->vcnama,
                                            'statusmesin' => $statusmesin,
                                            'avgoee' => round(($oee * 100),2)));

            $totaf  = $totaf + $availabilityfactor;
            $totpf  = $totpf + $performancefactor;
            $totqf  = $totqf + $qualityfactor;
            $totoee = $totoee + $oee;
            $jmlmesin++;
        }

        $avgaf  = ($jmlmesin == 0) ? 0 : $totaf/$jmlmesin;
        $avgpf  = ($jmlmesin == 0) ? 0 : $totpf/$jmlmesin;
        $avgqf  = ($jmlmesin == 0) ? 0 : $totqf/$jmlmesin;
        // $avgoee = ($jmlmesin == 0) ? 0 : round(($totoee/$jmlmesin),2);
        $avgoee = $avgaf * $avgpf * $avgqf;

        $data['avgaf']       = round(($avgaf*100),2);
        $data['avgpf']       = round(($avgpf*100),2);
        $data['avgqf']       = round(($avgqf*100),2);
        $data['avgoee']      = round(($avgoee*100),2);
        $data['oee']         = $datapermesin;
        $data['datest']      = $datest;
        $data['datefs']      = $datefs;
        $data['intrealtime'] = $intrealtime;
        $data['btnreal']     = $btnreal;
        $data['btnhistory']  = $btnhistory;
        $data['hidedate']    = $hidedate;
        $data['intgedung']   = $intgedung;
        $data['gedung']      = $datagedung;
        $data['jumlahcell']  = count($dtcell);
        $data['cell']        = $datacell;
        $this->load->view('monitoring_view/index',$data);
    }

    function machine($intmesin, $datest='', $datefs=''){
        $data['intmesin'] = $intmesin;
        $this->load->view('monitoring_view/index',$data);
    }

    function machine_ajax($intmesin, $datest='', $datefs=''){
        $intshift         = getshift(strtotime(date('H:i:s')));
        $datamesin        = $this->modelapp->getdatadetail('m_mesin',$intmesin);
        $intgedung        = $datamesin[0]->intgedung;
        $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $availabletime1   = 0;
        $availabletime2   = 0;
        $istirahat1       = 0;
        $istirahat2       = 0;

        if ($datest == '' && $datefs == '') {
            $intrealtime = 1;
            $btnreal     = 'btn-success';
            $btnhistory  = 'btn-default';
            $datest      = date('m/d/Y');
            $datefs      = date('m/d/Y');
            $hidedate    = 'hidden';
        } else {
            $intrealtime = 0;
            $btnreal     = 'btn-default';
            $btnhistory  = 'btn-success';
            $datest      = date('m/d/Y',strtotime($datest));
            $datefs      = date('m/d/Y',strtotime($datefs));
            $hidedate    = '';
        }

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        $datenow  = date('Y-m-d');
        $timenow  = date('H:is');
        // $intshift = 1;
        if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $worksift1);
            $date2      = date('Y-m-d H:i:s');
            $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
            // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
            $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
            // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
        }

        if ($intshift == 1) {
            $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
            $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
            $availabletime2 = 0;
            $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
            $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            $dataoutput2    = [];
            $listdowntime2  = [];
        } else {
            $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
            $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
            $availabletime2 = 0;
            $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
            $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
            $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
        }

        $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
        $vcnik      = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcnik : '';

        $availabletime      = $availabletime1 + $availabletime2 - ($istirahat1 + $istirahat2);
        $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
        $idletime           = $datadowntime[0]->decprosestime;
        $totaldowntime      = $datadowntime[0]->decdurasi;
        $runtime            = $availabletime - $totaldowntime;
        $theoriticalct      = $output[0]->decct;
        // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
        $theoriticaloutput  = round($output[0]->inttarget);
        $actualoutput       = $output[0]->intactual;
        $defectiveproduct   = $output[0]->intreject;
        $goodoutput         = $actualoutput - $defectiveproduct;
        $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
        $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
        $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

        if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
            $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
            $af                   = 0;
            $pf                   = 0;
            $qf                   = 0;
            $totruntime           = 0;
            $totavailabletime     = 0;
            $totactualoutput      = 0;
            $tottheoriticaloutput = 0;
            $totgoodoutput        = 0;
            $loop                 = 0;
            for ($i=0; $i <= $datediff ; $i++) {
                $dt = $i + 1;
                $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                // echo $date1. ' -  '.$date2 . '<br>';
                $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                $jamkerja1          = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                $jamkerja2          = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                $jam1               = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                $jam2               = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                $availabletime      = $jam1 + $jam2;
                $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                $idletime           = $datadowntime[0]->decprosestime;
                $totaldowntime      = $datadowntime[0]->decdurasi;
                $runtime            = $availabletime - $totaldowntime;
                $theoriticalct      = $output[0]->decct;
                $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $actualoutput       = $output[0]->intactual;
                $defectiveproduct   = $output[0]->intreject;
                $goodoutput         = $actualoutput - $defectiveproduct;
                $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
                $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                $loop++;
                $af                   = $af + $availabilityfactor;
                $pf                   = $pf + $performancefactor;
                $qf                   = $qf + $qualityfactor;
                $totruntime           = $totruntime + $runtime;
                $totavailabletime     = $totavailabletime + $availabletime;
                $totactualoutput      = $totactualoutput + $actualoutput;
                $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                $totgoodoutput        = $totgoodoutput + $goodoutput;
                // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
            }

            $avgaf = $af/$loop;
            $avgpf = $pf/$loop;
            $avgqf = $qf/$loop;

            $availabletime      = $totavailabletime;
            $machinebreackdown  = 0;
            $idletime           = 0;
            $totaldowntime      = 0;
            $runtime            = $totruntime;
            $theoriticalct      = 0;
            $theoriticaloutput  = $tottheoriticaloutput;
            $actualoutput       = $totactualoutput;
            $defectiveproduct   = 0;
            $goodoutput         = $totgoodoutput;
            $availabilityfactor = $avgaf;
            $performancefactor  = $avgpf;
            $qualityfactor      = $avgqf;
            $oee                = $avgaf * $avgpf * $avgqf;

        }

        $tottarget1       = 0;
        $totoutput1       = 0;
        $totreject1       = 0;
        $totalover1       = 0;
        $totalless1       = 0;
        $durasioutput1    = 0;
        $totnotfollowsop1 = 0;
        $totfollowsop1    = 0;
        $totoutputactual1 = 0;
        $loop1            = 0;
        foreach ($dataoutput1 as $output1) {
            if ($output1->vcketerangan != '') {
                $notfollowsop     = ($output1->inttarget - $output1->intpasang) == 0 ? 0 : ($output1->inttarget - $output1->intpasang) * -1;
                $totnotfollowsop1 = $totnotfollowsop1 + $notfollowsop;
            } else {
                $followsop     = ($output1->inttarget - $output1->intpasang) == 0 ? 0 : ($output1->inttarget - $output1->intpasang) * -1;
                $totfollowsop1 = $totfollowsop1 + $followsop;
            }

            $intactual  = ($output1->intpasang > $output1->inttarget) ? $output1->inttarget : $output1->intpasang;
            $tottarget1 = $tottarget1 + $output1->inttarget;
            $totoutput1 = $totoutput1 + $intactual;
            $totoutputactual1 = $totoutputactual1 + $output1->intpasang; 
            $totreject1 = $totreject1 + $output1->intreject;
            if ($loop1 > 0) {
                $before1 = $loop1 - 1;
                if ($dataoutput1[$before1]->dtmulai != $output1->dtmulai) {
                    $durasioutput1 = $durasioutput1 + $output1->decdurasi;    
                }
            } else {
                $durasioutput1 = $durasioutput1 + $output1->decdurasi;
            }

            $loop1++;
        }

        $tottarget2       = 0;
        $totoutput2       = 0;
        $totreject2       = 0;
        $totalover2       = 0;
        $totalless2       = 0;
        $durasioutput2    = 0;
        $totnotfollowsop2 = 0;
        $totfollowsop2    = 0;
        $loop2            = 0;
        foreach ($dataoutput2 as $output2) {
            if ($output2->vcketerangan != '') {
                $notfollowsop     = ($output2->inttarget - $output2->intpasang) == 0 ? 0 : ($output2->inttarget - $output2->intpasang) * -1;
                $totnotfollowsop2 = $totnotfollowsop2 + $notfollowsop;
            } else {
                $followsop     = ($output2->inttarget - $output2->intpasang) == 0 ? 0 : ($output2->inttarget - $output2->intpasang) * -1;
                $totfollowsop2 = $totfollowsop2 + $followsop;
            }

            $intactual  = ($output2->intpasang > $output2->inttarget) ? $output2->inttarget : $output2->intpasang;
            $tottarget2 = $tottarget2 + $output2->inttarget;
            $totoutput2 = $totoutput2 + $intactual;
            $totoutputactual2 = $totoutputactual2 + $output2->intpasang; 
            $totreject2 = $totreject2 + $output2->intreject;
            if ($loop2 > 0) {
                $before2 = $loop2 - 1;
                if ($dataoutput2[$before2]->dtmulai != $output2->dtmulai) {
                    $durasioutput2 = $durasioutput2 + $output2->decdurasi;    
                }
            } else {
                $durasioutput2 = $durasioutput2 + $output2->decdurasi;
            }

            $loop2++;
        }

        $totdurasi1 = 0;
        foreach ($listdowntime1 as $downtime1) {
            $totdurasi1 = $totdurasi1 + $downtime1->decdurasi;
        }

        $totdurasi2 = 0;
        foreach ($listdowntime2 as $downtime2) {
            $totdurasi2 = $totdurasi2 + $downtime2->decdurasi;
        }

        $data['vckodemesin']        = $datamesin[0]->vckode;
        $data['vcmesin']            = $datamesin[0]->vcnama;
        $data['vcnik']              = $vcnik;
        $data['vcoperator']         = $vcoperator;
        $data['runtime']            = $runtime;
        $data['plannedproduction']  = $availabletime;
        $data['actualoutput']       = $actualoutput;
        $data['theoriticaloutput']  = $theoriticaloutput;
        $data['goodoutput']         = $goodoutput;
        $data['availabilityfactor'] = round(($availabilityfactor*100),2);
        $data['performancefactor']  = round(($performancefactor*100),2);
        $data['qualityfactor']      = round(($qualityfactor*100),2);
        $data['oee']                = round(($oee * 100),2);
        $data['listoutput1']        = $dataoutput1;
        $data['listdowntime1']      = $listdowntime1;
        $data['listoutput2']        = $dataoutput2;
        $data['listdowntime2']      = $listdowntime2;
        $data['intshift']           = $intshift;
        $data['datest']             = $datest;
        $data['datefs']             = $datefs;
        $data['intrealtime']        = $intrealtime;
        $data['btnreal']            = $btnreal;
        $data['btnhistory']         = $btnhistory;
        $data['hidedate']           = $hidedate;
        $data['intmesin']           = $intmesin;
        $data['intgedung']          = $datamesin[0]->intgedung;
        $data['intmesin']           = $intmesin;
        $data['tottarget1']         = $tottarget1;
        $data['totoutput1']         = $totoutput1;
        $data['totreject1']         = $totreject1;
        $data['tottarget2']         = $tottarget2;
        $data['totoutput2']         = $totoutput2;
        $data['totreject2']         = $totreject2;
        $data['totdurasi1']         = $totdurasi1;
        $data['totdurasi2']         = $totdurasi2;
        $data['durasioutput1']      = $durasioutput1;
        $data['durasioutput2']      = $durasioutput2;
        $data['totnotfollowsop1']   = $totnotfollowsop1;
        $data['totfollowsop1']      = $totfollowsop1;
        $data['totnotfollowsop2']   = $totnotfollowsop2;
        $data['totfollowsop2']      = $totfollowsop2;
        $data['totoutputactual1']   = $totoutputactual1;
        $this->load->view('monitoring_view/index',$data);
    }

    function machine_($intgedung,$intmesin,$datest='',$datefs=''){
        $data['intgedung'] = $intgedung;
        $data['intmesin']  = $intmesin;
        $this->load->view('monitoring_view/index',$data);
    }

    function machine__ajax($intgedung,$intmesin,$datest='',$datefs=''){
        $intshift         = getshift(strtotime(date('H:i:s')));
        $datamesin        = $this->modelapp->getdatadetail('m_mesin',$intmesin);
        $intgedung        = $datamesin[0]->intgedung;
        $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $availabletime1   = 0;
        $availabletime2   = 0;
        $istirahat1       = 0;
        $istirahat2       = 0;

        if ($datest == '' && $datefs == '') {
            $intrealtime = 1;
            $btnreal     = 'btn-success';
            $btnhistory  = 'btn-default';
            $datest      = date('m/d/Y');
            $datefs      = date('m/d/Y');
            $hidedate    = 'hidden';
        } else {
            $intrealtime = 0;
            $btnreal     = 'btn-default';
            $btnhistory  = 'btn-success';
            $datest      = date('m/d/Y',strtotime($datest));
            $datefs      = date('m/d/Y',strtotime($datefs));
            $hidedate    = '';
        }

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        $datenow  = date('Y-m-d');
        $timenow  = date('H:is');
        // $intshift = 1;
        if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $worksift1);
            $date2      = date('Y-m-d H:i:s');
            $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
            // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
            $datalogout = $this->model->getlogout($date1, $date2, $intmesin, $intshift);
            // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
        }

        if ($intshift == 1) {
            $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
            $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
            $availabletime2 = 0;
            $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
            $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            $dataoutput2    = [];
            $listdowntime2  = [];
        } else {
            $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
            $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
            $availabletime2 = 0;
            $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
            $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
            $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
        }

        $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
        $vcnik      = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcnik : '';

        $availabletime      = $availabletime1 + $availabletime2 - ($istirahat1 + $istirahat2);
        $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
        $idletime           = $datadowntime[0]->decprosestime;
        $totaldowntime      = $datadowntime[0]->decdurasi;
        $runtime            = $availabletime - $totaldowntime;
        $theoriticalct      = $output[0]->decct;
        // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
        $theoriticaloutput  = round($output[0]->inttarget);
        $actualoutput       = $output[0]->intactual;
        $defectiveproduct   = $output[0]->intreject;
        $goodoutput         = $actualoutput - $defectiveproduct;
        $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
        $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
        $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

        if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
            $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
            $af                   = 0;
            $pf                   = 0;
            $qf                   = 0;
            $totruntime           = 0;
            $totavailabletime     = 0;
            $totactualoutput      = 0;
            $tottheoriticaloutput = 0;
            $totgoodoutput        = 0;
            $loop                 = 0;
            for ($i=0; $i <= $datediff ; $i++) {
                $dt = $i + 1;
                $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                // echo $date1. ' -  '.$date2 . '<br>';
                $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                $jamkerja1          = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                $jamkerja2          = $this->model->getjamkerja($date1, $date2, $intmesin, 2);
                $jam1               = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                $jam2               = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                $availabletime      = $jam1 + $jam2;
                $machinebreackdown  = $datadowntime[0]->decmachinedowntime;
                $idletime           = $datadowntime[0]->decprosestime;
                $totaldowntime      = $datadowntime[0]->decdurasi;
                $runtime            = $availabletime - $totaldowntime;
                $theoriticalct      = $output[0]->decct;
                $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $actualoutput       = $output[0]->intactual;
                $defectiveproduct   = $output[0]->intreject;
                $goodoutput         = $actualoutput - $defectiveproduct;
                $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
                $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                $loop++;
                $af                   = $af + $availabilityfactor;
                $pf                   = $pf + $performancefactor;
                $qf                   = $qf + $qualityfactor;
                $totruntime           = $totruntime + $runtime;
                $totavailabletime     = $totavailabletime + $availabletime;
                $totactualoutput      = $totactualoutput + $actualoutput;
                $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                $totgoodoutput        = $totgoodoutput + $goodoutput;
                // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
            }

            $avgaf = $af/$loop;
            $avgpf = $pf/$loop;
            $avgqf = $qf/$loop;

            $availabletime      = $totavailabletime;
            $machinebreackdown  = 0;
            $idletime           = 0;
            $totaldowntime      = 0;
            $runtime            = $totruntime;
            $theoriticalct      = 0;
            $theoriticaloutput  = $tottheoriticaloutput;
            $actualoutput       = $totactualoutput;
            $defectiveproduct   = 0;
            $goodoutput         = $totgoodoutput;
            $availabilityfactor = $avgaf;
            $performancefactor  = $avgpf;
            $qualityfactor      = $avgqf;
            $oee                = $avgaf * $avgpf * $avgqf;

        }

        $tottarget1       = 0;
        $totoutput1       = 0;
        $totreject1       = 0;
        $totalover1       = 0;
        $totalless1       = 0;
        $durasioutput1    = 0;
        $totnotfollowsop1 = 0;
        $totfollowsop1    = 0;
        $totoutputactual1 = 0;
        $loop1            = 0;
        foreach ($dataoutput1 as $output1) {
            if ($output1->vcketerangan != '') {
                $notfollowsop     = ($output1->inttarget - $output1->intpasang) == 0 ? 0 : ($output1->inttarget - $output1->intpasang) * -1;
                $totnotfollowsop1 = $totnotfollowsop1 + $notfollowsop;
            } else {
                $followsop     = ($output1->inttarget - $output1->intpasang) == 0 ? 0 : ($output1->inttarget - $output1->intpasang) * -1;
                $totfollowsop1 = $totfollowsop1 + $followsop;
            }

            $intactual  = ($output1->intpasang > $output1->inttarget) ? $output1->inttarget : $output1->intpasang;
            $tottarget1 = $tottarget1 + $output1->inttarget;
            $totoutput1 = $totoutput1 + $intactual;
            $totoutputactual1 = $totoutputactual1 + $output1->intpasang; 
            $totreject1 = $totreject1 + $output1->intreject;
            if ($loop1 > 0) {
                $before1 = $loop1 - 1;
                if ($dataoutput1[$before1]->dtmulai != $output1->dtmulai) {
                    $durasioutput1 = $durasioutput1 + $output1->decdurasi;    
                }
            } else {
                $durasioutput1 = $durasioutput1 + $output1->decdurasi;
            }

            $loop1++;
        }

        $tottarget2       = 0;
        $totoutput2       = 0;
        $totreject2       = 0;
        $totalover2       = 0;
        $totalless2       = 0;
        $durasioutput2    = 0;
        $totnotfollowsop2 = 0;
        $totfollowsop2    = 0;
        $loop2            = 0;
        foreach ($dataoutput2 as $output2) {
            if ($output2->vcketerangan != '') {
                $notfollowsop     = ($output2->inttarget - $output2->intpasang) == 0 ? 0 : ($output2->inttarget - $output2->intpasang) * -1;
                $totnotfollowsop2 = $totnotfollowsop2 + $notfollowsop;
            } else {
                $followsop     = ($output2->inttarget - $output2->intpasang) == 0 ? 0 : ($output2->inttarget - $output2->intpasang) * -1;
                $totfollowsop2 = $totfollowsop2 + $followsop;
            }

            $intactual  = ($output2->intpasang > $output2->inttarget) ? $output2->inttarget : $output2->intpasang;
            $tottarget2 = $tottarget2 + $output2->inttarget;
            $totoutput2 = $totoutput2 + $intactual;
            $totoutputactual2 = $totoutputactual2 + $output2->intpasang; 
            $totreject2 = $totreject2 + $output2->intreject;
            if ($loop2 > 0) {
                $before2 = $loop2 - 1;
                if ($dataoutput2[$before2]->dtmulai != $output2->dtmulai) {
                    $durasioutput2 = $durasioutput2 + $output2->decdurasi;    
                }
            } else {
                $durasioutput2 = $durasioutput2 + $output2->decdurasi;
            }

            $loop2++;
        }

        $totdurasi1 = 0;
        foreach ($listdowntime1 as $downtime1) {
            $totdurasi1 = $totdurasi1 + $downtime1->decdurasi;
        }

        $totdurasi2 = 0;
        foreach ($listdowntime2 as $downtime2) {
            $totdurasi2 = $totdurasi2 + $downtime2->decdurasi;
        }

        $data['vckodemesin']        = $datamesin[0]->vckode;
        $data['vcmesin']            = $datamesin[0]->vcnama;
        $data['vcnik']              = $vcnik;
        $data['vcoperator']         = $vcoperator;
        $data['runtime']            = $runtime;
        $data['plannedproduction']  = $availabletime;
        $data['actualoutput']       = $actualoutput;
        $data['theoriticaloutput']  = $theoriticaloutput;
        $data['goodoutput']         = $goodoutput;
        $data['availabilityfactor'] = round(($availabilityfactor*100),2);
        $data['performancefactor']  = round(($performancefactor*100),2);
        $data['qualityfactor']      = round(($qualityfactor*100),2);
        $data['oee']                = round(($oee * 100),2);
        $data['listoutput1']        = $dataoutput1;
        $data['listdowntime1']      = $listdowntime1;
        $data['listoutput2']        = $dataoutput2;
        $data['listdowntime2']      = $listdowntime2;
        $data['intshift']           = $intshift;
        $data['datest']             = $datest;
        $data['datefs']             = $datefs;
        $data['intrealtime']        = $intrealtime;
        $data['btnreal']            = $btnreal;
        $data['btnhistory']         = $btnhistory;
        $data['hidedate']           = $hidedate;
        $data['intmesin']           = $intmesin;
        $data['intgedung']          = $datamesin[0]->intgedung;
        $data['intmesin']           = $intmesin;
        $data['tottarget1']         = $tottarget1;
        $data['totoutput1']         = $totoutput1;
        $data['totreject1']         = $totreject1;
        $data['tottarget2']         = $tottarget2;
        $data['totoutput2']         = $totoutput2;
        $data['totreject2']         = $totreject2;
        $data['totdurasi1']         = $totdurasi1;
        $data['totdurasi2']         = $totdurasi2;
        $data['durasioutput1']      = $durasioutput1;
        $data['durasioutput2']      = $durasioutput2;
        $data['totnotfollowsop1']   = $totnotfollowsop1;
        $data['totfollowsop1']      = $totfollowsop1;
        $data['totnotfollowsop2']   = $totnotfollowsop2;
        $data['totfollowsop2']      = $totfollowsop2;
        $data['totoutputactual1']   = $totoutputactual1;
        $this->load->view('monitoring_view/index',$data);
    }

    function dashboard_bak(){
        $datagedung   = $this->modelapp->getdatalist('m_gedung');
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $startupdt    = $this->modelapp->getappsetting('startup')[0]->vcvalue;
        $shutdowndt   = $this->modelapp->getappsetting('shutdown')[0]->vcvalue;

        $shift1ts = '07:00:00';
        $shift2ts = '20:00:00';

        foreach ($datagedung as $gedung) {
            $datamesin = $this->model->getdatamesin($intgedung);
        }

        $data = array(
                    'datestart'          => '2019-01-01', 
                    'availabletime'      => 0, 
                    'plannedproduction'  => 0, 
                    'totaldowntime'      => 0, 
                    'runtime'            => 0, 
                    'theoriticalct'      => 0, 
                    'theoriticaloutput'  => 0, 
                    'actualoutput'       => 0, 
                    'defectiveproduct'   => 0, 
                    'availabilityfactor' => 0, 
                    'performancefactor'  => 0, 
                    'qualityfactor'      => 0, 
                    'oee'                => 0 
            );

        $data['title']      = 'Dashboard';
        $data['controller'] = 'operator';
        $data['listgedung'] = $datagedung;

        $this->load->view('monitoring_view/index',$data);
    }

    function validasi_password($vcpassword) {
        $intid = $this->session->intidoee;

        $data = $this->model->validasi_password($intid, md5($vcpassword));
        
        echo json_encode($data);
    }

    function change_password($vcpassword) {
        $intid = $this->session->intidoee;

        $dataupdate = array(
                    'vcpassword' => md5($vcpassword)
                );

        $data = $this->modelapp->updatedata('app_muser',$dataupdate,$intid);

        echo json_encode($data);
    }
}
