<?php

class Autentifikasi extends CI_Controller
{

    public_function index()
    {
        //jika statusnya sudah login, maka tidak bisa mengkases 
    halaman login alias dikembalikan ke tampilan user
        if($this->session->userdata('email')){
            redirect('user');
    }

        $this->form_validation->set_rules('email', 'Alamat Email', 
'required|trim|valid_email', [
            'required' => 'Email harus diisi!',
            'valid_email => 'Email tidak benar!!,
        ]);
        $this->form_validation->set_rules('password', 'Password',
'required|trim', [
            'required' => 'Password harus diisi'
        ]);
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'login';
            $data['user'] = '';
            //kata 'login' merupakan nilai dari variabel judul dalam
array $data dikirimkan ke view aute_header
            $this->load->view('templates/auto_header', $data);
            $this->load->view('autentifikasi/login');
            $this->load->view('templates/aute-footer');
        } else {
            $this->_login();
        }
    }

private function _login()
    {
        $email = htmlspecialchars($this->input->post('email',
true));
        $password = $this->input->post('password',true);

        $user = $this->ModelUser->cekData('email' => $email])-
>row_array();

public function cekData($where = null)
    {
        return $this->db->get_where('user', $where);
    }

    //jika usernya ada
    if ($user) {
        //jika user sudah aktif
        if ($user['is_active'] == 1) {
            //cek password
            if (password_verify($password, $user['password'])) {
                $data = [
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
                ];

                $this->session->set_userdata($data);

                if ($user['role_id'] == 1) {
                    redirect('admin');
                } else {
                    if ($user['image'] == 'default,png') {
                        $this->session->set_flashdata('pesan',
'div class="alert alert-info alert-message" role="alert">Silahkan 
Ubah Profile anda untuk ubah poto profil</div>');
                        }
                        redirect('user');
                }
            } else {
                $this->session->set_flashdata('pesan', '<div
class="alert" alert-danger alert-message" role="alert">Password
salah!!</div>');
                redirect('autentifikasi');
            }
        } else {
            $this->session->set_flashdata('pesan', '<div
class="alert alert-danger alert-message" role="alert">User belum
diaktivasi!</div');
            redirect('autentifikasi');
        }
    } else {
        $this->session->set_flashdata('pesan', '<div
class="alert alert-danger alert-message" role="alert">email tidak
terdaftar!!</div>');
        redirect('autentifikasi');
    }
}
