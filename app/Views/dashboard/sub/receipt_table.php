<table id = "#account_" class="table  table-sm caption-top">
  <caption>
    Приход
    <span><?php echo (isset($receipts_total) && !empty($receipts_total))?": ".number_format($receipts_total, 2, ',', ' ')." KZT":""; ?></span>
  </caption>
  <thead class="table-success">
    <tr>
      <td class="td-num">№ п/п</td>
      <td>Наименование</td>
      <td>Документ</td>
      <td class="td-align-right">Сумма</td>
      <td class="td-align-right">Действия</td>
    </tr>
  </thead>
  <tbody>
    <?php
      $ind=0;
      // echo '<br>receipt_all ======= '.print_r($receipts_all);
      if(isset($receipts_all) && ! empty($receipts_all)){
        
        foreach ($receipts_all as $item) {
            ++$ind;
            $is_edit_allowed = false;
            // echo $item;
            if($item['status'] == 1 || $item['status'] == 2 || $item['status'] == 4){
              $is_edit_allowed = true;
            }
            $is_author = ($item['author'] == $user_id) ? true : false;
            $on_revision = ($item['status'] == 6) ? true : false;
            $is_deleted = ($item['status'] == 3) ? true : false;
            $is_chief = (isset($user_role) && $user_role == 3) ? true : false;
    ?>
    <tr id="receipt_row_<?php echo $item['id']?>" class="<?php echo ($is_deleted)? 'row-deleted':''?>">
      <td class="td-align-right"><?=$ind ?></td>
      <td class="td-descr"><?=$item['description'] ?></td>
      <td class = "td-doc">
        <a target="_blank" href="<?= base_url('uploads/'.$item['document']) ?>"><?=$item['document'] ?></a>
      </td>
      <td class="td-align-right">
        <span> <?php echo number_format($item['sum'], 2, ',', ' '); ?></span>
        <?php if($on_revision) :?>
        <span class="revision"><i class="fa-solid fa-retweet"></i><?php echo number_format($item['sum_new_value'],2,',', ' ')?></span>
        <?php endif ?>
      </td>
      <td class="td-align-right">
        <?php if($is_edit_allowed  && $is_author): ?>
          <input type="hidden" id="item_name_<?php echo $item['id']?>" value="<?php echo $item['item_name']?>">
          <input type="hidden" id="agreement_name_<?php echo $item['id']?>" value="<?php echo $item['agreement_name']?>">
          <input type="hidden" id="descr_<?php echo $item['id']?>" value="<?php echo $item['description']?>">
          <input type="hidden" id="sum_<?php echo $item['id']?>" value="<?php echo $item['sum']?>">
          <input type="hidden" id="company_id_<?php echo $item['id']?>" value="<?php echo $account['company']?>">
          <input type="hidden" id="status_<?php echo $item['id']?>" value="<?php echo $item['status']?>">
          <?php
            $emp_name = (!empty($item['emp_surname'])) ? $item['emp_surname'].' '.$item['emp_name'] : " - ";
          ?>
          <input type="hidden" id="emp_name_<?php echo $item['id']?>" value="<?php echo $emp_name?>">
          <a href="#" class='btn-icon' name="edit_item" record_id = "<?php echo $item['id']?>" item_type="receipt"><i class="fa-solid fa-pen-to-square"></i> </a>
          <a href="#" class='btn-icon' name="delete_item" record_id = "<?php echo $item['id']?>" item_type="receipt"><i class="fa-solid fa-trash-can"></i> </a>
        <?php endif ?>
        <?php if($is_chief && ($on_revision || $is_deleted)):
          $btn_name = "";
          if($on_revision)
            $btn_name = "history_edition";
          else
            $btn_name = "history_deletion";
        ?>
          <a href="#" class='btn-icon' name="<?php echo $btn_name?>" record_id = "<?php echo $item['id']?>" item_type="receipt"><i class="fa-solid fa-circle-question"></i></a>
        <?php elseif(!$is_chief && ($on_revision || $is_deleted)): ?>
          <i class="fa-solid fa-clock"></i>
        <?php endif ?>
      </td>
    </tr>
    <?php
        //}
      }
      if ($ind == 0){
    ?>
    <tr>
      <td colspan="5">Нет записей</td>
    </tr>
    <?php
      }
    }else{
    ?>
    <tr>
      <td colspan="5">Нет записей</td>
    </tr>
    <?php
    }
    ?>
  </tbody>
</table>
