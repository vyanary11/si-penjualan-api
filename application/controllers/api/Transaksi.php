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
        $this->load->model('M_barang');
    }

    function index_post(){
        $api=$this->post('api');
        if($api=="tambah") {
            $kd_user         = $this->post('kd_user');
            $jml_item        = $this->post('jml_item');
            $harga_total     = $this->post('harga_total');
            $tgl_transaksi   = date("d F Y H:m");
            $status          = $this->post('status');
            $catatan         = $this->post('catatan');
            $jenis_transaksi = $this->post('jenis_transaksi');     

            $data = array(  
                "kd_transaksi"      => "",
                "kd_user"           => $kd_user,
                "jml_item"          => $jml_item,
                "harga_total"       => $harga_total,
                "tgl_transaksi"     => $tgl_transaksi,
                "status"            => $status,
                "catatan"           => $catatan,    
                "jenis_transaksi"   => $jenis_transaksi
            );

            $result = $this->M_transaksi->insert($data);
            if($result>=0){
                $data_terakhir=$this->M_transaksi->get_last();
                $kd_barang=explode(",", $this->post('kd_barang_keranjang'));
                $qty=explode(",",$this->post('qty_keranjang'));
                for ($i=0; $i < count($kd_barang); $i++) {
                    $data_barang=$this->M_barang->get_by_kd($kd_barang[$i]); 
                    $data = array(
                        'kd_transaksi'  => $data_terakhir->kd_transaksi,
                        'kd_barang'     => $kd_barang[$i], 
                        'harga_jual'    => $data_barang->harga_jual,
                        'harga_beli'    => $data_barang->harga_beli,
                        'qty'           => $qty[$i],
                    );
                    if ($data_terakhir->jenis_transaksi==0) {
                        $data_stok = array('stok' => $data_barang->stok-$qty[$i], );
                    }else{
                        $data_stok = array('stok' => $data_barang->stok+$qty[$i], );
                    }

                    $this->M_barang->update($kd_barang[$i],$data_stok);
                    $this->M_transaksi->insert_to_detail($data);
                }
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil disimpan!', 'kd_transaksi' => $data_terakhir->kd_transaksi], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }else if($api=="bayar") {
            $data = array(  
                "status"   => 0,
            );
            
            $result = $this->M_transaksi->update($this->post('kd_transaksi'),$data);
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Status Transaksi Berhasil diupdate!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Status Transaksi Gagal diupdate'], REST_Controller::HTTP_OK);
            }
        }else if($api=="edit") {
            $data = array(  
                "catatan"   => $this->post('catatan'),
            );
            
            $result = $this->M_transaksi->update($this->post('kd_transaksi'), $data);
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil diedit!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diedit!'], REST_Controller::HTTP_OK);
            }
        }
    }
    
    function index_get(){
        if ($this->get('api')=="transaksidetail") {
            $row = $this->M_transaksi->get_by_kd($this->get('kd_transaksi'));
            if ($row) {
                $data = array(
                    "kd_transaksi"      => $row->kd_transaksi,
                    "nama_user"         => $row->nama_depan,
                    "jml_item"          => $row->jml_item,
                    "harga_total"       => $row->harga_total,
                    "tgl_transaksi"     => date("d F Y H:m", strtotime($row->tgl_transaksi)),
                    "catatan"           => $row->catatan,
                    "status"            => $row->status,
                    "jenis_transaksi"   => $row->jenis_transaksi
                );
                $this->response($data, REST_Controller::HTTP_OK);   
            }
        }elseif ($this->get('api')=="detailinvoice") {
            $detailtransaksi = $this->M_transaksi->get_detail_transaksi($this->get('kd_transaksi'));
            $data = array(
                'data'     => $detailtransaksi,
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="penjualan") {
            $transaksi = $this->M_transaksi->get_all("0","0");
            $jml_transaksi= $this->M_transaksi->total_rows_perjenis("0","0");
            $data = array(
                'data'     => $transaksi,
                'jml_data' => $jml_transaksi
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="pembelian") {
            $transaksi = $this->M_transaksi->get_all("1","0");
            $jml_transaksi= $this->M_transaksi->total_rows_perjenis("1","0");
            $data = array(
                'data'     => $transaksi,
                'jml_data' => $jml_transaksi
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="utang") {
            $transaksi = $this->M_transaksi->get_all("1","1");
            $jml_transaksi= $this->M_transaksi->total_rows_perjenis("1","1");
            $data = array(
                'data'     => $transaksi,
                'jml_data' => $jml_transaksi
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="piutang") {
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