<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get_user_by_email($email) {
        return $this->db->get_where('users', ['email' => $email])->row();
    }

    public function get_user_by_id($id) {
        $this->db->select('users.*, user_levels.level_name as lv_member');
        $this->db->from('users');
        $this->db->join('user_levels', 'users.level_id = user_levels.id', 'left');
        $this->db->where('users.id', $id);
        return $this->db->get()->row();
    }

    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data); // Mengembalikan true jika berhasil
    }
    
    public function delete_user_related_data($user_id) {
        // Hapus data terkait di tabel redeem_voucher
        $this->db->where('user_id', $user_id);
        $this->db->delete('redeem_voucher');
    }

    public function delete_user($user_id) {
        // Hapus data terkait di tabel redeem_voucher
        $this->delete_user_related_data($user_id);
        
        // Hapus data pengguna
        $this->db->where('id', $user_id);
        return $this->db->delete('users');
    }
    
    public function get_old_picture($user_id) {
        $this->db->select('profile_pic');
        return $this->db->get_where('users', ['id' => $user_id])->row()->profile_pic;
    }

    public function update_profile_picture($user_id, $filename) {
        return $this->db->where('id', $user_id)
                        ->update('users', ['profile_pic' => $filename]);
    }

    public function get_all_levels() {
        return $this->db->get('user_levels')->result();
    }

    public function get_benefits_by_level($level_id) {
        return $this->db->get_where('user_benefits', ['level_id' => $level_id])->result();
    }

    public function get_referral_code($user_id) {
        $this->db->select('referral_code');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->referral_code;
        }
        return null;
    }

    // Metode untuk memeriksa apakah kode referral sudah ada
    public function is_referral_code_exists($referral_code) {
        $this->db->where('referral_code', $referral_code);
        $query = $this->db->get('users');
        return $query->num_rows() > 0;
    }

    public function get_user_by_referral_code($referral_code) {
        return $this->db->get_where('users', ['referral_code' => $referral_code])->row();
    }

    public function insert_user($data) {
        return $this->db->insert('users', $data);
    }

    // Metode untuk menyimpan riwayat penggunaan kode referral
    public function insert_referral_redeem($data) {
        return $this->db->insert('referral_redeem', $data);
    }
}
