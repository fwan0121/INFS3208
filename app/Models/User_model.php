<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['remember_token', 'refresh_token', 'password_reset_token', 'password_reset_expires_at'];
    protected $db;
    protected $builder;    
    
    public function __construct() {   
        parent::__construct();
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }
    
    public function login($userid, $password) {
        $this->builder->where('user_id', $userid);
        $query = $this->builder->get();
        $user = $query->getRowArray();
        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserById($userid) {
        $this->builder->where('user_id', $userid);
        $query = $this->builder->get();
        return $query->getRowArray();
    }

    public function insertUser($userid, $email, $password, $role, $verification_code) {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            'user_id' => $userid,
            'email' => $email,
            'password' => $hashed_password,
            'role_id' => $role, //default role is student
            'verification_code' => $verification_code,
            'is_email_verified' => 1
        ];
        if ($this->builder->insert($data)) {
            return true;
        } else {
            return false;
        }
        
    }

    public function is_email_verified($user_id) {
        $this->builder->select('is_email_verified');
        $this->builder->where('user_id', $user_id);
        $query = $this->builder->get();
        $row = $query->getRow();
        return $row->is_email_verified == 1;
    }
    
    public function updateUser($userid, $data) {
        $this->builder->where('user_id', $userid);
        if ($this->builder->update($data)) {
            return true;
        } else {
            $error = $this->db->error();
            log_message('error', 'Update user error: ' . $error['message'] . ' (Code: ' . $error['code'] . ')');
            return false;
        }
    }

    public function update_refresh_token() {
        $refresh_token = "";
    
        $model = new User_model();
        $userid = session()->get('user_id');
        $data = [
            'refresh_token' => $refresh_token,
        ];
        $model->updateUser($userid, $data);
    }
    

    public function userExists($userid, $email) {
        $this->builder->where('email', $email);
        $this->builder->orWhere('user_id', $userid);
        $result = $this->builder->get()->getResult();

        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
        
    }

    public function getUserByEmail($email) {
        return $this->where('email', $email)->first();
    }

    public function getUserByResetToken($token) {
        return $this->where('password_reset_token', $token)->first();
    }


    public function storeRememberToken($userid, $token) {
        $data = [
            'remember_token' => $token,
        ];
        $this->update($userid, $data);
    }
    
    public function getUserByRememberToken($token) {
        return $this->where('remember_token', $token)->first();
    }
    
    
}
