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

<?php 
if (isset($fzp) && !empty($fzp)) :?>

<div class="container">

  <div class="d-flex justify-content-end buttons mt-2">
    <?php
//    d($bonus_fines);
    $user_role = $user['role'];
    $status = isset($fzp) && !empty($fzp) ? $fzp['is_approved'] : -1;
    if ($status != 1) :
    ?>
      <?php if ($status == "4" && $user_role == 3) { ?>
        <button class="btn btn-secondary btn-sm mr-1" id="submit">Утвердить</button>
        <!--<button class="btn btn-secondary btn-sm mr-1" id="return">Отправить на доработку</button>-->
        <button class="btn btn-secondary mr-1" data-bs-toggle="modal" data-bs-target="#modal_Return">Отправить на доработку</button>

      <?php } else if (($status == "0" || $status == "2") && $user_role == 5) { ?>
        <button class="btn btn-secondary btn-sm mr-1" id="send">Отправить на утверждение</button>
      <?php } ?>

    <?php endif; ?>
    <a href="<?= base_url("salary") ?>" class="btn btn-info btn-sm mr-1" id="close">Закрыть</a>
  </div>

  <?php if ($status == "2" && $user_role == 5) : ?>
    <div>
      <div class="alert alert-danger mt-2" role="alert">
        <?php echo $fzp['rejection_reason'] ?>
      </div>
    </div>
  <?php endif; ?>

  <?php
  //d($employees);
  if (isset($employees) && !empty($employees)) : ?>
    <div class="content advances">
      <div class="salary-head d-flex justify-content-start align-items-center mr-3">
        <div class="flex-grow-1">
          <p>
            Всего <?php echo word_form('сотрудник', count($employees)) ?>
          </p>

          <p> Авансовая ведомость за <?= $month ?> <?= $year ?> года</p>
        </div>
        <div class="salary-total flex-grow-1">
          <p>Общая сумма к выплате <span></span></p>
        </div>
      </div>
      <?php
      //foreach ($employees as $key => $company) :
      //  $count = 1;
      //  $companyInfo = explode("|", $key);
      //  $companyId = $companyInfo[0];
      //  $companyName = $companyInfo[1];
      //?>
        <div class="department">
          <!--<p>Подразделение: <?php // echo $companyName ?></p>-->
          <?php //d($employees);
          ?>
          <table class="employee_salary display compact" width="100%">
            <thead>
              <tr>
                <th class="th_text clip" title="направление">направление</th>
                <th class="th_col">№ </th>
                <th class="th_text th_long_text">Ф.И.О.</th>
                <th class="th_text th_long_text">Должность</th>
                <th class="th_text">Компания</th>
                <th class="th_num">Кол-во рабочих часов в мес.</th>
                <th class="th_money">Оклад</th>
                <th class="th_money">Авансы</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $count = 1;
              foreach ($employees as $employee) :
                
              ?>
                <tr class="trow" data-trid="<?= $employee['id'] ?>">
                  <td><span><?= $employee['direction'] ?></span></td>
                  <td><?= $count++ ?></td>
                  <td><span><?= $employee['surname'] ?> <?= $employee['name'] ?></span></td>
                  <td><span><?= $employee['position'] ?></span></td>
                  <td><span><?= $employee['company'] ?></span></td>
                  <td><span><?= $employee['working_hours_per_month'] ?></span></td>
                  <td><span><?= number_format($employee['employee_salary'], 2, '.', ' ') ?></span></td>
                  <td><span><?= number_format($employee['all_advances'], 2, '.', ' ') ?></span></td>
                </tr>
              <?php endforeach; ?>

            </tbody>
            <tfoot>
              <tr class="table_footer">
                <th></th>
                <th></th>
                <th>Итого</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      <?php // endforeach; ?>

    </div>
  <? else : ?>
    <h2>Нет информации о сотрудниках</h2>
  <? endif; ?>

</div>

<input type="hidden" value="<?= $fzp['id'] ?>" id="fzp_id">
<input type="hidden" value="<?= $fzp['date_time'] ?>" id="fzp_date">
<input type="hidden" value="<?= $fzp['mrp'] ?>" id="mrp">
<input type="hidden" value="<?= $fzp['min_zp'] ?>" id="min_zp">
<input type="hidden" value="<?= $fzp['author'] ?>" id="fzp_author">
<input type="hidden" value="<?= $user_role ?>" id="role">

<!--<input type="hidden" value="<?php //echo $bonus_fines ?>" id="bonus_type">
<input type="hidden" value="<?php //echo $bonus_fines["fines"]?>" id="fines_type">-->


<?php endif;?>

<script>
  const EMPLOYEES = JSON.parse(<?php echo json_encode($json); ?>)
  console.log(EMPLOYEES);
</script>

<?php echo view('partials/modals/_advances_modal', []); ?>
