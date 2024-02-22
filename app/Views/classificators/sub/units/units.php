<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="agreements-content">

  <div class="card card-body">
    <?php echo view('classificators/sub/add_agreement', ['company_id' => $company['id'], 'company_name' => $company['name']]); ?>
    <?php
        if(!empty($materials)){
      ?>
    <table class="table table-bordered">
      <thead>
        <th scope='col'>№п/п</th>
        <th scope='col'>Наимерование</th>
        <th scope='col'>Ед.изм.</th>
        <th scope='col'>Описание</th>
      </thead>
      <tbody>
        <?php
          $ind = 1;
          foreach ($materials as $key => $material) {
      ?>
        <tr>
          <td><?php echo $ind++ ?></td>
          <td><?php echo $material['name'] ?></td>
          <td><?php echo $material['units'] ?></td>
          <td><?php echo $material['descr'] ?></td>
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