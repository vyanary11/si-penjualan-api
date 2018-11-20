<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic kategori interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Kategori extends REST_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_kategori');
    }

    function index_post(){
        $api=$this->post('api');
        if($api=="tambah") {
            $nama_kategori= $this->post('nama_kategori');

            $data = array(  
                "kd_kategori"     => "",
                "nama_kategori"   => $nama_kategori,
            );

            $result = $this->M_kategori->insert($data);
            if($result>=0){
                $this->response(['kode' => 1,'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }
    }
    
    function index_get(){
        if ($this->get('api')=="kategoridetail") {
            $row = $this->M_kategori->get_by_kd($this->get('kd_kategori'));
            if ($row) {
                $data = array(
                    "kd_kategori"     => $row->kd_kategori,
                    "nama_kategori"   => $row->nama_kategori,
                );
                $this->response($data, REST_Controller::HTTP_OK);   
            }
        }elseif ($this->get('api')=="kategoriall") {
            $kategori = $this->M_kategori->get_all();
            $jml_kategori= $this->M_kategori->total_rows(0);
            $data = array(
                'data'     => $kategori,
                'jml_data' => $jml_kategori
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="delete") {
            $result = $this->M_kategori->delete($this->get('kd_kategori'));
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil dihapus!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal dihapus!'], REST_Controller::HTTP_OK);
            }
        }
    }
}