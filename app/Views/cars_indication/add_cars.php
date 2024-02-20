<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<div class="container">
  <div class="report-content">
    <div class="row ">
      <div class="d-flex justify-content-center mb-3 mt-3" >
        <h4>Список машин</h4>
      </div>
    </div>
    <div class="row ">
      <div class="d-flex justify-content-end mb-3" >
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_addCar" id="addCarBtn">Добавить машину</button>
      </div>
    </div>
    <div class="row report-content">
      <div class="col-md-12 col-md-offset-12" id = "" >
        <table id = "cars" style = "width:100%">
          <thead>
            <tr>
              <td width = "10%">№ п/п</td>
              <td width = "30%">Водитель</td>
              <td width = "30%">Машина</td>
              <td width = "20%">Расход</td>
              <td width = "auto">Примечание</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($cars && count($cars) > 0) :
              $count = 1;?>
              <?php foreach($car as $cars) :?>
                <tr>
                  <td><?=$count++; ?></td>
                  <td><?=$car["user"]?></td>
                  <td><?=$car["car_name"]?></td>
                  <td><?=$car["consumption"]?></td>
                  <td></td>
                </tr>
              <?php endforeach?>
            <?php else :?>
              <tr>
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
 <?php echo view('partials/modals/_add_car_modal.php'); ?>
