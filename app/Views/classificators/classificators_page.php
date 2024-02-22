<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<?php
    $user_role = isset($user_role) ? $user_role : $user['role'];
    $is_chief = (isset($user_role) && $user_role == 3) ? true : false;
    $is_admin = (isset($user_role) && $user_role == 1) ? true : false;
    $is_access_to_classif = (isset($user_role) && ($user_role == 1 || $user_role == 3 || $user_role == 5)) ? true : false;

    $activeTab = $is_chief || $is_admin ? "" : "show active";
?>


<div class="container" style = "margin-top:50px;">
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <?php if ($is_admin || $is_chief) :?>
        <button class="nav-link active show" id="nav-agreements-tab" data-bs-toggle="tab" data-bs-target="#nav-agreements" type="button" role="tab" aria-controls="nav-agreements" aria-selected="true">Договора</button>
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Контрагенты</button>
        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Детали</button>
      <?php endif; ?>
      <?php if ($is_access_to_classif) :?>
        <button class="nav-link" id="nav-time-balance-tab" data-bs-toggle="tab" data-bs-target="#nav-time-balance" type="button" role="tab" aria-controls="nav-time-balance" aria-selected="false">Баланс рабочего времени</button>
        <button class="nav-link <?php echo $activeTab?>" id="nav-employees-tab" data-bs-toggle="tab" data-bs-target="#nav-employees" type="button" role="tab" aria-controls="nav-employees" aria-selected="false">Сотрудники</button>
        <button class="nav-link" id="nav-materials-tab" data-bs-toggle="tab" data-bs-target="#nav-materials" type="button" role="tab" aria-controls="nav-materials" aria-selected="false">Материалы</button>
        <!-- <button class="nav-link " id="nav-units-tab" data-bs-toggle="tab" data-bs-target="#nav-units" type="button" role="tab" aria-controls="nav-units" aria-selected="false">Ед.измерения</button>-->
      <?php endif; ?>
      </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
    <?php if ($is_admin || $is_chief) :?>
      <div class="tab-pane fade show active" id="nav-agreements" role="tabpanel" aria-labelledby="nav-agreements-tab" tabindex="0">
        <?php echo view('classificators/sub/agreements', ['agreements' => $agreements]); ?>
      </div>
      <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
        <!--<?php // echo view('classificators/sub/contractor', ['contractor' => $contractor]); ?>-->
      </div>
      <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0"></div>
    <?php endif; ?>
    
    <?php if ($is_access_to_classif) :?>
      <div class="tab-pane fade" id="nav-time-balance" role="tabpanel" aria-labelledby="nav-time-balance-tab" tabindex="0">
        <?php echo view('classificators/sub/time_balance/time_balance_view', ['balance_year' => $balance_year]); ?>
      </div>

      <div class="tab-pane fade  <?php echo $activeTab?>" id="nav-employees" role="tabpanel" aria-labelledby="nav-employees-tab" tabindex="0">
        <?php echo view('classificators/sub/employees/employee_tabs', ['employees' => $employees]); ?>
      </div>
      <div class="tab-pane fade" id="nav-materials" role="tabpanel" aria-labelledby="nav-materials-tab" tabindex="0">
        <?php echo view('classificators/sub/materials/materials', ['materials' => $materials]); ?>
      </div>
      <!--<div class="tab-pane fade  " id="nav-units" role="tabpanel" aria-labelledby="nav-units-tab" tabindex="0">
        <?php //echo view('classificators/sub/units/units', ['units' => $units]); ?>
      </div>-->
    <?php endif; ?>
    </div>

</div>
