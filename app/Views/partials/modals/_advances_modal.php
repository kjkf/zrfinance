<div class="modal fade" id="modal_editEmployeeAdvances" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal_addItemLabel"></h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="emp_id">
        <div class="row mb-2">
          <div class="col-4">
            <label for="working_hours">Официальная ЗП / ставка в час</label>
            <input type="text" class="form-control" id="official_salary" disabled>
          </div>

          <div class="col-4">
            <label for="worked_hours_fact">Фактическая ЗП</label>
            <input type="text" class="form-control" id="salary_fact" disabled>
          </div>

          <div class="col-4">
            <label for="total">Авансы</label>
            <input type="text" class="form-control" id="advanced_pay" placeholder="Авансы" disabled>
          </div>
        </div>

        <div class="d-flex justify-content-center"">
          <div class=" col-12">
            <table class="table table-sm caption-top bonus">
              <colgroup>
                <col style="width: 45%">
                <col style="width: 35%">
                <col style="width: auto">
              </colgroup>
              <caption>
                Авансы:
                <span>0.00</span> KZT
              </caption>
              <thead class="table-success">
                <tr>
                  <td class="ttype">Дата</td>
                  <td>Сумма</td>
                  <td>Действия</td>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr class="add_bonus">
                  <td>
                    <input type="text" class="form-control" id="advance_date" placeholder="Укажите дату">
                  </td>
                  <td>
                    <input type="number" min=0 class="sum" step="1000">
                  </td>
                  <td>
                    <a href="#" class="btn-icon save-btn"><i class="fas fa-save"></i> </a>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="d-flex justify-content-end ">
          <button class="btn btn-secondary save-em-info mr-10">Сохранить</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
</div>