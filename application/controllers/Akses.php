<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends CI_Controller {

	function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
			$this->load->model('AppModel');
			$this->load->model('AksesModel');
			$this->model    = $this->AksesModel;
			$this->modelapp = $this->AppModel;
    }

    function login($message=false){
			$errorLogin           = ($message) ? true : false ;
			$data['errorLogin']   = $errorLogin;
			$data['errorMessage'] = 'The combination of username and password is not appropriate !';
    	$this->load->view('akses_view/login',$data);
    }

    function auth(){
    	$vcusername = $this->input->post('vcusername');
    	$vcpassword = md5($this->input->post('vcpassword'));

			$data    = $this->model->auth($vcusername,$vcpassword);
			$jmldata = count($data);
			if ($jmldata > 0 && $jmldata == 1) {
				$sessiondata = array(
									'intid'       => $data[0]->intid,
									'vcnama'      => $data[0]->vcnama,
									'vckode'      => $data[0]->vckode,
									'inthakakses' => $data[0]->inthakakses,
									'appname'     => 'tpm'
								);

				$this->session->set_userdata($sessiondata);
				
				redirect(base_url('dashboard'));
			} else {
				redirect(base_url('akses/login/error'));
			}
    }

    function logout(){
    	$this->session->unset_userdata('intid','vcnama','vckode','inthakakses');
    	// $this->session->sess_destroy();
    	redirect(base_url());
    }

    function error(){
    	$data['title']      = 'error';
    	$this->template->set_layout('default')->build('akses_view/error',$data);
    }

    // ================================================================================================================
    // Opakses
    function loginop($message=false){
			$errorLogin           = ($message) ? true : false ;
			$data['errorLogin']   = $errorLogin;
			$data['errorMessage'] = 'The combination of username and password is not appropriate !';
			$data['listshift']  = $this->modelapp->getdatalistall('m_shift');
				$this->load->view('akses_view/loginop',$data);
    }

    function validasiop($vcusername='',$vcpassword='',$nik='', $intshift=0){
			$usercheck    = $this->model->auth($vcusername,md5($vcpassword));
			$uservalidasi = count($usercheck);

			$data = array(
							'status' => false,
							'message' => 'Terjadi Kesalahan'
						);
			
			if ($intshift == 0) {
				$data = array(
							'status' => false,
							'message' => 'Masukkan shift !'
						);
			}
			
			elseif ($uservalidasi == 0) {
				$data = array(
							'status' => false,
							'message' => 'Kombinasi username dan password tidak sesuai'
						);
			} else {
				$karyawancheck    = $this->model->karyawanvalidasi($usercheck[0]->intmesin,$nik);
				$karyawanvalidasi = count($karyawancheck);
				if ($karyawanvalidasi == 0) {
					$data = array(
							'status' => false,
							'message' => 'NIK Tidak terdaftar'
						);
				} else {
					// $shift1start   = strtotime('07:00:00');
					// $shift1finish  = strtotime('19:00:00');
					
					// $shift2start1  = strtotime('19:00:01');
					// $shift2start2  = strtotime('00:00:00');
					// $shift2finish1 = strtotime('23:59:59');
					// $shift2finish2 = strtotime('07:00:00');
					// $timenow       = time(date("H:i:s"));
					// $intshift      = 0;
				//       if ($timenow >= $shift1start && $timenow <= $shift1finish) {
				//           $intshift = 1;
				//       } elseif (($timenow >= $shift2start1 && $timenow <= $shift2finish1) || ($timenow >= $shift2start2 && $timenow <= $shift2finish2)) {
				//           $intshift = 2;
				//       }

					$datalog = array(
								'intuser'     => $usercheck[0]->intid,
								'intkaryawan' => $karyawancheck[0]->intkaryawan,
								'vcip'        => '',
								'intshift'    => $intshift,
								'intlogin'    => 1,
								'dtlogin'     => date('Y-m-d H:i:s')
							);

					$this->modelapp->insertdata('a_log_login',$datalog);

					$sessiondata = array(
										'intidop'     => $usercheck[0]->intid,
										'intmesinop'  => $karyawancheck[0]->intmesin,
										'vckodemesin' => $karyawancheck[0]->vckodemesin,
										'intgedungop' => $karyawancheck[0]->intgedung,
										'intcellop'   => $karyawancheck[0]->intcell,
										'intkaryawan' => $karyawancheck[0]->intkaryawan,
										'vcnik'       => $karyawancheck[0]->vcnik,
										'vckaryawan'  => $karyawancheck[0]->vckaryawan,
										'intgedung'   => $karyawancheck[0]->intgedung,
										'intshift'    => $intshift,
										'appname'     => 'tpmoperator'
									);

					// $this->session->set_userdata($sessiondata);

					$data = array(
								'intshift'  => $intshift,
								'dttanggal' => date('Y-m-d'),
								'status'    => true,
								'message'   => 'Berhasil Login'
							);
					$data['session'] = $sessiondata;
				}
			}

			echo json_encode($data);
    }

    function authop(){
		$vcusername = $this->input->post('vcusername');
		$vcpassword = md5($this->input->post('vcpassword'));
		$vcnik      = $this->input->post('vcnik');

		$data    = $this->model->auth($vcusername,$vcpassword);
		$jmldata = count($data);
		if ($jmldata > 0 && $jmldata == 1) {
			$sessiondata = array(
								'intid'       => $data[0]->intidop,
								'vcnama'      => $data[0]->vcnamaop,
								'vckode'      => $data[0]->vckodeop,
								'inthakakses' => $data[0]->inthakaksesop,
								'appname'     => 'tpmoperator'
							);

			$this->session->set_userdata($sessiondata);
			
			redirect(base_url('dashboard'));
		} else {
			redirect(base_url('akses/login/error'));
			// $data['errorLogin'] = 'Kombinasi username dan password tidak sesuai !';
			// $this->load->view('akses_view/login',$data);
		}
    }

    function logoutop(){
			$intidop     = $this->input->post('intidop');
			$intkaryawan = $this->input->post('intkaryawan');

			$shift1start   = strtotime('07:00:00');
			$shift1finish  = strtotime('20:00:00');
			
			$shift2start1  = strtotime('19:00:01');
			$shift2start2  = strtotime('00:00:00');
			$shift2finish1 = strtotime('23:59:59');
			$shift2finish2 = strtotime('07:15:00');
			$timenow       = time(date("H:i:s"));
			$intshift      = 0;
					if ($timenow >= $shift1start && $timenow <= $shift1finish) {
							$intshift = 1;
					} elseif (($timenow >= $shift2start1 && $timenow <= $shift2finish1) || ($timenow >= $shift2start2 && $timenow <= $shift2finish2)) {
							$intshift = 2;
					}
				$datalog = array(
						'intuser'     => $intidop,
						'intkaryawan' => $intkaryawan,
						'vcip'        => '',
						'intshift'    => $intshift,
						'intlogin'    => 2,
						'dtlogin'     => date('Y-m-d H:i:s')
					);

			$this->modelapp->insertdata('a_log_login',$datalog);
			
					$dtmasuk = $this->input->post('dtmasuk');
					$dtpulang = $this->input->post('dtpulang');
					$datalog2 = array(
						'intuser'     => $intidop,
						'intkaryawan' => $intkaryawan,
						'intshift'    => $intshift,
						'intlogin'    => 2,
						'dtlogin'     => date('Y-m-d H:i:s'),
						'dtmasuk'     => $dtmasuk,
						'dtpulang'    => $dtpulang
					);

			$this->modelapp->insertdata('a_log_history',$datalog2);

    	$this->session->unset_userdata('intmesinop','vckodemesin','intgedungop','intkaryawan','vcnik','vckaryawan');
    	// $this->session->sess_destroy();
    	redirect(base_url('akses/loginop'));
    }


    // ===============================================================================================================
    // OEE Monitoring Akses

    function loginoee($message=false){
    	$errorLogin           = ($message) ? true : false ;
			$data['errorLogin']   = $errorLogin;
			$data['errorMessage'] = 'The combination of username and password is not appropriate !';
    	$this->load->view('akses_view/loginoee',$data);
    }

    function authoee(){
    	$vcusername = $this->input->post('vcusername');
    	$vcpassword = md5($this->input->post('vcpassword'));

		$data    = $this->model->authoee($vcusername,$vcpassword);
		$jmldata = count($data);
		if ($jmldata > 0 && $jmldata == 1) {
			$sessiondata = array(
								'intidoee'       => $data[0]->intid,
								'vcnamaoee'      => $data[0]->vcnama,
								'vckodeoee'      => $data[0]->vckode,
								'inthakaksesoee' => $data[0]->inthakakses,
								'appname'        => 'oee_monitoring'
							);

			$this->session->set_userdata($sessiondata);
			if ($data[0]->vckodehakakses == 'OEEBD') {
				redirect(base_url('oee_monitoring/building_/'.$data[0]->intgedung));
			} else {
				redirect(base_url('oee_monitoring'));
			}
		} else {
			redirect(base_url('akses/loginoee/error'));
		}
    }

    function logoutoee(){
    	$this->session->unset_userdata('intidoee','vcnamaoee','vckodeoee','inthakaksesoee');
    	// $this->session->sess_destroy();
    	redirect(base_url('oee_monitoring'));
    }

    function erroroee(){
    	$data['title']      = 'error';
    	$this->template->set_layout('default')->build('akses_view/error',$data);
		}
		
		// login AM

	function loginam($message=false){
		$errorLogin           = ($message) ? true : false ;
		$data['errorLogin']   = $errorLogin;
		$data['errorMessage'] = 'The combination of username and password is not appropriate !';
	$this->load->view('akses_view/loginam',$data);
    }

    function autham(){
    	$vcusername = $this->input->post('vcusername');
    	$vcpassword = md5($this->input->post('vcpassword'));

			$data    = $this->model->auth($vcusername,$vcpassword);
			$jmldata = count($data);
			if ($jmldata > 0 && $jmldata == 1) {
				$sessiondata = array(
									'intid'       => $data[0]->intid,
									'vcnama'      => $data[0]->vcnama,
									'vckode'      => $data[0]->vckode,
									'inthakakses' => $data[0]->inthakakses,
									'appname'     => 'tpm'
								);

				$this->session->set_userdata($sessiondata);
				
				redirect(BASE_URL_PATH_HTTPS.'auditam');
			} else {
				redirect(base_url('akses/login/error'));
			}
    }

    function logoutam(){
    	$this->session->unset_userdata('intid','vcnama','vckode','inthakakses');
    	// $this->session->sess_destroy();
    	redirect(base_url('auditam'));
    }

    function loginsm($message=false){
		$errorLogin           = ($message) ? true : false ;
		$data['errorLogin']   = $errorLogin;
		$data['errorMessage'] = 'The combination of username and password is not appropriate !';
	$this->load->view('akses_view/loginsm',$data);
    }

    function authsm(){
    	$vcusername = $this->input->post('vcusername');
    	$vcpassword = md5($this->input->post('vcpassword'));

			$data    = $this->model->auth($vcusername,$vcpassword);
			$jmldata = count($data);
			if ($jmldata > 0 && $jmldata == 1) {
				$sessiondata = array(
									'intid'       => $data[0]->intid,
									'vcnama'      => $data[0]->vcnama,
									'vckode'      => $data[0]->vckode,
									'inthakakses' => $data[0]->inthakakses,
									'appname'     => 'tpm'
								);

				$this->session->set_userdata($sessiondata);
				
				redirect(BASE_URL_PATH_HTTPS.'scan_mesin');
			} else {
				redirect(base_url('akses/login/error'));
			}
    }

    function logoutsm(){
    	$this->session->unset_userdata('intid','vcnama','vckode','inthakakses');
    	// $this->session->sess_destroy();
    	redirect(base_url('scan_mesin'));
    }
}
