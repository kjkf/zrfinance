<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeesModel extends Model
{
    protected $table = 'employee';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 
      'name',
      'surname',
      'email',
      'position',
      'department',
      'company',
      'salary',
      'salary_fact',
      'telephone',
      'is_fired',
      'fire_date'
    ];

    public function getActiveEmployees() {
      $sql = "SELECT concat(`surname`, ' ', `employee`.`name`) as fio, `company`.`name` as company, employee.company as companyId, `email`, `telephone`, position.name as position, department.name as department, salary, salary_fact
              FROM `employee` 
              left join department on department.id = employee.department
              left join position on position.id = employee.position
              left join company on company.id = employee.company
              where `fire_date` is null and employee.company in (2, 3, 4)
              order by employee.company asc";
    

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      $employees = $query->getResultArray();;
      $res = $this->prepareEmployeesInfo($employees);
      return $res;
    } else {
      return false;
    }
  }

  private function prepareEmployeesInfo($employees) {
    $pk = array();
    $td = array();
    $montaj = array();

    $pk_key = "";
    $td_key = "";
    $mon_key = "";
    foreach($employees as $item) {
      if ($item['companyId'] == 2) {
        array_push($pk, $item);
        $pk_key = $item['company'];
      } else if ($item['companyId'] == 3) {
        array_push($td, $item);
        $td_key = $item['company'];
      } else {
        array_push($montaj, $item);
        $mon_key = $item['company'];
      }
    }
    $res = array(
      $pk_key => $pk,
      $td_key => $td,
      $mon_key => $montaj,
    );
    return $res;
  }
  
}

?>
