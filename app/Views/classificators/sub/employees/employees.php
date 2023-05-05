<script src="<?= base_url('assets/js/cl_employee.js')?>"></script>
<div class="container" style="margin-top:50px;">
  <div class="wrapper">
    <table class="employees" id = "tbl_employees">
    <colgroup>
      <col style="width: 5%">
      <col style="width: 20%">
      <col style="width: 10%">
      <col style="width: 15%">
      <col style="width: 15%">
      <col style="width: 10%">
      <col style="width: 10%">
      <col style="width: auto">
    </colgroup>
      <thead>
        <tr>
          <th>№ п/п</th>
          <th class="td_text">ФИО</th>
          <th class="td_text">Компания</th>
          <th class="td_text">Отдел</th>
          <th class="td_text">Должность</th>          
          <th class="td_text">Email</th>          
          <th class="td_text">Телефон</th>          
          <th class="td_money">Зарплата</th>             
        </tr>
      </thead>
      <tbody>
        <?php if (isset($employees) && !empty($employees)) : ?>
          <?php 
            $count = 1;
            foreach($employees as $key=>$company) :?>
              <tr class="trSpan">
                <td colspan=8 </td><?= $key ?></td>
              </tr>
              <?php foreach($company as $employee) :?>
                <tr class="emp_info">
                  <td class=""><?=$count++;?></td>
                  <td class="td_text"><?= $employee['fio'] ?></td>
                  <td class="td_text"><?= $employee['company'] ?></td>
                  <td class="td_text"><?= $employee['department'] ?></td>
                  <td class="td_text"><?= $employee['position'] ?></td>
                  <td class="td_text"><?= $employee['email'] ?></td>
                  <td class="td_text"><?= $employee['telephone'] ?></td>
                  <td class="td_money"><?=  number_format($employee['salary'], 2, '.', ' ') ?></td>
                </tr>
              <?php endforeach;?>
          
          <?php endforeach;?>
        <?php else :?>
          <tr class="empty">
            <td colspan=8>Нет записей</td>
          </tr>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>

<?php echo view('partials/_modals', []); ?>