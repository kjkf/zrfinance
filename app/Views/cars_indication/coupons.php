<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<div class="container">
  <div class="report-content">
    <div class="row ">
      <div class="d-flex justify-content-center mb-3 mt-3" >
        <h4>Талоны на бензин</h4>
      </div>
    </div>
    <div class="row ">
      <div class="d-flex justify-content-end mb-3" >
        <button class="btn btn-secondary mr-10" data-bs-toggle="modal" data-bs-target="#modal_addCouponsBase" id="addCouponsBaseBtn">Добавить основание</button>
        <button class="btn btn-secondary mr-10" data-bs-toggle="modal" data-bs-target="#modal_gas_receipt" id="gasReceiptBtn">Поступление бензина</button>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_issuingCoupons" id="issuingCouponsBtn">Выдача талонов</button>
      </div>
    </div>

    <?php //print_r($indications);?>

    <div class="row report-content">
      <div class="col-md-12 col-md-offset-12 table-flex" id = "" >
        <table id = "tableReciept" style = "width:100%">
          <thead>
            <tr>
              <td colspan=2 class="no-sort">Поступило</td>
            </tr>
            <tr>
                <td>Дата</td>
                <td>Количество(л)</td>
            </tr>
          </thead>
          <tbody>
           <?php 
            if (isset($gas_reciept) && $gas_reciept && count($gas_reciept) > 0) :
              $count = 1;?>
              <?php foreach($gas_reciept as $item) :?>
                <tr>
                  <td><?php echo date("d.m.Y", strtotime($item["date"]) );?></td>
                  <td><?=$item['quantity']?></td>
                </tr>
              <?php endforeach?>
              <tr class="tr-total">
                <td>Итого:</td>
                <td><?php if(isset($reciept_total) && count($reciept_total)>0) {
                  echo $reciept_total[0]["quantity"];
                }?></td>
              </tr>
            <?php else :?>
              <tr class="empty-row">
                <td colspan="5">
                  Нет записей!
                </td>
              </tr>
            <?php endif?>
            
          </tbody>
        </table><table id = "tableIssuing" style = "width:100%">
          <thead>
            <tr>
              <td colspan=3>Выдано</td>
            </tr>
            <tr>
                <td>Дата</td>
                <td>Основание</td>
                <td>Выдано</td>
            </tr>
          </thead>
          <tbody>
           <?php 
            if (isset($issuing_coupons) && $issuing_coupons && count($issuing_coupons) > 0) :
              $count = 1;?>
              <?php foreach($issuing_coupons as $item) :?>
                <tr>
                  <td><?php echo date("d.m.Y", strtotime($item["date_time"]) );?></td>
                  <td><?=$item['base_name']?></td>
                  <td><?=$item['quantity'] ? $item['quantity'].'л' : $item['money']."тг"?></td>
                </tr>
              <?php endforeach?>
              <tr class="tr-total">
                <td>Итого:</td>
                <td></td>
                <td><?php if(isset($issuing_total) && count($issuing_total)>0) {
                  echo $issuing_total[0]["quantity"].'л<br>'.$issuing_total[0]["money"]."тг";
                }?></td>
              </tr>
            <?php else :?>
              <tr class="empty-row">
                <td colspan="5">
                  Нет записей!
                </td>
              </tr>
            <?php endif?>
           
          </tbody>
        </table>
        <input type="hidden" id="remainder" value="<?echo $reciept_total[0]["quantity"] - $issuing_total[0]["quantity"]?>">
      </div>
    </div>
  </div>

 </div>
 <?php
  echo view('partials/modals/_gas_receipt_modal.php');
  echo view('partials/modals/_coupons_issuing_modal.php');
 ?>

<style>
  .table-flex {
    display: flex;
  }
  #tableReciept_wrapper {
    width: 40%;
  }

  #tableIssuing_wrapper {
    width: 60%;
  }

  @media (max-width: 769px) {
    .table-flex{
      flex-direction: column;
    }
    #tableReciept_wrapper, #tableIssuing_wrapper {
      width: 100%;
    }
  }

</style>