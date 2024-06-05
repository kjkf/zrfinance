<div class="modal fade" id="modal_gas_receipt" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal_addItemLabel">Поступление бензина</h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="add_coupons" action="<?php echo base_url('coupons/add_coupons')?>" method="post" enctype="multipart/form-data">
          <div class="row mb-2">
            <div class="col-12">
              <label for="receipt_date">Дата</label>
              <input type="text" id="receipt_date" class="form-control" readonly>
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-12">
              <label for="quantity">Количество в литрах</label>
              <input type="text" class="form-control" id="receipt_quantity" name="quantity" placeholder="Поступило">
            </div>
          </div>
        </form>
            
        <div class="d-flex justify-content-end ">
          <button class="btn btn-secondary mr-10" id="addGas">Сохранить</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
</div>