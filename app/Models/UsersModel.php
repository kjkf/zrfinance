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

    function get_time_balance($date) {
      $sql = "select * from working_time_balance where year = YEAR(?)";

      $query = $this->db->query($sql, array($date));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    } 
    
    function get_time_balance_year() {
      $sql = "SELECT year FROM `working_time_balance` GROUP by year order by year ";

      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

}

?>
