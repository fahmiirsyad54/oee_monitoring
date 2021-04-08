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
        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index');
    }

    function dashboard_ajax($datest='',$datefs=''){
        $shift1start   = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1finish  = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        
        $shift2start1  = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $shift2start2  = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
        $shift2finish1 = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
        $shift2finish2 = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;
        $break         = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplus     = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;
        $kalibrasi     = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
        $meeting       = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
        $sm            = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;
        
        $timenow       = date('H:i:s');
        $intshift      = 0;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $intshift = 1;
        } else {
          $intshift = 2;
        }

        //$intshift = getshift(strtotime(date('H:i:s')));
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
        //$intshift       = 1;
        $worksift1      = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $endsift1       = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        $worksift2      = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $end1sift2      = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
        $work2sift2     = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
        $end2sift2      = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;

        $availabletime1   = 0;
        $availabletime2   = 0;
        $istirahat1       = 0;
        $listgedung       = $this->model->getdatagedung('m_gedung');
        $datapergedung    = array();
        $avgtotaf         = 0;
        $avgtotpf         = 0;
        $avgtotqf         = 0;
        $loopgedung       = 0;
        
        $availabletime12  = 0;
        $istirahat12      = 0;
        $datapergedung2   = array();
        $avgtotaf2        = 0;
        $avgtotpf2        = 0;
        $avgtotqf2        = 0;
        $loopgedung2      = 0;
        foreach ($listgedung as $dtgedung) {
            $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
            $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;

            $totalaf          = 0;
            $totalpf          = 0;
            $totalqf          = 0;
            $totaloee         = 0;
            $totalmesin       = 0;
            $listmesin        = $this->model->getdatamesin($dtgedung->intid);

            $totalaf2          = 0;
            $totalpf2          = 0;
            $totalqf2          = 0;
            $totaloee2         = 0;
            $totalmesin2       = 0;
            $listmesin2        = $this->model->getdatamesin2($dtgedung->intid);
            
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
                } else {
                    $date1 = date('Y-m-d H:i:s');
                    $date2 = date('Y-m-d H:i:s');
                }

                if ($timenow >= $worksift1 && $timenow <= '19:59:59') {
                    $datestart = date('Y-m-d ' . $worksift1);
                } elseif ($timenow >= '20:00:00' && $timenow <= '23:59:59') {
                    $datestart = date('Y-m-d ' . $worksift2);
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $datestart = date('Y-m-d ' . $worksift2, strtotime('-1 day', strtotime(date('Y-m-d'))));
                }

                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $break : 0;
                    $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplus : 0;
                    //$datadowntime = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                    //$output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                    $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                    $availabletime1 = 0;
                    $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($datestart))/60);
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? $break : 0;
                    $istirahatplus  = 0;
                    //$datadowntime = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                    //$output       = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                    $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';

                //kalibrasi
                $pdkalibrasi   = 0;
                $dtkalibrasi   = 0;
                $plankalibrasi = 0;   
                $deckalibrasi  = $datadowntime[0]->deckalibrasi;
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
                $decmeeting  = $datadowntime[0]->decmeeting;
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
                $decsm  = $datadowntime[0]->decsm;
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

                $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - ($totplan);
                $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
                $idletime             = $datadowntime[0]->decprosestime;
                $totaldowntime        = $datadowntime[0]->decdurasi + ($totdt);
                $runtime              = $availabletime - $totaldowntime;
                $theoriticalct        = $output[0]->decct;
                // $theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput    = round($output[0]->inttarget);
                $actualoutput         = $output[0]->intactual;
                $defectiveproduct     = $output[0]->intreject;
                $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
                $oee                  = $availabilityfactor*$performancefactor*$qualityfactor;

                //OEE history tanggal
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

                if (count($dataoperator) > 0) {
                    $totalaf  = $totalaf + $availabilityfactor;
                    $totalpf  = $totalpf + $performancefactor;
                    $totalqf  = $totalqf + $qualityfactor;
                    // $totaloee = $totaloee + $oee;
                    $totalmesin++;
                }
                
            }

            foreach ($listmesin2 as $dtmesin2) {
                $intmesin2 = $dtmesin2->intid;
                if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1);
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                }

                if ($intshift == 1) {
                    $dataoperator2    = $this->model->getoperator($date1,$date2,$intmesin2, $intshift, 1);
                    $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat12     = (date('H:i:s') > '13:00:00' && $availabletime12 > 0) ? 60 : 0;
                    $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2,$intshift);
                    $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                    $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
                    $dataoutput22    = [];
                    $listdowntime22  = [];
                } else {
                    $dataoperator2    = $this->model->getoperator($date1,$date2,$intmesin2, $intshift, 1);
                    $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat12     = (date('H:i:s') > '02:00:00' && $availabletime12 > 0) ? 60 : 0;
                    $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                    $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,1);
                    $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,1);
                    $dataoutput22    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                    $listdowntime22  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
                }

                $vcoperator = (count($dataoperator2) > 0 ) ? $dataoperator2[0]->vcoperator : '';

                $availabletime2      = $availabletime12 - ($istirahat12);
                $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                $idletime2           = $datadowntime2[0]->decprosestime;
                $totaldowntime2      = $datadowntime2[0]->decdurasi;
                $runtime2            = $availabletime2 - $totaldowntime2;
                $theoriticalct2      = $output2[0]->decct;
                // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput2  = round($output2[0]->inttarget);
                $actualoutput2       = $output2[0]->intactual;
                $defectiveproduct2   = $output2[0]->intreject;
                $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($output2[0]->intactual - $output2[0]->intreject)/$actualoutput2;
                $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                //OEE history tanggal
                if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                    $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                    $af2                   = 0;
                    $pf2                   = 0;
                    $qf2                   = 0;
                    $totruntime2           = 0;
                    $totavailabletime2     = 0;
                    $totactualoutput2      = 0;
                    $tottheoriticaloutput2 = 0;
                    $totgoodoutput2        = 0;
                    $loop2                 = 0;
                    for ($i=0; $i <= $datediff ; $i++) {
                        $dt = $i + 1;
                        $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                        $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                        // echo $date1. ' -  '.$date2 . '<br>';
                        $datadowntime2       = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                        $output2             = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                        $jamkerja1           = $this->model->getjamkerja($date1, $date2, $intmesin2, 1);
                        $jamkerja2           = $this->model->getjamkerja($date1, $date2, $intmesin2, 2);
                        $jam1                = (count($jamkerja1) == 0) ? 0 : $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur - 60;
                        $jam2                = (count($jamkerja2) == 0) ? 0 : $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur - 60;
                        $availabletime2      = $jam1 + $jam2;
                        $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                        $idletime2           = $datadowntime2[0]->decprosestime;
                        $totaldowntime2      = $datadowntime2[0]->decdurasi;
                        $runtime2            = $availabletime2 - $totaldowntime2;
                        $theoriticalct2      = $output2[0]->decct;
                        $theoriticaloutput2  = ($theoriticalct2 == 0) ? 0 : ceil(60/$theoriticalct2*$runtime2);
                        $actualoutput2       = $output2[0]->intactual;
                        $defectiveproduct2   = $output2[0]->intreject;
                        $goodoutput2         = $actualoutput2 - $defectiveproduct2;
                        $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                        $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                        $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($goodoutput2)/$actualoutput2;
                        $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                        $loop2++;
                        $af2                   = $af2 + $availabilityfactor2;
                        $pf2                   = $pf2 + $performancefactor2;
                        $qf2                   = $qf2 + $qualityfactor2;
                        $totruntime2           = $totruntime2 + $runtime2;
                        $totavailabletime2     = $totavailabletime2 + $availabletime2;
                        $totactualoutput2      = $totactualoutput2 + $actualoutput2;
                        $tottheoriticaloutput2 = $tottheoriticaloutput2 + $theoriticaloutput2;
                        $totgoodoutput2        = $totgoodoutput2 + $goodoutput2;
                        // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                    }

                    $avgaf2 = $af2/$loop2;
                    $avgpf2 = $pf2/$loop2;
                    $avgqf2 = $qf2/$loop2;

                    $availabletime2      = $totavailabletime2;
                    $machinebreackdown2  = 0;
                    $idletime2           = 0;
                    $totaldowntime2      = 0;
                    $runtime2            = $totruntime2;
                    $theoriticalct2      = 0;
                    $theoriticaloutput2  = $tottheoriticaloutput2;
                    $actualoutput2       = $totactualoutput2;
                    $defectiveproduct2   = 0;
                    $goodoutput2         = $totgoodoutput2;
                    $availabilityfactor2 = $avgaf2;
                    $performancefactor2  = $avgpf2;
                    $qualityfactor2      = $avgqf2;
                    $oee2                = $avgaf2 * $avgpf2 * $avgqf2;
                }

                if (count($dataoperator2) > 0) {
                    $totalaf2  = $totalaf2 + $availabilityfactor2;
                    $totalpf2  = $totalpf2 + $performancefactor2;
                    $totalqf2  = $totalqf2 + $qualityfactor2;
                    // $totaloee = $totaloee + $oee;
                    $totalmesin2++;
                }
                
            }

            $avgaf = ($totalmesin == 0) ? 0 : $totalaf/$totalmesin;
            $avgpf = ($totalmesin == 0) ? 0 : $totalpf/$totalmesin;
            $avgqf = ($totalmesin == 0) ? 0 : $totalqf/$totalmesin;
            // $avgoee = ($totalmesin == 0) ? 0 : round($totaloee/$totalmesin,2);
            $avgoee = $avgaf * $avgpf * $avgqf;

            $avgaf2 = ($totalmesin2 == 0) ? 0 : $totalaf2/$totalmesin2;
            $avgpf2 = ($totalmesin2 == 0) ? 0 : $totalpf2/$totalmesin2;
            $avgqf2 = ($totalmesin2 == 0) ? 0 : $totalqf2/$totalmesin2;
            // $avgoee = ($totalmesin == 0) ? 0 : round($totaloee/$totalmesin,2);
            $avgoee2 = $avgaf2 * $avgpf2 * $avgqf2;

            // $avgoeeok = ($avgoee+$avgoee2)/2;
            // $avgafok  = ($avgaf+$avgaf2)/2;
            // $avgqfok  = ($avgqf+$avgqf2)/2;
            // $avgqfok  = ($avgqf+$avgqf2)/2; 
            
            $avgtotaf = $avgtotaf + $avgaf;
            $avgtotpf = $avgtotpf + $avgpf;
            $avgtotqf = $avgtotqf + $avgqf;
            $loopgedung++;
           

            array_push($datapergedung, array('intgedung' => $dtgedung->intid,'vcgedung' => $dtgedung->vcnama,'avgoee' => round(($avgoee*100),2)));
        }
        //print_r($avgaf); exit();

        $avgaftot  = ($loopgedung == 0) ? 0 : $avgtotaf/$loopgedung;
        $avgpftot  = ($loopgedung == 0) ? 0 : $avgtotpf/$loopgedung;
        $avgqftot  = ($loopgedung == 0) ? 0 : $avgtotqf/$loopgedung;
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

        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function buildingall($intgedung=0,$datest='',$datefs=''){
        $data['intgedung'] = $intgedung;
        $monitoring        = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function buildingall_ajax($intgedung=0,$datest='',$datefs=''){
        $dtcell = $this->model->getcentralcutting($intgedung);
        //$intshift = getshift(strtotime(date('H:i:s')));

        $shift1start   = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1finish  = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        
        $shift2start1  = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $shift2start2  = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
        $shift2finish1 = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
        $shift2finish2 = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;
        
        $timenow       = date('H:i:s');
        $intshift      = 0;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $intshift = 1;
        } else {
          $intshift = 2;
        }

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
        $datagedung       = $this->modelapp->getdatadetail('m_gedung',$intgedung);
        //oee comelz
        $availabletime1   = 0;
        $istirahat1       = 0;
        $totaf            = 0;
        $totpf            = 0;
        $totqf            = 0;
        $totoee           = 0;
        $jmlmesin         = 0;

        //oee laser
        $availabletime12   = 0;
        $istirahat12       = 0;
        $totaf2            = 0;
        $totpf2            = 0;
        $totqf2            = 0;
        $totoee2           = 0;
        $jmlmesin2         = 0;
        

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        $datacell = [];
        $loopcell = 0;

        $datacell2 = [];
        $loopcell2 = 0;

        foreach ($dtcell as $cell) {
            $intcell      = $cell->intid;
            $datapermesin = array();
            $listmesin    = $this->model->getdatamesin($intgedung,$intcell);
            $totafcell    = 0;
            $totpfcell    = 0;
            $totqfcell    = 0;
            $totoeecell   = 0;
            $jmlmesincell = 0;

            $listmesin2    = $this->model->getdatamesin2($intgedung,$intcell);
            $totafcell2    = 0;
            $totpfcell2    = 0;
            $totqfcell2    = 0;
            $totoeecell2   = 0;
            $jmlmesincell2 = 0;

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
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator  = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    //$dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $availabletime      = $availabletime1 - ($istirahat1);
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

                // array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                //                                 'vckodemesin' => $dtmesin->vckode,
                //                                 'vcmesin' => $dtmesin->vcnama,
                //                                 'statusmesin' => $statusmesin,
                //                                 'avgoee' => round(($oee * 100),2)));

                if (count($dataoperator) > 0) {
                    $totafcell  = $totafcell + $availabilityfactor;
                    $totpfcell  = $totpfcell + $performancefactor;
                    $totqfcell  = $totqfcell + $qualityfactor;
                    $totoeecell = $totoeecell + $oee;
                    $jmlmesincell++;
                }
            }

            foreach ($listmesin2 as $dtmesin2) {
                $intmesin2 = $dtmesin2->intid;
                if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1);
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                }

                if ($intshift == 1) {
                    $dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                    $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat12     = (date('H:i:s') > '13:00:00' && $availabletime12 > 0) ? 60 : 0;
                    $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2,$intshift);
                    $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                    $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
                    $dataoutput22    = [];
                    $listdowntime22  = [];
                } else {
                    $dataoperator2  = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                    //$dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 2);
                    $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat12     = (date('H:i:s') > '02:00:00' && $availabletime12 > 0) ? 60 : 0;
                    $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                    $dataoutput12    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin2,1);
                    $listdowntime12  = $this->model->getdatadowntime2($date1,$date2,$intmesin2,1);
                    $dataoutput22    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin2,$intshift);
                    $listdowntime22  = $this->model->getdatadowntime2($date1,$date2,$intmesin2,$intshift);
                }

                $availabletime2      = $availabletime1 - ($istirahat1);
                $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                $idletime2           = $datadowntime2[0]->decprosestime;
                $totaldowntime2      = $datadowntime2[0]->decdurasi;
                $runtime2            = $availabletime2 - $totaldowntime2;
                $theoriticalct2      = $output2[0]->decct;
                // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput2  = round($output2[0]->inttarget);
                $actualoutput2       = $output2[0]->intactual;
                $defectiveproduct2   = $output2[0]->intreject;
                $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($output2[0]->intactual - $output2[0]->intreject)/$actualoutput2;
                $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                    $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                    $af2                   = 0;
                    $pf2                   = 0;
                    $qf2                   = 0;
                    $totruntime2           = 0;
                    $totavailabletime2     = 0;
                    $totactualoutput2      = 0;
                    $tottheoriticaloutput2 = 0;
                    $totgoodoutput2        = 0;
                    $loop2                 = 0;
                    for ($i=0; $i <= $datediff ; $i++) {
                        $dt = $i + 1;
                        $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                        $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                        // echo $date1. ' -  '.$date2 . '<br>';
                        
                        $datadowntime2       = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                        $output2             = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                        $jamkerja12          = $this->model->getjamkerja($date1, $date2, $intmesin2, 1);
                        $jamkerja22          = $this->model->getjamkerja($date1, $date2, $intmesin2, 2);
                        $jam12               = (count($jamkerja12) == 0) ? 0 : $jamkerja12[0]->intjamkerja + $jamkerja12[0]->intjamlembur - 60;
                        $jam22               = (count($jamkerja22) == 0) ? 0 : $jamkerja22[0]->intjamkerja + $jamkerja22[0]->intjamlembur - 60;
                        $availabletime2      = $jam12 + $jam22;
                        $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                        $idletime2           = $datadowntime2[0]->decprosestime;
                        $totaldowntime2      = $datadowntime2[0]->decdurasi;
                        $runtime2            = $availabletime2 - $totaldowntime2;
                        $theoriticalct2      = $output2[0]->decct;
                        $theoriticaloutput2  = ($theoriticalct2 == 0) ? 0 : ceil(60/$theoriticalct2*$runtime2);
                        $actualoutput2       = $output2[0]->intactual;
                        $defectiveproduct2   = $output2[0]->intreject;
                        $goodoutput2         = $actualoutput2 - $defectiveproduct2;
                        $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                        $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                        $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($goodoutput2)/$actualoutput2;
                        $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                        $loop2++;
                        $af2                   = $af2 + $availabilityfactor2;
                        $pf2                   = $pf2 + $performancefactor2;
                        $qf2                   = $qf2 + $qualityfactor2;
                        $totruntime2           = $totruntime2 + $runtime2;
                        $totavailabletime2     = $totavailabletime2 + $availabletime2;
                        $totactualoutput2      = $totactualoutput2 + $actualoutput2;
                        $tottheoriticaloutput2 = $tottheoriticaloutput2 + $theoriticaloutput2;
                        $totgoodoutput2        = $totgoodoutput2 + $goodoutput2;
                        // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                    }
                    
                    $avgaf2 = $af2/$loop2;
                    $avgpf2 = $pf2/$loop2;
                    $avgqf2 = $qf2/$loop2;

                    $availabletime2      = $totavailabletime2;
                    $machinebreackdown2  = 0;
                    $idletime2           = 0;
                    $totaldowntime2      = 0;
                    $runtime2            = $totruntime2;
                    $theoriticalct2      = 0;
                    $theoriticaloutput2  = $tottheoriticaloutput2;
                    $actualoutput2       = $totactualoutput2;
                    $defectiveproduct2   = 0;
                    $goodoutput2         = $totgoodoutput2;
                    $availabilityfactor2 = $avgaf2;
                    $performancefactor2  = $avgpf2;
                    $qualityfactor2      = $avgqf2;
                    $oee2                = $avgaf2 * $avgpf2 * $avgqf2;
                }

                // array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                //                                 'vckodemesin' => $dtmesin->vckode,
                //                                 'vcmesin' => $dtmesin->vcnama,
                //                                 'statusmesin' => $statusmesin,
                //                                 'avgoee' => round(($oee * 100),2)));


                if (count($dataoperator2) > 0) {
                    $totafcell2  = $totafcell2 + $availabilityfactor2;
                    $totpfcell2  = $totpfcell2 + $performancefactor2;
                    $totqfcell2  = $totqfcell2 + $qualityfactor2;
                    $totoeecell2 = $totoeecell2 + $oee2;
                    $jmlmesincell2++;
                }
                
            }

            $avgafcell  = ($jmlmesincell == 0) ? 0 : $totafcell/$jmlmesincell;
            $avgpfcell  = ($jmlmesincell == 0) ? 0 : $totpfcell/$jmlmesincell;
            $avgqfcell  = ($jmlmesincell == 0) ? 0 : $totqfcell/$jmlmesincell;
            // $avgoeecell = ($jmlmesincell == 0) ? 0 : round(($totoeecell/$jmlmesincell),2);
            $avgoeecell = $avgafcell * $avgpfcell * $avgqfcell;
            ++$loopcell;

            $avgafcell2  = ($jmlmesincell2 == 0) ? 0 : $totafcell2/$jmlmesincell2;
            $avgpfcell2  = ($jmlmesincell2 == 0) ? 0 : $totpfcell2/$jmlmesincell2;
            $avgqfcell2  = ($jmlmesincell2 == 0) ? 0 : $totqfcell2/$jmlmesincell2;
            // $avgoeecell = ($jmlmesincell == 0) ? 0 : round(($totoeecell/$jmlmesincell),2);
            $avgoeecell2 = $avgafcell2 * $avgpfcell2 * $avgqfcell2;
            ++$loopcell2;


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
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                $dataoutput2    = [];
                $listdowntime2  = [];
            } else {
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                //$dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            }

            $availabletime      = $availabletime1 - ($istirahat1);
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

            //untuk history pertanggal
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
            
            if (count($dataoperator) > 0) {
                $totaf  = $totaf + $availabilityfactor;
                $totpf  = $totpf + $performancefactor;
                $totqf  = $totqf + $qualityfactor;
                $totoee = $totoee + $oee;
                $jmlmesin++;
            }
        }

        $listmesin2    = $this->model->getdatamesin2($intgedung);
        foreach ($listmesin2 as $dtmesin2) {
            $intmesin2 = $dtmesin2->intid;
            if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                $date1      = date('Y-m-d ' . $worksift1);
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            }

            if ($intshift == 1) {
                $dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $istirahat12     = (date('H:i:s') > '13:00:00' && $availabletime12 > 0) ? 60 : 0;
                $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2,$intshift);
                $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
                $dataoutput22    = [];
                $listdowntime22  = [];
            } else {
                $dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $istirahat12     = (date('H:i:s') > '02:00:00' && $availabletime12 > 0) ? 60 : 0;
                $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                $output22         = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,1);
                $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,1);
                $dataoutput22    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                $listdowntime22  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
            }

            $availabletime2      = $availabletime12  - ($istirahat12);
            $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
            $idletime2           = $datadowntime2[0]->decprosestime;
            $totaldowntime2      = $datadowntime2[0]->decdurasi;
            $runtime2            = $availabletime2 - $totaldowntime2;
            $theoriticalct2      = $output2[0]->decct;
            // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $theoriticaloutput2  = round($output2[0]->inttarget);
            $actualoutput2       = $output2[0]->intactual;
            $defectiveproduct2   = $output2[0]->intreject;
            $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
            $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
            $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($output2[0]->intactual - $output2[0]->intreject)/$actualoutput2;
            $oee2                = $availabilityfactor2 * $performancefactor2 * $qualityfactor2;

            //untuk history pertanggal
            if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                $af2                   = 0;
                $pf2                   = 0;
                $qf2                   = 0;
                $totruntime2           = 0;
                $totavailabletime2     = 0;
                $totactualoutput2      = 0;
                $tottheoriticaloutput2 = 0;
                $totgoodoutput2        = 0;
                $loop2                 = 0;
                for ($i=0; $i <= $datediff ; $i++) {
                    $dt = $i + 1;
                    $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                    $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                    // echo $date1. ' -  '.$date2 . '<br>';
                    
                    $datadowntime2       = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2             = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                    $jamkerja12          = $this->model->getjamkerja($date1, $date2, $intmesin2, 1);
                    $jamkerja22          = $this->model->getjamkerja($date1, $date2, $intmesin2, 2);
                    $jam12               = (count($jamkerja12) == 0) ? 0 : $jamkerja12[0]->intjamkerja + $jamkerja12[0]->intjamlembur - 60;
                    $jam22               = (count($jamkerja22) == 0) ? 0 : $jamkerja22[0]->intjamkerja + $jamkerja22[0]->intjamlembur - 60;
                    $availabletime2      = $jam12 + $jam22;
                    $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                    $idletime2           = $datadowntime2[0]->decprosestime;
                    $totaldowntime2      = $datadowntime2[0]->decdurasi;
                    $runtime2            = $availabletime2 - $totaldowntime2;
                    $theoriticalct2      = $output2[0]->decct;
                    $theoriticaloutput2  = ($theoriticalct2 == 0) ? 0 : ceil(60/$theoriticalct2*$runtime2);
                    $actualoutput2       = $output2[0]->intactual;
                    $defectiveproduct2   = $output2[0]->intreject;
                    $goodoutput2         = $actualoutput2 - $defectiveproduct2;
                    $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                    $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                    $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($goodoutput2)/$actualoutput2;
                    $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                    $loop2++;
                    $af2                   = $af2 + $availabilityfactor2;
                    $pf2                   = $pf2 + $performancefactor2;
                    $qf2                   = $qf2 + $qualityfactor2;
                    $totruntime2           = $totruntime2 + $runtime;
                    $totavailabletime2     = $totavailabletime2 + $availabletime2;
                    $totactualoutput2      = $totactualoutput2 + $actualoutput2;
                    $tottheoriticaloutput2 = $tottheoriticaloutput2 + $theoriticaloutput2;
                    $totgoodoutput2        = $totgoodoutput2 + $goodoutput2;
                }
                
                $avgaf2 = $af2/$loop2;
                $avgpf2 = $pf2/$loop2;
                $avgqf2 = $qf2/$loop2;
                
                $availabletime2      = $totavailabletime2;
                $machinebreackdown2  = 0;
                $idletime2           = 0;
                $totaldowntime2      = 0;
                $runtime2            = $totruntime2;
                $theoriticalct2      = 0;
                $theoriticaloutput2  = $tottheoriticaloutput2;
                $actualoutput2       = $totactualoutput2;
                $defectiveproduct2   = 0;
                $goodoutput2         = $totgoodoutput2;
                $availabilityfactor2 = $avgaf2;
                $performancefactor2  = $avgpf2;
                $qualityfactor2      = $avgqf2;
                $oee2                = $avgaf2 * $avgpf2 * $avgqf2;
            }
            

            if (count($dataoperator2) > 0) {
                $totaf2  = $totaf2 + $availabilityfactor2;
                $totpf2  = $totpf2 + $performancefactor2;
                $totqf2  = $totqf2 + $qualityfactor2;
                $totoee2 = $totoee2 + $oee2;
                $jmlmesin2++;
            }
            
        }

        //oee comelz
        $avgaf  = ($jmlmesin == 0) ? 0 : $totaf/$jmlmesin;
        $avgpf  = ($jmlmesin == 0) ? 0 : $totpf/$jmlmesin;
        $avgqf  = ($jmlmesin == 0) ? 0 : $totqf/$jmlmesin;
        // $avgoee = ($jmlmesin == 0) ? 0 : round(($totoee/$jmlmesin),2);
        $avgoee = $avgaf * $avgpf * $avgqf;

        //oee laser
        $avgaf2  = ($jmlmesin2 == 0) ? 0 : $totaf2/$jmlmesin2;
        $avgpf2  = ($jmlmesin2 == 0) ? 0 : $totpf2/$jmlmesin2;
        $avgqf2  = ($jmlmesin2 == 0) ? 0 : $totqf2/$jmlmesin2;
        // $avgoee = ($jmlmesin == 0) ? 0 : round(($totoee/$jmlmesin),2);
        $avgoee2 = $avgaf2 * $avgpf2 * $avgqf2;

        //oee comelz
        $data['avgaf']       = round(($avgaf*100),2);
        $data['avgpf']       = round(($avgpf*100),2);
        $data['avgqf']       = round(($avgqf*100),2);
        $data['avgoee']      = round(($avgoee*100),2);
        //oee laser
        

        $data['avgaf2']      = round(($avgaf2*100),2);
        $data['avgpf2']      = round(($avgpf2*100),2);
        $data['avgqf2']      = round(($avgqf2*100),2);
        $data['avgoee2']     = round(($avgoee2*100),2);
        
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

        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function buildingall_($intgedung=0,$datest='',$datefs=''){
        $data['intgedung'] = $intgedung;
        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function buildingall__ajax($intgedung=0,$datest='',$datefs=''){
        $dtcell = $this->model->getcentralcutting($intgedung);
        //$intshift = getshift(strtotime(date('H:i:s')));

        $shift1start   = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1finish  = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        
        $shift2start1  = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $shift2start2  = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
        $shift2finish1 = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
        $shift2finish2 = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;
        
        $timenow       = date('H:i:s');
        $intshift      = 0;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $intshift = 1;
        } else {
          $intshift = 2;
        }

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
        $datagedung       = $this->modelapp->getdatadetail('m_gedung',$intgedung);
        //oee comelz
        $availabletime1   = 0;
        $istirahat1       = 0;
        $totaf            = 0;
        $totpf            = 0;
        $totqf            = 0;
        $totoee           = 0;
        $jmlmesin         = 0;

        //oee laser
        $availabletime12   = 0;
        $istirahat12       = 0;
        $totaf2            = 0;
        $totpf2            = 0;
        $totqf2            = 0;
        $totoee2           = 0;
        $jmlmesin2         = 0;
        

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        $datacell = [];
        $loopcell = 0;

        $datacell2 = [];
        $loopcell2 = 0;

        foreach ($dtcell as $cell) {
            $intcell      = $cell->intid;
            $datapermesin = array();
            $listmesin    = $this->model->getdatamesin($intgedung,$intcell);
            $totafcell    = 0;
            $totpfcell    = 0;
            $totqfcell    = 0;
            $totoeecell   = 0;
            $jmlmesincell = 0;

            $listmesin2    = $this->model->getdatamesin2($intgedung,$intcell);
            $totafcell2    = 0;
            $totpfcell2    = 0;
            $totqfcell2    = 0;
            $totoeecell2   = 0;
            $jmlmesincell2 = 0;

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
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator  = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    //$dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $availabletime      = $availabletime1 - ($istirahat1);
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

                // array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                //                                 'vckodemesin' => $dtmesin->vckode,
                //                                 'vcmesin' => $dtmesin->vcnama,
                //                                 'statusmesin' => $statusmesin,
                //                                 'avgoee' => round(($oee * 100),2)));

                if (count($dataoperator) > 0) {
                    $totafcell  = $totafcell + $availabilityfactor;
                    $totpfcell  = $totpfcell + $performancefactor;
                    $totqfcell  = $totqfcell + $qualityfactor;
                    $totoeecell = $totoeecell + $oee;
                    $jmlmesincell++;
                }
            }

            foreach ($listmesin2 as $dtmesin2) {
                $intmesin2 = $dtmesin2->intid;
                if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1);
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                    $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                    // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
                }

                if ($intshift == 1) {
                    $dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                    $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat12     = (date('H:i:s') > '13:00:00' && $availabletime12 > 0) ? 60 : 0;
                    $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2,$intshift);
                    $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                    $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
                    $dataoutput22    = [];
                    $listdowntime22  = [];
                } else {
                    $dataoperator2  = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                    //$dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 2);
                    $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $istirahat12     = (date('H:i:s') > '02:00:00' && $availabletime12 > 0) ? 60 : 0;
                    $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                    $dataoutput12    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin2,1);
                    $listdowntime12  = $this->model->getdatadowntime2($date1,$date2,$intmesin2,1);
                    $dataoutput22    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin2,$intshift);
                    $listdowntime22  = $this->model->getdatadowntime2($date1,$date2,$intmesin2,$intshift);
                }

                $availabletime2      = $availabletime1 - ($istirahat1);
                $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                $idletime2           = $datadowntime2[0]->decprosestime;
                $totaldowntime2      = $datadowntime2[0]->decdurasi;
                $runtime2            = $availabletime2 - $totaldowntime2;
                $theoriticalct2      = $output2[0]->decct;
                // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput2  = round($output2[0]->inttarget);
                $actualoutput2       = $output2[0]->intactual;
                $defectiveproduct2   = $output2[0]->intreject;
                $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($output2[0]->intactual - $output2[0]->intreject)/$actualoutput2;
                $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                    $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                    $af2                   = 0;
                    $pf2                   = 0;
                    $qf2                   = 0;
                    $totruntime2           = 0;
                    $totavailabletime2     = 0;
                    $totactualoutput2      = 0;
                    $tottheoriticaloutput2 = 0;
                    $totgoodoutput2        = 0;
                    $loop2                 = 0;
                    for ($i=0; $i <= $datediff ; $i++) {
                        $dt = $i + 1;
                        $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                        $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                        // echo $date1. ' -  '.$date2 . '<br>';
                        
                        $datadowntime2       = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                        $output2             = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                        $jamkerja12          = $this->model->getjamkerja($date1, $date2, $intmesin2, 1);
                        $jamkerja22          = $this->model->getjamkerja($date1, $date2, $intmesin2, 2);
                        $jam12               = (count($jamkerja12) == 0) ? 0 : $jamkerja12[0]->intjamkerja + $jamkerja12[0]->intjamlembur - 60;
                        $jam22               = (count($jamkerja22) == 0) ? 0 : $jamkerja22[0]->intjamkerja + $jamkerja22[0]->intjamlembur - 60;
                        $availabletime2      = $jam12 + $jam22;
                        $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                        $idletime2           = $datadowntime2[0]->decprosestime;
                        $totaldowntime2      = $datadowntime2[0]->decdurasi;
                        $runtime2            = $availabletime2 - $totaldowntime2;
                        $theoriticalct2      = $output2[0]->decct;
                        $theoriticaloutput2  = ($theoriticalct2 == 0) ? 0 : ceil(60/$theoriticalct2*$runtime2);
                        $actualoutput2       = $output2[0]->intactual;
                        $defectiveproduct2   = $output2[0]->intreject;
                        $goodoutput2         = $actualoutput2 - $defectiveproduct2;
                        $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                        $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                        $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($goodoutput2)/$actualoutput2;
                        $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                        $loop2++;
                        $af2                   = $af2 + $availabilityfactor2;
                        $pf2                   = $pf2 + $performancefactor2;
                        $qf2                   = $qf2 + $qualityfactor2;
                        $totruntime2           = $totruntime2 + $runtime2;
                        $totavailabletime2     = $totavailabletime2 + $availabletime2;
                        $totactualoutput2      = $totactualoutput2 + $actualoutput2;
                        $tottheoriticaloutput2 = $tottheoriticaloutput2 + $theoriticaloutput2;
                        $totgoodoutput2        = $totgoodoutput2 + $goodoutput2;
                        // echo $availabilityfactor . '<br>' . $performancefactor . '('.$theoriticaloutput.','.$actualoutput.') <br>' . $qualityfactor . '('.$goodoutput.','.$actualoutput.') <br>' . $oee . '<br><br>';
                    }
                    
                    $avgaf2 = $af2/$loop2;
                    $avgpf2 = $pf2/$loop2;
                    $avgqf2 = $qf2/$loop2;

                    $availabletime2      = $totavailabletime2;
                    $machinebreackdown2  = 0;
                    $idletime2           = 0;
                    $totaldowntime2      = 0;
                    $runtime2            = $totruntime2;
                    $theoriticalct2      = 0;
                    $theoriticaloutput2  = $tottheoriticaloutput2;
                    $actualoutput2       = $totactualoutput2;
                    $defectiveproduct2   = 0;
                    $goodoutput2         = $totgoodoutput2;
                    $availabilityfactor2 = $avgaf2;
                    $performancefactor2  = $avgpf2;
                    $qualityfactor2      = $avgqf2;
                    $oee2                = $avgaf2 * $avgpf2 * $avgqf2;
                }

                // array_push($datapermesin, array('intmesin' => $dtmesin->intid,
                //                                 'vckodemesin' => $dtmesin->vckode,
                //                                 'vcmesin' => $dtmesin->vcnama,
                //                                 'statusmesin' => $statusmesin,
                //                                 'avgoee' => round(($oee * 100),2)));


                if (count($dataoperator2) > 0) {
                    $totafcell2  = $totafcell2 + $availabilityfactor2;
                    $totpfcell2  = $totpfcell2 + $performancefactor2;
                    $totqfcell2  = $totqfcell2 + $qualityfactor2;
                    $totoeecell2 = $totoeecell2 + $oee2;
                    $jmlmesincell2++;
                }
                
            }

            $avgafcell  = ($jmlmesincell == 0) ? 0 : $totafcell/$jmlmesincell;
            $avgpfcell  = ($jmlmesincell == 0) ? 0 : $totpfcell/$jmlmesincell;
            $avgqfcell  = ($jmlmesincell == 0) ? 0 : $totqfcell/$jmlmesincell;
            // $avgoeecell = ($jmlmesincell == 0) ? 0 : round(($totoeecell/$jmlmesincell),2);
            $avgoeecell = $avgafcell * $avgpfcell * $avgqfcell;
            ++$loopcell;

            $avgafcell2  = ($jmlmesincell2 == 0) ? 0 : $totafcell2/$jmlmesincell2;
            $avgpfcell2  = ($jmlmesincell2 == 0) ? 0 : $totpfcell2/$jmlmesincell2;
            $avgqfcell2  = ($jmlmesincell2 == 0) ? 0 : $totqfcell2/$jmlmesincell2;
            // $avgoeecell = ($jmlmesincell == 0) ? 0 : round(($totoeecell/$jmlmesincell),2);
            $avgoeecell2 = $avgafcell2 * $avgpfcell2 * $avgqfcell2;
            ++$loopcell2;


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
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                $dataoutput2    = [];
                $listdowntime2  = [];
            } else {
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                //$dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 2);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
                $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            }

            $availabletime      = $availabletime1 - ($istirahat1);
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

            //untuk history pertanggal
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

            // array_push($datapermesin, array('intmesin' => $dtmesin->intid,
            //                                 'vckodemesin' => $dtmesin->vckode,
            //                                 'vcmesin' => $dtmesin->vcnama,
            //                                 'statusmesin' => $statusmesin,
            //                                 'avgoee' => round(($oee * 100),2)));

            if (count($dataoperator) > 0) {
                $totaf  = $totaf + $availabilityfactor;
                $totpf  = $totpf + $performancefactor;
                $totqf  = $totqf + $qualityfactor;
                $totoee = $totoee + $oee;
                $jmlmesin++;
            }
        }

        $listmesin2    = $this->model->getdatamesin2($intgedung);
        foreach ($listmesin2 as $dtmesin2) {
            $intmesin2 = $dtmesin2->intid;
            if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                $date1      = date('Y-m-d ' . $worksift1);
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                $date2      = date('Y-m-d H:i:s');
                $datalogout = $this->model->getlogout($date1, $date2, $intmesin2, $intshift);
                // $intshift   = (count($datalogout) > 0) ? 2 : 1 ;
            }

            if ($intshift == 1) {
                $dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $istirahat12     = (date('H:i:s') > '13:00:00' && $availabletime12 > 0) ? 60 : 0;
                $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                $output2         = $this->model->getdataoutputall2($date1,$date2,$intmesin2,$intshift);
                $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
                $dataoutput22    = [];
                $listdowntime22  = [];
            } else {
                $dataoperator2   = $this->model->getoperator($intmesin2, $datenow, $intshift, 1);
                $availabletime12 = (count($dataoperator2) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $istirahat12     = (date('H:i:s') > '02:00:00' && $availabletime12 > 0) ? 60 : 0;
                $datadowntime2   = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                $output22         = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                $dataoutput12    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,1);
                $listdowntime12  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,1);
                $dataoutput22    = $this->model->getdataoutputkomponen22($date1,$date2,$intmesin2,$intshift);
                $listdowntime22  = $this->model->getdatadowntime22($date1,$date2,$intmesin2,$intshift);
            }

            $availabletime2      = $availabletime12 - $istirahat12;
            $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
            $idletime2           = $datadowntime2[0]->decprosestime;
            $totaldowntime2      = $datadowntime2[0]->decdurasi;
            $runtime2            = $availabletime2 - $totaldowntime2;
            $theoriticalct2      = $output2[0]->decct;
            // $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $theoriticaloutput2  = round($output2[0]->inttarget);
            $actualoutput2       = $output2[0]->intactual;
            $defectiveproduct2   = $output2[0]->intreject;
            $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
            $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
            $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($output2[0]->intactual - $output2[0]->intreject)/$actualoutput2;
            $oee2                = $availabilityfactor2 * $performancefactor2 * $qualityfactor2;

            //untuk history pertanggal
            if (date('Y-m-d') > date('Y-m-d',strtotime($datest))) {
                $datediff             = (strtotime($datefs) - strtotime($datest))/(3600*24);
                $af2                   = 0;
                $pf2                   = 0;
                $qf2                   = 0;
                $totruntime2           = 0;
                $totavailabletime2     = 0;
                $totactualoutput2      = 0;
                $tottheoriticaloutput2 = 0;
                $totgoodoutput2        = 0;
                $loop2                 = 0;
                for ($i=0; $i <= $datediff ; $i++) {
                    $dt = $i + 1;
                    $date1 = date('Y-m-d ' . $worksift1, strtotime($datest." +" . $i . " day"));
                    $date2 = date('Y-m-d 06:59:59', strtotime($datest." +" . $dt . " day"));
                    // echo $date1. ' -  '.$date2 . '<br>';
                    
                    $datadowntime2       = $this->model->getdatadowntimeall2($date1,$date2,$intmesin2);
                    $output2             = $this->model->getdataoutputall2($date1,$date2,$intmesin2);
                    $jamkerja12          = $this->model->getjamkerja($date1, $date2, $intmesin2, 1);
                    $jamkerja22          = $this->model->getjamkerja($date1, $date2, $intmesin2, 2);
                    $jam12               = (count($jamkerja12) == 0) ? 0 : $jamkerja12[0]->intjamkerja + $jamkerja12[0]->intjamlembur - 60;
                    $jam22               = (count($jamkerja22) == 0) ? 0 : $jamkerja22[0]->intjamkerja + $jamkerja22[0]->intjamlembur - 60;
                    $availabletime2      = $jam12 + $jam22;
                    $machinebreackdown2  = $datadowntime2[0]->decmachinedowntime;
                    $idletime2           = $datadowntime2[0]->decprosestime;
                    $totaldowntime2      = $datadowntime2[0]->decdurasi;
                    $runtime2            = $availabletime2 - $totaldowntime2;
                    $theoriticalct2      = $output2[0]->decct;
                    $theoriticaloutput2  = ($theoriticalct2 == 0) ? 0 : ceil(60/$theoriticalct2*$runtime2);
                    $actualoutput2       = $output2[0]->intactual;
                    $defectiveproduct2   = $output2[0]->intreject;
                    $goodoutput2         = $actualoutput2 - $defectiveproduct2;
                    $availabilityfactor2 = ($availabletime2 == 0) ? 0 : $runtime2/$availabletime2;
                    $performancefactor2  = ($theoriticaloutput2 == 0 || $availabletime2 == 0) ? 0 : $actualoutput2/$theoriticaloutput2;
                    $qualityfactor2      = ($actualoutput2 == 0 || $availabletime2 == 0) ? 0 : ($goodoutput2)/$actualoutput2;
                    $oee2                = $availabilityfactor2*$performancefactor2*$qualityfactor2;

                    $loop2++;
                    $af2                   = $af2 + $availabilityfactor2;
                    $pf2                   = $pf2 + $performancefactor2;
                    $qf2                   = $qf2 + $qualityfactor2;
                    $totruntime2           = $totruntime2 + $runtime;
                    $totavailabletime2     = $totavailabletime2 + $availabletime2;
                    $totactualoutput2      = $totactualoutput2 + $actualoutput2;
                    $tottheoriticaloutput2 = $tottheoriticaloutput2 + $theoriticaloutput2;
                    $totgoodoutput2        = $totgoodoutput2 + $goodoutput2;
                }
                
                $avgaf2 = $af2/$loop2;
                $avgpf2 = $pf2/$loop2;
                $avgqf2 = $qf2/$loop2;
                
                $availabletime2      = $totavailabletime2;
                $machinebreackdown2  = 0;
                $idletime2           = 0;
                $totaldowntime2      = 0;
                $runtime2            = $totruntime2;
                $theoriticalct2      = 0;
                $theoriticaloutput2  = $tottheoriticaloutput2;
                $actualoutput2       = $totactualoutput2;
                $defectiveproduct2   = 0;
                $goodoutput2         = $totgoodoutput2;
                $availabilityfactor2 = $avgaf2;
                $performancefactor2  = $avgpf2;
                $qualityfactor2      = $avgqf2;
                $oee2                = $avgaf2 * $avgpf2 * $avgqf2;
            }

            // array_push($datapermesin, array('intmesin' => $dtmesin->intid,
            //                                 'vckodemesin' => $dtmesin->vckode,
            //                                 'vcmesin' => $dtmesin->vcnama,
            //                                 'statusmesin' => $statusmesin,
            //                                 'avgoee' => round(($oee * 100),2)));

            if (count($dataoperator2) > 0) {
                $totaf2  = $totaf2 + $availabilityfactor2;
                $totpf2  = $totpf2 + $performancefactor2;
                $totqf2  = $totqf2 + $qualityfactor2;
                $totoee2 = $totoee2 + $oee2;
                $jmlmesin2++;
            }
            
        }

        //oee comelz
        $avgaf  = ($jmlmesin == 0) ? 0 : $totaf/$jmlmesin;
        $avgpf  = ($jmlmesin == 0) ? 0 : $totpf/$jmlmesin;
        $avgqf  = ($jmlmesin == 0) ? 0 : $totqf/$jmlmesin;
        // $avgoee = ($jmlmesin == 0) ? 0 : round(($totoee/$jmlmesin),2);
        $avgoee = $avgaf * $avgpf * $avgqf;

        //oee laser
        $avgaf2  = ($jmlmesin2 == 0) ? 0 : $totaf2/$jmlmesin2;
        $avgpf2  = ($jmlmesin2 == 0) ? 0 : $totpf2/$jmlmesin2;
        $avgqf2  = ($jmlmesin2 == 0) ? 0 : $totqf2/$jmlmesin2;
        // $avgoee = ($jmlmesin == 0) ? 0 : round(($totoee/$jmlmesin),2);
        $avgoee2 = $avgaf2 * $avgpf2 * $avgqf2;

        //oee comelz
        $data['avgaf']       = round(($avgaf*100),2);
        $data['avgpf']       = round(($avgpf*100),2);
        $data['avgqf']       = round(($avgqf*100),2);
        $data['avgoee']      = round(($avgoee*100),2);
        //oee laser

        $data['avgaf2']      = round(($avgaf2*100),2);
        $data['avgpf2']      = round(($avgpf2*100),2);
        $data['avgqf2']      = round(($avgqf2*100),2);
        $data['avgoee2']     = round(($avgoee2*100),2);
        
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

        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function bdg($intgedung=0,$datest='',$datefs=''){
        $data['intgedung'] = $intgedung;
        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function building_ajax($intgedung=0, $from='',$to='', $intshift=0){
        //from app setting
        $shift1start        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1startspecial = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $shift1finish       = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        $shift2start1       = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $shift2start2       = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
        $shift2finish1      = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
        $shift2finish2      = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;
        $break              = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplus          = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;
        $kalibrasi          = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
        $meeting            = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
        $sm                 = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;
        $notif_kalibrasi    = $this->modelapp->getappsetting('notif_kalibrasi')[0]->vcvalue;

        $dtcell               = $this->model->getcentralcutting($intgedung);
        $listgedung           = $this->model->getdatagedung('m_gedung');
        $datagedung           = $this->modelapp->getdatadetail('m_gedung',$intgedung);
        $datenow              = date('Y-m-d');
        $timenow              = date('H:i:s');
        $availabletime1       = 0;
        $availabletime2       = 0;
        $istirahat1           = 0;
        $istirahat2           = 0;
        $totav                = 0;
        $totruntime           = 0;
        $totactualoutput      = 0;
        $tottheoriticaloutput = 0;
        $totdefectiveproduct  = 0;
        $totaf                = 0;
        $totpf                = 0;
        $totqf                = 0;
        $totoee               = 0;
        $jmlmesin             = 0;

        if ($from == '' || $from == 0) {
            $from_ = $datenow;
        } else {
            $from_ = $from;
        }

        if ($to == '' || $to == 0) {
            $to_ = $datenow;
        } else {
            $to_ = $to;
        }

        // shift
        $intshift       = 0;
        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
            $intshift = 1;
        } else {
            $intshift = 2;
        }

        // if different building hours
        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
            if ($dtgedung->intspesial == 1) {
                $intgedungspecial = $dtgedung->intid;
            }
            if ($intgedung == $intgedungspecial) {
            $shift1start = $shift1startspecial;
            }
        }
        
        // range date
        if ($timenow >= $shift1start && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $shift1start);
            $date2      = date('Y-m-d H:i:s');
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $date1      = date('Y-m-d ' . $shift1start, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
        } else {
            $date1 = date('Y-m-d H:i:s');
            $date2 = date('Y-m-d H:i:s');
        }

        if ($timenow >= $shift1start && $timenow <= '19:59:59') {
            $datestart = date('Y-m-d ' . $shift1start);
        } elseif ($timenow >= '20:00:00' && $timenow <= '23:59:59') {
            $datestart = date('Y-m-d ' . $shift2start1);
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $datestart = date('Y-m-d ' . $shift2start1, strtotime('-1 day', strtotime(date('Y-m-d'))));
        }

        $datacell = [];
        $loopcell = 0;
        foreach ($dtcell as $cell) {
            $intcell                  = $cell->intid;
            $datapermesin             = array();
            $listmesin                = $this->model->getdatamesin($intgedung,$intcell);
            $totavcell                = 0;
            $totruntimecell           = 0;
            $totactualoutputcell      = 0;
            $tottheoriticaloutputcell = 0;
            $totdefectiveproductcell  = 0;
            $avgafcell                = 0;
            $avgpfcell                = 0;
            $avgqfcell                = 0;
            $avgoeecell               = 0;
            $jmlmesincell             = 0;
            foreach ($listmesin as $dtmesin) {
                $intmesin = $dtmesin->intid;
                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$shift1start)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $break : 0;
                    $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplus : 0;
                    $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                    $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                    $availabletime1 = 0;
                    $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($datestart))/60);
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? $break : 0;
                    $istirahatplus  = 0;
                    $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                    $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $vcoperator  = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
                $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

                $statuskalibrasi = '';
                if ($datadowntime[0]->deckalibrasi >= $notif_kalibrasi) {
                    $statuskalibrasi = 'Calibration';
                } else {
                    $statuskalibrasi = '';
                }

                //plan downtime
                //kalibrasi
                $pdkalibrasi   = 0;
                $dtkalibrasi   = 0;
                $plankalibrasi = 0;   
                $deckalibrasi  = $datadowntime[0]->deckalibrasi;
                if ($deckalibrasi > 0) {
                        if ($deckalibrasi <= $kalibrasi) {
                        $kalibrasitemp = $kalibrasi - $deckalibrasi;
                        $pdkalibrasi   = $kalibrasitemp;
                        $plankalibrasi = $deckalibrasi;
                    } elseif ($deckalibrasi > $kalibrasi ) {
                        $kalibrasitemp = $deckalibrasi - $kalibrasi;
                        $dtkalibrasi   = $kalibrasitemp;
                        $plankalibrasi = $kalibrasi;
                    }
                }
                
                //meeting
                $pdmeeting   = 0;
                $dtmeeting   = 0;
                $planmeeting = 0;   
                $decmeeting  = $datadowntime[0]->decmeeting;
                if ($decmeeting > 0) {
                        if ($decmeeting <= $meeting) {
                        $meetingtemp = $meeting - $decmeeting;
                        $pdmeeting   = $meetingtemp;
                        $planmeeting = $decmeeting;
                    } elseif ($decmeeting > $meeting ) {
                        $meetingtemp = $decmeeting - $meeting;
                        $dtmeeting   = $meetingtemp;
                        $planmeeting = $meeting;
                    }
                }
                
                //self maintenance
                $pdsm   = 0;
                $dtsm   = 0;
                $plansm = 0;   
                $decsm  = $datadowntime[0]->decsm;
                if ($decsm > 0) {
                        if ($decsm <= $sm) {
                        $smtemp = $sm - $decsm;
                        $pdsm   = $smtemp;
                        $plansm = $decsm;
                    } elseif ($decsm > $sm ) {
                        $smtemp = $decsm - $sm;
                        $dtsm   = $smtemp;
                        $plansm = $sm;
                    }
                }
                
                $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
                $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
                $totplan = $plankalibrasi + $planmeeting + $plansm;

                $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - ($totplan);
                $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
                $idletime             = $datadowntime[0]->decprosestime;
                $totaldowntime        = $datadowntime[0]->decdurasi + ($totdt);
                $runtime              = $availabletime - $totaldowntime;
                $theoriticalct        = $output[0]->decct;
                // $theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput    = $output[0]->inttarget > 0 ? round($output[0]->inttarget) : 0 ;
                $actualoutput         = $output[0]->intactual > 0 ? $output[0]->intactual : 0 ;
                $defectiveproduct     = $output[0]->intreject;
                $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
                $oee                  = $availabilityfactor*$performancefactor*$qualityfactor;

                array_push($datapermesin, array('intmesin'        => $dtmesin->intid,
                                                'vckodemesin'     => $dtmesin->vckode,
                                                'vcmesin'         => $dtmesin->vcnama,
                                                'intautocutting'  => $dtmesin->intautocutting,
                                                'statusmesin'     => $statusmesin,
                                                'statuskalibrasi' => $statuskalibrasi,
                                                'avgoee'          => round(($oee * 100),2)));

                if (count($dataoperator) > 0) {
                    $totavcell                = $totavcell + $availabletime;
                    $totruntimecell           = $totruntimecell + $runtime;
                    $totactualoutputcell      = $totactualoutputcell + $actualoutput;
                    $tottheoriticaloutputcell = $tottheoriticaloutputcell + $theoriticaloutput;
                    $totdefectiveproductcell  = $totdefectiveproductcell + $defectiveproduct;
                    $jmlmesincell++;
                }
                
            }

            //print_r($tottheoriticaloutputcell); exit();

            $avgafcell  = $totruntimecell > 0 ? $totruntimecell / $totavcell : 0;
            $avgpfcell  = $totactualoutputcell > 0 ? $totactualoutputcell/$tottheoriticaloutputcell : 0;
            $avgqfcell  =  $totactualoutputcell > 0 ? ($totactualoutputcell - $totdefectiveproductcell) / $totactualoutputcell : 0;
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
            if ($intshift == 1) {
                $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$shift1start)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $break : 0;
                $istirahat2     = 0;
                $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplus : 0;
                $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            } else {
                $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                $availabletime1 = 0;
                $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($datestart))/60);
                $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? $break : 0;
                $istirahatplus  = 0;
                $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            }

            $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
            $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

            //Status kalibrasi
            $statuskalibrasi = '';
            if ($datadowntime[0]->deckalibrasi >= $notif_kalibrasi) {
                $statuskalibrasi = 'Calibration';
            } else {
                $statuskalibrasi = '';
            }

            //plan downtime
            //kalibrasi
            $pdkalibrasi   = 0;
            $dtkalibrasi   = 0;
            $plankalibrasi = 0;   
            $deckalibrasi  = $datadowntime[0]->deckalibrasi;
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
            $decmeeting  = $datadowntime[0]->decmeeting;
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
            $decsm  = $datadowntime[0]->decsm;
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

            $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - ($totplan);
            $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
            $idletime             = $datadowntime[0]->decprosestime;
            $totaldowntime        = $datadowntime[0]->decdurasi + ($totdt);
            $runtime              = $availabletime - $totaldowntime;
            $theoriticalct        = $output[0]->decct;
            // $theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $theoriticaloutput    = round($output[0]->inttarget);
            $actualoutput         = $output[0]->intactual;
            $defectiveproduct     = $output[0]->intreject;
            $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
            $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($actualoutput - $defectiveproduct)/$actualoutput;
            $oee                  = $availabilityfactor * $performancefactor * $qualityfactor;

            array_push($datapermesin, array('intmesin'        => $dtmesin->intid,
                                            'vckodemesin'     => $dtmesin->vckode,
                                            'vcmesin'         => $dtmesin->vcnama,
                                            'intautocutting'  => $dtmesin->intautocutting,
                                            'statusmesin'     => $statusmesin,
                                            'statuskalibrasi' => $statuskalibrasi,
                                            'avgoee'          => round(($oee * 100),2)));

            if (count($dataoperator) > 0) {
                $totav                = $totav + $availabletime;
                $totruntime           = $totruntime + $runtime;
                $totactualoutput      = $totactualoutput + $actualoutput;
                $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                $totdefectiveproduct  = $totdefectiveproduct + $defectiveproduct;
                $jmlmesin++;
            }
        }
        
        $avgaf  = $totruntime > 0 ? $totruntime/$totav : 0;
        $avgpf  = $totactualoutput > 0 ? $totactualoutput/$tottheoriticaloutput : 0;
        $avgqf  = $totactualoutput > 0 ? (($totactualoutput - $totdefectiveproduct)/$totactualoutput) : 0;
        $avgoee = $avgaf * $avgpf * $avgqf;

        $data['avgaf']      = round($avgaf*100,2);
        $data['avgpf']      = round($avgpf*100,2);
        $data['avgqf']      = round($avgqf*100,2);
        $data['avgoee']     = round($avgoee*100,2);
        $data['oee']        = $datapermesin;
        $data['from']       = $from ? date('m/d/Y', strtotime($from_)) : '';
        $data['to']         = $to ? date('m/d/Y', strtotime($to_)) : '';
        $data['intgedung']  = $intgedung;
        $data['gedung']     = $datagedung;
        $data['jumlahcell'] = count($dtcell);
        $data['cell']       = $datacell;

        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function out($intgedung=0, $from='',$to='', $intshift=0){
        $data['intgedung'] = $intgedung;
        $data['from']      = $from;
        $data['to']        = $to;
        $data['intshift']  = $intshift;

        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function output_ajax($intgedung=0, $from='',$to='', $intshift=0 ){
        //from setting application
        $datagedung         = $this->modelapp->getdatadetail('m_gedung',$intgedung);
        $datamesin          = $this->model->getdtmesin($intgedung);
        $shift1start        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1startspecial = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $shift1finish       = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        $shift2start        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $breakplan          = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplusplan      = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;
        $kalibrasiplan      = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
        $meetingplan        = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
        $smplan             = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;
        $datenow            = date('Y-m-d');
        $timenow            = date('H:i:s');
        $availabletime1     = 0;
        $availabletime2     = 0;
        $istirahat1         = 0;
        $istirahat2         = 0;
        $istirahatplus      = 0;
        $durasiall          = 0;
        $totaldt            = 0;
        $jmlmesin           = 0;
        $totpd              = 0;
        $totdt              = 0;
        $totplan            = 0;
        $deckalibrasi       = 0;
        $decmeeting         = 0;
        $decsm              = 0;
        $totaltarget        = 0;
        $totalaktual        = 0;
        
        if ($from == '' || $from == 0) {
            $from_ = $datenow;
        } else {
            $from_ = $from;
        }

        if ($to == '' || $to == 0) {
            $to_ = $datenow;
        } else {
            $to_ = $to;
        }
        $date1         = date( "Y-m-d 07:00:00", strtotime( $from_) );
        $date2         = date( "Y-m-d 06:59:59", strtotime( $to_ . " + 1 day" ));

        $intgedungspecial = 0;
        foreach ($datagedung as $dtgedung) {
            if ($dtgedung->intspesial == 1) {
                $intgedungspecial = $dtgedung->intid;
            }
            if ($intgedung == $intgedungspecial) {
            $shift1start = $shift1startspecial;
            }
        }

        // for summary output
        if ($intshift == 0) { 
            $dataoutput = $this->model->getdatatotaloutput('pr_output',$intgedung,$date1,$date2);
            $mesinoutput   = array();
            foreach ($dataoutput as $do) {
                    $datamesinoutput = $this->model->getmesinoutput($intgedung, $do->intmodel, $do->intkomponen, $date1, $date2);
                    $totaltarget = $totaltarget + $do->inttarget;
                    $totalaktual = $totalaktual + $do->intaktual;
                    $datatemp = array(
                        'intpasang'       => $do->intpasang,
                        'inttarget'       => $do->inttarget,
                        'intmodel'        => $do->intmodel,
                        'intkomponen'     => $do->intkomponen,
                        'vcmodel'         => $do->vcmodel,
                        'vckomponen'      => $do->vckomponen,
                        'datamesinoutput' => $datamesinoutput
                    );
                array_push($mesinoutput, $datatemp);
            }
        } elseif ($intshift > 0) {
            $dataoutput = $this->model->getdatatotaloutputpershift('pr_output',$intgedung,$date1,$date2,$intshift);
            $mesinoutput   = array();
            foreach ($dataoutput as $do) {
                    $datamesinoutput = $this->model->getmesinoutputpershift($intgedung, $do->intmodel, $do->intkomponen, $date1, $date2, $intshift);
                    $totaltarget = $totaltarget + $do->inttarget;
                    $totalaktual = $totalaktual + $do->intaktual;
                    $datatemp = array(
                        'intpasang'       => $do->intpasang,
                        'inttarget'       => $do->inttarget,
                        'intmodel'        => $do->intmodel,
                        'intkomponen'     => $do->intkomponen,
                        'vcmodel'         => $do->vcmodel,
                        'vckomponen'      => $do->vckomponen,
                        'datamesinoutput' => $datamesinoutput
                    );
                array_push($mesinoutput, $datatemp);
            }
        }

       $totalkurang = $totaltarget - $totalaktual;
       $pf          = $totalaktual > 0 ? $totalaktual/$totaltarget : 0;

        $data['totalaktual'] = $totalaktual;
        $data['totaltarget'] = $totaltarget;
        $data['totalkurang'] = $totalkurang;
        $data['pf']          = round(($pf*100),2);
        $data['intrealtime'] = 1;
        $data['intgedung']   = $intgedung;
        $data['listshift']   = $this->modelapp->getdatalistall('m_shift');
        $data['from']        = $from ? date('m/d/Y', strtotime($from_)) : '';
        $data['to']          = $to ? date('m/d/Y', strtotime($to_)) : '';
        $data['intshift']    = $intshift ? $intshift : 0;
        $data['gedung']      = $datagedung;
        $data['mesin']       = $datamesin;
        $data['listoutput']  = $mesinoutput;
        
        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function downtime($intgedung=0, $from='',$to='', $intshift=0){
        $data['intgedung'] = $intgedung;
        $data['from']      = $from;
        $data['to']        = $to;
        $data['intshift']  = $intshift;

        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function downtime_ajax($intgedung=0, $from='',$to='', $intshift=0 ){
        //from setting application
        $datagedung         = $this->modelapp->getdatadetail('m_gedung',$intgedung);
        $datamesin          = $this->model->getdtmesin($intgedung);
        $shift1start        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1startspecial = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $shift1finish       = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        $shift2start        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $breakplan          = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplusplan      = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;
        $kalibrasiplan      = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
        $meetingplan        = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
        $smplan             = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;
        $datenow            = date('Y-m-d');
        $timenow            = date('H:i:s');
        $availabletime1     = 0;
        $availabletime2     = 0;
        $istirahat1         = 0;
        $istirahat2         = 0;
        $istirahatplus      = 0;
        $durasiall          = 0;
        $totaldt            = 0;
        $jmlmesin           = 0;
        $totpd              = 0;
        $totdt              = 0;
        $totplan            = 0;
        $deckalibrasi       = 0;
        $decmeeting         = 0;
        $decsm              = 0;
        $totav              = 0;
        $totruntime         = 0;
        $tottotdt           = 0;
        $totaf              = 0;
        $totpf              = 0;
        $totqf              = 0;
        $avgaf              = 0;
        $avgpf              = 0;
        $avgqf              = 0;
        
        if ($from == '' || $from == 0) {
            $from_ = $datenow;
        } else {
            $from_ = $from;
        }

        if ($to == '' || $to == 0) {
            $to_ = $datenow;
        } else {
            $to_ = $to;
        }
        
        $date1         = date( "Y-m-d 07:00:00", strtotime( $from_) );
        $date2         = date( "Y-m-d 06:59:59", strtotime( $to_ . " + 1 day" ));

        $intgedungspecial = 0;
        foreach ($datagedung as $dtgedung) {
            if ($dtgedung->intspesial == 1) {
                $intgedungspecial = $dtgedung->intid;
            }
            if ($intgedung == $intgedungspecial) {
            $shift1start = $shift1startspecial;
            }
        }

        // for summary Downtime
        if ($intshift == 0) {
            $datadowntime  = $this->model->getdatatotaldowntime('pr_downtime2',$intgedung,$date1,$date2);
            $mesindowntime = array();
            foreach ($datadowntime as $do) {
                    $datamesindowntime = $this->model->getmesindowntime($intgedung, $do->inttype_list, $date1, $date2);
                    $datatemp = array(
                        'jmldurasi'         => $do->jmldurasi,
                        'jmlcount'          => $do->jmlcount,
                        'inttype_list'      => $do->inttype_list,
                        'vcdowntime'        => $do->vcdowntime,
                        'datamesindowntime' => $datamesindowntime
                    );
                array_push($mesindowntime, $datatemp);
            }
        } elseif ($intshift > 0) {
            $datadowntime  = $this->model->getdatatotaldowntimepershift('pr_downtime2',$intgedung,$date1,$date2,$intshift);
            $mesindowntime = array();
            foreach ($datadowntime as $do) {
                    $datamesindowntime = $this->model->getmesindowntimepershift($intgedung, $do->inttype_list, $date1, $date2, $intshift);
                    $datatemp = array(
                        'jmldurasi'         => $do->jmldurasi,
                        'jmlcount'          => $do->jmlcount,
                        'inttype_list'      => $do->inttype_list,
                        'vcdowntime'        => $do->vcdowntime,
                        'datamesindowntime' => $datamesindowntime
                    );
                array_push($mesindowntime, $datatemp);
            }
        }

        //for OEE
        $listmesin    = $this->model->getdatamesin($intgedung);
        foreach ($listmesin as $dtmesin) {
            $intmesin = $dtmesin->intid;
            if ($intshift == 0) {
                $dataoperator1   = $this->model->getoperator($date1,$date2,$intmesin, 1, 1);
                $dataoperator2   = $this->model->getoperator($date1,$date2,$intmesin, 2, 1);
                if (strtotime(date($datenow)) == strtotime(date($from)) || $from == '' || $from == 0) {
                    if ((date('Y-m-d H:i:s')) > (date($datenow.' '.$shift1finish))) {
                        $availabletime1 = count($dataoperator1) == 0 ? 0 : ceil((strtotime(date($datenow.' '.$shift1finish)) - strtotime(date($datenow.' '.$shift1start)))/60);
                        $availabletime2 = count($dataoperator2) == 0 ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$shift2start)))/60);
                        $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplan : 0;
                        $istirahat2     = (date('H:i:s') > '02:00:00' && date('H:i:s') < '06:00:00') > 0 ? $breakplan : 0;
                        $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplusplan : 0;
                    } else {
                        $availabletime1 = count($dataoperator1) == 0 ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date('Y-m-d '.$shift1start)))/60);
                        $availabletime2 = 0;
                        $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplan : 0;
                        $istirahat2     = 0;
                        $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplusplan : 0;
                    }
                } else {
                    $datediff       = (strtotime($to) - strtotime($from))/(3600*24);
                    $jamkerja1      = $this->model->getwaktukerja($date1, $date2, $intmesin, 1);
                    $jamkerja2      = $this->model->getwaktukerja($date1, $date2, $intmesin, 2);
                    $availabletime1 = $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur;
                    $availabletime2 = $jamkerja2[0]->intjamkerja + $jamkerja2[0]->intjamlembur;
                    $istirahat1     = $availabletime1 > 0 ? ($breakplan * 2) * ($datediff + 1) : 0;
                    $istirahat2     = $availabletime2 > 0 ? ($breakplan * 2) * ($datediff + 1) : 0;
                }

                $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutput($date1,$date2,$intmesin);
            } else if ($intshift > 0) {
                if (strtotime(date($datenow)) == strtotime(date($from)) || $from == '' || $from == 0) {
                    $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                    if ($intshift == 1) {
                        $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$shift1start)))/60);
                        $availabletime2 = 0;
                        $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplan : 0;
                        $istirahat2     = 0;
                        $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplusplan : 0;
                    } else if ($intshift == 2) {
                        $availabletime1 = 0;
                        $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($shift2start1))/60);
                        $istirahat1     = 0;
                        $istirahat2     = (date('H:i:s') > '02:00:00' && $availabletime2 > 0) ? $breakplan : 0;
                        $istirahatplus  = 0;
                    }
                } else {
                    $datediff       = (strtotime($to) - strtotime($from))/(3600*24);
                    $jamkerja1      = $this->model->getwaktukerja($date1, $date2, $intmesin, $intshift);
                    $availabletime1 = $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur;
                    $availabletime2 = 0;
                    $istirahat1     = $availabletime1 > 0 ? $breakplan * ($datediff + 1) : 0;
                    $istirahat2     = 0;

                    // $jamkerja1      = $this->model->getjamkerja($date1, $date2, $intmesin, 1);
                    // $jamkerja2      = 0;
                    // $availabletime1 = $jamkerja1[0]->intjamkerja + $jamkerja1[0]->intjamlembur;
                    // $availabletime2 = 0;
                    // $istirahat1     = $availabletime1 > 0 ? $breakplan : 0;
                    // $istirahat2     = 00;
                }
                $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            } 

            //plan downtime
            //kalibrasi
            $pdkalibrasi   = 0;
            $dtkalibrasi   = 0;
            $plankalibrasi = 0;   
            $deckalibrasi  = $datadowntime[0]->deckalibrasi;
            if ($deckalibrasi > 0) {
                    if ($deckalibrasi <= $kalibrasiplan) {
                    $kalibrasitemp = $kalibrasiplan - $deckalibrasi;
                    $pdkalibrasi = $kalibrasitemp;
                    $plankalibrasi = $deckalibrasi;
                    } elseif ($deckalibrasi > $kalibrasiplan ) {
                        $kalibrasitemp = $deckalibrasi - $kalibrasiplan;
                        $dtkalibrasi = $kalibrasitemp;
                        $plankalibrasi = $kalibrasiplan;
                    }
            }
            
            //meeting
            $pdmeeting   = 0;
            $dtmeeting   = 0;
            $planmeeting = 0;   
            $decmeeting  = $datadowntime[0]->decmeeting;
            if ($decmeeting > 0) {
                    if ($decmeeting <= $meetingplan) {
                    $meetingtemp = $meetingplan - $decmeeting;
                    $pdmeeting = $meetingtemp;
                    $planmeeting = $decmeeting;
                } elseif ($decmeeting > $meetingplan ) {
                    $meetingtemp = $decmeeting - $meetingplan;
                    $dtmeeting = $meetingtemp;
                    $planmeeting = $meetingplan;
                }
            }
            
            //self maintenance
            $pdsm   = 0;
            $dtsm   = 0;
            $plansm = 0;   
            $decsm  = $datadowntime[0]->decsm;
            if ($decsm > 0) {
                    if ($decsm <= $smplan) {
                    $smtemp = $smplan - $decsm;
                    $pdsm = $smtemp;
                    $plansm = $decsm;
                } elseif ($decsm > $smplan ) {
                    $smtemp = $decsm - $smplan;
                    $dtsm = $smtemp;
                    $plansm = $smplan;
                }
            }
            
            $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
            $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
            $totplan = $plankalibrasi + $planmeeting + $plansm;

            $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - ($totplan);
            $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
            $idletime             = $datadowntime[0]->decprosestime;
            $totaldowntime        = $datadowntime[0]->decdurasi + ($totdt);
            $runtime              = $availabletime - $totaldowntime;
            $theoriticalct        = $output[0]->decct;
            $theoriticaloutput    = round($output[0]->inttarget);
            $actualoutput         = $output[0]->intactual;
            $defectiveproduct     = $output[0]->intreject;
            $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
            $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;

            $totav      = $totav + $availabletime;
            $totruntime = $totruntime + $runtime;
            $tottotdt   = $tottotdt + $totaldowntime;
        }
        
        $avgaf = $availabletime > 0 ? ($totruntime/$totav) * 100 : 0;
        
        //echo '<pre>';  print_r($mesindowntime); echo '</pre>';
       
        $data['intgedung']      = $intgedung;
        $data['listshift']      = $this->modelapp->getdatalistall('m_shift');
        $data['from']           = $from ? date('m/d/Y', strtotime($from_)) : '';
        $data['to']             = $to ? date('m/d/Y', strtotime($to_)) : '';
        $data['intshift']       = $intshift ? $intshift : 0;
        $data['gedung']         = $datagedung;
        $data['mesin']          = $datamesin;
        $data['listdowntime']   = $mesindowntime;
        $data['grafikdowntime'] = $datadowntime;
        $data['availabletime']  = round($totav,2);;
        $data['runtime']        = round($totruntime,2);
        $data['totaldt']        = round($tottotdt,2);
        $data['af']             = round($avgaf,2);
        
        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function building_($intgedung=0,$datest='',$datefs=''){
        $intgedung= decrypt_url($intgedung);
        $data['intgedung'] = $intgedung;
        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);

        //print_r($monitoring); exit();
    
    }

    function building__ajax($intgedung=0, $from='',$to='', $intshift=0){
         //from app setting
         $shift1start        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
         $shift1startspecial = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
         $shift1finish       = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
         $shift2start1       = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
         $shift2start2       = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
         $shift2finish1      = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
         $shift2finish2      = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;
         $break              = $this->modelapp->getappsetting('break')[0]->vcvalue;
         $breakplus          = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;
         $kalibrasi          = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
         $meeting            = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
         $sm                 = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;
         $notif_kalibrasi    = $this->modelapp->getappsetting('notif_kalibrasi')[0]->vcvalue;
 
         $dtcell               = $this->model->getcentralcutting($intgedung);
         $listgedung           = $this->model->getdatagedung('m_gedung');
         $datagedung           = $this->modelapp->getdatadetail('m_gedung',$intgedung);
         $datenow              = date('Y-m-d');
         $timenow              = date('H:i:s');
         $availabletime1       = 0;
         $availabletime2       = 0;
         $istirahat1           = 0;
         $istirahat2           = 0;
         $totav                = 0;
         $totruntime           = 0;
         $totactualoutput      = 0;
         $tottheoriticaloutput = 0;
         $totdefectiveproduct  = 0;
         $totaf                = 0;
         $totpf                = 0;
         $totqf                = 0;
         $totoee               = 0;
         $jmlmesin             = 0;

         if ($from == '' || $from == 0) {
            $from_ = $datenow;
        } else {
            $from_ = $from;
        }

        if ($to == '' || $to == 0) {
            $to_ = $datenow;
        } else {
            $to_ = $to;
        }

        // shift
        $intshift       = 0;
        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
            $intshift = 1;
        } else {
            $intshift = 2;
        }

        // if different building hours
        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
            if ($dtgedung->intspesial == 1) {
                $intgedungspecial = $dtgedung->intid;
            }
            if ($intgedung == $intgedungspecial) {
            $shift1start = $shift1startspecial;
            }
        }

        // range date
        if ($timenow >= $shift1start && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $shift1start);
            $date2      = date('Y-m-d H:i:s');
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $date1      = date('Y-m-d ' . $shift1start, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
        } else {
            $date1 = date('Y-m-d H:i:s');
            $date2 = date('Y-m-d H:i:s');
        }

        if ($timenow >= $shift1start && $timenow <= '19:59:59') {
            $datestart = date('Y-m-d ' . $shift1start);
        } elseif ($timenow >= '20:00:00' && $timenow <= '23:59:59') {
            $datestart = date('Y-m-d ' . $shift2start1);
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $datestart = date('Y-m-d ' . $shift2start1, strtotime('-1 day', strtotime(date('Y-m-d'))));
        }

        $datacell = [];
        $loopcell = 0;
        foreach ($dtcell as $cell) {
            $intcell                  = $cell->intid;
            $datapermesin             = array();
            $listmesin                = $this->model->getdatamesin($intgedung,$intcell);
            $totavcell                = 0;
            $totruntimecell           = 0;
            $totactualoutputcell      = 0;
            $tottheoriticaloutputcell = 0;
            $totdefectiveproductcell  = 0;
            $avgafcell                = 0;
            $avgpfcell                = 0;
            $avgqfcell                = 0;
            $avgoeecell               = 0;
            $jmlmesincell             = 0;
            foreach ($listmesin as $dtmesin) {
                $intmesin = $dtmesin->intid;
                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$shift1start)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $break : 0;
                    $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplus : 0;
                    $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                    $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                    $dataoutput2    = [];
                    $listdowntime2  = [];
                } else {
                    $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                    $availabletime1 = 0;
                    $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($datestart))/60);
                    $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? $break : 0;
                    $istirahatplus  = 0;
                    $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                    $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
                    $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
                    $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
                    $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
                    $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
                }

                $vcoperator  = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
                $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

                 //Status kalibrasi
                $statuskalibrasi = '';
                if ($datadowntime[0]->deckalibrasi >= $notif_kalibrasi) {
                    $statuskalibrasi = 'Calibration';
                } else {
                    $statuskalibrasi = '';
                }

                //plan downtime
                //kalibrasi
                $pdkalibrasi   = 0;
                $dtkalibrasi   = 0;
                $plankalibrasi = 0;   
                $deckalibrasi  = $datadowntime[0]->deckalibrasi;
                if ($deckalibrasi > 0) {
                        if ($deckalibrasi <= $kalibrasi) {
                        $kalibrasitemp = $kalibrasi - $deckalibrasi;
                        $pdkalibrasi   = $kalibrasitemp;
                        $plankalibrasi = $deckalibrasi;
                    } elseif ($deckalibrasi > $kalibrasi ) {
                        $kalibrasitemp = $deckalibrasi - $kalibrasi;
                        $dtkalibrasi   = $kalibrasitemp;
                        $plankalibrasi = $kalibrasi;
                    }
                }
                
                //meeting
                $pdmeeting   = 0;
                $dtmeeting   = 0;
                $planmeeting = 0;   
                $decmeeting  = $datadowntime[0]->decmeeting;
                if ($decmeeting > 0) {
                        if ($decmeeting <= $meeting) {
                        $meetingtemp = $meeting - $decmeeting;
                        $pdmeeting   = $meetingtemp;
                        $planmeeting = $decmeeting;
                    } elseif ($decmeeting > $meeting ) {
                        $meetingtemp = $decmeeting - $meeting;
                        $dtmeeting   = $meetingtemp;
                        $planmeeting = $meeting;
                    }
                }
                
                //self maintenance
                $pdsm   = 0;
                $dtsm   = 0;
                $plansm = 0;   
                $decsm  = $datadowntime[0]->decsm;
                if ($decsm > 0) {
                        if ($decsm <= $sm) {
                        $smtemp = $sm - $decsm;
                        $pdsm   = $smtemp;
                        $plansm = $decsm;
                    } elseif ($decsm > $sm ) {
                        $smtemp = $decsm - $sm;
                        $dtsm   = $smtemp;
                        $plansm = $sm;
                    }
                }
                
                $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
                $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
                $totplan = $plankalibrasi + $planmeeting + $plansm;

                $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - ($totplan);
                $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
                $idletime             = $datadowntime[0]->decprosestime;
                $totaldowntime        = $datadowntime[0]->decdurasi + ($totdt);
                $runtime              = $availabletime - $totaldowntime;
                $theoriticalct        = $output[0]->decct;
                // $theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                $theoriticaloutput    = round($output[0]->inttarget);
                $actualoutput         = $output[0]->intactual;
                $defectiveproduct     = $output[0]->intreject;
                $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
                $oee                  = $availabilityfactor*$performancefactor*$qualityfactor;

                array_push($datapermesin, array('intmesin'        => $dtmesin->intid,
                                                'vckodemesin'     => $dtmesin->vckode,
                                                'vcmesin'         => $dtmesin->vcnama,
                                                'intautocutting'  => $dtmesin->intautocutting,
                                                'statusmesin'     => $statusmesin,
                                                'statuskalibrasi' => $statuskalibrasi,
                                                'avgoee'          => round(($oee * 100),2)));
                
                if (count($dataoperator) > 0) {
                    $totavcell                = $totavcell + $availabletime;
                    $totruntimecell           = $totruntimecell + $runtime;
                    $totactualoutputcell      = $totactualoutputcell + $actualoutput;
                    $tottheoriticaloutputcell = $tottheoriticaloutputcell + $theoriticaloutput;
                    $totdefectiveproductcell  = $totdefectiveproductcell + $defectiveproduct;
                    $jmlmesincell++;
                } 
            }

            $avgafcell  = $totruntimecell > 0 ? $totruntimecell / $totavcell : 0;
            $avgpfcell  = $totactualoutputcell > 0 ? $totactualoutputcell/$tottheoriticaloutputcell : 0;
            $avgqfcell  = $totactualoutputcell > 0 ? ($totactualoutputcell - $totdefectiveproductcell) / $totactualoutputcell : 0;;
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
            if ($intshift == 1) {
                $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$shift1start)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $break : 0;
                $istirahat2     = 0;
                $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplus : 0;
                $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            } else {
                $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
                $availabletime1 = 0;
                $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($datestart))/60);
                $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? $break : 0;
                $istirahatplus  = 0;
                $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
                $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            }

            $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
            $statusmesin = (count($dataoperator) > 0 ) ? 'On' : 'Off';

            //Status kalibrasi
            $statuskalibrasi = '';
            if ($datadowntime[0]->deckalibrasi >= $notif_kalibrasi) {
                $statuskalibrasi = 'Calibration';
            } else {
                $statuskalibrasi = '';
            }

            ///plan downtime
            //kalibrasi
            $pdkalibrasi   = 0;
            $dtkalibrasi   = 0;
            $plankalibrasi = 0;   
            $deckalibrasi  = $datadowntime[0]->deckalibrasi;
            if ($deckalibrasi > 0) {
                if ($deckalibrasi <= $kalibrasi) {
                    $kalibrasitemp = $kalibrasi - $deckalibrasi;
                    $pdkalibrasi   = $kalibrasitemp;
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
            $decmeeting  = $datadowntime[0]->decmeeting;
            if ($decmeeting > 0) {
                if ($decmeeting <= $meeting) {
                    $meetingtemp = $meeting - $decmeeting;
                    $pdmeeting   = $meetingtemp;
                    $planmeeting = $decmeeting;
                } elseif ($decmeeting > $meeting ) {
                    $meetingtemp = $decmeeting - $meeting;
                    $dtmeeting   = $meetingtemp;
                    $planmeeting = $meeting;
                }
            }
            
            //self maintenance
            $pdsm   = 0;
            $dtsm   = 0;
            $plansm = 0;   
            $decsm  = $datadowntime[0]->decsm;
                if ($decsm > 0) {
                    if ($decsm <= $sm) {
                    $smtemp = $sm - $decsm;
                    $pdsm   = $smtemp;
                    $plansm = $decsm;
                } elseif ($decsm > $sm ) {
                    $smtemp = $decsm - $sm;
                    $dtsm   = $smtemp;
                    $plansm = $sm;
                }
            }
            
            $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
            $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
            $totplan = $plankalibrasi + $planmeeting + $plansm;
        
            $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - ($totplan);
            $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
            $idletime             = $datadowntime[0]->decprosestime;
            $totaldowntime        = $datadowntime[0]->decdurasi + ($totdt);
            $runtime              = $availabletime - $totaldowntime;
            $theoriticalct        = $output[0]->decct;
            // $theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $theoriticaloutput    = round($output[0]->inttarget);
            $actualoutput         = $output[0]->intactual;
            $defectiveproduct     = $output[0]->intreject;
            $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
            $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($actualoutput - $defectiveproduct)/$actualoutput;
            $oee                  = $availabilityfactor * $performancefactor * $qualityfactor;

            array_push($datapermesin, array('intmesin'        => $dtmesin->intid,
                                            'vckodemesin'     => $dtmesin->vckode,
                                            'vcmesin'         => $dtmesin->vcnama,
                                            'intautocutting'  => $dtmesin->intautocutting,
                                            'statusmesin'     => $statusmesin,
                                            'statuskalibrasi' => $statuskalibrasi,
                                            'avgoee'          => round(($oee * 100),2)));

            if (count($dataoperator) > 0) {
                $totav                = $totav + $availabletime;
                $totruntime           = $totruntime + $runtime;
                $totactualoutput      = $totactualoutput + $actualoutput;
                $tottheoriticaloutput = $tottheoriticaloutput + $theoriticaloutput;
                $totdefectiveproduct  = $totdefectiveproduct + $defectiveproduct;
                $jmlmesin++;
            }
        }

        $avgaf  = $totruntime > 0 ? $totruntime/$totav : 0;
        $avgpf  = $totactualoutput > 0 ? $totactualoutput/$tottheoriticaloutput : 0;
        $avgqf  = $totactualoutput > 0 ? ($totactualoutput - $totdefectiveproduct)/$totactualoutput : 0;
        $avgoee = $avgaf * $avgpf * $avgqf;

        $data['avgaf']      = round($avgaf*100,2);
        $data['avgpf']      = round($avgpf*100,2);
        $data['avgqf']      = round($avgqf*100,2);
        $data['avgoee']     = round($avgoee*100,2);
        $data['oee']        = $datapermesin;
        $data['from']       = $from ? date('m/d/Y', strtotime($from_)) : '';
        $data['to']         = $to ? date('m/d/Y', strtotime($to_)) : '';
        $data['intgedung']  = $intgedung;
        $data['gedung']     = $datagedung;
        $data['jumlahcell'] = count($dtcell);
        $data['cell']       = $datacell;

        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
    }

    function output_($intgedung=0, $from='',$to='', $intshift=0){
        $intgedung= decrypt_url($intgedung);
        $data['intgedung'] = $intgedung;
        $data['from']      = $from;
        $data['to']        = $to;
        $data['intshift']  = $intshift;
        
        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function output__ajax($intgedung=0, $from='',$to='', $intshift=0){
        $datagedung   = $this->model->getdtgedung($intgedung);
        $datamesin    = $this->model->getdtmesin($intgedung);
        $shift1start  = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1finish = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        $datenow       = date('Y-m-d');
        $timenow       = date('H:i:s');

        if ($from == '' || $from == 0) {
            $from_ = $datenow;
        } else {
            $from_ = $from;
        }

        if ($to == '' || $to == 0) {
            $to_ = $datenow;
        } else {
            $to_ = $to;
        }
        
        // $intshift      = 0;
        // if ($timenow >= $shift1start && $timenow <= $shift1finish) {
        //   $intshift = 1;
        // } else {
        //   $intshift = 2;
        // }

        if ($intshift == 0) { 
            $date1      = date( "Y-m-d 07:00:00", strtotime( $from_) );
            $date2      = date( "Y-m-d 06:59:59", strtotime( $to_ . " + 1 day" ) );
            $dataoutput = $this->model->getdatatotaloutput('pr_output',$intgedung,$date1,$date2);
            $mesinoutput   = array();
            foreach ($dataoutput as $do) {
                    $datamesinoutput = $this->model->getmesinoutput($intgedung, $do->intmodel, $do->intkomponen, $date1, $date2);
                    $datatemp = array(
                        'intpasang'       => $do->intpasang,
                        'inttarget'       => $do->inttarget,
                        'intmodel'        => $do->intmodel,
                        'intkomponen'     => $do->intkomponen,
                        'vcmodel'         => $do->vcmodel,
                        'vckomponen'      => $do->vckomponen,
                        'datamesinoutput' => $datamesinoutput
                    );
                array_push($mesinoutput, $datatemp);
            }
        } elseif ($intshift > 0) {
            $date1      = date( "Y-m-d 07:00:00", strtotime( $from_) );
            $date2      = date( "Y-m-d 06:59:59", strtotime( $to_ . " + 1 day" ) );
            $dataoutput = $this->model->getdatatotaloutputpershift('pr_output',$intgedung,$date1,$date2,$intshift);
            $mesinoutput   = array();
            foreach ($dataoutput as $do) {
                    $datamesinoutput = $this->model->getmesinoutputpershift($intgedung, $do->intmodel, $do->intkomponen, $date1, $date2, $intshift);
                    $datatemp = array(
                        'intpasang'       => $do->intpasang,
                        'inttarget'       => $do->inttarget,
                        'intmodel'        => $do->intmodel,
                        'intkomponen'     => $do->intkomponen,
                        'vcmodel'         => $do->vcmodel,
                        'vckomponen'      => $do->vckomponen,
                        'datamesinoutput' => $datamesinoutput
                    );
                array_push($mesinoutput, $datatemp);
            }
        }
        
       
        $data['intrealtime'] = 1;
        $data['intgedung']   = $intgedung;
        $data['listshift']   = $this->modelapp->getdatalistall('m_shift');
        $data['gedung']      = $datagedung;
        $data['mesin']       = $datamesin;
        $data['listoutput']  = $mesinoutput;
        $data['from']        = $from ? date('m/d/Y', strtotime($from_)) : '';
        $data['to']          = $to ? date('m/d/Y', strtotime($to_)) : '';
        $data['intshift']    = $intshift ? $intshift : 0;
        
        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function machine($intmesin, $datest='', $datefs=''){
        $data['intmesin'] = $intmesin;
        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function machine_ajax($intmesin, $datest='', $datefs=''){
        //$intshift         = getshift(strtotime(date('H:i:s')));

        $shift1start      = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1finish     = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        
        $shift2start1     = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $shift2start2     = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
        $shift2finish1    = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
        $shift2finish2    = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;
        $planned_downtime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $break            = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplus        = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;
        $kalibrasi        = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
        $meeting          = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
        $sm               = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;
        
        $timenow       = date('H:i:s');
        $intshift      = 0;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $intshift = 1;
        } else {
          $intshift = 2;
        }

        $datamesin        = $this->modelapp->getdatadetail('m_mesin',$intmesin);
        $intgedung        = $datamesin[0]->intgedung;
        $listgedung       = $this->model->getdatagedung('m_gedung');
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

        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
                if ($dtgedung->intspesial == 1) {
                    $intgedungspecial = $dtgedung->intid;
                }

                 if ($intgedung == $intgedungspecial) {
                $worksift1 = $worksift1special;
            }

        }

        $datenow  = date('Y-m-d');
        $timenow  = date('H:i:s');
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
        } else {
            $date1 = date('Y-m-d H:i:s');
            $date2 = date('Y-m-d H:i:s');
        }

                if ($timenow >= $worksift1 && $timenow <= '19:59:59') {
                    $datestart = date('Y-m-d ' . $worksift1);
                } elseif ($timenow >= '20:00:00' && $timenow <= '23:59:59') {
                    $datestart = date('Y-m-d ' . $worksift2);
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $datestart = date('Y-m-d ' . $worksift2, strtotime('-1 day', strtotime(date('Y-m-d'))));
                }


        if ($intshift == 1) {
            $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
            $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
            $availabletime2 = 0;
            $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $break : 0;
            $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplus : 0;
            //$datadowntime = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
            //$output       = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
            $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            $dataoutput2    = [];
            $listdowntime2  = [];
        } else {
            $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
            $availabletime1 = 0;
            $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($datestart))/60);
            $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? $break : 0;
            $istirahatplus  = 0;
            //$datadowntime = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
            //$output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
            $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
            $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
        }

        $vcoperator        = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
        $vcnik             = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcnik : '';
        
        //kalibrasi
        $pdkalibrasi   = 0;
        $dtkalibrasi   = 0;
        $plankalibrasi = 0;   
        $deckalibrasi  = $datadowntime[0]->deckalibrasi;
        if ($deckalibrasi > 0) {
            if ($deckalibrasi <= $kalibrasi) {
                $kalibrasitemp = $kalibrasi - $deckalibrasi;
                $pdkalibrasi   = $kalibrasitemp;
                $plankalibrasi = $deckalibrasi;
            } elseif ($deckalibrasi > $kalibrasi ) {
                $kalibrasitemp = $deckalibrasi - $kalibrasi;
                $dtkalibrasi   = $kalibrasitemp;
                $plankalibrasi = $kalibrasi;
            }
        }
        
        //meeting
        $pdmeeting   = 0;
        $dtmeeting   = 0;
        $planmeeting = 0;   
        $decmeeting  = $datadowntime[0]->decmeeting;
        if ($decmeeting > 0) {
            if ($decmeeting <= $meeting) {
                $meetingtemp = $meeting - $decmeeting;
                $pdmeeting   = $meetingtemp;
                $planmeeting = $decmeeting;
            } elseif ($decmeeting > $meeting ) {
                $meetingtemp = $decmeeting - $meeting;
                $dtmeeting   = $meetingtemp;
                $planmeeting = $meeting;
            }
        }
        
        //self maintenance
        $pdsm   = 0;
        $dtsm   = 0;
        $plansm = 0;   
        $decsm  = $datadowntime[0]->decsm;
        if ($decsm > 0) {
            if ($decsm <= $sm) {
                $smtemp = $sm - $decsm;
                $pdsm   = $smtemp;
                $plansm = $decsm;
            } elseif ($decsm > $sm ) {
                $smtemp = $decsm - $sm;
                $dtsm   = $smtemp;
                $plansm = $sm;
            }
        }

        //Trial
        $plantrial = 0;   
        $dectrial  = $datadowntime[0]->dectrial;
        if ($dectrial > 0) {
            $plantrial = $dectrial;
        }
        
        $totpd   = $pdkalibrasi + $pdmeeting + $pdsm;
        $totdt   = $dtkalibrasi + $dtmeeting + $dtsm; 
        $totplan = $plankalibrasi + $planmeeting + $plansm;

        $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - $totplan;
        $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
        $idletime             = $datadowntime[0]->decprosestime;
        $totaldowntime        = $datadowntime[0]->decdurasi + $totdt;
        $runtime              = $availabletime - $totaldowntime;
        $theoriticalct        = $output[0]->decct;
        // $theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
        $theoriticaloutput    = round($output[0]->inttarget);
        $actualoutput         = $output[0]->intactual;
        $defectiveproduct     = $output[0]->intreject;
        $goodoutput           = $actualoutput - $defectiveproduct;
        $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
        $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
        $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
        $oee                  = $availabilityfactor*$performancefactor*$qualityfactor;

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
        $durasi1          = 0;
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
                    $durasi1 = $durasi1 + ($output1->decdurasi * 60);
                    //Untuk menghitung jumlah satuan jam  
                    $jumlah_jam = floor($durasi1/3600);
                    //Untuk menghitung jumlah dalam satuan menit:
                    $sisa = $durasi1% 3600;
                    $jumlah_menit = floor($sisa/60);
                    //Untuk menghitung jumlah dalam satuan detik:
                    $sisa = $sisa % 60;
                    $jumlah_detik = floor($sisa/1);

                    $durasioutput1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;

                    //$durasioutput1 = $durasioutput1 + $output1->decdurasi;    
                }
            } else {
                $durasi1 = $durasi1 + ($output1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($durasi1/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $durasi1% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $durasioutput1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;

                //$durasioutput1 = $durasioutput1 + $output1->decdurasi;
            }

            $loop1++;
        }

        $tottarget2       = 0;
        $totoutput2       = 0;
        $totreject2       = 0;
        $durasi2          = 0;
        $durasioutput2    = 0;
        $totoutputactual2 = 0;
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
                    $durasi2 = $durasi2 + ($output2->decdurasi * 60);
                    //Untuk menghitung jumlah satuan jam  
                    $jumlah_jam = floor($durasi2/3600);
                    //Untuk menghitung jumlah dalam satuan menit:
                    $sisa = $durasi2% 3600;
                    $jumlah_menit = floor($sisa/60);
                    //Untuk menghitung jumlah dalam satuan detik:
                    $sisa = $sisa % 60;
                    $jumlah_detik = floor($sisa/1);

                    $durasioutput2 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
                    //$durasioutput2 = $durasioutput2 + $output2->decdurasi;    
                }
            } else {
                    $durasi2 = $durasi2 + ($output2->decdurasi * 60);
                    //Untuk menghitung jumlah satuan jam  
                    $jumlah_jam = floor($durasi2/3600);
                    //Untuk menghitung jumlah dalam satuan menit:
                    $sisa = $durasi2% 3600;
                    $jumlah_menit = floor($sisa/60);
                    //Untuk menghitung jumlah dalam satuan detik:
                    $sisa = $sisa % 60;
                    $jumlah_detik = floor($sisa/1);

                    $durasioutput2 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
                    //$durasioutput2 = $durasioutput2 + $output2->decdurasi;
            }

            $loop2++;
        }

        $mesindurasi1 = 0;
        $prosesdurasi1 = 0;
        $totmesindurasi1 = 0;
        $totprosesdurasi1 = 0;
        foreach ($listdowntime1 as $downtime1) {
            if ($downtime1->inttype_downtime == 1) {
                $mesindurasi1 = $mesindurasi1 + ($downtime1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($mesindurasi1/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $mesindurasi1% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totmesindurasi1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
            } else {
                $prosesdurasi1 = $prosesdurasi1 + ($downtime1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($prosesdurasi1/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $prosesdurasi1% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totprosesdurasi1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
            }
        }

        $mesindurasi2 = 0;
        $prosesdurasi2 = 0;
        $totmesindurasi2 = 0;
        $totprosesdurasi2 = 0;
        foreach ($listdowntime2 as $downtime2) {
            if ($downtime2->inttype_downtime == 1) {
                $mesindurasi2 = $mesindurasi2 + ($downtime2->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($mesindurasi2/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $mesindurasi2% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totmesindurasi2 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
            } else {
                $prosesdurasi2 = $prosesdurasi2 + ($downtime2->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($prosesdurasi2/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $prosesdurasi2% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totprosesdurasi2 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
                //$totprosesdurasi2 = $totprosesdurasi2 + $downtime2->decdurasi;
            }
        }

        //print_r($totaldowntime); exit();

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
        $data['totmesindurasi1']    = $totmesindurasi1;
        $data['totprosesdurasi1']   = $totprosesdurasi1;
        $data['totmesindurasi2']    = $totmesindurasi2;
        $data['totprosesdurasi2']   = $totprosesdurasi2;
        $data['durasioutput1']      = $durasioutput1;
        $data['durasioutput2']      = $durasioutput2;
        $data['totnotfollowsop1']   = $totnotfollowsop1;
        $data['totfollowsop1']      = $totfollowsop1;
        $data['totnotfollowsop2']   = $totnotfollowsop2;
        $data['totfollowsop2']      = $totfollowsop2;
        $data['totoutputactual1']   = $totoutputactual1;
        $data['totoutputactual2']   = $totoutputactual2;

        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function machine_($intgedung,$intmesin,$datest='',$datefs=''){
        $intmesin = decrypt_url($intmesin);
        $data['intgedung'] = $intgedung;
        $data['intmesin']  = $intmesin;
        $monitoring   = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
    }

    function machine__ajax($intgedung,$intmesin,$datest='',$datefs=''){
        //$intshift         = getshift(strtotime(date('H:i:s')));

        $shift1start      = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $shift1finish     = $this->modelapp->getappsetting('end-work-sift1')[0]->vcvalue;
        $shift2start1     = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $shift2start2     = $this->modelapp->getappsetting('start-work2-sift2')[0]->vcvalue;
        $shift2finish1    = $this->modelapp->getappsetting('end-work1-sift2')[0]->vcvalue;
        $shift2finish2    = $this->modelapp->getappsetting('end-work2-sift2')[0]->vcvalue;
        $planned_downtime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $break            = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplus        = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;
        $kalibrasi        = $this->modelapp->getappsetting('kalibrasi')[0]->vcvalue;
        $meeting          = $this->modelapp->getappsetting('meeting')[0]->vcvalue;
        $sm               = $this->modelapp->getappsetting('self_maintenance')[0]->vcvalue;

        
        $timenow       = date('H:i:s');
        $intshift      = 0;

        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
          $intshift = 1;
        } else {
          $intshift = 2;
        }

        $datamesin        = $this->modelapp->getdatadetail('m_mesin',$intmesin);
        $intgedung        = $datamesin[0]->intgedung;
        //$intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $listgedung       = $this->model->getdatagedung('m_gedung');
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

        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
                if ($dtgedung->intspesial == 1) {
                    $intgedungspecial = $dtgedung->intid;
                }

                 if ($intgedung == $intgedungspecial) {
                $worksift1 = $worksift1special;
            }

        }

        $datenow  = date('Y-m-d');
        $timenow  = date('H:i:s');
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
        } else {
            $date1 = date('Y-m-d H:i:s');
            $date2 = date('Y-m-d H:i:s');
        }

            if ($timenow >= $worksift1 && $timenow <= '19:59:59') {
                $datestart = date('Y-m-d ' . $worksift1);
            } elseif ($timenow >= '20:00:00' && $timenow <= '23:59:59') {
                $datestart = date('Y-m-d ' . $worksift2);
            } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                $datestart = date('Y-m-d ' . $worksift2, strtotime('-1 day', strtotime(date('Y-m-d'))));
            }

        if ($intshift == 1) {
            $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
            $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
            $availabletime2 = 0;
            $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $break : 0;
            $istirahatplus  = (date('l') == 'Friday' && date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? $breakplus : 0;
            //$datadowntime = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
            //$output         = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
            $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
            $dataoutput2    = [];
            $listdowntime2  = [];
        } else {
            $dataoperator   = $this->model->getoperator($date1,$date2,$intmesin, $intshift, 1);
            $availabletime1 = 0;
            $availabletime2 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime($datestart))/60);
            $istirahat1     = (date('H:i:s') > '02:00:00' && $availabletime1 > 0) ? $break : 0;
            $istirahatplus  = 0;
            //$datadowntime = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $datadowntime   = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
            //$output       = $this->model->getdataoutputall($date1,$date2,$intmesin,$intshift);
            $output         = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            $dataoutput1    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,1);
            $listdowntime1  = $this->model->getdatadowntime2($date1,$date2,$intmesin,1);
            $dataoutput2    = $this->model->getdataoutputkomponen2($date1,$date2,$intmesin,$intshift);
            $listdowntime2  = $this->model->getdatadowntime2($date1,$date2,$intmesin,$intshift);
        }

        $vcoperator = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcoperator : '';
        $vcnik      = (count($dataoperator) > 0 ) ? $dataoperator[0]->vcnik : '';

        //kalibrasi
        $pdkalibrasi   = 0;
        $dtkalibrasi   = 0;
        $plankalibrasi = 0;   
        $deckalibrasi  = $datadowntime[0]->deckalibrasi;
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
        $decmeeting  = $datadowntime[0]->decmeeting;
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
        $decsm  = $datadowntime[0]->decsm;
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

        
        $availabletime        = ($availabletime1 + $availabletime2) - ($istirahat1 + $istirahatplus) - ($totplan);
        $machinebreackdown    = $datadowntime[0]->decmachinedowntime;
        $idletime             = $datadowntime[0]->decprosestime;
        $totaldowntime        = $datadowntime[0]->decdurasi + ($totdt);
        $runtime              = $availabletime - $totaldowntime;
        $theoriticalct        = $output[0]->decct;
        // $theoriticaloutput = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
        $theoriticaloutput    = round($output[0]->inttarget);
        $actualoutput         = $output[0]->intactual;
        $defectiveproduct     = $output[0]->intreject;
        $goodoutput           = $actualoutput - $defectiveproduct;
        $availabilityfactor   = ($availabletime == 0) ? 0 : $runtime/$availabletime;
        $performancefactor    = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
        $qualityfactor        = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($goodoutput)/$actualoutput;
        $oee                  = $availabilityfactor*$performancefactor*$qualityfactor;

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
        $durasi1          = 0;
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
                    $durasi1 = $durasi1 + ($output1->decdurasi * 60);
                    //Untuk menghitung jumlah satuan jam  
                    $jumlah_jam = floor($durasi1/3600);
                    //Untuk menghitung jumlah dalam satuan menit:
                    $sisa = $durasi1% 3600;
                    $jumlah_menit = floor($sisa/60);
                    //Untuk menghitung jumlah dalam satuan detik:
                    $sisa = $sisa % 60;
                    $jumlah_detik = floor($sisa/1);

                    $durasioutput1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
                    //$durasioutput1 = $durasioutput1 + $output1->decdurasi;    
                }
            } else {
                $durasi1 = $durasi1 + ($output1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($durasi1/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $durasi1% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $durasioutput1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
                //$durasioutput1 = $durasioutput1 + $output1->decdurasi;
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
        $totoutputactual2 = 0;
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

        $mesindurasi1 = 0;
        $prosesdurasi1 = 0;
        $totmesindurasi1 = 0;
        $totprosesdurasi1 = 0;
        foreach ($listdowntime1 as $downtime1) {
            if ($downtime1->inttype_downtime == 1) {
                $mesindurasi1 = $mesindurasi1 + ($downtime1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($mesindurasi1/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $mesindurasi1% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totmesindurasi1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
            } else {
                $prosesdurasi1 = $prosesdurasi1 + ($downtime1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($prosesdurasi1/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $prosesdurasi1% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totprosesdurasi1 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
                
            }
        }

        $mesindurasi2 = 0;
        $prosesdurasi2 = 0;
        $totmesindurasi2 = 0;
        $totprosesdurasi2 = 0;
        foreach ($listdowntime2 as $downtime2) {
            if ($downtime2->inttype_downtime == 1) {
                $mesindurasi2 = $mesindurasi2 + ($downtime1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($mesindurasi2/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $mesindurasi2% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totmesindurasi2 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
            } else {
                $prosesdurasi2 = $prosesdurasi2 + ($downtime1->decdurasi * 60);
                //Untuk menghitung jumlah satuan jam  
                $jumlah_jam = floor($prosesdurasi2/3600);
                //Untuk menghitung jumlah dalam satuan menit:
                $sisa = $prosesdurasi2% 3600;
                $jumlah_menit = floor($sisa/60);
                //Untuk menghitung jumlah dalam satuan detik:
                $sisa = $sisa % 60;
                $jumlah_detik = floor($sisa/1);

                $totprosesdurasi2 = $jumlah_jam.":".$jumlah_menit.":".$jumlah_detik;
            }
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
        $data['totmesindurasi1']    = $totmesindurasi1;
        $data['totprosesdurasi1']   = $totprosesdurasi1;
        $data['totmesindurasi2']    = $totmesindurasi2;
        $data['totprosesdurasi2']   = $totprosesdurasi2;
        $data['durasioutput1']      = $durasioutput1;
        $data['durasioutput2']      = $durasioutput2;
        $data['totnotfollowsop1']   = $totnotfollowsop1;
        $data['totfollowsop1']      = $totfollowsop1;
        $data['totnotfollowsop2']   = $totnotfollowsop2;
        $data['totfollowsop2']      = $totfollowsop2;
        $data['totoutputactual1']   = $totoutputactual1;
        $data['totoutputactual2']   = $totoutputactual2;
        
        $monitoring          = $this->modelapp->getappsetting('monitoring')[0]->vcvalue;
        $this->load->view($monitoring . '_view/index',$data);
        //$this->load->view('monitoring_view/index',$data);
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

    function grafikdowntime($intgedung, $from, $to, $intshift){
        $datenow = date('Y-m-d');
        if ($from == '' || $from == 0) {
            $from_ = $datenow;
        } else {
            $from_ = $from;
        }

        if ($to == '' || $to == 0) {
            $to_ = $datenow;
        } else {
            $to_ = $to;
        }

        $date1         = date( "Y-m-d 07:00:00", strtotime( $from_) );
        $date2         = date( "Y-m-d 06:59:59", strtotime( $to_ . " + 1 day" ));

        $data  = $this->model->getgrafikdowntime('pr_downtime2',$intgedung,$date1,$date2, $intshift);

        $vcdowntime = [];
        $jmldurasi = [];
        foreach ($data as $dt) {
            array_push($vcdowntime, $dt->vcdowntime);
            array_push($jmldurasi, $dt->jmldurasi);
        }
        $result['vcdowntime']     = $vcdowntime;
        $result['jmldurasi']      = $jmldurasi;
        $result['grafikdowntime'] = $data;

        echo json_encode($result);
    }
}
