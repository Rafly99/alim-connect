<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ustad extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'ustad') {
            redirect('auth/login');
        }
		$this->load->model('Pesan_model');
        $this->load->model('User_model');
	}

	public function dashboard(){
		
		$ustad_id = $this->session->userdata('id_user');

		// Log untuk debugging sesi
        log_message('debug', 'Session ID User: ' . $ustad_id);
		
		$data['kontak'] = $this->Pesan_model->get_kontak($ustad_id);
		$data['jumlah_pesan_id'] = $this->Pesan_model->get_jumlah_pesan_berdasarkan_id($ustad_id);
		
		$data['message_view'] = 'ustad/dashboard'; // Nama view untuk konten utama (default)
		
		// Pastikan ustad_id tidak null
        if (!$ustad_id) {
            show_error('ID Ustad tidak ditemukan di sesi.', 500, 'Kesalahan Server');
        }
		// Log untuk debugging
        log_message('debug', 'Pesan: ' . print_r($data['kontak'], true));

        $this->load->view('ustad/template', $data);
	}


	public function view_message($sender_id) {
		$receiver_id = $this->session->userdata('id_user');
		$messages = $this->Pesan_model->get_messages_by_sender($sender_id, $receiver_id);
		
        
		if (!empty($messages)) {
			foreach ($messages as $message) {
				if	($message->penerima_id == $receiver_id){
					$this->Pesan_model->mark_message_as_read($message->id_pesan);
				}
			}
            $data['messages'] = $messages;
			$data['message_view'] = 'ustad/message';
			$data['jumlah_pesan_id'] = $this->Pesan_model->get_jumlah_pesan_berdasarkan_id($receiver_id);
			$data['status'] = $this->User_model->get_user_by_id($sender_id);
			
            $this->load->view('ustad/template', $data);
        } else {
            show_error('Pesan tidak ditemukan.', 404, 'Pesan Tidak Ditemukan');
        }
	}

	public function mark_as_read() {
		$receiver_id = $this->session->userdata('id_user');
		$sender_id = $this->input->post('sender_id');
	
		if ($receiver_id && $sender_id) {
			$this->Pesan_model->mark_messages_as_read($receiver_id, $sender_id);
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
		}
	}
	
	public function send_message() {
		require BASEPATH . '../vendor/autoload.php';
		date_default_timezone_set('Asia/Jakarta');
	
		$receiver_id = $this->input->post('receiver_id');
		$pesan = $this->input->post('pesan');
		$sender_id = $this->session->userdata('id_user');
	
		if ($receiver_id && $pesan && $sender_id) {

			$sender = $this->User_model->get_user_by_id($sender_id);
        	$sender_name = $sender->nama_lengkap;	
			
			$data = array(
				'pengirim_id' => $sender_id,
				'penerima_id' => $receiver_id,
				'pesan' => $pesan,
				'is_read' => 0,  // Set pesan sebagai belum dibaca
				'created_at' => date('Y-m-d H:i:s')
			);
	
			// Simpan pesan ke database
			$this->Pesan_model->insert_message($data);
	
			// Kirim notifikasi ke Pusher
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
	
			$data_push = array(
				'message' => $pesan,
				'sender_name' => $sender_name,
				'sender_id' => $sender_id,
				'receiver_id' => $receiver_id,
				'created_at' => date('Y-m-d H:i:s')
			);
			$channel = 'public-chat';
			$pusher->trigger($channel, 'my-event', $data_push);
	
			// Redirect ke halaman pesan
			redirect('ustad/view_message/' . $receiver_id);
		} else {
			show_error('Pesan atau ID penerima tidak valid.', 400, 'Kesalahan Pengiriman Pesan');
		}
	}
	

	public function delete_chat($sender_id) {
		$receiver_id = $this->session->userdata('id_user');
		if ($sender_id && $receiver_id) {
			$this->Pesan_model->delete_messages_by_sender($sender_id, $receiver_id);
			// Setelah menghapus pesan, kembali ke dashboard
			redirect('ustad/dashboard');
		} else {
			show_error('ID Pengirim atau Penerima tidak valid.', 400, 'Kesalahan Penghapusan Pesan');
		}
	}
	
	public function search() {
		$keyword = $this->input->post('keyword');
		$user_id = $this->session->userdata('id_user');

		if ($keyword) {
			$results = $this->Pesan_model->search_messages_and_contacts($keyword, $user_id);
			echo json_encode($results);
		} else {
			echo json_encode([]);
		}
	}
	public function update_contact_list() {
		$ustad_id = $this->session->userdata('id_user');
		// Ambil data dari model
		$data['jumlah_pesan_id'] = $this->Pesan_model->get_jumlah_pesan_berdasarkan_id($ustad_id);
	
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