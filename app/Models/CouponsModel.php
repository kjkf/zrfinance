<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n;

class CouponsModel extends Model
{
    protected $table = 'coupons';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'name'];
    //$db = db_connect();

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets array of companies
    public function add_coupons($data) {
      $builder = $this->db->table("coupons_receipt");
      
      $builder->insert($data);
      $insert_id =  $this->db->insertID();
      return $insert_id;
    }
    public function issuing_coupons($data) {
      $builder = $this->db->table("coupons");
      
      $builder->insert($data);
      $insert_id =  $this->db->insertID();
      return $insert_id;
    }

    public function getGasReceipt() {
      $sql = "select * from coupons_receipt";
      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getIssuingCoupons() {
      $sql = "SELECT base, date_time, quantity, money,
      CASE WHEN base = 0 THEN 'кара/пила' 
      ELSE concat(employee.surname, ' ', employee.name) 
      END as base_name
      FROM `coupons`
            left join employee on employee.id=coupons.base";
      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getIssuingBase() {
      $sql = "SELECT base, concat(employee.surname, ' ', employee.name) as name FROM `coupons_base`
      left join employee on coupons_base.base = employee.id";
      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getIssuingTotal() {
      $sql = "SELECT SUM(quantity) as quantity, SUM(money) as money FROM `coupons`;";
      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

    public function getRsecieptTotal() {
      $sql = "SELECT SUM(quantity) as quantity FROM `coupons_receipt`";
      $query = $this->db->query($sql);

      if (!empty($sql)) {
        return $query->getResultArray();
      } else {
        return false;
      }
    }

}


