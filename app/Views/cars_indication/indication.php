<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<div class="container">
  <div class="report-content">
    <div class="row ">
      <div class="d-flex justify-content-center mb-3 mt-3" >
        <h4>Расход бензина</h4>
      </div>
    </div>
    <?php if (isset($car_info) && !empty($car_info)) {?>
    <div class="row ">
      <div class="d-flex justify-content-end mb-3" >
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_addIndication" id="addIndicationBtn">Добавить расход</button>
      </div>
    </div>
    <?php }?>
    <div class="row report-content">
      <div class="col-md-12 col-md-offset-12" id = "" >
        <table id = "tableIndication" style = "width:100%">
          <thead>
            <tr>
              <td  class="no-sort" width = "10%">№ п/п</td>
              <td width = "30%">Дата</td>
              <td width = "30%">Машина</td>
              <td width = "20%">Показание</td>
              <td  class="no-sort" width = "auto">Фото</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($indications && count($indications) > 0) :
              $count = 1;?>
              <?php foreach($indications as $item) :?>
                <tr>
                  <td><?=$count++; ?></td>
                  <td><?php echo date("d.m.Y H:i", strtotime($item["date_time"]) );?></td>
                  <td><?=$item["car_name"]?></td>
                  <td><?=$item["indication"]?></td>
                  <td><img class="indication-pic" src="<?=base_url("public/uploads/images/".$item["pic"])?>"></td>
                </tr>
              <?php endforeach?>
            <?php else :?>
              <tr class="empty-row">
                <td colspan="5">
                  Нет записей!
                </td>
              </tr>
            <?php endif?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

 </div>
 <?php
 if (isset($car_info) && !empty($car_info)) {
  echo view('partials/modals/_add_indication_modal.php');
 }
 ?>

 <style>
  .indication-pic {
    max-height: 100px;
    width: auto;
  }
 </style>