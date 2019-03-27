<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autonomus_qrcode extends CI_Controller {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('AutonomusModel');
        $this->model = $this->AutonomusModel;

        $this->load->model('AppModel');
        $this->modelapp = $this->AppModel;

        if (!$this->session->intid && $this->session->appname != 'tpm') {
            redirect(base_url('akses/loginam'));
        } else {
            $this->datanotes     = $this->AppModel->getdatanotes();
            $this->jmlnotes      = $this->AppModel->getjmlnotes()[0]->jmldata;
            $this->notesin       = $this->AppModel->getnotesin()[0]->notesin;
        }
    }

    function index(){
        redirect(base_url($this->controller . '/scan'));
    }

    function view($halaman=1){
        $keyword   = $this->input->get('key');
        $intbulan  = ($this->input->get('int1') == '') ? date('m') : $this->input->get('int1');
        $inttahun  = ($this->input->get('int2') == '') ? date('Y') : $this->input->get('int2');
        $intgedung = ($this->input->get('int3') == '') ? 0 : $this->input->get('int3');
        $intcell   = ($this->input->get('int4') == '') ? 0 : $this->input->get('int4');

        $jmldata            = $this->model->getjmldata($this->table,$intbulan,$inttahun,$intgedung,$intcell);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $bulan = array(
                '1'  => 'January',
                '2'  => 'February',
                '3'  => 'March',
                '4'  => 'April',
                '5'  => 'May',
                '6'  => 'Juny',
                '7'  => 'July',
                '8'  => 'August',
                '9'  => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            );

        $tahun = array();

        for ($i=2017; $i <= date('Y'); $i++) { 
            array_push($tahun, $i);
        }

        $cell = [];
        if ($intgedung > 0) {
            $cell = $this->modelapp->getdatalistall('m_cell',$intgedung,'intgedung');
        }

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['keyword']    = $keyword;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$intbulan,$inttahun,$intgedung,$intcell);
        $data['intbulan']   = $intbulan;
        $data['inttahun']   = $inttahun;
        $data['intgedung']  = $intgedung;
        $data['intcell']    = $intcell;
        $data['listbulan']  = $bulan;
        $data['listtahun']  = $tahun;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listcell']   = $cell;

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function add($vckodemesin){
        $datamesin  = $this->modelapp->getdatadetailcustom('m_mesin',$vckodemesin,'vckode');
        $datagedung = $this->modelapp->getdatalist('m_gedung');
        $datacell   = $this->modelapp->getdatalistall('m_cell',$datamesin[0]->intgedung,'intgedung');
        
        $intmesin    = 0;
        $intgedung   = 0;
        $intcell     = 0;
        $vcnamamesin = '';

        if (count($datamesin) > 0) {
            $intmesin    = $datamesin[0]->intid;
            $intgedung   = $datamesin[0]->intgedung;
            $intcell     = $datamesin[0]->intcell;
            $vcnamamesin = $datamesin[0]->vckode . ' - ' . $datamesin[0]->vcnama;
        }

        $data = array(
            'intid'           => 0,
            'dttanggal'       => date('d-m-Y'),
            'intgedung'       => $intgedung,
            'intcell'         => $intcell,
            'intmesin'        => $intmesin,
            'vckodemesin'     => $vckodemesin,
            'vcnamamesin'     => $vcnamamesin,
            'intoperator'     => 0,
            'intformterisi'   => 0,
            'intimplementasi' => 0,
            'vcketerangan'    => '',
            'intadd'          => $this->session->intid,
            'dtadd'           => date('Y-m-d H:i:s'),
            'intupdate'       => $this->session->intid,
            'dtupdate'        => date('Y-m-d H:i:s'),
            'intstatus'       => 0
        );

        $data['title']      = 'Autonomus Maintenance';
        $data['action']     = 'Add';
        $data['controller'] = 'autonomus_qrcode';
        $data['listgedung'] = $datagedung;
        $data['listcell']   = $datacell;
        $data['listscore']  = array(0,10,20,30,40,50,60,70,80,90,100);

        $this->template->set_layout('default')->build('autonomus_qrcode_view/form',$data);
    }

    function getcellajax($intid=0){
        $data = $this->modelapp->getdatalistall('m_cell',$intid,'intgedung');

        echo json_encode($data);
    }

    function getmesinoperatorajax(){
        $dataoperator = $this->modelapp->getdatalistall('m_karyawan',3,'intjabatan');

        $data = array(
                'dataoperator' => $dataoperator
            );

        echo json_encode($data);
    }

    function aksi($tipe,$intid=0,$status=0){
        if ($tipe == 'Add') {
            $intgedung       = $this->input->post('intgedung');
            $dttanggal       = $this->input->post('dttanggal');
            $intcell         = $this->input->post('intcell');
            $intmesin        = $this->input->post('intmesin');
            $intoperator     = $this->input->post('intoperator');
            $intformterisi   = $this->input->post('intformterisi');
            $intimplementasi = $this->input->post('intimplementasi');
            $vcketerangan    = $this->input->post('vcketerangan');
                $data    = array(
                    'dttanggal'       => date('Y-m-d',strtotime($dttanggal)),
                    'intgedung'       => $intgedung,
                    'intcell'         => $intcell,
                    'intmesin'        => $intmesin,
                    'intoperator'     => $intoperator,
                    'intformterisi'   => $intformterisi,
                    'intimplementasi' => $intimplementasi,
                    'vcketerangan'    => $vcketerangan,
                    'intadd'          => $this->session->intid,
                    'dtadd'           => date('Y-m-d H:i:s'),
                    'intupdate'       => $this->session->intid,
                    'dtupdate'        => date('Y-m-d H:i:s')
                );

                $result = $this->modelapp->insertdata('pr_am',$data);

            if ($result) {
                redirect(base_url('autonomus_qrcode/after_save'));
            }
        }
    }

    function after_save(){
        $data['title']      = 'Autonomus Maintenance';
        $data['action']     = 'Add';
        $data['controller'] = 'autonomus_qrcode';
        $this->template->set_layout('default')->build('autonomus_qrcode_view/after_save',$data);
    }

}
