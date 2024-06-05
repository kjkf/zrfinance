<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n;

class CarsModel extends Model
{
    protected $table = 'cars';
    protected $cars_indication_table = 'cars_indication';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'name'];
    //$db = db_connect();

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets array of companies
    public function save_car($user, $car_name, $consumption){
      $builder = $this->db->table($this->table);
      $data = [
        'user' => $user,
        'car_name' => $car_name,
        'consumption' => $consumption,
      ];

      $builder->insert($data);
      $insert_id =  $this->db->insertID();
      return $insert_id;
    }
    public function save_indication($data, $tablename) {
      $builder = $this->db->table($tablename);
      
      $builder->insert($data);
      $insert_id =  $this->db->insertID();
      return $insert_id;
    }

    public function getActiveDrivers(){
      $sql = " SELECT id, name from users where role = (select id from roles where role='driver')";

      $query = $this->db->query($sql);

      if (!empty($sql)){
        return $query->getResultArray();
      } else {
        return false;
      }
    }
    public function getIndications() {
      $sql = "SELECT cars_indication.`car`, cars_indication.`date_time`, cars_indication.`indication`,cars_indication.`pic`, 
      cars_indication_end.`car`, cars_indication_end.`date_time` as date_time_end, cars_indication_end.`indication` as indication_end,cars_indication_end.`pic` as pic_end, 
      cars.user, cars.car_name, cars.consumption, concat(temp.name, ' ', temp.surname) as driver
            from cars_indication 
            left join cars_indication_end on cars_indication_end.date_key = cars_indication.date_key
            left join cars on cars.id = cars_indication.car
            left join (SELECT users.id, employee.name, employee.surname from employee
                   left join users on users.employee = employee.id) as temp on cars.user = temp.id
            order by cars_indication.date_time DESC;";

      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }
    public function getIndicationsByUser($user){
      $sql = "SELECT cars_indication.`car`, cars_indication.`date_time`, cars_indication.`indication`,cars_indication.`pic`, cars_indication.date_key,
      cars_indication_end.`car`, cars_indication_end.`date_time` as date_time_end, cars_indication_end.`indication` as 	   indication_end,cars_indication_end.`pic` as pic_end, 
	  employee.name, employee.surname, cars.car_name, cars.consumption
      from cars_indication 
      left join cars_indication_end on cars_indication_end.date_key = cars_indication.date_key
      left join employee on employee.id = (select employee from users where id = ?)
      left join cars on cars.id=cars_indication.car
      where cars_indication.car in (select id from cars where user = ?)
      order by date_time DESC";

      $query = $this->db->query($sql, array($user, $user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getCarInfo($user) {
      $sql = "select id, user, car_name, consumption from cars where user = ?";
      $query = $this->db->query($sql, array($user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getPrevIndication($user) {
      $sql = "select cars_indication.date_time, cars_indication.date_key, cars_indication.id, cars_indication.indication as indication_start, 
      cars_indication_end.date_time, cars_indication_end.id, cars_indication_end.indication as indication_end
      from cars_indication 
      left JOIN cars_indication_end on cars_indication.date_key = cars_indication_end.date_key
      where cars_indication.car in (select id from cars where user = ?) order by cars_indication.date_time desc  limit 1";
      $query = $this->db->query($sql, array($user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getCouponsByUser($user) {
      $sql = "SELECT base, date_time, quantity, money
      FROM `coupons` where base = (select employee from users where id=?)";
      $query = $this->db->query($sql, array($user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getTotalCouponsByUser($user) {
      $sql = "SELECT `base`, sum(quantity) as quantity, sum(money) as money FROM `coupons` where base = (select employee from users where id = 10) group by base ";
      $query = $this->db->query($sql, array($user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getTotalConsuptionByUser($user) {
      $sql = "SELECT  t_cars.consumption, t1.car, sum(t2.indication-t1.indication), sum(t2.indication-t1.indication)*t_cars.consumption/100 as total_consumption
      FROM
      (
            SELECT car, indication, date_key 
            FROM cars_indication
      ) AS t1
      LEFT JOIN
      (
            SELECT indication, date_key
            FROM cars_indication_end
      ) AS t2
      ON t1.date_key=t2.date_key
      left join (select id, consumption, user from cars where user=?) as t_cars on t_cars.id = t1.car
      group by t1.car
      ";
      $query = $this->db->query($sql, array($user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }
}


