<?php

namespace App\Controllers;

class Dashboard extends BaseController
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
          'title' => 'Ежедневное финансовое движение',
          'page_name' => 'finance_movements',
          'user'=> $userInfo,
          'companies' => $financeModel->get_all_companies(),
          'accounts' => $financeModel->get_all_accounts(),
          'receipts_all' => $financeModel->get_all_receipts_today(),
          'expense_all' => $financeModel->get_all_expense_today(),

          'receipt_items' => $financeModel->get_all_receipt_items(),
          'expence_items' => $financeModel->get_all_expense_items(),

          'user_id' => $loggedUserID,
          'user_role' => $userInfo['role']
        ];
        //echo json_encode($data['receipts_all']);
        echo view('partials/_header', $data);
        echo view('dashboard/finance_movements', $data);
        echo view('partials/_footer', $data);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets all receipt items
    public function get_receipt_items(){
      $financeModel = new \App\Models\FinanceModel();
      $receipt_items = $financeModel->get_all_receipt_items();
      echo json_encode($receipt_items);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //get all expense items
    public function get_all_expense_items(){
      $financeModel = new \App\Models\FinanceModel();
      $expense_items = $financeModel->get_all_expense_items();
      echo json_encode($expense_items);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //get contractors by company_id and item_type
    public function get_contractors(){
      $financeModel = new \App\Models\FinanceModel();
      $company = $_GET['company_id'];
      $item_type = $_GET['item_type'];

      $contractors = $financeModel->get_contractors($company, $item_type);
      echo json_encode($contractors);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //get agreements by company_id and item_type
    public function get_agreements(){
      $financeModel = new \App\Models\FinanceModel();
      $company = $_GET['company_id'];
      $item_type = $_GET['item_type'];
      $contractor = $_GET['contractor'];

      $receipt_items = $financeModel->get_agreements($company, $contractor);
      echo json_encode($receipt_items);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //get employees by company_id and item_type
    public function get_employees(){
      $financeModel = new \App\Models\FinanceModel();
      $company = $_GET['company_id'];

      $employees = $financeModel->get_employees_byCompany($company);
      echo json_encode($employees);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //get all goods for trading and rent
    public function get_goods(){
      $financeModel = new \App\Models\FinanceModel();
      // $company = $_GET['company_id'];
      //(1, 'готовый товар'),
      //(2, 'изготовленный товар'),
      //(3, 'товар в аренду');
      $employees = $financeModel->get_goods_byType("(1,3)");
      echo json_encode($employees);
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //adds new item_type
    public function save_item(){
      
      $validation = $this->validate([
            'item_type' => [
              'rules' => 'required',
              'errors' => [
                'required' => 'Попробуйте перезагрузить страницу и сохранить запись заново.'
              ]
            ],
            'company_id' => [
              'rules' => 'required',
              'errors' => [
                'required' => 'Попробуйте перезагрузить страницу и сохранить запись заново.'
              ]
            ],
            'company_account' => [
              'rules' => 'required',
              'errors' => [
                'required' => 'Попробуйте перезагрузить страницу и сохранить запись заново.'
              ]
            ],
            'agreement_type' => [
              'rules' => 'required',
              'errors' => [
                'required' => 'Попробуйте перезагрузить страницу и сохранить запись заново.'
              ]
            ],
            'author' => [
              'rules' => 'required',
              'errors' => [
                'required' => 'Попробуйте перезагрузить страницу и сохранить запись заново.'
              ]
            ],
            'description' => [
              'rules' => 'required|min_length[10]|max_length[500]',
              'errors' => [
                'required'=>'Необходимо заполнить поле Описания',
                'min_length'=> 'Описание должно содержать не менее 20 символов.',
                'max_length' => 'Описание должно содержать не более 500 символов '
              ]
            ],
            'sum' => [
              'rules' => 'required|alpha_numeric_punct',
              'errors' => [
                'required'=>'Необходимо заполнить поле Сумма',
                'alpha_numeric_punct' => 'Поле Сумма должно содержать цифру.'
              ]
            ],
            'user_doc' => [
              'rules' => 'uploaded[user_doc]'.
                        '|mime_in[user_doc,image/jpg,image/jpeg,image/png,image/gif,image/webp,application/pdf]'.
                        '|max_size[user_doc,52000]',
              'errors' => [
                'uploaded'=>'Подтверждающий документ не загружен',
                'mime_in'=>'Загрузите подтверждающий документ подходящего формата: jpg, jpeg, png, pdf, gif, webp, pdf.',
                'max_size'=>'Подтверждающий документ слишком большой: разрешенный размер - не более 50Мб.'
              ]
            ]
          ]);
      
      if(!$validation){        
        $usersModel = new \App\Models\UsersModel();
        $financeModel = new \App\Models\FinanceModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $usersModel->find($loggedUserID);
        //echo "smth goes wrong!".validation_errors();
        $data = [
                  'title' => 'Ежедневный финансовый отчет',
                  'user' => $userInfo,
                  'user_id' => $loggedUserID,
                  'companies' => $financeModel->get_all_companies(),
                  'accounts' => $financeModel->get_all_accounts(),
                  'receipts_all' => $financeModel->get_all_receipts_today(),
                  'expense_all' => $financeModel->get_all_expense_today(),

                  'receipt_items' => $financeModel->get_all_receipt_items(),
                  'expence_items' => $financeModel->get_all_expense_items(),

                  'agreements' => $financeModel->get_agreements($_POST['company_id']),

                  'show_item_modal' => 1,
                  'validation' => $this->validator,
                ];

        echo view('partials/_header');
        echo view('dashboard/finance_movements', $data);
        echo view('partials/_footer');
      }else{
        
        $sendForRevision = false;
        $message = "Документ сохранен!";
        $status = 1;

        $financeModel = new \App\Models\FinanceModel();
        $user_id ="";
        if(session()->has('loggedUser')){
          $user_id = session()->get('loggedUser');
        }

        
        $time = new \CodeIgniter\I18n\Time;
        $now = $time::now('Asia/Almaty');
        //$now_time = $time::now('Asia/Almaty');
        // echo ($now->getHour() >= 18);
        $restrict_time_from = 8;
        $restrict_time_till = 18;
        if(($now->getHour() <= $restrict_time_from && $now->getHour() >= $restrict_time_till) && $now->getMinute() > 0){
          $status = 3;
          $message = "Документ сохранен, но изменения вступят в силу только после одобрения директора.";
        }

        
        $sum = str_replace(" ", "", $_POST['sum']);
        $sum = str_replace(",", ".", $sum);
        $employee = (isset($_POST['employee']) && !empty($_POST['employee'])) ? $_POST['employee'] : NULL;
        $goods = (isset($_POST['goods']) && !empty($_POST['goods'])) ? $_POST['goods'] : NULL;
        $save_data = [
            'company_account' => $_POST['company_account'],
            'item' => $_POST['item_name'],
            'official' => $_POST['official'],
            'description' => $_POST['description'],
            'employee' => $employee,
            // 'goods' => $goods,

            'sum' => $sum,
            'author' => $user_id,
            'document'=> '-',
            'date_time' => $now->toDateTimeString(),
            'status' => $status,
            'status_date' => $now->toDateTimeString()
        ];


        if($_POST['item_type'] == 'receipt'){
          
          $table_name = "receipt";
          $save_data['goods'] = $goods;
        }elseif($_POST['item_type'] == 'expense'){
          
          $table_name = "expense";
        }
        $agreement_type = $_POST['agreement_type'];

        if(!empty($_POST['agreement']) && $agreement_type == 'forZR' ){
          $save_data['agreement_forzr'] = $_POST['agreement'];
        }elseif(!empty($_POST['agreement']) && $agreement_type == 'fromZR'){
          $save_data['agreement_fromzr'] = $_POST['agreement'];
        }

        $receipt_items = $financeModel->save_item($table_name, $save_data);
        // session()->set('active_tab', $_POST['company_account']);
        if ($receipt_items) {
          
          //upload file
          $file = $this->validate([
              'user_doc' => [
                              'rules' => 'uploaded[user_doc]'.
                              '|mime_in[user_doc,image/jpg,image/jpeg,image/png,image/gif,image/webp,application/pdf]'.
                              '|max_size[user_doc,52000]',
                              'errors' => [
                                            'uploaded'=>'Подтверждающий документ не загружен',
                                            'mime_in'=>'Загрузите подтверждающий документ подходящего формата: jpg, jpeg, png, pdf, gif, webp, pdf.',
                                            'max_size'=>'Подтверждающий документ слишком большой: разрешенный размер - не более 50Мб.'
                                          ]
                            ],
          ]);

          if (!$file) {
            
              return redirect()->to('dashboard')->with('fail', 'Что то не так с доком. Ошибка:'.display_error($this->validator, 'user_doc'));
          } else {
            $img = $this->request->getFile('user_doc');
            $img_path = FCPATH.'/uploads';
            $img->move($img_path);

            $data = [
               'document' => $img->getName()
            ];

            $itemDoc = $financeModel->save_itemDoc($table_name, $receipt_items, $data);
            return redirect()->to('dashboard')->with('success', $message);
          }
        }else{
          echo view('partials/_header');
          echo view('dashboard/finance_movements');
          echo view('partials/_footer');
        }
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //deletes receipt/expense item, but deletion is not physical
    public function delete_item(){
      $financeModel = new \App\Models\FinanceModel();
      $validation = $this->validate([
        'status_reason' => [
                            'rules' => 'required|min_length[20]|max_length[255]',
                            'errors' => ['required'=>'Необходимо заполнить поле Описания',
                                        'min_length'=> 'Причина должна содержать не менее 20 символов.',
                                        'max_length' => 'Причина должна содержать не более 255 символов '
                            ]
                          ],
      ]);

      //
      if(!$validation){
        $usersModel = new \App\Models\UsersModel();
        $financeModel = new \App\Models\FinanceModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $usersModel->find($loggedUserID);

        $data = [
                  'title' => 'Ежедневный финансовый отчет',
                  'user' => $userInfo,
                  'user_id' => $loggedUserID,
                  'companies' => $financeModel->get_all_companies(),
                  'accounts' => $financeModel->get_all_accounts(),
                  'receipts_all' => $financeModel->get_all_receipts_today(),
                  'expense_all' => $financeModel->get_all_expense_today(),

                  'receipt_items' => $financeModel->get_all_receipt_items(),
                  'expence_items' => $financeModel->get_all_expense_items(),

                  'agreements' => $financeModel->get_agreements($_POST['company_id']),

                  'show_item_modal_delete' => 1,
                  'delete_validation' => $this->validator,
                ];

        echo view('partials/_header');
        echo view('dashboard/finance_movements', $data);
        echo view('partials/_footer');
      }else{
        $item_type = $_POST['delete_item_type'];

        $delete_data  = [
          'id' => $_POST['delete_record_id'],
          'status_reason' => $_POST['status_reason'],
        ];

        if($item_type == 'receipt'){
          $table_name = "receipt";
        }elseif($item_type == 'expense'){
          $table_name = "expense";
        }

        $del_status = $financeModel -> delete_item($table_name, $delete_data);
        //echo "table_name - ".$table_name.'; query status - '.$del_status.'; rec id  = '.$_POST['delete_record_id'];
        if ($del_status){
          $message = "Статья деактивирована и ждет подтверждения у руководителя.";
          return  redirect()->to('dashboard')->with('success', $message);
        }else{
          $message = "Не удалось деактивировать статью, попробуйте позже.";
          return  redirect()->to('dashboard')->with('fail', $message);
        }
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //upload document
    public function upload_document(){
      $file = $_GET['document'];;

      $config['upload_path']          = '/uploads/';
      $config['allowed_types']        = 'gif|jpg|png';
      $config['max_size']             = 1048576;
      $config['max_width']            = 1024;
      $config['max_height']           = 768;

      $this->load->library('upload', $config);

      if ( ! $this->upload->do_upload($file))
      {
        $error = array('error' => $this->upload->display_errors());
        echo 'there is error while uploading file';
        //$this->load->view('upload_form', $error);
      }
      else
      {
        $data = array('upload_data' => $this->upload->data());
        echo "file uploaded successfuly";
        //$this->load->view('upload_success', $data);
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //edits receipt/expense item
    public function edit_item(){
      $validation = $this->validate([
                  'new_value' => [
                    'rules' => 'required',
                    'errors' => [
                      'required' => 'Введите новове значение суммы.']],

                                      'reason' => [
                                                          'rules' => 'required|min_length[20]|max_length[500]',
                                                          'errors' => ['required'=>'Необходимо заполнить поле "Причины редактирования"',
                                                                      'min_length'=> 'Поле "Причины редактирования" должно содержать не менее 20 символов.',
                                                                      'max_length' => 'Поле "Причины редактирования" должно содержать не более 500 символов '
                                                                     ]
                                                        ],
                                      'edit_item_type' => ['rules' => 'required',
                                                      'errors' => ['required' => 'Попробуйте перезагрузить страницу и сохранить запись заново.']],
                                      'edit_company_id' => ['rules' => 'required',
                                                      'errors' => ['required' => 'Попробуйте перезагрузить страницу и сохранить запись заново.']],
                                      'user_doc' => [
                                                       'rules' => 'uploaded[user_doc]'.
                                                                  '|mime_in[user_doc,image/jpg,image/jpeg,image/png,image/gif,image/webp,application/pdf]'.
                                                                  '|max_size[user_doc,52000]',
                                                       'errors' => [
                                                                     'uploaded'=>'Подтверждающий документ не загружен',
                                                                     'mime_in'=>'Загрузите подтверждающий документ подходящего формата: jpg, jpeg, png, pdf, gif, webp, pdf.',
                                                                     'max_size'=>'Подтверждающий документ слишком большой: разрешенный размер - не более 50Мб.'
                                                                   ]
                                                   ],
                                    ]);

      
      if(!$validation){
        $usersModel = new \App\Models\UsersModel();
        $financeModel = new \App\Models\FinanceModel();
        $loggedUserID = session()->get('loggedUser');
        $userInfo = $usersModel->find($loggedUserID);

        $data = [
                  'title' => 'Ежедневный финансовый отчет',
                  'user'=> $userInfo,
                  'user_id' => $loggedUserID,

                  'item_name' => $_POST['edit_itemName'],
                  'agreement' => $_POST['edit_itemAgreement'],
                  'employee' => $_POST['edit_itemEmployee'],
                  'description' => $_POST['edit_itemDescription'],
                  'old_value' => $_POST['old_value'],
                  'record_id' => $_POST['record_id'],

                  'companies' => $financeModel->get_all_companies(),
                  'accounts' => $financeModel->get_all_accounts(),
                  'receipts_all' => $financeModel->get_all_receipts_today(),
                  'expense_all' => $financeModel->get_all_expense_today(),

                  'receipt_items' => $financeModel->get_all_receipt_items(),
                  'expence_items' => $financeModel->get_all_expense_items(),

                  'show_item_modal_edit' => 1,
                  'validation' => $this->validator,
                ];

        echo view('partials/_header');
        echo view('dashboard/finance_movements', $data);
        echo view('partials/_footer');          
      }else{
        //проверить по времени: можно ли редактировать без подтверждения
        $sendForRevision = false;
        $message = "Документ сохранен!";
        $status = 2;

        $financeModel = new \App\Models\FinanceModel();
        $user_id ="";
        if(session()->has('loggedUser')){
          $user_id = session()->get('loggedUser');
        }

        $time = new \CodeIgniter\I18n\Time;
        $now = $time::now('Asia/Almaty');

        $restrict_time_from = 8;
        $restrict_time_till = 18;
        //d(($now->getHour() <= $restrict_time_from || $now->getHour() >= $restrict_time_till) && $now->getMinute() > 0);
        if(($now->getHour() <= $restrict_time_from || $now->getHour() >= $restrict_time_till) && $now->getMinute() > 0){
          $status = 6;
          $message = "Документ сохранен, но изменения вступят в силу только после одобрения директора.";
        }

        $sum = str_replace(" ", "", $_POST['new_value']);
        $sum = str_replace(",", ".", $sum);
        $save_data = [
            'record_id' => $_POST['record_id'],
            'date_time' => $now->toDateTimeString(),
            'old_value' => $_POST['old_value'],
            'new_value' => $sum,
            'old_status' => $_POST['status'],
            'new_status' => $status,
            'reason' => $_POST['reason'],
        ];

        if($_POST['edit_item_type'] == 'receipt'){
          $table_name = "receipt_change";
          $parent_table = "receipt";
        }elseif($_POST['edit_item_type'] == 'expense'){
          $table_name = "expense_change";
          $parent_table = "expense";
        }

        $receipt_items = $financeModel->edit_item($table_name, $save_data, $status);

        if ($receipt_items){
          //change parent status

          //echo $status;
          //upload file
          $file = $this->validate([
              'user_doc' => [
                              'rules' => 'uploaded[user_doc]'.
                                         '|mime_in[user_doc,image/jpg,image/jpeg,image/png,image/gif,image/webp,application/pdf]'.
                                         '|max_size[user_doc,52000]',
                              'errors' => [
                                            'uploaded'=>'Подтверждающий документ не загружен',
                                            'mime_in'=>'Загрузите подтверждающий документ подходящего формата: jpg, jpeg, png, pdf, gif, webp, pdf.',
                                            'max_size'=>'Подтверждающий документ слишком большой: разрешенный размер - не более 50Мб.'
                                          ]
                            ],
          ]);

          if (!$file) {
              return redirect()->to('dashboard')->with('fail', 'Что то не так с доком. Ошибка:'.display_error($this->validator, 'user_doc'));
          } else {
            $img = $this->request->getFile('user_doc');
            $img_path = FCPATH.'/uploads';
            $img->move($img_path);

            $data = [
               'document' => $img->getName()
            ];

            $itemDoc = $financeModel -> save_itemDoc($table_name, $receipt_items, $data);

            $edited_data = [
              'status_date' => $now->toDateTimeString(),
              'status' => $status,
              'new_sum_value' => $sum,
              'new_document' => $img->getName(),
            ];

            $change_parentStatis = $financeModel->change_parentStatus($parent_table,$save_data['record_id'], $edited_data);

            return redirect()->to('dashboard')->with('success', $message);
          }
        }else{
          $data = [
                    'title' => 'Ежедневный финансовый отчет',
                    'item_name' => $_POST['edit_itemName'],
                    'agreement' => $_POST['edit_itemAgreement'],
                    'employee' => $_POST['edit_itemEmployee'],
                    'description' => $_POST['edit_itemDescription'],
                    'old_value' => $_POST['old_value'],
                    'record_id' => $_POST['record_id'],

                    'companies' => $financeModel->get_all_companies(),
                    'accounts' => $financeModel->get_all_accounts(),
                    'receipts_all' => $financeModel->get_all_receipts_today(),
                    'expense_all' => $financeModel->get_all_expense_today(),

                  ];
          echo view('partials/_header');
          echo view('dashboard/finance_movements');
          echo view('partials/_footer');
        }
      }
      
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets deletion history
    public function get_history(){
      $financeModel = new \App\Models\FinanceModel();

      if($_GET['item_type'] == 'receipt'){
        // $table_name = "receipt_change";
        $table_name = "receipt";
      }elseif($_GET['item_type'] == 'expense'){
        // $table_name = "expense_change";
        $table_name = "expense";
      }
      if($_GET['history_type'] == "deletion")
        $result = $financeModel -> get_history_deletion($table_name, $_GET['record_id']);
      else
        $result = $financeModel -> get_history_edition($table_name, $_GET['record_id']);

      echo json_encode($result);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function approve_deletion(){
      $financeModel = new \App\Models\FinanceModel();
      $validation = $this->validate([
                                      'history_decision' => ['rules' => 'required',
                                                            'errors' => ['required' => 'Необходимо заполнить решение.']],
                                    ]);
      $usersModel = new \App\Models\UsersModel();
      $financeModel = new \App\Models\FinanceModel();
      $loggedUserID = session()->get('loggedUser');
      if(!$validation){
        $userInfo = $usersModel->find($loggedUserID);

        $data = [
                  'title' => 'Ежедневный финансовый отчет',
                  'user'=> $userInfo,
                  'user_id' => $loggedUserID,
                  'record_id' => $_POST['record_id'],

                  'companies' => $financeModel->get_all_companies(),
                  'accounts' => $financeModel->get_all_accounts(),
                  'receipts_all' => $financeModel->get_all_receipts_today(),
                  'expense_all' => $financeModel->get_all_expense_today(),

                  'receipt_items' => $financeModel->get_all_receipt_items(),
                  'expence_items' => $financeModel->get_all_expense_items(),

                  'show_modal_history' => 1,
                  'approve_validation' => $this->validator,
                ];

        echo view('partials/_header');
        echo view('dashboard/finance_movements', $data);
        echo view('partials/_footer');
      }else{
        //1 - approved, 0-not approved
        $decision = $_POST['history_decision'];
        $item_type = $_POST['aprove_item_type'];
        $record_id = $_POST['aprove_record_id'];
        $res = $financeModel->approve_deletion($item_type, $decision, $record_id, $loggedUserID);

        return redirect()->to('dashboard')->with('success', 'good');

      }
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function approve_edition(){
      $financeModel = new \App\Models\FinanceModel();
      $validation = $this->validate([
                                      'history_decision' => ['rules' => 'required',
                                                            'errors' => ['required' => 'Необходимо заполнить решение.']],
                                    ]);
      $usersModel = new \App\Models\UsersModel();
      $financeModel = new \App\Models\FinanceModel();
      $loggedUserID = session()->get('loggedUser');

      if(!$validation){
        $userInfo = $usersModel->find($loggedUserID);

        $data = [
                  'title' => 'Ежедневный финансовый отчет',
                  'user'=> $userInfo,
                  'user_id' => $loggedUserID,
                  'record_id' => $_POST['record_id'],

                  'companies' => $financeModel->get_all_companies(),
                  'accounts' => $financeModel->get_all_accounts(),
                  'receipts_all' => $financeModel->get_all_receipts_today(),
                  'expense_all' => $financeModel->get_all_expense_today(),

                  'receipt_items' => $financeModel->get_all_receipt_items(),
                  'expence_items' => $financeModel->get_all_expense_items(),

                  'show_modal_history' => 1,
                  'new_value' => $_POST['aprove_new_value'],
                  'old_value' => $_POST['aprove_old_value'],
                  'document' => $_POST['aprove_document'],
                  'approve_validation' => $this->validator,
                ];

        echo view('partials/_header');
        echo view('dashboard/finance_movements', $data);
        echo view('partials/_footer');
      }else{
        //1 - approved, 0-not approved
        $decision = $_POST['history_decision'];
        $item_type = $_POST['aprove_item_type'];
        $record_id = $_POST['aprove_record_id'];

        $change_data = [
          'record_id' => $record_id,
          'new_value' => $_POST['aprove_new_value'],
          'old_value' => $_POST['aprove_old_value'],
          'document' => $_POST['aprove_document']
        ];
        $res = $financeModel->approve_edition($item_type, $decision, $change_data, $loggedUserID);

        return redirect()->to('dashboard')->with('success', 'good');

      }
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //approve deletion






    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //approve edition







}
