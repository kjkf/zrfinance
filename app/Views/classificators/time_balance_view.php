<div class="wrapper">
<?php //d($balance_for_current_year); ?>
<h4 class="classif_title">Баланс рабочего времени на 2023 год</h4>
  <table class="time_balance">
  <colgroup>
    <col style="background-color: #D6EEEE; width: auto">
    <col span=10 style="background-color: #D6EEEE; width: 9%">
  </colgroup>
    <thead>
      <tr>
        <th rowspan="5">
        Месяц, квартал, полугодие, 9 месяцев, год 
        </th>
        <th colspan=2>Количество дней</th>
        <th colspan=8>Рабочие дни</th>
      </tr>
      <tr>
        <th rowspan=3>Календарные дни</th>
        <th rowspan=3>Календарные дни без праздников</th>
        <th colspan=4>40-часовая рабочая неделя</th>
        <th colspan=4>36-часовая рабочая неделя</th>
      </tr>
      <tr>
        <th colspan=4>График работы с 1-го числа до конца месяца</th>
        <th colspan=4>График работы с 1-го числа до конца месяца</th>
      </tr>
      <tr>
        <th colspan=2>пятидневка</th>
        <th colspan=2>шестидневка</th>
        <th colspan=2>пятидневка</th>
        <th colspan=2>шестидневка</th>
      </tr>
      <tr>
        <th colspan=2>дни</th>
        <th>дни</th>
        <th>часы</th>
        <th>дни</th>
        <th>часы</th>
        <th>дни</th>
        <th>часы</th>
        <th>дни</th>
        <th>часы</th>
      </tr>
    </thead>


  <?php
  if (isset($balance_for_current_year) && !empty($balance_for_current_year)) : ?>
    <tbody>
    <?php foreach ($balance_for_current_year as $item) : ?>
      <tr>
        <td><?=getMonthByNum($item['month'] - 1)?></td> <!---1 потому что в БД месяца начинаются с 1-->
        <td><?=$item['calendar_days']?></td>
        <td><?=$item['working_calendar_days']?></td>
        <td><?=$item['working_5_days']?></td>
        <td><?=$item['w40_5d_hours']?></td>
        <td><?=$item['working_6_days']?></td>
        <td><?=$item['w40_6d_hours']?></td>
        <td><?=$item['working_5_days']?></td>
        <td><?=$item['w36_5d_hours']?></td>
        <td><?=$item['working_6_days']?></td>
        <td><?=$item['w36_6d_hours']?></td>
      </tr>    
    <?php endforeach;?>
    </tbody>
  <?php else :?>
    <tbody>
      <tr class="empty">
        <td colspan=11>Нет записей</td>
      </tr>
    </tbody>
  <?php endif;?>
  </table>
</div>