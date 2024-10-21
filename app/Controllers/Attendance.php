<?php

namespace App\Controllers;
use App\Models\AttendanceModel;


class Attendance extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function index()
    {
      $db2 = db_connect("db_class");
      $attendanceModel = new AttendanceModel($db2);

      $usersModel = new \App\Models\UsersModel();
      $loggedUserID = session()->get('loggedUser');
      $userInfo = $usersModel->find($loggedUserID);

      $data=[
        'title' => 'Табель посещаемости',
        'user'=> $userInfo,
        'page_name' => 'attendance',
        'user_id' => $loggedUserID,
        'user_role' => $userInfo['role'],
        'items' => $attendanceModel->getAllDepartments()
      ];

      echo view('partials/_header', $data);
      echo view('attendance/attendance_form', $data);
      echo view('partials/_footer', $data);
    }


    public function getempattendance()
    {
      $db2 = db_connect("db_class");
      $attendanceModel = new AttendanceModel($db2);

      $depart_id = $_GET['depart_id'];
      $date_start = $_GET['date_start'];

      $contractors = $attendanceModel->getEmpAttendance($depart_id, $date_start);
      echo json_encode($contractors);
    }

}
