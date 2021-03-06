<?php
/*
 * Generated by CRUDigniter v3.0 Beta
 * www.crudigniter.com
 */

class Kategori_pengaduan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Get kategori_pengaduan by id_kategori
     */
    function get_kategori_pengaduan($id_kategori)
    {
        return $this->db->get_where('kategori_pengaduan',array('id_kategori'=>$id_kategori, 'status' => 'Y'))->row_array();
    }

    /*
     * Get all kategori_pengaduan
     */
    function get_all_kategori_pengaduan_kabupaten()
    {
        return $this->db->get('kategori_pengaduan')->result_array();
    }

    function get_all_kategori_pengaduan()
    {
        return $this->db->get_where('kategori_pengaduan', ['status' => 'Y'])->result_array();
    }

    /*
     * function to add new kategori_pengaduan
     */
    function add_kategori_pengaduan($params)
    {
        $this->db->insert('kategori_pengaduan',$params);
        return $this->db->insert_id();
    }

    /*
     * function to update kategori_pengaduan
     */
    function update_kategori_pengaduan($id_kategori,$params)
    {
        $this->db->where('id_kategori',$id_kategori);
        $response = $this->db->update('kategori_pengaduan',$params);
        if($response)
        {
            return "kategori telah di setujui";
        }
        else
        {
            return "Error occuring while updating kategori_pengaduan";
        }
    }

    /*
     * function to delete kategori_pengaduan
     */
    function delete_kategori_pengaduan($id_kategori)
    {
        $response = $this->db->delete('kategori_pengaduan',array('id_kategori'=>$id_kategori));
        if($response)
        {
            return "kategori_pengaduan deleted successfully";
        }
        else
        {
            return "Error occuring while deleting kategori_pengaduan";
        }
    }

    function getJumlah($id_kategori)
    {
      $this->db->select('COUNT(*) as jumlah');

      $this->db->from('laporan_pengaduan');

      $this->db->join('kategori_pengaduan', 'laporan_pengaduan.id_kategori = kategori_pengaduan.id_kategori');

      $this->db->where('laporan_pengaduan.id_kategori', $id_kategori);
      $this->db->where("(laporan_pengaduan.status_pengaduan='valid' OR laporan_pengaduan.status_pengaduan='ok')", NULL, FALSE);
      $this->db->where('kategori_pengaduan.status', 'Y');

      return $this->db->get()->row_array();
    }

    function getJumlah_sekitar($id_kategori, $lokasi)
    {
      $this->db->select('COUNT(*) as jumlah');

      $this->db->from('laporan_pengaduan');

      $this->db->join('kategori_pengaduan', 'laporan_pengaduan.id_kategori = kategori_pengaduan.id_kategori');
      $this->db->join('penduduk', 'laporan_pengaduan.id_pdk = penduduk.id_pdk');
      $this->db->join('riwayat_alamat', 'penduduk.id_pdk = riwayat_alamat.id_pdk');
      $this->db->join('rt', 'riwayat_alamat.id_rt = rt.id_rt');
      $this->db->join('rw', 'rt.id_rw = rw.id_rw');
      $this->db->join('dusun', 'rw.id_dusun = dusun.id_dusun');
      $this->db->join('kelurahan', 'dusun.id_kel = kelurahan.id_kel');
      $this->db->join('kecamatan', 'kelurahan.id_kec = kecamatan.id_kec');
      $this->db->join('kabupaten', 'kecamatan.id_kab = kabupaten.id_kab');
      $this->db->join('provinsi', 'kabupaten.id_prov = provinsi.id_prov');

      $this->db->where('kategori_pengaduan.status', 'Y');
      $this->db->where('riwayat_alamat.stts_ra', 'aktif');
      $this->db->where('kelurahan.id_kel', $lokasi['id_kel']);
      $this->db->where('laporan_pengaduan.id_kategori', $id_kategori);
      $this->db->where("(laporan_pengaduan.status_pengaduan='valid' OR laporan_pengaduan.status_pengaduan='ok')", NULL, FALSE);


      return $this->db->get()->row_array();
    }
}
