<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        
        if ($query->num_rows() == 1) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }
    
    public function get_all_user($limit, $start){
        $this->db->limit($limit, $start);
        
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('users');
       
       if ($query->num_rows() > 0) {
        return $query->result();
    }
    return false;
    }

    public function tambah_user($data){
        if ($this->db->insert('users', $data)){
        return true;
        } else {
            return false;
        }
    }
    
    public function hapus_user($id){
        $this->db->where('id_user', $id);
        return $this->db->delete('users');
    }
    
    public function update_user($id, $data){
        $this->db->where('id_user', $id);
        return $this->db->update('users', $data);
    }
    
    public function get_user_by_id($id){
        $query = $this->db->get_where('users', array('id_user' => $id));
        return $query->row();
    }

    public function get_ustad($id){
        $query = $this->db->get_where('users', array('id_user' => $id));
        return $query->row();
    }
    
    // Mengambil semua topik yang ada di tabel users
    public function get_all_topik() {
        $this->db->distinct();
        $this->db->select('bidang'); 
        $this->db->where('role', 'ustad');
        $query = $this->db->get('users');
        return $query->result();
    }

    // Mengambil semua ustad berdasarkan topik
    public function get_ustad_by_topik($topik) {
        
        $this->db->select('id_user, nama_lengkap'); 
        $this->db->where('role', 'ustad');
        $this->db->where('bidang', $topik);
        $query = $this->db->get('users');
        return $query->result();
    }
    
    public function jumlah_data_user(){
        return $this->db->count_all('users');
    }

    public function update_status($user_id, $status) {
        $this->db->set('status', $status);
        $this->db->where('id_user', $user_id);
        return $this->db->update('users');
    }
}
?>