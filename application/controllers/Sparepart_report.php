<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart_report extends MY_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('Sparepart_reportModel');
        $this->model = $this->Sparepart_reportModel;
    }

    function index(){
    	redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intsparepart = $this->input->get('intsparepart');
        $dtmonth      = $this->input->get('dtmonth');
        $intyear      = $this->input->get('intyear');
        $datareport   = [];
        $offset       = 0;
        $jmlpage      = 0;

        if ($intsparepart != '' && $dtmonth != '') {
            if ($intsparepart == 0) {
                $jmldata    = $this->model->jumlahreportall($dtmonth,$intyear);
                $offset     = ($halaman - 1) * $this->limit;
                $jmlpage    = ceil($jmldata / $this->limit);
                $datareport = $this->model->getreportalllimit($dtmonth,$intyear,$offset,$this->limit);
            } elseif ($intsparepart > 0) {
                $jmldata    = $this->model->jumlahreportpersparepart($intsparepart,$dtmonth,$intyear);
                $offset     = ($halaman - 1) * $this->limit;
                $jmlpage    = ceil($jmldata / $this->limit);
                $datareport = $this->model->getreportpersparepatlimit($intsparepart,$dtmonth,$intyear,$offset,$this->limit);
            }
        }
        

        $data['title']         = $this->title;
        $data['controller']    = $this->controller;
        $data['intsparepart']  = $intsparepart;
        $data['halaman']       = $halaman;
        $data['jmlpage']       = $jmlpage;
        $data['firstnum']      = $offset;
        $data['report']        = $datareport;
        $data['listsparepart'] = $this->modelapp->getdatalistall('m_sparepart');

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function exportexcel(){
        $listbulan = array(
                '1' => 'January',
                '2' => 'February',
                '3' => 'March',
                '4' => 'April',
                '5' => 'May',
                '6' => 'July',
                '7' => 'June',
                '8' => 'August',
                '9' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            );
        $intsparepart = $this->input->get('intsparepart');
        $dtmonth      = $this->input->get('dtmonth');
        $intyear      = $this->input->get('intyear');
        $datareport   = [];

        if ($intsparepart != '' && $dtmonth != '') {
            if ($intsparepart == 0) {
                $datareport = $this->model->getreportall($dtmonth,$intyear);
                $this->exportall($datareport,$listbulan[$dtmonth],$intyear);
            } elseif ($intsparepart > 0) {
                $datareport = $this->model->getreportpersparepat($intsparepart,$dtmonth,$intyear);
                $this->exportpersparepart($datareport,$listbulan[$dtmonth],$intyear);
            }
        }
    }

    function exportall($data, $bulan, $tahun){
        
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Spare Part Logistic ". $bulan . " ". $tahun)
                     ->setSubject("Report Spare Part Logistic")
                     ->setDescription("Report Spare Part Logistic")
                     ->setKeywords("Report Spare Part Logistic");

        // variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
          'font' => array('bold' => true), // Set font nya jadi bold
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Report Spare Part Logistic ". $bulan . " ". $tahun);
        $excel->getActiveSheet()->mergeCells('B1:J1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Code");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Sparepart");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Part Number");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Unit");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Quantity");
        $excel->setActiveSheetIndex(0)->setCellValue('G4', "Awal");
        $excel->setActiveSheetIndex(0)->setCellValue('H4', "Masuk");
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "Keluar");
        $excel->setActiveSheetIndex(0)->setCellValue('J4', "Akhir");

        $excel->getActiveSheet()->mergeCells('B3:B4');
        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C3:C4');
        $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('D3:D4');
        $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('E3:E4');
        $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('F3:F4');
        $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('G3:J3');
        $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('B3:B4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('C3:C4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('D3:D4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('E3:E4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('F3:F4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('G3:J3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('G4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('H4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('I4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('J4')->applyFromArray($style_col);

        $numrow = 5;
        $no = 0;
        foreach ($data as $dataset) {
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ++$no);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vckodesparepart);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $dataset->vcsparepart);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->vcpart);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->vcunit);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $dataset->awal);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $dataset->masuk);
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $dataset->keluar);
            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $dataset->akhir);

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);       
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Report Spare Part Logistic");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Spare Part Logistic '. $bulan . ' '. $tahun.'.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $write->save('php://output');
    }

    function exportpersparepart($data, $bulan, $tahun){
        // print_r($data);exit();
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Spare Part Logistic ". $bulan . " ". $tahun)
                     ->setSubject("Report Spare Part Logistic")
                     ->setDescription("Report Spare Part Logistic")
                     ->setKeywords("Report Spare Part Logistic");

        // variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
          'font' => array('bold' => true), // Set font nya jadi bold
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        // variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Report Spare Part Logistic ". $data[0]->vcsparepart . " " . $bulan . " ". $tahun);
        $excel->getActiveSheet()->mergeCells('B1:G1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Code");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Date");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Quantity");
        $excel->setActiveSheetIndex(0)->setCellValue('E4', "Masuk");
        $excel->setActiveSheetIndex(0)->setCellValue('F4', "Keluar");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Remaks");

        $excel->getActiveSheet()->mergeCells('B3:B4');
        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('C3:C4');
        $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('D3:D4');
        $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('E3:F3');
        $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->mergeCells('G3:G4');
        $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('B3:B4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('C3:C4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('D3:D4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('E3:F3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('E4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('F4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('G3:G4')->applyFromArray($style_col);

        $numrow = 5;
        $no = 0;
        foreach ($data as $dataset) {
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ++$no);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vckode);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date('d M Y', strtotime($dataset->dtorder)));
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->decqtymasuk);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->decqtykeluar);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $dataset->vcketerangan);

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);       
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Report Spare Part Logistic");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Spare Part Logistic '. $data[0]->vcsparepart . ' ' . $bulan . ' '. $tahun.'.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $write->save('php://output');
    }

}
