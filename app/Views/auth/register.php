
    <div class="container">
      <div class="row" style = "margin-top:100px;  justify-content:center;">
        <div class="col-md-4 col-md-offset-4">
          <h4>Регистрация</h4><hr>
          <form class="" action="<?=base_url('auth/save')?>" method="post">
            <?= csrf_field();?>
            <?php if(!empty(session()->getFlashData('fail'))){ ?>
              <div class="alert alert-danger">
                <?=session()->getFlashData('fail')?>
              </div>
            <?php } ?>
            <?php if(!empty(session()->getFlashData('success'))){ ?>
              <div class="alert alert-success">
                <?=session()->getFlashData('success')?>
              </div>
            <?php } ?>
            <div class="mb-3">
              <label for="name">Имя:</label>
              <input tabindex="1" type="text" class="form-control" name="name" value="<?=set_value('name')?>" placeholder="Введите имя">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'name'):''?> </span>
            </div>
            <div class="mb-3">
              <label for="email">E-mail:</label>
              <input tabindex="2" type="text" class="form-control" name="email" value="<?=set_value('email')?>" placeholder="Введите e-mail">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'email'):''?> </span>
            </div>
            <div class="mb-3">
              <label for="password">Пароль:</label>
              <input tabindex="3" type="password" class="form-control" name="password" value="<?=set_value('password')?>" placeholder="Введите пароль">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'password'):''?> </span>
            </div>
            <div class="mb-3">
              <label for="cpassword">Подтверждение пароля:</label>
              <input tabindex="4" type="password" class="form-control" name="cpassword" value="<?=set_value('cpassword')?>" placeholder="Введите пароль повторно">
              <span class="text-danger"> <?= isset($validation) ? display_error($validation, 'cpassword'):''?> </span>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary btn-block" type="submit" name="button">Зарегистрироваться</button>
            </div>
            <br>
            <a href="<?= site_url('auth')?>"> У меня уже есть аккаунт, войти в систему</a>
          </form>
        </div>

      </div>

    </div>
