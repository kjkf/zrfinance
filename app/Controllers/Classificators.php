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

      $data=[
        'title' => 'Классификаторы',
        'user'=> $userInfo,
        'page_name' => 'classificators',
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role'],

        'companies' => $financeModel->get_all_companies(),
        'agreements' => $this->getAgreements(),
        //'contractors' => $this->(),
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


    
}
