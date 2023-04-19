<?php

namespace App\Controllers;
use App\Models\ContractorsModel;

class Classificators extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
      $usersModel = new \App\Models\UsersModel();
      $financeModel = new \App\Models\FinanceModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);

      $time_balance = $this->get_time_balance();

      $data=[
        'title' => 'Классификаторы',
        'user'=> $userInfo,
        'page_name' => 'classificators',
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role'],

        'companies' => $financeModel->get_all_companies(),
        'agreements' => $this->getAgreements(),
        //'contractors' => $this->(),
        'balance_for_current_year' => $time_balance
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

      $balance = $usersModel->get_time_balance();

      return $balance;
    }
    
}
