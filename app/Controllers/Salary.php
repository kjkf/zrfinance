<?php

namespace App\Controllers;

class Salary extends BaseController
{
  public function __construct()
  {
    helper(['url', 'form']);
  }

  public function index()
  {
    $usersModel = new \App\Models\UsersModel();
    $salaryModel = new \App\Models\SalaryModel();
    $loggedUserID = session()->get('loggedUser');
    $userInfo = $usersModel->find($loggedUserID);

    $data = [
      'title' => 'Фонд заработной платы',
      'page_name' => 'salary_fond',
      'user' => $userInfo,
      'is_current_fzp' => $salaryModel->getCurrentMonthFZP()
    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_view', $data);
    echo view('partials/_footer', $data);
  }
  public function create_fzp() {
    $usersModel = new \App\Models\UsersModel();
    $salaryModel = new \App\Models\SalaryModel();
    $loggedUserID = session()->get('loggedUser');
    $userInfo = $usersModel->find($loggedUserID);


    $data = [
      'title' => 'СОЗДАТЬ Фонд заработной платы',
      'page_name' => 'salary_fond',
      'user' => $userInfo
    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_form', $data);
    echo view('partials/_footer', $data);
  }

  public function update_fzp($id) {
    $usersModel = new \App\Models\UsersModel();
    $salaryModel = new \App\Models\SalaryModel();
    $loggedUserID = session()->get('loggedUser');
    $userInfo = $usersModel->find($loggedUserID);


    $data = [
      'title' => 'ОБНОВИТЬ Фонд заработной платы',
      'page_name' => 'salary_fond',
      'user' => $userInfo
    ];

    echo view('partials/_header', $data);
    echo view('salary/salary_form', $data);
    echo view('partials/_footer', $data);
  }

}