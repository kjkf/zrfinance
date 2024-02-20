<?php echo view('partials/_top_nav.php'); ?>

<?php
if (isset($validation)) : ?>
  <div class="have-errors">
    <?= $validation->listErrors() ?>
    <a href="<?= base_url('salary') ?>" class="goto">Вернуться</a>
  </div>
<?php endif; ?>

<div class="row">
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

<div class="container">

  <div class="d-flex justify-content-end buttons mt-2">
    <?php
//    d($bonus_fines);
    $user_role = $user['role'];
      
    ?>
      <a href="<?= base_url("/request/create") ?>" class="btn btn-success btn-sm mr-1" id="create_deal">Создать</a>
      <a href="<?= base_url() ?>" class="btn btn-info btn-sm mr-1" id="close">Закрыть</a>
  </div>

  <?php
  // d($employees);
  if (isset($requests) && !empty($requests)) : ?>
    <div class="content salary">
      <div class="department">
          <h3>Заявки на закуп</h3>
          <table class="display compact" id="requests" width="100%">
            <thead>
              <tr>
                <th class="th_col">№ </th>
                <th class="th_text">Номер сделки</th>
                <th class="th_text th_long_text">Номер заявки</th>
                <th class="th_text">Дата</th>
                <th class="th_text">Статус</th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>            
          </table>
        </div>
      <?php // endforeach; ?>

    </div>
  <? else : ?>
    <h2>Нет информации о заявках</h2>
  <? endif; ?>

</div>

<input type="hidden" value="<?= $user_role ?>" id="role">


<!--<template id="rejection_reason">-->
<!--<div class="modal fade" id="modal_Return" tabindex="-1" aria-labelledby="modal_addItemLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_addItemLabel">Причина отказа</h5>
        
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <textarea id="rej_reason" class="form-control" cols="30" rows="5" placeholder="Укажите причину возвращения на доработку" required></textarea>
        </div>
        <div class="d-flex justify-content-end buttons mt-1">
          <button class="btn btn-info btn-sm mr-1" id="return">Отправить на доработку</button>
        </div>
      </div>
    </div>
  </div>
</div>-->

<?php //echo view('partials/modals/_salary_modal', []); ?>