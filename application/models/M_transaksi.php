<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_transaksi extends CI_Model
{

    public $table = 'transaksi';
    public $kd = 'kd_transaksi';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all($jenis_transaksi,$status) {
    	$this->db->where('jenis_transaksi', $jenis_transaksi);
    	$this->db->where('status', $status);
        $this->db->order_by($this->kd, $this->order);
        return $this->db->get($this->table)->result();
    }
    function get_where($where){
        $this->db->where($where);
        $this->db->order_by($this->kd, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by kd
    function get_by_kd($kd){
        $this->db->where($this->kd, $kd);
        $this->db->join("user", "user.kd_user=transaksi.kd_user","left");
        return $this->db->get($this->table)->row();
    }

    function get_last(){
        $this->db->order_by($this->kd, $this->order);
        return $this->db->get($this->table)->row();
    }

    function get_detail_transaksi($kd) {
        $this->db->where('kd_transaksi', $kd);
        $this->db->order_by($this->kd, $this->order);
        $this->db->join("barang", "barang.kd_barang=detail_transaksi.kd_barang","left");
        return $this->db->get("detail_transaksi")->result();
    }

    // get data by transaksi
    /*function get_by_kat($kat)
    {
        $this->db->where($this->kat, $kat);
        return $this->db->get($this->table)->result();
    }*/

     // get total rows
    function total_rows($limit,$q = NULL) {
        $this->db->like('kd_transaksi', $q);
        $this->db->or_like('tgl_transaksi', $q);
        $this->db->or_like('harga_total', $q);
        $this->db->or_like('jenis_transaksi', $q);
        $this->db->or_like('status_transaksi', $q);
        return $this->db->get($this->table,$limit)->num_rows();
    }

    function total_rows_perjenis($jenis_transaksi,$status) {
    	$this->db->where('jenis_transaksi', $jenis_transaksi);
    	$this->db->where('status', $status);
        return $this->db->get($this->table)->num_rows();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $nama_kolom='kd_transaksi', $order='DESC') {
       $this->db->order_by($nama_kolom, $order);
       $this->db->like('kd_transaksi', $q);
       $this->db->or_like('tgl_transaksi', $q);
       $this->db->or_like('harga_total', $q);
       $this->db->or_like('jenis_transaksi', $q);
       $this->db->or_like('status_transaksi', $q);
       $this->db->limit($limit, $start);
       return $this->db->get($this->table)->result();
    }

    function laporan($where){
        return $this->db->query("SELECT * FROM transaksi ".$where);
    }

    function bebanbiaya($where){
        return $this->db->query("SELECT * FROM biaya ".$where);
    }


    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // insert data
    function insert_to_detail($data)
    {  
        $this->db->insert("detail_transaksi", $data);
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

    function delete_detail($kd)
    {
        $this->db->where($this->kd, $kd);
        $this->db->delete("detail_transaksi");
    }

}