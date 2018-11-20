<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic biaya interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Biaya extends REST_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_biaya');
    }

    function index_post(){
        $api=$this->post('api');
        if($api=="tambah") {
            $nama_biaya= $this->post('nama_biaya');
            $tgl_biaya= $this->post('tgl_biaya');
            $jumlah_biaya=$this->post('jumlah_biaya');

            $data = array(  
                'kd_biaya'      => "",
                'nama_biaya'    => $nama_biaya, 
                'tgl_biaya'     => $tgl_biaya,
                'jumlah_biaya'  => $jumlah_biaya,
            );

            $result = $this->M_biaya->insert($data);
            if($result>=0){
                $this->response(['kode' => 1,'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }else if($api=="edit") {
            $nama_biaya= $this->post('nama_biaya');
            $tgl_biaya= $this->post('tgl_biaya');
            $jumlah_biaya=$this->post('jumlah_biaya');
            $data = array(  
                'nama_biaya'    => $nama_biaya, 
                'tgl_biaya'     => $tgl_biaya,
                'jumlah_biaya'  => $jumlah_biaya,
            );
            $result = $this->M_biaya->update($this->post('kd_biaya'), $data);
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }
    }
    
    function index_get(){
        if ($this->get('api')=="biayadetail") {
            $row = $this->M_biaya->get_by_kd($this->get('kd_biaya'));
            if ($row) {
                $data = array(
                    "kd_biaya"          => $row->kd_biaya,
                    "nama_biaya"        => $row->nama_biaya,
                    "tgl_biaya"         => $row->tgl_biaya,
                    "jumlah_biaya"      => $row->jumlah_biaya,
                );
                $this->response($data, REST_Controller::HTTP_OK);   
            }
        }elseif ($this->get('api')=="biayaall") {
            $biaya = $this->M_biaya->get_all();
            $jml_biaya= $this->M_biaya->total_rows(0);
            foreach ($biaya as $data_biaya) {
                $data_biaya->jumlah_biaya=str_replace(",", ".", number_format($data_biaya->jumlah_biaya));
            }
            $data = array(
                'data'     => $biaya,
                'jml_data' => $jml_biaya
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }elseif ($this->get('api')=="delete") {
            $result = $this->M_biaya->delete($this->get('kd_biaya'));
            if($result>=0){
                $this->response(['kode' => 1, 'pesan' =>'Data Berhasil dihapus!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal dihapus!'], REST_Controller::HTTP_OK);
            }
        }
    }
}