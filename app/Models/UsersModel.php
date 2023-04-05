<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'name',
                          'email',
                          'password'];

    // public function getUserRole(){
    //   $sql = "SELECT *
    //           FROM receipt_item
    //          ";
    //   $query = $this->db->query($sql);
    //   return $query->getResultArray();
    // }
}

?>
