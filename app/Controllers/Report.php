<?php

namespace App\Controllers;

class Report extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
      $usersModel = new \App\Models\UsersModel();
      //$financeModel = new \App\Models\FinanceModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);

      $data=[
        'title' => 'Отчёты',
        'user'=> $userInfo,
        'page_name' => 'report',
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role']
      ];
      echo view('partials/_header', $data);
      echo view('reports/report_page', $data);
      echo view('partials/_footer', $data);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets reports
    public function get_report(){
      $financeModel = new \App\Models\FinanceModel();

      $date_start = date_create($_GET['date_start']);
      $date_end = date_create($_GET['date_end']);

      $report = array();

      $report_main = $financeModel->get_reportMain($date_start, $date_end);
      $report_byGoods = $financeModel->get_reportByGoods();
      $report['main'] = $report_main;
      $report['report_byGoods'] = $report_byGoods;
      echo json_encode($report);
    }


}
