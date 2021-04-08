<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cutting_output extends MY_Controller { 

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('AppModel');
        $this->load->model('Cutting_outputModel');
        $this->model = $this->Cutting_outputModel;
        $this->modelapp = $this->AppModel;
    } 

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword   = $this->input->get('key');
        $intgedung = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intshift  = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from      = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to        = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $dataP     = [];

        //$datediff = (strtotime($to) - strtotime($from))/(3600*24);
        if ($intshift == 0) { 
            $date1   = date( "Y-m-d 07:00:00", strtotime( $from) );
            $date2   = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
            $jmldata = $this->model->getjmldata($this->table, $date1,$date2);
            $offset  = ($halaman - 1) * $this->limit;
            $jmlpage = ceil($jmldata[0]->jmldata / $this->limit);
            $dataP   = $this->model->getdatalimit($this->table,$intgedung,$offset,$this->limit,$date1,$date2);
        } elseif ($intshift > 0) {
            $date1   = date( "Y-m-d 07:00:00", strtotime( $from) );
            $date2   = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
            $jmldata = $this->model->getjmldatapershift($this->table,$date1,$date2, $intshift);
            $offset  = ($halaman - 1) * $this->limit;
            $jmlpage = ceil($jmldata[0]->jmldata / $this->limit);
            $dataP   = $this->model->getdatalimitpershift($this->table,$intgedung,$offset,$this->limit,$date1,$date2,$intshift);
        }

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['keyword']    = $keyword;
        $data['intgedung']  = $intgedung;
        $data['intshift']   = $intshift;
        $data['from']       = $from;
        $data['to']         = $to;
        $data['from_input'] = ($this->input->get('from')) ? date('m/d/Y', strtotime($from)) : '';
        $data['to_input']   = ($this->input->get('to')) ? date('m/d/Y', strtotime($to)) : '';
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['dataP']      = $dataP;
        $data['listgedung']  = $this->modelapp->getdatalistall('m_gedung');
        $data['listshift']  = $this->modelapp->getdatalistall('m_shift');
        $data['hideaction'] = $this->hideaction;

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
            'intid'     => 0 ,
            'dttanggal' => date('m/d/Y H:i'),
            'intadd'    => $this->session->intid ,
            'dtadd'     => date('Y-m-d H:i:s') ,
            'intupdate' => $this->session->intid ,
            'dtupdate'  => date('Y-m-d H:i:s') ,
            'intstatus' => 0
        );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listmodels'] = $this->modelapp->getdatalist('m_models');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData   = $this->modelapp->getdatadetail('pr_cutting_output',$intid);
        $data = array(
                     'intid'     => $resultData[0]->intid,
                     'dttanggal' => date('m/d/Y H:i', strtotime($resultData[0]->dttanggal)),
                     'intgedung' => $resultData[0]->intgedung,
                     'intmodel'  => $resultData[0]->intmodel,
                     'intcell'   => $resultData[0]->intcell,
                     'inttarget' => $resultData[0]->inttarget,
                     'intupdate' => $this->session->intid,
                     'dtupdate'  => date('Y-m-d H:i:s')
                );
        
        $data['title']        = $this->title;
        $data['action']       = 'Edit';
        $data['controller']   = $this->controller;
        $data['listgedung']    = $this->modelapp->getdatalist('m_gedung');
        $data['listmodels']    = $this->modelapp->getdatalist('m_models');

        $this->template->set_layout('default')->build($this->view . '/form_edit',$data);
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
            $dttanggal   = $this->input->post('dttanggal');
            $intgedung   = $this->input->post('intgedung');
            $intmodel    = $this->input->post('intmodel');
            $intcell     = $this->input->post('intcell');
            $inttarget   = $this->input->post('inttarget');
            $countdetail = count($intmodel);
           
            for ($i=0; $i < $countdetail; $i++) {
                $data_detail = array(
                                'dttanggal' => $dttanggal,
                                'intgedung' => $intgedung,
                                'intmodel'  => $intmodel[$i],
                                'intcell'   => $intcell[$i],
                                'inttarget' => $inttarget,
                                'intadd'    => $this->session->intid,
                                'dtadd'     => date('Y-m-d H:i:s'),
                                'intupdate' => $this->session->intid,
                                'dtupdate'  => date('Y-m-d H:i:s'),
                                'intstatus' => 1

                            );
                    $result = $this->modelapp->insertdata($this->table,$data_detail);
            }

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
                
            
        } elseif ($tipe == 'Edit') {
            $dttanggal = $this->input->post('dttanggal');
            $intgedung = $this->input->post('intgedung');
            $intmodel  = $this->input->post('intmodel');
            $intcell   = $this->input->post('intcell');
            $inttarget = $this->input->post('inttarget');
                $data = array(
                    'dttanggal' => $dttanggal,
                    'intgedung' => $intgedung,
                    'intmodel'  => $intmodel,
                    'intcell'   => $intcell,
                    'inttarget' => $inttarget,
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s')
                );
                
            $result = $this->modelapp->updatedata($this->table,$data,$intid);
            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        }
    }

    function form_detail_models(){
        $data['listmodels'] = $this->modelapp->getdatalist('m_models');
        $data['controller'] = $this->controller;

        $this->load->view('cutting_output_view/form_models',$data);
    }

    function importdata(){
        $path        = $_FILES["dataimport"]["tmp_name"];
        // $intmodel    = $this->input->post('intmodel');
        
        $datenow     = date('Y-m-d H:i:s');
        $object      = PHPExcel_IOFactory::load($path);
        $dataimport  = array();

        foreach($object->getWorksheetIterator() as $worksheet){
            $highestRow    = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            
            for($row=2; $row<=$highestRow; $row++){
                $dttanggal = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $intgedung = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $intmodel  = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $intcell   = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $inttarget = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
               

                $datalog = array (
                    'dttanggal' => $dttanggal,
                    'intgedung' => $intgedung,
                    'intmodel'  => $intmodel,
                    'intcell'   => $intcell,
                    'inttarget' => $inttarget,
                    'intadd'    => $this->session->intid,
                    'dtadd'     => date('Y-m-d H:i:s'),
                    'intupdate' => $this->session->intid,
                    'dtupdate'  => date('Y-m-d H:i:s'),
                    'intstatus' => 1
                );
                array_push($dataimport, $datalog);
            }
        }
        $result = $this->model->insert_multiple($dataimport);
        //$this->model->delete_multiple();
        
        if ($result) {
            echo "<script>
                    alert('Import Data Success');
                    window.location.href='" . base_url('models_loadplan/view') . "';
                    </script>";
        } else {
            echo "<script>
                    alert('Import Data Failed');
                    window.location.href='" . base_url('models_loadplan/view') . "';
                    </script>";
        }
    }

    function exportexcelnew(){
        $intgedung = ($this->input->get('intgedung') == '') ? 0 : $this->input->get('intgedung');
        $intshift  = ($this->input->get('intshift') == '') ? 0 : $this->input->get('intshift');
        $from      = ($this->input->get('from') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('from')));
        $to        = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        //$datamesin = $this->model->getdatamesin($intgedung, $intmesin);
        $dtgedung  = $this->model->getgedungautocutting();
        $judul     = 'Semua gedung';

        $vcshift = "All Shift";
        if ($intshift == 1) {
            $vcshift = "Shift Pagi";
        } else if ($intshift == 2) {
            $vcshift = "Shift Malam";
        }

        if ($intgedung > 0) {
            $dtgedung = $this->modelapp->getdatadetailcustom('m_gedung',$intgedung,'intid');
            $judul    = $dtgedung[0]->vcnama;
        }
        
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Cutting Output " . $judul . '-' . $vcshift)
                     ->setSubject("Report Cutting Output")
                     ->setDescription("Report Cutting Output")
                     ->setKeywords("Report Cutting Output");

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

        

        $loop = 0;
        foreach ($dtgedung as $gedung) {
            if ($loop > 0) {
                $excel->createSheet();
            }

            $excel->setActiveSheetIndex($loop)->setCellValue('B1', "Report Cutting Output " . $gedung->vcnama);
            $excel->getActiveSheet()->mergeCells('B1:G1'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
            $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B2', "Report Cutting Output, on Date : ". date('d-m-Y',strtotime($from)) . " To ". date('d-m-Y',strtotime($to)). " - " . $vcshift);
            $excel->getActiveSheet()->mergeCells('B2:G2'); // Set Merge Cell
            $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(TRUE); // Set bold
            $excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(12); // Set font size 15
            $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center

            $excel->setActiveSheetIndex($loop)->setCellValue('B3', "NO");
            $excel->setActiveSheetIndex($loop)->setCellValue('C3', "Model");
            $excel->setActiveSheetIndex($loop)->setCellValue('D3', "Component");
            $excel->setActiveSheetIndex($loop)->setCellValue('E3', "Qty Cell");
            $excel->setActiveSheetIndex($loop)->setCellValue('F3', "Target Cell");
            $excel->setActiveSheetIndex($loop)->setCellValue('G3', "Target Cutting");
            $excel->setActiveSheetIndex($loop)->setCellValue('H3', "Actual Output");
            $excel->setActiveSheetIndex($loop)->setCellValue('I3', "GAP");

            $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           

            $excel->setActiveSheetIndex($loop)->getStyle('B3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('C3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('D3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('E3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('F3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('G3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('H3')->applyFromArray($style_col);
            $excel->setActiveSheetIndex($loop)->getStyle('I3')->applyFromArray($style_col);
                
            if ($intshift == 0) { 
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
                $data  = $this->model->getdatatotal($this->table,$gedung->intid,$date1,$date2);
            } elseif ($intshift > 0) {
                $date1 = date( "Y-m-d 07:00:00", strtotime( $from) );
                $date2 = date( "Y-m-d 06:59:59", strtotime( $to . " + 1 day" ) );
                $data  = $this->model->getdatatotalpershift($this->table,$gedung->intid,$date1,$date2,$intshift);
            }
                
            $gap    = 0;
            $numrow = 4;
            $no     = 0;
            foreach ($data as $dataset) {
                $targetcell = $dataset->intcell * $dataset->targetcell;
                $gap        = $dataset->intpasang - $targetcell;
                
                $excel->setActiveSheetIndex($loop)->setCellValue('B'.$numrow, ++$no);
                $excel->setActiveSheetIndex($loop)->setCellValue('C'.$numrow, $dataset->vcmodel);
                $excel->setActiveSheetIndex($loop)->setCellValue('D'.$numrow, $dataset->vckomponen);
                $excel->setActiveSheetIndex($loop)->setCellValue('E'.$numrow, $dataset->intcell);
                $excel->setActiveSheetIndex($loop)->setCellValue('F'.$numrow, $targetcell);
                $excel->setActiveSheetIndex($loop)->setCellValue('G'.$numrow, $dataset->inttarget);
                $excel->setActiveSheetIndex($loop)->setCellValue('H'.$numrow, $dataset->intpasang);
                $excel->setActiveSheetIndex($loop)->setCellValue('I'.$numrow, $gap);
        
                $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
                $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
                $numrow++;
            }
            

            // Set width kolom
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth('15');
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth('5');
            $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $excel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);

            // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
            $excel->getActiveSheet($loop)->getDefaultRowDimension()->setRowHeight(-1);

            // Set orientasi kertas jadi LANDSCAPE
            $excel->getActiveSheet($loop)->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

            // Set judul file excel nya
            $excel->getActiveSheet($loop)->setTitle($gedung->vcnama);
            $loop++;
        }

        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Cutting Output ' .$judul. '-' . $vcshift . '.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

}
