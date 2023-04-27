<?php //defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<?php echo view('partials/_top_nav.php'); ?>
<div class="container salary-view">
  <?php
  if (isset($validation)) : ?>
  <div class="have-errors">
    <?= $validation->listErrors() ?>
    <a href="<?= base_url('salary') ?>" class="goto">Вернуться</a>
  </div>
  <?php endif; ?>
  <div class="row" style="">
    <div class="col-md-12 col-md-offset-12" style="padding-left: 0; padding-right:0;">
      <?php if (!empty(session()->getFlashData('fail'))) { ?>
      <div class="alert alert-danger">
        <?= session()->getFlashData('fail') ?>
      </div>
      <?php } ?>
      <?php if (!empty(session()->getFlashData('success'))) { ?>
      <div class="alert alert-success">
        <?= session()->getFlashData('success') ?>
      </div>
      <?php } ?>
    </div>
  </div>

  <!--тут все утвержденные ФЗП-->
  <div class="row">
    <?php if(isset($currentYearFZPs) && !empty($currentYearFZPs)) :?>
    <?php foreach($currentYearFZPs as $fzp) :
      $fzpId = $fzp['id'];
      $timestamp = strtotime($fzp['date_time']);
      //if (date("n") == date("n", $timestamp)) continue;
      $fzp_month = getMonthByNum(date("n", $timestamp) - 1);
      $fzp_year = date('Y', $timestamp);
      ?>
      <div>
        <a href="<?=base_url('salary/fzp/'.$fzpId)?>">Открыть ФЗП за <?=$fzp_month?> месяц  <?=$fzp_year?></a>
      </div>
    <?php endforeach;?>
    <?php endif;?>
  </div>

  
  <div class="row">
    <?php if (isset($is_current_fzp) && empty($is_current_fzp)) :?>
     <a href="<?=base_url('salary/fzp')?>">Создать ФЗП за текущий месяц</a>    <!--если нет ФЗП на этот месяц-->
    <?php elseif ($is_current_fzp[0]['is_approved'] != "1"):
      $fzp_id = $is_current_fzp[0]['id']?>
      <a href="<?=base_url('salary/fzp/'.$fzp_id)?>">Открыть ФЗП за текущий месяц</a> <!--если есть не утрвержденный ФЗП на этот месяц-->
    <?php endif;?>
  </div>

</div>