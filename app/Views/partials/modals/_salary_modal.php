<div class="modal fade" id="modal_editEmployeeSalaryInfo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal_addItemLabel"></h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-2 editable" >
          <div class="col-3 disable-editable">
            <label for="working_hours" >Официальная ЗП / ставка в час</label>
            <input type="text" class="form-control" id="official_salary" disabled data-fld="employee_salary">
            <?php 
            $status = isset($fzp) && !empty($fzp) ? $fzp['is_approved'] : -1;
            if ($status != 1) {?>
              <a href="#" class="disable_edit" ><i class='fa fa-pencil'></i></a>
            <?php }?>
          </div>

          <div class="col-3 disable-editable">
            <label for="worked_hours_fact">Фактическая ЗП</label>
            <input type="text" class="form-control" id="salary_fact" disabled data-fld="employee_salary_fact">
            <?php 
            if ($status != 1) {?>
              <a href="#" class="disable_edit" ><i class='fa fa-pencil'></i></a>
            <?php }?>
          </div>

          <div class="col-3">
            <label for="total">Авансы</label>
            <input type="text" class="form-control" id="advanced_pay" placeholder="Укажите сумму выданных авансов">
          </div>

          <div class="col-3">
            <label for="total">Отпускные</label>
            <input type="text" class="form-control" id="holiday_pay" placeholder="Укажите сумму отпускных">
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-sm-8">
            <div class="row">
              <div class="col-4">
                <label for="working_hours">Кол-во рабочих часов</label>
                <input type="text" class="form-control" id="working_hours" disabled>
              </div>
              <div class="col-4">
                <label for="worked_hours_fact">Кол-во отработанных часов</label>
                <input type="number" min=0 step=10 class="form-control" id="worked_hours_fact"
                  placeholder="Введите колво отработанных часов по факту">
              </div>
              <div class="col-4">
                <label for="total">Начислено по отраб. часам</label>
                <input type="text" class="form-control" id="worked_salary" disabled>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="row">
              <div class="col-6">
                <label for="total">Итого начислено</label>
                <input type="text" class="form-control" id="worked_salary_before_tax" disabled>
              </div>
              <div class="col-6">
                <label for="total">Итого к выдаче</label>
                <input type="text" class="form-control" id="total" disabled>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <h5>Налоги</h5>
        </div>
        <div class="row mb-2">
          <div class="col-4">
            <label for="working_hours">ОСМС</label>
            <input type="text" class="form-control" id="tax_osms" disabled>
          </div>

          <div class="col-4">
            <label for="working_hours">ОПВ</label>
            <input type="text" class="form-control" id="tax_opv" disabled>
          </div>

          <div class="col-4">
            <label for="working_hours">ИПН</label>
            <input type="text" class="form-control" id="tax_ipn" disabled>
          </div>
        </div>

        <div class="row">
          <h5>Прибавки и удержания</h5>
        </div>
        <div class="row">
          <div class="col-6">
            <table class="table table-sm caption-top bonus">
              <colgroup>
                <col style="width: 45%">
                <col style="width: 35%">
                <col style="width: auto">
              </colgroup>
              <caption>
                Всего Прибавки:
                <span>0.00</span> KZT
              </caption>
              <thead class="table-success">
                <tr>
                  <td class="ttype">Тип</td>
                  <td>Сумма</td>
                  <td>Действия</td>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr class="add_bonus">
                  <td> <select class="form-select">
                      <option value="-1">Выберите тип прибавки</option>
                      <?php foreach($bonus_fines["bonus"] as $bonus) :?>
                      <option value="<?=$bonus['id']?>"><?php echo $bonus['name']?></option>
                      <?php endforeach;?>
                    </select>
                  </td>
                  <td>
                    <input type="number" min=0 class="sum" step="1000">
                  </td>
                  <td>
                    <a href="#" class="btn-icon save-btn" data-type="bonus" name="edit_item"><i class="fas fa-save"></i>
                    </a>
                  </td>
                </tr>
              </tfoot>
            </table>

          </div>

          <div class="col-6">
            <table class="table table-sm caption-top fines">
              <colgroup>
                <col style="width: 45%">
                <col style="width: 35%">
                <col style="width: auto">
              </colgroup>
              <caption>
                Всего Удержаний:
                <span>0.00</span> KZT
              </caption>
              <thead class="table-info">
                <tr>
                  <td class="ttype">Тип</td>
                  <td>Сумма</td>
                  <td>Действия</td>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr class="add_fines">
                  <td> <select class="form-select fines">
                      <option value="-1">Выберите тип удержания</option>
                      <?php foreach($bonus_fines["fines"] as $fines) :?>
                      <option value="<?=$fines['id']?>"><?php echo $fines['name']?></option>
                      <?php endforeach;?>
                    </select>
                  </td>
                  <td>
                    <input type="number" min=0 class="sum" step="1000">
                  </td>
                  <td>
                    <a href="#" class="btn-icon save-btn" data-type="fines" name="edit_item"><i class="fas fa-save"></i>
                    </a>
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