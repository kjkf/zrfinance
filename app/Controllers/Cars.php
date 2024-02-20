<?php

namespace App\Controllers;

class Cars extends BaseController
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

      $carsModel = new \App\Models\CarsModel();

      $data=[
        'title' => 'Машины',
        'drivers'=> $carsModel->getActiveDrivers(),
        'cars' => Array(),
        'page_name' => 'cars',
        'user' => $userInfo,
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role']
      ];
      echo view('partials/_header', $data);
      echo view('cars_indication/add_cars', $data);
      echo view('partials/_footer', $data);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets reports
    public function save_car(){
      $carsModel = new \App\Models\CarsModel();
      $user = $_POST['user'];
      $car_name = $_POST['car_name'];
      $consumption = $_POST['consumption'];

      $result = $carsModel->save_car($user, $car_name, $consumption);
      return json_encode($result);
    }


}
