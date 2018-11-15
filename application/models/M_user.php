<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_user extends CI_Model
{

    public $table = 'user';
    public $kd = 'kd_user';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->kd, $this->order);
        return $this->db->get($this->table)->result();
    }
    function get_where($where)
    {
        $this->db->where($where);
        $this->db->order_by($this->kd, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by kd
    function get_by_kd($kd)
    {
        $this->db->where($this->kd, $kd);
        return $this->db->get($this->table)->row();
    }

     // get total rows
    function total_rows($limit,$q = NULL,$where=NULL) {
        $this->db->where($where);
        $this->db->like('kd_user', $q);
        $this->db->or_like('nama_depan', $q);
        $this->db->or_like('level_user', $q);
        return $this->db->get($this->table,$limit)->num_rows();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $nama_kolom='kd_user', $order='DESC') {
       $this->db->order_by($nama_kolom, $order);
       $this->db->like('kd_user', $q);
       $this->db->or_like('nama_depan', $q);
       $this->db->or_like('level_user', $q);
       $this->db->limit($limit, $start);
       return $this->db->get($this->table)->result();
    }


    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($kd, $data)
    {
        $this->db->where($this->kd, $kd);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($kd)
    {
        $this->db->where($this->kd, $kd);
        $this->db->delete($this->table);
    }

    function cek_login($where){      
        return $this->db->get_where($this->table,$where);
    }

    function cek_user($where){ 
        $this->db->where($where);  
        return $this->db->get($this->table);
    }

}