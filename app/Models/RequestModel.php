<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class RequestModel extends Model
{
    protected $table = 'purchase_request';
    protected $allowedFields = [
        'deal_num', 
        'deal_name', 
        'deal_date',
        'deal_goods',
        'deal_specificaion'
    ];

    public function getAllDeals() {
      $sql = "SELECT * from deals";
      
      $query = $this->db->query($sql);
      return $query->getResultArray();
    }

    
}
