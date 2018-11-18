<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic transaksi interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Transaksi extends REST_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_transaksi');
    }

    function index_post(){
        $api=$this->post('api');
        if($api=="tambah") {
            $nama_transaksi= $this->post('nama_transaksi');

            $data = array(  
                "kd_transaksi"      => $kd_transaksi,
                "harga_jual"        => $harga_jual,
                "harga_beli"        => $harga_beli,
                "stok"              => $stok,
                "tgl_transaksi"     => $tgl_transaksi,
                "status"            => 0,
                "jenis_transaksi"   => $jenis_transaksi
            );

            $result = $this->M_transaksi->insert($data);
            if($result>=0){
                $this->response(['kode' => 1,'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }else if($api=="bayar") {
            $nama_transaksi= $this->post('nama_transaksi');

            $data = array(  
                "kd_transaksi"     => "",
                "nama_transaksi"   => $nama_transaksi,
            );
            
            $result = $this->M_transaksi->insert($this->post('kd_transaksi'), $data);
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }else if($api=="edit") {
            $nama_transaksi= $this->post('nama_transaksi');

            $data = array(  
                "kd_transaksi"     => "",
                "nama_transaksi"   => $nama_transaksi,
            );
            
            $result = $this->M_transaksi->update($this->post('kd_transaksi'), $data);
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }
    }
    
    function index_get(){
        if ($this->get('api')=="transaksidetail") {
            $row = $this->M_transaksi->get_by_kd($this->get('kd_transaksi'));
            if ($row) {
                $data = array(
                    "kd_transaksi"      => $row->kd_transaksi,
                    "harga_jual"        => $row->harga_jual,
                    "harga_beli"        => $row->harga_beli,
                    "stok"              => $row->stok,
                    "tgl_transaksi"     => date("d M Y", strtotime($row->tgl_transaksi)),
                    "status"            => $row->status,
                    "jenis_transaksi"   => $row->jenis_transaksi
                );
                $this->response($data, REST_Controller::HTTP_OK);   
            }
        }elseif ($this->get('api')=="transaksipenjualan") {
            $transaksi = $this->M_transaksi->get_all("0","0");
            $jml_transaksi= $this->M_transaksi->total_rows_perjenis("0","0");
            $data = array(
                'data'     => $transaksi,
                'jml_data' => $jml_transaksi
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="transaksipembelian") {
            $transaksi = $this->M_transaksi->get_all("1","0");
            $jml_transaksi= $this->M_transaksi->total_rows_perjenis("1","0");
            $data = array(
                'data'     => $transaksi,
                'jml_data' => $jml_transaksi
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="transaksiutang") {
            $transaksi = $this->M_transaksi->get_all("1","0");
            $jml_transaksi= $this->M_transaksi->total_rows_perjenis("1","0");
            $data = array(
                'data'     => $transaksi,
                'jml_data' => $jml_transaksi
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="transaksipiutang") {
            $transaksi = $this->M_transaksi->get_all("0","1");
            $jml_transaksi= $this->M_transaksi->total_rows_perjenis("0","1");
            $data = array(
                'data'     => $transaksi,
                'jml_data' => $jml_transaksi
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="delete") {
            $result = $this->M_transaksi->delete($this->get('kd_transaksi'));
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil dihapus!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal dihapus!'], REST_Controller::HTTP_OK);
            }
        }
    }
}