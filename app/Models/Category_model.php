<?php

namespace App\Models;

use CodeIgniter\Model;

class Category_model extends Model
{
    // protected $table = 'category';
    // protected $primaryKey = 'category_id';
    // protected $allowedFields = ['category_name'];

    // public function getCategories() {
    //     return $this->findAll();
    // }

    protected $table = 'category';
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

}
