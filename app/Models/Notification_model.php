<?php

namespace App\Models;

use CodeIgniter\Model;

class Notification_model extends Model
{
    protected $table = 'notifications';
    protected $db;
    protected $builder;


    public function __construct() {   
        parent::__construct();
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function addNotifications($user_id, $course_id, $title, $body) {
        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'title' => $title,
            'body' => $body,
        ];
        if ($this->builder->insert($data)) {
            return true;
        } else {
            return false;
        }
        
    }

    public function getUserNotifications() {
        $this->builder->orderBy('created_at', 'desc');
        return $this->builder->get()->getResultArray();
    }
}
