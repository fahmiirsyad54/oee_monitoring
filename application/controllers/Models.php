<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Models extends MY_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('ModelsModel');
        $this->model = $this->ModelsModel;
    }

 
    function index(){
    	redirect(base_url($this->controller . '/lihat'));
    }

    function view($halaman=1){
        $keyword = $this->input->get('key');

        $jmldata            = $this->model->getjmldata($this->table,$keyword);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword);

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller'] = $this->controller;
        $data['dataMain']   = $this->modelapp->getdatadetail($this->table,$intid);
        $dataDetail         = $this->model->get_detail_komponen($intid);
        $dataDetail2        = $this->model->get_detail_komponen2($intid);
        $komponen   = [];
        $komponen2 = [];

        foreach ($dataDetail as $dm) {
            $datact       = $this->model->get_detail_ct($dm->intid);
            $datatemp = array(
                        'intid'        => $dm->intid,
                        'intkomponen'  => $dm->intkomponen,
                        'intlayer'     => $dm->intlayer,
                        'vckomponen'   => $dm->vckomponen,
                        'datact'       => $datact

                    );
            array_push($komponen, $datatemp);   
        }

        foreach ($dataDetail2 as $dm2) {
            $datact2       = $this->model->get_detail_ct2($dm2->intid);
            $datatemp2 = array(
                        'intid'        => $dm2->intid,
                        'intkomponen'  => $dm2->intkomponen,
                        'intlayer'     => $dm2->intlayer,
                        'vckomponen'   => $dm2->vckomponen,
                        'datact'       => $datact2

                    );
            array_push($komponen2, $datatemp2);   
        }

        $data['dataDetail']  = $komponen;
        $data['dataDetail2'] = $komponen2;
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){ 

        $layer = array(
                        '2' => '2 Layer',
                        '4' => '4 Layer',
                        '6' => '6 Layer',
                        '8' => '8 Layer'
                    );

        $data = array(
                    'intid'        => 0,
                    'vcnama'       => '',
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 0
                );

        $data['title']        = $this->title;
        $data['action']       = 'Add';
        $data['controller']   = $this->controller;
        $data['dataModels']   = [];
        $data['dataModels2']  = [];
        $data['listkomponen'] = $this->modelapp->getdatalist('m_komponen');
        $data['listkomponen2'] = $this->modelapp->getdatalist('m_komponen');
        $data['listlayer']    = $layer;
        $data['listlayer2']    = $layer;

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData  = $this->modelapp->getdatadetail($this->table,$intid);
        $dataModels  = $this->model->get_detail_komponen($intid);
        $dataModels2 = $this->model->get_detail_komponen2($intid);
        $komponen    = [];
        $komponen2   = [];

        foreach ($dataModels as $dm) {
            $layer = array(
                        '2' => '2 Layer',
                        '4' => '4 Layer',
                        '6' => '6 Layer',
                        '8' => '8 Layer'
                    );

            $datact       = $this->model->get_detail_ct($dm->intid);
            $listkomponen = $this->modelapp->getdatalist('m_komponen');
            $datatemp = array(
                        'intid'        => $dm->intid,
                        'intkomponen'  => $dm->intkomponen,
                        'intlayer'     => $dm->intlayer,
                        'vckomponen'   => $dm->vckomponen,
                        'datact'       => $datact,
                        'listkomponen' => $listkomponen,
                        'listlayer'    => $layer

                    );
            array_push($komponen, $datatemp);   
        }

        foreach ($dataModels2 as $dm2) {
            $layer2 =array(
                        '2' => '2 Layer',
                        '4' => '4 Layer',
                        '6' => '6 layer',
                        '8' => '8 Layer'
                    );
            $datact2       = $this->model->get_detail_ct2($dm2->intid);
            $listkomponen2 = $this->modelapp->getdatalist('m_komponen');
            $datatemp2 = array(
                            'intid2'        => $dm2->intid,
                            'intkomponen2'  => $dm2->intkomponen,
                            'intlayer2'     => $dm2->intlayer,
                            'vckomponen2'   => $dm2->vckomponen,
                            'datact2'       => $datact2,
                            'listkomponen2' => $listkomponen2,
                            'listlayer2'    => $layer2
                        );
                array_push($komponen2, $datatemp2);
        }

        $data = array(
                    'intid'        => $resultData[0]->intid,
                    'vcnama'       => $resultData[0]->vcnama,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );

        $data['title']         = $this->title;
        $data['action']        = 'Edit';
        $data['controller']    = $this->controller;
        $data['dataModels']    = $komponen;
        $data['dataModels2']   = $komponen2;
        $data['listkomponen']  = (count($dataModels) == 0) ? $this->modelapp->getdatalist('m_komponen') : $komponen;
        $data['listkomponen2'] = (count($dataModels2) == 0) ? $this->modelapp->getdatalist('m_komponen') : $komponen2;

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
                    $array[]['error'] = $error . ' Already Exist !';
                }
            }
        } elseif ($tipe == 'required') {
            foreach ($data as $key => $value) {
                if ($value == '') {
                    $front = substr($key,0,2);
                    $end   = substr($key,2);
                    $end2  = substr($key,3);
                    $error = ($front == 'vc') ? $end : $end2 ;
                    $array[]['error'] = 'column ' . $error . ' can not be empty !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Add') {
            //comelz
            $vcnama        = $this->input->post('vcnama');
            $intkomponen   = $this->input->post('intkomponen');
            $deccycle_time = $this->input->post('deccycle_time');
            $intkomponenct = $this->input->post('intkomponenct');
            $intlayer      = $this->input->post('intlayer');
            $intlayerct    = $this->input->post('intlayerct');
            $vclayer       = $this->input->post('vclayer');
            $countdetail   = count($intkomponen);
            $countdetailct = count($deccycle_time);
            //laser
            $intkomponen2   = $this->input->post('intkomponen2');
            $deccycle_time2 = $this->input->post('deccycle_time2');
            $intkomponenct2 = $this->input->post('intkomponenct2');
            $intlayer2      = $this->input->post('intlayer2');
            $intlayerct2    = $this->input->post('intlayerct2');
            $vclayer2       = $this->input->post('vclayer2');
            $countdetail2   = count($intkomponen2);
            $countdetailct2 = count($deccycle_time2);

            $data    = array(
                    'vcnama'       => $vcnama,
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 1
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                for ($i=0; $i < $countdetail; $i++) {
                    $decct_temp = 0;
                    for ($k=0; $k < $countdetailct; $k++) { 
                         if ($intkomponen[$i] == $intkomponenct[$k] && $intlayer[$i] == $intlayerct[$k]) {
                             $decct_temp = $deccycle_time[$k];
                         }
                     } 
                    $data_detail = array(
                                    'intheader'     => $result,
                                    'intkomponen'   => $intkomponen[$i],
                                    'deccycle_time' => $decct_temp,
                                    'intlayer'      => $intlayer[$i]
                                );
                        $resultkomponen = $this->modelapp->insertdata($this->table . '_komponen',$data_detail);

                    if ($resultkomponen) {
                        for ($j=0; $j < $countdetailct; $j++) {
                            if ($intkomponenct[$j] == $intkomponen[$i]) {
                                $data_komponen = array(
                                            'intheader'     => $resultkomponen,
                                            'deccycle_time' => $deccycle_time[$j],
                                            'vcnama'        => $vclayer[$j],
                                            'intlayerct'    => $intlayerct[$j]
                                            );
                                $this->modelapp->insertdata($this->table . '_komponen_ct',$data_komponen);
                            }
                        }
                    }
                }

                for ($i2=0; $i2 < $countdetail2; $i2++) {
                    $decct_temp2 = 0;
                    for ($k2=0; $k2 < $countdetailct2; $k2++) { 
                         if ($intkomponen2[$i2] == $intkomponenct2[$k2] && $intlayer2[$i2] == $intlayerct2[$k2]) {
                             $decct_temp2 = $deccycle_time2[$k2];
                         }
                     } 
                    $data_detail2 = array(
                                    'intheader'     => $result,
                                    'intkomponen'   => $intkomponen2[$i2],
                                    'deccycle_time' => $decct_temp2,
                                    'intlayer'      => $intlayer2[$i2]
                                );
                        $resultkomponen2 = $this->modelapp->insertdata($this->table . '_komponen2',$data_detail2);

                    if ($resultkomponen2) {
                        for ($j2=0; $j2 < $countdetailct2; $j2++) {
                            if ($intkomponenct2[$j2] == $intkomponen2[$i2]) {
                                $data_komponen2 = array(
                                            'intheader'     => $resultkomponen2,
                                            'deccycle_time' => $deccycle_time2[$j2],
                                            'vcnama'        => $vclayer2[$j2],
                                            'intlayerct'    => $intlayerct2[$j2]
                                            );
                                $this->modelapp->insertdata($this->table . '_komponen_ct2',$data_komponen2);
                            }
                        }
                    }
                }
                
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
            $vcnama           = $this->input->post('vcnama');
            //comelz
            $intmodelkomponen = $this->input->post('intmodelkomponen');
            $intkomponen      = $this->input->post('intkomponen');
            $deccycle_time    = $this->input->post('deccycle_time');
            $intkomponenct    = $this->input->post('intkomponenct');
            $intlayer         = $this->input->post('intlayer');
            $intlayerct       = $this->input->post('intlayerct');
            $vclayer          = $this->input->post('vclayer');
            $countdetail      = count($intkomponen);
            $countdetailct    = count($deccycle_time);

            //laser
            $intmodelkomponen2 = $this->input->post('intmodelkomponen2');
            $intkomponen2      = $this->input->post('intkomponen2');
            $deccycle_time2    = $this->input->post('deccycle_time2');
            $intkomponenct2    = $this->input->post('intkomponenct2');
            $intlayer2         = $this->input->post('intlayer2');
            $intlayerct2       = $this->input->post('intlayerct2');
            $vclayer2          = $this->input->post('vclayer2');
            $countdetail2      = count($intkomponen2);
            $countdetailct2    = count($deccycle_time2);

            $data    = array(
                    'vcnama'       => $vcnama,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                //comelz
                $this->modelapp->deletedata($this->table . '_komponen',$intid,'intheader');
                for ($i=0; $i < $countdetail; $i++) { 
                    $decct_temp = 0;
                    for ($k=0; $k < $countdetailct; $k++) { 
                         if ($intkomponen[$i] == $intkomponenct[$k] && $intlayer[$i] == $intlayerct[$k]) {
                             $decct_temp = $deccycle_time[$k];
                         }
                     } 
                    $data_detail = array(
                                    'intheader'     => $intid,
                                    'intkomponen'   => $intkomponen[$i],
                                    'intlayer'   => $intlayer[$i],
                                    'deccycle_time' => $decct_temp
                                );
                        $resultkomponen = $this->modelapp->insertdata($this->table . '_komponen',$data_detail);

                    if ($resultkomponen) {
                        $this->modelapp->deletedata($this->table . '_komponen_ct',$intmodelkomponen[$i],'intheader');
                        for ($j=0; $j < $countdetailct; $j++) {
                            if ($intkomponenct[$j] == $intkomponen[$i]) {
                                $data_komponen = array(
                                            'intheader'     => $resultkomponen,
                                            'deccycle_time' => $deccycle_time[$j],
                                            'vcnama'        => $vclayer[$j],
                                            'intlayerct'    => $intlayerct[$j]


                            );

                                $this->modelapp->insertdata($this->table . '_komponen_ct',$data_komponen);
                            }
                        }
                    }
                }

                //laser
                $this->modelapp->deletedata($this->table . '_komponen2',$intid,'intheader');
                for ($i2=0; $i2 < $countdetail2; $i2++) { 
                    $decct_temp2 = 0;
                    for ($k2=0; $k2 < $countdetailct2; $k2++) { 
                         if ($intkomponen2[$i2] == $intkomponenct2[$k2] && $intlayer2[$i2] == $intlayerct2[$k2]) {
                             $decct_temp2 = $deccycle_time2[$k2];
                         }
                     } 
                    $data_detail2 = array(
                                    'intheader'     => $intid,
                                    'intkomponen'   => $intkomponen2[$i2],
                                    'intlayer'   => $intlayer2[$i2],
                                    'deccycle_time' => $decct_temp2
                                );
                        $resultkomponen2 = $this->modelapp->insertdata($this->table . '_komponen2',$data_detail2);

                    if ($resultkomponen2) {
                        $this->modelapp->deletedata($this->table . '_komponen_ct2',$intmodelkomponen2[$i2],'intheader');
                        for ($j2=0; $j2 < $countdetailct2; $j2++) {
                            if ($intkomponenct2[$j2] == $intkomponen2[$i2]) {
                                $data_komponen2 = array(
                                            'intheader'     => $resultkomponen2,
                                            'deccycle_time' => $deccycle_time2[$j2],
                                            'vcnama'        => $vclayer2[$j2],
                                            'intlayerct'    => $intlayerct2[$j2]


                            );

                                $this->modelapp->insertdata($this->table . '_komponen_ct2',$data_komponen2);
                            }
                        }
                    }
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

    function addsme2($intmodel){
        $dataSME2      = $this->model->getteknologimesin();
        $datamodelSME2 = $this->model->getmodelteknologimesin($intmodel);
        $data['controller'] = $this->controller;
        $data['intmodel']   = $intmodel;
        $data['dataSME2']   = (count($datamodelSME2) == 0) ? $dataSME2 : $datamodelSME2;
        $this->load->view($this->view . '/form_sme2',$data);
    }

    function simpansme2($intmodel){
        $intprosesgroup    = $this->input->post('intprosesgroup');
        $intteknologimesin = $this->input->post('intteknologimesin');
        $intapplicable     = $this->input->post('intapplicable');
        $intcomply         = $this->input->post('intcomply');

        $datamodelSME2 = $this->model->getmodelteknologimesin($intmodel);
        $this->modelapp->deletedata($this->table . '_sme2',$intmodel,'intheader');

        for ($i=0; $i < count($intprosesgroup); $i++) { 
            $datadetail = array(
                    'intheader'         => $intmodel,
                    'intprosesgroup'    => $intprosesgroup[$i],
                    'intteknologimesin' => $intteknologimesin[$i],
                    'intapplicable'     => $intapplicable[$i],
                    'intcomply'         => $intcomply[$i],
                );
            $this->modelapp->insertdata($this->table . '_sme2',$datadetail);
        }
        redirect(base_url($this->controller . '/view'));
    }

    function getdetailajax($intid){
        $data = $this->model->get_detail_komponen($intid);
        echo json_encode($data);
    }

    function form_detail_models(){
        $layer = array(
                        '2' => '2 Layer',
                        '4' => '4 Layer',
                        '6' => '6 Layer',
                        '8' => '8 Layer'
                    );

        $data['listkomponen'] = $this->modelapp->getdatalist('m_komponen');
        $data['listlayer']    = $layer;
        $data['controller']   = $this->controller;

        $this->load->view('models_view/form_models',$data);
    }

    function form_detail_models2(){
        $layer2 = array(
                        '2' => '2 Layer',
                        '4' => '4 Layer',
                        '6' => '6 Layer',
                        '8' => '8 Layer'
                    );

        $data['listkomponen2'] = $this->modelapp->getdatalist('m_komponen');
        $data['listlayer2']    = $layer2;
        $data['controller']   = $this->controller;

        $this->load->view('models_view/form_models2',$data);
    }

    function getintkomponen($intkomponen){
        $data = $this->model->getintkomponen($intkomponen);
        echo json_encode($data);
    }

    function getintkomponen2($intkomponen2){
        $data = $this->model->getintkomponen2($intkomponen2);
        echo json_encode($data);
    }

}
