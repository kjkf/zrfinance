<div class="modal fade" id="modal_addCouponsBase" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal_addItemLabel"></h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <div class="row mb-2">
          <div class="col-12">
            <label for="driver">Водитель</label>
            <select id="driver" style="width:100%; padding: 0.375rem 0.75rem">
              <option value=""></option>
              <?php foreach($drivers as $driver):?>
                <option value="<?=$driver['id']?>"><?=$driver['name']?></option>
              <?php endforeach?>
            </select>
          </div>

        </div>
        <div class="row mb-2">
          <div class="col-12">
            <label for="car">Машина</label>
            <input type="text" class="form-control" id="car_name" placeholder="Номер машины">
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-12">
            <label for="consumption">Расход на 100км</label>
            <input type="text" class="form-control" id="consumption" placeholder="Расход">
          </div>
        </div>


        <div class="d-flex justify-content-end ">
          <button class="btn btn-secondary mr-10" id="saveCar">Сохранить</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
</div>