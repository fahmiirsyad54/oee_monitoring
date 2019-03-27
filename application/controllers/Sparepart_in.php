<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sparepart_in extends MY_Controller { 

    function __construct(){
        parent::__construct();
        $this->load->model('Sparepart_inModel');
        $this->model = $this->Sparepart_inModel;
    }

    function index(){
        redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $intsparepart = (empty($this->input->get('intsparepart'))) ? 0 : $this->input->get('intsparepart');
        $keyword      = $this->input->get('key');
        $from         = date('Y-m-d',strtotime($this->input->get('from')));
        $to           = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));

        $jmldata            = $this->model->getjmldata($this->table,$intsparepart,$from,$to);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']        = $this->title;
        $data['controller']   = $this->controller;
        $data['intsparepart'] = $intsparepart;
        $data['from']         = ($this->input->get('from')) ? $from : '';
        $data['to']           = ($this->input->get('to')) ? $to : '';
        $data['from_input']   = ($this->input->get('from')) ? date('m/d/Y', strtotime($from)) : '';
        $data['to_input']     = ($this->input->get('to')) ? date('m/d/Y', strtotime($to)) : '';
        $data['halaman']      = $halaman;
        $data['jmlpage']      = $jmlpage;
        $data['firstnum']     = $offset;
        $data['dataP']        = $this->model->getdatalimit($this->table,$offset,$this->limit,$intsparepart,$from,$to);

        $this->template->set_layout('default')->build($this->view . '/index',$data);
    }

    function detail($intid){
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail($this->table,$intid);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$intid);
        $this->load->view($this->view . '/detail',$data);
    }

    function add(){
        $kodeunik   = $this->Sparepart_inModel->buat_kode();
        $data = array(
                    'intid'        => 0,
                    'vckode'       => $kodeunik,
                    'intsparepart' => 0,
                    'intinout'     => 1,
                    'vcnomor_po'   => '',
                    'dtorder'      => date('m/d/Y'),
                    'intsuplier'   => 0,
                    'decqtymasuk'  => 0.00,
                    'decharga'     => 0.00,
                    'dectotal'     => 0.00,
                    'vcketerangan' => '',
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 0
                );

        $data['title']         = $this->title;
        $data['action']        = 'Add';
        $data['controller']    = $this->controller;
        $data['listsparepart'] = $this->modelapp->getdatalistall('m_sparepart');
        $data['listsuplier']   = $this->modelapp->getdatalist('m_suplier');

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'        => $resultData[0]->intid,
                    'vckode'       => $resultData[0]->vckode,
                    'intsparepart' => $resultData[0]->intsparepart,
                    'vcnomor_po'   => $resultData[0]->vcnomor_po,
                    'dtorder'      => $resultData[0]->dtorder,
                    'intsuplier'   => $resultData[0]->intsuplier,
                    'decqtymasuk'  => $resultData[0]->decqtymasuk,
                    'decharga'     => $resultData[0]->decharga,
                    'dectotal'     => $resultData[0]->dectotal,
                    'vcketerangan' => $resultData[0]->vcketerangan,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );

        $data['title']         = $this->title;
        $data['action']        = 'Edit';
        $data['controller']    = $this->controller;
        $data['listsparepart'] = $this->modelapp->getdatalistall('m_sparepart');
        $data['listsuplier']   = $this->modelapp->getdatalist('m_suplier');
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
                    $array[]['error'] = $error . ' ready !';
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
        if ($tipe      == 'Add') {
            $vckode       = $this->input->post('vckode');
            $intsparepart = $this->input->post('intsparepart');
            $intinout     = $this->input->post('intinout');
            $vcnomor_po   = $this->input->post('vcnomor_po');
            $dtorder      = $this->input->post('dtorder');
            $intsuplier   = $this->input->post('intsuplier');
            $decqtymasuk  = $this->input->post('decqtymasuk');
            $decharga     = $this->input->post('decharga');
            $dectotal     = $this->input->post('dectotal');
            $vcketerangan = $this->input->post('vcketerangan');
            $data         = array(
                    'vckode'       => $vckode,
                    'intsparepart' => $intsparepart,
                    'intinout'     => $intinout,
                    'vcnomor_po'   => $vcnomor_po,
                    'dtorder'      => date('Y-m-d',strtotime($dtorder)),
                    'intsuplier'   => $intsuplier,
                    'decqtymasuk'  => $decqtymasuk,
                    'decharga'     => $decharga,
                    'dectotal'     => $dectotal,
                    'vcketerangan' => $vcketerangan,
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 2
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
            
        } elseif ($tipe == 'Edit') {
            $vckode       = $this->input->post('vckode');
            $intsparepart = $this->input->post('intsparepart');
            $intinout     = $this->input->post('intinout');
            $vcnomor_po   = $this->input->post('vcnomor_po');
            $dtorder      = $this->input->post('dtorder');
            $intsuplier   = $this->input->post('intsuplier');
            $decqtymasuk  = $this->input->post('decqtymasuk');
            $decharga     = $this->input->post('decharga');
            $dectotal     = $this->input->post('dectotal');
            $vcketerangan = $this->input->post('vcketerangan');
            $data    = array(
                    'vckode'       => $vckode,
                    'intsparepart' => $intsparepart,
                    'intinout'     => $intinout,
                    'vcnomor_po'   => $vcnomor_po,
                    'dtorder'      => $dtorder,
                    'intsuplier'   => $intsuplier,
                    'decqtymasuk'  => $decqtymasuk,
                    'decharga'     => $decharga,
                    'dectotal'     => $dectotal,
                    'vcketerangan' => $vcketerangan,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
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
                $intstatus = 2;
            } elseif ($status == 2) {
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
        } elseif ($tipe == 'approvepo') {
            $intstatus = 1;
            $dtinout = $this->input->post('dtinout');
            $data = array(
                'intstatus' => $intstatus,
                'dtinout'   => date('Y-m-d',strtotime($dtinout)),
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
        $intsparepart = $this->input->get('intsparepart');
        $from         = date('Y-m-d',strtotime($this->input->get('from')));
        $to           = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        $data         = $this->model->getdata($this->table,$intsparepart,$from,$to);
        $title = ($this->input->get('from') == '') ? '' : ", on Date : ". date('d m Y',strtotime($from)) . " To ". date('d m Y',strtotime($to));
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Report Spare Part In ")
                     ->setSubject("Report Spare Part In")
                     ->setDescription("Report Spare Part In")
                     ->setKeywords("Report Spare Part In");

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

        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Report Spare Part In ");
        $excel->getActiveSheet()->mergeCells('B1:M1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B2', "Report Spare Part In");
        $excel->getActiveSheet()->mergeCells('B2:M2'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(12); // Set font size 15
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); // Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Code");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Sparepart");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Spesificatipn");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Part Number");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "Unit");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "No. PO");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Suplier");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "Quantity");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "Price");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "Total");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "Date Order");


        $excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('B3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('C3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('D3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('E3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('F3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('G3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('H3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('I3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('J3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('K3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('L3')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('M3')->applyFromArray($style_col);

        $numrow = 4;
        $no = 0;
        foreach ($data as $dataset) {

           $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ++$no);
           $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vckode);
           $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $dataset->vcsparepart);
           $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->vcspesifikasi);
           $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->vcpart);
           $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $dataset->vcunit);
           $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $dataset->vcnomor_po);
           $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $dataset->vcsuplier);
           $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $dataset->decqtymasuk);
           $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $dataset->decharga);
           $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $dataset->dectotal);
           $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, date('d M Y', strtotime($dataset->dtorder)));

            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);

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
        $excel->getActiveSheet(0)->setTitle("Report Spare Part In");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Spare Part In.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $write->save('php://output');
    }

}
