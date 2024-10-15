<?php

namespace App\Models; // Make sure this is the Models namespace

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'isbn_no', 'author','book_image','categ','genres','state_id','city_id','description'];

    public function getStates()
{
    return $this->db->table('state_tb')->get()->getResultArray();
}

public function getCitiesByState($stateId)
{
    return $this->db->table('city_tb')
        ->where('state_id', $stateId)
        ->get()
        ->getResultArray();
}

}
