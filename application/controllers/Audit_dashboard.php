<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('Audit_dashboardModel');
        $this->model = $this->Audit_dashboardModel;

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
        $month = date('m');
        $intweek = $this->getweeknumber(date('Y-m-d'));
        $totalautonomus = $this->model->totalautonomus();
        $autonomus_disiplin = ($totalautonomus[0]->persendisiplin / $totalautonomus[0]->decjumlahmesin) * 100;
        $autonomus_peduli   = ($totalautonomus[0]->persenpeduli / $totalautonomus[0]->decjumlahmesin) * 100;

        $smelevel2 = $this->model->grafiksme2monthall($month, $intweek);

        $data['title']              = 'Dashboard';
        $data['controller']         = 'dashboard';
        $data['autonomus_disiplin'] = $autonomus_disiplin;
        $data['autonomus_peduli']   = $autonomus_peduli;
        $data['smelevel2']          = $smelevel2[0]->decpercent . '%';
        
        $this->template->set_layout('default')->build('audit_dashboard_view' . '/index',$data);
    }

    // Autonomus Part
    function autonomus($month=0,$year=0){
        $gedung   = $this->model->getgedung();
        $datacell = array();
        $dtmonth  = ($month == 0) ? date('m') : $month;
        $dttahun  = ($year == 0) ? date('Y') : $year; 
        foreach ($gedung as $gd) {
            $cell   = $this->model->getcell($gd->intid);
            $dataam = array();

            for ($i=0; $i < count($cell); $i++) {
                $amdata = $this->model->grafikammonthpercell($dtmonth,$cell[$i]->intid);
                array_push($dataam, $amdata);
            }
            array_push($datacell, $dataam);
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
        $inttahun = array();
        for ($i=2018; $i <= date('Y') ; $i++) { 
            array_push($inttahun, $i);
        }
        
        $data['title']   = 'Autonomus Maintenance';
        $data['gedung']  = $gedung;
        $data['cell']    = $datacell;
        $data['bulan']   = $intbulan;
        $data['tahun']   = $inttahun;
        $data['dtmonth'] = $dtmonth;
        $data['dttahun'] = $dttahun;
        $this->template->set_layout('default')->build('audit_dashboard_view' . '/index_autonomus',$data);
    }

    function grafikmontham(){
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

        $warna_am = array('#00e6e6','#e60000','#000080','#e6e600','#00b33c','#660066','#e6e600','#00b33c','#660066');

        $gedung     = $this->modelapp->getdatalist('m_gedung');
        $result     = array();
        $data       = array();
        $data2      = array();
        $firstmonth = date("M",strtotime("-6 month"));

        for ($i = 1; $i <= 6; $i++) {
            $datenow  = date('m', strtotime($firstmonth . " +$i month"));

            array_push($data, date('M', strtotime($firstmonth . " +$i month")));
        }

        $amdisiplin = array();
        $ampeduli   = array();
        $loop = 0;
        foreach ($gedung as $dtgedung) {
            $datadisiplin = array();
            $datapeduli   = array();
            for ($i = 1; $i <= 6; $i++) {
                $datenow  = date('m', strtotime($firstmonth . " +$i month"));
                $downtime = $this->model->grafikautonomus($datenow,$dtgedung->intid);
                array_push($datadisiplin, ($downtime[0]->persendisiplin) ? $downtime[0]->persendisiplin : 0);
                array_push($datapeduli, ($downtime[0]->persenpeduli) ? $downtime[0]->persenpeduli : 0);
            }
            $datatempdisiplin = array(
                        'label'           => $dtgedung->vcnama,
                        'backgroundColor' => $warna_am[$loop],
                        'data'            => $datadisiplin
                    );
            $datatemppeduli = array(
                        'label'           => $dtgedung->vcnama,
                        'backgroundColor' => $warna_am[$loop],
                        'data'            => $datapeduli
                    );
            array_push($amdisiplin, $datatempdisiplin);
            array_push($ampeduli, $datatemppeduli);
            $loop++;
        }

        $result['bulan']      = $data;
        $result['amdisiplin'] = $amdisiplin;
        $result['ampeduli']   = $ampeduli;
        echo json_encode($result);
    }

    function grafikmonthampercell($intgedung,$intbulan,$inttahun){
        $dataam   = $this->model->grafikammonthallcell($intgedung,$intbulan,$inttahun);
        $cell     = array();
        $disiplin = array();
        $peduli   = array();
        $warna    = array();
        $target   = array();
        foreach ($dataam as $am) {
            array_push($cell, $am->vccell);
            array_push($disiplin, round(($am->persendisiplin/$am->decjumlahmesin) * 100, 1));
            array_push($peduli, round(($am->persenpeduli/$am->decjumlahmesin) * 100, 1));
            array_push($warna, '#3e95cd');
            array_push($target, 100);
        }

        $data['cell']     = $cell;
        $data['disiplin'] = array(
                                'label' => '',
                                'backgroundColor' => $warna,
                                'data' => $disiplin
                            );
        $data['peduli']   = array(
                                'label' => '',
                                'backgroundColor' => $warna,
                                'data' => $peduli
                            );
        $data['targetam']   = array(
                                'label' => 'target',
                                'borderColor' => '#ff6666',
                                'data' => $target,
                                'type' => 'line'
                            );
        echo json_encode($data);
    }

    // SME2 Part
    function sme2($month=0,$year=0,$week=0){
        if ($week == 0) {
            $week = $this->getweeknumber(date('Y-m-d'));
        }
        $gedung   = $this->model->getgedung();
        $datacell = array();
        $dtmonth  = ($month == 0) ? date('m') : $month;
        $dttahun  = ($year == 0) ? date('Y') : $year; 
        foreach ($gedung as $gd) {
            $cell   = $this->model->getcell($gd->intid);
            $datasme2 = array();

            $terisi = 0;
            $totalpersen = 0;
            for ($i=0; $i < count($cell); $i++) {
                $sme2data      = $this->model->grafiksme2monthpercell($dtmonth,$week,$cell[$i]->intid);
                $vccell        = (count($sme2data) > 0) ? $sme2data[0]->vccell : $cell[$i]->vcnama;
                $vcmodel       = (count($sme2data) > 0) ? $sme2data[0]->vcmodel : '';
                $intapplicable = (count($sme2data) > 0) ? $sme2data[0]->intapplicable : '';
                $intcomply     = (count($sme2data) > 0) ? $sme2data[0]->intcomply : '';
                $decpercent    = (count($sme2data) > 0) ? $sme2data[0]->decpercent.'%' : '';
                $decpercentval = (count($sme2data) > 0) ? $sme2data[0]->decpercent : 0;
                $datatempsme2  = array(
                                'vccell'        => $vccell,
                                'vcmodel'       => $vcmodel,
                                'intapplicable' => $intapplicable,
                                'intcomply'     => $intcomply,
                                'decpercent'    => $decpercent,
                                'decpercentval' => $decpercentval
                                );
                array_push($datasme2, $datatempsme2);
            }
            array_push($datacell, $datasme2);
        }; 

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
        $inttahun = array();
        for ($i=2018; $i <= date('Y') ; $i++) { 
            array_push($inttahun, $i);
        }

        $listweek = array(
            '1'  => 'Week 1',
            '2'  => 'Week 2',
            '3'  => 'Week 3',
            '4'  => 'Week 4',
            '5'  => 'Week 5'
        );
        
        $data['title']    = 'SME Level 2';
        $data['gedung']   = $gedung;
        $data['cell']     = $datacell;
        $data['bulan']    = $intbulan;
        $data['tahun']    = $inttahun;
        $data['dtmonth']  = $dtmonth;
        $data['dttahun']  = $dttahun;
        $data['listweek'] = $listweek;
        $data['intweek']  = $week;
        $this->template->set_layout('default')->build('audit_dashboard_view' . '/index_sme2',$data);
    }

    function grafikmonthsme2(){
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

        $warna_am = array('#00e6e6','#e60000','#000080','#e6e600','#00b33c','#660066','#e6e600','#00b33c','#660066');

        $gedung     = $this->modelapp->getdatalist('m_gedung');
        $result     = array();
        $data       = array();
        $data2      = array();
        $firstmonth = date("M",strtotime("-6 month"));

        for ($i = 1; $i <= 6; $i++) {
            $datenow  = date('m', strtotime($firstmonth . " +$i month"));

            array_push($data, date('M', strtotime($firstmonth . " +$i month")));
        }

        $week = array();
        $loop = 0;
        for ($i=0; $i < 5; $i++) {
            $numweek = $i + 1;
            $dataweek = array();
            for ($j = 1; $j <= 6; $j++) {
                $datenow  = date('m', strtotime($firstmonth . " +$j month"));
                $inttahun = date('Y');
                $datasme = $this->model->grafiksme2(0, $numweek, $datenow, $inttahun);
                array_push($dataweek, $datasme[0]->averagesme);
            }
            $datatempweek = array(
                        'label'           => 'Week' . $numweek,
                        'backgroundColor' => $warna_am[$loop],
                        'data'            => $dataweek
                    );
            array_push($week, $datatempweek);
            $loop++;
        }

        $result['bulan'] = $data;
        $result['week']  = $week;
        echo json_encode($result);
    }

    function grafikmonthsme2percell($intgedung,$intbulan,$inttahun,$intweek){
        $dataam   = $this->model->grafikammonthallcell($intgedung,$intbulan,$inttahun);
        $datacell = $this->model->getcell($intgedung);
        $cell     = array();
        $sme2data = array();
        $peduli   = array();
        $warna    = array();
        $target   = array();
        foreach ($datacell as $dtcell) {
            $datatemp = $this->model->grafiksme2monthpercell($intbulan,$intweek,$dtcell->intid);
            array_push($cell, $dtcell->vcnama);
            array_push($sme2data, (count($datatemp) > 0) ? $datatemp[0]->decpercent : 0);
            array_push($warna, '#3e95cd');
            array_push($target, 100);
        }

        $data['cell']     = $cell;
        $data['sme2data'] = array(
                                'label' => '',
                                'backgroundColor' => $warna,
                                'data' => $sme2data
                            );
        $data['targetam']   = array(
                                'label' => 'target',
                                'borderColor' => '#ff6666',
                                'data' => $target,
                                'type' => 'line'
                            );
        echo json_encode($data);
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
