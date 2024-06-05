<?php

namespace App\Controllers;

class Coupons extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
        $this->couponsModel = new \App\Models\CouponsModel();
    }

    public function index()
    {
      $usersModel = new \App\Models\UsersModel();
      //$financeModel = new \App\Models\FinanceModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);

      $gas_reciept = $this->couponsModel->getGasReceipt();
      $issuing_base = $this->couponsModel->getIssuingBase();
      $issuing_coupons = $this->couponsModel->getIssuingCoupons();
      $issuing_total = $this->couponsModel->getIssuingTotal();
      $reciept_total = $this->couponsModel->getRsecieptTotal();
      $data=[
        'title' => 'Талоны на бензин',
        'cars' => Array(),
        'page_name' => 'coupons',
        'gas_reciept' => $gas_reciept,
        'issuing_base' => $issuing_base,
        'issuing_coupons' => $issuing_coupons,
        'user' => $userInfo,
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role'],
        'issuing_total' => $issuing_total,
        'reciept_total' => $reciept_total
      ];
      echo view('partials/_header', $data);
      echo view('cars_indication/coupons', $data);
      echo view('partials/_footer', $data);
    }

    public function add_coupons() {
      if ($this->request->getMethod() == "post") {
        $formData = $this->request->getVar();
        $result = $this->couponsModel->add_coupons($formData);
        
        return redirect()->to("coupons");
      }
    }

    public function issuing_coupons() {
      if ($this->request->getMethod() == "post") {
        //echo "1111111";
        $data = $this->request->getVar();
        //print_r($formData);
        unset($data["issuing_type"]);
        $couponsModel = new \App\Models\CouponsModel();
        
        $result = $this->couponsModel->issuing_coupons($data);
        return redirect()->to("coupons");
      }
    }
}
