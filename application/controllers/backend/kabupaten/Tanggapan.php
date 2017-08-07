<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 */
class Tanggapan extends Backend
{

  function __construct()
  {
    parent::__construct();
    $this->is_kabupaten();
    date_default_timezone_set("Asia/Bangkok");
  }

  public function index()
  {
    $this->load->model('Tanggapan_pengaduan_model');

    $jabatan = $this->session->userdata('admin');

    $tanggapan = $this->Tanggapan_pengaduan_model->get_tanggapan($jabatan['id_rj']);

    $data = [
      'sidebar' => 7,
      'header' => 'Tanggapan',
      'crumb' =>  [
                    ['label'=>'Petugas Kabupaten','link'=>'admin/kabupaten'],
                    ['label'=>'Tanggapan','link'=>'admin/kabupaten/tanggapan'],
                      ],
      'small' => 'Daftar Tanggapan',
      'tanggapan' => $tanggapan,
      'no' => 0,
    ];
    $this->layout->load_backend('backend/kabupaten/tanggapan/daftar', $data);
  }

  public function tambah($id_pengaduan)
  {

      $this->load->model('Tanggapan_pengaduan_model');

      $params = [
        'id_tanggapan' => '',
        'id_pengaduan' => $id_pengaduan,
        'id_rj' => $this->input->get('rj'),
        'waktu_tanggapan' => date('G:i:s'),
        'tanggal_tanggapan' => date('Y-m-d'),
        'isi_tanggapan' => $this->input->post('tanggapan'),

      ];

      $this->Tanggapan_pengaduan_model->insert($params);

      $status = [
        'id_status' => "",
        'id_pengaduan' => $id_pengaduan,
        'kategori' => 'tanggapan/klarifikasi',
        'waktu_status' => date('G:i:s'),
        'tanggal_status' => date('Y-m-d'),
        'nama_status' => 'Telah ditanggapi oleh Petugas Kabupaten',
        'dibaca' => 'belum',
      ];

      $this->load->model('Status_pengaduan_model');
      $this->Status_pengaduan_model->add_riwayat($status);

      $ok = [
        'status_pengaduan' => 'ok',
      ];

      $this->load->model('Laporan_pengaduan_model');
      $this->Laporan_pengaduan_model->ok($ok, $id_pengaduan);

      $this->alert('Pengaduan berhasil menambahkan tanggapan', base_url('admin/kabupaten/pengaduan/detail/'.$id_pengaduan));

    
  }

  public function hapus($id_pengaduan, $id_tanggapan)
  {
    $this->load->model('Tanggapan_pengaduan_model');

    $ok = [
      'status_pengaduan' => 'valid',
    ];

    $this->load->model('Laporan_pengaduan_model');
    $this->Laporan_pengaduan_model->ok($ok, $id_pengaduan);

    $this->load->model('Status_pengaduan_model');
    $this->Status_pengaduan_model->hapus($id_pengaduan, 'tanggapan/klarifikasi');

    $this->alert($this->Tanggapan_pengaduan_model->delete_tanggapan($id_tanggapan), base_url('admin/kabupaten/tanggapan'));
  }

  public function edit($id_tanggapan)
  {
    if ($id_tanggapan!=null) {
      $data = ['isi_tanggapan' => $this->input->post('tanggapan'), ];
      $this->load->model('Tanggapan_pengaduan_model');
      $this->alert($this->Tanggapan_pengaduan_model->update_tanggapan($id_tanggapan, $data), base_url('admin/kabupaten/pengaduan/detail/'.$this->input->get('pengaduan')));
    }else {
        $this->alert('tidak ada yang bisa diubah', base_url('admin/kabupaten/tanggapan'));
    }
  }
}

?>
