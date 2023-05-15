<div class="container" style="margin-top:50px;">
  <nav>
    <div class="nav nav-tabs" id="nav-tabEmp" role="tablist">
      <button class="nav-item nav-link active" id="nav-employee_tab_works" data-bs-toggle="tab" data-bs-target="#employee_tab_works"   type="button" role="tab" aria-controls="employee_tab_works" aria-selected="false">Текущие</button>

      <button class="nav-item nav-link" id="nav-employee_tab_fired" data-bs-toggle="tab" data-bs-target="#employee_tab_fired"   type="button" role="tab" aria-controls="employee_tab_fired" aria-selected="false">Уволенные</button>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContentEmp">
    
      <div class="tab-pane fade show active" id="employee_tab_works" role="tabpanel" aria-labelledby="nav-employee_tab_works" tabindex="0">
        <?php echo view('classificators/sub/employees/employees', ['employees' => $employees]); ?>
      </div>

      <div class="tab-pane fade" id="employee_tab_fired" role="tabpanel" aria-labelledby="nav-employee_tab_fired" tabindex="1">
        <?php echo view('classificators/sub/employees/employees_fired', []); ?>
      </div>
  </div>
</div>

<?php echo view('partials/modals/_employee_edit_modal', []); ?>