<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_all_artikel($limit = null, $start = null) {
        if ($limit !== null && $start !== null) {
            $this->db->limit($limit, $start);
        }
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('artikels');
        return $query->result();
    }
    public function get_topik(){
        $query = $this->db->get('artikels');
        return $query->result();
    }
    

    public function jumlah_data_artikel(){
        return $this->db->count_all('artikels');    
    }

    public function tambah_artikel($data){
        $this->db->insert('artikels', $data);
    }

    public function hapus_artikel($id){
        $this->db->where('id_artikel', $id);
        return $this->db->delete('artikels');
    }
    
    public function get_artikel_by_id($id){
        $query = $this->db->get_where('artikels', array('id_artikel' => $id));
        return $query->row();
    }

    public function update_artikel($id, $data){
        $this->db->where('id_artikel', $id);
        return $this->db->update('artikels', $data);
    }
    
    public function get_artikel_terkait($limit = 5) {
        $this->db->select('id_artikel, judul_artikel, topik, isi_artikel, banner');
        $this->db->limit($limit);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('artikels');
        
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }

    public function search_artikel($query, $limit, $start) {
        $this->db->like('judul_artikel', $query);
        $this->db->or_like('isi_artikel', $query);
        $this->db->limit($limit, $start);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('artikels');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function count_search_results($query) {
        $this->db->like('judul_artikel', $query);
        $this->db->or_like('isi_artikel', $query);
        return $this->db->count_all_results('artikels');
    }
}


?>