<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\DateTime;

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

  public function get_fired_employees()
  {
    $sql = "SELECT employee.id, concat(`surname`, ' ', `employee`.`name`) as fio, `company`.`name` as company, employee.company as companyId, `email`, `telephone`, position.name as position, department.name as department, salary, salary_fact, fire_date, pay_per_hour
              FROM `employee` 
              left join department on department.id = employee.department
              left join position on position.id = employee.position
              left join company on company.id = employee.company
              where `fire_date` is not null and employee.company in (1,2, 3, 4,5)
              order by employee.company asc,  `surname` ASC";


    $query = $this->db->query($sql);

    if (!empty($sql)) {
      $employees = $query->getResultArray();;
      $res = $this->prepareEmployeesInfo($employees);
      return $res;
    } else {
      return false;
    }
  }

  public function getActiveEmployees()
  {
    $sql = "SELECT employee.id, concat(`surname`, ' ', `employee`.`name`) as fio, `company`.`name` as company, employee.company as companyId, `email`, `telephone`, position.name as position, department.name as department, salary, salary_fact, is_tax , pay_per_hour, parttime_is_deduction, parttime_is_base, 
    case when parttime_is_base = 0 then (select empl.id from employee as empl where empl.parttime_is_base = 1 and concat(empl.`surname`, ' ', `empl`.`name`) = concat(employee.`surname`, ' ', `employee`.`name`)) else -1 end  as main_id
              FROM `employee` 
              left join department on department.id = employee.department
              left join position on position.id = employee.position
              left join company on company.id = employee.company
              where `fire_date` is null and employee.company in (1,2,3,4,5)
              order by employee.company asc,  `surname` ASC";


    $query = $this->db->query($sql);

    if (!empty($sql)) {
      $employees = $query->getResultArray();;
      $res = $this->prepareEmployeesInfo($employees);
      return $res;
    } else {
      return false;
    }
  }

  public function getEmployeeById()
  {
    $id = $_POST['trid'];
    $sql = "SELECT employee.id, `surname`, `employee`.`name`, `company`.`name` as company, employee.company as company, `email`, `telephone`, position,  department, salary_fact, salary, birth_date, middlename, start_date, fire_date, direction, `contract_type`, `is_tax`, `pay_per_hour`, resident_type.`citezenship_type`, resident_type.`country`, parttime_is_deduction, parttime_is_base
              FROM `employee` 
              left join resident_type on resident_type.`employee_id` = employee.id
              left join department on department.id = employee.department
              left join position on position.id = employee.position
              left join company on company.id = employee.company
              where employee.id = ? 
              order by employee.id asc";
    $query = $this->db->query($sql, array($id));

    if (!empty($sql)) {
      $employees = $query->getResultArray();
      //$res = $this->prepareEmployeesInfoByEmpId($employees);
      return $employees;
    } else {
      return false;
    }
  }

  public function getPartTimeInfo() {
    $id = $_POST['trid'];

    $sql = "SELECT employee.id, position, position.name as position_name, company, company.name as company_name,  department, department.name as department_name, salary, salary_fact,  start_date, fire_date, direction, parttime_is_deduction, parttime_is_base
            FROM `employee` 
            left join department on department.id = employee.department
            left join position on position.id = employee.position
            left join company on company.id = employee.company
            where  `parttime_is_base`=0 and employee.surname=(select surname from employee where employee.id = ?)
            order by employee.id asc"; //
    $query = $this->db->query($sql, array($id));

    if (!empty($sql)) {
      $employees = $query->getResultArray();
      return $employees;
    } else {
      return false;
    }
  }

  public function get_employee_byFIO() {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $middlename = $_POST['middlename'];
    $sql = "SELECT id FROM `employee` where `name`=? and `surname`=? and (`middlename` is null or `middlename`=?) and fire_date is null";
    $query = $this->db->query($sql, array($name, $surname, $middlename));

    if (!empty($sql)) {
      $employees = $query->getResultArray();
      return $employees;
    } else {
      return false;
    }
  }

  private function getDateParam($date) {
    if (empty($date)) {
      return NULL;
    } else {
      $time = strtotime($date);
      return date('Y-m-d',$time);
    }
  }

  public function save_employee() {
    $builder = $this->db->table('employee');

    $data = [
      'surname' => empty($_POST['surname']) ? NULL : $_POST['surname'],
      'name' => empty($_POST['name']) ? NULL : $_POST['name'],
      'middlename' => empty($_POST['middlename']) ? NULL : $_POST['middlename'],
      'company' => empty($_POST['company']) ? NULL : $_POST['company'],
      'telephone' => empty($_POST['telephone']) ? NULL : $_POST['telephone'],
      'email' => empty($_POST['email']) ? NULL : $_POST['email'],
      'position' => empty($_POST['position']) ? NULL : $_POST['position'],
      'direction' => empty($_POST['direction']) ? NULL : $_POST['direction'],
      'department' => empty($_POST['department']) ? NULL : $_POST['department'],
      'salary' => empty($_POST['salary']) ? 0 : $_POST['salary'],
      'salary_fact' => empty($_POST['salary_fact']) ? 0 : $_POST['salary_fact'],
      'pay_per_hour' => empty($_POST['pay_per_hour']) ? 0 : $_POST['pay_per_hour'],
      'is_tax' => empty($_POST['is_tax']) ? NULL : $_POST['is_tax'],
      'contract_type' => empty($_POST['contract_type']) ? NULL : $_POST['contract_type'],
      'fire_date' => empty($_POST['fire_date']) ? NULL : $_POST['fire_date'],
      'start_date' => empty($_POST['start_date']) ? NULL : $_POST['start_date'],
      'birth_date' => empty($_POST['birth_date']) ? NULL : $_POST['birth_date'],
      'parttime_is_deduction' => isset($_POST['parttime_is_deduction']) ? $_POST['parttime_is_deduction'] : 1,
      'parttime_is_base' => isset($_POST['parttime_is_base']) ? $_POST['parttime_is_base'] : 1,
  ];
  //$sql = $builder->set($data)->getCompiledInsert();
  //echo $sql;
  //print_r($_POST['parttime_is_base']);
  $builder->insert($data);
   $newId = $this->db->insertID();

   $country = empty($_POST['country']) ? NULL : $_POST['country'];
   $citezenship_type = empty($_POST['citezenship_type']) ? NULL : $_POST['citezenship_type'];
   $this->add_citezenship_type($newId, $citezenship_type, $country);
   $this->add_new_employee_to_existing_FZP($newId, $data['start_date'], $data['fire_date']);
   
   return $newId;
  }

  public function update_employee_byId() {
    $id = $_POST['trid'];
    $company = empty($_POST['company']) ? NULL : $_POST['company'];
    $position = empty($_POST['position']) ? NULL : $_POST['position'];
    $direction = empty($_POST['direction']) ? NULL : $_POST['direction'];
    $department = empty($_POST['department']) ? NULL : $_POST['department'];
    $is_tax = empty($_POST['is_tax']) ? NULL : $_POST['is_tax'];
    $contract_type = empty($_POST['contract_type']) ? NULL : $_POST['contract_type'];
    $fire_date = empty($_POST['fire_date']) ? NULL : $_POST['fire_date'];
    $start_date = empty($_POST['start_date']) ? NULL : $_POST['start_date'];
    $birth_date = empty($_POST['birth_date']) ? NULL : $_POST['birth_date'];
    
    $builder = $this->db->table('employee');

    $builder->set('surname', $_POST['surname']);
    $builder->set('name', $_POST['name']);
    $builder->set('middlename', $_POST['middlename']);
    $builder->set('telephone', $_POST['telephone']);
    $builder->set('email', $_POST['email']);
    $builder->set('company', $company);
    $builder->set('position', $position);
    $builder->set('direction', $direction);
    $builder->set('department', $department);
    $builder->set('salary', $_POST['salary']);
    $builder->set('salary_fact', $_POST['salary_fact']);
    $builder->set('pay_per_hour', $_POST['pay_per_hour']);
    $builder->set('is_tax', $is_tax);
    $builder->set('contract_type', $contract_type);
    $builder->set('fire_date', $fire_date);
    $builder->set('start_date', $start_date);
    $builder->set('birth_date', $birth_date);

    $builder->where('id', $id);
    //$sql = $builder->getCompiledUpdate();
    //print_r($sql);
    $res = $builder->update();
    $this->checkEmployeesInExistingFZP($id, $start_date, $fire_date);
    return $res; 
  }

  public function getEmployeeForFZP_byId($fzpId, $fzpDate, $id) {
    $sql = "SELECT employee.id as employee_id, ? as salary_fzp, employee.salary as employee_salary, employee.salary_fact as employee_salary_fact, pay_per_hour,
    CASE WHEN employee.direction = 2 THEN (select `working_6_days`*8 from working_time_balance where year = year(?) AND `month`= month(?))
    ELSE (select `w40_5d_hours` from working_time_balance where year = year(?) AND `month`= month(?))
    END AS working_hours_per_month
    from employee 
    where employee.id = ?"; 

    $query = $this->db->query($sql, array($fzpId, $fzpDate, $fzpDate, $fzpDate, $fzpDate, $id));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  private function add_new_employee_to_existing_FZP($id, $start_date, $fire_date) {
    $fzpIds = $this->get_existing_FZP_byDate($start_date, $fire_date);
    if (!empty($fzpIds)) {
      foreach($fzpIds as $fzp) {
        $fzpId = $fzp["id"];
        $fzpDate = $fzp["date_time"];
        $row = $this->getEmployeeForFZP_byId($fzpId, $fzpDate, $id);
        
        $this->addEmployeeToFZP($row);
      }
    }
  }
  private function checkEmployeesInExistingFZP($id, $start_date, $fire_date) {
    $fzpIds = $this->get_existing_FZP_byDate($start_date, $fire_date);
    $employeeFZPs = $this->get_FZP_for_currentEmployee($id);  

    echo "1111111";
    print_r($fzpIds);
    echo "222222";
    print_r($employeeFZPs);

    if (!empty($fzpIds)) {
      $fzp_ids = $this->get_vals_by_field($fzpIds, "id");
      
      $monthFzpEmployeeIds = $this->get_vals_by_field($employeeFZPs, "salary_fzp");
      
      foreach($employeeFZPs as $employeeFZP) {
        $employeeFzpId = $employeeFZP["salary_fzp"];
              
        if (!in_array($employeeFzpId, $fzp_ids)) {
          $this->deleteEmployeeFromFZP($id, $employeeFzpId);
        }
      }

      foreach($fzpIds as $fzp) {
        $fzpId = $fzp["id"];
        if (!in_array($fzpId, $monthFzpEmployeeIds)){
          $fzpDate = $fzp["date_time"];
          $row = $this->getEmployeeForFZP_byId($fzpId, $fzpDate, $id);
          
          $this->addEmployeeToFZP($row);
        }
      }
      
    } else {
      if (!empty($employeeFZPs)) {
        $this->deleteEmployeeFromAllFZP($id);
      }
    }    
  }

  private function deleteEmployeeFromFZP($id, $fzpIds) {
    $sql = "delete FROM `salary_month` where `employee_id`=? and salary_fzp = ?";

    $query = $this->db->query($sql, array($id, $fzpIds));
    return $query; 
  }

  private function deleteEmployeeFromAllFZP($id) {
    $sql = "delete FROM `salary_month` where `employee_id`=? and (select is_approved from salary_fzp where salary_fzp.id = salary_month.`salary_fzp`) <> 1";

    $query = $this->db->query($sql, array($id));
    return $query;    
  }

  private function get_vals_by_field($rows, $field) {
    $ids = array();
    foreach($rows as $row) {
      array_push($ids, $row[$field]);
    }

    return $ids;
  }

  private function addEmployeeToFZP($data) {
    $builder = $this->db->table("salary_month");
    $builder->insertBatch($data);
  }

  private function get_existing_FZP_byDate($start_date, $fire_date) {
    $sql = "SELECT * FROM `salary_fzp` where year(`date_time`) >= year(?)  and month(`date_time`) >= month(?) and is_approved <> 1";
    $args = array($start_date, $start_date);

    if (!empty($fire_date)) {
      $sql = $sql." and year(`date_time`) <= year(?) and month(`date_time`) >= month(?)";
      $args = array($start_date, $start_date, $fire_date, $fire_date);
    }

    $query = $this->db->query($sql, $args);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return array();
    }
  }

  private function get_FZP_for_currentEmployee($id) {
    //$fzpIds = implode(",", $fzpIds);
    $sql = "SELECT * FROM `salary_month` where `employee_id`=? "; //and `salary_fzp` in (?)
    $query = $this->db->query($sql, array($id));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return array();
    }
  }

  private function add_citezenship_type($id, $citezenship_type, $country) {
    $builder = $this->db->table('resident_type');
    $data = array(
      'citezenship_type' => $citezenship_type,
      'country' => $country,
      'employee_id' => $id
    );

    $res = $builder->insert($data);
    return $res;
  }
  public function update_citezenship_type() {
    $id = $_POST['trid'];
    $country = empty($_POST['country']) ? NULL : $_POST['country'];
    $citezenship_type = empty($_POST['citezenship_type']) ? NULL : $_POST['citezenship_type'];

    $builder = $this->db->table('resident_type');
    if (!empty($this->get_citizenship_by_employeeId($id))) {
      $builder->set('country', $country);
      $builder->set('citezenship_type', $citezenship_type);
  
      $builder->where('employee_id', $id);
      //$sql = $builder->getCompiledUpdate();
      //print_r($sql);
      $res = $builder->update();
    } else {
      $res = $this->add_citezenship_type($id, $citezenship_type, $country);
    }   
    
   return $res; 
  }

  private function get_citizenship_by_employeeId($id) {
    $sql = "SELECT id FROM `resident_type` where `employee_id`=? "; //and `salary_fzp` in (?)
    $query = $this->db->query($sql, array($id));

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return array();
    }
  }

  private function prepareEmployeesInfo($employees)
  {
    $ares = array();
    $pk = array();
    $td = array();
    $montaj = array();

    $pk_key = "";
    $td_key = "";
    $mon_key = "";
    $ares_key = "";
    foreach ($employees as $item) {
      if ($item['companyId'] == 2) {
        array_push($pk, $item);
        $pk_key = $item['company'];
      } else if ($item['companyId'] == 3) {
        array_push($td, $item);
        $td_key = $item['company'];
      }  else if ($item['companyId'] == 5) {
        array_push($ares, $item);
        $ares_key = $item['company'];
      } else {
        array_push($montaj, $item);
        $mon_key = $item['company'];
      }
    }
    $res = array(
      $pk_key => $pk,
      $td_key => $td,
      $mon_key => $montaj,
      $ares_key => $ares,
    );
    return $res;
  }
  public function get_companies()
  {
    $sql = " SELECT id, name from company";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function get_positions()
  {
    $sql = " SELECT id, name from position";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function get_directions()
  {
    $sql = " SELECT id, name from direction";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function get_department()
  {
    $sql = " SELECT id, name from department";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function get_tax_pay_type()
  {
    $sql = " SELECT id, tax_pay_type from tax_pay_types";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function get_citizenship()
  {
    $sql = " SELECT id, citezenship_types from citezenship_types";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function get_contract_type()
  {
    $sql = " SELECT id, contract_type from emp_contract_type";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function get_countries()
  {
    $sql = " SELECT id, country from countries";

    $query = $this->db->query($sql);

    if (!empty($sql)) {
      return $query->getResultArray();
    } else {
      return false;
    }
  }

  public function update_employee_parttime_info() {
    $id = $_POST['trid'];
    $isParttimeDeductionChanged = $_POST['isParttimeDeductionChanged'];
    $isBaseJobChanged = $_POST['isBaseJobChanged'];

    $builder = $this->db->table('employee');
//`parttime_is_deduction` `parttime_is_base`
    if ($isParttimeDeductionChanged == 1) {
      $builder->set('parttime_is_deduction', 0);  
    }
    
    if ($isBaseJobChanged == 1) {
      $builder->set('parttime_is_base', 0);  
    }

    $builder->where('id<>'+$id+' and `surname` = (select `surname` from employee where id='+$id);
      //$sql = $builder->getCompiledUpdate();
      //print_r($sql);
    $res = $builder->update();

    return $res;
    
  }
}