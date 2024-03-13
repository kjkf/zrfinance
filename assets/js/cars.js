document.addEventListener('DOMContentLoaded', (ev) => {
  const saveCarBtn = document.getElementById('saveCar');
  if (saveCarBtn) {
    saveCarBtn.addEventListener('click', (e) => {
      save_car();
    });
  }

  const saveIndicationBtn = document.getElementById('saveIndication');
  if (saveIndicationBtn) {
    saveIndicationBtn.addEventListener('click', (e) => {
      save_indication();
    });
  }

  datepickerInit();
  if ($("#tableIndication") && $("#tableIndication").length > 0) {
    $("#tableIndication").DataTable({
      //info: false,
      columnDefs: [
        { "orderable": false, "targets": [ 0, 4 ] }
      ],
      language: {
        info: 'Страница _PAGE_ из _PAGES_',
        infoEmpty: 'Нет записей!',
        infoFiltered: '(filtered from _MAX_ total records)',
        lengthMenu: 'Показывать _MENU_ записей на странице',
        zeroRecords: 'Nothing found - sorry',
        search: 'Искать:'
    }
    });
  }
  
});

function formatDateTime(date) {
  const time =
    (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) +
    ':' +
    (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());

  return date.toLocaleDateString('ru-RU') + ' ' + time;
}

function datepickerInit() {
  const indicationDate = document.getElementById('indication_date');
  console.log($('#indication_date'));
  if (!indicationDate) return false;
  const date = new Date();

  indicationDate.value = formatDateTime(date);
}

function validityState() {
  const modal = document.getElementById('modal_addCar');
  const select = modal.querySelector('#driver');
  if (!select.value) {
    alert('Выберите водителя!');
    select.focus();
    return false;
  }

  const car = modal.querySelector('#car_name');
  if (!car.value) {
    alert('Введите номер машины!');
    car.focus();
    return false;
  }

  const consumption = modal.querySelector('#consumption');
  if (!consumption.value) {
    alert('Введите расход на 100км!');
    consumption.focus();
    return false;
  }
  return true;
}

function save_car() {
  if (!validityState()) return false;
  const modal = document.getElementById('modal_addCar');
  let collection = modal.querySelector('#driver').selectedOptions;
  let driver = collection[0].label;
  const data = {
    user: modal.querySelector('#driver').value,
    driver: driver,
    car_name: modal.querySelector('#car_name').value,
    consumption: modal.querySelector('#consumption').value,
  };

  const array_data = [driver, modal.querySelector('#car_name').value, modal.querySelector('#consumption').value, ""];

  url_path = base_url + '/cars/save_car';
  
  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (result) {
      console.log(result);
      $('#modal_addCar').modal('hide');
      addRow(array_data, 'cars');
    },
    fail: function (result) {
      console.error(result);
      alert('Error while status update');
    },
  });
}
function save_indication() {
  const modal = document.getElementById('modal_addIndication');
  const indication = modal.querySelector("#indication");
  const pic = modal.querySelector("#pic");
  if (!indication.value) {
    alert('Введите показания на текущую дату и время!');
    indication.focus();
    return false;
  }
  if (!pic.value) {
    alert('Прикрепите фото спидометра!');
    pic.focus();
    return false;
  }
  const prev_indication_input = document.getElementById('prev_indication'); 
  const prev_indication = parseFloat(prev_indication_input.value);
  if (prev_indication >= parseFloat(indication.value)) {
    alert('Текущие показания не могут быть меньше предыдущих!');
    indication.focus();
    return false;
  }

  const form = document.querySelector("#indication-form");
  form.submit();
  
}

function addRow(data, tableId) {
  const table = document.querySelector(`#${tableId}`);
  const emptyRow = table.querySelector('.empty-row');
  if (emptyRow) emptyRow.remove();

  const tbody = table.querySelector('tbody');
  const trs = tbody.querySelector('tr');
  const num = trs && trs.length > 0 ? trs.length : 1;

  const tr = createRow(num, data);

  tbody.insertAdjacentElement('beforeend', tr);
}

function createRow(num, data) {
  console.log(num, data);
  const tr = document.createElement('tr');

  const td1 = createTd(num);
  tr.insertAdjacentElement('beforeend', td1);
  data.forEach(item => {
    const td = createTd(item);
    tr.insertAdjacentElement('beforeend', td);
  });
  //const td2 = createTd(data.driver);
  //const td3 = createTd(data.car_name);
  //const td4 = createTd(data.consumption);
  //const td5 = createTd('');

  //tr.insertAdjacentElement('beforeend', td2);
  //tr.insertAdjacentElement('beforeend', td3);
  //tr.insertAdjacentElement('beforeend', td4);
  //tr.insertAdjacentElement('beforeend', td5);

  return tr;
}

function createTd(value) {
  const td = document.createElement('td');
  td.textContent = value;

  return td;
}
