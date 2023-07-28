<?php
    $companies = $clasificData['companies'];
    $positions = $clasificData['positions'];
    $directions = $clasificData['directions'];
    $contract_type = $clasificData['contractType'];
    $tax_pay_type = $clasificData['taxPayType'];
    $citizenship = $clasificData['citizenship'];
    $countries = $clasificData['countries'];
    $department = $clasificData['department'];
    ?>
   
<div class="modal fade" id="modal_employeeInfo" tabindex="-1" aria-labelledby="modal_addItemLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_addItemLabel">Личная карточка сотрудника</h5>
        <input type="hidden" id="trid">
        <input type="hidden" id="is_tr_changed" value="0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row flex-grow-1">
          <div class="col-4">
            <label for="surname">Фамилия</label>
            <input type="text" class="form-control" id="surname" placeholder="Укажите фамилию сотрудника">
          </div>
          <div class="col-4">
            <label for="name">Имя</label>
            <input type="text" class="form-control" id="name" placeholder="Укажите имя сотрудника">
          </div>
          <div class="col-4">
            <label for="middlename">Отчество</label>
            <input type="text" class="form-control" id="middlename" placeholder="Укажите отчество сотрудника">
          </div>          
        </div>
        <div class="row mt-3 parttime-basejob-settings d-none">
          <div class="col-2">
            <label for="is_base_job">
              <input type="checkbox" class="" id="is_base_job" name="is_base_job"> Основная работа
            </label>
          </div>
          <div class="col-2">
            <label for="is_deduction">
              <input type="checkbox" class="" id="is_deduction" name="is_deduction"> Применить вычет
            </label>
          </div>
        </div>
        <hr />

        <div class="row flex-grow-1 mt-3">
          <div class="col-3">
            <label for="birth_date">Дата рождения:</label>
            <input type="text" class="form-control" id="birth_date" placeholder="Укажите дату рождения">
          </div>
          <div class="col-3">
            <label for="telephone">Телефон:</label>
            <input type="phone" class="form-control" id="telephone" placeholder="Укажите телефон сотрудника">
          </div>
          <div class="col-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Укажите эл. почту сотрудника">
          </div>
          <div class="col-3">
            <label for="start_date">Принят на работу:</label>
            <input type="text" class="form-control" id="start_date" placeholder="Укажите дату принятия на работу">
          </div>
        </div>
        <hr />

        <div class="row flex-grow-1 mt-3">
          
          <div class="col-3">
            <label>Компания:</label>
            <select class="form-select" name="" id="company">
              <option value="-1">Выберите компанию</option>
              <?php foreach($companies as $comp) :?>
                <option value="<?=$comp['id']?>"><?=$comp['name']?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-3">
            <label>Отдел:</label>
            <select class="form-select" name="" id="department">
              <option value="-1">Выберите отдел</option>
              <?php foreach($department as $dep) :?>
                <option value="<?=$dep['id']?>"><?=$dep['name']?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-3">
            <label>Должность:</label>
            <select class="form-select" name="" id="position">
              <option value="-1">Выберите должность</option>
              <?php foreach($positions as $pos) :?>
                <option value="<?=$pos['id']?>"><?=$pos['name']?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-3">
            <label>Направление:</label>
            <select class="form-select" name="" id="direction">
              <option value="-1">Выберите направления</option>
              <?php foreach($directions as $direct) :?>
                <option value="<?=$direct['id']?>"><?=$direct['name']?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        <hr />

        <div class="row flex-grow-1 mt-3">
          <div class="col-3">
            <label>Тип договора:</label>
            <?php foreach($contract_type as $contract) :?>
              <div class="form-check">
                <input type="radio" class="form-check-input" id="contract_type<?=$contract["id"]?>" name="contract_type" value="<?=$contract["id"]?>" >
                <label class="form-check-label" for="contract_type<?=$contract["id"]?>"><?=$contract["contract_type"]?></label>
              </div>
            <?php endforeach;?>
          </div>

          <div class="col-3">
            <label>Оплата налогов:</label>
            <?php foreach($tax_pay_type as $tax_pay) :?>
              <div class="form-check">
                <input type="radio" class="form-check-input" id="tax_pay_type<?=$tax_pay["id"]?>" name="is_tax" value="<?=$tax_pay["id"]?>" >
                <label class="form-check-label" for="tax_pay_type<?=$tax_pay["id"]?>"><?=$tax_pay["tax_pay_type"]?></label>
              </div>
            <?php endforeach;?>
          </div>

          <div class="col-4 citizenship">
            <input type="hidden" id="citizenship_changed" value="0">
            <label>Гражданство:</label>
            <?php foreach($citizenship as $citizen) :?>
              <div class="form-check">
                <input type="radio" class="form-check-input" id="citizenship<?=$citizen["id"]?>" name="citezenship_type" value="<?=$citizen["id"]?>" >
                <label class="form-check-label" for="citizenship<?=$citizen["id"]?>"><?=$citizen["citezenship_types"]?></label>
              </div>
            <?php endforeach;?>
          </div>

          <div class="col-2 country-wrap" style="display:none;">
            <label for="country">Страна:</label>
            <select class="form-select" name="country" id="country">
              <option value="-1">Выберите страну</option>
              <?php foreach($countries as $country) :?>
                <option value="<?=$country['id']?>"><?=$country['country']?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>

        <div class="row flex-grow-1 mt-3">
          <div class="col-3">
            <label for="salary">Официальная ЗП:</label>
            <input type="text" class="form-control" id="salary" placeholder="Укажите официальную ЗП">
          </div>
          <div class="col-3">
            <label for="salary_fact">Фактическая ЗП:</label>
            <input type="text" class="form-control" id="salary_fact" placeholder="Укажите фактическую ЗП">
          </div>
          <div class="col-3">
            <label for="pay_per_hour">Часовая оплата:</label>
            <input type="text" class="form-control" id="pay_per_hour" placeholder="Укажите почасовую оплату">
          </div>
          <div class="col-3">
            <label for="fire_date">Дата увольнения:</label>
            <input type="text" class="form-control" id="fire_date" placeholder="Укажите дату увольнения">
          </div>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button class="btn btn-success btn-sm mr-1" id="addPartTimeWorker">Добавить совмещение</button>
        </div>
        <div class="row mt-3 parttimes d-none">
          <table>
            <thead>
              <th>Основная работа</th>
              <th>Компания</th>
              <th>Отдел</th>
              <th>Должность</th>
              <th>Дата начала</th>
              <th>Официальная зар.плата</th>
              <th>Фактическая зар.плата</th>
              <th>Применить вычет</th>              
              <th>Дата окончания</th>
              <th></th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <td>
                <input type="checkbox" class="" id="parttime_is_base">
              </td>
              <td>
                <select class="form-select" name="" id="parttime_company">
                  <option value="-1">Выберите компанию</option>
                  <?php foreach($companies as $comp) :?>
                    <option value="<?=$comp['id']?>"><?=$comp['name']?></option>
                  <?php endforeach;?>
                </select>
              </td>
              <td>
                <select class="form-select" name="" id="parttime_department">
                  <option value="-1">Выберите отдел</option>
                  <?php foreach($department as $depart) :?>
                    <option value="<?=$depart['id']?>"><?=$depart['name']?></option>
                  <?php endforeach;?>
                </select>
              </td>
              <td>
                <select class="form-select" name="" id="parttime_position">
                  <option value="-1">Выберите должность</option>
                  <?php foreach($positions as $pos) :?>
                    <option value="<?=$pos['id']?>"><?=$pos['name']?></option>
                  <?php endforeach;?>
                </select>
              </td>
              <td>
                <input type="text" class="form-control" id="parttime_start">
              </td>
              <td>
                <input type="text" class="form-control" id="parttime_salary_official">
              </td>
              <td>
                <input type="text" class="form-control" id="parttime_salary_fact">
              </td>
              <td>
                <input type="checkbox" class="" id="parttime_deduction">
              </td>
              <td>
                <input type="text" class="form-control" id="parttime_end">
              </td>
              <td>
                <a href="#" class="btn-icon-lg btn-icon save-btn"  id="parttime_save"><i class="fas fa-save"></i></a>
              </td>
            </tfoot>
          </table>
        </div>

        <div class="d-flex justify-content-end buttons mt-5">
          <button class="btn btn-info btn-sm mr-1" id="saveBtn">Сохранить</button>
          <button class="btn btn-info btn-sm mr-1" id="updateBtn">Сохранить</button>
          <button class="btn btn-info btn-sm mr-1" id="closeModal" data-bs-dismiss="modal"
            aria-label="Close">Закрыть</button>
        </div>
      </div>
    </div>
  </div>
</div>