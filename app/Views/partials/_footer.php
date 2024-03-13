<!-- Modal: create receipt/expense item -->
<?php 
if(isset($page_name) && $page_name == "finance_movements"){ ?>
<div class="modal fade" id="modal_addItem" tabindex="-1" aria-labelledby="modal_addItemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_addItemLabel">Статья прихода----</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="item_form" action="<?=base_url('dashboard/save_item')?>" method="post" enctype = "multipart/form-data">
        <?= csrf_field();?>
        <?php
          $user_id = null;
          if(isset($user_id) && !empty($user_id)){
            $user_id = $user_id;
          }elseif (isset($user)){
            $user_id = $user['id'];
          }
          $official = 1;
          if(isset($validation)){
            $old_itemType = set_value('item_type');
            $old_itemName = set_value("item_name");
            $old_agreement = set_value('agreement');
            $old_employee = set_value("employee");
            $old_description = set_value("description");
            $old_sum = set_value("sum");
            $old_userDoc = set_value("user_doc");
            $old_company_id = set_value("company_id");
            $old_company_account = set_value("company_account");
            $old_agreement_type= set_value("agreement_type");
            $old_author= set_value("author");
            $old_official = set_value("official");
            $official = $old_official;
            //echo "old_agreement = $old_agreement" ;
            $item_names = [];
            $item_type = $old_itemType;
            if(isset($old_itemType) && !empty($old_itemType)){
              switch ($old_itemType) {
                case 'receipt':
                  $item_names = $receipt_items;
                  // echo print_r($item_names);
                  break;

                case 'expense':
                  $item_names = $expence_items;
                  break;

                default:
                  $item_names = $receipt_items;
                  // echo print_r($item_names);
                  break;
              }
            }
          }
        ?>
        <div class="modal-body">
            <div class="row">
              <div class="col">
                <span class="modal-subtitle warning" id="modal_addItemSubLabel" style="color: grey"></span>
              </div>
            </div>
            <!-- - - - - - - - - - - - - - - - - - - - hidden fields  - - - - - - - - - - - - - - - - - - -  -->
            <input type="hidden" name="company_id" id="company_id" value="<?php echo (isset($old_company_id) && !empty($old_company_id)) ? $old_company_id : ''?>">
            <input type="hidden" name="company_account" id="company_account" value="<?php echo (isset($old_company_account) && !empty($old_company_account)) ? $old_company_account : ''?>">
            <input type="hidden" name="item_type" id="item_type" value="<?php echo (isset($old_itemType) && !empty($old_itemType)) ? $old_itemType : ''?>">
            <input type="hidden"   name="agreement_type" id="agreement_type" value="<?php echo (isset($agreement_type) && !empty($agreement_type)) ? $agreement_type : 'null'?>">
            <input type="hidden" name="date" id="date" value="">
            <input type="hidden" name="author" id="author" value="<?php echo (isset($old_author) && !empty($old_author)) ? $old_author : $user_id?>">
            <!-- - - - - - - - - - - - - - - - - - - - - - - - - - -  - - - - - - - - - - - - - - - - - - -  -->
            <div class="mb-4">
              <label for="item_name" class="col-form-label">Наименование:</label>
              <select class="form-select" aria-label="Default select example" id = "item_name" name = "item_name">
                <?php
                if(isset($item_names)){
                  echo "<option>Выберите</option>";
                  // echo print_r($item_names);
                  foreach ($item_names as $item) {
                    $need_agreement = (isset($item['need_agreement']) && $item['need_agreement'] == 1)?true:false;
                    $need_employee = (isset($item['need_employee']) && $item['need_employee'] == 1)?true:false;
                    $need_contractor = (isset($item['need_contractor']) && $item['need_contractor'] == 1)?true:false;
                    $need_goods = (isset($item['need_goods']) && $item['need_goods'] == 1)?true:false;
                    if($item['id'] == $old_itemName){
                      $option = "<option selected value='".$item['id']."' item_type='".$item_type."' need_agreement = '".$need_agreement."' need_employee = '".$need_employee."' need_goods = '".$need_goods."' need_contractor = '".$need_contractor."'>".$item['name']."</option>";
                    }else{
                      $option = "<option value='".$item['id']."' item_type='".$item_type."' need_agreement = '".$need_agreement."' need_employee = '".$need_employee."' need_goods = '".$need_goods."' need_contractor = '".$need_contractor."'>".$item['name']."</option>";
                    }
                    echo $option;
                  }
                }else{
                ?>
                <option selected>Выберите</option>
                <option >Что-то пошло не так</option>
                <?php
                }
                ?>
              </select>
              <span class="text-danger"> <?php echo isset($validation) ? display_error($validation, 'item_name'):''?> </span>
            </div>
            <div class="mb-4" id="block_contractor">
              <label for="contractor" class="col-form-label" >Контрагент:</label>
              <select class="form-select" aria-label="Default select example" id = "contractor" name = "contractor"  <?php echo (isset($old_contractor) && !empty($old_contractor))?"":"disabled"?>>
                <?php
                  if(isset($contractors) && !empty($contractors)){
                    foreach ($contractors as $item) {
                      echo "<option>Выберите</option>";
                      echo $option;
                    }
                  }else{
                ?>
                <option selected>Выберите</option>
                <option >Что-то пошло не так</option>
                <?php
                  }
                ?>
              </select>
            </div>
            <div class="mb-4" id="block_agreement">
              <label for="agreement" class="col-form-label" >Договор:</label>
              <select class="form-select" aria-label="Default select example" id = "agreement" name = "agreement" <?php echo (isset($old_agreement) && !empty($old_agreement))?"":"disabled"?> >
                <?php
                  if(isset($agreements) && !empty($agreements)){
                    foreach ($agreements as $item) {
                      echo "<option>Выберите</option>";
                      $agreement_sum = (isset($item['agreement_sum'])) ? $item['agreement_sum'] : "";
                      $agreement_type = (isset($item['type'])) ? $item['type'] : "";
                      $agreement_num = (isset($item['agreement_num'])) ? $item['agreement_num'] : "";
                      if($item['id'] == $old_agreement){
                        $option = "<option selected value='".$item['id']."' agreement_sum='".$agreement_sum."'  agreement_type = '".$agreement_type."'>".$agreement_num."</option>";
                      }else{
                        $option = "<option value='".$item['id']."' agreement_sum='".$agreement_sum."'  agreement_type = '".$agreement_type."'>".$agreement_num."</option>";
                      }
                      echo $option;
                    }
                  }else{
                ?>
                <option selected>Выберите</option>
                <option >Что-то пошло не так</option>
                <?php
                  }
                ?>
              </select>
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'agreement'):''?> </span>
            </div>
            <div class="mb-4" id="block_employee">
              <label for="employee" class="col-form-label" >Сотрудник:</label>
              <select class="form-select" aria-label="Default select example" id = "employee" name = "employee" disabled>
                <option selected>Выберите</option>
                <option >Что-то пошло не так</option>
              </select>
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'employee'):''?> </span>
            </div>
            <div class="mb-4" id="block_goods">
              <label for="goods" class="col-form-label" >Товары:</label>
              <select class="mdb-select form-select" aria-label="" id = "goods" name = "goods" disabled>
                <option selected>Выберите</option>
                <option >Что-то пошло не так</option>
              </select>
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'goods'):''?> </span>
            </div>
            <div class="mb-4" id="block_official">
              <label for="official" class="col-form-label">Официально:</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="official" id="official_1" value="1" <?php echo (isset($official) && $official) ? "checked" : "" ?>>
                <label class="form-check-label" for="official_1">да</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="official" id="official_2" value="0" <?php echo (isset($official) && !$official) ? "checked" : "" ?>>
                <label class="form-check-label" for="official_2">нет</label>
              </div>
              <!-- <input type="checkbox" class="form-control" id="official" name="official"   value=""> -->
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'official'):''?> </span>
            </div>
            <div class="mb-4" id="block_description">
              <label for="description" class="col-form-label">Описание:</label>
              <input type="text" class="form-control" id="description" name="description"   value="<?php echo (isset($old_description) && !empty($old_description))?$old_description:''?>" required minlength="20" maxlength="500">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'description'):''?> </span>
              <span id="description_key_count"> </span>
            </div>
            <div class="mb-4" id="block_sum">
              <label for="sum" class="col-form-label">Сумма:</label>
              <input type="text" class="form-control" required id="sum" name="sum" value="<?php echo (isset($old_sum) && !empty($old_sum))?$old_sum:''?>">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'sum'):''?> </span>
            </div>
            <div class="mb-4" id="block_document">
              <label class="document" for="user_doc">Подтверждающий документ:</label>
              <input type="file"  class="form-control" id="user_doc" name="user_doc" single value="<?php echo (isset($old_userDoc) && !empty($old_userDoc))?$old_userDoc:''?>"/>
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'user_doc'):''?> </span>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary" id="btn_">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal: create receipt/expense item -->
<!-- Modal: edit receipt/expense sum -->
<div class="modal fade" id="modal_editItem" tabindex="-1" aria-labelledby="modal_editItemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_editItemLabel">Статья прихода</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="" action="<?=base_url('dashboard/edit_item')?>" method="post" enctype = "multipart/form-data">
        <?= csrf_field();?>
        <?php

        if(isset($edit_validation)){
          $old_newValue = set_value('new_value');
          $old_reason = set_value('reason');
          $old_company_id = set_value('edit_company_id');
          $old_itemType = set_value('edit_item_type');
          $old_userDoc = set_value('user_doc');

          $old_item_name = $item_name;
          echo '1 - '.display_error($edit_validation, 'new_value');
          echo '2 - '.display_error($edit_validation, 'reason');
          echo '3 - '.display_error($edit_validation, 'edit_item_type');
          echo '4 - '.display_error($edit_validation, 'edit_company_id');
          echo '5 - '.display_error($edit_validation, 'user_doc');
        }
        ?>
        <input type="hidden" name="edit_company_id" id="edit_company_id" value="<?php echo (isset($old_company_id) && !empty($old_company_id)) ? $old_company_id : ''?>">
        <input type="hidden" name="edit_item_type" id="edit_item_type" value="<?php echo (isset($old_itemType) && !empty($old_itemType)) ? $old_itemType : ''?>">
        <input type="hidden" name="record_id" id="record_id" value="<?php echo (isset($record_id) && !empty($record_id)) ? $record_id : ''?>">
        <input type="hidden" name="status" id="status" value="">
        <div class="modal-body">
            <div class="row">
              <div class="col">
                <span class="modal-subtitle warning" id="modal_editubLabel" style="color: grey"></span>
              </div>
            </div>
            <div class="mb-3">
              <label for="item_name" class="col-form-label">Наименование:</label>
              <span id="modal_editItem_Name"><?php echo (isset($old_item_name)) ? $old_item_name : "" ?></span>
              <input type="hidden" name="edit_itemName" id="edit_itemName" value="<?php echo (isset($old_item_name)) ? $old_item_name : "" ?>">
            </div>
            <div class="mb-3">
              <label for="agreement" class="col-form-label" >Договор:</label>
              <span id="modal_editItem_Agreement"><?php echo (isset($agreement)) ? $agreement : "" ?></span>
              <input type="hidden" name="edit_itemAgreement" id="edit_itemAgreement" value="<?php echo (isset($agreement)) ? $agreement : "" ?>">
            </div>
            <div class="mb-3">
              <label for="employee" class="col-form-label" >Сотрудник:</label>
              <span id="modal_editItem_Employee"><?php echo (isset($employee)) ? $employee : "" ?></span>
              <input type="hidden" name="edit_itemEmployee" id="edit_itemEmployee" value="<?php echo (isset($employee)) ? $employee : "" ?>">
            </div>
            <div class="mb-3">
              <label for="description" class="col-form-label">Описание:</label>
              <span id="modal_editItem_Description"><?php echo (isset($description)) ? $description : "" ?></span>
              <input type="hidden" name="edit_itemDescription" id="edit_itemDescription" value="<?php echo (isset($description)) ? $description : "" ?>">
            </div>
            
            <div class="mb-3">
              <label for="old_value" class="col-form-label">Сумма, старое значение:</label>
              <span id="modal_editItem_Sum"><?php echo (isset($old_value)) ? number_format($old_value, 2, ',', ' ') : "" ?></span>
              <input type="hidden" name="old_value" id="old_value" value="<?php echo (isset($old_value)) ? $old_value : "" ?>">
            </div>
            
            <div class="mb-3">
              <label for="new_value" class="col-form-label">Сумма, новое значение:</label>
              <input type="text" required class="form-control" id="new_value" name="new_value" value="<?php echo (isset($old_value) && !empty($old_value))?$old_value:''?>">
              <span class="text-danger"> <?= isset($edit_validation) ? display_error($edit_validation, 'new_value'):''?> </span>
            </div>
            <div class="mb-3">
              <label class="document" for="user_doc">Подтверждающий документ:</label>
              <input type="file" class="form-control" id="user_doc" name="user_doc" single  value="<?php echo (isset($old_userDoc) && !empty($old_userDoc))?$old_userDoc:''?>"/>
              <span class="text-danger"> <?= isset($edit_validation) ? display_error($edit_validation, 'user_doc'):''?> </span>
            </div>
            <div class="mb-3">
              <label class="document" for="reason">Причина редактирования:</label>
              <input type="text" required minlength="20" class="form-control" id="reason" name="reason"  value="<?php echo (isset($old_reason) && !empty($old_reason))?$old_reason:''?>" />
              <span class="text-danger"> <?= isset($edit_validation) ? display_error($edit_validation, 'reason'):''?> </span>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary" id="btn_">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal: edit receipt/expense sum -->
