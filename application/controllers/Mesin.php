<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mesin extends MY_Controller {

	function __construct(){
        parent::__construct();
        $this->load->model('MesinModel');
        $this->model = $this->MesinModel;
    }

    function index(){
    	redirect(base_url($this->controller . '/view'));
    }

    function view($halaman=1){
        $keyword   = $this->input->get('key');
        $intgedung = $this->input->get('int1');
        $intcell   = $this->input->get('int2');

        $jmldata            = $this->model->getjmldata($this->table, $keyword, $intgedung, $intcell);
        $offset             = ($halaman - 1) * $this->limit;
        $jmlpage            = ceil($jmldata[0]->jmldata / $this->limit);

        $data['title']      = $this->title;
        $data['controller'] = $this->controller;
        $data['keyword']    = $keyword;
        $data['int1']       = $intgedung;
        $data['int2']       = $intcell;
        $data['halaman']    = $halaman;
        $data['jmlpage']    = $jmlpage;
        $data['firstnum']   = $offset;
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listcell']   = $this->modelapp->getdatalistall('m_cell',$intgedung,'intgedung');
        $data['dataP']      = $this->model->getdatalimit($this->table,$offset,$this->limit,$keyword, $intgedung, $intcell);

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
                    'intid'        => 0,
                    'vckode'       => '',
                    'vcnama'       => '',
                    'intbrand'     => 0,
                    'vcjenis'      => '',
                    'vcserial'     => '',
                    'vcpower'      => '',
                    'intgedung'    => 0,
                    'intcell'      => 0,
                    'intdeparture' => 0,
                    'intgroup'     => 0,
                    'vclocation'   => '',
                    'vcgambar'       => '',
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 0
                );

        $data['title']      = $this->title;
        $data['action']     = 'Add';
        $data['controller'] = $this->controller;
        $data['listbrand']  = $this->modelapp->getdatalist('m_brand');
        $data['listarea']   = $this->modelapp->getdatalist('m_area');
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listgroup']  = $this->modelapp->getdatalist('m_mesin_group');
        $data['listcell']   = [];

        $this->template->set_layout('default')->build($this->view . '/form',$data);
    }

    function edit($intid){
        $resultData = $this->modelapp->getdatadetail($this->table,$intid);
        $data = array(
                    'intid'        => $resultData[0]->intid,
                    'vckode'       => $resultData[0]->vckode,
                    'vcnama'       => $resultData[0]->vcnama,
                    'intbrand'     => $resultData[0]->intbrand,
                    'vcjenis'      => $resultData[0]->vcjenis,
                    'vcserial'     => $resultData[0]->vcserial,
                    'vcpower'      => $resultData[0]->vcpower,
                    'intgedung'    => $resultData[0]->intgedung,
                    'intcell'      => $resultData[0]->intcell,
                    'intdeparture' => $resultData[0]->intdeparture,
                    'intgroup'     => $resultData[0]->intgroup,
                    'vclocation'   => $resultData[0]->vclocation,
                    'vcgambar'       => $resultData[0]->vcgambar,
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s')
                );

        $data['title']      = $this->title;
        $data['action']     = 'Edit';
        $data['controller'] = $this->controller;
        $data['listbrand']  = $this->modelapp->getdatalist('m_brand');
        $data['listarea']   = $this->modelapp->getdatalist('m_area');
        $data['listgedung'] = $this->modelapp->getdatalist('m_gedung');
        $data['listgroup']  = $this->modelapp->getdatalist('m_mesin_group');
        $data['listcell']   = $this->modelapp->getdatalistall('m_cell', $resultData[0]->intgedung,'intgedung');

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
                    $array[]['error'] = 'Kolom ' . $error . ' tidak boleh kosong !';
                }
            }
        }
        echo json_encode($array);
    }

    function aksi($tipe,$intid,$status=0){
        if ($tipe == 'Add') {
            $vckode       = $this->input->post('vckode');
            $vcnama       = $this->input->post('vcnama');
            $intbrand     = $this->input->post('intbrand');
            $vcjenis      = $this->input->post('vcjenis');
            $vcserial     = $this->input->post('vcserial');
            $vcpower      = $this->input->post('vcpower');
            $intgedung    = $this->input->post('intgedung');
            $intcell      = $this->input->post('intcell');
            $intdeparture = $this->input->post('intdeparture');
            $intgroup     = $this->input->post('intgroup');
            $vclocation   = $this->input->post('vclocation');
            $vcgambar       = $this->model->_uploadImage($vcjenis);
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
                    'vcgambar'       => $vcgambar,
                    'intadd'       => $this->session->intid,
                    'dtadd'        => date('Y-m-d H:i:s'),
                    'intupdate'    => $this->session->intid,
                    'dtupdate'     => date('Y-m-d H:i:s'),
                    'intstatus'    => 1
                );

            $result = $this->modelapp->insertdata($this->table,$data);

            if ($result) {
                redirect(base_url($this->controller . '/view'));
            }
        } elseif ($tipe == 'Edit') {
            $vckode       = $this->input->post('vckode');
            $vcnama       = $this->input->post('vcnama');
            $intbrand     = $this->input->post('intbrand');
            $vcjenis      = $this->input->post('vcjenis');
            $vcserial     = $this->input->post('vcserial');
            $vcpower      = $this->input->post('vcpower');
            $intgedung    = $this->input->post('intgedung');
            $intcell      = $this->input->post('intcell');
            $intdeparture = $this->input->post('intdeparture');
            $intgroup     = $this->input->post('intgroup');
            $vclocation   = $this->input->post('vclocation');

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

    function exportexcel($intjenis, $intgedung=0, $intcell=0, $keyword=''){
        if ($intjenis == 1) {
            $this->exportexcelall($keyword,$intgedung, $intcell);
        } elseif ($intjenis == 2) {
            $this->exportexcellabel($keyword,$intgedung, $intcell);
        }
        // $intsparepart = $this->input->get('intsparepart');
        // $from         = date('Y-m-d',strtotime($this->input->get('from')));
        // $to           = ($this->input->get('to') == '') ? date('Y-m-d') : date('Y-m-d',strtotime($this->input->get('to')));
        // $data         = $this->model->getdata($this->table,$intsparepart,$from,$to);
        // $title = ($this->input->get('from') == '') ? '' : ", on Date : ". date('d m Y',strtotime($from)) . " To ". date('d m Y',strtotime($to));
    }

    function exportexcelall($keyword='',$intgedung=0,$intcell=0){
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Machine Data ")
                     ->setSubject("Machine Data")
                     ->setDescription("Machine Data")
                     ->setKeywords("Machine Data");

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

        $excel->setActiveSheetIndex(0)->setCellValue('B1', "Machine Data ");
        $excel->getActiveSheet()->mergeCells('B1:K1'); // Set Merge Cell
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(TRUE); // Set bold
        $excel->getActiveSheet()->getStyle('B1')->getFont()->setSize(15); // Set font size 15
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center Set text center

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "CODE");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NAME");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "BRAND");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "SPESIFICATION");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "SERIAL NUMBER");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "POWER");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "PANEL LOCATION");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "LOCATION");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "DEPARTURE");


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

        $data = $this->model->getdata($this->table,$keyword, $intgedung, $intcell);
        $numrow = 4;
        $no = 0;
        foreach ($data as $dataset) {

            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ++$no);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vckode);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $dataset->vcnama);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $dataset->vcbrand);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $dataset->vcjenis);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $dataset->vcserial);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $dataset->vcpower);
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $dataset->vcpanel_location);
            $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $dataset->vclocation);
            $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $dataset->intdeparture);
           

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

            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);       
        $excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Machine Data All");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Machine All.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $write->save('php://output');
    }

    function exportexcellabel($keyword='',$intgedung=0,$intcell=0){
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
        $excel = new PHPExcel();

        $excel->getProperties()->setCreator('')
                     ->setLastModifiedBy('')
                     ->setTitle("Machine Data ")
                     ->setSubject("Machine Data")
                     ->setDescription("Machine Data")
                     ->setKeywords("Machine Data");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "ID");
        $excel->setActiveSheetIndex(0)->setCellValue('B1', "BRAND");
        $excel->setActiveSheetIndex(0)->setCellValue('C1', "TYPE");


        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex()->getStyle('A1')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('B1')->applyFromArray($style_col);
        $excel->setActiveSheetIndex()->getStyle('C1')->applyFromArray($style_col);

        $data = $this->model->getdata($this->table,$keyword, $intgedung, $intcell);
        $numrow = 2;
        $no = 0;
        foreach ($data as $dataset) {

           $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $dataset->vckode);
           $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $dataset->vcbrand);
           $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $dataset->vcjenis);

            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);

            $numrow++;
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Machine Data All");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment; filename="Report Machine for Label.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $write->save('php://output');
    }

    function print($intgedung=0, $intcell=0, $keyword=''){
        $data['dataMain'] = $this->model->getdata($this->table,$keyword, $intgedung, $intcell);
        $this->load->view($this->view . '/print',$data);
    }

    function scanqr(){
        $this->load->view($this->view . '/scanqr');
        redirect(SCANNER);
    }

    function detail_($vckode){
        $data['title']  = $this->title;
        $data['controller']  = $this->controller;
        $data['dataMain']    = $this->model->getdatadetail2($this->table,$vckode);
        $data['dataHistory'] = $this->modelapp->getdatahistory2($this->tablehistory,$data['dataMain'][0]->intid);
        $this->template->set_layout('default')->build($this->view . '/detail_scan',$data);
    }
}
