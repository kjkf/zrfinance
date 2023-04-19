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
  <?php
  
  //d($employees);
  if (isset($employees) && !empty($employees)) : ?>
  <div class="content salary">
    <div class="salary-head">
      <p>
        Всего <?php echo word_form('сотрудник', $employees_count)?>
      </p>

      <p> Зарплата за <?=$month?> <?=$year?> года</p>
    </div>
    <?php 
          foreach($employees as $key=>$company):
            $count = 1;
            $companyInfo = explode("|", $key);
            $companyId = $companyInfo[0];
            $companyName = $companyInfo[1];
            ?>
    <div class="department">
      <p>Подразделение: <?php echo $companyName?></p>
      <?php //d($company);?>
      <table class="employee_salary display compact" id="salary_company_<?=$companyId?>">
        <thead>
          <tr>
            <th class="th_text clip">направление</th>
            <th class="th_col">№ </th>
            <th class="th_text">Ф.И.О.</th>
            <th class="th_text">Должность</th>
            <th class="th_text">Компания</th>
            <th class="th_num">Кол-во рабочих часов в мес.</th>
            <th class="th_num">Кол-во отработаных часов</th>
            <th class="th_money">Оклад</th>
            <th class="th_money">Начислено по отраб. дням</th>
            <th class="th_money">Прибавки</th>
            <th class="th_money">Удержания</th>
            <th class="th_money">Итого начислено</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($company as $employee) :
            //$json[$employee['id']] = $employee; ?>
          <tr class="trow" data-trid="<?=$employee['id']?>">
            <td><span><?=$employee['direction']?></span></td>
            <td><?=$count++?></td>
            <td><span><?=$employee['surname']?> <?=$employee['name']?></span></td>
            <td><span><?=$employee['position']?></span></td>
            <td><span><?=$employee['company']?></span></td>
            <td><span>11</span></td>
            <td><span>12</span></td>
            <td><span><?=number_format($employee['salary'], 2, '.', ' ')?></span></td>
            <td><span></span></td>
            <td><span></span></td>
            <td><span></span></td>
            <td><span></span></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <?php endforeach;?>

  </div>
  <? else : ?>
  <h2>Нет информации о сотрудниках</h2>
  <? endif; ?>
</div>

<script>
  const EMPLOYEES = JSON.parse(<?php echo json_encode($json);?>);
</script>