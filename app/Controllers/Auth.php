<?php

namespace App\Controllers;
use App\Models\AkunModel;

class Auth extends BaseController
{
    public function register()
    {
        // Jika method request adalah POST
        if ($this->request->getMethod() === 'post') {
            // Validasi input
            $rules = [
                'username' => 'required|is_unique[akun.username]',
                'password' => 'required|min_length[8]',
            ];

            if (!$this->validate($rules)) {
                // Jika validasi gagal, kembali ke halaman register dengan pesan kesalahan
                return redirect()->to('/register')->withInput()->with('errors', $this->validator->getErrors());
            }

            // Simpan data pengguna ke database
            $akunModel = new AkunModel();
            $akunModel->save([
                'username' => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'nama' => $this->request->getVar('nama'),
                'role' => $this->request->getVar('role'),
            ]);

            // Redirect ke halaman login dengan pesan sukses
            return redirect()->to(base_url('login'))->with('success', 'Akun berhasil dibuat. Silakan login.');
        }

        // Tampilkan halaman register
        return view('admin/register');
    }

    public function login()
    {
        // Jika method request adalah POST
        if ($this->request->getMethod() === 'post') {
            // Validasi input
            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];

            if (!$this->validate($rules)) {
                // Jika validasi gagal, kembali ke halaman login dengan pesan kesalahan
                return redirect()->to(base_url('login'))->withInput()->with('errors', $this->validator->getErrors());
            }

            // Cari pengguna berdasarkan email
            $akunModel = new AkunModel();
            $user = $akunModel->where('username', $this->request->getVar('username'))->first();

            if ($user) {
                // Verifikasi password
                if (password_verify($this->request->getVar('password'), $user['password'])) {
                    // Set session dan redirect ke halaman dashboard atau halaman lainnya
                    session()->set('username', $user['username']);
                    session()->set('nama', $user['nama']);
                    session()->set('role', $user['role']);
                    return redirect()->to(base_url('admin'));
                }
            }

            // Jika login gagal, kembali ke halaman login dengan pesan kesalahan
            return redirect()->to(base_url('login'))->withInput()->with('error', 'Email atau password salah.');
        }

        // Tampilkan halaman login
        return view('admin/login');
    }

    public function logout()
    {
        // Hapus semua data session dan redirect ke halaman login
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}