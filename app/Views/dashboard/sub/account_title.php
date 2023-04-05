
<div class="row">
  <div class="col-12">
    <h6>Остаток на начало дня: <?php echo number_format(($account['receipt_sum']-$account['expense_sum']), 2, '.', ' ') ?> KZT </h6>
  </div>
</div>
<div class="row" style = "margin-bottom:50px;">
  <div class="col-6">
    <?php
      $receipt_by_account = [];
      $receipts_sum_arr = 0;
      $receipts_sum = 0;
      if(array_key_exists($account['id'], $receipts_all)){
        $receipt_by_account = $receipts_all[$account['id']];
        // echo json_encode($receipt_by_account);
        $receipts_sum_arr = array_column($receipt_by_account, "sum");
        $receipts_sum = array_sum($receipts_sum_arr);
      }
      // echo print_r($receipts_all);
    ?>
    <?php echo view('dashboard/sub/receipt_table', ['account_id' => $account['id'], 'receipts_all' => $receipt_by_account, 'receipts_total' => $receipts_sum] ); ?>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_addItem" data-bs-whatever="@receipt" company_id = "<?php echo $account['company']?>" account_id = "<?php echo $account['id']?>" account_name = "<?php echo $account['name']?>">
      Добавить запись
    </button>
  </div>
  <div class="col-6">
    <?php
      $expense_by_account = [];
      $expense_sum_arr = 0;
      $expense_sum = 0;
      if(array_key_exists($account['id'], $expense_all)){
        $expense_by_account = $expense_all[$account['id']];
        $expense_sum_arr = array_column($expense_by_account, "sum");
        $expense_sum = array_sum($expense_sum_arr);
      }
    ?>
    <?php echo view('dashboard/sub/expense_table', ['account_id' => $account['id'], 'expense_all' => $expense_by_account, 'expense_total' => $expense_sum]); ?>
    <button type="button" class="btn btn-info"    data-bs-toggle="modal" data-bs-target="#modal_addItem" data-bs-whatever="@expense" company_id = "<?php echo $account['company']?>" account_id = "<?php echo $account['id']?>" account_name = "<?php echo $account['name']?>">
      Добавить запись
    </button>
  </div>
</div>
