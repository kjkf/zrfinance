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

function saveAdvances(modal) {
  const trid = modal.querySelector("#emp_id").value;
  const ddate = modal.querySelector("#advance_date").value || 0;
  const sum = modal.querySelector(".sum").value || 0;

  const data = {
    employee_id: trid,
    salary_fzp: document.getElementById("fzp_id").value,
    date_time: ddate, 
    advances: sum
  };

  const isValid = validateData(data);
  
  if (isValid) {
    const url_path = base_url + '/salary/addAdvance';
    $.ajax({
      url: url_path,
      data: data,
      method: 'POST',
      success: function (id) {
        
      },
    });    
  }
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

  modal.querySelector("h4").textContent = employee.surname + " " + employee.name;

  //modal.querySelector("#working_hours").value = working_hours_per_month;
  modal.querySelector("#official_salary").value = numberWithSpaces(Math.round(official_salary));
  modal.querySelector("#salary_fact").value = numberWithSpaces(Math.round(employee_salary_fact));
  modal.querySelector("#emp_id").value = trid;
  
  //appendRows(employee.bonus_fines);
}

function validateData(data) {
  
  if (data.sum <= 0) {
    alert("Укажите сумму аванса");
    return false;
  }
  
  if (data.date_time == "") {
    alert("Укажите дату аванса");
    return false;
  }
  return true;
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