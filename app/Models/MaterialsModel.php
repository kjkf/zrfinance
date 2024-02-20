<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialsModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'material',
                          'unit',
                          'description'];

    function getAllMaterials() {
      $sql = "select material, amount, units.unit, materials.description from materials left join units on units.id=materials.unit ";  
  
      $query = $this->db->query($sql);
  
      if (!empty($sql)) {
        $res =  $query->getResultArray();
        return $res;
      } else {
        return false;
      }
    } 

    function getAllUnits() {
      $sql = "select  unit, description from units ";  
  
      $query = $this->db->query($sql);
  
      if (!empty($sql)) {
        $res =  $query->getResultArray();
        return $res;
      } else {
        return false;
      }
    } 

    function getFromAnotherDB() {
      $sql = "select  name, email, phone from phonebook ";  
  
      $query = $this->db->query($sql);
  
      if (!empty($sql)) {
        $res =  $query->getResultArray();
        return $res;
      } else {
        return false;
      }
    } 
    
    
}

?>
