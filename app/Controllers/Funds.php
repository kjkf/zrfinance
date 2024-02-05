<?php 
namespace App\Controllers;
use App\Models\FundsModel;
use CodeIgniter\I18n\Time;

class Funds extends BaseController
{
  var $userInfo;
  public function __construct()
  {
    helper(['url', 'form', 'file', 'date']);
    $usersModel = new \App\Models\UsersModel();
    $loggedUserID = session()->get('loggedUser');
    $this->fundsModel = new FundsModel();
    $this->userInfo = $usersModel->find($loggedUserID);
  }

    public function index()
    {
      $usersModel = new \App\Models\UsersModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);
      $data = [
        'title' => 'Аналитика',
        'page_name' => 'funds',
        'user' => $userInfo
  
      ];
        //return view('index');
        echo view('partials/_header', $data);
        echo view('analytics/reports', $data);
        echo view('partials/_footer', $data);
    }
    
    public function importCsvToDb()
    {
      $usersModel = new \App\Models\UsersModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);
      $data = [
        'title' => 'Аналитика',
        'page_name' => 'funds',
        'user' => $userInfo
  
      ];
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
            echo view('partials/_header', $data);
            echo view('analytics/reports', $data);
            echo view('partials/_footer', $data);
        } else{
          
            if($file = $this->request->getFile('file')) {
          
            if ($file->isValid() && ! $file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('public/csvfile', $newName);
                $file = fopen("public/csvfile/".$newName,"r");
                
                $i = 0;
                $numberOfFields = 7;
                $csvArr = array();
                
                $filedata = fgetcsv($file, 1000, ",");
                $contractorsNotFound = array();
                
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($filedata);
                    $contractor = $this->prepareContractorName($filedata[4]);
                    $contractorInfo = $this->fundsModel->get_contractor_info($contractor);
                    if ($contractorInfo) {
                      $current_contr = $contractorInfo[0];
                      
                      $dd = explode(" ", $filedata[0]);
                      $timestamp = date_create_from_format("m/j/Y", $dd[0]);
                      
                      if($i > 0 && $num == $numberOfFields){ 
                        $csvArr[$i]['date'] = $timestamp->format('Y-m-d');
                        $csvArr[$i]['number'] = $filedata[1];
                        $csvArr[$i]['operation_type'] = $filedata[2];
                        $csvArr[$i]['sum'] = $filedata[3];
                        $csvArr[$i]['contractor'] = $current_contr->id;
                        $csvArr[$i]['expense_type'] = $current_contr->expense_type;
                        $csvArr[$i]['author'] = $filedata[5];
                        $csvArr[$i]['comments'] = $filedata[6];
                      }
                    } else {
                      array_push($contractorsNotFound, $contractor);                      
                    }
                    
                    $i++;
                }
                
                fclose($file);
                $count = count($csvArr);
                $this->fundsModel->insertAnalytics($csvArr);

                $contructorList = implode(",", $contractorsNotFound);
                session()->setFlashdata('message', $count.' rows successfully added.');
                session()->setFlashdata('alert-class', 'alert-success');
                session()->setFlashdata('contractorsNotFound', $contructorList);
            }
            else{
                session()->setFlashdata('message', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
            session()->setFlashdata('message', 'CSV file coud not be imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('analytics');         
    }

    private function prepareContractorName($text) {
      $text = str_replace(array('ТОО', 'ИП'),'', $text);
      return trim($text);
    }

    public function create_expense_report() {
      $date_start = $_POST['date_start'];
      $date_end = $_POST['date_end'];

      $timestamp_start = date_create_from_format("j.m.Y", $date_start);
      $timestamp_end = date_create_from_format("j.m.Y", $date_end);

      $res = array();
      $info = array();
      $data = $this->fundsModel->getExpensesByDatePeriodAndExpenseType($timestamp_start->format('Y-m-d'), $timestamp_end->format('Y-m-d'));
      //$expense_types = $this->fundsModel->get_expense_types();
      if ($data) {
        $res["info"] = $data;
        $res["total_sum"]= $this->fundsModel->getTotalExpensesSum($timestamp_start->format('Y-m-d'), $timestamp_end->format('Y-m-d'));
      }
      
      //return $res;
      echo json_encode($res);
    }

    public function load_expense_info() {
      $id = $_POST['expenseId'];
      $date_start = $_POST['date_start'];
      $date_end = $_POST['date_end'];

      $timestamp_start = date_create_from_format("j.m.Y", $date_start);
      $timestamp_end = date_create_from_format("j.m.Y", $date_end);

      $data = $this->fundsModel->load_expense_info($id, $timestamp_start->format('Y-m-d'), $timestamp_end->format('Y-m-d'));

      echo json_encode($data);
    }
}