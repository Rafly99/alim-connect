<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publik extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'publik') {
            redirect('auth/login');
        }
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->model('User_model');
		$this->load->model('Pesan_model');
		$this->load->model('Artikel_model');
        
    }

    public function dashboard() {
        $id_user  = $this->session->userdata('id_user');

		// Log untuk debugging sesi
        log_message('debug', 'Session ID User: ' . $id_user );

        $senders = $this->Pesan_model->get_unique_senders($id_user);

        $data['senders_list'] = $senders;
        $data['unread_senders_count'] = count($senders);
        
        $data['results'] = array_slice($this->Artikel_model->get_all_artikel(), 0, 4); //membatasi artikel yg tampil
        $data['topik'] = $this->Artikel_model->get_topik();

        // Pastikan ustad_id tidak null
        if (!$id_user) {
            show_error('ID Ustad tidak ditemukan di sesi.', 500, 'Kesalahan Server');
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('publik/dashboard', $data);
        $this->load->view('templates/footer');
    }

    public function lihat_artikel($id){
        $id_user  = $this->session->userdata('id_user');

        $senders = $this->Pesan_model->get_unique_senders($id_user);

        $data['senders_list'] = $senders;
        $data['unread_senders_count'] = count($senders);
        
        $data['artikel'] = $this->Artikel_model->get_artikel_by_id($id);

        if (empty($data['artikel'])) {
            show_404();
        }

        $data['artikel_terkait'] = $this->Artikel_model->get_artikel_terkait();
        
        $this->load->view('templates/header', $data);
        $this->load->view('publik/artikel_view', $data);
        $this->load->view('templates/footer');
    }

    public function artikel(){
        $id_user  = $this->session->userdata('id_user');

        $senders = $this->Pesan_model->get_unique_senders($id_user);

        $data['senders_list'] = $senders;
        $data['unread_senders_count'] = count($senders);
        
        $config['base_url'] = site_url('publik/artikel');
        $config['total_rows'] = $this->Artikel_model->jumlah_data_artikel();
		$config['per_page'] = 10; // Jumlah data per halaman
		$config['uri_segment'] = 3; // Uri segment untuk mengambil nomor halaman
	
		// Styling pagination
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tagl_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tagl_close'] = '</li>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tagl_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tagl_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');
	
		$this->pagination->initialize($config);
	
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	
		$data['results'] = $this->Artikel_model->get_all_artikel($config['per_page'], $page);
		$data['pagination'] = $this->pagination->create_links();
	
        $data['artikel_terkait'] = $this->Artikel_model->get_artikel_terkait();
        
        $this->load->view('templates/header', $data);
        $this->load->view('publik/artikel', $data);
        $this->load->view('templates/footer');
    }
    
    public function search() {
        $id_user  = $this->session->userdata('id_user');
        $senders = $this->Pesan_model->get_unique_senders($id_user);

        $data['senders_list'] = $senders;
        $data['unread_senders_count'] = count($senders);
        
        $query = $this->input->get('query');
        
        // Pagination setup
        $config['base_url'] = site_url('publik/search');
        $config['total_rows'] = $this->Artikel_model->count_search_results($query);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['results'] = $this->Artikel_model->search_artikel($query, $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('templates/header', $data);
        $this->load->view('publik/artikel', $data);
        $this->load->view('templates/footer');
    }

    public function select_ustad() {
        // Mengambil daftar topik dari tabel users
        $id_user  = $this->session->userdata('id_user');

        $senders = $this->Pesan_model->get_unique_senders($id_user);

        $data['senders_list'] = $senders;
        $data['unread_senders_count'] = count($senders);
        
        $data['topik'] = $this->User_model->get_all_topik();

        // var_dump($data['topik']);
        // exit();
        
        $this->load->view('templates/header', $data);
        $this->load->view('publik/select_ustad', $data);
    }

    public function kontak(){
        $id_user  = $this->session->userdata('id_user');

        $senders = $this->Pesan_model->get_unique_senders($id_user);

        $data['senders_list'] = $senders;
        $data['unread_senders_count'] = count($senders);
        
        $this->load->view('templates/header', $data);
        $this->load->view('publik/kontak');
    }

    public function get_ustad_by_topik($topik) {
        $ustad = $this->User_model->get_ustad_by_topik($topik);
        echo json_encode($ustad);
        exit();
    }
    
    public function chat_publik($id_ustad){
        $receiver_id = $this->session->userdata('id_user');

        $messages = $this->Pesan_model->get_messages_by_sender($id_ustad, $receiver_id);
        
		foreach ($messages as $message) {
			if	($message->penerima_id == $receiver_id){
				$this->Pesan_model->mark_message_as_read($message->id_pesan);
			}
		}
        if (empty($messages)) {
            // Ambil bidang pengguna
            $user = $this->User_model->get_user_by_id($id_ustad);
            $bidang = $user->bidang;

            // Ambil pesan default berdasarkan bidang
            $this->db->select('pesan_default');
            $this->db->from('default_message');
            $this->db->where('bidang', $bidang);
            $default_messages = $this->db->get()->result();
        } else {
            $default_messages = [];
        }

        $data['ustad_id'] = $id_ustad;  
        $data['sender_name'] = $this->Pesan_model->get_sender_name($id_ustad); // Mengambil nama pengirim
        $data['status'] = $this->User_model->get_user_by_id($id_ustad);
        $data['messages'] = $messages;
        $data['default_messages'] = $default_messages;
        
        $this->load->view('publik/chat', $data);
    
    }

    public function kirim_pesan() {
        require BASEPATH . '../vendor/autoload.php';
        date_default_timezone_set('Asia/Jakarta');
    
        $pengirim_id = $this->session->userdata('id_user');
        $penerima_id = $this->input->post('penerima_id');
        $pesan = $this->input->post('pesan');
    
        if (!$pengirim_id || !$penerima_id || !$pesan) {
            echo "Gagal mengirim pesan: Data tidak lengkap.";
            return; // Stop further execution
        }
    
        // Data pesan
        $data = array(
            'pengirim_id' => $pengirim_id, 
            'penerima_id' => $penerima_id,
            'pesan' => $pesan,
            'is_read' => 0, // Default is_read = 0
            'created_at' => date('Y-m-d H:i:s')
        );
    
        // Setup Pusher
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $pusher = new Pusher\Pusher(
            '8757e07d52d81616f1bb',
            '941aea76f4a0ede34918',
            '1843203',
            $options
        );
    
        // Data untuk Pusher
        $data_push = array(
            'message' => $pesan,
            'sender_id' => $pengirim_id,
            'receiver_id' => $penerima_id,
            'created_at' => $data['created_at']
        );
    
        // Trigger event Pusher
        $pusher->trigger('public-chat', 'my-event', $data_push);
    
        // Simpan pesan ke database
        if ($this->Pesan_model->insert_message($data)) {
            redirect('publik/chat_publik'); // Redirect tanpa data
        } else {
            echo "Gagal menyimpan pesan.";
        }
    }    
    
    public function delete_chat($sender_id) {
        $receiver_id = $this->session->userdata('id_user');
        
        if ($sender_id && $receiver_id) {
            $this->Pesan_model->delete_messages_by_sender($sender_id, $receiver_id);
            // Tambahkan pesan sukses atau redirect ke halaman dashboard setelah penghapusan
            redirect('publik/dashboard');
        } else {
            show_error('ID Pengirim atau Penerima tidak valid.', 400, 'Kesalahan Penghapusan Pesan');
        }
    }

    public function get_senders_list(){
        $user_id = $this->session->userdata('id_user');
    
        // Ambil daftar pengirim dan hitung jumlah pesan belum dibaca
        $senders = $this->Pesan_model->get_unique_senders($user_id);

        $data['senders_list'] = $senders;
        $data['unread_senders_count'] = count($senders);

        echo json_encode($data);
    }
    
    public function auth() {
        require BASEPATH . '../vendor/autoload.php';
        $pusher = new Pusher\Pusher(
            '8757e07d52d81616f1bb',
            '941aea76f4a0ede34918',
            '1843203',
            array('cluster' => 'ap1')
        );

        // Mengambil data dari request
        $socket_id = $this->input->post('socket_id');
        $channel_name = $this->input->post('channel_name');

            // Pastikan channel_name memiliki format yang benar
        if (strpos($channel_name, 'private-user-') === 0) {
            $auth = $pusher->authorizeChannel($channel_name, $socket_id);
            echo json_encode($auth);
        } else {
            // Error handling jika channel_name tidak valid
            echo json_encode(array('error' => 'Invalid channel name'));
        }
    }
}
?>