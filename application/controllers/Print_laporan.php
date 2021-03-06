<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print_laporan extends CI_Controller {

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
		$hari_dari= date("d",strtotime($this->input->get('dari')));
        $bulan_dari= date("m",strtotime($this->input->get('dari')));
        $tahun_dari=date("Y",strtotime($this->input->get('dari')));
        $dari= date("Y-m-d",strtotime($tahun_dari."/".$bulan_dari."/".$hari_dari));
        $hari_sampai= date("d",strtotime($this->input->get('sampai')))+1;
        $bulan_sampai= date("m",strtotime($this->input->get('sampai')));
        $tahun_sampai=date("Y",strtotime($this->input->get('sampai')));
        $sampai= date("Y-m-d",strtotime($tahun_sampai."/".$bulan_sampai."/".$hari_sampai));
        $bulan=$this->input->get('bulan');
        $tahun=$this->input->get('tahun');


        if ($this->input->get("lap")=="harian") {
            $periode=date("d F Y", strtotime($dari))." - ".date("d F Y", strtotime($this->input->get('sampai')));
            $where="WHERE transaksi.jenis_transaksi='0' and transaksi.tgl_transaksi BETWEEN '$dari' and '$sampai'";
        }elseif ($this->input->get("lap")=="bulanan") {
            $where="WHERE transaksi.jenis_transaksi='0' and MONTH(transaksi.tgl_transaksi)='$bulan' and YEAR(transaksi.tgl_transaksi)='$tahun'";
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
            $periode=$bulan." ".$tahun;
        }elseif ($this->input->get("lap")=="tahunan") {
            $periode=$tahun;
            $where="WHERE transaksi.jenis_transaksi='0' and YEAR(transaksi.tgl_transaksi)='$tahun'";
        }

        $laporan = $this->M_transaksi->laporan($where)->result();
        $jml_data= $this->M_transaksi->laporan($where)->num_rows();

        $pendapatan=0;
        foreach ($laporan as $data_laporan) {
            $pendapatan=$pendapatan+$data_laporan->harga_total;
        }
        $data = array(
            'lap'               => $this->input->get("lap"),
            'periode'           => $periode,
            'pendapatan'        => $pendapatan,
            'jml_transaksi'     => $jml_data,
            'total_harga_semua' => $pendapatan,
            'barangTerjual'     => $this->M_transaksi->get_barang_terjual($where),
        );
		$this->load->view('print_laporan', $data);
	}
}
