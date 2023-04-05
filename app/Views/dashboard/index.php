
    <div class="container">
      <div class="row" style = "margin-top:50px">
        <div class="col-md-4 col-md-offset-4">
          <h4><?=$title?>&nbsp | &nbsp; <?=$user['name']?></h4>
          
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
        </div>
      </div>
    </div>
