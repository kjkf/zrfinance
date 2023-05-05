<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Hash;

class Auth extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }
    //shows login form
    public function index()
    {
        $data=[
          'title' => 'Authentification',
        ];
        echo view('partials/_header', $data);
        echo view('auth/login');
        echo view('partials/_footer');
    }
    //shows regisetr form
    public function register()
    {
        $data=[
          'title' => 'Register',
        ];
        echo view('partials/_header', $data);
        echo view('auth/register');
        echo view('partials/_footer');
    }
    //saves new accont
    public function save()
    {
        //validation
        $validation = $this->validate([
                                        'name'  => [
                                                      'rules' => 'required',
                                                      'errors' => ['required'=>'Your name is required']
                                                    ],
                                        'email' => [
                                                      'rules' => 'required|valid_email|is_unique[users.email]',
                                                      'errors' => ['required'=>'Email is required',
                                                                  'valid_email'=> 'VALID email is required',
                                                                  'is_unique' => 'Such email is already used in the system'
                                                                 ]
                                                    ],
                                        'password' => [
                                                      'rules' => 'required|min_length[5]|max_length[12]',
                                                      'errors' => ['required'=>'Password is required',
                                                                  'min_length'=> 'Password must have atleast 5 characters in length',
                                                                  'max_length' => 'Password must not have characters more than 12 in length'
                                                                 ]
                                                      ],
                                        'cpassword' => [
                                                      'rules' => 'required|min_length[5]|max_length[12]|matches[password]',
                                                      'errors' => ['required'=>'Confirm password is required',
                                                                  'min_length'=> 'Confirm password must have atleast 5 characters in length',
                                                                  'max_length' => 'Confirm password must not have characters more than 12 in length',
                                                                  'matches' => 'Confirm password must match to password'
                                                                ]
                                                       ],
                                      ]);
        if(!$validation){
          echo view('partials/_header');
          echo view('auth/register', ['validation'=>$this->validator]);
          echo view('partials/_footer');
        }else{
          //user registration
          $name = $this->request->getPost('name');
          $email = $this->request->getPost('email');
          $psw = $this->request->getPost('password');
          //$cpsw = $this->request->getPost('cpassword');

          $data = [
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($psw)
          ];

          $usersModel = new \App\Models\UsersModel();
          $query = $usersModel->insert($data);

          if(!$query){
            return redirect()->back()->with('fail', 'Что-то не так. Попробуйте позже');
            // return redirect()->to('register')->with('fail', 'Something is wrong. Try later.')
          }else{
            return redirect()->to('auth/register')->with('success', 'Вы зарегистрированы!');
          }

        }
    }
    //checks login user
    public function check(){
      $validation = $this->validate([
                                      'email' => [
                                                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                                                    'errors' => ['required'=>'Email is required',
                                                                'valid_email'=> 'VALID email is required',
                                                                'is_not_unique' => 'This email is not registered'
                                                               ]
                                                  ],
                                      'password' => [
                                                    'rules' => 'required|min_length[5]|max_length[12]',
                                                    'errors' => ['required'=>'Password is required',
                                                                'min_length'=> 'Password must have atleast 5 characters in length',
                                                                'max_length' => 'Password must not have characters more than 12 in length'
                                                               ]
                                                    ]
                                    ]);
      if(!$validation){
        echo view('partials/_header');
        echo view('auth/login', ['validation'=>$this->validator]);
        echo view('partials/_footer');

      }else{
        $email = $this->request->getPost('email');
        $psw = $this->request->getPost('password');

        $usersModel = new \App\Models\UsersModel();
        $user_info = $usersModel->where('email', $email)->first();
        $check_psw = Hash::check($psw, $user_info['password']);

        if(!$check_psw){
          return redirect()->back()->with('fail', 'Неверный пароль');
          // return redirect()->to('register')->with('fail', 'Something is wrong. Try later.')
        }else{
          $user_id = $user_info['id'];
          session()->set('loggedUser', $user_id);
          if ($user_info['role'] === "5") {
            return redirect()->to('salary')->with('success', 'Вы вошли в систему!');
          } else {
            return redirect()->to('dashboard')->with('success', 'Вы вошли в систему!');
          }
        }
      }
    }

    //logout function
    public function logout(){
      if(session()->has('loggedUser')){
        session()->remove('loggedUser');
        return redirect()->to('/auth?access=out')->with('fail', 'Вы вышли из системы!');
      }
    }
}
?>
