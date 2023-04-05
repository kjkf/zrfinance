<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>


<div class="container" style = "margin-top:50px;">
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-agreements-tab" data-bs-toggle="tab" data-bs-target="#nav-agreements" type="button" role="tab" aria-controls="nav-agreements" aria-selected="true">Договора</button>
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Контрагенты</button>
        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Детали</button>

      </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="nav-agreements" role="tabpanel" aria-labelledby="nav-agreements-tab" tabindex="0">
        <?php echo view('classificators/sub/agreements', ['agreements' => $agreements]); ?>
      </div>
      <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
        <!--<?php // echo view('classificators/sub/contractor', ['contractor' => $contractor]); ?>-->
      </div>
      <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0"></div>
    </div>

</div>
