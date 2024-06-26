<?php
class MY_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->session_cek();
	}

	function input_error()
	{
		$json['status'] = 0;
		$json['pesan'] 	= "<div class='alert alert-warning error_validasi'>" . validation_errors() . "</div>";
		echo json_encode($json);
	}

	function query_error($pesan = "Terjadi kesalahan, coba lagi !")
	{
		$json['status'] = 2;
		$json['pesan'] 	= "<div class='alert alert-danger error_validasi'>" . $pesan . "</div>";
		echo json_encode($json);
	}

	function session_cek()
	{
		$u = $this->session->userdata('ap_id_user');
		$p = $this->session->userdata('ap_password');
		$x = $this->session->userdata('ap_level');

		$controller = $this->router->fetch_class();
		$method		= $this->router->fetch_method();

		if ($controller == 'secure') {
			if ($method == 'index') {
				if (!empty($u) && !empty($p)) {
					$URL_home = 'Dashboard';
					if ($x == 'inventory') {
						$URL_home = 'barang';
					}
					if ($x == 'keuangan') {
						$URL_home = 'penjualan/history';
					}

					redirect($URL_home, 'refresh');
				}
			}
		} else {
			if (empty($u) or empty($p)) {
				redirect('secure', 'refresh');
			} else {
				$this->load->model('m_user');
				$cek = $this->m_user->is_valid($u, $p);
				if ($cek->num_rows() < 1) {
					//redirect('secure/logout', 'refresh');
				}
			}
		}
	}

	function clean_tag_input($str)
	{
		$t = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
		$t = htmlentities($t, ENT_QUOTES, "UTF-8");
		$t = trim($t);
		return $t;
	}

	protected function switchDatabase($hostname, $username, $password, $database, $port)
	{
		$params = array(
			'hostname' => $hostname . ':' . $port,
			'username' => $username,
			'password' => $password,
			'database' => $database,
			// Other database configuration parameters
			'dbdriver' => 'mysqli',
			'dbprefix' => '',
			'pconnect' => FALSE,
			'db_debug' => (ENVIRONMENT !== 'production'),
			'cache_on' => FALSE,
			'cachedir' => '',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'encrypt'  => FALSE,
			'compress' => FALSE,
			'stricton' => FALSE,
			'failover' => array(),
			'save_queries' => TRUE
		);

		return $params;
	}
}
