<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('DashboardModel');
        $this->model = $this->DashboardModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;
        $this->jmlnotes      = $this->AppModel->getjmlnotes()[0]->jmldata;
        $this->datanotes     = $this->AppModel->getdatanotes();
        $this->notesin       = $this->AppModel->getnotesin()[0]->notesin;

        if (!$this->session->intid && $this->session->intid != 'tpm') {
            redirect(base_url('akses/login'));
        }
    }

    function index(){
        redirect(base_url('dashboard/view'));
    }

    function view(){
        $datalogin    = $this->model->getdatalogin();
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $af           = 0;
        $pf           = 0;
        $qf           = 0;
        $roee         = 0;
        $countmesin   = (count($datalogin) == 0) ? 1 : count($datalogin);
        $shift        = getshift(time(date("H:i:s")));
        
        $data['title']       = 'Dashboard';
        $data['controller']  = 'dashboard';
        $data['sparepart']   = ($this->model->stoksparepart()[0]->jumlah) ? $this->model->stoksparepart()[0]->jumlah : 0 ;
        $data['mesin']       = ($this->model->totalmesin()[0]->jumlah) ? $this->model->totalmesin()[0]->jumlah : 0 ;
        $data['downtime']    = ($this->model->totaldowntime()[0]->jumlah) ? $this->model->totaldowntime()[0]->jumlah : 0 ;
        $data['oee']         = 0;

        $bulan = array(
                    '1'  => 'Jan',
                    '2'  => 'Feb',
                    '3'  => 'Mar',
                    '4'  => 'Apr',
                    '5'  => 'May',
                    '6'  => 'Jun',
                    '7'  => 'Jul',
                    '8'  => 'Aug',
                    '9'  => 'Sep',
                    '10' => 'Oct',
                    '11' => 'Nov',
                    '12' => 'Dec'
                );

        // $datadowntime = ($this->model->grafikdowntime()[0]->jumlah) ? $this->model->grafikdowntime()[0]->jumlah : 0 ;
        
        $data2        = [];
        $data3 = [];

        // foreach ($datadowntime as $downtime) {
            for ($q=1; $q <= 12; $q++) {
                $nextmonth = ($q == 12) ? 1 : $q + 1 ;
                $year = ($q == 12) ? date('Y') + 1 : date('Y') ;
                $datamonthly  = $this->model->getmonthly( $q, $year);
                $datanextmonthly  = $this->model->getmonthly( $nextmonth, $year);
                $jumlahpenggunaan = (count($datamonthly) == 0 || count($datanextmonthly) == 0) ? 0 : $datanextmonthly[0]->decactive_ed - $datamonthly[0]->decactive_ed;

                array_push($data3, $jumlahpenggunaan);
            }
        //}

        for ($i=1; $i <= 12; $i++) { 
            $month = $bulan[$i];
            array_push($data2, $month);
        }

        $data['datadowntime'] = $data3;
        $data['dttanggal']    = $data2;

        //print_r($data2); exit();

        
        
        $this->template->set_layout('default')->build('dashboard_view' . '/index',$data);
    }

    function grafikmonth(){
        $bulan = array(
                    '1'  => 'Jan',
                    '2'  => 'Feb',
                    '3'  => 'Mar',
                    '4'  => 'Apr',
                    '5'  => 'May',
                    '6'  => 'Jun',
                    '7'  => 'Jul',
                    '8'  => 'Aug',
                    '9'  => 'Sept',
                    '10' => 'Oct',
                    '11' => 'Nov',
                    '12' => 'Dec'
                );

        $result = array();
        $data = array();
        $data2 = array();
        $firstmonth = date("M",strtotime("-6 month"));
        for ($i = 1; $i <= 6; $i++) {
            $datenow = date('m', strtotime($firstmonth . " +$i month"));
            $downtime = $this->model->grafikdowntime($datenow);
            // $datatemp = array(
            //             'y' => date('M', strtotime($firstmonth . " +$i month")),
            //             'a' => ($downtime[0]->jumlah) ? $downtime[0]->jumlah : 0
            //         );
            array_push($data, date('M', strtotime($firstmonth . " +$i month")));
            array_push($data2, ($downtime[0]->jumlah) ? $downtime[0]->jumlah : 0);
        }
        $result['data'] = $data2;
        $result['bulan'] = $data;
        echo json_encode($result);
    }

    function grafiktopdowntime(){
        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','2048M');
        $data  = $this->model->grafiktopdowntime();
        $data1 = [];
        $data2 = [];
        foreach ($data as $dt) {
            array_push($data1, $dt->jmldowntime);
            array_push($data2, $dt->vcdowntime);
        }
        
        $result['data']       = $data1;
        $result['vcdowntime'] = $data2;

        echo json_encode($result);
    }

    function oee(){
        $datalogin    = $this->model->getdatalogin();
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $af           = 0;
        $pf           = 0;
        $qf           = 0;
        $roee         = 0;
        $countmesin   = (count($datalogin) == 0) ? 1 : count($datalogin);
        $shift        = getshift(time(date("H:i:s")));

        foreach ($datalogin as $login) {
            $dtlogin         = $login->loginshift1;
            $dtnow           = date('Y-m-d H:i:s');
            $availableshift1 = ($shift == 1) ? ceil((strtotime($dtnow) - strtotime($dtlogin))/60) : ceil((strtotime($login->logoutshift1) - strtotime($login->loginshift1))/60);
            $availableshift2 = ($shift == 1) ? 0 : ceil((strtotime($dtnow) - strtotime($login->loginshift2))/60);


            $intmesin     = $login->intmesin;
            $datadowntime = $this->model->getdatadowntime($dtlogin,$dtnow,$intmesin);
            $output       = $this->model->getdataoutput($dtlogin,$dtnow,$intmesin);

            $decdowntime        = ($datadowntime[0]->decdurasi) ? $datadowntime[0]->decdurasi : 0 ;
            $availabletime      = $availableshift1 + $availableshift2;
            $plannedstop        = $plandowntime + $datadowntime[0]->decplanned;
            $plannedproduction  = $availabletime - $plannedstop;
            $runtime            = $plannedproduction - $decdowntime;
            $theoriticalct      = $output[0]->decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;

            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            $af   = $af + ($availabilityfactor * 100);
            $pf   = $pf + ($performancefactor * 100);
            $qf   = $qf + ($qualityfactor * 100);
            $roee = $roee + ($oee * 100);
        }

        $data['title']              = 'OEE';
        $data['listgedung']         = $this->modelapp->getdatalist('m_gedung');
        $data['availabilityfactor'] = round($af/$countmesin,2);
        $data['performancefactor']  = round($pf/$countmesin,2);
        $data['qualityfactor']      = round($qf/$countmesin,2);
        $data['oee']                = round($roee/$countmesin,2);
        $this->template->set_layout('default')->build('dashboard_view' . '/oee',$data);
    }

    function oee_building($intgedung){
        $data['title']     = 'OEE';
        $data['intgedung'] = $intgedung;
        $this->template->set_layout('default')->build('dashboard_view/oee_building',$data);
    }

    function oee_machine($intmesin){
        $data['title']    = 'OEE';
        $data['intmesin'] = $intmesin;
        $this->template->set_layout('default')->build('dashboard_view/oee_machine',$data);
    }

    function oeeby($intgedung,$intmesingedung){
        $datalogin           = $this->model->getdataloginby($intgedung,$intmesingedung);
        $plandowntime        = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $af                  = 0;
        $afruntime           = 0;
        $afplannedproduction = 0;
        $pf                  = 0;
        $pfactualoutput      = 0;
        $pftheoriticaloutput = 0;
        $qf                  = 0;
        $qfgoodoutput        = 0;
        $roee                = 0;
        $countmesin          = (count($datalogin) == 0) ? 1 : count($datalogin);
        $shift               = getshift(time(date("H:i:s")));

        foreach ($datalogin as $login) {
            $dtlogin         = $login->loginshift1;
            $dtnow           = date('Y-m-d H:i:s');
            $availableshift1 = ($shift == 1) ? ceil((strtotime($dtnow) - strtotime($dtlogin))/60) : ceil((strtotime($login->logoutshift1) - strtotime($login->loginshift1))/60);
            $availableshift2 = ($shift == 1) ? 0 : ceil((strtotime($dtnow) - strtotime($login->loginshift2))/60);

            $intmesin     = $login->intmesin;
            $datadowntime = $this->model->getdatadowntime($dtlogin,$dtnow,$intmesin);
            $output       = $this->model->getdataoutput($dtlogin,$dtnow,$intmesin);

            $decdowntime        = ($datadowntime[0]->decdurasi) ? $datadowntime[0]->decdurasi : 0 ;
            $availabletime      = $availableshift1 + $availableshift2;
            $plannedstop        = $plandowntime + $datadowntime[0]->decplanned;
            $plannedproduction  = $availabletime - $plannedstop;
            $runtime            = $plannedproduction - $decdowntime;
            $theoriticalct      = $output[0]->decct;
            $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
            $actualoutput       = $output[0]->intactual;
            $defectiveproduct   = $output[0]->intreject;

            $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$plannedproduction;
            $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
            $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($output[0]->intactual - $output[0]->intreject)/$actualoutput;
            $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

            $af                  = $af + ($availabilityfactor * 100);
            $afruntime           = $afruntime + $runtime;
            $afplannedproduction = $afplannedproduction + $plannedproduction;
            $pf                  = $pf + ($performancefactor * 100);
            $pfactualoutput      = $pfactualoutput + $actualoutput;
            $pftheoriticaloutput = $pftheoriticaloutput + $theoriticaloutput;
            $qf                  = $qf + ($qualityfactor * 100);
            $qfgoodoutput        = $qfgoodoutput + ($actualoutput - $defectiveproduct);
            $roee                = $roee + ($oee * 100);
        }

        $data['availabilityfactor'] = round($af/$countmesin,2);
        $data['runtime']            = $afruntime;
        $data['plannedproduction']  = $afplannedproduction;
        $data['performancefactor']  = round($pf/$countmesin,2);
        $data['actualoutput']       = $pfactualoutput;
        $data['theoriticaloutput']  = $pftheoriticaloutput;
        $data['qualityfactor']      = round($qf/$countmesin,2);
        $data['goodoutput']         = $qfgoodoutput;
        $data['oee']                = round($roee/$countmesin,2);
        $data['listmesin']          = ($intmesingedung == 0) ? $datalogin : null;

        echo json_encode($data);
    }

    function grafikoee(){
        $bulan = array(
            '1'  => 'Jan',
            '2'  => 'Feb',
            '3'  => 'Mar',
            '4'  => 'Apr',
            '5'  => 'May',
            '6'  => 'Jun',
            '7'  => 'Jul',
            '8'  => 'Aug',
            '9'  => 'Sept',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec'
        );

        $result = array();
        $data = array();
        $data2 = array();
        $firstmonth = date("M",strtotime("-6 month"));
        for ($i = 1; $i <= 6; $i++) {
            $datenow = date('m', strtotime($firstmonth . " +$i month"));
            $downtime = $this->model->grafikdowntime($datenow);
            // $datatemp = array(
            //             'y' => date('M', strtotime($firstmonth . " +$i month")),
            //             'a' => ($downtime[0]->jumlah) ? $downtime[0]->jumlah : 0
            //         );
            array_push($data, date('M', strtotime($firstmonth . " +$i month")));
            array_push($data2, ($downtime[0]->jumlah) ? $downtime[0]->jumlah : 0);
        }
        $result['data'] = $data2;
        $result['bulan'] = $data;
        echo json_encode($result);
    }

    function machine(){
        $gedung         = $this->model->getgedung();
        $mesin          = [];

        foreach ($gedung as $gd) {
            $mesintemp = $this->model->getmesin($gd->intid);
            array_push($mesin, $mesintemp);
        }
 
        $data['title']  = 'Machine';
        $data['gedung'] = $gedung;
        $data['mesin']  = $mesin; 
        $this->template->set_layout('default')->build('dashboard_view' . '/machine',$data);
    }

    function detail_list($intgedung, $intcell) {
        // $jmldata            = $this->model->getjmldata( $intgedung, $intcell);
        // $offset             = ($halaman - 1) * $this->limit;
        // $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        // $data['halaman']    = $halaman;
        // $data['jmlpage']    = $jmlpage;
        // $data['firstnum']   = $offset;

        $data['title']  = 'Detail List Machine';
        $data['dataP']  = $this->model->getdatadetail($intgedung, $intcell);
        $data['datatitle'] = $this->model->getdatatitle($intgedung, $intcell);

        $this->template->set_layout('default')->build('dashboard_view' . '/detail_machine',$data);
    }

    function edit($intid){
        $resultData = $this->model->getdatadetail2($intid);
        $data = array(
                    'intid'         => $resultData[0]->intid,
                    'vckode'        => $resultData[0]->vckode,
                    'vcnama'        => $resultData[0]->vcnama,
                    'intbrand'      => $resultData[0]->intbrand,
                    'vcjenis'       => $resultData[0]->vcjenis,
                    'vcserial'      => $resultData[0]->vcserial,
                    'vcpower'       => $resultData[0]->vcpower,
                    'intgedung'     => $resultData[0]->intgedung,
                    'intgedungback' => $resultData[0]->intgedung,
                    'intcell'       => $resultData[0]->intcell,
                    'intcellback'   => $resultData[0]->intcell,
                    'intdeparture'  => $resultData[0]->intdeparture,
                    'intgroup'      => $resultData[0]->intgroup,
                    'vclocation'    => $resultData[0]->vclocation,
                    'vcgambar'      => $resultData[0]->vcgambar,
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s')
                );

        $data['title']      = 'Data machine';
        $data['action']     = 'Edit';
        $data['listbrand']  = $this->modelapp->getdatalist('m_brand');
        $data['listarea']   = $this->modelapp->getdatalist('m_area');
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listgroup']  = $this->modelapp->getdatalist('m_mesin_group');
        $data['listcell']   = $this->modelapp->getdatalistall('m_cell', $resultData[0]->intgedung,'intgedung');

        $this->template->set_layout('default')->build('dashboard_view' . '/edit_mesin',$data);
    }

    function aksi($tipe,$intid,$status=0){
        
        if ($tipe == 'Edit') {
            $vckode        = $this->input->post('vckode');
            $vcnama        = $this->input->post('vcnama');
            $intbrand      = $this->input->post('intbrand');
            $vcjenis       = $this->input->post('vcjenis');
            $vcserial      = $this->input->post('vcserial');
            $vcpower       = $this->input->post('vcpower');
            $intgedung     = $this->input->post('intgedung');
            $intcell       = $this->input->post('intcell');
            $intgedungback = $this->input->post('intgedungback');
            $intcellback   = $this->input->post('intcellback');
            $intdeparture  = $this->input->post('intdeparture');
            $intgroup      = $this->input->post('intgroup');
            $vclocation    = $this->input->post('vclocation');

            if (!empty($_FILES["vcgambar"]["name"])) {
                $vcgambar = $this->model->_uploadImage($vcjenis);
            } else {
                $vcgambar = $this->input->post('oldfile');
            }

            $data    = array(
                    'vckode'       => $vckode,
                    'vcnama'       => $vcnama,
                    'intbrand'     => $intbrand,
                    'vcjenis'      => $vcjenis,
                    'vcserial'     => $vcserial,
                    'vcpower'      => $vcpower,
                    'intgedung'    => $intgedung,
                    'intcell'      => $intcell,
                    'intdeparture' => $intdeparture,
                    'intgroup'     => $intgroup,
                    'vclocation'   => $vclocation,
                    'vcgambar'     => $vcgambar,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata('m_mesin',$data,$intid);
            if ($result) {
                redirect(base_url('dashboard/detail_list/' . $intgedungback . '/' . $intcellback));
            }
        
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

    function getkode($intgroup, $action, $vckode=''){
        $datagroup  = $this->modelapp->getdatadetailcustom('m_mesin_group',$intgroup,'intid');
        $vckodelast = $this->model->getlastkode()[0]->vckode;
        $kodetemp   = ($action == 'Edit') ? substr($vckode, 4) : substr($vckodelast, 4) + 1;
        $kodetemp2  = str_pad($kodetemp, 6, 0, STR_PAD_LEFT);
        // if (substr($vckodelast, 4) >= 6657 && substr($vckodelast, 4) < 6662) {
        //     $vckodelast = $this->model->getlastkode2()[0]->vckode;
        //     $kodetemp   = ($action == 'Edit') ? substr($vckode, 4) : substr($vckodelast, 4) + 1;
        //     $kodetemp2  = str_pad($kodetemp, 6, 0, STR_PAD_LEFT);
        // }
        echo $datagroup[0]->vckode . $kodetemp2;
    }

    function detail($intid){
        $data['dataMain']    = $this->model->getdatadetail2($intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2('m_mesin_history',$intid);
        $this->load->view('dashboard_view/detail',$data);
    }

    function downtime($month=0){
        $gedung   = $this->model->getgedung();
        $datacell = array();
        $dtmonth  = ($month == 0) ? date('m') : $month; 
        foreach ($gedung as $gd) {
            $cell   = $this->model->getcell($gd->intid);
            $datadt = array();

            for ($i=0; $i < count($cell); $i++) {
                $dt      = $this->model->getreportdowntime($cell[$i]->intid,$dtmonth);
                array_push($datadt, $dt);
            }
            array_push($datacell, $datadt);
        }

        $intbulan = array(
                    '1'  => 'January',
                    '2'  => 'February',
                    '3'  => 'March',
                    '4'  => 'April',
                    '5'  => 'May',
                    '6'  => 'June',
                    '7'  => 'July',
                    '8'  => 'August',
                    '9'  => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December'
                );
        
        $data['title']   = 'Downtime';
        $data['gedung']  = $gedung;
        $data['cell']    = $datacell;
        $data['bulan']   = $intbulan;
        $data['dtmonth'] = $dtmonth;
        $this->template->set_layout('default')->build('dashboard_view' . '/downtime',$data);
    }


    function sparepartstock(){
        $gedung         = $this->model->getgedung();
        $stock          = $this->model->getstoksparepart();
        $stockpergedung = [];

        foreach ($gedung as $gd) {
            $stockpergedungemp = $this->model->getstoksparepartpergedung($gd->intid);
            array_push($stockpergedung, $stockpergedungemp);
        }
        
        $data['title']          = 'Sparepart Stock';
        $data['stock']          = $stock;
        $data['gedung']         = $gedung;
        $data['stockpergedung'] = $stockpergedung;
        $this->template->set_layout('default')->build('dashboard_view' . '/sparepartstock',$data);
    }

    // OEE Part
    function getoeeajax(){
        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','2048M');
        $intshift = getshift(strtotime(date('H:i:s')));

        $datenow        = date('Y-m-d');
        $timenow        = date('H:is');
        $intshift       = 1;
        $worksift1      = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2      = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $availabletime1 = 0;
        $availabletime2 = 0;
        $istirahat1     = 0;
        $istirahat2     = 0;

        $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
        $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
        $totalaf          = 0;
        $totalpf          = 0;
        $totalqf          = 0;
        $totaloee         = 0;
        $totalmesin       = 0;
        $listmesin        = $this->model->getdatamesin();

        foreach ($listmesin as $dtmesin) {
            if ($dtmesin->intid == $intgedungspecial) {
                $worksift1 = $worksift1special;
            }
            $intmesin = $dtmesin->intid;
            if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                $date1      = date('Y-m-d ' . $worksift1);
                $date2      = date('Y-m-d H:i:s');
            } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                $date2      = date('Y-m-d H:i:s');
            }

            if ($intshift == 1) {
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
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

            $totalaf  = $totalaf + $availabilityfactor;
            $totalpf  = $totalpf + $performancefactor;
            $totalqf  = $totalqf + $qualityfactor;
            $totaloee = $totaloee + round(($oee * 100),2);
            $totalmesin++;
        }
        $avgaf  = ($totalmesin == 0) ? 0 : $totalaf/$totalmesin;
        $avgpf  = ($totalmesin == 0) ? 0 : $totalpf/$totalmesin;
        $avgqf  = ($totalmesin == 0) ? 0 : $totalqf/$totalmesin;
        // $avgoee = ($totalmesin == 0) ? 0 : round($totaloee/$totalmesin,2);
        $avgoee = ($totalmesin == 0) ? 0 : round(($avgaf*$avgpf*$avgqf)*100,2);

        echo $avgoee . '%';
    }

    function getgedungajax(){
        $intshift = getshift(strtotime(date('H:i:s')));

        $datenow        = date('Y-m-d');
        $timenow        = date('H:is');
        $intshift       = 1;
        $worksift1      = $this->modelapp->getappsetting('start-work-sift1')[0]->vcvalue;
        $worksift2      = $this->modelapp->getappsetting('start-work-sift2')[0]->vcvalue;
        $availabletime1 = 0;
        $availabletime2 = 0;
        $istirahat1     = 0;
        $istirahat2     = 0;
        $listgedung      = $this->model->getdatagedung('m_gedung');
        $datapergedung   = array();

        foreach ($listgedung as $dtgedung) {
            $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
            $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;
            $totalaf          = 0;
            $totalpf          = 0;
            $totalqf          = 0;
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
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                }

                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
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

                $totalaf  = $totalaf + $availabilityfactor;
                $totalpf  = $totalpf + $performancefactor;
                $totalqf  = $totalqf + $qualityfactor;
                $totaloee = $totaloee + round(($oee * 100),2);
                $totalmesin++;
            }
            $avgaf  = ($totalmesin == 0) ? 0 : $totalaf/$totalmesin;
            $avgpf  = ($totalmesin == 0) ? 0 : $totalpf/$totalmesin;
            $avgqf  = ($totalmesin == 0) ? 0 : $totalqf/$totalmesin;
            // $avgoee = ($totalmesin == 0) ? 0 : round($totaloee/$totalmesin,2);
            $avgoee = ($totalmesin == 0) ? 0 : round(($avgaf*$avgpf*$avgqf)*100,2);

            array_push($datapergedung, array('intgedung' => $dtgedung->intid,'vcgedung' => $dtgedung->vcnama,'avgoee' => $avgoee));
        }

        $data['gedung']         = $datapergedung;
        $this->load->view('dashboard_view/oee_view/oee_all',$data);
    }

    function getmesinajax($intgedung){
        $centralcutting   = $this->model->getcentralcutting($intgedung);
        if (count($centralcutting) > 1) {
            $this->getmesinajax2($intgedung);
        } else {
            $this->getmesinajax1($intgedung);
        }
        
    }

    function getmesinajax1($intgedung){
        $datagedung       = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $mesin            = $this->model->getdatamesin($intgedung);
        $datenow          = date('Y-m-d');
        $timenow          = date('H:is');
        $intshift         = getshift(strtotime(date('H:i:s')));
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
        $mesintemp        = array();

        $totafcell    = 0;
        $totpfcell    = 0;
        $totqfcell    = 0;
        $totoeecell   = 0;
        $jmlmesincell = 0;

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        foreach ($mesin as $dtmesin) {
            $intmesin = $dtmesin->intid;
            if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
                $date1      = date('Y-m-d ' . $worksift1);
                $date2      = date('Y-m-d H:i:s');
            } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                $date2      = date('Y-m-d H:i:s');
            }

            if ($intshift == 1) {
                $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                $availabletime2 = 0;
                $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
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

            $datatemp = array(
                        'intmesin'    => $dtmesin->intid,
                        'vcmesin'     => $dtmesin->vcnama,
                        'vckodemesin' => $dtmesin->vckode,
                        'avgoee'      => round(($oee * 100),2),
                        'statusmesin' => $statusmesin,
                    );
            array_push($mesintemp,$datatemp);

            $totafcell  = $totafcell + $availabilityfactor;
            $totpfcell  = $totpfcell + $performancefactor;
            $totqfcell  = $totqfcell + $qualityfactor;
            $totoeecell = $totoeecell + round(($oee * 100),2);
            $jmlmesincell++;
        }

        $avgafcell  = ($jmlmesincell == 0) ? 0 : $totafcell/$jmlmesincell;
        $avgpfcell  = ($jmlmesincell == 0) ? 0 : $totpfcell/$jmlmesincell;
        $avgqfcell  = ($jmlmesincell == 0) ? 0 : $totqfcell/$jmlmesincell;
        $avgoeecell = $avgafcell * $avgpfcell * $avgqfcell;

        $data['mesin']    = $mesintemp;
        $data['vcgedung'] = $datagedung[0]->vcnama;

        $data['avgaf']  = round(($avgafcell*100),2);
        $data['avgpf']  = round(($avgpfcell*100),2);
        $data['avgqf']  = round(($avgqfcell*100),2);
        $data['avgoee'] = round(($avgoeecell*100),2);

        $this->load->view('dashboard_view/oee_view/oee_building',$data);
    }

    function getmesinajax2($intgedung){
        $datagedung       = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
        $dtcell = $this->model->getcentralcutting($intgedung);
        $intshift = getshift(strtotime(date('H:i:s')));
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
                } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
                    $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
                    $date2      = date('Y-m-d H:i:s');
                }

                if ($intshift == 1) {
                    $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
                    $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
                    $availabletime2 = 0;
                    $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
                    $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
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
            $avgoeecell = $avgafcell * $avgpfcell * $avgqfcell;

            ++$loopcell;
            $celltemp = array(
                        'vccell'             => 'Cutting '.substr($datagedung[0]->vcnama,-1).$loopcell,
                        'availabilityfactor' => round(($avgafcell*100),2),
                        'performancefactor'  => round(($avgpfcell*100),2),
                        'qualityfactor'      => round(($avgqfcell*100),2),
                        'oee'                => round(($avgoeecell*100),2),
                        'datamesin'          => $datapermesin
                    );

            array_push($datacell, $celltemp);
        }

        $data['cell']    = $datacell;
        $data['vcgedung'] = $datagedung[0]->vcnama;

        $this->load->view('dashboard_view/oee_view/oee_building2',$data);
    }

    function getmesindetailajax($intmesin){
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

        if ($intgedung == $intgedungspecial) {
            $worksift1 = $worksift1special;
        }

        $datenow  = date('Y-m-d');
        $timenow  = date('H:is');
        // $intshift = 1;
        if ($timenow >= $worksift1 && $timenow <= '23:59:59') {
            $date1      = date('Y-m-d ' . $worksift1);
            $date2      = date('Y-m-d H:i:s');
        } elseif ($timenow >= '00:00:00' && $timenow <= '06:59:59') {
            $date1      = date('Y-m-d ' . $worksift1, strtotime('-1 day', strtotime(date('Y-m-d'))));
            $date2      = date('Y-m-d H:i:s');
        }

        if ($intshift == 1) {
            $dataoperator   = $this->model->getoperator($intmesin, $datenow, $intshift, 1);
            $availabletime1 = (count($dataoperator) == 0) ? 0 : ceil((strtotime(date('Y-m-d H:i:s')) - strtotime(date($datenow.' '.$worksift1)))/60);
            $availabletime2 = 0;
            $istirahat1     = (date('H:i:s') > '13:00:00' && $availabletime1 > 0) ? 60 : 0;
            $datadowntime   = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
            $output         = $this->model->getdataoutputall($date1,$date2,$intmesin);
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

        $data['oee']                = round(($oee*100),2);
        $data['runtime']            = $runtime;
        $data['plannedproduction']  = $availabletime;
        $data['actualoutput']       = $actualoutput;
        $data['theoriticaloutput']  = $theoriticaloutput;
        $data['goodoutput']         = $goodoutput;
        $data['availabilityfactor'] = round(($availabilityfactor*100),2);
        $data['performancefactor']  = round(($performancefactor*100),2);
        $data['qualityfactor']      = round(($qualityfactor*100),2);
        $data['listoutput1']        = $dataoutput1;
        $data['listdowntime1']      = $listdowntime1;
        $data['listoutput2']        = $dataoutput2;
        $data['listdowntime2']      = $listdowntime2;
        $data['intshift']           = $intshift;
        $data['intmesin']           = $intmesin;
        $data['intgedung']          = $datamesin[0]->intgedung;
        $data['vckodemesin']        = $datamesin[0]->vckode;
        $data['vcmesin']            = $datamesin[0]->vcnama;
        $data['vcnik']              = $vcnik;
        $data['vcoperator']         = $vcoperator;

        $this->load->view('dashboard_view/oee_view/oee_machine',$data);
    }

    function oeetest(){
        ini_set('max_execution_time', 0); 
        ini_set('memory_limit','2048M');
        $weekpos = $this->getweeknumber(date('Y-m-d'));
        $dataoee = array();
        for ($i=1; $i <= $weekpos; $i++) {
            $intweek    = $weekpos - $i;
            $daypos     = date('w', strtotime("-".$intweek." weeks")) - 1;
            $datepos    = date('Y-m-d', strtotime("-".$intweek." weeks"));
            $week_start = date('Y-m-d', strtotime($datepos . ' -'.$daypos.' days'));
            $week_end   = date('Y-m-d', strtotime($datepos . ' +'.(4-$daypos).' days'));
            $datediff   = (strtotime($week_end) - strtotime($week_start))/(3600*24);
            $totafweek  = 0;
            $totpfweek  = 0;
            $totqfweek  = 0;
            $loopweek   = 0;
            // echo 'Minggu ke ' . $i . '<br>';
            for ($j=0; $j <= $datediff ; $j++) {
                $dt               = $j + 1;
                
                $listmesin = $this->model->getdatamesin();
                $loopmesin = 0;
                $totaf = 0;
                $totpf = 0;
                $totqf = 0;
                foreach ($listmesin as $dtmesin) {
                    $intmesin = $dtmesin->intid;
                    $timestart        = '07:00:00';
                    $timefinish       = '06:59:59';
                    $worksift1special = $this->modelapp->getappsetting('start-work-sift1-special')[0]->vcvalue;
                    $intgedungspecial = $this->modelapp->getappsetting('intgedung-special')[0]->vcvalue;

                    if ($dtmesin->intgedung == $intgedungspecial) {
                        $timestart        = '06:30:00';
                        $timefinish       = '06:29:59';
                    }

                    $date1              = date( "Y-m-d " . $timestart, strtotime( $week_start . ' ' ." +" . $j . " day" ) );
                    $date2              = date( "Y-m-d " . $timefinish, strtotime( $week_start . ' ' ." +" . $dt . " day" ) );
                    $dataoperator       = $this->model->getoperator2($intmesin, $date1, $date2, 1, 1);
                    $dataoperator2      = $this->model->getoperator2($intmesin, $date1, $date2, 2, 1);
                    $availabletime1     = (count($dataoperator) == 0) ? 0 : $dataoperator[0]->intjamkerja + $dataoperator[0]->intjamlembur;
                    $availabletime2     = (count($dataoperator2) == 0) ? 0 : $dataoperator2[0]->intjamkerja + $dataoperator2[0]->intjamlembur;
                    $istirahat1         = ($availabletime1 <= 0) ? 0 : (($availabletime1 >= 720) ? 60 : 0);
                    $istirahat2         = 60;
                    $availabletime      = ($availabletime1 - $istirahat1) + ($availabletime2 - $istirahat2);
                    // $datadowntime       = $this->model->getdatadowntimeall($date1,$date2,$intmesin);
                    // $output             = $this->model->getdataoutputall($date1,$date2,$intmesin);
                    $dtoutput           = $this->model->getowntimeoutput($date1,$date2,$intmesin);
                    $actualoutput       = $dtoutput[0]->intactual;
                    $defectiveproduct   = $dtoutput[0]->intreject;
                    $totaldowntime      = $dtoutput[0]->decdurasi;
                    $runtime            = $availabletime - $totaldowntime;
                    $theoriticalct      = $dtoutput[0]->decct;
                    $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
                    $actualoutput       = $dtoutput[0]->intactual;
                    $defectiveproduct   = $dtoutput[0]->intreject;
                    $availabilityfactor = ($availabletime == 0) ? 0 : $runtime/$availabletime;
                    $performancefactor  = ($theoriticaloutput == 0 || $availabletime == 0) ? 0 : $actualoutput/$theoriticaloutput;
                    $qualityfactor      = ($actualoutput == 0 || $availabletime == 0) ? 0 : ($dtoutput[0]->intactual - $dtoutput[0]->intreject)/$actualoutput;
                    $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

                    $totaf = $totaf + $availabilityfactor;
                    $totpf = $totpf + $performancefactor;
                    $totqf = $totqf + $qualityfactor;

                    $loopmesin++;

                }
                $avgaf = ($loopmesin == 0) ? 0 : $totaf/$loopmesin;
                $avgpf = ($loopmesin == 0) ? 0 : $totpf/$loopmesin;
                $avgqf = ($loopmesin == 0) ? 0 : $totqf/$loopmesin;

                $totafweek = $totafweek + $avgaf;
                $totpfweek = $totpfweek + $avgpf;
                $totqfweek = $totqfweek + $avgqf;

                $loopweek++;
            }

            $avgweekaf = ($loopweek == 0) ? 0 : $totafweek/$loopweek;
            $avgweekpf = ($loopweek == 0) ? 0 : $totpfweek/$loopweek;
            $avgweekqf = ($loopweek == 0) ? 0 : $totqfweek/$loopweek;
            $oee = $avgweekaf * $avgweekpf * $avgweekqf;
            array_push($dataoee,round(($oee*100),2));
            // echo $avgweekaf . ' | ' . $avgweekpf . ' | ' . $avgweekqf . '<br>';
            // echo '------------------------------------------------- <br>';
            // echo '<br><br>';
        }

        $dataweek = array();
        $dataoee2 = array();
        for ($i=1; $i <= 5; $i++) { 
            array_push($dataweek,('Week ' . $i));
            // $datatemp = ($dataoee[$i] == null) ? 0 : $dataoee[$i];
            // array_push($dataoee2,$datatemp);
        }
        $result['data'] = $dataoee;
        $result['bulan'] = $dataweek;
        echo json_encode($result);
    }

    function getweeknumber($date){

        $tgl = date_parse($date);
        $tanggal = $tgl['day'];
        $bulan = $tgl['month'];
        $tahun = $tgl['year'];
        
        //tanggal 1 tiap bulan
        
        $tanggalAwalBulan = mktime(0, 0, 0, $bulan, 1, $tahun);
        $mingguAwalBulan  = (int) date('W', $tanggalAwalBulan);
        
        //tanggal sekarang
        
        $tanggalYangDicari       = mktime(0, 0, 0, $bulan, $tanggal, $tahun);
        $mingguTanggalYangDicari = (int) date('W', $tanggalYangDicari);
        $mingguKe                = $mingguTanggalYangDicari - $mingguAwalBulan + 1;

        $harike = date('N', strtotime(date('Y-m-',strtotime($date)).'1'));
        if ($harike > 2) {
            $mingguKe--;
        }

        if ($mingguKe == 0) {
            $datetemp = date('Y-m-d', (strtotime ( '-1 day' , strtotime ( $date) ) ));
            $tgl = date_parse($datetemp);
            $tanggal = $tgl['day'];
            $bulan = $tgl['month'];
            $tahun = $tgl['year'];
            
            //tanggal 1 tiap bulan
            
            $tanggalAwalBulan = mktime(0, 0, 0, $bulan, 1, $tahun);
            $mingguAwalBulan  = (int) date('W', $tanggalAwalBulan);
            
            //tanggal sekarang
            
            $tanggalYangDicari       = mktime(0, 0, 0, $bulan, $tanggal, $tahun);
            $mingguTanggalYangDicari = (int) date('W', $tanggalYangDicari);
            $mingguKe                = $mingguTanggalYangDicari - $mingguAwalBulan + 1;

            $harike = date('N', strtotime(date('Y-m-',strtotime($datetemp)).'1'));
            if ($harike > 2) {
                $mingguKe--;
            }
        }

        return $mingguKe;
        
    }
}