<!-- Modal: delete receipt/expense -->
<div class="modal fade" id="modal_deleteItem" tabindex="-1" aria-labelledby="modal_deleteItemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_deleteItemLabel">Статья прихода</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="" action="<?=base_url('dashboard/delete_item')?>" method="post" enctype = "multipart/form-data">
        <?= csrf_field();?>
        <?php
        if(isset($edit_validation)){
          $old_reason = set_value('status_reason');
        }
        ?>
        <input type="hidden" name="delete_item_type" id="delete_item_type" value="<?php echo (isset($old_itemType) && !empty($old_itemType)) ? $old_itemType : ''?>">
        <input type="hidden" name="delete_record_id" id="delete_record_id" value="<?php echo (isset($record_id) && !empty($record_id)) ? $record_id : ''?>">
        <div class="modal-body">
            <div class="mb-3">
              <label class="document" for="status_reason">Причина удаления:</label>
              <input type="text" class="form-control" id="status_reason" name="status_reason"  value="<?php echo (isset($old_reason) && !empty($old_reason))?$old_reason:''?>" />
              <span class="text-danger"> <?= isset($edit_validation) ? display_error($edit_validation, 'reason'):''?> </span>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary" id="btn_">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal: delete receipt/expense -->
<!-- Modal: history of receipt/expense edition/deletion -->
<div class="modal fade" id="modal_history" tabindex="-1" aria-labelledby="modal_historyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">История</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="modal_history_form" action="<?=base_url('dashboard/approve_deletion')?>" method="post" enctype = "multipart/form-data">
        <?= csrf_field();?>
        <?php
        if(isset($approve_validation)){
          $old_itemType = set_value('edit_item_type');
        }
        ?>
        <input type="hidden" name="aprove_item_type" id="aprove_item_type" value="<?php echo (isset($old_itemType) && !empty($old_itemType)) ? $old_itemType : ''?>">
        <input type="hidden" name="aprove_record_id" id="aprove_record_id" value="<?php echo (isset($record_id) && !empty($record_id)) ? $record_id : ''?>">
        <input type="hidden" name="aprove_new_value" id="aprove_new_value" value="<?php echo (isset($new_value) && !empty($new_value)) ? $new_value : ''?>">
        <input type="hidden" name="aprove_old_value" id="aprove_old_value" value="<?php echo (isset($old_value) && !empty($old_value)) ? $old_value : ''?>">
        <input type="hidden" name="aprove_document" id="aprove_document" value="<?php echo (isset($document) && !empty($document)) ? $document : ''?>">
        <div class="modal-body">
          <h5>Статья:</h5>
          <span id="history_item"></span>
          <hr>
          <h5>Автор изменения:</h5>
          <span id="history_author"></span>
          <hr>
          <h5>Заявленное изменение:</h5>
          <span id="history_edition_name"></span>
          <hr>
          <h5>Причина:</h5>
          <span id="history_reason"></span>
          <hr>
          <h5>Решение:</h5>
          <div id="history_decision_block" class="">
            <div class="input-group">
              <div class="input-group-text">
                <input class="form-check-input mt-0" name = "history_decision" type="radio" value="1" aria-label="Radio button for following text input" required>
              </div>
              <span class="form-control">Подтвердить изменение</span>
            </div>
            <div class="input-group">
              <div class="input-group-text">
                <input class="form-check-input mt-0" name = "history_decision" type="radio" value="0" aria-label="Radio button for following text input">
              </div>
              <span class="form-control">Отказать в изменении</span>
            </div>
            <span class="text-danger"> <?= isset($approve_validation) ? approve_validation($edit_validation, 'history_decision'):''?> </span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
          <button type="submit" class="btn btn-primary" id="btn_">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal: history of receipt/expense edition/deletion -->
