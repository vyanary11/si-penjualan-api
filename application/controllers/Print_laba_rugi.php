<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print_laba_rugi extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
    {
        parent::__construct();
        $this->load->model('M_transaksi');
    }

	public function index(){
		$expense=0;
        $income=0;
        $totalbiaya=0;
        $bulan=$this->input->get('bulan');
        $tahun=$this->input->get('tahun');
        $where="WHERE MONTH(tgl_transaksi)='$bulan' and YEAR(tgl_transaksi)='$tahun'";
        $laporan = $this->M_transaksi->laporan($where)->result();
        $bebanbiaya = $this->M_transaksi->bebanbiaya("WHERE MONTH(tgl_biaya)='$bulan' and YEAR(tgl_biaya)='$tahun'")->result();
        foreach ($laporan as $data_laporan) {
            if ($data_laporan->jenis_transaksi=="0") {
                $income=$income+$data_laporan->harga_total;
            }
            if ($data_laporan->jenis_transaksi=="1") {
                $expense=$expense+$data_laporan->harga_total;
            }
        }
        foreach ($bebanbiaya as $data_bebanbiaya) {
            $totalbiaya=$totalbiaya+$data_bebanbiaya->jumlah_biaya;
        }

        if ($bulan==1) {
        	$bulan="Januari";
        }elseif ($bulan==2) {
        	$bulan="Februari";
        }elseif ($bulan==3) {
        	$bulan="Maret";
        }elseif ($bulan==4) {
        	$bulan="April";
        }elseif ($bulan==5) {
        	$bulan="Mei";
        }elseif ($bulan==6) {
        	$bulan="Juni";
        }elseif ($bulan==7) {
        	$bulan="Juli";
        }elseif ($bulan==8) {
        	$bulan="Agustus";
        }elseif ($bulan==9) {
        	$bulan="September";
        }elseif ($bulan==10) {
        	$bulan="Oktober";
        }elseif ($bulan==11) {
        	$bulan="November";
        }elseif ($bulan==12) {
        	$bulan="Desember";
        }

        $data = array(
        	'periode'		=> $bulan." ".$tahun,
            'income'        => $income, 
            'expense'       => $expense,
            'net_income'    => $income-($expense+$totalbiaya),
            'totalbiaya'    => $expense+$totalbiaya,
            'data_biaya'    => $bebanbiaya,
        );
		$this->load->view('print_laba_rugi', $data);
	}
}
