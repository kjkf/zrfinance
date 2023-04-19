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

  public function create_month_fzp($user) {
      $builder = $this->db->table($this->table);
      $data = [
        'author' => $user
      ];
      $builder->insert($data);
      $insert_id =  $this->db->insertID();//$builder->insert_id();
      
      return  $insert_id;
  }

  public function create_month_salary($data) {
    $builder = $this->db->table("salary_month");
    
    $builder->insertBatch($data);
    
  }

  public function getEmployeesInfo($company_id)
  {
    $sql = "SELECT employee.id, employee.name, employee.surname, employee.`email`, employee.`salary`, position.name as position, department.name as department, direction.name as direction, company.name as company
    from employee 
    left join position on position.id = employee.position
    left join department on department.id = employee.`department`
    left join direction on direction.id = employee.direction
    left join company on company.id = employee.company
    where `fire_date` is null and employee.company in (2,3,4) and employee.company = ?
    ORDER by employee.company asc,  `surname` ASC";

    $query = $this->db->query($sql, array(intval($company_id)));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getAllEmployeesForFZP() {
    $sql = "SELECT employee.id as employee_id, (select id from salary_fzp where MONTH(`date_time`) = MONTH(now()) and YEAR(`date_time`) = YEAR(now())) as salary_fzp, employee.salary as employee_salary, 160 as working_hours_per_month
    from employee 
    where `fire_date` is null and employee.company in (2,3,4)
    ORDER by employee.company asc,  `surname` ASC";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getCompaniesInfo() {
    $sql = "select * from company where id in (2,3,4)";
    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function update_employee_salary_calculation() {
    $id = $_POST['id'];
    $fzp_id = $_POST['fzp_id'];
    //print_r($_POST['worked_hours_per_month']);
    $builder = $this->db->table('salary_month');
    $builder->set('worked_hours_per_month', $_POST['worked_hours_per_month']);
    $builder->set('increase_payments', $_POST['increase_payments']);
    //$builder->set('increase_explanation', $_POST['increase_explanation']);
    $builder->set('decrease_payments', $_POST['decrease_payments']);
    //$builder->set('decrease_explanation', $_POST['decrease_explanation']);
    
    $builder->where('employee_id', $id);
    $builder->where('salary_fzp', $fzp_id);
    $res = $builder->update();
    
    return $res;
    //return true;
  }
 
}