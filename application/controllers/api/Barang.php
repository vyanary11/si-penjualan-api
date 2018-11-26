<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic barang interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Barang extends REST_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_barang');
    }

    function index_post(){
        $api=$this->post('api');
        if($api=="tambah") {
            $nama_barang= $this->post('nama_barang');
            $kd_kategori= $this->post('kd_kategori');
            $harga_jual= $this->post('harga_jual');
            $harga_beli=$this->post('harga_beli');
            $stok=$this->post('stok');

            $path='assets/images/upload/barang/barang_'.$nama_barang.'.jpeg';
            if ($this->post('gambar_barang')=="") {
                $data = array(  
                    "kd_barang"     => "",
                    "kd_kategori"   => $kd_kategori,
                    "nama_barang"   => $nama_barang,
                    "harga_jual"    => $harga_jual,
                    "harga_beli"    => $harga_beli,
                    "stok"          => $stok,
                    "gambar_barang" => "",
                );
            }else{
                file_put_contents($path, base64_decode($this->post('gambar_barang')));
                $data = array(  
                    "kd_barang"     => "",
                    "kd_kategori"   => $kd_kategori,
                    "nama_barang"   => $nama_barang,
                    "harga_jual"    => $harga_jual,
                    "harga_beli"    => $harga_beli,
                    "stok"          => $stok,
                    "gambar_barang" => $path,
                );
            }

            $result = $this->M_barang->insert($data);
            if($result>=0){
                $this->response(['kode' => 1,'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }else if($api=="edit") {
            $kd_kategori= $this->post('kd_kategori');
            $nama_barang= $this->post('nama_barang');
            $harga_jual= $this->post('harga_jual');
            $harga_beli=$this->post('harga_beli');
            $stok=$this->post('stok');

            $path='assets/images/upload/barang/barang_'.$nama_barang.'.jpeg';
            if ($this->post('gambar_barang')=="") {
                $data = array(  
                    "kd_kategori"   => $kd_kategori,
                    "nama_barang"   => $nama_barang,
                    "harga_jual"    => $harga_jual,
                    "harga_beli"    => $harga_beli,
                    "stok"          => $stok,
                    "gambar_barang" => "",
                );
            }else{
                file_put_contents($path, base64_decode($this->post('gambar_barang')));
                $data = array(  
                    "kd_kategori"   => $kd_kategori,
                    "nama_barang"   => $nama_barang,
                    "harga_jual"    => $harga_jual,
                    "harga_beli"    => $harga_beli,
                    "stok"          => $stok,
                    "gambar_barang" => $path,
                );
            }
            
            $result = $this->M_barang->update($this->post('kd_barang'), $data);
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }
    }
    
    function index_get(){
        if ($this->get('api')=="barangdetail") {
            $row = $this->M_barang->get_by_kd($this->get('kd_barang'));
            if ($row) {
                $data = array(
                    "kd_barang"     => $row->kd_barang,
                    "kd_kategori"   => $row->kd_kategori,
                    "nama_barang"   => $row->nama_barang,
                    "nama_kategori" => $row->nama_kategori,
                    "harga_jual"    => $row->harga_jual,
                    "harga_beli"    => $row->harga_beli,
                    "stok"          => $row->stok,
                    "gambar_barang" => $row->gambar_barang,
                );
                $this->response($data, REST_Controller::HTTP_OK);   
            }
        }elseif ($this->get('api')=="barangall") {
            $barang = $this->M_barang->get_all();
            $jml_barang= $this->M_barang->total_rows(0);
            $data = array(
                'data'     => $barang,
                'jml_data' => $jml_barang
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="delete") {
            $result = $this->M_barang->delete($this->get('kd_barang'));
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil dihapus!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal dihapus!'], REST_Controller::HTTP_OK);
            }
        }
    }
}