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
    public function save_indication($data) {
      $builder = $this->db->table("cars_indication");
      //$data = [
      //  'pic' => $fileName,
      //  'car' => $car_id,
      //  'indication' => $indication,
      //];

      $builder->insert($data);
      $insert_id =  $this->db->insertID();
      return $insert_id;
    }

    public function getActiveDrivers(){
      $sql = " SELECT id, name from users where role = (select id from roles where role='driver')";

      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }
    public function getIndications() {
      $sql = "SELECT `car`, `date_time`, `indication`, `pic`, cars.user, cars.car_name, cars.consumption, concat(temp.name, ' ', temp.surname) as driver
      from cars_indication 
      left join cars on cars.id = cars_indication.car
      left join (SELECT users.id, employee.name, employee.surname from employee
             left join users on users.employee = employee.id) as temp on cars.user = temp.id
      order by date_time DESC;";

      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }
    public function getIndicationsByUser($user){
      $sql = "SELECT car, date_time, indication, pic, employee.name, employee.surname, cars.car_name, cars.consumption
      from cars_indication 
      left join employee on employee.id = (select employee from users where id = ?)
      left join cars on cars.id=cars_indication.car
      where car in (select id from cars where user = ?)
      order by date_time DESC";

      $query = $this->db->query($sql, array($user, $user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }
    //public function getIndicationsByUser($user){
    //  $sql = "SELECT `car`, `date_time`, `indication`, `pic`, cars.user, cars.car_name, cars.consumption, users.name
    //  from cars_indication 
    //  left join cars on cars.id = cars_indication.car
    //  left join users on cars.user = users.id
    //  where cars.user = ?
    //  order by date_time DESC";

    //  $query = $this->db->query($sql, array($user));

    //  if (!empty($sql)) {
    //    return $query->getResultArray();
    //  } else {
    //    return false;
    //  }
    //}

    public function getCarInfo($user) {
      $sql = "select id, user, car_name from cars where user = ?";
      $query = $this->db->query($sql, array($user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    } 

    public function getPrevIndication($user) {
      $sql = "select date_time, id, indication from cars_indication where car in (select id from cars where user = ?) order by date_time desc  limit 1";
      $query = $this->db->query($sql, array($user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets array of accounts
    
}

?>
