<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class User extends REST_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_user');
    }

    function index_post(){
        $api=$this->post('api');
        if ($api=="login") {
            $username = $this->post('user');
            $password = $this->post('pass');
            $where = array('username' => $username, );
            $cek_fase_1 = $this->M_user->cek_login($where)->num_rows();
            $cek_fase_2 = $this->M_user->cek_login($where)->row();
            if($cek_fase_1 > 0){
                $this->load->library('encryption'); 
                $key = 'pratamatechnocraft';
                $this->encryption->initialize(
                    array(
                        'cipher' => 'aes-256',
                        'mode' => 'ctr',
                        'key' => $key
                    )
                );
                $password_encryption =  $this->encryption->decrypt($cek_fase_2->password);
                if ($password==$password_encryption) {
                    $data_session = array(
                        'kd_user'       => $cek_fase_2->kd_user,
                        'level_user'    => $cek_fase_2->level_user

                    );
                    $message = array("success"=>1,"data_user"=>$data_session);
                    $this->response($message, REST_Controller::HTTP_OK);
                }else{
                    $message = array("success"=>2);
                    $this->response($message, REST_Controller::HTTP_OK);
                }
            }else{
                $message = array("success"=>3);
                $this->response($message, REST_Controller::HTTP_OK);   
            }
        }else if ($api=="editprofile") {
            $data = array(
                'nama_depan'    => $this->post('nama_depan'),
                'nama_belakang' => $this->post('nama_belakang'),
                'alamat'        => $this->post('alamat'),
                'no_telp'       => $this->post('no_telp')
            );
            $res = $this->M_user->update($this->post('kd_user'),$data);
            if($res>=0){
                $this->response(['kode' => 1,'data' => $res], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Proses Gagal'], REST_Controller::HTTP_OK);
            }
        }else if ($api=="ubahpassword"){
            $this->load->library('encryption'); 
            $key = 'pratamatechnocraft';
            $this->encryption->initialize(
                array(
                    'cipher' => 'aes-256',
                    'mode' => 'ctr',
                    'key' => $key
                )
            );
            $password_encrypt =  $this->encryption->encrypt($this->post('password_baru'), $key);
            $data = array(
                'password'  => $password_encrypt,
            );
            $res = $this->M_user->update($this->post('kd_user'),$data);
            if($res>=0){
                $this->response(['kode' => 1,'data' => $res], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Proses Gagal'], REST_Controller::HTTP_OK);
            }
        }else if($api=="ubahfoto"){
            $tgl_sekarang=date("ymdHis");
            $path="assets/images/upload/user/".$this->post('kd_user')."_".$tgl_sekarang.".jpeg";
            if (file_put_contents($path, base64_decode($this->post('foto')))) {
                $data = array(
                    'foto'  => $path,
                );
                $res = $this->M_user->update($this->post('kd_user'),$data);
                if($res>=0){
                    $this->response(['kode' => 1,'urlFoto' => $path], REST_Controller::HTTP_OK);
                }else{
                    $this->response(['kode' => 2,'pesan' =>'Proses Gagal'], REST_Controller::HTTP_OK);
                }   
            }else{
                $this->response(['kode' => 2,'pesan' =>'Proses Gagal'], REST_Controller::HTTP_OK);
            }   
        }else if($api=="tambah") {
            $this->load->library('encryption'); 
            $key = 'pratamatechnocraft';
            $this->encryption->initialize(
                array(
                    'cipher' => 'aes-256',
                    'mode' => 'ctr',
                    'key' => $key
                )
            );
            $username= $this->post('username');
            $password =  $this->encryption->encrypt($this->post('password'), $key);
            $nama_depan= $this->post('nama_depan');
            $nama_belakang= $this->post('nama_belakang');
            $no_telp=$this->post('no_telp');
            $alamat=$this->post('alamat');
            $level_user=$this->post('level_user');

            $path='assets/images/upload/user/user_'.$nama_depan.'.jpeg';
            if ($this->post('foto')=="";) {
                $data = array(  
                    'kd_user'       => "",
                    'username'      => $username, 
                    'password'      => $password,
                    'nama_depan'    => $nama_depan, 
                    'nama_belakang' => $nama_belakang,
                    'no_telp'       => $no_telp,
                    'alamat'        => $alamat,
                    'foto'          => "",
                    'level_user'    => $level_user,
                );
            }else{
                file_put_contents($path, base64_decode($this->post('foto')));
                $data = array(  
                    'kd_user'       => "",
                    'username'      => $username, 
                    'password'      => $password,
                    'nama_depan'    => $nama_depan, 
                    'nama_belakang' => $nama_belakang,
                    'no_telp'       => $no_telp,
                    'alamat'        => $alamat,
                    'foto'          => $path,
                    'level_user'    => $level_user,
                );
            }

            $result = $this->M_user->insert($data);
            if($result>=0){
                $this->response(['kode' => 1, 'data' => $dataterakhir,'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }else if($api=="edit") {
            $nama_depan= $this->post('nama_depan');
            $nama_belakang= $this->post('nama_belakang');
            $no_telp=$this->post('no_telp');
            $alamat=$this->post('alamat');
            $level_user=$this->post('level_user');
            $data = array(  
                'nama_depan'    => $nama_depan, 
                'nama_belakang' => $nama_belakang,
                'no_telp'       => $no_telp,
                'alamat'        => $alamat,
                'level_user'    => $level_user,
            );
            $result = $this->M_user->update($this->post('kd_user'), $data);
            if($result>=0){
                $this->response(['kode' => 1, 'data' => $dataterakhir,'pesan' =>'Data Berhasil disimpan!'], REST_Controller::HTTP_OK);
            }else{
                $this->response(['kode' => 2,'pesan' =>'Data gagal diSimpan!'], REST_Controller::HTTP_OK);
            }
        }
    }
    
    function index_get(){
        if ($this->get('api')=="profile") {
            $row = $this->M_user->get_by_kd($this->get('kd_user'));
            $this->load->library('encryption'); 
            $key = 'pratamatechnocraft';
            $this->encryption->initialize(
                array(
                    'cipher' => 'aes-256',
                    'mode' => 'ctr',
                    'key' => $key
                )
            );
            $password_decryption =  $this->encryption->decrypt($row->password);
            if ($row) {
                $data = array(
                    "kd_user"           => $row->kd_user,
                    "nama_depan"        => $row->nama_depan,
                    "nama_belakang"     => $row->nama_belakang,
                    "password"          => $password_decryption,
                    "no_telp"           => $row->no_telp,
                    "alamat"            => $row->alamat,
                    "level_user"        => $row->level_user,
                    "foto"              => $row->foto,
                );
                $this->response($data, REST_Controller::HTTP_OK);   
            }
        }elseif ($this->get('api')=="userall") {
            $user = $this->M_user->get_all();
            $jml_user= $this->M_user->total_rows(0);
            $data = array(
                'data'     => $user,
                'jml_data' => $jml_user
            );
            $this->response($data, REST_Controller::HTTP_OK);
        }else if ($this->get('api')=="userdetail") {
            $user = $this->M_user->get_by_kd($this->get('kd_user'));
            $this->response($user, REST_Controller::HTTP_OK);
        }
    }
}