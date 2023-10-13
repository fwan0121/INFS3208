<?php

namespace App\Models;

use CodeIgniter\Model;

class Cart_model extends Model {

    protected $table = 'shopping_cart';
    protected $db;
    protected $builder;

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table($this->table);
    }

    public function addToCart($userId, $courseId) {
        $data = [
            'user_id' => $userId,
            'course_id' => $courseId
        ];
        if ($this->builder->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function removeFromCart($userId, $courseId) {
        $this->builder->where('user_id', $userId)->where('course_id', $courseId)->delete();
        return true;
    }

    public function getCartItems($userId) {
        $this->builder->select('shopping_cart.course_id, course.title, course.course_brief, course.course_fee');
        $this->builder->join('course', 'course.course_id = shopping_cart.course_id');
        $this->builder->where('user_id', $userId);
        return $this->builder->get()->getResultArray();
    }

    public function getCartByUserAndCourse($user_id, $course_id) {
        $this->builder->where('user_id', $user_id);
        $this->builder->where('course_id', $course_id);
        return $this->builder->get()->getRowArray();
    }


}
