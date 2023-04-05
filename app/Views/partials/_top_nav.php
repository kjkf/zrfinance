<?php
    $is_chief = (isset($user_role) && $user_role == 3) ? true : false;
    $is_admin = (isset($user_role) && $user_role == 1) ? true : false;
?>

<div class="row" style = "margin-top:30px; padding:20px 60px; height: auto; background-color:#A50000; color: white">
  <div class="col-md-11 col-md-offset-11">
    <h4><?=$title?>&nbsp </h4>
    <span> Пользователь: <?=$user['name']?></span>
  </div>
  <div class="col-md-1 col-md-offset-1">
    <div class="dropdown">
      <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Меню
      </button>
      <ul class="dropdown-menu">
        <?php if ($is_admin || $is_chief) :?>
        <li><a class="dropdown-item" href="<?php echo base_url('dashboard')?>">Движение</a></li>
        <li><a class="dropdown-item" href="<?php echo base_url('report')?>">Отчёты</a></li>
        <li><a class="dropdown-item" href="<?php echo base_url('classificators')?>">Классификаторы</a></li>
        <?php endif; ?>
        <li><a href="<?php echo  site_url('auth/logout')?>" class="dropdown-item">Выйти</a></li>
      </ul>
    </div>
  </div>
</div>
