<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
		$this->load->model('User_model');
		$this->load->model('Artikel_model');
		$this->load->model('Pesan_model');
		$this->load->library('pagination');
		$this->load->library('upload');
		
	}

	public function dashboard(){
		// Konfigurasi pagination
        $config['base_url'] = site_url('admin/dashboard'); // Ubah sesuai dengan route yang benar
		$config['total_rows'] = $this->User_model->jumlah_data_user();
		$config['per_page'] = 10; // Jumlah data per halaman
		$config['uri_segment'] = 3; // Uri segment untuk mengambil nomor halaman

		// Konfigurasi styling pagination (Opsional)
		$config['full_tag_open'] = '<ul class="pagination justify-content-end">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$data['results'] = $this->User_model->get_all_user($config['per_page'], $page);
		$data['pagination'] = $this->pagination->create_links();

		// Jumlah data yang ditampilkan saat ini
		$data['current_count'] = count($data['results']);
		// Total jumlah data
		$data['total_count'] = $config['total_rows'];
		
		$this->load->view('admin/header');
		$this->load->view('admin/dashboard', $data);
	}
	
	public function tambah_user(){
		$data = array(
			'nama_lengkap' => $this->input->post('nama_lengkap'),
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'bidang' => $this->input->post('bidang'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			'role' => $this->input->post('role')
		);

		if($this->User_model->tambah_user($data));
        redirect('admin/dashboard');
	}

	public function hapus_user($id){
		$this->User_model->hapus_user($id);
		redirect('admin/dashboard');
	}
	
	public function get_user_data(){
		$id = $this->input->get('id');
		$user_data = $this->User_model->get_user_by_id($id);
		echo json_encode($user_data);
	}
	
	
	public function update_user(){
		$id = $this->input->post('id_user'); 
		$data = array(
			'nama_lengkap' => $this->input->post('nama_lengkap'),
			'username' => $this->input->post('username'),
			'bidang' => $this->input->post('bidang'),
			'email' => $this->input->post('email'),
			'role' => $this->input->post('role')
		);
		
		$password = $this->input->post('password');
		if (!empty($password)) {
			$data['password'] = password_hash($password, PASSWORD_BCRYPT); // Hash the new password
		}

		$this->User_model->update_user($id, $data);
		echo 'success';
	}


// halaman artikel
	
	public function artikel(){
		$config['base_url'] = site_url('admin/artikel');
		$config['total_rows'] = $this->Artikel_model->jumlah_data_artikel();
		$config['per_page'] = 10; // Jumlah data per halaman
		$config['uri_segment'] = 3; // Uri segment untuk mengambil nomor halaman
	
		// Styling pagination
		$config['full_tag_open'] = '<ul class="pagination justify-content-end">';
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
	
		// Jumlah data yang ditampilkan saat ini
		$data['current_count'] = count($data['results']);
		// Total jumlah data
		$data['total_count'] = $config['total_rows'];
	
		$this->load->view('admin/header');
		$this->load->view('admin/artikel', $data);
	}

	public function tambah_artikel(){
		  // Atur konfigurasi untuk upload
		  $config['upload_path'] = './uploads/';
		  $config['allowed_types'] = 'jpg|png';
		  $config['max_size'] = 5120; // 5MB
		  $config['encrypt_name'] = TRUE;
		  $this->upload->initialize($config);

		  if (!$this->upload->do_upload('banner')) {
			  // Tampilkan pesan error jika upload gagal
			  $error = array('error' => $this->upload->display_errors());
			  $this->session->set_flashdata('error', $error['error']);
			  redirect('admin/artikel'); // Ganti dengan method yang memuat form
		  } else {
			  // Upload berhasil, ambil data file
			  $upload_data = $this->upload->data();
			  $banner = $upload_data['file_name'];
  
			  // Ambil data dari form
			  $judul = $this->input->post('judul');
			  $topik = $this->input->post('topik');
			  $deskripsi = $this->input->post('deskripsi');
  
			  // Siapkan data untuk disimpan ke database
			  $data = array(
				  'judul_artikel' => $judul,
				  'topik' => $topik,
				  'banner' => $banner,
				  'isi_artikel' => $deskripsi,
				  'created_at' => date('Y-m-d H:i:s')
			  );
  
			  // Simpan data ke database
			  $this->Artikel_model->tambah_artikel($data);
  
			  // Tampilkan pesan sukses
			  $this->session->set_flashdata('success', 'Artikel berhasil ditambahkan.');
			  redirect('admin/artikel');
			}
			
	}

	public function hapus_artikel($id){
		$this->Artikel_model->hapus_artikel($id);
		redirect('admin/artikel');
	} 

	public function get_artikel_data(){
		$id = $this->input->get('id');
		$artikel = $this->Artikel_model->get_artikel_by_id($id);
		echo json_encode($artikel);
	}

	public function update_artikel(){
		$id_artikel = $this->input->post('id_artikel');
		$judul_artikel = $this->input->post('judul_artikel');
		$topik = $this->input->post('topik');
		$deskripsi = $this->input->post('deskripsi');

		// Handle file upload if banner is updated
		if (!empty($_FILES['banner']['name'])) {
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'jpg|png';
			$config['max_size'] = 5120;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			
			if ($this->upload->do_upload('banner')) {
				$uploadData = $this->upload->data();
				$banner = $uploadData['file_name'];
			} else {
				$error = $this->upload->display_errors();
				echo "Error: " . $error; // Handle upload error
				return;
			}
		} else {
			$banner = NULL; // No new banner uploaded
		}

		$updateData = array(
			'judul_artikel' => $judul_artikel,
			'topik' => $topik,
			'isi_artikel' => $deskripsi,
		);

		if ($banner) {
			$updateData['banner'] = $banner;
		}

		$this->Artikel_model->update_artikel($id_artikel, $updateData);
		echo 'success';
	}
	
// halaman massage
	public function massage(){
		$config['base_url'] = site_url('admin/massage/'); // Ubah 'controller' menjadi nama controller Anda
		$config['total_rows'] = $this->Pesan_model->jumlah_pesan();
		$config['per_page'] = 10; // Jumlah data per halaman
		$config['uri_segment'] = 4; // Uri segment untuk mengambil nomor halaman

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$data['results'] = $this->Pesan_model->get_all_pesan($config['per_page'], $page);
		$data['pagination'] = $this->pagination->create_links();

		// Jumlah data yang ditampilkan saat ini
		if (is_array($data['results'])) {
			$data['current_count'] = count($data['results']);
		} else {
			$data['current_count'] = 0; // Set ke 0 jika bukan array
		}
		// Total jumlah data
		$data['total_count'] = $config['total_rows'];
		
		$this->load->view('admin/header');
		$this->load->view('admin/massage', $data);
	}
	
	public function hapus_pesan($id_pesan) {
        $this->Pesan_model->hapus_pesan($id_pesan);
        redirect('admin/massage');
    }

	public function hapus_pesan_terpilih(){
		$ids = $this->input->post('ids');
        if (!empty($ids)) {
            $this->Pesan_model->hapus_pesan_terpilih($ids);
        }
        redirect('admin/massage');
	}
}

?>