<?php } ?>

<!-- Scipts -->
<script src="<?= base_url('assets/vendors/jquery/jquery-3.6.0.min.js')?>"></script>

<!-- <script src="<?php //echo base_url('assets/vendors/bootstrap/popper.min.js')?>"></script> -->
<!-- <script src="<?php //echo base_url('assets/vendors/bootstrap/bootstrap.min.js')?>"></script> -->
<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>
<?php if(isset($page_name) && $page_name == "finance_movements"){  ?>
<script src="<?= base_url('assets/vendors/datatables/datatables.min.js')?>"></script>
<script src="<?= base_url('assets/js/main.js')?>"></script>
<!-- End Scripts -->
<?php } ?>
<?php if(isset($page_name) && $page_name == 'report'){ ?>
<script src="<?= base_url('assets/vendors/jquery/jquery-ui.min.js')?>"></script>
<script src="<?= base_url('assets/js/report_page.js')?>"></script>
<?php } ?>
<?php if(isset($page_name) && $page_name == 'classificators'){ ?>
<script src="<?= base_url('assets/vendors/jquery/jquery-ui.min.js')?>"></script>
<script src="<?= base_url('assets/vendors/datatables/datatables.min.js')?>"></script>
<script src="<?= base_url('assets/vendors/datatables/dataTables.fixedColumns.min.js')?>"></script>
<script src="<?= base_url('assets/js/components/SearchDropdown.js')?>"></script>
<script src="<?= base_url('assets/js/classificators.js')?>"></script>
<script src="<?= base_url('assets/js/cl_employee.js')?>"></script>
<script src="<?= base_url('assets/js/cl_materials.js')?>"></script>
<?php } ?>

