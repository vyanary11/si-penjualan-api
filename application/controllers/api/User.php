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
                    "kd_user" => $row->kd_user,
                );
                $this->response($data, REST_Controller::HTTP_OK);   
            }
        }
    }
}