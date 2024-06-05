<div class="modal fade" id="modal_issuingCoupons" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal_addItemLabel">Выдача талонов</h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="issuing_coupons" action="<?php echo base_url('coupons/issuing_coupons')?>" method="post" enctype="multipart/form-data">
          <div class="row mb-2">
            <div class="col-12">
              <label for="issuing_date">Дата</label>
              <input type="text" id="issuing_date" class="form-control" readonly>
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-12">
              <label for="issuing_base">Основание</label>
              <select name="base" id="issuing_base" class="form-control">
                <option value="-1"></option>
                <option value="0">Кара / Пила</option>
                <?php if (isset($issuing_base) && count($issuing_base) > 0) {
                  foreach($issuing_base as $base)?>
                  <option value="<?=$base["base"]?>"><?=$base["name"]?></option>
                <?php }?>
              </select>
              
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-12">
              <label for="remainder">Остаток купонов в литрах</label>
              <input readonly type="text" id="remainder" class="form-control" value="<?echo $reciept_total[0]["quantity"] - $issuing_total[0]["quantity"]?>" />
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-12 issuing_type">
              <label><input type="radio" name="issuing_type" value="coupons"> Талоны </label>
              <label><input type="radio" name="issuing_type" value="money"> Деньги </label>
            </div>
          </div>
          <div class="row mb-2 issuing_type-coupons">
            <div class="col-12">
              <label for="quantity">Количество в литрах</label>
              <input type="text" class="form-control" id="issuing_quantity" name="quantity" placeholder="Выдать">
            </div>
          </div>
          <div class="row mb-2 issuing_type-money">
            <div class="col-12">
              <label for="issuing_money">Сумма</label>
              <input type="text" class="form-control" id="issuing_money" name="money" placeholder="Выдать">
            </div>
          </div>
        </form>
            
        <div class="d-flex justify-content-end ">
          <button class="btn btn-secondary mr-10" id="issuingGas">Сохранить</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
</div>