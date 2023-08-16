<div class="modal fade" id="modal_materials" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modal_addItemLabel"></h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-2 editable">
          <div class="col-3 disable-editable">
            <label for="working_hours">Официальная ЗП / ставка в час</label>
            <input type="text" class="form-control" id="official_salary" disabled data-fld="employee_salary">
            <?php
            $status = isset($fzp) && !empty($fzp) ? $fzp['is_approved'] : -1;
            if ($status != 1) { ?>
              <a href="#" class="disable_edit"><i class='fa fa-pencil'></i></a>
            <?php } ?>
          </div>

          <div class="col-3 disable-editable">
            <label for="worked_hours_fact">Фактическая ЗП</label>
            <input type="text" class="form-control" id="salary_fact" disabled data-fld="employee_salary_fact">
            <?php
            if ($status != 1) { ?>
              <a href="#" class="disable_edit"><i class='fa fa-pencil'></i></a>
            <?php } ?>
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