<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_barang extends CI_Model
{

    public $table = 'barang';
    public $kd = 'kd_barang';
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
        $this->db->join("kategori","kategori.kd_kategori=barang.kd_kategori","left");
        return $this->db->get($this->table)->row();
    }

    // get data by kategori
    /*function get_by_kat($kat)
    {
        $this->db->where($this->kat, $kat);
        return $this->db->get($this->table)->result();
    }*/

     // get total rows
    function total_rows($limit,$q = NULL) {
        $this->db->like('kd_barang', $q);
        $this->db->or_like('nama_barang', $q);
        $this->db->or_like('harga_jual', $q);
        $this->db->or_like('harga_beli', $q);
        $this->db->or_like('stok', $q);
        return $this->db->get($this->table,$limit)->num_rows();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $nama_kolom='kd_barang', $order='DESC') {
       $this->db->order_by($nama_kolom, $order);
       $this->db->like('kd_barang', $q);
       $this->db->or_like('nama_barang', $q);
       $this->db->or_like('harga_jual', $q);
       $this->db->or_like('harga_beli', $q);
       $this->db->or_like('stok', $q);
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

}