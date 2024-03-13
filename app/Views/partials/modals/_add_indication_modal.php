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
          $prev = 0;
          if ($prev_indication && !empty($prev_indication)) {
            $prev = $prev_indication["indication"];
          }
        ?>
          <input type="hidden" id="prev_indication" value="<?=$prev?>">
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