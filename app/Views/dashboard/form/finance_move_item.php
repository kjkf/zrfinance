<div class="container">
  <div class="row" style = "margin-top:30px; background-color:#A50000; color: white">
    <div class="col-md-12 col-md-offset-12">
      <h4><?=$title?>&nbsp | &nbsp; <?=$user['name']?></h4>
      <a href="<?php echo  site_url('auth/logout')?>" style="text-decoration:none; color: white" class="right"> Exit</a>
    </div>
  </div>
  <div class="row" style = "">
    <div class="col-md-12 col-md-offset-12" style="padding-left: 0; padding-right:0;">
      <?php if(!empty(session()->getFlashData('fail'))){ ?>
        <div class="alert alert-danger">
          <?=session()->getFlashData('fail')?>
        </div>
      <?php } ?>
      <?php if(!empty(session()->getFlashData('success'))){ ?>
        <div class="alert alert-success">
          <?=session()->getFlashData('success')?>
        </div>
      <?php } ?>
    </div>
  </div>

  <div class="row" style = "margin-top:50px;">
    <div class="col-md-12">
      <form id="item_form" action="<?=base_url('dashboard/save_item')?>" method="post" enctype = "multipart/form-data">
      <?php echo csrf_field();?>
      <?php
        if(isset($validation)){
          $old_itemType = set_value('item_type');
          $old_itemName = set_value('item_name');
          $old_agreement = set_value('agreement');
          $old_employee = set_value("employee");
          $old_description = set_value("description");
          $old_sum = set_value("sum");
          $old_userDoc = set_value("user_doc");
          $old_company_id = set_value("company_id");
          $old_company_account = set_value("company_account");
          $old_agreement_type= set_value("agreement_type");
          $old_author= set_value("author");
          //echo "old_agreement = $old_agreement" ;
          $items = [];
          if(isset($item_type) && !empty($item_type)){
            switch ($item_type) {
              case 'receipt':
                $items = $receipt_items;
                break;

              case 'expense':
                $items = $expence_items;
                break;

              default:
                $items = $receipt_items;
                break;
            }
          }
        }

        echo ">>>>".set_value("agreement_type");

        if(isset($validation))
          echo display_error($validation, 'item_name');
      ?>
        <div class="modal-body">
            <div class="row">
              <div class="col">
                <span class="modal-subtitle warning" id="modal_addItemSubLabel" style="color: grey"></span>
              </div>
            </div>
            <input type="hidden" name="company_id" id="company_id" value="<?php echo (isset($old_company_id) && !empty($old_company_id)) ? $old_company_id : ''?>">
            <input type="hidden" name="company_account" id="company_account" value="<?php echo (isset($old_company_account) && !empty($old_company_account)) ? $old_company_account : ''?>">
            <input type="hidden" name="item_type" id="item_type" value="<?php echo (isset($old_itemType) && !empty($old_itemType)) ? $old_itemType : ''?>">
            <input type="hidden" name="agreement_type" id="agreement_type" value="<?php echo (isset($agreement_type) && !empty($agreement_type)) ? $agreement_type : ''?>">
            <input type="hidden" name="date" id="date" value="">
            <input type="hidden" name="author" id="author" value="<?php echo (isset($old_author) && !empty($old_author)) ? $old_author : ''?>">
            <div class="mb-3">
              <label for="item_name" class="col-form-label">Наименование:</label>
              <select class="form-select" aria-label="Default select example" id = "item_name" name = "item_name">
                <?php
                if(isset($items)){
                  echo "<option>Выберите</option>";
                  foreach ($items as $item) {
                    $need_agreement = (isset($item['need_employee']) && $item['need_agreement'] == 1)?true:false;
                    $need_employee = (isset($item['need_employee']) && $item['need_employee'] == 1)?true:false;
                    if($item['id'] == $old_itemName){
                      $option = "<option selected value='".$item['id']."' item_type='".$item_type."' need_agreement = '".$need_agreement."' need_employee = '".$need_employee."'>".$item['name']."</option>";
                    }else{
                      $option = "<option value='".$item['id']."' item_type='".$item_type."' need_agreement = '".$need_agreement."' need_employee = '".$need_employee."'>".$item['name']."</option>";
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
            <div class="mb-3">
              <label for="agreement" class="col-form-label" >Договор:</label>
              <select class="form-select" aria-label="Default select example" id = "agreement" name = "agreement" <?php (isset($old_agreement) && !empty($old_agreement))?"":"disabled"?>>
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
            <div class="mb-3">
              <label for="employee" class="col-form-label" >Сотрудник:</label>
              <select class="form-select" aria-label="Default select example" id = "employee" name = "employee" disabled>
                <option selected>Выберите</option>
                <option >Что-то пошло не так</option>
              </select>
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'employee'):''?> </span>
            </div>
            <div class="mb-3">
              <label for="description" class="col-form-label">Описание:</label>
              <input type="text" class="form-control" id="description" name="description"  minlength='20' maxlength='500' value="<?php echo (isset($old_description) && !empty($old_description))?$old_description:''?>">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'description'):''?> </span>
            </div>
            <div class="mb-3">
              <label for="sum" class="col-form-label">Сумма:</label>
              <input type="text" class="form-control" id="sum" name="sum" value="<?php echo (isset($old_sum) && !empty($old_sum))?$old_sum:''?>">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'sum'):''?> </span>
            </div>
            <div class="mb-3">
              <label class="document" for="user_doc">Подтверждающий документ:</label>
              <input type="file" class="" id="user_doc" name="user_doc" single  value="<?php echo (isset($old_userDoc) && !empty($old_userDoc))?$old_userDoc:''?>"/>
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
