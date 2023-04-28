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
    $sql = "select * from salary_fzp where MONTH(`date_time`) = MONTH(now()) and YEAR(`date_time`) = YEAR(now()) ";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getMonthFZP_by_id($id)
  {
    $sql = "select * from salary_fzp where id = ?";

    $query = $this->db->query($sql, array($id));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getMonthFZPs_by_year($year, $status)
  {
    $sql = "select * from salary_fzp where year(date_time) = year(?) and is_approved in (?) order by date_time";

    $query = $this->db->query($sql, array($year, $status));

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
      
      write_to_file("fzp_log", "create fzp - id=".$insert_id );
      return  $insert_id;
  }

  public function create_fzp_by_date($user, $date) {
    $builder = $this->db->table($this->table);
    $data = [
      'author' => $user,
      'date_time' => $date,
    ];
    
    $sql = $builder->set($data)->getCompiledInsert();
//    print_r($sql);
    $builder->insert($data);
    $insert_id =  $this->db->insertID();//
    
    write_to_file("fzp_log", "create fzp - id=".$insert_id );
    return  $insert_id;
}

  public function create_month_salary($data) {
    $builder = $this->db->table("salary_month");
    
    $builder->insertBatch($data);
    
  }

  public function is_fzp_by_date($fzpMonth, $fzpYear) {
    $sql = "SELECT * FROM `salary_fzp` where month(`date_time`)=? and YEAR(`date_time`) = ?";
    $query = $this->db->query($sql, array(intval($fzpMonth), intval($fzpYear)));
    
    $res = $query->getResultArray();
   
    return $res;
  }

  public function getEmployeesInfo($company_id, $fzp_id, $date)
  {
    $sql = "SELECT employee.id, employee.name, employee.surname, employee.`email`, employee.`salary`, position.name as position, department.name as department, direction.name as direction, company.name as company, `employee_salary`, `working_hours_per_month`, `worked_hours_per_month`, `increase_payments`, `increase_explanation`, `decrease_payments`, `decrease_explanation`, CASE
    WHEN direction.name = 'Цех' THEN (select `working_6_days`*8 from working_time_balance where year = year(?) AND `month`= month(?))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(?) AND `month`= month(?))
END AS working_hours
    from salary_month 
    left JOIN employee on employee.id = salary_month.`employee_id`
    left join position on position.id = employee.position
    left join department on department.id = employee.`department`
    left join direction on direction.id = employee.direction
    left join company on company.id = employee.company
    where `salary_fzp`=? and employee.company in (2,3,4) and employee.company = ?
    ORDER by employee.company asc,  `surname` ASC";

    $query = $this->db->query($sql, array($date, $date, $date, $date, intval($fzp_id), intval($company_id)));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getAllEmployeesForFZP() {
    $sql = "SELECT employee.id as employee_id, (select id from salary_fzp where MONTH(`date_time`) = MONTH(now()) and YEAR(`date_time`) = YEAR(now())) as salary_fzp, employee.salary as employee_salary, CASE
    WHEN employee.direction = 2 THEN (select `working_6_days`*8 from working_time_balance where year = year(now()) AND `month`= month(now()))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(now()) AND `month`= month(now()))
    END AS working_hours_per_month
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
  public function getAllEmployeesForFZP_by_date($date) {
    $sql = "SELECT employee.id as employee_id, (select id from salary_fzp where MONTH(`date_time`) = MONTH(?) and YEAR(`date_time`) = YEAR(?)) as salary_fzp, employee.salary as employee_salary, CASE
    WHEN employee.direction = 2 THEN (select `working_6_days`*8 from working_time_balance where year = year(?) AND `month`= month(?))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(?) AND `month`= month(?))
    END AS working_hours_per_month
    from employee 
    where `fire_date` is null and employee.company in (2,3,4)
    ORDER by employee.company asc,  `surname` ASC";

    $query = $this->db->query($sql, array($date, $date, $date, $date, $date, $date));

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
    
    $log_info =  "employee_id: ".$id."; worked_hours_per_month:".$_POST['worked_hours_per_month']."; increase_payments: ".$_POST['increase_payments']."; decrease_payments = ".$_POST['decrease_payments'];
    d( $log_info);
    write_to_file("fzp_log", "update_employee_salary - ".$log_info);

    return $res;
    //return true;
  }

  public function update_fzp_status() {
    $id = $_POST['id'];
    
    $builder = $this->db->table('salary_fzp');
    $builder->set('is_approved', $_POST['is_approved']);
    $builder->set('rejection_reason', $_POST['rejection_reason']);
    
    $builder->where('id', $id);
    $res = $builder->update();
    
    $log_info = $_POST['is_approved'] == 2 ? " Вернуть на доработку - ".$_POST['rejection_reason'] : ($_POST['is_approved'] == 1 ? 'утвержден' : 'на согласовании');
    write_to_file("fzp_log", "update fzp_status  - ".$log_info);
    
    return $res;
  }
 
}