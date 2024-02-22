<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n;

class CarsModel extends Model
{
    protected $table = 'cars';
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
    public function save_indication($date, $car_id, $indication){
      $builder = $this->db->table("indication");
      $data = [
        //'date' => $date,
        'car' => $car_id,
        'indication' => $indication,
      ];

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
    public function getIndications(){
      $sql = " SELECT * from indication";

      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }
    public function getIndicationsByUser($user){
      $sql = "SELECT car, date, indication, pic, employee.name, employee.surname, cars.car_name, cars.consumption
      from indication 
      left join employee on employee.id = (select employee from users where id = ?)
      left join cars on cars.id=indication.car
      where car in (select id from cars where user = ?)";

      $query = $this->db->query($sql, array($user, $user));

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

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
      $sql = "select date, id, indication from indication where car in (select id from cars where user = ?) order by date desc  limit 1";
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
