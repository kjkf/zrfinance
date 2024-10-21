<?php //defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo view('partials/_top_nav.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12 col-md-offset-12" style="padding-left: 0; padding-right:0;">
      <p class="report-datebar_formfiled" >
        <label for="attendanceDate_start">Введите дату отчётного периода: </label>
        <input type="text" id="attendanceDate_start" class="report-datebar_field">
        <input type="hidden" id="period_start" value="" >
        <input type="hidden" id="period_end" value="">
        <label id="period_label"></label>
      </p>
    </div>

    <div class="col-md-12 col-md-offset-12 attendance-datebar">
      Выберите подразделение:
      <p class="attendance-datebar_formfiled">
        <select class="form-select" aria-label="Default select example" id = "depart_name" name = "depart_name">
          <?php
          if(isset($items)){
            echo "<option>Выберите</option>";
            foreach ($items as $item) {
              $option = "<option value='".$item['id']."'>".$item['name']."</option>";
              echo $option;
            }
          }else{
          ?>
          <option selected>Выберите</option>
          <option >Что-то пошло не так</option>
          <?php
          }
          ?>
        </select>
      </p>
    </div>
    <div class="col-md-12 col-md-offset-12 attendance-datebar">
      <button class = "btn btn-primary" type="button" name="button" id="getattendance">
        Показать
      </button>
    </div>
  </div>
  <div class="attendance-content">
    <div class="row ">
      <div class="col-md-12 col-md-offset-12" id = "attendance_resultMain_title" >
        <h4>Табель посещения</h4>
      </div>
    </div>
    <div class="row attendance-content">
      <div class="col-md-12 col-md-offset-12" id = "attendance_resultMain" >
        <table id = "attendance_main" style = "width:100%">
          <thead>
            <tr>
              <td width = "10%" >№ п/п</td>
              <td width = "30%" >Ф.И.О</td>
              <td width = "30%" >Должность</td>
              <td width = "10%" >Часы</td>
              <td width = "10%" >Действие</td>
              <!-- <td width = "10%" ></td> -->
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<br>
<br>
<br>
<br>

<style media="screen">

</style>
