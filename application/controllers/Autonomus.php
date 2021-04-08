<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autonomus extends MY_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('AutonomusModel');
        $this->model = $this->AutonomusModel;
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
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $data = array(
                    'intid'           => 0,
                    'dttanggal'       => date('d-m-Y'),
                    'intgedung'       => 0,
                    'intcell'         => 0,
                    'intmesin'        => 0,
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

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listscore']  = array(0,10,20,30,40,50,60,70,80,90,100);

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetailcustom($this->table,$intid,'intid');
        $data = array(
                    'intid'           => $resultData[0]->intid,
                    'dttanggal'       => date('d-m-Y', strtotime($resultData[0]->dttanggal)),
                    'intgedung'       => $resultData[0]->intgedung,
                    'intcell'         => $resultData[0]->intcell,
                    'intmesin'        => $resultData[0]->intmesin,
                    'intoperator'     => $resultData[0]->intoperator,
                    'intformterisi'   => $resultData[0]->intformterisi,
                    'intimplementasi' => $resultData[0]->intimplementasi,
                    'vcketerangan'    => $resultData[0]->vcketerangan,
                    'intupdate'       => $this->session->intid,
                    'dtupdate'        => date('Y-m-d H:i:s')
                );

        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listcell']   = $this->modelapp->getdatalistall('m_cell',$resultData[0]->intgedung,'intgedung');
        $data['listscore']  = array(0,10,20,30,40,50,60,70,80,90,100);

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
            $intgedung       = $this->input->post('intgedung');
            $dttanggal       = $this->input->post('dttanggal');
            $intcell         = $this->input->post('intcell');
            $intmesin        = $this->input->post('intmesin');
            $intoperator     = $this->input->post('intoperator');
            $intformterisi   = $this->input->post('intformterisi');
            $intimplementasi = $this->input->post('intimplementasi');
            $vcketerangan    = $this->input->post('vcketerangan');
            for ($i=0; $i < count($intmesin); $i++) { 
                $data    = array(
                    'dttanggal'       => date('Y-m-d',strtotime($dttanggal)),
                    'intgedung'       => $intgedung,
                    'intcell'         => $intcell,
                    'intmesin'        => $intmesin[$i],
                    'intoperator'     => $intoperator[$i],
                    'intformterisi'   => $intformterisi[$i],
                    'intimplementasi' => $intimplementasi[$i],
                    'vcketerangan'    => $vcketerangan[$i],
                    'intadd'          => $this->session->intid,
                    'dtadd'           => date('Y-m-d H:i:s'),
                    'intupdate'       => $this->session->intid,
                    'dtupdate'        => date('Y-m-d H:i:s')
                );

                $result = $this->modelapp->insertdata($this->table,$data);
            }

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
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
                    'intmesin'        => $intmesin[0],
                    'intoperator'     => $intoperator[0],
                    'intformterisi'   => $intformterisi[0],
                    'intimplementasi' => $intimplementasi[0],
                    'vcketerangan'    => $vcketerangan[0],
                    'intupdate'       => $this->session->intid,
                    'dtupdate'        => date('Y-m-d H:i:s')
                );
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "Report Audit Autonomus Maintenance " . $bulan[$intbulan] . " " . $inttahun);
        $excel->getActiveSheet()->mergeCells('A1:G1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "AREA");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "MACHINE");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "OPERATOR");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "FORM");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "IMPLEMENTATION");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "REMAKS");


        $excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('A3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('B3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('C3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('D3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('E3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('F3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('G3')->applyFromArray($style_col);

        $data   = $this->model->getdataam('pr_am', $intbulan, $inttahun, $intgedung, $intcell);
        $numrow = 4;
        $no     = 0;
        foreach ($data as $dataset) {

            if ($dataset->intorder == 1) {
                $rowform         = $numrow - 3;
                $rowimplementasi = $numrow - 2;
                $rowjumlahform   = $numrow - 1;
                $excel->setActiveSheetIndex(0)->setCellValue('H'.$rowform, 'Form Terisi 100%');
                $excel->setActiveSheetIndex(0)->setCellValue('I'.$rowform, $dataset->intformterisi);

                $excel->setActiveSheetIndex(0)->setCellValue('H'.$rowimplementasi, 'Implementasi 100%');
                $excel->setActiveSheetIndex(0)->setCellValue('I'.$rowimplementasi, $dataset->intimplementasi);

                $excel->setActiveSheetIndex(0)->setCellValue('H'.$rowjumlahform, 'Jumlah Mesin');
                $excel->setActiveSheetIndex(0)->setCellValue('I'.$rowjumlahform, $dataset->intjumlahmesin);

                $excel->getActiveSheet()->mergeCells('A'.$numrow.':G'.$numrow);
                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_warna);
            } else {
                $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, ++$no);
                $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $dataset->vccell);
                $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vckodemesin);
                $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $dataset->vcoperator);
                $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->intformterisi);
                $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->intimplementasi);
                $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $dataset->vcketerangan);

                $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            }

            // if ($dataset->intorder == 1) {
            //     $excel->getActiveSheet()->mergeCells('B'.$numrow.':D'.$numrow);
            //     $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_warna);
            //     $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_warna);
            //     $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_warna);
            //     $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_warna);
            //     $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_warna);
            //     $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_warna);
            //     $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_warna);
            // }

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

    function get_formscore(){
        $datamesin    = $this->modelapp->getdatalistall('m_mesin');
        $dataoperator = $this->modelapp->getdatalistall('m_karyawan',3,'intjabatan');
        $listscore    = array(0,10,20,30,40,50,60,70,80,90,100);

        $data = array(
                'listmachine'  => $datamesin,
                'listoperator' => $dataoperator,
                'listscore' => $listscore
            );
        
        $this->load->view($this->view . '/form_score',$data);
    }

}
