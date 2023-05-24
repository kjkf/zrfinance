//(function() {
//  var url = 'https://debug.datatables.net/bookmarklet/DT_Debug.js';
//  if (typeof DT_Debug != 'undefined') {
//      if (DT_Debug.instance !== null) {
//          DT_Debug.close();
//      } else {
//          new DT_Debug();
//      }
//  } else {
//      var n = document.createElement('script');
//      n.setAttribute('language', 'JavaScript');
//      n.setAttribute('src', url + '?rand=' + new Date().getTime());
//      document.body.appendChild(n);
//  }
//})();
datepickerLocaleRu();
const d = new Date();
minYear = d.getFullYear()- 1;
maxYear = d.getFullYear() +1;
$( "#advance_date" ).datepicker({
  buttonImage: "images/calendar.gif",
  buttonImageOnly: true,
  buttonText: "Выберите дату",
  minDate: new Date(minYear, 0, 1),
  maxDate: new Date(maxYear, 11, 1),
  changeMonth: true,
  changeYear: true,
  altField: "#actualDate",
  dateFormat: "dd.mm.yy",
});

document.addEventListener('DOMContentLoaded', ev => {
  const tables = $('.employee_salary').DataTable({
    'scrollY': '50vh',
    'scrollX':true,
    'scrollCollapse': true,      
    'fixedColumns': {
      left: 3
    },
    'paging': false,
    'searching': false,
    'order': [[1, 'asc']],
    'info' : false,
    'columns': [
      { className: "td_text clip" },
      { className: "td_col" },
      { className: "td_text " },
      { className: "td_text " },
      { className: "td_text" },
      { className: "td_num" },
      { className: "td_money" },
      { className: "td_money" },
    ],
    footerCallback: function (row, data, start, end, display) {
      api = this.api();

      // Remove the formatting to get integer data for summation
      var intVal = function (i) {
          //return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
          return typeof i === 'string' ? i.replace(/ /g, '') * 1 : typeof i === 'number' ? i : 0;
      };

      let removeTag = function(str) {
        return str.replace(/<\/?[^>]+(>|$)/g, "");
      }

      // Total over all pages
      

        total = api
        .column(7)
        .data()
        .reduce(function (a, b) {
          b = removeTag(b);
            return intVal(a) + intVal(b);
        }, 0);
        
      // Update footer
      $(api.column(7).footer()).html(numberWithSpaces(total));
  },
  });
  const advancesContent = document.querySelector('.content.advances');
  advancesContent.addEventListener('click', e => {
    const target = e.target.closest(".trow");
    if (target) {
      showEditEmployeeAdvancesModal(target);
    }
  }); 

  const modal = document.getElementById("modal_editEmployeeAdvances");
  modal.addEventListener('click', e => {
    const target = e.target.closest(".btn-icon");
    
    if (target) {
      const tr = target.closest("tr");
      const id = tr.dataset.advanceid;

      if (target.classList.contains("save-btn")) {
        saveAdvances(modal);   
      } else if (target.classList.contains("edit-btn")) {
        //console.log("edit");
        editAdvances(tr); 
      } else if (target.classList.contains("update-btn")) {
        //console.log("update");
        updateAdvances(tr); 
      } else {
        //console.log("delete");
        deleteAdvances(tr); 
      }      
    }
  });
});

function deleteAdvances(tr) {
  const modal = document.getElementById("modal_editEmployeeAdvances");
  const trid = modal.querySelector("#emp_id").value;
  const id = tr.dataset.trid;
  const tds = tr.children;
  const sum = tds[1].innerHTML || 0;
  const data = {
    id
  };
  const url = base_url + '/salary/deleteAdvance';
  $.ajax({
    url: url,
    data: data,
    method: 'POST',
    success: function (id) {
      const adv_table = modal.querySelector("table tbody");
      EMPLOYEES[trid].all_advances = parseFloat(EMPLOYEES[trid].all_advances) -  parseFloat(intVal(sum));
      modal.querySelector("#advanced_pay").value = numberWithSpaces(EMPLOYEES[trid].all_advances);

      //console.log(typeof EMPLOYEES[trid].advances, EMPLOYEES[trid]);
      const ids = EMPLOYEES[trid].advances.map(el => el.id);
      const index = ids.indexOf(id); 
      EMPLOYEES[trid].advances.splice(index, 1);
      tr.remove();

      calcAndShowRemindSalary(trid, modal);
    },
  });    
}

