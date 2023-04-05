    <?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <?php echo view('partials/_top_nav.php'); ?>
    <div class="container">
      
      <?php       
      if(isset($validation)) :?>
        <div class="have-errors">
          <?= $validation->listErrors() ?>
          <a href="<?= base_url('dashboard')?>" class="goto">Вернуться</a>
        </div>
      <?php endif;?>
      <?php
      $str = "";
      if(isset($show_item_modal)){
        $str = $show_item_modal;
      }
      $session_activeTab = (session()->has('active_tab'))? session()->get('active_tab') : "";
      $show = "";
      ?>
      <input type="hidden" name="show_item_modal" id="show_item_modal" value="<?php echo (isset($show_item_modal) && !empty($show_item_modal)) ? "1" : ""?>">
      <input type="hidden" name="show_item_modal_edit" id="show_item_modal_edit" value="<?php echo (isset($show_item_modal_edit) && !empty($show_item_modal_edit)) ? "1" : ""?>">
      <input type="hidden" name="show_item_modal_delete" id="show_item_modal_delete" value="<?php echo (isset($show_item_modal_delete) && !empty($show_item_modal_delete)) ? "1" : ""?>">
      <input type="hidden" name="show_modal_history" id="show_modal_history" value="<?php echo (isset($show_modal_history) && !empty($show_modal_history)) ? "1" : ""?>">

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
      <?php
      // echo json_encode($receipts_all);
      ?>
      <div class="row" style = "margin-top:50px;">
        <div class="col-md-12">
          <?php
            if(isset($companies) && !empty($companies)){
              $ind = 0;
          ?>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <?php
              foreach ($companies as $item) {
            ?>
            <li class="nav-item" role="presentation">
              <button class="nav-link <?php echo ($ind == 0) ? 'active':''?> " id="tab_<?php echo $item['id']?>" data-bs-toggle="tab" data-bs-target="#tab_id_<?php echo $item['id']?>" type="button" role="tab" aria-controls="home" aria-selected="true">
                <?php echo $item['name'] ?>
              </button>
            </li>
            <?php
              ++$ind;
            } ?>
          </ul>
          <div class="tab-content" id="myTabContent">
            <?php
              $ind = 0;
              foreach ($companies as $item) {
            ?>
            <div class="tab-pane fade <?php echo ($ind == 0) ? 'show active':''?> " id="tab_id_<?php echo $item['id']?>" role="tabpanel" aria-labelledby="tab_<?php echo $item['id']?>">
              <div class="accordion accordion-warning" id="accordionAccounts">
              <?php
                  if(isset($accounts) && !empty($accounts)){
                    foreach ($accounts as $account_item) {
                      if($account_item['company'] == $item['id']){
                        $show = ($account_item['id'] == intval($session_activeTab)) ? "show" : "";
              ?>

                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading_<?php echo $account_item['id']?>">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $account_item['id']?>" aria-expanded="true" aria-controls="collapse_<?php echo $account_item['id']?>">
                      <?php echo $account_item['name']?>
                    </button>
                  </h2>
                  <div id="collapse_<?php echo $account_item['id']?>" class="accordion-collapse collapse <?php echo $show ?>" aria-labelledby="heading_<?php echo $account_item['id']?>" data-bs-parent="#accordionAccounts">
                    <div class="accordion-body">
                      <?php echo view('dashboard/sub/account_title', ['account' => $account_item, 'receipts_all' => $receipts_all, 'expense_all' => $expense_all]); ?>
                    </div>
                  </div>
                </div>
              <?php
                      }
                    }
                  }
              ?>
              </div>
            </div>
            <?php
                ++$ind;
              }
            ?>
          </div>
          <?php
            }//end if - check "isset($companies)"
          ?>
        </div>
      </div>
    </div>
