<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{

    function getAllDepartments() {
      $sql = "select * from department";

      $query = $this->db->query($sql);

      if (!empty($sql)) {
        $res =  $query->getResultArray();
        return $res;
      } else {
        return false;
      }
    }
    // получаем список сотрудников определенного департамента с указанием раб. времени
    function getEmpAttendance($depart_id, $date_start){
      $sql = "SELECT
              emp.id,
              emp.surname,
              emp.name,
              pos.name as position_name,
              att.week_date,
              att.hours
              FROM employee as emp
              LEFT JOIN position as pos ON pos.id = emp.position
              LEFT JOIN attendance as att ON emp.id = att.employee_id and att.week_date = ?
              WHERE emp.depart_id = ? ";

      $query = $this->db->query($sql, [$date_start, intval($depart_id)]);

      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }

    //save hours for emp
    public function save_itemDoc($emp_id, $week_date, $hours){
      $db      = \Config\Database::connect();
      $builder = $db->table($table_name);

      $data = [
        'employee_id' => $emp_id,
        'week_date' => $week_date,
        'hours' => $hours,

      ];
      $builder->insert($data);
      // $builder->set('employee_id', $path);
      // $builder->set('week_date', $path);
      // $builder->set('hours', $hours);
      // $builder->where('id', $record_id);
      return $builder->save();
      // return $builder->replace($data);
    }

}

?>
