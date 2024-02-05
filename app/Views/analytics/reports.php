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
    <?php if (!empty(session()->getFlashData('message'))) { ?>
      <div class="alert <?= session()->getFlashdata('alert-class') ?>">
        <?= session()->getFlashData('message') ?>
      </div>
    <?php } ?>
    <?php if (!empty(session()->getFlashData('contractorsNotFound'))) { ?>
      <p style="padding-left:20px">Не найдеры поставщики:</p>
      <div class="alert alert-danger">
        <?= session()->getFlashData('contractorsNotFound') ?>
      </div>
    <?php } ?>

  </div>
</div>


<div class="container" style = "margin-top:50px;">
  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-upload_file-tab" data-bs-toggle="tab" data-bs-target="#nav-upload_file" type="button" role="tab" aria-controls="nav-upload_file" aria-selected="false">Загрузить файл</button>
        <button class="nav-link" id="nav-reports-tab" data-bs-toggle="tab" data-bs-target="#nav-reports" type="button" role="tab" aria-controls="nav-reports" aria-selected="false">Отчеты</button>
  </nav>
  <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade active show" id="nav-upload_file" role="tabpanel" aria-labelledby="nav-upload_file-tab" tabindex="0">
        <?php echo view('analytics/sub/upload_file', []); ?>
      </div>

      <div class="tab-pane fade" id="nav-reports" role="tabpanel" aria-labelledby="nav-reports-tab" tabindex="0">
        <?php echo view('analytics/sub/reports', []); ?>
      </div>
  </div>

</div>