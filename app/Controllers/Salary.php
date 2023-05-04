<?php

namespace App\Controllers;
use App\Models\SalaryModel;
use CodeIgniter\I18n\Time;
class Salary extends BaseController
{
  var $userInfo;
  public function __construct()
  {
    helper(['url', 'form', 'file', 'date']);
    $this->salaryModel = new SalaryModel();
    $usersModel = new \App\Models\UsersModel();
    $loggedUserID = session()->get('loggedUser');
    $this->userInfo = $usersModel->find($loggedUserID);
  }

  public function index()
  {
    $usersModel = new \App\Models\UsersModel();
    $loggedUserID = session()->get('loggedUser');
    $userInfo = $usersModel->find($loggedUserID);

    $currentYearFZPs = $this->salaryModel->getMonthFZPs_by_year(date('Y-m-d H:i:s'), 1);
    $currentYearWorkingFZPs = $this->salaryModel->getMonthFZPs_by_year(date('Y-m-d H:i:s'), "0, 1, 4");

    $data = [
      'title' => 'Фонд заработной платы',
      'page_name' => 'salary_fond',
      'user' => $userInfo,
      'is_current_fzp' => $this->salaryModel->getCurrentMonthFZP(),
      'currentYearFZPs' => $currentYearFZPs,
      'currentYearWorkingFZPs' => $currentYearWorkingFZPs,

    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_view', $data);
    echo view('partials/_footer', $data);
  }

  /** create fzp for current month */
  public function create_fzp() {
    $usersModel = new \App\Models\UsersModel();
    $loggedUserID = session()->get('loggedUser');
    $userInfo = $usersModel->find($loggedUserID);


    $fzp = $this->salaryModel->getCurrentMonthFZP();

    if (!$fzp) {
      $fzp_id = $this->salaryModel->create_month_fzp($userInfo['id']);
      $this->create_month_salary();
      return  redirect()->to('salary/fzp/'.$fzp_id);
    } else {
      $fzp_id = $fzp[0]['id'];
      
      return  redirect()->to('salary/fzp/'.$fzp_id);
    }

    $companies = $this->salaryModel->getCompaniesInfo();
    $date = date('Y-m-d H:i:s');
    $employeesArr = $this->prepareEmployeesInfo($companies, $fzp_id, $date);
    $employees = $employeesArr['employees'];
    //print_r($employeesArr['json']);
    $json = json_encode($employeesArr['json']);
    
    $data = [
      'title' => 'СОЗДАТЬ Фонд заработной платы',
      'page_name' => 'salary_month',
      'user' => $userInfo, 
      'employees' => $employees,
      'employees_count' => $this->getEmployeesCount($employees),
      'month' => getMonthByNum(date("n") - 1),
      'year' => date('Y'),
      'json' => $json,
      'fzp_id' => $fzp_id,
      'fzp' => $fzp
    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_form', $data);
    echo view('partials/_footer', $data);
  }

  public function update_fzp($id) {
    
    $usersModel = new \App\Models\UsersModel();
    $loggedUserID = session()->get('loggedUser');
    $userInfo = $usersModel->find($loggedUserID);

    $companies = $this->salaryModel->getCompaniesInfo();
    $fzp = $this->salaryModel->getMonthFZP_by_id($id);
    $fzp_date = $fzp[0]['date_time'];
    $timestamp = strtotime($fzp_date);
    
    $employeesArr = $this->prepareEmployeesInfo($companies, $id, $fzp_date);
    $employees = $employeesArr['employees'];
    //print_r($employeesArr['json']);
    $json = json_encode($employeesArr['json']);

    $bonus_fines = $this->prepare_bonus_fines_types();
    
    $data = [
      'title' => 'СОЗДАТЬ Фонд заработной платы',
      'page_name' => 'salary_month',
      'user' => $userInfo, 
      'employees' => $employees,
      'employees_count' => $this->getEmployeesCount($employees),
      'month' => getMonthByNum(date("n", $timestamp) - 1),
      'year' => date('Y', $timestamp),
      'json' => $json,
      'fzp_id' => $id, 
      'fzp' => $fzp[0],
      'bonus_fines' => $bonus_fines
    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_form', $data);
    echo view('partials/_footer', $data);
  }

  public function create_fzp_by_date() {
    $fzpMonth = $_POST['fzpMonth'];
    $fzpYear = $_POST['fzpYear'];
    $fzp = $this->salaryModel->is_fzp_by_date($fzpMonth, $fzpYear);
    if (!empty($fzp)) {
      $fzp_id = $fzp[0]['id'];
      $result = "exist";
    } else {
      $time = Time::create($fzpYear, $fzpMonth, 1);
      $date = $time->toDateString();
    
     // $date = date_create($time);
      $fzp_id = $this->salaryModel->create_fzp_by_date($this->userInfo['id'], $date );
      $this->create_month_salary_by_date($date);
      $result = "new";
      
    }

    return json_encode(array(
      "fzp_id" => $fzp_id,
      "type" => $result,
    ));
  }

  private function prepareEmployeesInfo($companies, $fzp_id, $date) {
    $employees = array();
    $json = array();
    foreach($companies as $company) {
      $employeesInfo = $this->salaryModel->getEmployeesInfo($company['id'], $fzp_id, $date);
      $key = $company['id']."|".$company['name'];
      $employees[$key] = $employeesInfo;

      foreach($employeesInfo as $employee) {
        $employee['fzp_id'] = $fzp_id;
        $employee['bonus_fines'] = $this->salaryModel->getBonusFines_byEmployeeId($employee['id'], $fzp_id);
        //$employee['bonus'] = 0;
        //$employee['fines'] = 0;
        $employee['work_day_fact'] = 0;
        $employee['company_id'] = $company['id'];
        $json[$employee['id']] = $employee;
      }
    }

    return array(
      'employees'=> $employees, 
      'json' => $json
    );
  }

  private function getEmployeesCount($companies) {
    $sum = 0;
    foreach($companies as $company) {
      $sum += count($company);
    }
    return $sum;
  }

  public function create_month_salary() {
    $data = $this->salaryModel->getAllEmployeesForFZP();

    $this->salaryModel->create_month_salary($data);
  }

  public function create_month_salary_by_date($date) {
    $data = $this->salaryModel->getAllEmployeesForFZP_by_date($date);

    $this->salaryModel->create_month_salary($data);
  }

  public function update_employee_salary_calculation() {
    //d($_POST);
    $update_res =  $this->salaryModel->update_employee_salary_calculation();
    return $update_res;
  }

  public function update_fzp_status() {
    
    $update_res =  $this->salaryModel->update_fzp_status();
    return $update_res;
  }

  private function prepare_bonus_fines_types() {
    $bonus_fines = $this->salaryModel->getBonusFinesTypes();
    $bonus = array();
    $fines = array();
    foreach($bonus_fines as $item) {
      if ($item['type'] == 'bonus') {
        array_push($bonus, $item);
      } else {
        array_push($fines, $item);
      }
    }

    return array(
      "bonus" => $bonus,
      "fines" => $fines,
    );
  }

  public function add_bonus_fines() {
    $res = $this->salaryModel->add_bonus_fines();

    //print_r($res);

    echo $res;
  }

}