function saveAdvances(modal) {
  const trid = modal.querySelector("#emp_id").value;
  //const ddate = modal.querySelector("#advance_date").value || 0;
  const ddate = dateToYMD($('#advance_date').datepicker("getDate"))  || null;
  const sum = modal.querySelector(".sum").value || 0;

  if (sum <= 0) return false;
  if (!ddate) return false;

  const data = {
    employee_id: trid,
    salary_fzp: document.getElementById("fzp_id").value,
    date_time: ddate, 
    advances: sum
  };

  const isValid = validateData(data, EMPLOYEES[trid]);
  
  if (isValid) {
    const url_path = base_url + '/salary/addAdvance';
    $.ajax({
      url: url_path,
      data: data,
      method: 'POST',
      success: function (id) {
        data.id = id;
        const tr = createAdvanceTr(data);
        const adv_table = modal.querySelector("table tbody");
        adv_table.insertAdjacentElement("beforeend", tr);
        const all_advances = parseFloat(EMPLOYEES[trid].all_advances) || 0;
        EMPLOYEES[trid].all_advances = all_advances + parseFloat(intVal(data.advances));
        EMPLOYEES[trid].advances.push(data);
        modal.querySelector("#advanced_pay").value = numberWithSpaces(EMPLOYEES[trid].all_advances);

        modal.querySelector(".sum").value = "";
        $('#advance_date').datepicker("setDate", "");

        calcAndShowRemindSalary(trid, modal);
      },
    });    
  }
}

function loadEmployeeAdvances(trid) {
  const url_path = base_url + '/salary/loadAdvances_byEmployeeId';
  const data = {
    employee_id: trid,
    salary_fzp: document.getElementById("fzp_id").value,
  };
  ;
  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (res) {
      const advances = JSON.parse(res);
      EMPLOYEES[trid].advances = advances;
      console.log(EMPLOYEES[trid]);
      displayEmployeeAdvances(trid);
    },
  });    
}

function showEditEmployeeAdvancesModal(tr) {
  if (!tr) return false;

  const trId = tr.dataset.trid;
  prepareEmployeeModalInfo(trId);
  $('#modal_editEmployeeAdvances').modal('show');
}

function numberWithSpaces(x) {
  x = x ? parseFloat(x).toFixed(2) : 0.00;
  var parts = x.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return parts.join(".");
}

function prepareEmployeeModalInfo(trid) {
  const employee = EMPLOYEES[trid];
  
  const modal = document.getElementById("modal_editEmployeeAdvances");

  //const working_hours_per_month = parseInt(employee.working_hours_per_month) || 0;
  const employee_salary_fact = parseInt(employee.employee_salary_fact) || 0;

  let official_salary = parseInt(employee.employee_salary) || 0;
  if (!official_salary && employee.direction === 'Цех') {
    official_salary = employee.pay_per_hour * employee.working_hours;
  }

  if (!employee.advances) {
    loadEmployeeAdvances(trid);
  } else {
    displayEmployeeAdvances(trid);
  }

  modal.querySelector("h4").textContent = employee.surname + " " + employee.name;
  modal.querySelector("#advanced_pay").value = numberWithSpaces(employee.all_advances);

  //modal.querySelector("#working_hours").value = working_hours_per_month;
  modal.querySelector("#official_salary").value = numberWithSpaces(Math.round(official_salary));
  modal.querySelector("#salary_fact").value = numberWithSpaces(Math.round(employee_salary_fact));
  modal.querySelector("#emp_id").value = trid;

  calcAndShowRemindSalary(trid, modal);
  
  //appendRows(employee.bonus_fines);
}

function calcAndShowRemindSalary(trid, modal) {
  const employee = EMPLOYEES[trid];
  const employee_salary_fact = parseInt(employee.employee_salary_fact) || 0;
  const working_hours =  parseInt(intVal(employee.working_hours_per_month)) || 1;
  const worked_hours_fact =  parseInt(intVal(employee.worked_hours_per_month)) || 0;

  const holiday_pay =  parseFloat(intVal(employee.holiday_pays)) || 0;
  const bonus =  parseInt(intVal(employee.bonus)) || 0;
  const fines =  parseInt(intVal(employee.fines)) || 0;

  const tax_ipn =  parseInt(intVal(employee.tax_IPN)) || 0;
  const tax_opv =  parseInt(intVal(employee.tax_OPV)) || 0;
  const tax_osms =  parseInt(intVal(employee.tax_OSMS)) || 0;
  const taxes = employee.is_tax === "1" ? 0 : employee.is_tax === "2" ? (tax_osms + tax_opv + tax_ipn) : (tax_opv + tax_ipn);

  const worked_salary = Math.round(employee_salary_fact / working_hours * worked_hours_fact);
  const worked_salary_before_tax = worked_salary + bonus - fines + holiday_pay;
  const total = worked_salary_before_tax - taxes - employee.all_advances;
  modal.querySelector("#working_hours").value  = working_hours;
  modal.querySelector("#worked_hours_fact").value  = worked_hours_fact;
  modal.querySelector("#worked_salary").value  = numberWithSpaces(worked_salary);
  modal.querySelector("#worked_salary_before_tax").value  = numberWithSpaces(worked_salary_before_tax);
  modal.querySelector("#total").value  = numberWithSpaces(total);

  EMPLOYEES[trid].totalSalaryToPay = total;

  updateTableInfo(trid);
}

