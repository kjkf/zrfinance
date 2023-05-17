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
  <div class="row fzp-wrapper">
    <?php if(isset($currentYearFZPs) && !empty($currentYearFZPs)) :?>
      <h3>Утвержденные ФЗП</h3>
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

  <!--тут созданные ФЗП-->
  <div class="row">
    <?php if(isset($currentYearWorkingFZPs) && !empty($currentYearWorkingFZPs)) :?>
      <h3>Созданные, но ещё не утвержденные ФЗП</h3>
      <table>
    <?php foreach($currentYearWorkingFZPs as $fzp) :
      $fzpId = $fzp['id'];
      $timestamp = strtotime($fzp['date_time']);
      //if (date("n") == date("n", $timestamp)) continue;
      $fzp_month = getMonthByNum(date("n", $timestamp) - 1);
      $fzp_year = date('Y', $timestamp);
      ?>
      <tbody>
        <tr>
          <td><?=$fzp_month?></td>
          <td>
            <a href="<?=base_url('salary/fzp/'.$fzpId)?>">Открыть ФЗП за <?=$fzp_month?> месяц  <?=$fzp_year?></a>
            <?php
              if ($fzp['is_advance'] == 1) :
            ?>
            <br>
            <a href="<?=base_url('salary/advance/'.$fzpId)?>">Открыть авансовую ведомость за <?=$fzp_month?> месяц  <?=$fzp_year?></a>            
            <?php endif; ?>
          </td>
          <td>
          <?php
              if ($fzp['is_advance'] != 1) :
            ?>
            <a href="<?=base_url('salary/create_advance/'.$fzp['id'])?>" title="Создать авансовую ведомость" data-fzpid="<?=$fzp['id']?>" class="btn-icon add-btn" name="edit_item"><i class="fas fa-plus-circle"></i> </a>
            <?php endif; ?>
          </td>
        </tr>
      </tbody>
      
    <?php endforeach;?>
    </table>
    <?php endif;?>
  </div>

  <?php 
  $user_role = $user['role'];
  if ($user_role != 3) { ?>
    <div class="mt-3">
    <?php $cur_month = getMonthByNum(date('n')-1);?>
    <?php if (isset($is_current_fzp) && empty($is_current_fzp)) :?>
     <a class="btn btn-secondary" href="<?=base_url('salary/fzp')?>">Создать ФЗП за текущий месяц(<?= $cur_month?>)</a>    <!--если нет ФЗП на этот месяц-->

    <?php endif;?>

    <input type="hidden" id="fzp_month">
    <input type="hidden" id="fzp_year">
    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_chooseDate">Создать ФЗП</button>    <!--если нет ФЗП на этот месяц-->
  </div>
  <?php }?>
  

</div>

<div class="modal fade" tabindex="-1" aria-labelledby="create_fzp_for_date" id="modal_chooseDate" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="create_fzp_for_date">Выберите месяц и год, для которых хотите создать ФЗП</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <input type="text" id="fzpDate" class="date-picker"  autocomplete="off"/>
        </div>
        <div class="d-flex justify-content-end buttons mt-1">
          <button class="btn btn-info btn-sm mr-1" id="create_fzp">Создать ФЗП</button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .ui-datepicker-calendar {
    display: none;
    }
</style>