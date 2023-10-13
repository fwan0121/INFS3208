<?php

namespace App\Models;

use CodeIgniter\Model;

class Course_model extends Model
{
    protected $table = 'course';
    protected $db;
    protected $builder;    
    
    public function __construct()
    {   
        parent::__construct();
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }
    
    public function getAll() {
        return $this->builder->get()->getResultArray();
    }

    public function getCourseById($id) {
        return $this->where('course_id', $id)->first();
    }

    public function searchCourses($keyword) {
        $this->builder->select('course.*, users.first_name, users.last_name, category.category_name');
        $this->builder->join('users', 'course.instructor_id = users.user_id');
        $this->builder->join('category', 'course.category_id = category.category_id');
        
        $this->builder->groupStart()
            ->like('course.title', $keyword)
            ->orLike('category.category_name', $keyword)
            ->groupEnd();
        
        $this->builder->orGroupStart()
            ->like('users.first_name', $keyword)
            ->orLike('users.last_name', $keyword)
            ->groupEnd();
        
        return $this->builder->get()->getResultArray();
    }

    public function getAutoComplete($keyword) {
        $this->builder->select('course.title, users.first_name, users.last_name, category.category_name');
        $this->builder->join('users', 'course.instructor_id = users.user_id');
        $this->builder->join('category', 'course.category_id = category.category_id');
    
        $this->builder->groupStart()
            ->like('course.title', $keyword)
            ->orLike('category.category_name', $keyword)
            ->groupEnd();
    
        $this->builder->orGroupStart()
            ->like('users.first_name', $keyword)
            ->orLike('users.last_name', $keyword)
            ->groupEnd();
    
        $this->builder->limit(10); // limit the number of keyword
        $query = $this->builder->get();
        return $query->getResultArray();
    }
    
    public function getBatch($offset, $limit, $keyword = null) {
        if ($keyword) {
            $this->searchCourses($keyword);
        }
        return $this->builder->limit($limit, $offset)->get()->getResultArray();
    }

    public function getCoursesByInstructor($instructor_id) {
        $this->builder->where('instructor_id',$instructor_id);
        return $this->builder->get()->getResultArray();
    }

    public function insertCourse($data) {
        return $this->builder->insert($data);

    }

    
}
