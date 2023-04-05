<div class="agreements-nav" >
  <p>
    <?php
      foreach ($companies as $key => $company) {
        $btn = '<button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#zr_'.$company['id'].'" aria-expanded="false" aria-controls="zr_'.$company['id'].'">'
                    .$company['name'].
                '</button>';
        echo $btn;
      }
    ?>
  </p>
</div>

<div class="agreements-content">
  <?php
    foreach ($companies as $key => $company) {
  ?>
  <div class="collapse" id="zr_<?php echo $company['id']?>">
    <div class="card card-body">
      <?php echo view('classificators/sub/add_agreement', ['company_id' => $company['id'], 'company_name' => $company['name']]); ?>
      <?php
        $company_agreements = $agreements[$company['id']];

        if(!empty($company_agreements)){
      ?>
      <table class="table table-bordered">
        <thead>
          <th scope = 'col'>№п/п</th>
          <th scope = 'col'>Исполнитель</th>
          <th scope = 'col'>Заказчик</th>
          <th scope = 'col'>Дата договора</th>
          <th scope = 'col'>Номер договора</th>
          <th scope = 'col'>Сумма</th>
          <th scope = 'col'>Краткое наименование договора</th>
          <th scope = 'col'>Ответственный менеджер</th>
        </thead>
        <tbody>
      <?php
          $ind = 1;
          foreach ($company_agreements as $key => $agreement) {
            $executer = ($agreement['type'] == 'fromZR') ? $company['name'] : $agreement['contractor_name'];
            $customer = ($agreement['type'] == 'fromZR') ? $agreement['contractor_name'] : $company['name'];
      ?>
          <tr>
            <td><?php echo $ind ?></td>
            <td><?php echo $executer ?></td>
            <td><?php echo $customer ?></td>
            <td><?php echo $agreement['agreement_date'] ?></td>
            <td><?php echo $agreement['agreement_num'] ?></td>
            <td><?php echo $agreement['agreement_sum'] ?></td>
            <td><?php echo $agreement['short_name'] ?></td>
            <td><?php echo $agreement['manager'] ?></td>
          </tr>
      <?php
            ++$ind;
          }
      ?>
        </tbody>
      </table>
      <?php
      }else{
      ?>
          Нет записей
      <?php } ?>
    </div>
  </div>
  <?php
    }
  ?>
</div>
