<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // return view('index');
        $data=[
          'title' => 'Главная'
        ];
        //echo print_r($data['accounts']);
        echo view('partials/_header', $data);
        echo view('index');
        echo view('partials/_footer');
    }
}
