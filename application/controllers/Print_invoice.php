<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print_invoice extends CI_Controller {

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
        $this->load->model('M_barang');
    }

	public function index()
	{
		$row = $this->M_transaksi->get_by_kd($this->input->get('no_invoice'));
        $detailinvoice = $this->M_transaksi->get_detail_transaksi($this->input->get('no_invoice'));
        $data = array(
            "kd_transaksi"      => $row->kd_transaksi,
            "nama_user"         => $row->nama_depan,
            "jml_item"          => $row->jml_item,
            "harga_total"       => str_replace(",",".", number_format($row->harga_total)),
            "tgl_transaksi"     => date("d F Y H:m", strtotime($row->tgl_transaksi)),
            "catatan"           => $row->catatan,
            "status"            => $row->status,
            "jenis_transaksi"   => $row->jenis_transaksi,
            "detailinvoice"     => $detailinvoice
        );
		$this->load->view('print_invoice',$data);
	}
}
