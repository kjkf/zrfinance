<?php //defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<?php echo view('partials/_top_nav.php'); ?>
<div class="container">
  <?php
  if (isset($validation)) : ?>
  <div class="have-errors">
    <?= $validation->listErrors() ?>
    <a href="<?= base_url('dashboard') ?>" class="goto">Вернуться</a>
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

  <div class="row">
    <?php if (isset($is_current_fzp) && empty($is_current_fzp)) :?>
      <a href="<?=base_url('salary/fzp')?>">Создать ФЗП за текущий месяц</a>
    <?php else :?>
      <a href="<?=base_url('salary/fzp/12')?>">Открыть ФЗП за текущий месяц</a>
    <?php endif;?>
  </div>

</div>