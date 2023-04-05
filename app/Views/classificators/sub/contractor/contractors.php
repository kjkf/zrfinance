<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="agreements-nav" >
  <p>
    <?php
      foreach ($companies as $key => $company) {
        $btn = '<button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#zr_'.$company['id'].'" aria-expanded="false" aria-controls="zr_'.$company['id'].'">'
                    .$company['name'].
                '</button>';
        echo $btn;
      }
    ?>
  </p>
</div>

<div class="agreements-content">
  <?php
    foreach ($companies as $key => $company) {
  ?>
  <div class="collapse" id="zr_<?php echo $company['id']?>">
    <div class="card card-body">
      <?php echo view('classificators/sub/add_agreement', ['company_id' => $company['id'], 'company_name' => $company['name']]); ?>
      <?php
        $company_agreements = $agreements[$company['id']];

        if(!empty($company_agreements)){
      ?>
      <table class="table table-bordered">
        <thead>
          <th scope = 'col'>№п/п</th>
          <th scope = 'col'>Контрагент</th>
          <th scope = 'col'>Тип контрагента</th>
          <th scope = 'col'>ИИН</th>
          <th scope = 'col'>Адрес</th>
          <th scope = 'col'>ФИО Руководителя</th>
        </thead>
        <tbody>
      <?php
          $ind = 1;
          foreach ($contractors as $key => $contractor) {
      ?>
          <tr>
            <td><?php echo $ind++ ?></td>
            <td><?php echo $contractor['name'] ?></td>
            <td><?php echo $contractor['company_type'] ?></td>
            <td><?php echo $contractor['INN'] ?></td>
            <td><?php echo $contractor['adress'] ?></td>
            <td><?php echo $contractor['head_fullname'] ?></td>
          </tr>
      <?php
          }
      ?>
        </tbody>
      </table>
      <?php
      }else{
      ?>
          Нет записей
      <?php } ?>
    </div>
  </div>
  <?php
    }
  ?>
</div>
