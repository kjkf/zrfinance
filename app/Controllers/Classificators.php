<?php

namespace App\Controllers;
use App\Models\ContractorsModel;
//use App\Models\EmployeesModel;

class Classificators extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
      $usersModel = new \App\Models\UsersModel();
      $employeesModel = new \App\Models\EmployeesModel;
      $financeModel = new \App\Models\FinanceModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);

      $working_time_balance = $this->get_time_balance();
      $time_balance = $working_time_balance['balance'];
      $balance_year = $working_time_balance['year'];

      $data=[
        'title' => 'Классификаторы',
        'user'=> $userInfo,
        'page_name' => 'classificators',
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role'],

        'companies' => $financeModel->get_all_companies(),
        'agreements' => $this->getAgreements(),
        //'contractors' => $this->(),
        'balance_for_current_year' => $time_balance,
        'balance_year' => $balance_year,
        'employees' => $employeesModel->getActiveEmployees(),
        'clasificData' => $this->get_classificators_data()
      ];

      echo view('partials/_header', $data);
      echo view('classificators/classificators_page', $data);
      echo view('partials/_footer', $data);
    }

    public function getAgreements(){
      $financeModel = new \App\Models\FinanceModel();
      $companies = $financeModel->get_all_companies();

      $agreements = array();
      $comp_agreements = array();
      foreach ($companies as $key => $company) {
        $agreements[$company['id']] = $financeModel->get_agreements($company['id']);
      }

      return $agreements;
    }

    public function get_time_balance() {
      $usersModel = new \App\Models\UsersModel();
      $date = date('Y-m-d H:i:s');
      $balance = $usersModel->get_time_balance($date);

      $year = $usersModel->get_time_balance_year();

      return array(
        'balance' => $balance,
        'year' => $year,
      );
    }

    public function get_classificators_data() {
      $employeeModel = new \App\Models\EmployeesModel();
      $contractType = $employeeModel->get_contract_type();
      $citizenship = $employeeModel->get_citizenship();
      $taxPayType = $employeeModel->get_tax_pay_type();
      $directions = $employeeModel->get_directions();
      $positions = $employeeModel->get_positions();
      $positions = $employeeModel->get_positions();
      $companies = $employeeModel->get_companies();
      $countries = $employeeModel->get_countries();
      $department = $employeeModel->get_department();

      return array(
        'contractType' => $contractType,
        'citizenship' => $citizenship,
        'taxPayType' => $taxPayType,
        'directions' => $directions,
        'positions' => $positions,
        'companies' => $companies,
        'countries' => $countries,
        'department' => $department,
      );
    }

    public function get_fired_employees() {
      $employeesModel = new \App\Models\EmployeesModel;
      $firedEmployees = $employeesModel->get_fired_employees();
      echo  json_encode($firedEmployees);
    }

    public function get_employee_byId() {
      $employeeModel = new \App\Models\EmployeesModel();
      $employee = $employeeModel->getEmployeeById();
      echo  json_encode($employee);
    }

    public function save_employee() {
      $employeeModel = new \App\Models\EmployeesModel();
      echo $employeeModel->save_employee();
    }


    public function update_employee_byId() {
      $employeeModel = new \App\Models\EmployeesModel();
      return $employeeModel->update_employee_byId();
    }

    public function update_citezenship_type() {
      $employeeModel = new \App\Models\EmployeesModel();
      return $employeeModel->update_citezenship_type();
    }
    
    
}