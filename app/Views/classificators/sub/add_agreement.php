<button type="btn btn-success" name="add_agreement" id="add_agreement" company_id = "<?php echo $company_id ?>">Добавить договор</button>


<form class="" action="index.html" method="post" id="form_add_agreement">
  Заполните поля:<br>
  <div class="input-group col-3 mb-3">
    <label class="input-group-text" for="inputGroupSelect01">Тип договора</label>
    <select class="form-select" id="inputGroupSelect01">
      <option selected>Выберите...</option>
      <option value="1">Поставка товаров/услуг для Златаря</option>
      <option value="2">оставка товаров/услуг Златарем</option>
    </select>
  </div>
  <!-- Дата договора -->
  <div class="nput-group col-12 mb-3">
    <label class="document" for="agreement_date">Дата договора:</label>
    <p class="classificator-date">
      <input type="text" id="agreement_date" class="">
    </p>
  </div>
  <!-- Номер договора -->
  <div class="nput-group col-12 mb-3">
    <label class="document" for="agreement_num">Номер договора:</label>
    <input type="text" class="form-control" id="agreement_num" name="agreement_num" value=""/>
    <span class="text-danger">  </span>
  </div>
  <!-- Исполнитель-->
  <div class="nput-group col-12 mb-3">
    <label class="document" for="executer">Исполнитель:</label>
    <input type="text" class="form-control" id="executer" name="executer" value=""/>
    <span class="text-danger">  </span>
  </div>
  <!-- Клиент-->
  <div class="nput-group col-12 mb-3">
    <label class="document" for="customer">Клиент:</label>
    <input type="text" class="form-control" id="customer" name="customer" value=""/>
    <span class="text-danger">  </span>
  </div>
  <!-- Менеджер-->
  <div class="nput-group col-12 mb-3">
    <label class="document" for="manager">Ответственный менеджер:</label>
    <input type="text" class="form-control" id="manager" name="manager" value=""/>
    <span class="text-danger">  </span>
  </div>
  <!-- Краткое наименование -->
  <div class="nput-group col-12 mb-3">
    <label class="document" for="short_name">Краткое наименование договора:</label>
    <input type="text" class="form-control" id="short_name" name="short_name" value=""/>
    <span class="text-danger">  </span>
  </div>
  <!-- Сумма договора-->
  <div class="nput-group col-12 mb-3">
    <label class="document" for="agreement_sum">Сумма договора:</label>
    <input type="text" class="form-control" id="agreement_sum" name="agreement_sum" value=""/>
    <span class="text-danger">  </span>
  </div>

</form>
