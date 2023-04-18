<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaryModel extends Model
{
  protected $table = 'salary_fzp';
  protected $primaryKey = 'id';
  protected $allowedFields = [
    'id',
    'author',
    'date_time',
    'is_approved'
  ];


  public function getCurrentMonthFZP()
  {
    $sql = "select * from salary_fzp where MONTH(`date_time`) = MONTH(now()) and YEAR(`date_time`) = YEAR(now())";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }
}
