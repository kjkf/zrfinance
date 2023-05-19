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
    $sql = "select *, (SELECT 1 from advances_month where salary_fzp.id = `salary_fzp` GROUP by salary_fzp) as is_advance from salary_fzp where year(date_time) = year(?) and is_approved in (?) order by date_time";

    $query = $this->db->query($sql, array($year, $status));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function create_month_fzp($user) {
      $builder = $this->db->table($this->table);
      $fzp_settings = $this->getCurrentFZPSettings(date('Y-m-d H:i:s'));
      $mrp = $fzp_settings[0]['mrp'];
      $min_zp = $fzp_settings[0]['min_zp'];
      $data = [
        'author' => $user,
        'mrp' => $mrp,
        'min_zp' => $min_zp,
      ];
      $builder->insert($data);
      $insert_id =  $this->db->insertID();//$builder->insert_id();
      
      write_to_file("fzp_log", "create fzp - id=".$insert_id );
      return  $insert_id;
  }

  public function create_fzp_by_date($user, $date) {
    $builder = $this->db->table($this->table);
    $fzp_settings = $this->getCurrentFZPSettings(date('Y-m-d H:i:s'));
    $mrp = $fzp_settings[0]['mrp'];
    $min_zp = $fzp_settings[0]['min_zp'];
    $data = [
      'author' => $user,
      'date_time' => $date,
      'mrp' => $mrp,
      'min_zp' => $min_zp,
    ];
    
 //   $sql = $builder->set($data)->getCompiledInsert();
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

  public function create_month_advances($data) {
    $builder = $this->db->table("advances_month");
    
    //$sql = $builder->set($data)->getCompiledInsert();
    //print_r($sql);
    $builder->insertBatch($data);
    
  }

  public function getCurrentFZPSettings($date)
  {
    $sql = "select mrp, min_zp from salary_settings where year(date_time) = year(?)";

    $query = $this->db->query($sql, array($date));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function is_fzp_by_date($fzpMonth, $fzpYear) {
    $sql = "SELECT * FROM `salary_fzp` where month(`date_time`)=? and YEAR(`date_time`) = ?";
    $query = $this->db->query($sql, array(intval($fzpMonth), intval($fzpYear)));
    
    $res = $query->getResultArray();
   
    return $res;
  }

  public function getEmployeesInfoByCompany($company_id, $fzp_id, $date)
  {
    $sql = "SELECT employee.id, employee.name, employee.surname, employee.`email`, employee.`salary`, position.name as position, department.name as department, direction.name as direction, company.name as company, `employee_salary`, `working_hours_per_month`, `worked_hours_per_month`,  bf.bonus as bonus, bf.fines as fines, `tax_OSMS`, `tax_IPN`, `tax_OPV`, employee_salary_fact,  salary_month.advances, salary_month.holiday_pays, employee.contract_type,  resident_type.citezenship_type, is_tax, salary_month.pay_per_hour,
    CASE WHEN direction.name = 'Цех' THEN (select `working_6_days`*8 from working_time_balance where year = year(?) AND `month`= month(?))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(?) AND `month`= month(?))
END AS working_hours
    from salary_month 
    left JOIN employee on employee.id = salary_month.`employee_id`
    left join position on position.id = employee.position
    left join department on department.id = employee.`department`
    left join direction on direction.id = employee.direction
    left join company on company.id = employee.company
    left join resident_type on resident_type.employee_id = salary_month.`employee_id`
    left join (SELECT bonus_fines.`employee_id`, max(bonus_fines.`salary_fzp`) as salary_fzp, sum(`bonus`) as bonus, sum(`fines`) as fines  
FROM `bonus_fines` where bonus_fines.`salary_fzp`= ?  group by bonus_fines.`employee_id`) bf on bf.employee_id = salary_month.employee_id and salary_month.salary_fzp = bf.salary_fzp
    where salary_month.`salary_fzp`=? and employee.company in (2,3,4) and employee.company = ?
    ORDER by employee.company asc,  `surname` ASC";

    $query = $this->db->query($sql, array($date, $date, $date, $date, intval($fzp_id), intval($fzp_id), intval($company_id)));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getEmployeesInfo($fzp_id, $date)
  {
    $sql = "SELECT employee.id, employee.name, employee.surname, employee.`email`, employee.`salary`, position.name as position, department.name as department, direction.name as direction, company.name as company, employee.company as company_id, `employee_salary`, `working_hours_per_month`, `worked_hours_per_month`,  bf.bonus as bonus, bf.fines as fines, `tax_OSMS`, `tax_IPN`, `tax_OPV`, employee_salary_fact,  salary_month.advances, salary_month.holiday_pays, employee.contract_type,  resident_type.citezenship_type, is_tax, salary_month.pay_per_hour,
    CASE WHEN direction.name = 'Цех' THEN (select `working_6_days`*8 from working_time_balance where year = year(?) AND `month`= month(?))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(?) AND `month`= month(?))
END AS working_hours
    from salary_month 
    left JOIN employee on employee.id = salary_month.`employee_id`
    left join position on position.id = employee.position
    left join department on department.id = employee.`department`
    left join direction on direction.id = employee.direction
    left join company on company.id = employee.company
    left join resident_type on resident_type.employee_id = salary_month.`employee_id`
    left join (SELECT bonus_fines.`employee_id`, max(bonus_fines.`salary_fzp`) as salary_fzp, sum(`bonus`) as bonus, sum(`fines`) as fines  
FROM `bonus_fines` where bonus_fines.`salary_fzp`= ?  group by bonus_fines.`employee_id`) bf on bf.employee_id = salary_month.employee_id and salary_month.salary_fzp = bf.salary_fzp
    where salary_month.`salary_fzp`=? and employee.company in (2,3,4)
    ORDER by `surname` ASC";

    $query = $this->db->query($sql, array($date, $date, $date, $date, intval($fzp_id), intval($fzp_id)));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  //public function getCurrentYearAdvances($date)
  //{
  //  $sql = "SELECT id FROM `salary_fzp` where exists(SELECT id from advances_month where salary_fzp.id = `salary_fzp`) and year(?) = year(`date_time`)";
  //  $query = $this->db->query($sql, array($date));
  //  if (!empty($sql)) {
  //    return $query->getResultArray();
  //  } else {
  //    return false;
  //  }
  //}

  public function prepareAdvanceEmployeesInfo($fzp_id)
  {
    $sql = "SELECT employee.id, employee.name, employee.surname, employee.`email`, employee.`salary`, position.name as position, department.name as department, direction.name as direction, company.name as company, employee.company as company_id, salary_month.`employee_salary`, salary_month.`working_hours_per_month`,  salary_month.employee_salary_fact
    from advances_month 
    left JOIN employee on employee.id = advances_month.`employee_id`
    left JOIN salary_month on salary_month.id = advances_month.`employee_id` and salary_month.salary_fzp=advances_month.salary_fzp
    left join position on position.id = employee.position
    left join department on department.id = employee.`department`
    left join direction on direction.id = employee.direction
    left join company on company.id = employee.company
    where advances_month.`salary_fzp`=? and employee.company in (2,3,4)
    ORDER by `surname` ASC";

    $query = $this->db->query($sql, array(intval($fzp_id)));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function getAllEmployeesForFZP() {
    $sql = "SELECT employee.id as employee_id, (select id from salary_fzp where MONTH(`date_time`) = MONTH(now()) and YEAR(`date_time`) = YEAR(now())) as salary_fzp, employee.salary as employee_salary, employee.salary_fact as employee_salary_fact, pay_per_hour,
    CASE WHEN employee.direction = 2 THEN (select `working_6_days`*8 from working_time_balance where year = year(now()) AND `month`= month(now()))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(now()) AND `month`= month(now()))
    END AS working_hours_per_month
    from employee 
    where ((date(`fire_date`) > date(now()) ) OR `fire_date` IS NULL) and employee.company in (2,3,4) and (date(`start_date`) < date(now()) ))
    ORDER by employee.company asc,  `surname` ASC"; 

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }
  public function getAllEmployeesForFZP_by_date($date) {
    $sql = "SELECT employee.id as employee_id, (select id from salary_fzp where MONTH(`date_time`) = MONTH(?) and YEAR(`date_time`) = YEAR(?)) as salary_fzp, employee.salary as employee_salary, employee.salary_fact as employee_salary_fact,  pay_per_hour, 
    CASE WHEN employee.direction = 2 THEN (select `working_6_days`*8 from working_time_balance where year = year(?) AND `month`= month(?))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(?) AND `month`= month(?))
    END AS working_hours_per_month
    from employee 
    where ((date(`fire_date`) > date(?) ) OR `fire_date` IS NULL) and employee.company in (2,3,4) and (date(`start_date`) <  date(?) )
    ORDER by employee.company asc,  `surname` ASC";

    $query = $this->db->query($sql, array($date, $date, $date, $date, $date, $date, $date, $date));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }
  public function getAllEmployeesForAdvance_by_date($fzpid, $date) {
    $sql = "SELECT employee.id as employee_id, ? as salary_fzp
    from employee 
    where ((date(`fire_date`) > date(?) ) OR `fire_date` IS NULL) and employee.company in (2,3,4) and (date(`start_date`) <  date(?) )
    ORDER by employee.company asc,  `surname` ASC";

    $query = $this->db->query($sql, array($fzpid, $date, $date));

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
    $builder->set('advances', $_POST['advances']);
    $builder->set('holiday_pays', $_POST['holiday_pays']);
    $builder->set('tax_OSMS', $_POST['tax_OSMS']);
    $builder->set('tax_OPV', $_POST['tax_OPV']);
    $builder->set('tax_IPN', $_POST['tax_IPN']);
    
    $builder->where('employee_id', $id);
    $builder->where('salary_fzp', $fzp_id);
    $res = $builder->update();
    
    $log_info =  "employee_id: ".$id."; worked_hours_per_month:".$_POST['worked_hours_per_month'];
    //d( $log_info);
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

  public function getBonusFinesTypes() {
    $sql = "select * from bonus_fines_types";
    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function addAdvance() {
    $employee_id = $_POST['employee_id'];
    $salary_fzp = $_POST['salary_fzp'];
    $date_time = $_POST['date_time'];
    $advances = $_POST['advances'];
    
    $builder = $this->db->table("advances");
    $data = [
      'salary_fzp' => $salary_fzp,
      'advances' => $advances,
      'date_time' => $date_time,
      'employee_id' => $employee_id,
    ];
    $builder->insert($data);
    $insert_id =  $this->db->insertID();//$builder->insert_id();

    //print_r($insert_id);

    return $insert_id;
  }
  public function add_bonus_fines() {
    $type_id = $_POST['type_id'];
    $bonus = $_POST['bonus'];
    $fines = $_POST['fines'];
    $employee_id = $_POST['employee_id'];
    $salary_fzp = $_POST['salary_fzp'];
    
    $builder = $this->db->table("bonus_fines");
    $data = [
      'salary_fzp' => $salary_fzp,
      'type_id' => $type_id,
      'bonus' => $bonus,
      'fines' => $fines,
      'employee_id' => $employee_id,
    ];
    $builder->insert($data);
    $insert_id =  $this->db->insertID();//$builder->insert_id();

    //print_r($insert_id);

    return $insert_id;
  }

  public function getBonusFines_byEmployeeId($emp_id, $fzp_id) {
    //TO DO переделать запрос так, чтобы один запрос вытаскивал бонусы\штрафы для всех сотрудников по фзп, а потом уже из результтата этого запроса искать бонусы\штрафы для конкретного сотрудника
    $sql = " SELECT bonus_fines.id, bonus_fines.`bonus`, bonus_fines.`fines`, bonus_fines.`type_id`, bonus_fines_types.`type`, bonus_fines_types.name FROM `bonus_fines` left join bonus_fines_types on bonus_fines_types.id=bonus_fines.type_id  where employee_id = ? and salary_fzp=?";

    $query = $this->db->query($sql, array($emp_id, $fzp_id));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function update_bonus_fines() {
    $id = $_POST['id'];
    $type = $_POST['type'];

    $builder = $this->db->table('bonus_fines');
    $builder->set($type, $_POST['newVal']);
    
    $builder->where('id', $id);
    $res = $builder->update();
   return $res; 
  }

  public function delet_bonus_fines() {
    $id = $_POST['id'];
    
    $builder = $this->db->table('bonus_fines');
    $builder->where('id', $id);
    
    return $builder->delete();
  }
  
}