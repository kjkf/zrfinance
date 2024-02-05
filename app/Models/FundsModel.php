<?php 
namespace App\Models;
use CodeIgniter\Model;
 
class FundsModel extends Model
{
    protected $table = 'funds_operations';
    protected $allowedFields = [
        'date', 
        'number', 
        'operation_type',
        'sum',
        'contractor',
        'expense_type',
        'author',
        'comments'
    ];

    public function insertAnalytics($data) {
      $this->db->transStart();
      $this->insertRowsToTemporaryTable($data);
      $this->copyToOriginalTable();
      $this->clearTemporaryTable();
      $this->db->transComplete();
    }

    private function insertRowsToTemporaryTable($data) {
      $builder = $this->db->table("funds_operations_supporting");
      $builder->insertBatch($data);
    }

    private function copyToOriginalTable() {
      $sql = "insert into funds_operations(date, number, operation_type, sum, contractor, expense_type, author, comments) 
      select date, number, operation_type, sum, contractor, expense_type, author, comments from funds_operations_supporting t1 where NOT EXISTS(select id from funds_operations t2 where t1.date=t2.date and t1.contractor=t2.contractor and t1.sum=t2.sum)";

      $query = $this->db->query($sql);
      return $query;
    }

    private function clearTemporaryTable() {
      $sql = "delete FROM `funds_operations_supporting` ";

      $query = $this->db->query($sql);
      return $query; 
    }

    public function get_contractor_info($contractor) {
      //$sql = "select id, expense_type from contractor where full_name like '%?%' ";
      $builder = $this->db->table("contractor");
      $builder->like('full_name', $contractor);
      $builder->select(array('id', 'expense_type'));

      $query = $builder->get();
      return $query->getResult();

    }

    //public function create_expense_report($date_start, $date_end) {
    //  $expense_types = $this->get_expense_types();
    //  $info = array();
    //  if ($expense_types) {
    //    foreach ($expense_types as $type) {
    //      $info[$type->id] = $this->getExpensesByType($type->id, $date_start, $date_end);
    //    }

    //    $group_info = $this->getGroupExpensesSum($date_start, $date_end);
    //    $total_sum = $this->getTotalExpensesSum($date_start, $date_end);
    //  }
    //}

    public function getExpensesByType($type, $start, $end) {
      $sql = "SELECT `date`, `number`, `operation_type`, `sum`, `contractor`, `author`, `comments`, expense_type.expense_type FROM `funds_operations`
      left join contractor on contractor.id = funds_operations.contractor
      left join expense_type on expense_type.id = funds_operations.expense_type
      where funds_operations.`expense_type` = ? and Date(`date`) between ? and ?";
      
      $query = $this->db->query($sql, array($type, $start, $end));
      return $query->getResultArray();
    }

    public function getExpensesByDatePeriod($start, $end) {
      $sql = "SELECT `date`, `number`, `operation_type`, `sum`, `contractor`.`full_name` as `contractor`, `author`, `comments`, expense_type.expense_type FROM `funds_operations`
      left join contractor on contractor.id = funds_operations.contractor
      left join expense_type on expense_type.id = funds_operations.expense_type
      where  Date(`date`) between date(?) and date(?)";
      
      $query = $this->db->query($sql, array($start, $end));
      return $query->getResult();
    }
    public function getExpensesByDatePeriodAndExpenseType($start, $end) {
      $sql = "SELECT sum(`sum`) as sum, expense_type.expense_type as expense_type_name, funds_operations.expense_type 
      FROM `funds_operations`
      left join expense_type on expense_type.id = funds_operations.expense_type
      where  Date(`date`) between date(?) and date(?)
      group by funds_operations.expense_type";
      
      $query = $this->db->query($sql, array($start, $end));
      return $query->getResult();
    }

    public function get_expense_types() {
      $builder = $this->db->table('expense_type');
      $builder->select(array("id"));

      $query = $builder->get();
      return $query->getResult();
    }

    //public function getGroupExpensesSum($start, $end) {
    //  $sql = "select expense_type.expense_type, SUM(sum) FROM `funds_operations` 
    //  left join expense_type on expense_type.id = funds_operations.expense_type
    //  where Date(`date`) BETWEEN ? AND ?
    //  GROUP by funds_operations.expense_type";

    //  $query = $this->db->query($sql, array($start, $end));
    //  return $query->getResultArray();
    //}

    public function getTotalExpensesSum($start, $end) {
      $sql = "select SUM(sum) as total 
      FROM `funds_operations` 
      where Date(`date`) BETWEEN date(?) and date(?)";

      $query = $this->db->query($sql, array($start, $end));
      return $query->getResultArray();
    }

    public function load_expense_info($id, $start, $end) {
      $sql = "SELECT `date`, `number`, `operation_type`, `sum`, `contractor`.`full_name` as `contractor`, `author`, `comments`, expense_type.expense_type FROM `funds_operations`
      left join contractor on contractor.id = funds_operations.contractor
      left join expense_type on expense_type.id = funds_operations.expense_type
      where funds_operations.expense_type=? and Date(`date`) between date(?) and date(?)
      order by date";
      
      $query = $this->db->query($sql, array($id, $start, $end));
      return $query->getResult();
    }
}
