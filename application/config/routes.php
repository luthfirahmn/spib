<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'secure';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;
$route['set_mutasi_9010'] = 'Porsi9010/H2h9010/view_mutasi';
$route['proses_set_mutasi_9010'] = 'Porsi9010/H2h9010/proses_set_mutasi';
$route['belum_lapor_bulanan_9010'] = 'Porsi9010/H2h9010/get_belum_lapor';
$route['setMutasiKtp'] = 'Porsi9010/H2h9010/setMutasiKtp';
$route['Nasabah9010'] = 'Porsi9010/Nasabah9010';
$route['Pelunasan9010'] = 'Porsi9010/Pelunasan9010';
$route['CekNik'] = 'Porsi9010/Pelunasan9010/cek_ktp';
$route['getAngsuranByKtpPeriode'] = 'Porsi9010/Pelunasan9010/getAngsuranByKtpPeriode';
$route['setPelunasanDipercepat'] = 'Porsi9010/Pelunasan9010/setPelunasanDipercepat';

//get Angsuran
$route['Angsuran9010/(:any)'] = 'Porsi9010/Angsuran9010/getAngsuranByKtp/$1';
$route['getKewajibanBankBulanan'] = 'Mts/getKewajibanBankBulanan';

//report
$route['saldoPokok'] = 'report/SaldoPokok';
$route['detailSaldoPokok'] = 'report/SaldoPokok/detail';

//SBUM
$route['cair'] = 'SBUM/Cair';
$route['formg/(:any)'] = 'SBUM/Cair/formg/$1';
$route['download_formg/(:any)'] = 'SBUM/Cair/download_formg/$1';
$route['periode_cair'] = 'SBUM/Cair/periode_cair';
$route['take_period_cair_flpp'] = 'SBUM/Service_sbum/take_period_cair_flpp';
$route['take_no_cair/(:any)'] = 'SBUM/Cair/take_no_cair/$1';
$route['hit_uji_sbum/(:any)'] = 'SBUM/Cair/hit_uji_sbum/$1';
$route['proses_uji_sbum'] = 'SBUM/Service_sbum/proses_uji_sbum';
$route['hasil_uji_sbum'] = 'SBUM/Cair/hasil_uji_sbum';
$route['get_hasil_uji_sbum'] = 'SBUM/Service_sbum/get_hasil_uji_sbum';

$route['periode_ba'] = 'SBUM/Cair/periode_ba';
$route['take_period_ba_sbum'] = 'SBUM/Service_sbum/take_period_ba_sbum';
$route['take_no_ba_sbum/(:any)'] = 'SBUM/Cair/take_no_ba_sbum/$1';
$route['take_si_by_ba/(:any)'] = 'SBUM/Cair/take_si_by_ba/$1';
$route['periode_si'] = 'SBUM/Cair/periode_si';
$route['take_period_si'] = 'SBUM/Service_sbum/take_period_si';
//transfer NIK
$route['transfer_nik'] = 'SBUM/Cair/transfer_nik';
$route['proses_transfer_nik'] = 'SBUM/Service_sbum/proses_transfer_nik';
$route['hit_transfer_nik/(:any)'] = 'SBUM/Cair/hit_transfer_nik/$1';
$route['take_transfer_nik/(:any)'] = 'SBUM/Cair/take_transfer_nik/$1';

//transfer Pengembang
$route['transfer_dev'] = 'SBUM/Cair/transfer_dev';
$route['proses_transfer_dev'] = 'SBUM/Service_sbum/proses_transfer_dev';
$route['hit_transfer_dev/(:any)'] = 'SBUM/Cair/hit_transfer_dev/$1';
$route['take_transfer_dev/(:any)'] = 'SBUM/Cair/take_transfer_dev/$1';

//refrensi
$route['refrensi'] = 'SBUM/Cair/refrensi';