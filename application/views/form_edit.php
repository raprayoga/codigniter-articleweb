<?php $this->load->view('partials/header'); ?>

<!-- Page Header -->
<header class="masthead" style="background-image: url('<?= base_url(); ?>assets/img/post-bg.jpg')">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
          <div class="post-heading">
            <h1>Tambah Artikel</h1>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        
        <h1>Ubah Artikel</h1>

        <div class="alert alert-warning">
          <?= validation_errors(); ?> <!--memunculkan pesan error dari validasi-->
        </div>

        <?= form_open_multipart(); ?>
          <div class="form-group">
            <label>Judul</label>
            <?= form_input('title',  set_value('title', $blog['title']), 'class="form-control"'); ?> <!-- parameter kedua adalah value, ketiga adalah attribute-->
          </div>
          
          <div class="form-group">
            <label>URL</label>
            <?= form_input('url',  set_value('url', $blog['url']), 'class="form-control"'); ?> <!--blog['title'] adalah nilai default-->
          </div>

          <div class="form-group">
            <label>Konten</label>
            <?= form_textarea('content',  set_value('content', $blog['content']), 'class="form-control"'); ?>
          </div>

          <div class="form-group">
            <label>Cover</label>
            <?= form_upload('cover', $blog['cover'], 'class="form-control"'); ?>
          </div>

          <button class="btn btn-primary" type="submit">Simpan Artikel</button>
        </form>
      </div>
    </div>
  </div>
  
  <?php $this->load->view('partials/footer'); ?>