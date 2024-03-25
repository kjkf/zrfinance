<div class="modal fade" id="modal_addIndication" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal_addItemLabel"></h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
        <form id="indication-form" action="<?php echo base_url('cars/save_indication')?>" method="post" enctype="multipart/form-data">
          <input type="hidden" id="car_user" value="<?=$car_info['user']?>">
          <input type="hidden" id="car" name="car" value="<?=$car_info['id']?>">
          <?php 
          //print_r($prev_indication);
          $prev = 0;
          $isEndIdification = false;
          $today = date("d.m.Y H:i"); 
          $tablename = "cars_indication";
          $date_key = $today;
          if ($prev_indication && !empty($prev_indication)) {
            $prev = isset($prev_indication["indication_end"]) && !empty($prev_indication["indication_end"]) ? $prev_indication["indication_end"] : $prev_indication["indication_start"];
            $isEndIdification = isset($prev_indication["indication_end"]) && !empty($prev_indication["indication_end"]);
            $date_key = $isEndIdification ? $today : $prev_indication["date_key"];
            $tablename = $isEndIdification ? 'cars_indication' : 'cars_indication_end';
          }
        ?>          
          <input type="hidden" id="prev_indication" value="<?=$prev?>">
          <input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>">
          <input type="hidden" id="date_key" value="<?=$date_key?>" name="date_key">
          <div class="row mb-">
            <div class="col-12">
              <label for="driver">Дата</label>
              <input type="text" id="indication_date" class="form-control" readonly>
            </div>

          </div>
          <div class="row mb-2">
            <div class="col-12">
              <label for="car">Машина</label>
              <input type="text" class="form-control" id="car_name" value="<?=$car_info['car_name']?>" readonly>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-12">
              <label for="indication">Показания</label>
              <input type="text" class="form-control" id="indication" name="indication" placeholder="Показания">
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-12">
              <label for="pic">Фото</label>
              <input type="file" class="form-control" id="pic" name="pic" placeholder="Фото">
            </div>
          </div>


          <div class="d-flex justify-content-end ">
            <button type="button" class="btn btn-secondary mr-10" id="saveIndication">Сохранить</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Закрыть</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>