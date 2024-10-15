<?php

namespace App\Models; // Make sure this is the Models namespace

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email','password'];
}
