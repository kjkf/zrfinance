<div class="container" style="margin-top:50px;">

  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <?php foreach ($balance_year as $item) :
        $year = $item['year'];
        $activeClass = (date('Y') == $year) ? 'active' : '' ?>
        <button class="nav-link <?= $activeClass ?>" id="nav-time-balance-tab_<?= $year ?>" data-bs-toggle="tab" data-bs-target="#nav-time-balance<?= $year ?>" type="button" role="tab" aria-controls="nav-time-balance" aria-selected="false"><?= $year ?></button>
      <?php endforeach; ?>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <?php foreach ($balance_year as $item) :
      $year = $item['year'];
      $activeClass = (date('Y') == $year) ? 'show active' : '' ?>
      <div class="tab-pane fade <?= $activeClass ?>" id="nav-time-balance<?= $year ?>" role="tabpanel" aria-labelledby="nav-time-balance-tab_<?= $year ?>" tabindex="0">
        <?php if (date('Y') == $year) : ?>
          <?php echo view('classificators/sub/time_balance/time_balance_table', ['balance_for_year' => $balance_for_current_year, 'year' => $year]); ?>
        <?php else : ?>
          <?php echo view('classificators/sub/time_balance/time_balance_table', ['balance_for_year' => array(), 'year' => $year]); ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>