<?php 
namespace App\Controllers;
use App\Models\RequestModel;

class PurchaseRequest extends BaseController
{
  var $userInfo;
  public function __construct()
  {
    helper(['url', 'form', 'file', 'date']);
    $usersModel = new \App\Models\UsersModel();
    $loggedUserID = session()->get('loggedUser');
    $this->requestModel = new RequestModel();
    $this->userInfo = $usersModel->find($loggedUserID);
  }

    public function index()
    {
      $usersModel = new \App\Models\UsersModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);
      $data = [
        'title' => 'Заявки на закуп',
        'page_name' => 'requests_list',
        'user' => $userInfo,
        'requests' => $this->requestModel->getAllRequests()
  
      ];
        //return view('index');
        echo view('partials/_header', $data);
        echo view('manufacture/purchase_material/requests_list', $data);
        echo view('partials/_footer', $data);
    }

    public function create() {
      $usersModel = new \App\Models\UsersModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);
  
      $data = [
        'title' => 'Новая заявка',
        'page_name' => 'request',
        'user' => $userInfo
  
      ];
        //return view('index');
        echo view('partials/_header', $data);
        echo view('manufacture/deal', $data);
        echo view('partials/_footer', $data);
      
    }
}