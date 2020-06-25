<?php

class Blog_model extends CI_Model {

  public function getBlogs($limit, $offset)
  {
    $filter = $this->input->get('find');
    $this->db->like('title', $filter);
    $this->db->limit($limit, $offset);
    $this->db->order_by('date', 'desc');

    return $this->db->get("blog"); //query builder select all dari codeigniter
  }

  public function getTotalBlogs()
  {
    $filter = $this->input->get('find');
    $this->db->like('title', $filter);

    return $this->db->count_all_results("blog"); // mengembalikan total data
  }

  public function getSingleBlog($field, $value)
  {
    $this->db->where($field, $value); // membuat where untuk digunakan pada fungsi db->get
    return $this->db->get('blog'); // gunakan  get()
  }

  public function insertBlog($data)
  {
    $this->db->insert('blog', $data); // fungsi CI untuk insert into
    return $this->db->insert_id(); // insert_id mengembalikan nilai kolom id yg di insert guna nanti mengecek apakah berhasil di input
  }

  public function updateBlog($id, $post)
  {
    $this->db->where('id', $id);
    $this->db->update('blog', $post);
    return $this->db->affected_rows(); // affected_rows() mengembalikan nilai jumlah baris yang mengalami perubahan
  }

  public function deleteBlog($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('blog');
    return $this->db->affected_rows();
  }
}