function updateTableInfo(trid) {
  const tr = document.querySelector(`.employee_salary tr[data-trid='${trid}']`)
  if (tr) {
    const tds = tr.children;
    tds[7].innerHTML = numberWithSpaces(EMPLOYEES[trid].all_advances);
  }

  updateTotalsInFooter();
}

function updateTotalsInFooter() {
  //let allAdvances = EMPLOYEES.reduce((sum, current) => sum + current, 0);
  let advancesSum = 0;
  for (let key in EMPLOYEES) {
    const currentAdvance = parseFloat(EMPLOYEES[key].all_advances) || 0;
    advancesSum += currentAdvance;
  }

  const table =$(".employee_salary").DataTable();
  $(table.column(7).footer()).html(numberWithSpaces(advancesSum));

  const sumToPay = document.querySelector(".salary-total span");
  sumToPay.innerHTML = numberWithSpaces(advancesSum);
}

function displayEmployeeAdvances(trid) {
  const modal = document.getElementById("modal_editEmployeeAdvances");
  const adv_table = modal.querySelector("table tbody");
  adv_table.innerHTML = "";
  for (let i=0; i<EMPLOYEES[trid].advances.length; i++) {
    const tr = createAdvanceTr(EMPLOYEES[trid].advances[i]);
    adv_table.insertAdjacentElement("beforeend", tr);
  }
}

function createAdvanceTr(data) {
  
  const tr = document.createElement("tr");
  tr.dataset.trid = data.id;

  const advTd = document.createElement("td");
  advTd.innerHTML = numberWithSpaces(data.advances);

  const dateTd = document.createElement("td");
  let date = new Date(data.date_time);
  dateTd.innerHTML = dateToDMY(date);

  const deleteBtn = createBtnForTable("delete");
  const actionTd = document.createElement("td");
  actionTd.insertAdjacentElement('beforeend', deleteBtn);

  tr.insertAdjacentElement("beforeend", dateTd);
  tr.insertAdjacentElement("beforeend", advTd);
  tr.insertAdjacentElement("beforeend", actionTd);

  return tr;
}

function validateData(data, employee) {  
  console.log(data);
  if (data.advances <= 0) {
    alert("Укажите сумму аванса");
    return false;
  }

  const rest_salary = employee.totalSalaryToPay - data.advances;
  if (rest_salary < 0) {
    alert("Аванс превышает остаток заработной платы!");
    return false;
  }
  
  if (data.date_time == "") {
    alert("Укажите дату аванса");
    return false;
  }
  return true;
}

function createBtnForTable(actionType) {
  const a = document.createElement("a");
  a.href = "#";
  a.classList.add("btn-icon");
  a.classList.add(actionType+"-btn");

  const i = document.createElement("i");
  i.classList.add("fa-solid");
  const className = actionType === 'edit' ? "fa-pen-to-square" : actionType === 'delete' ?  "fa-trash-can" : "fa-save";
  i.classList.add(className);

  a.insertAdjacentElement('afterbegin', i);

  return a;
}

function datepickerLocaleRu() {
  $.datepicker.setDefaults({
    closeText: 'Закрыть',
    prevText: 'Пред',
    nextText: 'След',
    currentText: 'Сегодня',
    monthNames: [
      'Январь',
      'Февраль',
      'Март',
      'Апрель',
      'Май',
      'Июнь',
      'Июль',
      'Август',
      'Сентябрь',
      'Октябрь',
      'Ноябрь',
      'Декабрь',
    ],
    monthNamesShort: [
      'Янв',
      'Фев',
      'Мар',
      'Апр',
      'Май',
      'Июн',
      'Июл',
      'Авг',
      'Сен',
      'Окт',
      'Ноя',
      'Дек',
    ],
    dayNames: [
      'воскресенье',
      'понедельник',
      'вторник',
      'среда',
      'четверг',
      'пятница',
      'суббота',
    ],
    dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    weekHeader: 'Нед',
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: '',
  });
}

function dateToYMD(date) {
  if (!date) return null;
  var d = date.getDate();
  var m = date.getMonth() + 1; //Month from 0 to 11
  var y = date.getFullYear();
  return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
}

function dateToDMY(date) {
  if (!date) return null;
  var d = date.getDate();
  var m = date.getMonth() + 1; //Month from 0 to 11
  var y = date.getFullYear();
  //return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
  return '' + (d <= 9 ? '0' + d : d) + '.' + (m<=9 ? '0' + m : m) + '.' + y;
}
var intVal = function (i) {
  //return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
  return typeof i === 'string' ? i.replace(/ /g, '') * 1 : typeof i === 'number' ? i : 0;
};