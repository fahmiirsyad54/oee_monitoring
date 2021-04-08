<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('AppModel');
        $this->load->model('OperatorModel');
        $this->model = $this->OperatorModel;
        $this->modelapp = $this->AppModel;

        // if (!$this->session->intmesinop && $this->session->appname != 'tpmoperator') {
        //     redirect(base_url('akses/loginop'));
        // }
    }
 
    function index(){
        // redirect(base_url('operator/downtime'));
        $this->load->view('operator_view/auth');
    }

    function dashboard(){
        $intmesin           = $this->session->intmesinop;
        $plandowntime       = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $login              = $this->model->getdataloginD();
        
        $datestart          = date("Y-m-d H:i:s", strtotime($login[0]->loginawal));
        $datefinish         = date("Y-m-d H:i:s");
        
        $downtime           = $this->model->getdatadowntimeD();
        $output             = $this->model->getdataoutputD();
        
        $decdowntime        = ($downtime[0]->decdurasi) ? $downtime[0]->decdurasi : 0 ;
        $availabletime      = ceil((strtotime($datefinish) - strtotime($datestart)) / 60);
        $plannedstop        = $plandowntime + $downtime[0]->decplanned;
        $plannedproduction  = $availabletime - $plannedstop;
        $runtime            = $plannedproduction - $decdowntime;
        $theoriticalct      = ($output[0]->jmlct == 0) ? 0 : $output[0]->jmlct / $output[0]->jmlid;
        $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
        $actualoutput       = $output[0]->jmlpasang;
        $defectiveproduct   = $output[0]->jmlreject;
        $availabilityfactor = (($runtime == 0 || $plannedproduction == 0) ? 0 : $runtime/$plannedproduction);
        $performancefactor  = (($theoriticaloutput == 0 || $actualoutput == 0) ? 0 : $actualoutput/$theoriticaloutput);
        $qualityfactor      = (($actualoutput == 0) ? 0 : ($actualoutput-$defectiveproduct)/$actualoutput);
        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            $data = array(
                    'datestart'          => $datestart, 
                    'availabletime'      => $availabletime, 
                    'plannedproduction'  => $plannedproduction, 
                    'totaldowntime'      => $decdowntime, 
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

        $data['title']         = 'Dashboard';
        $data['controller']    = 'operator';

        $this->load->view('operator_view/index',$data);
    }

    function insertoeeop(){
        $intmesin     = $this->session->intmesinop;
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $login        = $this->model->getdataloginD();

        $datestart    = date("Y-m-d H:i:s", strtotime($login[0]->dtlogin));
        $datefinish   = date("Y-m-d H:i:s");  

        $downtime     = $this->model->getdatadowntimeD($datestart,$datefinish,$intmesin);
        $output       = $this->model->getdataoutputD($datestart,$datefinish,$intmesin);
        
        $availabletime      = ceil((strtotime($datefinish) - strtotime($datestart)) / 60);
        $plannedstop        = ($plandowntime == 0) ? 0 :$plandowntime;
        $plannedproduction  = $availabletime - $plannedstop;
        $totaldowntime      = $downtime[0]->jmldowntime;
        $runtime            = $plannedproduction - $totaldowntime;
        $theoriticalct      = ($output[0]->jmlct == 0) ? 0 : $output[0]->jmlct / $output[0]->jmlid;
        $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
        $actualoutput       = $output[0]->jmlpasang;
        $defectiveproduct   = $output[0]->jmlreject;
        $availabilityfactor = (($runtime == 0 || $plannedproduction == 0) ? 0 : $runtime/$plannedproduction);
        $performancefactor  = (($theoriticaloutput == 0 || $actualoutput == 0) ? 0 : $actualoutput/$theoriticaloutput);
        $qualityfactor      = (($actualoutput == 0) ? 0 : ($actualoutput-$defectiveproduct)/$actualoutput);
        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

        $data = array(
            'intmesin'           => $intmesin,
            'dttanggal'          => $datefinish, 
            'decavailable_time'  => $availabletime, 
            'decplanned_product' => $plannedproduction, 
            'dectotal_downtime'  => $totaldowntime, 
            'decrun_time'        => $runtime, 
            'deccycle_time'      => $theoriticalct, 
            'decoutput'          => $theoriticaloutput, 
            'decactual_output'   => $actualoutput, 
            'decreject'          => $defectiveproduct, 
            'decaf'              => $availabilityfactor, 
            'decpf'              => $performancefactor, 
            'decqf'              => $qualityfactor, 
            'decoee'             => $oee,
            'intadd'             => $this->session->intidop,
            'dtadd'              => date('Y-m-d H:i:s'),
            'intupdate'          => $this->session->intidop,
            'dtupdate'           => date('Y-m-d H:i:s'),
            'intstatus'          => 1 
        );

        $this->modelapp->insertdata('pr_oee2',$data);
    }

    function downtime(){
        // echo '<a href="http://10.10.100.147/tpmsystem2/operator">Login Disini</a>';
        // exit();
        $datenow           = date('Y-m-d');
        $timenow           = date('H:i:s');
        // $intshift          = $this->session->intshift;
        // $intgedungsess     = $this->session->intgedung;
        // $intmesin          = $this->session->intmesinop;
        // $intkaryawan       = $this->session->intkaryawan;
        // $datashift         = $this->modelapp->getdatadetailcustom('m_shift',$intshift,'intid');
        // $worksift1         = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        // $worksift1_special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        // $worksift2         = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        // $intgedungspecial  = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        // $messagemax        = $this->modelapp->getappsetting('message-max')[0]->vcvalue;

        // if ($intgedungsess == $intgedungspecial) {
        //     $worksift1 = $worksift1_special;
        // }

        // if (strtotime($timenow) >= strtotime($worksift1) && strtotime($timenow) <= strtotime('23:59:59')) {
        //     $date1   = date('Y-m-d ' . $worksift1);
        //     $date2   = date('Y-m-d H:i:s');
        //     $stsift1 = $date1;
        //     $stsift2 = date('Y-m-d '.$worksift2);
        // } elseif (strtotime($timenow) >= strtotime('00:00:00') && strtotime($timenow) <= strtotime('06:59:59')) {
        //     $date1   = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
        //     $date2   = date('Y-m-d H:i:s');
        //     $stsift2 = date('Y-m-d '.$worksift2, strtotime('-1 day', strtotime(date('Y-m-d'))));
        // }

        // if ($intshift == 1) {
        //     $istirahatplus = (date('l') == 'Friday') ? (30*60) : 0;
        //     $getjamkerja   = $this->model->getjamkerja($date1, $date2, $intmesin, $intshift);
        //     $istirahat     = ($getjamkerja[0]->intjamlembur >= 180) ? (60 * 60) : 0;
        //     $jammasuk      = $stsift1;
        //     $jamkerja      = strtotime($stsift1) + ($getjamkerja[0]->intjamkerja * 60) + ($getjamkerja[0]->intjamlembur * 60) + $istirahat + $istirahatplus;
        //     $jamkeluar     = date("Y-m-d H:i:s", $jamkerja);
        // } else {
        //     $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, $intshift);
        //     $jammasuk    = $stsift2;
        //     $jamkerja   = strtotime($stsift2) + ($getjamkerja[0]->intjamkerja * 60) + ($getjamkerja[0]->intjamlembur * 60);
        //     $jamkeluar   = date("Y-m-d H:i:s", $jamkerja);
        // }

        // $countmessage = $this->model->get_countmessage($date1, $date2, $intmesin, $intkaryawan);
        // $sisapesan = $messagemax - $countmessage;

        $data['title']         = 'Downtime';
        $data['controller']    = 'operator';
        // $data['intshift']      = $datashift[0]->vcnama;
        // $data['listdowntime']  = $this->model->getdatalistdowntime();
        // $data['listmekanik']   = $this->modelapp->getdatalistall('m_karyawan',2,'intjabatan');
        // $data['listsparepart'] = $this->modelapp->getdatalistall('m_sparepart');
        // $data['listmodels']    = $this->modelapp->getdatalist('m_models');
        // $data['datadowntime']  = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
        // $data['dataoutput']    = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
        // $data['jammasuk']      = date('H:i:s',strtotime($jammasuk));
        // $data['jamkeluar']     = date('H:i:s',strtotime($jamkeluar));
        // $data['sisapesan']     = $sisapesan;

        $this->load->view('operator_view/index',$data);
    }

    function add_downtime(){
        $shift1start  = strtotime('07:00:00');
        $shift1finish = strtotime('20:00:00');
        
        $shift2start1  = strtotime('19:00:01');
        $shift2start2  = strtotime('00:00:00');
        $shift2finish1 = strtotime('23:59:59');
        $shift2finish2 = strtotime('07:00:00');
        $timenow      = time(date("H:i:s"));
        $intshift = 0;
        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
            $intshift = 1;
        } elseif (($timenow >= $shift2start1 && $timenow <= $shift2finish1) || ($timenow >= $shift2start2 && $timenow <= $shift2finish2)) {
            $intshift = 2;
        }

        // Dari Form
        $inttype_downtime = $this->input->post('inttype_downtime');
        $inttype_list     = $this->input->post('inttype_list');
        $dtmulai          = $this->input->post('dtmulai');
        $dtselesai        = $this->input->post('dtselesai');
        $decdurasi        = abs(strtotime($dtselesai) - strtotime($dtmulai)) / 60;
        $intmekanik       = $this->input->post('intmekanik');
        $intsparepart     = $this->input->post('intsparepart');
        $intjumlah        = $this->input->post('intjumlah');
        
        // Default
        $dttanggal   = date('Y-m-d H:i:s');
        $intgedung   = $this->session->intgedungop;
        $intmesin    = $this->session->intmesinop;
        $intoperator = $this->session->intkaryawan;
        $intcell     = $this->session->intcellop;

        if ($dtmulai > $dtselesai && $intshift == 2) {
            $tanggalmulai   = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 days')) . ' ' . $dtmulai;
            $tanggalselesai = date('Y-m-d ' .  $dtselesai);
            $decdurasi      = abs(strtotime($tanggalselesai) - strtotime($tanggalmulai)) / 60;
        }

        $data = array(
                'dttanggal'        => $dttanggal,
                'intgedung'        => $intgedung,
                'intcell'          => $intcell,
                'intmesin'         => $intmesin,
                'intshift'         => $intshift,
                'intoperator'      => $intoperator,
                'intleader'        => 0,
                'inttype_downtime' => $inttype_downtime,
                'inttype_list'     => $inttype_list,
                'intmekanik'       => $intmekanik,
                'dtmulai'          => $dtmulai,
                'dtselesai'        => $dtselesai,
                'decdurasi'        => $decdurasi,
                'intsparepart'     => $intsparepart,
                'intjumlah'        => $intjumlah,
                'intadd'           => $this->session->intidop,
                'dtadd'            => date('Y-m-d H:i:s'),
                'intupdate'        => $this->session->intidop,
                'dtupdate'         => date('Y-m-d H:i:s'),
                'intstatus'        => 1
            );

        $result = $this->modelapp->insertdata('pr_downtime2',$data);
        if ($result) {
            redirect(base_url('operator/downtime'));
        }
    }

    function add_downtime_ajax($inttipe){
        // Form
        $inttype_downtime = $this->input->post('inttype_downtime');
        $inttype_list     = $this->input->post('inttype_list');
        //$dtmulai          = $this->input->post('dtmulai');
        //$dtselesai        = $this->input->post('dtselesai');
        //$decdurasitemp    = abs(strtotime($dtselesai) - strtotime($dtmulai)) / 60;
        $intmekanik       = $this->input->post('intmekanik');
        $intsparepart     = $this->input->post('intsparepart');
        $intjumlah        = $this->input->post('intjumlah');

        $intgedung   = $this->input->post('intgedung');
        $intmesin    = $this->input->post('intmesin');
        $intoperator = $this->input->post('intoperator');
        $intcell     = $this->input->post('intcell');
        $intshift    = $this->input->post('intshift');
        $intidop     = $this->input->post('intidop');

        // Default
        $dttanggal        = date('Y-m-d H:i:s');
        $datenow          = date('Y-m-d');
        $timenow          = date('H:is');
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $listgedung       = $this->model->getdatagedung();
	
        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
            if ($dtgedung->intspesial == 1) {
                $intgedungspecial = $dtgedung->intid;
            }

            if ($intgedung == $intgedungspecial) {
                $worksift1 = $worksift1special;
            }
        }

        if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $worksift1);
            $date2      = date('Y-m-d H:i:s');
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:29:59') {
            $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
        }

        $datawaktu        = $this->model->getwaktu($intmesin, $date1, $date2, $intshift);
        $dtmulai          = $datawaktu[0]->ttemp;
        $dtselesai        = date('H:i:s');
        $decdurasitemp    = abs(strtotime($dtselesai) - strtotime($dtmulai)) / 60;

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

        $data = array(
                'dttanggal'        => $dttanggal,
                'intgedung'        => $intgedung,
                'intcell'          => $intcell,
                'intmesin'         => $intmesin,
                'intshift'         => $intshift,
                'intoperator'      => $intoperator,
                'intleader'        => 0,
                'inttype_downtime' => $inttype_downtime,
                'inttype_list'     => $inttype_list,
                'intmekanik'       => $intmekanik,
                'dtmulai'          => $dtmulai,
                'dtselesai'        => $dtselesai,
                'decdurasi'        => $decdurasi,
                'intsparepart'     => $intsparepart,
                'intjumlah'        => $intjumlah,
                'intadd'           => $intidop,
                'dtadd'            => date('Y-m-d H:i:s'),
                'intupdate'        => $intidop,
                'dtupdate'         => date('Y-m-d H:i:s'),
                'intstatus'        => 1
            );

        
        $datatemp = array(
            'dttanggal' => date('Y-m-d H:i:s'),
            'intmesin'  => $intmesin,
            'intshift'  => $intshift,
            'ttemp'     => date('H:i:s'),
            'inttype'   => $inttipe
                    );

        $hasil['status']       = false;
        $hasil['datadowntime'] = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
               $result         = $this->modelapp->insertdata('pr_downtime2',$data);
               $result         = $this->modelapp->insertdata('temp_time',$datatemp);
        if ($result) {
            $hasil['status']       = true;
            $hasil['datadowntime'] = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
            $hasil['dttime']       = date('H:i:s');
        }

        echo json_encode($hasil);
    }

    function add_output(){
        $shift1start  = strtotime('07:00:00');
        $shift1finish = strtotime('20:00:00');
        
        $shift2start1  = strtotime('19:00:01');
        $shift2start2  = strtotime('00:00:00');
        $shift2finish1 = strtotime('23:59:59');
        $shift2finish2 = strtotime('07:00:00');
        $timenow      = time(date("H:i:s"));
        $intshift = 0;
        if ($timenow >= $shift1start && $timenow <= $shift1finish) {
            $intshift = 1;
        } elseif (($timenow >= $shift2start1 && $timenow <= $shift2finish1) || ($timenow >= $shift2start2 && $timenow <= $shift2finish2)) {
            $intshift = 2;
        }

        // Dari Form
        $intmodel    = $this->input->post('intmodel');
        $intkomponen = $this->input->post('intkomponen');
        $decct       = $this->input->post('decct');
        $intpasang   = $this->input->post('intpasang');
        $intreject   = $this->input->post('intreject');
        $dtmulai     = $this->input->post('dtmulai');
        $dtselesai   = $this->input->post('dtselesai');
        $decdurasi   = abs(strtotime($dtselesai) - strtotime($dtmulai)) / 60;
        

        // Default
        $dttanggal   = date('Y-m-d H:i:s');
        $intgedung   = $this->session->intgedungop;
        $intcell     = $this->session->intcellop;
        $intmesin    = $this->session->intmesinop;
        $intoperator = $this->session->intkaryawan;

        $data = array(
                'dttanggal'   => $dttanggal,
                'intgedung'   => $intgedung,
                'intcell'     => $intcell,
                'intmesin'    => $intmesin,
                'intshift'    => $intshift,
                'intoperator' => $intoperator,
                'intleader'   => 0,
                'intmodel'    => $intmodel,
                'intkomponen' => $intkomponen,
                'decct'       => $decct,
                'dtmulai'     => $dtmulai,
                'dtselesai'   => $dtselesai,
                'decdurasi'   => $decdurasi,
                'intpasang'   => $intpasang,
                'intreject'   => $intreject,
                'intadd'      => $this->session->intidop,
                'dtadd'       => date('Y-m-d H:i:s'),
                'intupdate'   => $this->session->intidop,
                'dtupdate'    => date('Y-m-d H:i:s'),
                'intstatus'   => 1
            );

        $result = $this->modelapp->insertdata('pr_output',$data);
        if ($result) {
            redirect(base_url('operator/downtime'));
        }
    }

    function add_output_ajax(){
        // Default
        $dttanggal   = date('Y-m-d H:i:s');
        $intgedung   = $this->input->post('intgedung');
        $intmesin    = $this->input->post('intmesin');
        $intoperator = $this->input->post('intoperator');
        $intcell     = $this->input->post('intcell');
        $intshift    = $this->input->post('intshift');
        $intidop     = $this->input->post('intidop');

        $datenow          = date('Y-m-d');
        $timenow          = date('H:is');
        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $listgedung       = $this->model->getdatagedung();
        // $tempstart        = 0;
        // $tempselesai      = 0;

        
        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
            if ($dtgedung->intspesial == 1) {
                $intgedungspecial = $dtgedung->intid;
            }

            if ($intgedung == $intgedungspecial) {
                $worksift1 = $worksift1special;
            }
        }

        if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $worksift1);
            $date2      = date('Y-m-d H:i:s');
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:29:59') {
            $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
        }

        $datawaktucutting = $this->model->getwaktucutting($intmesin, $date1, $date2, $intshift, 2);
        $tempstart        = $datawaktucutting[0]->ttemp;
        $datawaktu        = $this->model->getwaktu($intmesin, $date1, $date2, $intshift);
        $tempselesai      = $datawaktu[0]->ttemp;

        // Dari Form
        $intmodel      = $this->input->post('intmodel');
        $intkomponen   = $this->input->post('intkomponen');
        $vcpo          = $this->input->post('vcpo');
        $decct         = $this->input->post('decct');
        $intlayer      = $this->input->post('intlayer');
        $intremark     = $this->input->post('intremark');
        $intpasang     = $this->input->post('intpasang');
        $intreject     = $this->input->post('intreject');
        $dtmulai       = $tempstart;
        $dtselesai     = $tempselesai;
        $sop           = $this->input->post('sop1');
        $decdurasitemp = abs(strtotime($dtselesai) - strtotime($dtmulai)) / 60;

        if ($decdurasitemp > 1000) {
            $decdurasi = 1440 - $decdurasitemp;
        } else {
            $decdurasi = $decdurasitemp;
        }

        $intmodel2      = $this->input->post('intmodel2');
        $intkomponen2   = $this->input->post('intkomponen2');
        $vcpo2          = $this->input->post('vcpo2');
        $decct2         = $this->input->post('decct2');
        $intlayer2      = $this->input->post('intlayer2');
        $intremark2     = $this->input->post('intremark2');
        $intpasang2     = $this->input->post('intpasang2');
        $intreject2     = $this->input->post('intreject2');
        $dtmulai2       = $tempstart;
        $dtselesai2     = $tempselesai;
        $sop2           = $this->input->post('sop2');
        $decdurasitemp2 = abs(strtotime($dtselesai2) - strtotime($dtmulai2)) / 60;

        if ($decdurasitemp2 > 1000) {
            $decdurasi2 = 1440 - $decdurasitemp2;
        } else {
            $decdurasi2 = $decdurasitemp2;
        }

        $intmodel3      = $this->input->post('intmodel3');
        $intkomponen3   = $this->input->post('intkomponen3');
        $vcpo3          = $this->input->post('vcpo3');
        $decct3         = $this->input->post('decct3');
        $intlayer3      = $this->input->post('intlayer3');
        $intremark3     = $this->input->post('intremark3');
        $intpasang3     = $this->input->post('intpasang3');
        $intreject3     = $this->input->post('intreject3');
        $dtmulai3       = $tempstart;
        $dtselesai3     = $tempselesai;
        $sop3           = $this->input->post('sop3');
        $decdurasitemp3 = abs(strtotime($dtselesai3) - strtotime($dtmulai3)) / 60;

        if ($decdurasitemp3 > 1000) {
            $decdurasi3 = 1440 - $decdurasitemp3;
        } else {
            $decdurasi3 = $decdurasitemp3;
        }

        $intmodel4      = $this->input->post('intmodel4');
        $intkomponen4   = $this->input->post('intkomponen4');
        $vcpo4          = $this->input->post('vcpo4');
        $decct4         = $this->input->post('decct4');
        $intlayer4      = $this->input->post('intlayer4');
        $intremark4     = $this->input->post('intremark4');
        $intpasang4     = $this->input->post('intpasang4');
        $intreject4     = $this->input->post('intreject4');
        $dtmulai4       = $tempstart;
        $dtselesai4     = $tempselesai;
        $sop4           = $this->input->post('sop4');
        $decdurasitemp4 = abs(strtotime($dtselesai4) - strtotime($dtmulai4)) / 60;
        
        if ($decdurasitemp4 > 1000) {
            $decdurasi4 = 1440 - $decdurasitemp4;
        } else {
            $decdurasi4 = $decdurasitemp4;
        }

        $intmodel5      = $this->input->post('intmodel5');
        $intkomponen5   = $this->input->post('intkomponen5');
        $vcpo5          = $this->input->post('vcpo5');
        $decct5         = $this->input->post('decct5');
        $intlayer5      = $this->input->post('intlayer5');
        $intremark5     = $this->input->post('intremark5');
        $intpasang5     = $this->input->post('intpasang5');
        $intreject5     = $this->input->post('intreject5');
        $dtmulai5       = $tempstart;
        $dtselesai5     = $tempselesai;
        $sop5           = $this->input->post('sop5');
        $decdurasitemp5 = abs(strtotime($dtselesai5) - strtotime($dtmulai5)) / 60;
        
        if ($decdurasitemp5 > 1000) {
            $decdurasi5 = 1440 - $decdurasitemp5;
        } else {
            $decdurasi5 = $decdurasitemp5;
        }

        $intmodel6      = $this->input->post('intmodel6');
        $intkomponen6   = $this->input->post('intkomponen6');
        $vcpo6          = $this->input->post('vcpo6');
        $decct6         = $this->input->post('decct6');
        $intlayer6      = $this->input->post('intlayer6');
        $intremark6     = $this->input->post('intremark6');
        $intpasang6     = $this->input->post('intpasang6');
        $intreject6     = $this->input->post('intreject6');
        $dtmulai6       = $tempstart;
        $dtselesai6     = $tempselesai;
        $sop6           = $this->input->post('sop6');
        $decdurasitemp6 = abs(strtotime($dtselesai6) - strtotime($dtmulai6)) / 60;
        
        if ($decdurasitemp6 > 1000) {
            $decdurasi6 = 1440 - $decdurasitemp6;
        } else {
            $decdurasi6 = $decdurasitemp6;
        }

        //baru
        $durasitemp     = $decdurasi * 60;
        $totpasangtemp  = $intpasang;
        $persen1        = 1;
        $timeori1       = ceil($intpasang * $decct);
        $tottimeoritemp = $timeori1;
        $totctall       = $tottimeoritemp / $totpasangtemp;
        $targetori      = ceil($durasitemp / $totctall);
        $target1        = ceil($targetori * $persen1);
        $target2        = 0;
        $target3        = 0;
        $target4        = 0;
        $target5        = 0;
        $target6        = 0;

        if ($intmodel2 > 0 && $intkomponen2 > 0 && $intpasang2 > 0) {
            //baru
            $durasitemp     = $decdurasi * 60;
            $totpasangtemp  = $intpasang + $intpasang2;
            $persen1        = $intpasang / $totpasangtemp;
            $persen2        = $intpasang2 / $totpasangtemp;
            $timeori1       = ceil($intpasang * $decct);
            $timeori2       = ceil($intpasang2 * $decct2);
            $tottimeoritemp = ($timeori1 + $timeori2);
            $totctall       = $tottimeoritemp / $totpasangtemp;
            $targetori      = ceil($durasitemp / $totctall);
            $target1        = ceil($targetori * $persen1);
            $target2        = ceil($targetori * $persen2);
        }
        
        if ($intmodel3 > 0 && $intkomponen3 > 0 && $intpasang3 > 0) {
            //baru
            $durasitemp     = $decdurasi * 60;
            $totpasangtemp  = $intpasang + $intpasang2 + $intpasang3;
            $persen1        = $intpasang / $totpasangtemp;
            $persen2        = $intpasang2 / $totpasangtemp;
            $persen3        = $intpasang3 / $totpasangtemp;
            $timeori1       = ceil($intpasang * $decct);
            $timeori2       = ceil($intpasang2 * $decct2);
            $timeori3       = ceil($intpasang3 * $decct3);
            $tottimeoritemp = ($timeori1 + $timeori2 + $timeori3);
            $totctall       = $tottimeoritemp / $totpasangtemp;
            $targetori      = ceil($durasitemp / $totctall);
            $target1        = ceil($targetori * $persen1);
            $target2        = ceil($targetori * $persen2);
            $target3        = ceil($targetori * $persen3);
        }

        if ($intmodel4 > 0 && $intkomponen4 > 0 && $intpasang4 > 0) {
            //baru
            $durasitemp     = $decdurasi * 60;
            $totpasangtemp  = $intpasang + $intpasang2 + $intpasang3 + $intpasang4;
            $persen1        = $intpasang / $totpasangtemp;
            $persen2        = $intpasang2 / $totpasangtemp;
            $persen3        = $intpasang3 / $totpasangtemp;
            $persen4        = $intpasang4 / $totpasangtemp;
            $timeori1       = ceil($intpasang * $decct);
            $timeori2       = ceil($intpasang2 * $decct2);
            $timeori3       = ceil($intpasang3 * $decct3);
            $timeori4       = ceil($intpasang4 * $decct4);
            $tottimeoritemp = ($timeori1 + $timeori2 + $timeori3 + $timeori4);
            $totctall       = $tottimeoritemp / $totpasangtemp;
            $targetori      = ceil($durasitemp / $totctall);
            $target1        = ceil($targetori * $persen1);
            $target2        = ceil($targetori * $persen2);
            $target3        = ceil($targetori * $persen3);
            $target4        = ceil($targetori * $persen4);

        }

        if ($intmodel5 > 0 && $intkomponen5 > 0 && $intpasang5 > 0) {
            //baru
            $durasitemp     = $decdurasi * 60;
            $totpasangtemp  = $intpasang + $intpasang2 + $intpasang3 + $intpasang4 + $intpasang5;
            $persen1        = $intpasang / $totpasangtemp;
            $persen2        = $intpasang2 / $totpasangtemp;
            $persen3        = $intpasang3 / $totpasangtemp;
            $persen4        = $intpasang4 / $totpasangtemp;
            $persen5        = $intpasang5 / $totpasangtemp;
            $timeori1       = ceil($intpasang * $decct);
            $timeori2       = ceil($intpasang2 * $decct2);
            $timeori3       = ceil($intpasang3 * $decct3);
            $timeori4       = ceil($intpasang4 * $decct4);
            $timeori5       = ceil($intpasang5 * $decct5);
            $tottimeoritemp = ($timeori1 + $timeori2 + $timeori3 + $timeori4 + $timeori5);
            $totctall       = $tottimeoritemp / $totpasangtemp;
            $targetori      = ceil($durasitemp / $totctall);
            $target1        = ceil($targetori * $persen1);
            $target2        = ceil($targetori * $persen2);
            $target3        = ceil($targetori * $persen3);
            $target4        = ceil($targetori * $persen4);
            $target5        = ceil($targetori * $persen5);
        }

        if ($intmodel6 > 0 && $intkomponen6 > 0 && $intpasang6 > 0) {
            //baru
            $durasitemp     = $decdurasi * 60;
            $totpasangtemp  = $intpasang + $intpasang2 + $intpasang3 + $intpasang4 + $intpasang5 + $intpasang6;
            $persen1        = $intpasang / $totpasangtemp;
            $persen2        = $intpasang2 / $totpasangtemp;
            $persen3        = $intpasang3 / $totpasangtemp;
            $persen4        = $intpasang4 / $totpasangtemp;
            $persen5        = $intpasang5 / $totpasangtemp;
            $persen6        = $intpasang6 / $totpasangtemp;
            $timeori1       = ceil($intpasang * $decct);
            $timeori2       = ceil($intpasang2 * $decct2);
            $timeori3       = ceil($intpasang3 * $decct3);
            $timeori4       = ceil($intpasang4 * $decct4);
            $timeori5       = ceil($intpasang5 * $decct5);
            $timeori6       = ceil($intpasang6 * $decct6);
            $tottimeoritemp = ($timeori1 + $timeori2 + $timeori3 + $timeori4 + $timeori5 + $timeori6);
            $totctall       = $tottimeoritemp / $totpasangtemp;
            $targetori      = ceil($durasitemp / $totctall);
            $target1        = ceil($targetori * $persen1);
            $target2        = ceil($targetori * $persen2);
            $target3        = ceil($targetori * $persen3);
            $target4        = ceil($targetori * $persen4);
            $target5        = ceil($targetori * $persen5);
            $target6        = ceil($targetori * $persen6);
        }

        $data = array(
                'dttanggal'    => $dttanggal,
                'intgedung'    => $intgedung,
                'intcell'      => $intcell,
                'intmesin'     => $intmesin,
                'intshift'     => $intshift,
                'intoperator'  => $intoperator,
                'intleader'    => 0,
                'intmodel'     => $intmodel,
                'intkomponen'  => $intkomponen,
                'vcpo'         => $vcpo,
                'decct'        => $decct,
                'intlayer'     => $intlayer,
                'intremark'    => $intremark,
                'dtmulai'      => $dtmulai,
                'dtselesai'    => $dtselesai,
                'decdurasi'    => $decdurasi,
                'intpasang'    => $intpasang,
                'intreject'    => $intreject,
                'inttarget'    => $target1,
                'vcketerangan' => $sop,
                'intadd'       => $intidop,
                'dtadd'        => date('Y-m-d H:i:s'),
                'intupdate'    => $intidop,
                'dtupdate'     => date('Y-m-d H:i:s'),
                'intstatus'    => 1
            );

        $data2 = array(
                'dttanggal'    => $dttanggal,
                'intgedung'    => $intgedung,
                'intcell'      => $intcell,
                'intmesin'     => $intmesin,
                'intshift'     => $intshift,
                'intoperator'  => $intoperator,
                'intleader'    => 0,
                'intmodel'     => $intmodel2,
                'intkomponen'  => $intkomponen2,
                'vcpo'         => $vcpo2,
                'decct'        => $decct2,
                'intlayer'     => $intlayer2,
                'intremark'    => $intremark2,
                'dtmulai'      => $dtmulai2,
                'dtselesai'    => $dtselesai2,
                'decdurasi'    => $decdurasi2,
                'intpasang'    => $intpasang2,
                'intreject'    => $intreject2,
                'inttarget'    => $target2,
                'vcketerangan' => $sop2,
                'intadd'       => $intidop,
                'dtadd'        => date('Y-m-d H:i:s'),
                'intupdate'    => $intidop,
                'dtupdate'     => date('Y-m-d H:i:s'),
                'intstatus'    => 1
            );

        $data3 = array(
                'dttanggal'    => $dttanggal,
                'intgedung'    => $intgedung,
                'intcell'      => $intcell,
                'intmesin'     => $intmesin,
                'intshift'     => $intshift,
                'intoperator'  => $intoperator,
                'intleader'    => 0,
                'intmodel'     => $intmodel3,
                'intkomponen'  => $intkomponen3,
                'vcpo'         => $vcpo3,
                'decct'        => $decct3,
                'intlayer'     => $intlayer3,
                'intremark'    => $intremark3,
                'dtmulai'      => $dtmulai3,
                'dtselesai'    => $dtselesai3,
                'decdurasi'    => $decdurasi3,
                'intpasang'    => $intpasang3,
                'intreject'    => $intreject3,
                'inttarget'    => $target3,
                'vcketerangan' => $sop3,
                'intadd'       => $intidop,
                'dtadd'        => date('Y-m-d H:i:s'),
                'intupdate'    => $intidop,
                'dtupdate'     => date('Y-m-d H:i:s'),
                'intstatus'    => 1
            );

        $data4 = array(
                'dttanggal'    => $dttanggal,
                'intgedung'    => $intgedung,
                'intcell'      => $intcell,
                'intmesin'     => $intmesin,
                'intshift'     => $intshift,
                'intoperator'  => $intoperator,
                'intleader'    => 0,
                'intmodel'     => $intmodel4,
                'intkomponen'  => $intkomponen4,
                'vcpo'         => $vcpo4,
                'decct'        => $decct4,
                'intlayer'     => $intlayer4,
                'intremark'    => $intremark4,
                'dtmulai'      => $dtmulai4,
                'dtselesai'    => $dtselesai4,
                'decdurasi'    => $decdurasi4,
                'intpasang'    => $intpasang4,
                'intreject'    => $intreject4,
                'inttarget'    => $target4,
                'vcketerangan' => $sop4,
                'intadd'       => $intidop,
                'dtadd'        => date('Y-m-d H:i:s'),
                'intupdate'    => $intidop,
                'dtupdate'     => date('Y-m-d H:i:s'),
                'intstatus'    => 1
            );

        $data5 = array(
                'dttanggal'    => $dttanggal,
                'intgedung'    => $intgedung,
                'intcell'      => $intcell,
                'intmesin'     => $intmesin,
                'intshift'     => $intshift,
                'intoperator'  => $intoperator,
                'intleader'    => 0,
                'intmodel'     => $intmodel5,
                'intkomponen'  => $intkomponen5,
                'vcpo'         => $vcpo5,
                'decct'        => $decct5,
                'intlayer'     => $intlayer5,
                'intremark'    => $intremark5,
                'dtmulai'      => $dtmulai5,
                'dtselesai'    => $dtselesai5,
                'decdurasi'    => $decdurasi5,
                'intpasang'    => $intpasang5,
                'intreject'    => $intreject5,
                'inttarget'    => $target5,
                'vcketerangan' => $sop5,
                'intadd'       => $intidop,
                'dtadd'        => date('Y-m-d H:i:s'),
                'intupdate'    => $intidop,
                'dtupdate'     => date('Y-m-d H:i:s'),
                'intstatus'    => 1
            );

            $data5 = array(
                'dttanggal'    => $dttanggal,
                'intgedung'    => $intgedung,
                'intcell'      => $intcell,
                'intmesin'     => $intmesin,
                'intshift'     => $intshift,
                'intoperator'  => $intoperator,
                'intleader'    => 0,
                'intmodel'     => $intmodel5,
                'intkomponen'  => $intkomponen5,
                'vcpo'         => $vcpo5,
                'decct'        => $decct5,
                'intlayer'     => $intlayer5,
                'intremark'    => $intremark5,
                'dtmulai'      => $dtmulai5,
                'dtselesai'    => $dtselesai5,
                'decdurasi'    => $decdurasi5,
                'intpasang'    => $intpasang5,
                'intreject'    => $intreject5,
                'inttarget'    => $target5,
                'vcketerangan' => $sop5,
                'intadd'       => $intidop,
                'dtadd'        => date('Y-m-d H:i:s'),
                'intupdate'    => $intidop,
                'dtupdate'     => date('Y-m-d H:i:s'),
                'intstatus'    => 1
            );

            $data6 = array(
                'dttanggal'    => $dttanggal,
                'intgedung'    => $intgedung,
                'intcell'      => $intcell,
                'intmesin'     => $intmesin,
                'intshift'     => $intshift,
                'intoperator'  => $intoperator,
                'intleader'    => 0,
                'intmodel'     => $intmodel6,
                'intkomponen'  => $intkomponen6,
                'vcpo'         => $vcpo6,
                'decct'        => $decct6,
                'intlayer'     => $intlayer6,
                'intremark'    => $intremark6,
                'dtmulai'      => $dtmulai6,
                'dtselesai'    => $dtselesai6,
                'decdurasi'    => $decdurasi6,
                'intpasang'    => $intpasang6,
                'intreject'    => $intreject6,
                'inttarget'    => $target6,
                'vcketerangan' => $sop6,
                'intadd'       => $intidop,
                'dtadd'        => date('Y-m-d H:i:s'),
                'intupdate'    => $intidop,
                'dtupdate'     => date('Y-m-d H:i:s'),
                'intstatus'    => 1
            );

        $datatemp = array(
            'dttanggal' => date('Y-m-d H:i:s'),
            'intmesin'  => $intmesin,
            'intshift'  => $intshift,
            'ttemp'     => date('H:i:s'),
            'inttype'   => 1
                    );
            
        $dtmulaidowntime       = $tempselesai;
        //$dtmulaidowntime       = $this->input->post('dtmulaidowntime');
        $dtselesaidowntime     = date('H:i:s');
        $decdurasidowntimetemp = abs(strtotime($dtselesaidowntime) - strtotime($dtmulaidowntime)) / 60;

        if ($decdurasidowntimetemp > 1000) {
            $decdurasidowntime = 1440 - $decdurasidowntimetemp;
        } else {
            $decdurasidowntime = $decdurasidowntimetemp;
        }
        
        $inttype_downtime  = $this->modelapp->getappsetting('inttype_downtime')[0]->vcvalue;
        $inttype_list      = $this->modelapp->getappsetting('inttype_list')[0]->vcvalue;
        
        $datadowntime = array(
                'dttanggal'        => $dttanggal,
                'intgedung'        => $intgedung,
                'intcell'          => $intcell,
                'intmesin'         => $intmesin,
                'intshift'         => $intshift,
                'intoperator'      => $intoperator,
                'intleader'        => 0,
                'inttype_downtime' => $inttype_downtime,
                'inttype_list'     => $inttype_list,
                'dtmulai'          => $dtmulaidowntime,
                'dtselesai'        => $dtselesaidowntime,
                'decdurasi'        => $decdurasidowntime,
                'intadd'           => $intidop,
                'dtadd'            => date('Y-m-d H:i:s'),
                'intupdate'        => $intidop,
                'dtupdate'         => date('Y-m-d H:i:s'),
                'intstatus'        => 1
            );

        $hasil['status']       = false;
        $hasil['dataoutput']   = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
        $hasil['datadowntime'] = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
        $result                = $this->modelapp->insertdata('pr_output',$data);
        $result                = $this->modelapp->insertdata('pr_downtime2',$datadowntime);
        $result                = $this->modelapp->insertdata('temp_time',$datatemp);
        if ($intmodel2 > 0 && $intkomponen2 > 0 && $intpasang2 > 0) {
            $this->modelapp->insertdata('pr_output',$data2);
        }
        if ($intmodel3 > 0 && $intkomponen3 > 0 && $intpasang3 > 0) {
            $this->modelapp->insertdata('pr_output',$data3);
        }
        if ($intmodel4 > 0 && $intkomponen4 > 0 && $intpasang4 > 0) {
            $this->modelapp->insertdata('pr_output',$data4);
        }
        if ($intmodel5 > 0 && $intkomponen5 > 0 && $intpasang5 > 0) {
            $this->modelapp->insertdata('pr_output',$data5);
        }
        if ($intmodel6 > 0 && $intkomponen6 > 0 && $intpasang6 > 0) {
            $this->modelapp->insertdata('pr_output',$data6);
        }
        
        if ($result) {
            $hasil['status']       = true;
            $hasil['dataoutput']   = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
            $hasil['datadowntime'] = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
            $datawaktudt           = $this->model->getwaktu($intmesin, $date1, $date2, $intshift);
            $hasil['dttime']       = $datawaktudt[0]->ttemp;
        }

        echo json_encode($hasil);
    }

    function add_pesan(){
        $vcpesan     = $this->input->post('vcpesan');
        $intkaryawan = $this->input->post('intkaryawan');
        $intmesinop  = $this->input->post('intmesinop');
        $intidop     = $this->input->post('intidop');

        $data = array(
                'vcpesan'     => $vcpesan,
                'intkaryawan' => $intkaryawan,
                'intmesin'    => $intmesinop,
                'intadd'      => $intidop,
                'dttanggal'   => date('Y-m-d H:i:s'),
                'dtadd'       => date('Y-m-d H:i:s'),
                'intupdate'   => $intidop,
                'dtupdate'    => date('Y-m-d H:i:s'),
                'intstatus'   => 0
            );

        $result = $this->modelapp->insertdata('pr_pesan',$data);
        if ($result) {
            redirect(base_url('operator/downtime'));
        }
    }
    
    function getkomponen_ajax($intid, $sdd, $vcpo){
        $dataloadplan   = $this->model->dataloadplan($intid, $sdd, $vcpo);

        $data['intqty']       = $dataloadplan[0]->intqty;
        $data['listkomponen'] = $this->model->getkomponen($intid, $vcpo);

        echo json_encode($data);
    }

    function getlistdowntime_ajax(){
        $data = $this->model->getdatalistdowntime();

        echo json_encode($data);
    }

    function addlembur_ajax($intjamlembur, $intshift, $intkaryawan, $intmesin, $intgedungsess){
        $datenow           = date('Y-m-d');
        $timenow           = date('H:i:s');
        // $intshift          = $this->session->intshift;
        // $intmesin          = $this->session->intmesinop;
        // $intgedungsess     = $this->session->intgedung;
        $datashift         = $this->modelapp->getdatadetailcustom('m_shift',$intshift,'intid');
        $worksift1         = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift1_special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $worksift2         = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        //$intgedungspecial  = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $break            = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplus        = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;

        $listgedung        = $this->model->getdatagedung('m_gedung');

        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
                if ($dtgedung->intspesial == 1) {
                    $intgedungspecial = $dtgedung->intid;
                }

                 if ($intgedungsess == $intgedungspecial) {
                $worksift1 = $worksift1_special;
            }
        }
        
        if (strtotime($timenow) >= strtotime($worksift1) && strtotime($timenow) <= strtotime('23:59:59')) {
            $date1  = date('Y-m-d ' . $worksift1);
            $date2  = date('Y-m-d H:i:s');
            $stsift1 = $date1;
            $stsift2 = date('Y-m-d '.$worksift2);
        } elseif (strtotime($timenow) >= strtotime('00:00:00') && strtotime($timenow) <= strtotime('06:59:59')) {
            $date1 = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2 = date('Y-m-d H:i:s');
            $stsift2 = date('Y-m-d '.$worksift2, strtotime('-1 day', strtotime(date('Y-m-d'))));
        }

        $this->model->updatelembur($date1, $date2, $intkaryawan, $intshift, $intjamlembur);

        if ($intshift == 1) {
            $getjamkerja   = $this->model->getjamkerja($date1, $date2, $intmesin, $intshift);
            $istirahat     = ($intjamlembur >= 180) ? $break : 0;
            $istirahatplus = (date('l') == 'Friday') ? $breakplus : 0;
            $jammasuk      = $stsift1;
            $jamkerja      = strtotime($stsift1) + ($getjamkerja[0]->intjamkerja * 60) + ($getjamkerja[0]->intjamlembur * 60) + ($istirahat * 60) + ($istirahatplus * 60);
            $jamkeluar     = date("Y-m-d H:i:s", $jamkerja);
        } else {
            $getjamkerja = $this->model->getjamkerja($date1, $date2, $intmesin, $intshift);
            $jammasuk    = $stsift2;
            $jamkerja   = strtotime($stsift2) + ($getjamkerja[0]->intjamkerja * 60);
            $jamkeluar   = date("Y-m-d H:i:s", $jamkerja);
        }

        $data = array(
                    'jammasuk'  => date('H:i:s',strtotime($jammasuk)),
                    'jamkeluar' => date('H:i:s',strtotime($jamkeluar))
                );

        echo json_encode($data);
    }

    function add_form_modelkomponen(){
        $data['listmodels'] = $this->modelapp->getdatalist('m_models');
        $this->load->view('operator_view/modelkomponen',$data);
    }

    function updatereject_ajax($intid, $intreject){
        $data = array('intreject' => $intreject);
        $this->modelapp->updatedata('pr_output',$data,$intid);
    }

    function getkomponenct_ajax($intid){
        $data = $this->model->getkomponenct($intid);
        echo json_encode($data);
    }

    function getdatadefault_ajax($intshift, $intgedungsess, $intmesin, $intkaryawan){
        $datenow           = date('Y-m-d');
        $timenow           = date('H:i:s');
        // $intshift          = $this->session->intshift;
        // $intgedungsess     = $this->session->intgedung;
        // $intmesin          = $this->session->intmesinop;
        // $intkaryawan       = $this->session->intkaryawan;
        $datashift          = $this->modelapp->getdatadetailcustom('m_shift',$intshift,'intid');
        $worksift1          = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift1_special  = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $worksift2          = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        //$intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $messagemax         = $this->modelapp->getappsetting('message-max')[0]->vcvalue;
        $listgedung         = $this->model->getdatagedung('m_gedung');
        $break              = $this->modelapp->getappsetting('break')[0]->vcvalue;
        $breakplus          = $this->modelapp->getappsetting('breakplus')[0]->vcvalue;

        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
                if ($dtgedung->intspesial == 1) {
                    $intgedungspecial = $dtgedung->intid;
                }

                 if ($intgedungsess == $intgedungspecial) {
                $worksift1 = $worksift1_special;
            }

        }

        if (strtotime($timenow) >= strtotime($worksift1) && strtotime($timenow) <= strtotime('23:59:59')) {
            $date1   = date('Y-m-d ' . $worksift1);
            $date2   = date('Y-m-d H:i:s');
            $stsift1 = $date1;
            $stsift2 = date('Y-m-d '.$worksift2);
        } elseif (strtotime($timenow) >= strtotime('00:00:00') && strtotime($timenow) <= strtotime('06:59:59')) {
            $date1   = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2   = date('Y-m-d H:i:s');
            $stsift2 = date('Y-m-d '.$worksift2, strtotime('-1 day', strtotime(date('Y-m-d'))));
        }

        if ($intshift == 1) {
            $getjamkerja   = $this->model->getjamkerja($date1, $date2, $intmesin, $intshift);
            $intjamkerja   = (count($getjamkerja) > 0) ? $getjamkerja[0]->intjamkerja : 0;
            $intjamlembur  = (count($getjamkerja) > 0) ? $getjamkerja[0]->intjamlembur : 0;
            $istirahat     = ($intjamlembur >= 180) ? $break : 0;
            $istirahatplus = (date('l') == 'Friday') ? $breakplus : 0;
            $jammasuk      = $stsift1;
            $jamkerja      = strtotime($stsift1) + ($intjamkerja * 60) + ($intjamlembur * 60) + ($istirahat * 60) + ($istirahatplus * 60);
            $jamkeluar     = date("Y-m-d H:i:s", $jamkerja);
        } else {
            $getjamkerja  = $this->model->getjamkerja($date1, $date2, $intmesin, $intshift);
            $intjamkerja  = (count($getjamkerja) > 0) ? $getjamkerja[0]->intjamkerja : 0;
            $intjamlembur = (count($getjamkerja) > 0) ? $getjamkerja[0]->intjamlembur : 0;
            $jammasuk     = $stsift2;
            $jamkerja     = strtotime($stsift2) + ($intjamkerja * 60) + ($intjamlembur * 60);
            $jamkeluar    = date("Y-m-d H:i:s", $jamkerja);
        }

        $countmessage = $this->model->get_countmessage($date1, $date2, $intmesin, $intkaryawan);
        $sisapesan = $messagemax - $countmessage;

        $data['title']         = 'Downtime';
        $data['controller']    = 'operator';
        $data['intshift']      = $datashift[0]->vcnama;
        $data['listdowntime']  = $this->model->getdatalistdowntime();
        $data['listmekanik']   = $this->modelapp->getdatalistall('m_karyawan',2,'intjabatan');
        $data['listsparepart'] = $this->modelapp->getdatalistall('m_sparepart');
        $data['listmodels']    = $this->modelapp->getdatalist('m_models');
        $data['listremark']    = $this->modelapp->getdatalist('m_output_remark');
        $data['datadowntime']  = $this->model->getdatadowntime($date1,$date2,$intmesin,$intshift);
        $data['dataoutput']    = $this->model->getdataoutput($date1,$date2,$intmesin,$intshift);
        $data['jammasuk']      = date('H:i:s',strtotime($jammasuk));
        $data['jamkeluar']     = date('H:i:s',strtotime($jamkeluar));
        $data['sisapesan']     = $sisapesan;
        $data['intjamkerja']   = $intjamkerja;

        echo json_encode($data);
    }

    function add_finish_cutting($intmesin, $intshift){
        $datatemp = array(
            'dttanggal' => date('Y-m-d H:i:s'),
            'intmesin'  => $intmesin,
            'intshift'  => $intshift,
            'ttemp'     => date('H:i:s'),
            'inttype'   => 1
                    );
        
        $datenow = date('Y-m-d');
        $timenow  = date('H:is');

        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $worksift1        = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2        = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $listgedung       = $this->model->getdatagedung();
        $datamesin        = $this->modelapp->getdatadetailcustom('m_mesin', $intmesin, 'intid');
        $intgedung        = $datamesin[0]->intgedung;

        $intgedungspecial = 0;
        foreach ($listgedung as $dtgedung) {
            if ($dtgedung->intspesial == 1) {
                $intgedungspecial = $dtgedung->intid;
            }

            if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
            }
        }

        if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $worksift1);
            $date2      = date('Y-m-d H:i:s');
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
        }
        
        $result        = $this->modelapp->insertdata('temp_time',$datatemp);
        if ($result) {
            $datawaktu       = $this->model->getwaktu($intmesin, $date1, $date2, $intshift);
            $data['dttime']  = $datawaktu[0]->ttemp;
        }
        
        echo json_encode($data);
    }

    function web_realtime(){
        $data['year']   = date('Y');
        $data['month']  = date('m');
        $data['date']   = date('d');
        $data['day']    = date('l');
        $data['hour']   = date('H');
        $data['minute'] = date('i');
        $data['second'] = date('s');

        echo json_encode($data);
    }

    function getstatuspo($vcpo) {
        $cekloadplan = $this->model->cekloadplan($vcpo);
        if (count($cekloadplan) == 0) {
            $status = 0;   //po tidak ada
        } else {
            $status = 1; //PO ada
        }
        $data['listmodel'] = $cekloadplan;
        $data['intstatus'] = $status;

        echo json_encode($data);
    }

    function getaktual_ajax($intkomponen, $intmodel, $vcpo) {
        $allow_po     = $this->modelapp->getappsetting('allowance_po')[0]->vcvalue;
        $dataloadplan = $this->model->getpo($intmodel, $vcpo);
        $dataaktual   = $this->model->getaktual($intmodel, $intkomponen, $vcpo);
       
        if ($dataaktual[0]->jumlahpasang >= ($dataloadplan[0]->intqty + round($dataloadplan[0]->intqty * ($allow_po/100)))) {
            $status       = 0;  // target po terpenuhi
        } else {
            $status       = 1; // ok
        }

        $data['intstatus'] = $status;

        echo json_encode($data);
    }
}
