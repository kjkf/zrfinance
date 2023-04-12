<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n;

class FinanceModel extends Model
{
    protected $table = 'company';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'name'];
    //$db = db_connect();

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets array of companies
    public function get_all_companies(){
      return $this->findAll();
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets array of accounts
    public function get_all_accounts(){
      $sql = "SELECT
                account.id,
                account.name,
                account.company,
                row_expense.sum as expense_sum,
                row_receipt.sum as receipt_sum
              FROM account
              LEFT JOIN
                (
                  SELECT expense.company_account, sum(expense.sum) as sum
                  FROM expense WHERE DATE(expense.date_time) <= ?
                  GROUP BY expense.company_account
                ) as row_expense ON row_expense.company_account = account.id
              LEFT JOIN
                (
                  SELECT receipt.company_account, sum(receipt.sum) as sum
                  FROM receipt WHERE DATE(receipt.date_time) <= ?
                  GROUP BY receipt.company_account
                ) as row_receipt ON row_receipt.company_account = account.id";
      $time = new \CodeIgniter\I18n\Time;
      $now = $time::now('Asia/Almaty');
      $dif = 1;
      $stat_date = $now->subDays($dif)->toDateString();

      $query = $this->db->query($sql, [$stat_date, $stat_date]);
      $result = $query->getResultArray();

      $sum = null;

      foreach ($result as $item) {
        $sum = $item['receipt_sum'];
        if (!empty($sum ))
          break;
      }
      while (empty($sum)) {
        ++$dif;
        $stat_date = $now->subDays($dif)->toDateString();
        $query = $this->db->query($sql, [$stat_date, $stat_date]);
        $result = $query->getResultArray();

        foreach ($result as $item) {
          $sum = $item['receipt_sum'];
          if (!empty($sum ))
            break;
        }
        if ($dif >=15)
          break;
      }
      
      return $result;
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets all receipts
    public function get_all_receipts_today(){
      $sql = "SELECT
                receipt.*,
                receipt_item.name as item_name,
                agreement_forZR.agreement_num as agreement_name,
                employee.name as emp_surname,
                employee.surname as emp_name,
                receipt_change.new_value as sum_new_value
              FROM receipt
              LEFT JOIN receipt_item ON receipt_item.id = receipt.item
              LEFT JOIN agreement_forZR ON agreement_forZR.id = receipt.agreement_forzr
              LEFT JOIN employee ON employee.id = receipt.employee
              LEFT JOIN (SELECT * FROM receipt_change WHERE receipt_change.new_status in (3,6)) as receipt_change ON receipt_change.record_id = receipt.id
              WHERE DATE(receipt.date_time) = ? AND status <> 5
              ORDER BY receipt.id DESC";//AND status <> 5

      $time = new \CodeIgniter\I18n\Time;
      $stat_date = $time::now('Asia/Almaty');

      $query = $this->db->query($sql, [$stat_date->toDateString()]);
      // echo $sql. " - ". $stat_date->toDateString() . "<br><br>";
      $receipts_all =  $query->getResultArray();
      //echo print_r($receipts_all) . "<br><br>";
      $receipts_by_account = array();

      $account_prev = null;
      $i = 0;
      foreach ($receipts_all as $item) {
        ++$i;
        //echo $i."<br>";
        if(is_null($account_prev)){
            //echo "is null<br>";
            $account_prev = intval($item['company_account']);
            $ind = $item['company_account'];
            $receipts_by_account[$ind] = [];
            array_push($receipts_by_account[$item['company_account']],$item);
        }else{
          if($account_prev == intval($item['company_account'])){
            //echo "not null and equal<br>";
            array_push($receipts_by_account[$item['company_account']],$item);
          }else{
            $ind = $item['company_account'];
            $receipts_by_account[$ind] = array();
            array_push($receipts_by_account[$item['company_account']],$item);
          }
        }

        $account_prev = $item['company_account'];
      }
      
      // echo json_encode($receipts_by_account) . "<br><br>";
      return $receipts_by_account;
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets all receipts
    public function get_all_expense_today(){
      $sql = "SELECT
                  expense.*,
                  expense_item.name as item_name,
                  agreement_forZR.agreement_num as agreement_forZR_name,
                  agreement_fromZR.agreement_num as agreement_fromZR_name,
                  employee.name as emp_surname,
                  employee.surname as emp_name,
                  expense_change.new_value as sum_new_value
              FROM expense
              LEFT JOIN expense_item ON expense_item.id = expense.item
              LEFT JOIN agreement_forZR ON agreement_forZR.id = expense.agreement_forzr
              LEFT JOIN agreement_fromZR ON agreement_fromZR.id = expense.agreement_fromzr
              LEFT JOIN employee ON employee.id = expense.employee
              LEFT JOIN (SELECT * FROM expense_change WHERE expense_change.new_status in (3,6)) as expense_change ON expense_change.record_id = expense.id
              WHERE DATE(expense.date_time) = ? AND status <> 5
              ORDER BY `company_account` asc, expense.id DESC";//AND status <> 5

      $time = new \CodeIgniter\I18n\Time;
      $stat_date = $time::now('Asia/Almaty');

      $query = $this->db->query($sql, [$stat_date->toDateString()]);
      $expense_all =  $query->getResultArray();

      $expense_by_account = [];

      $account_prev = null;
      foreach ($expense_all as $item) {
        if(is_null($account_prev)){
            $account_prev = $item['company_account'];
            $ind = $item['company_account'];
            $expense_by_account[$ind] = [];
        }

        if($account_prev != $item['company_account']){
          $ind = $item['company_account'];
          $expense_by_account[$ind] = [];
        }
        array_push($expense_by_account[$item['company_account']],$item);

        $account_prev = $item['company_account'];
      }

      return $expense_by_account;
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets recipt_items
    public function get_all_receipt_items(){
      $sql = "SELECT *
              FROM receipt_item
              WHERE id <> 4";
      $query = $this->db->query($sql);
      return $query->getResultArray();
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets expense items
    public function get_all_expense_items(){
      $sql = "SELECT *
              FROM expense_item
              order by name asc
             ";
      $query = $this->db->query($sql);
      return $query->getResultArray();
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets item by id
    public function get_item_byid($table_name, $record_id){
      $db      = \Config\Database::connect();
      $builder = $db->table($table_name);
      $builder->where('id', $record_id);
      return $builder->get()->getResultArray();
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets contractors
    public function get_contractors($company_id, $item_type){

      $sql = "SELECT
                COUNT(agreement_forZR.id),
                agreement_forZR.executer as contractor_id,
                contractor.short_name  as contractor_name
              FROM agreement_forZR
              LEFT JOIN contractor ON contractor.id = agreement_forZR.executer
			        WHERE company = ?
              GROUP BY contractor_id

              UNION ALL

              SELECT
              	COUNT(agreement_fromZR.id),
                agreement_fromZR.customer as contractor_id,
                contractor.short_name as contractor_name
              FROM agreement_fromZR
              LEFT JOIN contractor ON contractor.id = agreement_fromZR.customer
              WHERE company = ?
              GROUP BY contractor_id";
      $query = $this->db->query($sql, [intval($company_id),intval($company_id)]);

      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets contractors
    public function get_contractors_by_category($catId){

      if ($catId == '4') {
        $sql = "SELECT id, `short_name` contractor_name FROM contractor order by short_name asc";
        $query = $this->db->query($sql);
      } else {
        $sql = "SELECT id, `short_name` contractor_name FROM contractor where category = ?  order by short_name asc";
        $query = $this->db->query($sql, [intval($catId)]);
      }

      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets agreements
    public function get_agreements($company_id, $contractor=null){
      $condition_exp = (!empty($contractor))?  "agreement_fromZR.company = ? AND agreement_fromZR.customer = ?": " agreement_fromZR.company = ?";
      $condition_rec = (!empty($contractor))?  "agreement_forZR.company = ? AND agreement_forZR.executer = ?": " agreement_forZR.company = ?";
      $sql = "SELECT
                agreement_forZR.id,
                agreement_forZR.company,
                agreement_forZR.agreement_num,
                agreement_forZR.agreement_date,
                agreement_forZR.agreement_sum,
                agreement_forZR.short_name,
                contractor.short_name as contractor_name,
                '-' as manager,
                'forZR' as type
              FROM agreement_forZR
              LEFT JOIN contractor ON contractor.id = agreement_forZR.executer
              WHERE " . $condition_rec .
              " UNION ALL
              SELECT
                agreement_fromZR.id,
                agreement_fromZR.company,
                agreement_fromZR.agreement_num,
                agreement_fromZR.agreement_date,
                agreement_fromZR.agreement_sum,
                agreement_fromZR.short_name,
                contractor.short_name as contractor_name,
                employee.surname as manager,
                'fromZR' as type
              FROM agreement_fromZR
              LEFT JOIN contractor ON contractor.id = agreement_fromZR.customer
              LEFT JOIN employee ON employee.id = agreement_fromZR.manager
              WHERE ".$condition_exp;

      $query_attr = [];

      if(!empty($contractor)){
        $query_attr = [intval($company_id), intval($contractor),intval($company_id), intval($contractor)];
      }else{
        $query_attr = [intval($company_id), intval($company_id)];
      }
      //echo $sql;
      $query = $this->db->query($sql, $query_attr);

      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets agreements
    public function get_employees_byCompany($company_id){
      $sql = "";

      $sql = "SELECT
                id,
                name,
                surname,
                email,
                position
              FROM employee
              WHERE company = ?";

      $query = $this->db->query($sql, [intval($company_id)]);

      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //gets goods by type
    public function get_goods_byType($type_id){
      $sql = "";

      $sql = "SELECT
                *
              FROM goods
              WHERE type in ".$type_id;

      $query = $this->db->query($sql);
      // echo $this->db->last_query();
      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //saves item recept or expense
    public function save_item($table_name, $data){
      $db      = \Config\Database::connect();
      $builder = $db->table($table_name);

      $builder->insert($data);
      $insert_id =  $db->insertID();//$builder->insert_id();
      
      return  $insert_id;
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //deletes item recept or expense
    public function delete_item($table_name, $delete_data){

      $item_record = $this->get_item_byid($table_name, $delete_data['id']);
      echo print_r($item_record);
      $old_status = $item_record[0]['status'];
      $author = $item_record[0]['author'];

      $time = new \CodeIgniter\I18n\Time;
      $now = $time::now('Asia/Almaty');

      $db = \Config\Database::connect();
      $changetable_name = $table_name."_change";
      $builder = $db->table($changetable_name);
      $data = [
        'record_id' => $delete_data['id'],
        'old_status' => $old_status,
        'new_status' => 3,
        'reason' => $delete_data['status_reason'],
        'date_time' => $now->toDateTimeString(),
      ];
      $builder->insert($data);
      $change_id = $db->insertID();

      //$db      = \Config\Database::connect();
      $builder = $db->table($table_name);

      $builder->set('status', 3);
      $builder->set('status_reason', $delete_data['status_reason']);
      $builder->set('status_date', $now->toDateTimeString());
      $builder->where('id', $delete_data['id']);
      return $builder->update();
      //return $builder->delete();
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //save document for item
    public function save_itemDoc($table_name, $record_id, $path){
      $db      = \Config\Database::connect();
      $builder = $db->table($table_name);


      $builder->set('document', $path);
      $builder->where('id', $record_id);
      return $builder->update();
      // return $builder->replace($data);
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    //save document for item
    public function edit_item($table_name, $data, $status){
      $result = false;
      $itemtable_name = str_replace("_change","",$table_name);
      $sql = "SELECT
                date_time as create_date
              FROM ".$itemtable_name.
              " WHERE id = ?";
      $query = $this->db->query($sql, [$data['record_id']]);
      $old_item = $query->getResult();

      $time = new \CodeIgniter\I18n\Time;
      $create_date = $time::parse($old_item[0]->create_date, 'Asia/Almaty');
      $now = $time::now('Asia/Almaty');
      if($create_date->getYear == $now->getYear && $create_date->getMonth == $now->getMonth && $create_date->getDay == $now->getDay)
      {
        $db      = \Config\Database::connect();
        $builder = $db->table($table_name);

        $builder->insert($data);
        $insert_id =  $db->insertID();//$builder->insert_id();
        //echo "good";
        return  $insert_id;
      }else{
        //echo "not good";
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function change_parentStatus($parent_table,$record_id, $data){
      $db      = \Config\Database::connect();
      $builder = $db->table($parent_table);

      if($data['status'] == 2){
        $builder->set('status', $data['status']);
        $builder->set('status_date', $data['status_date']);
        $builder->set('sum', $data['new_sum_value']);
        $builder->set('document', $data['new_document']);
      }else if($data['status'] == 6){
        $builder->set('status', $data['status']);
        $builder->set('status_date', $data['status_date']);
      }
      $builder->where('id', $record_id);
      $res = $builder->update();

      return $res;

    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function get_history_deletion($table_name, $record_id){
      $sql = "";

      $sql = "SELECT
                item.id,
                item_table.name as item_name,
                item.description,
                item.sum,
                forzr.agreement_num as forzr_agr_num,
                forzr.agreement_date as forzr_agr_date,
                fromzr.agreement_num as fromzr_agr_num,
                fromzr.agreement_date as fromzr_agr_date,
                status,
                status_reason,
                author,
                employee.surname as emp_surname,
                employee.name as emp_name
              FROM ".$table_name." as item
              LEFT JOIN users ON users.id = item.author
              LEFT JOIN employee ON employee.id = users.employee
              LEFT JOIN ".$table_name."_item as item_table ON item_table.id = item.item
              LEFT JOIN agreement_forZR as forzr ON forzr.id = item.agreement_forzr
              LEFT JOIN agreement_fromZR as fromzr ON fromzr.id = item.agreement_fromzr
              WHERE item.id = ? AND status=3";

      $query = $this->db->query($sql, [$record_id]);

      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function get_history_edition($table_name, $record_id){
      $sql = "";

      $sql = "SELECT
                item.id,
                item_table.name as item_name,
                item.description,
                item.sum,
                item.document,
                forzr.agreement_num as forzr_agr_num,
                forzr.agreement_date as forzr_agr_date,
                fromzr.agreement_num as fromzr_agr_num,
                fromzr.agreement_date as fromzr_agr_date,
                status,
                status_reason,
                author,
                employee.surname as emp_surname,
                employee.name as emp_name,
                change_table.reason as edition_reason,
                change_table.new_value as edition_new_value,
                change_table.old_value as edition_old_value,
                change_table.document as edition_doc
              FROM ".$table_name." as item
              LEFT JOIN users ON users.id = item.author
              LEFT JOIN employee ON employee.id = users.employee
              LEFT JOIN ".$table_name."_item as item_table ON item_table.id = item.item
              LEFT JOIN (SELECT * FROM ".$table_name."_change WHERE ".$table_name."_change.record_id = ? ORDER BY ".$table_name."_change.id DESC LIMIT 1) as change_table ON change_table.record_id = item.id
              LEFT JOIN agreement_forZR as forzr ON forzr.id = item.agreement_forzr
              LEFT JOIN agreement_fromZR as fromzr ON fromzr.id = item.agreement_fromzr
              WHERE item.id = ? AND status=6";

      $query = $this->db->query($sql, [$record_id, $record_id]);
      //echo $sql;

      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function approve_deletion($table_name, $decision, $record_id, $userID){
      $time = new \CodeIgniter\I18n\Time;
      $now = $time::now('Asia/Almaty');
      $db      = \Config\Database::connect();
      $builder = $db->table($table_name);

      if($decision){
        $status = 5;
      }else{
        $status = 4;
      }

      $builder->set('status', $status);
      $builder->set('status_date', $now->toDateTimeString());
      $builder->where('id', $record_id);
      $res_maintable = $builder->update();

      if($res_maintable){

        $changetable_name = $table_name."_change";
        $builder = $db->table($changetable_name);
        $data = [
          'record_id' => $record_id,
          'old_status' => 3,
          'new_status' => $status,
          'date_time' => $now->toDateTimeString(),
          'decision' => $decision,
          'decision_date' => $now->toDateTimeString(),
          'decision_by' => $userID,

        ];
        $builder->insert($data);
        $change_id = $db->insertID();
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function approve_edition($table_name, $decision, $change_data, $userID){
      $time = new \CodeIgniter\I18n\Time;
      $now = $time::now('Asia/Almaty');
      $db      = \Config\Database::connect();
      $builder = $db->table($table_name);

      if($decision){
        $status = 2;
        $builder->set('status', $status);
        $builder->set('status_date', $now->toDateTimeString());
        $builder->set('sum', $_POST['aprove_new_value']);
        $builder->set('document', $change_data['document']);
        $builder->where('id', $change_data['record_id']);
      }else{
        $status = 4;
        $builder->set('status', $status);
        $builder->set('status_date', $now->toDateTimeString());
        $builder->where('id', $change_data['record_id']);
      }

      $res_maintable = $builder->update();

      if($res_maintable){

        $changetable_name = $table_name."_change";
        $builder = $db->table($changetable_name);
        $data = [
          'record_id' => $change_data['record_id'],
          'old_status' => 6,
          'new_status' => $status,
          'old_value' => $_POST['aprove_old_value'],
          'new_value' => $_POST['aprove_new_value'],
          'date_time' => $now->toDateTimeString(),
          'decision' => $decision,
          'decision_date' => $now->toDateTimeString(),
          'decision_by' => $userID,

        ];
        $builder->insert($data);
        $change_id = $db->insertID();
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    // REPORTS VVV
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function get_reportMain($date_start, $date_end){
      $sql = 'SELECT
                  company.id,
                  company.full_name,
                  company.name,
                  account.id,
                  account.name,
                  FORMAT(receipt_all.sum, 2, "ru_RU")  as receipt_sum,
              	  FORMAT(expense_all.sum, 2, "ru_RU")  as expense_sum
              FROM account
              LEFT JOIN company ON account.company = company.id
              LEFT JOIN(
                          SELECT
                              receipt.company_account,
                              SUM(receipt.sum) AS sum
                          FROM
                              receipt
                          WHERE
                              DATE(receipt.date_time) BETWEEN ? AND ?
                          GROUP BY
                              receipt.company_account
                          ) as receipt_all ON receipt_all.company_account = account.id
              LEFT JOIN(
                          SELECT
                              expense.company_account,
                              SUM(expense.sum) AS sum
                          FROM
                              expense
                          WHERE
                              DATE(expense.date_time) BETWEEN ? AND ?
                          GROUP BY
                              expense.company_account
                          ) as expense_all ON expense_all.company_account = account.id';
      $query = $this->db->query($sql, [$date_start->format('Y-m-d'), $date_end->format('Y-m-d'), $date_start->format('Y-m-d'), $date_end->format('Y-m-d')]);
      //echo (string) $this->db->getLastQuery();
      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function get_reportByGoods(){
      $sql = 'SELECT
                  receipt.id,
                  account.company as company_id,
                  company.full_name as company_name,
                  receipt.company_account as account_id,
                  account.name as account_name,
                  receipt.goods as good_id,
                  goods.name as good_name,
                  FORMAT(receipt.sum, 2, "ru_RU") as receipt_sum
              FROM receipt
              LEFT JOIN goods ON goods.id = receipt.goods
              LEFT JOIN account ON account.id = receipt.company_account
              LEFT JOIN company ON company.id = account.company
              WHERE receipt.goods is not null';
      $query = $this->db->query($sql);
      if (!empty($sql)){
        return $query->getResultArray();
      }else{
        return false;
      }
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//
    // CLASSIFICATORS VVV
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------//


}

?>
