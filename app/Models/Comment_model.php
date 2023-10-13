<?php
namespace App\Models;

use CodeIgniter\Model;

class Comment_model extends Model
{
    protected $table = 'course_reply';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'course_id',
        'comment',
        'created_date',
        'update_date'
    ];

    protected $db;
    protected $builder;

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table($this->table);
    }

    public function getCommentsByCourseId($course_id) {
        $this->builder->where('course_id', $course_id);
        return $this->builder->get()->getResultArray();

    }

    public function addComment($data) {
        return $this->builder->insert($data);
    }
}
