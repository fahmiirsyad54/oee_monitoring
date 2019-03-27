<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oee_gedung extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('AppModel');
        $this->load->model('Oee_gedungModel');
        $this->model = $this->Oee_gedungModel;
        $this->modelapp = $this->AppModel;
    }

    function index(){
        redirect(base_url('oee_gedung/all'));
    }

    function gedunga(){
        $intmesin     = $this->session->intmesinop;
        $plandowntime = $this->modelapp->getappsetting('planned-downtime')[0]->vcvalue;
        $login        = $this->model->getdataloginD();

        $datestart    = date("Y-m-d H:i:s", strtotime($login[0]->dtlogin));
        $datefinish   = date("Y-m-d H:i:s");  

        $downtime     = $this->model->getdatadowntimeD($datestart,$datefinish,$intmesin);
        $output       = $this->model->getdataoutputD($datestart,$datefinish,$intmesin);
        
        $availabletime      = ceil((strtotime($datefinish) - strtotime($datestart)) / 60);
        $plannedstop        = $plandowntime;
        $plannedproduction  = $availabletime - $plannedstop;
        $totaldowntime      = $downtime[0]->jmldowntime;
        $runtime            = $plannedproduction - $totaldowntime;
        $theoriticalct      = ($output[0]->jmlct == 0) ? 0 : $output[0]->jmlct / $output[0]->jmlid;
        $theoriticaloutput  = ($theoriticalct == 0) ? 0 : ceil(60/$theoriticalct*$runtime);
        $actualoutput       = $output[0]->jmlpasang + $output[0]->jmlreject;
        $defectiveproduct   = $output[0]->jmlreject;
        $availabilityfactor = (($runtime == 0) ? 0 : $runtime/$plannedproduction);
        $performancefactor  = (($actualoutput == 0) ? 0 : $actualoutput/$theoriticaloutput);
        $qualityfactor      = (($output[0]->jmlpasang == 0) ? 0 : $output[0]->jmlpasang/$actualoutput);
        $oee                = $availabilityfactor*$performancefactor*$qualityfactor;

        $data = array(
            'datestart'          => $datestart, 
            'availabletime'      => $availabletime, 
            'plannedproduction'  => $plannedproduction, 
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

        $data['title']         = 'Gedung A';
        $data['controller']    = 'oee_gedung';

        $this->load->view('oee_gedung_view/gedunga',$data);
    }

}
