<?php

// Controler berfokus pada pemrosesan data bukan menampilkan data

class Blog extends CI_Controller {  // nama class harus diawali huruf kapital dan harus sama dengan nama file

  public function __construct()
  {
    parent::__construct(); // untuk class extends maka lakukan override untuk mengambil construct parentsnya
    // load database agar dapat menggunakan $this->db. Dilakukan di file config/autoload.php
    // include url_helper agar bisa menggunakan site_url. Dilakukan di file config/autoload.php
    $this->load->model('Blog_model'); // include model Blog_model
    $this->load->library('session');
  }

  public function index($offset = 0) //offset disini adalah urutan artikal yang mulai di tampilkan tiap page. didapatkan dari url
  {
    $this->load->library('pagination');

    $config['base_url'] = site_url('blog/index');
    $config['total_rows'] = $this->Blog_model->getTotalBlogs(); // Menghitung total artikel
    $config['per_page'] = 3;

    $this->pagination->initialize($config);

    $query = $this->Blog_model->getBlogs($config['per_page'], $offset); // parameter untuk mengirim limit dan artikel yg mulai ditampilkan 
    $data['blogs'] = $query->result_array(); // mengambil beberapa baris data jika result() keluaran dalam bentuk objek

    $this->load->view('blog', $data);
  }

  public function detail($url)
  {
    $query = $this->Blog_model->getSingleBlog('url', $url);
    $data['blog'] = $query->row_array(); // mengambil satu baris saja

    $this->load->view('detail', $data);
  }

  public function add()
  {
    $this->form_validation->set_rules('title', 'Judul', 'required'); //rule required mengecek apakah terisi atau kosong
    $this->form_validation->set_rules('url', 'URL', 'required|alpha_dash'); // rule apha_dash string tidak boleh berisi - @ #
    $this->form_validation->set_rules('content', 'Konten', 'required');

    if ( $this->form_validation->run() ) { // set mengecek apakah lolos rule atau tidak
      $data['title'] = $this->input->post('title'); // $this->input->post perintah dari CI untuk mengambil data post dimana ketika tidak ada data yg dikirim tidak akan muncul pesan error, sehingga tidak perlu cek seperti menggunakan $_POST/$_GET
      $data['content'] = $this->input->post('content');
      $data['url'] = $this->input->post('url');

      $config['upload_path']          = './uploads/';
      $config['allowed_types']        = 'gif|jpg|png';
      $config['max_size']             = 100;
      $config['max_width']            = 1024;
      $config['max_height']           = 768;

      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('cover'))
      {
              echo $this->upload->display_errors();
      }
      else
      {
              $data['cover'] = $this->upload->data()['file_name'];
      }

      $id = $this->Blog_model->insertBlog($data);

      if ($id) {
        $this->session->set_flashdata("message", '<div class="alert alert-success">"Data berhasil disimpan"</div>');
        redirect('/');
      } else {
        $this->session->set_flashdata("message", '<div class="alert alert-warning">"Data gagal disimpan"</div>');
        redirect('/');
      }
    }

    $this->load->view('form_add');
  }

  public function edit($id)
  {
    $query = $this->Blog_model->getSingleBlog('id', $id);
    $data['blog'] = $query->row_array(); // mengambil satu baris saja

    $this->form_validation->set_rules('title', 'Judul', 'required'); //rule required mengecek apakah terisi atau kosong
    $this->form_validation->set_rules('url', 'URL', 'required|alpha_dash'); // rule apha_dash string tidak boleh berisi - @ #
    $this->form_validation->set_rules('content', 'Konten', 'required');

    if ( $this->form_validation->run() === TRUE ) {
      $post['title'] = $this->input->post('title');
      $post['content'] = $this->input->post('content');
      $post['url'] = $this->input->post('url');

      $config['upload_path']          = './uploads/';
      $config['allowed_types']        = 'gif|jpg|png';
      $config['max_size']             = 100;
      $config['max_width']            = 1024;
      $config['max_height']           = 768;

      $this->load->library('upload', $config);
      $this->upload->do_upload('cover');

      if(!empty($this->upload->data()['file_name'])) {
        $post['cover'] = $this->upload->data()['file_name'];
      }
    
      $id = $this->Blog_model->updateBlog($id, $post);

      if ($id) {
        $this->session->set_flashdata("message", '<div class="alert alert-success">"Data berhasil disimpan"</div>');
        redirect('/');
      } else {
        $this->session->set_flashdata("message", '<div class="alert alert-warning">"Data gagal disimpan"</div>');
        redirect('/');
      }
    }
    
    $this->load->view('form_edit', $data);
  }

  public function delete($id)
  {
    $result = $this->Blog_model->deleteBlog($id);

    if($result)
      $this->session->set_flashdata("message", '<div class="alert alert-success">"Data berhasil dihapus"</div>');
    else
    $this->session->set_flashdata("message", '<div class="alert alert-warning">"Data gagal dihapus"</div>');

    redirect('/');  // Untuk rederict ke halaman utama, cukup / kerena manggunakan helper('url')
  }

  public function login()
  {
    if($this->input->post())
    {
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      
      if($username == 'admin' && $password == 'admin')
      {
        $_SESSION['username'] = 'admin';
        redirect('/');
      }
      else
      {
        $this->session->set_flashdata('message', '<div class="alert alert-warning">Username/password tidak valid</div>');
        redirect('blog/login');
      } 
    }
    $this->load->view('login');
  }

  public function logout()
  {
    $this->session->sess_destroy();
  }
}