<?php if(isset($page_name) && $page_name == 'salary_month'){ ?>
  <script src="<?= base_url('assets/vendors/datatables/datatables.min.js')?>"></script>
  <script src="<?= base_url('assets/vendors/datatables/dataTables.fixedColumns.min.js')?>"></script>
  <script src="<?= base_url('assets/js/salary.js')?>"></script>
<?php } ?>

<?php if(isset($page_name) && $page_name == 'advance_month'){ ?>
  <script src="<?= base_url('assets/vendors/jquery/jquery-ui.min.js')?>"></script>
  <script src="<?= base_url('assets/vendors/datatables/datatables.min.js')?>" ></script>
  <script src="<?= base_url('assets/vendors/datatables/dataTables.fixedColumns.min.js')?>"></script>
  <script src="<?= base_url('assets/js/advance.js')?>" defer></script>
<?php } ?>

<?php if(isset($page_name) && $page_name == 'salary_fond'){ ?>
  <script src="<?= base_url('assets/vendors/jquery/jquery-ui.min.js')?>"></script>
  <script src="<?= base_url('assets/js/salary_view.js')?>"></script>
<?php }?>

<?php if(isset($page_name) && $page_name == 'funds'){ ?>
  <script src="<?= base_url('assets/vendors/jquery/jquery-ui.min.js')?>"></script>
  <script src="<?= base_url('assets/vendors/datatables/datatables.min.js')?>" ></script>
  <script src="<?= base_url('assets/vendors/datatables/dataTables.fixedColumns.min.js')?>"></script>
  <script src="<?= base_url('assets/js/helper.js')?>"></script>
  <script src="<?= base_url('assets/js/analytics_report.js')?>"></script>
<?php }?>

<?php if(isset($page_name) && ($page_name == 'cars' || $page_name == 'indication')){ ?>
  <script src="<?= base_url('assets/vendors/jquery/jquery-ui.min.js')?>"></script>
  <script src="<?= base_url('assets/vendors/datatables/datatables.min.js')?>" ></script>
  <script src="<?= base_url('assets/vendors/datatables/dataTables.fixedColumns.min.js')?>"></script>
  <script src="<?= base_url('assets/js/cars.js')?>"></script>
<?php }?>

</body>
</html>
