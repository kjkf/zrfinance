<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<div class="container">
  <div class="report-content">
    <div class="row ">
      <div class="d-flex justify-content-center mb-3 mt-3" >
        <h4>Расход бензина</h4>
      </div>
    </div>
    <?php if (isset($filter) && $filter !== 'all') {?>
    <div class="row ">
      <div class="d-flex justify-content-end mb-3" >
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_addIndication" id="addIndicationBtn">Добавить расход</button>
      </div>
    </div>
    <?php }?>

    <?php //print_r($indications);?>

    <div class="row report-content">
    <?php if (isset($total_consuption) && count($total_consuption) > 0) :?>
          <div class="totals">
            Итого израсходавано:  <?php echo number_format($total_consuption[0]["total_consumption"], 2)." л."?>
          </div>
      <?php endif;?>
      <?php if (isset($total_coupons) && count($total_coupons) > 0) :?>
        <div class="totals">
          Итого выдано:  <?php echo $total_coupons[0]["quantity"]." л. -  ".$total_coupons[0]["money"]."тг ."?>
        </div>
      <?php endif;?>
      <div class="col-md-12 col-md-offset-12 table-flex" id = "" >

        <table id = "tableIndication" style = "width:100%">
          <thead>
            <tr>
              <td rowspan=2 class="no-sort" width = "5%">№ п/п</td>
              <td rowspan=2 width = "15%">Дата</td>
              <?php if (isset($filter) && $filter == 'all') {?>
                <td rowspan=2 width = "20%">Водитель</td>
              <?php }?>  
              <td colspan=2 width = "30%">Показание</td>
              <td rowspan=2 width = "10%">Км</td>
              <td rowspan=2 width = "10%">Расход</td>
              
            </tr>
            <tr>
                <td>Начало дня</td>
                <td>Конец дня</td>
            </tr>
          </thead>
          
          <tbody>
         
            <?php 
            //print_r($indications);
            
            if ($indications && count($indications) > 0) :
              $count = 1;?>
              
              <?php foreach($indications as $item) :?>
                <tr>
                  <td><?=$count++; ?></td>
                  <td>
                    <?php echo date("d.m.Y H:i", strtotime($item["date_time"]) );?>
                    <?php if (isset($item["date_time_end"])) {
                      echo "<br />";
                      echo date("d.m.Y H:i", strtotime($item["date_time_end"]) );
                    } ?>
                  </td>
                  <?php if (isset($filter) && $filter == 'all') {?>
                  <td><?=$item["driver"]?></td>
                  <?php }?>
                  <td>
                    <?=$item["indication"]?>
                    <a href="#" class="indication-pic" data-src="<?=base_url("public/uploads/images/".$item["pic"])?>" data-alt="Показания <?=$item["car_name"]?> за <?php echo date("d.m.Y H:i", strtotime($item["date_time"]) );?>">Фото</a>
                  </td>
                  <td>
                    <?php 
                    $km = "-";
                    $consumption = "-";
                    if (isset($item["indication_end"]) && !empty($item["indication_end"]) && $item["indication_end"] > 0) {
                      $km = $item["indication_end"] - $item["indication"];
                      $consumption = $km * $item["consumption"] / 100;
                      ?>
                    <?=$item["indication_end"]?>
                    <a href="#" class="indication-pic" data-src="<?=base_url("public/uploads/images/".$item["pic_end"])?>" data-alt="Показания <?=$item["car_name"]?> за <?php echo date("d.m.Y H:i", strtotime($item["date_time"]) );?>">Фото</a>
                    <?php }?>
                  </td>
                  <td><?=$km?></td>
                  <td><?=$consumption?></td>
                </tr>
              <?php endforeach?>
            <?php else :?>
              <tr class="empty-row">
                <td colspan="7">
                  Нет записей!
                </td>
              </tr>
            <?php endif?>
          </tbody>
        </table>
        <?php if (isset($filter) && $filter !== 'all') {?>
        <table id="coupons_table">
          <thead>
            <tr>
              <td rowspan=2 width = "10%">Выданные талоны</td>
            </tr>
          </thead>
          <tbody>
          <?php if (isset($coupons) && count($coupons) > 0) :
              $count = 1;?>
              
              <?php foreach($coupons as $item) :?>
                <tr>
                  <td>
                    <? echo date("d.m.Y", strtotime($item["date_time"]))." - ".$item["quantity"].'л';?>
                  </td>
                </tr>
              <?php endforeach?>
          <?php else :?>
              <tr class="empty-row">
                <td colspan="7">
                  Нет записей!
                </td>
              </tr>
            <?php endif?>
          </tbody>
        </table>
        <?php }?>
      </div>
    </div>
  </div>

 </div>
 <?php
 if (isset($filter) && $filter !== 'all') {
  echo view('partials/modals/_add_indication_modal.php');
 }
 ?>

 <?php echo view('partials/modals/_enlarge_pic_modal.php'); ?>

 <style>
  .indication-pic {
    max-height: 100px;
    width: auto;
  }

  .modal-body img {
    width: 100%;
  }
  .table-flex {
    display: flex;
  }

  #tableIndication thead tr {
    height: 35px;
  }
  #coupons_table thead tr {
    height: 70px;
  }
  #tableIndication_wrapper {
    width: 100%;
  }
  .totals {
    margin-bottom: 20px;
    font-weight: bold;
    font-size: 16px;
  }
 </style>