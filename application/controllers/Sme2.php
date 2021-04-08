<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sme2 extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Sme2Model');
        $this->model = $this->Sme2Model;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
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

        $week = array(
                '0' => '-- All Week --',
                '1' => 'Week 1',
                '2' => 'Week 2',
                '3' => 'Week 3',
                '4' => 'Week 4',
                '5' => 'Week 5'
            );

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

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail2($this->table,$intid);
        $data['dataDetail']  = $this->model->getteknologimesin2($intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $data = array(
                    'intid'        => 0,
                    'dttanggal'    => date('d-m-Y'),
                    'intgedung'    => 0,
                    'intcell'      => 0,
                    'intmodel'     => 0,
                    'intweek'      => 0,
                    'vcartikel'    => '',
                    'vcketerangan' => '',
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 0
                );

        $week = array(
                '1' => 'Week 1',
                '2' => 'Week 2',
                '3' => 'Week 3',
                '4' => 'Week 4',
                '5' => 'Week 5'
            );

        $data['title']              = $this->title;
        $data['action']             = 'Add';
        $data['controller']         = $this->controller;
        $data['listgedung']         = $this->modelapp->getdatalist('m_gedung');
        $data['listmodel']          = $this->modelapp->getdatalist('m_models');
        $data['listteknologimesin'] = $this->model->getteknologimesin();
        $data['listweek']           = $week;

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetailcustom($this->table,$intid,'intid');
        $data = array(
                    'intid'     => $resultData[0]->intid,
                    'dttanggal' => date('d-m-Y', strtotime($resultData[0]->dttanggal)),
                    'intgedung' => $resultData[0]->intgedung,
                    'intcell'   => $resultData[0]->intcell,
                    'intmodel'  => $resultData[0]->intmodel,
                    'intweek'   => $resultData[0]->intweek,
                    'vcartikel' => $resultData[0]->vcartikel,
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s')
                );

        $week = array(
                '1' => 'Week 1',
                '2' => 'Week 2',
                '3' => 'Week 3',
                '4' => 'Week 4',
                '5' => 'Week 5'
            );

        $data['title']              = $this->title;
        $data['action']             = 'Edit';
        $data['controller']         = $this->controller;
        $data['listgedung']         = $this->modelapp->getdatalist('m_gedung');
        $data['listcell']           = $this->modelapp->getdatalistall('m_cell',$resultData[0]->intgedung,'intgedung');
        $data['listmodel']          = $this->modelapp->getdatalist('m_models');
        $data['listteknologimesin'] = $this->model->getteknologimesin2($intid);
        $data['listweek']           = $week;

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
                    $array[]['error'] = $error . ' Sudah ada !';
                }
            }
        } elseif ($tipe == 'required') {
            foreach ($data as $key => $value) {
                if ($value == '') {
                    $front = substr($key,0,2);
                    $end   = substr($key,2);
                    $end2  = substr($key,3);
                    $error = ($front == 'vc') ? $end : $end2 ;
                    $array[]['error'] = 'Column ' . $error . ' can not be empty !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Add') {
            $intgedung         = $this->input->post('intgedung');
            $dttanggal         = $this->input->post('dttanggal');
            $intcell           = $this->input->post('intcell');
            $intmodel          = $this->input->post('intmodel');
            $intweek           = $this->input->post('intweek');
            $vcartikel         = $this->input->post('vcartikel');
            $intprosesgroup    = $this->input->post('intprosesgroup');
            $intteknologimesin = $this->input->post('intteknologimesin');
            $intapplicable     = $this->input->post('intapplicable');
            $intcomply         = $this->input->post('intcomply');
            $vcketerangan      = $this->input->post('vcketerangan');

            $_intapplicable = 0;
            $_intcomply     = 0;
            for ($i=0; $i < count($intprosesgroup); $i++) { 
                if ($intapplicable[$i] == 1) {
                    ++$_intapplicable;
                }

                if ($intcomply[$i] == 1) {
                    ++$_intcomply;
                }
            }

            $_decpercent = round(($_intcomply/$_intapplicable) * 100, 0);

            $data    = array(
                    'dttanggal'     => date('Y-m-d',strtotime($dttanggal)),
                    'intgedung'     => $intgedung,
                    'intcell'       => $intcell,
                    'intmodel'      => $intmodel,
                    'intweek'       => $intweek,
                    'vcartikel'     => $vcartikel,
                    'intapplicable' => $_intapplicable,
                    'intcomply'     => $_intcomply,
                    'decpercent'    => $_decpercent,
                    'intadd'        => $this->session->intid,
                    'dtadd'         => date('Y-m-d H:i:s'),
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s')
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                for ($i=0; $i < count($intprosesgroup); $i++) { 
                    $datadetail = array(
                            'intheader'         => $result,
                            'intprosesgroup'    => $intprosesgroup[$i],
                            'intteknologimesin' => $intteknologimesin[$i],
                            'intapplicable'     => (!empty($intapplicable[$i])) ? 1 : 0,
                            'intcomply'         => (!empty($intcomply[$i])) ? 1 : 0,
                            'vcketerangan'      => $vcketerangan[$i]
                        );
                    $this->modelapp->insertdata($this->table . '_detail',$datadetail);
                }
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
            $intgedung         = $this->input->post('intgedung');
            $dttanggal         = $this->input->post('dttanggal');
            $intcell           = $this->input->post('intcell');
            $intmodel          = $this->input->post('intmodel');
            $intweek           = $this->input->post('intweek');
            $vcartikel         = $this->input->post('vcartikel');
            $intprosesgroup    = $this->input->post('intprosesgroup');
            $intteknologimesin = $this->input->post('intteknologimesin');
            $intapplicable     = $this->input->post('intapplicable');
            $intcomply         = $this->input->post('intcomply');
            $vcketerangan      = $this->input->post('vcketerangan');

            $_intapplicable = 0;
            $_intcomply     = 0;
            for ($i=0; $i < count($intprosesgroup); $i++) { 
                if ($intapplicable[$i] == 1) {
                    ++$_intapplicable;
                }

                if ($intcomply[$i] == 1) {
                    ++$_intcomply;
                }
            }

            $_decpercent = round(($_intcomply/$_intapplicable) * 100, 0);
           
            $data    = array(
                    'dttanggal'     => date('Y-m-d',strtotime($dttanggal)),
                    'intgedung'     => $intgedung,
                    'intcell'       => $intcell,
                    'intmodel'      => $intmodel,
                    'intweek'       => $intweek,
                    'vcartikel'     => $vcartikel,
                    'intapplicable' => $_intapplicable,
                    'intcomply'     => $_intcomply,
                    'decpercent'    => $_decpercent,
                    'intupdate'     => $this->session->intid,
                    'dtupdate'      => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            $this->modelapp->deletedata($this->table . '_detail',$intid,'intheader');
            if ($result) {
                for ($i=0; $i < count($intprosesgroup); $i++) { 
                    $datadetail = array(
                            'intheader'         => $intid,
                            'intprosesgroup'    => $intprosesgroup[$i],
                            'intteknologimesin' => $intteknologimesin[$i],
                            'intapplicable'     => (!empty($intapplicable[$i])) ? 1 : 0,
                            'intcomply'         => (!empty($intcomply[$i])) ? 1 : 0,
                            'vcketerangan'      => $vcketerangan[$i]
                        );
                    $this->modelapp->insertdata($this->table . '_detail',$datadetail);
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

    function exportexcel(){
        $column = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

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

        $intbulan  = ($this->input->get('intbulan') == '') ? date('m') : $this->input->get('intbulan');
        $inttahun  = ($this->input->get('inttahun') == '') ? date('Y') : $this->input->get('inttahun');
        $intgedung = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intcell   = ($this->input->get('intcell') == '') ? 0 : $this->input->get('intcell');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Audit Autonomus " . $bulan[$intbulan] . " " . $inttahun)
                     ->setSubject("Report Audit Autonomus")
                     ->setDescription("Report Audit Autonomus")
                     ->setKeywords("Report Audit Autonomus");

        // variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
        'font'       => array('bold' => true), // Set font nya jadi bold
        'alignment'  => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
            'borders' => array(
            'top'     => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // variabel untuk menampung pengaturan style dari isi tabel
            $style_row  = array(
            'alignment' => array(
            'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
        'borders' => array(
        'top'     => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
        'right'   => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
        'bottom'  => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
        'left'    => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $style_warna =  array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '66b3ff')
                            )
                        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "MANUFACTURING EXCELLENCE SAMPLE ROOM SME ASSESMENT FORM");
        $excel->getActiveSheet()->mergeCells('A1:D1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(FALSE); // Set bold
        // $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12); // Set font size 12
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('A2', "PRODUCTION SME LEVEL 2 ASSESSMENT");
        $excel->getActiveSheet()->mergeCells('A2:D2'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(FALSE); // Set bold
        // $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12); // Set font size 12
        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('A3', "");
        $excel->getActiveSheet()->mergeCells('A3:D3'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(FALSE); // Set bold
        // $excel->getActiveSheet()->getStyle('A3')->getFont()->setSize(12); // Set font size 12
        $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('A4', "Factory:");
        $excel->setActiveSheetIndex(0)->setCellValue('B4', "HWI");
        $excel->setActiveSheetIndex(0)->setCellValue('C4', "Auditor:");
        $excel->setActiveSheetIndex(0)->setCellValue('D4', "");

        $excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('A4')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('B4')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('C4')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('D4')->applyFromArray($style_row);

        $excel->setActiveSheetIndex(0)->setCellValue('A5', "Cell:");
        $excel->setActiveSheetIndex(0)->setCellValue('B5', "");
        $excel->setActiveSheetIndex(0)->setCellValue('C5', "Date:");
        $excel->setActiveSheetIndex(0)->setCellValue('D5', "");

        $excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('A5')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('B5')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('C5')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('D5')->applyFromArray($style_row);

        $excel->setActiveSheetIndex(0)->setCellValue('A6', "No");
        $excel->setActiveSheetIndex(0)->setCellValue('B6', "Procces Group");
        $excel->setActiveSheetIndex(0)->setCellValue('C6', "Technology / Machine");
        $excel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C6:D6');

        $excel->setActiveSheetIndex()->getStyle('A6')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('B6')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('C6')->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('D6')->applyFromArray($style_row);

        $datacell = $this->model->getcell();
        $col = 4;
        foreach ($datacell as $cell) {
            $datasme2 = $this->model->getsme2($cell->intid);
            $vcmodel  = '';

            if (count($datasme2) > 0) {
                $vcmodel = $datasme2[0]->vcmodel;
            }

            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 4, $vcmodel);
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, 5, $cell->vcnama);
            $excel->getActiveSheet()->mergeCellsByColumnAndRow($col, 4, $col + 1, 4);
            $excel->getActiveSheet()->mergeCellsByColumnAndRow($col, 5, $col + 1, 5);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, 4)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, 4)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, 5)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, 5)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $col2 = $col + 1;
            $excel->getActiveSheet()->getStyleByColumnAndRow($col2, 4)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col2, 5)->applyFromArray($style_row);

            // $excel->getActiveSheet()->mergeCells($col.'4:'.$col2.'4');
            // $excel->getActiveSheet()->mergeCells($col.'5:'.$col2.'5');
            $col = $col2;
            $col++;
        }

        $colscore = 4;
        foreach ($datacell as $cell) {
            $excel->getActiveSheet()->setCellValueByColumnAndRow($colscore, 6, 'Applicable (Y/N)');
            $excel->getActiveSheet()->getStyleByColumnAndRow($colscore, 6)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($colscore, 6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $colscore2 = $colscore + 1;
                $excel->getActiveSheet()->setCellValueByColumnAndRow($colscore2, 6, 'Comply (Y/N)');
            $excel->getActiveSheet()->getStyleByColumnAndRow($colscore2, 6)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($colscore2, 6)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $colscore = $colscore2;
            $colscore++;
        }

        $data = $this->model->getteknologimesin();
        $numrow = 7;
        $no     = 0;
        foreach ($data as $dataset) {
            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ++$no);
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $dataset->vcprosesgroup);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vcteknologimesin);
            $excel->getActiveSheet()->mergeCells('C'.$numrow.':D'.$numrow);

            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);

            $col = 4;
            foreach ($datacell as $cell) {
                $datasme2      = $this->model->getsme2($cell->intid);
                $intapplicable = '';
                $intcomply     = '';

                if (count($datasme2) > 0) {
                    $intsme2           = $datasme2[0]->intid;
                    $intprosesgroup    = $dataset->intprosesgroup;
                    $intteknologimesin = $dataset->intteknologimesin;
                    $datadetailsme2    = $this->model->getdetailsme2($intsme2,$intprosesgroup, $intteknologimesin);
                    if (count($datadetailsme2) > 0) {
                        $intapplicable = ($datadetailsme2[0]->intapplicable == 1) ? 'Y' : 'N';
                        $intcomply     = ($datadetailsme2[0]->intcomply == 1) ? 'Y' : 'N';
                    }
                }

                $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $numrow, $intapplicable);
                $excel->getActiveSheet()->getStyleByColumnAndRow($col, $numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyleByColumnAndRow($col, $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $col2 = $col + 1;
                $excel->getActiveSheet()->setCellValueByColumnAndRow($col2, $numrow, $intcomply);
                $excel->getActiveSheet()->getStyleByColumnAndRow($col2, $numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyleByColumnAndRow($col2, $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                // $excel->getActiveSheet()->mergeCells($col.'4:'.$col2.'4');
                // $excel->getActiveSheet()->mergeCells($col.'5:'.$col2.'5');
                $col = $col2;
                $col++;
            }

            $numrow++;
        }

        $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, "");
        $excel->getActiveSheet()->getStyle('A'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('A'.$numrow.':D'.$numrow);

        $excel->setActiveSheetIndex()->getStyle('A'.$numrow)->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('B'.$numrow)->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('C'.$numrow)->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('D'.$numrow)->applyFromArray($style_row);

        $numrow2 = $numrow + 1;
        $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow2, "Score Level 2");
        $excel->getActiveSheet()->getStyle('A'.$numrow2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('A'.$numrow2.':D'.$numrow2);

        $excel->setActiveSheetIndex()->getStyle('A'.$numrow2)->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('B'.$numrow2)->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('C'.$numrow2)->applyFromArray($style_row);
        $excel->setActiveSheetIndex()->getStyle('D'.$numrow2)->applyFromArray($style_row);

        $col = 4;
        foreach ($datacell as $cell) {
            $datasme2      = $this->model->getsme2($cell->intid);
            $intapplicable = '';
            $intcomply     = '';
            $decscore      = '';

            if (count($datasme2) > 0) {
                $intsme2       = $datasme2[0]->intid;
                $intapplicable = $datasme2[0]->intsumapplicable;
                $intcomply     = $datasme2[0]->intsumcomply;
                $decscore      = round($intcomply/$intapplicable,2);
            }
            $rowscore = $numrow + 1;
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $numrow, $intapplicable);
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $rowscore, $decscore);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col,$rowscore)->getNumberFormat()->applyFromArray( 
                    array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                );
            $excel->getActiveSheet()->mergeCellsByColumnAndRow($col, $rowscore, $col + 1, $rowscore);

            $excel->getActiveSheet()->getStyleByColumnAndRow($col, $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, $rowscore)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col, $rowscore)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $col2 = $col + 1;
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col2, $numrow, $intcomply);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col2, $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col2, $numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col2, $rowscore)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyleByColumnAndRow($col2, $rowscore)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // $excel->getActiveSheet()->mergeCells($col.'4:'.$col2.'4');
            // $excel->getActiveSheet()->mergeCells($col.'5:'.$col2.'5');
            // $excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            // $excel->getActiveSheet()->getColumnDimension($col2)->setAutoSize(true);
            $col = $col2;
            $col++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        // $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        // $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);       
        // $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        // $excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        // $excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle($bulan[$intbulan] . " " . $inttahun);
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Audit Autonomus Maintenance ' . $bulan[$intbulan] . ' ' . $inttahun . '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    function getcellajax($intid=0){
        $data = $this->modelapp->getdatalistall('m_cell',$intid,'intgedung');

        echo json_encode($data);
    }

    function getmesinoperatorajax(){
        $datamesin    = $this->modelapp->getdatalistall('m_mesin');
        $dataoperator = $this->modelapp->getdatalistall('m_karyawan',3,'intjabatan');

        $data = array(
                'datamesin'    => $datamesin,
                'dataoperator' => $dataoperator
            );

        echo json_encode($data);
    }

    function getteknologimesin($intmodel){
        $datateknologi  = $this->model->getmodelteknologimesin($intmodel);
        $datateknologi2 = $this->model->getteknologimesin();
        $data['listteknologimesin'] = (count($datateknologi) == 0) ? $datateknologi2 : $datateknologi;

        $this->load->view($this->view . '/form_teknologimesin',$data);
    }

}
