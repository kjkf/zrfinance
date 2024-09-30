<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12 col-md-offset-12 report-datebar">
      Введите даты отчётного периода
      <p class="report-datebar_formfiled">
        <label for="reportDate_start">ОТ: </label>
        <input type="text" id="reportDate_start" class="report-datebar_field">
      </p>
      <p class="report-datebar_formfiled">
        <label for="reportDate_end">ДО: </label>
        <input type="text" id="reportDate_end" class="report-datebar_field">
      </p>
      <button class = "btn btn-primary" type="button" name="button" id="getReport">
        Сгенерировать
      </button>
    </div>
  </div>
  <div class="report-content">
    <div class="row ">
      <div class="col-md-12 col-md-offset-12" id = "report_resultMain_title" style="display:none; ">
        <h4>Основной отчёт</h4>
      </div>
    </div>
    <div class="row report-content">
      <div class="col-md-12 col-md-offset-12" id = "report_resultMain" style="display:none; ">
        <table id = "report_main" style = "width:100%">
          <thead>
            <tr>
              <td width = "10%" >№ п/п</td>
              <td width = "30%" >Наименование компании</td>
              <td width = "30%" >Наименование счета</td>
              <td width = "10%" >Приход</td>
              <td width = "10%" >Расход</td>
              <td width = "10%" >Примечание</td>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>


    <!-- <div class="report-content">
    <div class="row">
      <div class="col-md-12 col-md-offset-12" id = "report_resultByGoods_title" style="display:none; ">
        <h4> Отчёт: приход по товарам</h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-md-offset-12" id = "report_resultByGoods" style="display:none; ">
        <table id = "report_byGoods" style = "width:100%">
          <thead>
            <tr>
              <td width = "10%" >№ п/п</td>
              <td width = "20%" >Наименование компании888</td>
              <td width = "20%" >Наименование счета</td>
              <td width = "35%" >Наименование товара</td>
              <td width = "15%" >Приход</td>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div> -->

</div>

<br>
<br>
<br>
<br>

<style media="screen">

</style>
