<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            if ($role == 'admin') {
                redirect('admin/dashboard');
            } elseif ($role == 'ustad') {
                redirect('ustad/dashboard');
            } else {
                redirect('publik/dashboard');
            }
        }
        $this->load->view('index');
    }

    // metode login
    public function do_login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');


        $user = $this->User_model->login($username, $password);

        if ($user) {
            $this->User_model->update_status($user->id_user, 1);
            
            $session_data = array(
                'id_user' => $user->id_user,
                'username' => $user->username,
                'role' => $user->role,
                'logged_in' => TRUE
            );
            $this->session->set_userdata($session_data);

            if ($user->role == 'admin') {
                redirect('admin/dashboard');
            } elseif ($user->role == 'ustad') {
                redirect('ustad/dashboard');
            } else {
                redirect('publik/dashboard');
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid Username or Password');
            redirect('auth/login');
        }
    }

    public function logout() {
        $user_id = $this->session->userdata('id_user');
        if ($user_id) {
            $this->User_model->update_status($user_id, 0);
        }
        
        $this->session->unset_userdata(['id_user', 'username', 'logged_in']);
        $this->session->sess_destroy();
        redirect('auth/login');
    }
    
    //register umum
    public function register() {
        $this->load->view('register');
    }

    public function register_action() {
        $nama_lengkap = $this->input->post('nama_lengkap');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $role = 'publik';  // Default role

        $data = array(
            'nama_lengkap' => $nama_lengkap,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role
        );

        $this->db->insert('users', $data);
        
        $this->db->where('username', $username);
        $user = $this->db->get('users')->row();

         // Set session data
         $session_data = array(
            'id_user' => $user->id_user,
            'nama_lengkap' => $nama_lengkap,
            'username' => $user->username,
            'email' => $email,
            'role' => $user->role,
            'logged_in' => TRUE
        );

        $this->session->set_userdata($session_data);

        // Redirect to the appropriate dashboard
        if ($user->role == 'admin') {
            redirect('admin/dashboard');
        } elseif ($user->role == 'publik') {
            redirect('publik/dashboard');
        } elseif ($user->role == 'ustad') {
            redirect('ustad/dashboard');
        }
    }
}
?>