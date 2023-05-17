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
              where `fire_date` is not null and employee.company in (2, 3, 4)
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
    $sql = "SELECT employee.id, concat(`surname`, ' ', `employee`.`name`) as fio, `company`.`name` as company, employee.company as companyId, `email`, `telephone`, position.name as position, department.name as department, salary, salary_fact, is_tax , pay_per_hour
              FROM `employee` 
              left join department on department.id = employee.department
              left join position on position.id = employee.position
              left join company on company.id = employee.company
              where `fire_date` is null and employee.company in (2, 3, 4)
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
    $sql = "SELECT employee.id, `surname`, `employee`.`name`, `company`.`name` as company, employee.company as company, `email`, `telephone`, position,  department, salary, salary_fact, salary, birth_date, middlename, start_date, fire_date, direction, `contract_type`, `is_tax`, `pay_per_hour`, resident_type.`citezenship_type`, resident_type.`country`
              FROM `employee` 
              left join resident_type on resident_type.`employee_id` = employee.id
              left join department on department.id = employee.department
              left join position on position.id = employee.position
              left join company on company.id = employee.company
              where employee.id = ?
              order by employee.id asc";
    $query = $this->db->query($sql, array($id));

    if (!empty($sql)) {
      $employees = $query->getResultArray();;
      //$res = $this->prepareEmployeesInfoByEmpId($employees);
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

  public function update_employee_byId() {
    $id = $_POST['trid'];
    $company = empty($_POST['company']) ? NULL : $_POST['company'];
    $position = empty($_POST['position']) ? NULL : $_POST['position'];
    $direction = empty($_POST['direction']) ? NULL : $_POST['direction'];
    $department = empty($_POST['department']) ? NULL : $_POST['department'];
    $department = empty($_POST['department']) ? NULL : $_POST['department'];
    $is_tax = empty($_POST['is_tax']) ? NULL : $_POST['is_tax'];
    $contract_type = empty($_POST['contract_type']) ? NULL : $_POST['contract_type'];
    $fire_date = empty($_POST['fire_date']) ? NULL : $_POST['fire_date'];
    $start_date = empty($_POST['start_date']) ? NULL : $_POST['start_date'];
    //$start_date = $this->getDateParam($_POST['start_date']);
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
   return $res; 
  }

  public function update_citezenship_type() {
    $id = $_POST['trid'];
    $country = empty($_POST['country']) ? NULL : $_POST['country'];
    $citezenship_type = empty($_POST['citezenship_type']) ? NULL : $_POST['citezenship_type'];
    
    $builder = $this->db->table('resident_type');
    $builder->set('country', $country);
    $builder->set('citezenship_type', $citezenship_type);

    $builder->where('employee_id', $id);
    //$sql = $builder->getCompiledUpdate();
    //print_r($sql);
    $res = $builder->update();
   return $res; 
  }

  //private function prepareEmployeesInfoByEmpId($employees) {
  //  $json = array();
  //  foreach ($employees as $item) {
  //    $json[$item['id']] = $item;
  //  }

  //  return $json;
  //}

  private function prepareEmployeesInfo($employees)
  {
    $pk = array();
    $td = array();
    $montaj = array();

    $pk_key = "";
    $td_key = "";
    $mon_key = "";
    foreach ($employees as $item) {
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
}
