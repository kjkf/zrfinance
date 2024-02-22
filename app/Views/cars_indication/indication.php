<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<div class="container">
  <div class="report-content">
    <div class="row ">
      <div class="d-flex justify-content-center mb-3 mt-3" >
        <h4>Расход бензина</h4>
      </div>
    </div>
    <div class="row ">
      <div class="d-flex justify-content-end mb-3" >
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_addIndication" id="addIndicationBtn">Добавить расход</button>
      </div>
    </div>
    <div class="row report-content">
      <div class="col-md-12 col-md-offset-12" id = "" >
        <table id = "cars" style = "width:100%">
          <thead>
            <tr>
              <td width = "10%">№ п/п</td>
              <td width = "30%">Дата</td>
              <td width = "30%">Машина</td>
              <td width = "20%">Показание</td>
              <td width = "auto">Фото</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($indications && count($indications) > 0) :
              $count = 1;?>
              <?php foreach($indications as $item) :?>
                <tr>
                  <td><?=$count++; ?></td>
                  <td><?php echo date("d.m.Y H:i", strtotime($item["date"]) );?></td>
                  <td><?=$item["car_name"]?></td>
                  <td><?=$item["indication"]?></td>
                  <td></td>
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
 <?php echo view('partials/modals/_add_indication_modal.php'); ?>
