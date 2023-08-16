<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="agreements-content">

  <div class="card card-body">
  <search-dropdown nid="materialsList" ntitle="Выберите материал"></search-dropdown>
    <div class="d-flex justify-content-end buttons mt-2">
      <button id="arrivalMaterial" class="btn btn-secondary btn-sm mr-1">Приход материала</button>
      <button id="consumptionMaterial" class="btn btn-secondary btn-sm mr-1">Расход материала</button>
    </div>
    
    <?php
        if(!empty($materials)){
      ?>
    <table class="table table-bordered" id="materials">
      <colgroup>
        <col style="width: 5%">
        <col style="width: 35%">
        <col style="width: 15%">
        <col style="width: 15%">
        <col style="width: auto">
      </colgroup>
      <thead>
        <tr>
          <th scope='col'>№п/п</th>
          <th class="td_text" scope='col'>Наимерование</th>
          <th scope='col'>Количество</th>
          <th class="td_text" scope='col'>Ед.изм.</th>
          <th class="td_text" scope='col'>Описание</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $ind = 1;
          foreach ($materials as $key => $material) {
      ?>
        <tr>
          <td><?php echo $ind++ ?></td>
          <td class="td_text"><?php echo $material['material'] ?></td>
          <td><?php echo $material['amount'] ?></td>
          <td class="td_text"><?php echo $material['unit'] ?></td>
          <td class="td_text"><?php echo $material['description'] ?></td>
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

<script>
  const materials = JSON.parse(<?php echo json_encode($json_materials); ?>);
  let OBJ = {};
  OBJ.materialsList = materials;
  console.log(materials);
</script>