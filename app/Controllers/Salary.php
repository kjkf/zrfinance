<?php

namespace App\Controllers;
use App\Models\SalaryModel;
class Salary extends BaseController
{
  public function __construct()
  {
    helper(['url', 'form', 'file', 'date']);
    $this->salaryModel = new SalaryModel();
  }

  public function index()
  {
    $usersModel = new \App\Models\UsersModel();
    $loggedUserID = session()->get('loggedUser');
    $userInfo = $usersModel->find($loggedUserID);

    $currentYearFZPs = $this->salaryModel->getMonthFZPs_by_year(date('Y-m-d H:i:s'));

    $data = [
      'title' => 'Фонд заработной платы',
      'page_name' => 'salary_fond',
      'user' => $userInfo,
      'is_current_fzp' => $this->salaryModel->getCurrentMonthFZP(),
      'currentYearFZPs' => $currentYearFZPs
    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_view', $data);
    echo view('partials/_footer', $data);
  }

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
      'fzp' => $fzp[0]
    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_form', $data);
    echo view('partials/_footer', $data);
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
        $employee['bonus'] = 0;
        $employee['fines'] = 0;
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

  public function update_employee_salary_calculation() {
    //d($_POST);
    $update_res =  $this->salaryModel->update_employee_salary_calculation();
    return $update_res;
  }

  public function update_fzp_status() {
    
    $update_res =  $this->salaryModel->update_fzp_status();
    return $update_res;
  }

}