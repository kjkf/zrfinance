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
    public function save_indication_to_db($data){
      $carsModel = new \App\Models\CarsModel();
      $tablename = $data['tablename'];
      unset($data["tablename"]);

      $result = $carsModel->save_indication($data, $tablename);
      return json_encode($result);
    }
    public function save_indication() {
    //echo "!!!save_indication";
      if ($this->request->getMethod() == "post") {
        //echo "1111111";
        $formData = $this->request->getVar();
        $fileInfo = $this->request->getFile("pic");

        if (!empty($fileInfo)) {
          //echo "2222222222";
          $fileName = $fileInfo->getName();
          $nameArray = explode('.', $fileName);

          $newFileName = time().".".end($nameArray);

          if ($fileInfo->move("public/uploads/images", $newFileName)) {
            //echo "3333333333";
            //echo "File uploaded";
            $formData['pic'] = $newFileName;
            $this->save_indication_to_db($formData);
          } else {
            //echo "4444444444";
            echo "Failed to upload";
          }
        }

        return redirect()->to("cars/indication");

        
      }
    }

    public function indication()
    {
      $usersModel = new \App\Models\UsersModel();
      //$financeModel = new \App\Models\FinanceModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);

      $carsModel = new \App\Models\CarsModel();
      
      if ($userInfo['role'] == '3' || $userInfo['role'] == '4') {
        $indications = $carsModel->getIndications();
        $carInfo = Array();
        $carInfo[0] = null;
        $prev_indication = Array();
        $filter="all";
        $coupons = Array();
        $total_coupons = Array();
        $total_consuption = Array();
      } else {
        $indications = $carsModel->getIndicationsByUser($loggedUserID);
        $carInfo = $carsModel->getCarInfo($loggedUserID);
        $prev_indication = $carsModel->getPrevIndication($loggedUserID);
        $prev_indication = count($prev_indication) > 0 ? $prev_indication[0] : "";
        $coupons = $carsModel->getCouponsByUser($loggedUserID); 
        $total_coupons = $carsModel->getTotalCouponsByUser($loggedUserID); 
        $total_consuption = $carsModel->getTotalConsuptionByUser($loggedUserID); 
        $filter="byUser";
      }

      $data=[
        'title' => 'Показания',
        'page_name' => 'indication',
        'indications' => $indications,
        'car_info' => $carInfo[0],
        'prev_indication' => $prev_indication,
        'user' => $userInfo,
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role'],
        'filter' => $filter,
        'coupons' => $coupons,
        'total_coupons' => $total_coupons,
        'total_consuption' => $total_consuption
      ];
      echo view('partials/_header', $data);
      echo view('cars_indication/indication', $data);
      echo view('partials/_footer', $data);
    }

}
