<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_pesan($limit, $start){
        $this->db->limit($limit, $start);

        $this->db->select('messages.*, pengirim.nama_lengkap as nama_pengirim, penerima.nama_lengkap as nama_penerima');
        $this->db->from('messages');
        $this->db->join('users as pengirim', 'messages.pengirim_id = pengirim.id_user');
        $this->db->join('users as penerima', 'messages.penerima_id = penerima.id_user');
        $this->db->order_by('messages.created_at', 'ASC');
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result();
        }else {
            return [];
        }
    }

    public function get_kontak($receiver_id){
        $this->db->select('messages.*, users.nama_lengkap as pengirim_nama');
        $this->db->from('messages');
        $this->db->join('users', 'messages.pengirim_id = users.id_user');
        $this->db->where('messages.penerima_id', $receiver_id);
        $this->db->order_by('messages.created_at', 'DESC');
        
        $query = $this->db->get();
        // Log untuk debugging
        log_message('debug', 'SQL Query: ' . $this->db->last_query());
        return $query->result();
    }

    public function delete_messages_by_sender($sender_id, $receiver_id) {
        $this->db->where('pengirim_id', $sender_id);
        $this->db->where('penerima_id', $receiver_id);
        $this->db->or_where('pengirim_id', $receiver_id);
        $this->db->where('penerima_id', $sender_id);
        $this->db->delete('messages');
    }
    
    // Method untuk mendapatkan isi pesan berdasarkan id_pesan
    public function get_jumlah_pesan_berdasarkan_id($receiver_id) {
        $this->db->select('messages.pengirim_id, users.nama_lengkap as pengirim_nama, COUNT(CASE WHEN messages.is_read = 0 THEN 1 END) as unread_count, MAX(messages.id_pesan) as id_pesan, messages.is_read');
        $this->db->from('messages');
        $this->db->join('users', 'messages.pengirim_id = users.id_user');
        $this->db->where('messages.penerima_id', $receiver_id);
        $this->db->group_by('messages.pengirim_id');
        $this->db->order_by('id_pesan', 'DESC');
        
        $query = $this->db->get();

        // Log untuk debugging query
        log_message('debug', 'Grouped SQL Query: ' . $this->db->last_query());

        return $query->result();
    }

    // Method untuk memperbarui status pesan menjadi "dibaca"
    public function mark_message_as_read($id_pesan) {
        // Ambil data pesan dari database
        $this->db->select('pengirim_id');
        $this->db->where('id_pesan', $id_pesan);
        $query = $this->db->get('messages');
        $message = $query->row();
    
        if ($message) {
            $current_user_id = $this->session->userdata('id_user');
    
            // Periksa apakah pengirim_id adalah pengguna saat ini
            if ($message->pengirim_id != $current_user_id) {
                // Jika bukan, tandai pesan sebagai sudah dibaca
                $this->db->set('is_read', 1);
                $this->db->where('id_pesan', $id_pesan);
                $this->db->update('messages');
            }
        }
    }
    
    
    public function insert_message($data) {
        $this->db->insert('messages', $data);
    }
    
    public function get_messages_by_sender($sender_id, $receiver_id) {
        $this->db->select('messages.*, users.nama_lengkap as pengirim_nama');
        $this->db->from('messages');
        $this->db->join('users', 'messages.pengirim_id = users.id_user');
        $this->db->where("(messages.pengirim_id = $sender_id AND messages.penerima_id = $receiver_id)");
        $this->db->or_where("(messages.pengirim_id = $receiver_id AND messages.penerima_id = $sender_id)");
        $this->db->order_by('messages.created_at', 'ASC');
    
        $query = $this->db->get();
    
        return $query->result();
    }
    
    public function jumlah_pesan(){
        return $this->db->count_all('messages');
    }
    
    public function hapus_pesan($id_pesan) {
        $this->db->where('id_pesan', $id_pesan);
        $this->db->delete('messages');
    }
    
    public function hapus_pesan_terpilih($ids){
        $this->db->where_in('id_pesan', $ids);
        $this->db->delete('messages');
    }
    
    public function search_messages_and_contacts($keyword, $user_id) {
        $this->db->select('messages.pengirim_id, users.nama_lengkap as pengirim_nama, messages.pesan, messages.created_at');
        $this->db->from('messages');
        $this->db->join('users', 'messages.pengirim_id = users.id_user');
        $this->db->where('messages.penerima_id', $user_id);
        $this->db->group_start();
        $this->db->like('users.nama_lengkap', $keyword);
        $this->db->or_like('messages.pesan', $keyword);
        $this->db->group_end();
        $this->db->order_by('messages.created_at', 'DESC');
        
        $query = $this->db->get();
    
        // Log untuk debugging query
        log_message('debug', 'Search SQL Query: ' . $this->db->last_query());
    
        return $query->result();
    }
    
    public function get_sender_name($sender_id) {
        $this->db->select('id_user, nama_lengkap'); // Ganti dengan nama kolom yang sesuai
        $this->db->where('id_user', $sender_id);
        return $this->db->get('users')->row()->nama_lengkap;
    }
    
    public function get_jumlah_pengirim($receiver_id) {
        $this->db->select('COUNT(DISTINCT messages.pengirim_id) as unique_senders');
        $this->db->from('messages');
        $this->db->where('messages.penerima_id', $receiver_id);
        
        $query = $this->db->get();
    
        // Log untuk debugging query
        log_message('debug', 'Unique Senders SQL Query: ' . $this->db->last_query());
    
        return $query->row()->unique_senders;
    }
    public function get_pengirim_names($receiver_id) {
        $this->db->select('users.nama_lengkap, users.id_user');
        $this->db->from('messages');
        $this->db->join('users', 'messages.pengirim_id = users.id_user');
        $this->db->where('messages.penerima_id', $receiver_id);
        
        $query = $this->db->get();

        // Log untuk debugging query
        log_message('debug', 'Senders List SQL Query: ' . $this->db->last_query());

        return $query->result();
    }

    public function get_unique_senders($user_id) {
        $this->db->select('pengirim_id, nama_lengkap, COUNT(*) as unread_count');
        $this->db->from('messages');
        $this->db->join('users', 'messages.pengirim_id = users.id_user');
        $this->db->where('penerima_id', $user_id);
        $this->db->where('is_read', 0);
        $this->db->group_by('pengirim_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_unread_senders($receiver_id) {
        $this->db->select('messages.pengirim_id, users.nama_lengkap as pengirim_nama, COUNT(*) as unread_count');
        $this->db->from('messages');
        $this->db->join('users', 'messages.pengirim_id = users.id_user');
        $this->db->where('messages.penerima_id', $receiver_id);
        $this->db->group_by('pengirim_id');
        $query = $this->db->get();
        return $query->result();
    }
    
    
}