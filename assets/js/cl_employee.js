let EMPLOYEES = {};

document.addEventListener("DOMContentLoaded", ev => {

  datepickerLocaleRu();

  const d = new Date();
  let minYear = d.getFullYear()- 2;
  let maxYear = d.getFullYear();
  $( "#fire_date" ).datepicker({
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

  minYear = d.getFullYear()- 66;
  maxYear = d.getFullYear() - 15;
  $( "#birth_date" ).datepicker({
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
  
  minYear = d.getFullYear()- 2;
  maxYear = d.getFullYear() + 1;
  $( "#start_date" ).datepicker({
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
  const modal = document.getElementById("modal_employeeInfo");
  const cl_empl = document.querySelector("#nav-employees");
  const employeeTable = document.getElementById("tbl_employees");
  if (cl_empl) {
    //const modal = document.getElementById("modal_employeeInfo");
    
    cl_empl.addEventListener("click", e => {
      const target = e.target.closest("tr.emp_info");
      if (!target) return false;
      const id = target.dataset.trid;
      let currentEmployee = EMPLOYEES[id];
      if (!currentEmployee) {
        currentEmployee = loadEmployeeInfo(id);       
      } else {
        prepareModalVals(modal, id);        
        $('#modal_employeeInfo').modal('show');
      }
      
    });    
  }

  const employeeFiredTab = document.getElementById("nav-employee_tab_fired");
  if (employeeFiredTab) {
    let IS_FIRED_LOADED = false;
    employeeFiredTab.addEventListener('click', e => {
      if (!IS_FIRED_LOADED) {        
        url_path = base_url + '/Classificators/get_fired_employees';
        $.ajax({
          url: url_path,
          data: [],
          method: 'POST',
          success: function (result) {
            IS_FIRED_LOADED = true;   
            firedEmployees = JSON.parse(result);
            const  tblEmployeesFired = document.getElementById("tbl_employees_fired");
            draw_table_body(tblEmployeesFired, firedEmployees);
          },
          fail: function(result) {
            console.error(result);
            alert("Error fired employees loaded");
          }
        });
      }
    });
  }

  const citizenshipWrap = document.querySelector(".citizenship");
  if (citizenshipWrap) {
    citizenshipWrap.addEventListener('change', e => {
      const countryWrap = document.querySelector(".country-wrap");
      if (!countryWrap) return false;
      if (e.target && e.target.value && parseInt(e.target.value) === 3) {
        countryWrap.style.display = "block";
      } else {
        countryWrap.style.display = "none";
      }
      const trid = modal.querySelector("#trid").value;
      //console.log(e.target);
      const property = e.target.getAttribute("name"); //$(this).attr("name");
      //console.log(property);
      var selected = $("input[type='radio'][name='" + property + "']:checked");
      console.log(trid, selected, selected.val());
      if (selected.length > 0) {
        const inputVal = selected.val();
        if (EMPLOYEES[trid][property] !== inputVal) {
          document.getElementById("citizenship_changed").value = 1;
        }
      }
      
    });
  }

  $("input").on("change", function(e) {
    //console.log("input changed", );
    //modal.querySelector("#is_tr_changed").value = 0;
    const trid = modal.querySelector("#trid").value;
   
    if ($(this).attr("type") === "radio") {
      const property = $(this).attr("name");
      var selected = $("input[type='radio'][name='" + property + "']:checked");
      if (selected.length > 0) {
          const inputVal = selected.val();
          //console.log(EMPLOYEES[trid][property], inputVal);
          //console.log(property, EMPLOYEES[trid]);
          if (EMPLOYEES[trid][property] !== inputVal) {
            modal.querySelector("#is_tr_changed").value = 1;
          }
      }
      
    } else {
      const property = $(this).attr("id");
      if (EMPLOYEES[trid][property] !== $(this).value) {
        modal.querySelector("#is_tr_changed").value = 1;
      }
    } 
  });

  $("select").on("change", function(e) {
    const trid = modal.querySelector("#trid").value;
   
    const property = $(this).attr("id");
    const select = document.getElementById(property);
    const value = e.value;
    //console.log("!!!!", property, "222", select, "333", value);
    if (EMPLOYEES[trid][property] !== value) {
      modal.querySelector("#is_tr_changed").value = 1;
    }
  });

  const updateEmployeeInfoBtn = modal.querySelector("#updateBtn");
  if (updateEmployeeInfoBtn) {
    updateEmployeeInfoBtn.addEventListener('click', e => {
      const id = modal.querySelector("#trid").value;
      updateEmployeeInfo(id, modal);
    });
  }

  const saveEmployeeInfoBtn = modal.querySelector("#saveBtn");
  if (saveEmployeeInfoBtn) {
    saveEmployeeInfoBtn.addEventListener('click', e => {
      saveEmployeeInfo(modal);
    });
  }

  const addEmployeeBtn = document.getElementById("addE");
  if (addEmployeeBtn) {
    addEmployeeBtn.addEventListener("click", e => {
      clearModalVals(modal, 'save');    
      EMPLOYEES["new"] = getNewEmployee();
      console.log(EMPLOYEES);
      modal.querySelector("#trid").value = "new";
      $('#modal_employeeInfo').modal('show');
    });
  }
});

function draw_table_body(table, data) {
  const tbody = table.querySelector("tbody");
  for (let key in data) {
    const tr = createSpanTr(key, "trSpan");
    tbody.insertAdjacentElement('beforeend', tr);
    const employees = data[key];
    if (employees.length > 0) {
      for (let i=0; i<employees.length; i++) {
        let count = i + 1;
        const emp_tr = createEmployeeTr(count, employees[i]);
        tbody.insertAdjacentElement('beforeend', emp_tr);
      }
    } else {
      const tr = createSpanTr("Нет записей", "empty");
      tbody.insertAdjacentElement('beforeend', tr);
    }    
  }
}

function createSpanTr(text, className) {
  const tr = document.createElement("tr");
  const td = document.createElement("td");
  tr.className = className;
  td.setAttribute("colspan", 9);
  td.textContent = text;
  tr.insertAdjacentElement('afterbegin', td);
  return tr;
}

function createEmployeeTr(count, employee) {
  const tr = document.createElement("tr");
  tr.classList = "emp_info";
  //tr.setAttribute('trid', employee.id);
  tr.dataset.trid = employee.id;

  const numTd = createTd(count, "");
  tr.insertAdjacentElement('beforeend', numTd);

  const fio = createTd(employee.fio, 'td_text');
  tr.insertAdjacentElement('beforeend', fio);

  const company = createTd(employee.company, 'td_text');
  tr.insertAdjacentElement('beforeend', company);

  const department = createTd(employee.department, 'td_text');
  tr.insertAdjacentElement('beforeend', department);

  const position = createTd(employee.position, 'td_text');
  tr.insertAdjacentElement('beforeend', position);

  const email = createTd(employee.email, 'td_text');
  tr.insertAdjacentElement('beforeend', email);

  const telephone = createTd(employee.telephone, 'td_text');
  tr.insertAdjacentElement('beforeend', telephone);

  const salary = createTd(employee.salary, 'td_money');
  tr.insertAdjacentElement('beforeend', salary);

  const ddate = new Date(employee.fire_date);
  const fireDate = ddate.toLocaleDateString("ru-RU");
  const fire_date = createTd(fireDate, 'td_text');
  tr.insertAdjacentElement('beforeend', fire_date);

  return tr;
}

function createActiveEmployeeTr(count, employee) {
  const tr = document.createElement("tr");
  tr.classList = "emp_info";
  //tr.setAttribute('trid', employee.id);
  tr.dataset.trid = employee.id;

  const numTd = createTd(count, "");
  tr.insertAdjacentElement('beforeend', numTd);

  const fio = createTd(employee.surname + " " + employee.name, 'td_text');
  tr.insertAdjacentElement('beforeend', fio);

  const companySelect = document.getElementById("company");
  const companyText = companySelect.options[employee.company] ? companySelect.options[employee.company].text : "";
  const company = createTd(companyText, 'td_text');
  tr.insertAdjacentElement('beforeend', company);

  const departmentSelect = document.getElementById("department");
  const departmentText = departmentSelect.options[employee.department] ? departmentSelect.options[employee.department].text : "";
  const department = createTd(departmentText, 'td_text');
  tr.insertAdjacentElement('beforeend', department);

  const positionSelect = document.getElementById("position");
  const positionText = positionSelect.options[employee.position] ? positionSelect.options[employee.position].text : "";
  const position = createTd(positionText, 'td_text');
  tr.insertAdjacentElement('beforeend', position);

  const email = createTd(employee.email, 'td_text');
  tr.insertAdjacentElement('beforeend', email);

  const telephone = createTd(employee.telephone, 'td_text');
  tr.insertAdjacentElement('beforeend', telephone);

  const salary = createTd(employee.salary, 'td_money');
  tr.insertAdjacentElement('beforeend', salary);

  const salary_fact = createTd(employee.salary_fact, 'td_money');
  tr.insertAdjacentElement('beforeend', salary_fact);

  return tr;
}

function createTd(fld, className) {
  const td = document.createElement('td');
  td.textContent = fld;
  td.className = className;

  return td;
}

function loadEmployeeInfo(id) {
  //console.log("loadEmployeeInfo");
  const url_path = base_url + '/Classificators/get_employee_byId';
  const data = {
    "trid": id
  };
  
  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (result) {
      const currentEmployee = JSON.parse(result);
      const modal = document.getElementById("modal_employeeInfo");

      if (currentEmployee.length > 0) {
        EMPLOYEES[id] = currentEmployee[0];
        prepareModalVals(modal, id);
        
        $('#modal_employeeInfo').modal('show');
      }
      
    },
    fail: function(result) {
      console.error(result);
      alert("Error loading employee by id");
    }
  });
}

function prepareModalVals(modal, id) {
  clearModalVals(modal, 'update');
  modal.querySelector("#trid").value = id;
  modal.querySelector("#surname").value = EMPLOYEES[id].surname;
  modal.querySelector("#name").value = EMPLOYEES[id].name;
  modal.querySelector("#middlename").value = EMPLOYEES[id].middlename;
  
  if (EMPLOYEES[id].birth_date) $('#birth_date').datepicker("setDate", new Date(EMPLOYEES[id].birth_date) );
  
  modal.querySelector("#telephone").value = EMPLOYEES[id].telephone;
  modal.querySelector("#email").value = EMPLOYEES[id].email;
  if (EMPLOYEES[id].start_date) $('#start_date').datepicker("setDate", new Date(EMPLOYEES[id].start_date) );
  
  modal.querySelector("#company").value = EMPLOYEES[id].company;
  modal.querySelector("#position").value = EMPLOYEES[id].position;
  modal.querySelector("#department").value = EMPLOYEES[id].department;
  modal.querySelector("#direction").value = EMPLOYEES[id].direction;

  $('input:radio[name="contract_type"][value="'+EMPLOYEES[id].contract_type+'"]').attr('checked', true);
  $('input:radio[name="is_tax"][value="'+EMPLOYEES[id].is_tax+'"]').attr('checked', true);
  $('input:radio[name="citezenship_type"][value="'+EMPLOYEES[id].citezenship_type+'"]').attr('checked', true);
  if (EMPLOYEES[id].citezenship_type === '3') {
    modal.querySelector("#country").value = EMPLOYEES[id].country;
    modal.querySelector(".country-wrap").style.display = 'block';
  }   

  modal.querySelector("#salary").value = EMPLOYEES[id].salary;
  modal.querySelector("#salary_fact").value = EMPLOYEES[id].salary_fact;
  modal.querySelector("#pay_per_hour").value = EMPLOYEES[id].pay_per_hour;
  if (EMPLOYEES[id].fire_date) $('#fire_date').datepicker("setDate", new Date(EMPLOYEES[id].fire_date) );
}

function clearModalVals(modal, type="update") {
  if (type === "save") {
    modal.querySelector("#saveBtn").style.display = "block";
    modal.querySelector("#updateBtn").style.display = "none";
  } else {    
    modal.querySelector("#saveBtn").style.display = "none";
    modal.querySelector("#updateBtn").style.display = "block";
  }

  modal.querySelector("#is_tr_changed").value = 0;
  modal.querySelector("#citizenship_changed").value = 0;
  modal.querySelector("#surname").value = '';
  modal.querySelector("#name").value = '';
  modal.querySelector("#middlename").value = '';
  $('#birth_date').datepicker("setDate", '' );
  
  modal.querySelector("#telephone").value = '';
  modal.querySelector("#email").value = '';
  $('#start_date').datepicker("setDate", '' );
  
  modal.querySelector("#company").value = '';
  modal.querySelector("#position").value = '';
  modal.querySelector("#direction").value = '';
  modal.querySelector("#department").value = '';
  $('input:radio[name="contract_type"]').attr('checked', false);
  $('input:radio[name="is_tax"]').attr('checked', false);
  $('input:radio[name="citezenship_type"]').attr('checked', false);

  modal.querySelector("#country").value = '';
  modal.querySelector(".country-wrap").style.display = 'none';

  modal.querySelector("#salary").value = '';
  modal.querySelector("#salary_fact").value = '';
  modal.querySelector("#pay_per_hour").value = '';
  $('#fire_date').datepicker("setDate", '' );
}

function saveEmployeeInfo(modal) {
  const url_path = base_url + '/Classificators/save_employee';
  const isValidVals = isValidEmployeeVals(modal);
  if (!isValidVals) return false;
  updateEmployeeJSON(modal, "new");
  const data = getUpdateFields("new");
  

  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (result) {
      console.log(result);
      const id = result;
      modal.querySelector("#closeModal").click();
      EMPLOYEES[id] = EMPLOYEES["new"];
      delete EMPLOYEES["new"];

      location.reload(); 
      
      //updateTableAfterSaving(id);
      //if (document.getElementById("citizenship_changed").value === "1") {
      //  updateCitizenship(id);
      //}
      
    },
    fail: function(result) {
      console.error(result);
      alert("Error loading employee by id");
    }
  });
}

function updateEmployeeInfo(id, modal) {
  const isChanged = modal.querySelector("#is_tr_changed").value === "0" ? false : true;
  //console.log("isChanged="+isChanged);
  if (!isChanged) {
    modal.querySelector("#closeModal").click();
    return false;
  }

  updateEmployeeJSON(modal, id);
  const data = getUpdateFields(id);

  const url_path = base_url + '/Classificators/update_employee_byId';
    
  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (result) {
      //console.log(result);
      modal.querySelector("#closeModal").click();
      //addTableAfterSaving(id);
      if (document.getElementById("citizenship_changed").value === "1") {
        updateCitizenship(id);
      }
      
    },
    fail: function(result) {
      console.error(result);
      alert("Error loading employee by id");
    }
  });
}

function updateTableAfterSaving(id) {
  const tr = document.querySelector("table.employees tr[data-trid='" + id + "']");
  
  if (tr) {
    const tds = tr.children;
    tds[1].textContent = EMPLOYEES[id].surname + " " + EMPLOYEES[id].name;

    const companySelect = document.getElementById("company");
    const companyText = companySelect.options[companySelect.selectedIndex].text;
    tds[2].textContent = companyText;

    const departmentSelect = document.getElementById("department");
    const departmentText = departmentSelect.options[departmentSelect.selectedIndex].text;
    tds[3].textContent = departmentText;

    const positionSelect = document.getElementById("position");
    const positionText = positionSelect.options[positionSelect.selectedIndex].text;
    tds[4].textContent = positionText;

    tds[5].textContent = EMPLOYEES[id].email;
    tds[6].textContent = EMPLOYEES[id].telephone;
    tds[7].textContent = parseFloat(EMPLOYEES[id].pay_per_hour) > 0 ? numberWithSpaces(EMPLOYEES[id].pay_per_hour) : numberWithSpaces(EMPLOYEES[id].salary);
    tds[8].textContent = numberWithSpaces(EMPLOYEES[id].salary_fact);
  } else {
    addEmployeeTr(id);
  }
}

function addEmployeeTr(id) {
  //console.log(id);
  //console.log("++++++++++++++++++++");
  //console.log(EMPLOYEES);
  const table = document.getElementById("tbl_employees");
  const tbody = table.querySelector("tbody");

  const count = document.getElementById("employeeCount").value;
  document.getElementById("employeeCount").value = parseInt(count) + 1 || 1;
  const emp_tr = createActiveEmployeeTr(count, EMPLOYEES[id]);
  tbody.insertAdjacentElement('beforeend', emp_tr)
}

function updateCitizenship(id) {
  const data = {
    "trid" : id,
    "country" : EMPLOYEES[id].country,
    "citezenship_type" : EMPLOYEES[id].citezenship_type
  }
  const url = base_url + '/Classificators/update_citezenship_type';
  $.ajax({
    url: url,
    data: data,
    method: 'POST',
    success: function (result) {
            
    },
    fail: function(result) {
      console.error(result);
      alert("Error loading employee by id");
    }
  });
}

function updateEmployeeJSON(modal, id) {
  EMPLOYEES[id].surname = modal.querySelector("#surname").value;
  EMPLOYEES[id].name = modal.querySelector("#name").value;
  EMPLOYEES[id].middlename = modal.querySelector("#middlename").value;
  EMPLOYEES[id].telephone = modal.querySelector("#telephone").value;
  EMPLOYEES[id].email = modal.querySelector("#email").value;
  EMPLOYEES[id].company = modal.querySelector("#company").value || null;
  EMPLOYEES[id].position = modal.querySelector("#position").value  || null;
  EMPLOYEES[id].direction = modal.querySelector("#direction").value  || null;
  EMPLOYEES[id].department = modal.querySelector("#department").value  || null;
  EMPLOYEES[id].country = modal.querySelector("#country").value  || null;
  EMPLOYEES[id].salary = modal.querySelector("#salary").value || 0;
  EMPLOYEES[id].salary_fact = modal.querySelector("#salary_fact").value || 0;
  EMPLOYEES[id].pay_per_hour = modal.querySelector("#pay_per_hour").value || 0;

  EMPLOYEES[id].contract_type = modal.querySelector('input[name="contract_type"]:checked').value  || null;
  EMPLOYEES[id].is_tax = modal.querySelector('input[name="is_tax"]:checked').value  || null;
  EMPLOYEES[id].citezenship_type = modal.querySelector('input[name="citezenship_type"]:checked').value  || null;

  EMPLOYEES[id].fire_date = dateToYMD($('#fire_date').datepicker("getDate")) || null;
  EMPLOYEES[id].start_date = dateToYMD($('#start_date').datepicker("getDate"))  || null;
  EMPLOYEES[id].birth_date = dateToYMD($('#birth_date').datepicker("getDate"))  || null;
  
}

function isValidEmployeeVals(modal) {
  if (modal.querySelector("#surname").value === "") {
    alert("Укажите фамилию сотрудника");
    modal.querySelector("#surname").focus();
    return false;
  } 
  if (modal.querySelector("#name").value === "") {
    modal.querySelector("#name").focus();
    alert("Укажите имя сотрудника");
    return false;
  }  
  if (!$('#start_date').datepicker("getDate")) {
    modal.querySelector("#start_date").focus();
    alert("Укажите дату принятия на работу");
    return false;
  }
  if (!modal.querySelector("#company").value) {
    modal.querySelector("#company").focus();
    alert("Укажите компанию");
    return false;
  } 
  if (!modal.querySelector("#department").value) {
    modal.querySelector("#department").focus();
    alert("Укажите отдел");
    return false;
  } 
    if (!modal.querySelector("#direction").value) {
    modal.querySelector("#direction").focus();
    alert("Укажите направление");
    return false;
  } 
  if (!modal.querySelector("#position").value) {
    modal.querySelector("#position").focus();
    alert("Укажите должность");
    return false;
  } 
  if (!modal.querySelector('input[name="contract_type"]:checked')) {
    modal.querySelector("#contract_type1 + label").focus();
    alert("Укажите тип договора");
    return false;
  } 
  if (!modal.querySelector('input[name="is_tax"]:checked')) {
    modal.querySelector("#tax_pay_type1").focus();
    alert("Укажите как оплачиваются налоги");
    return false;
  } 
  if (!modal.querySelector('input[name="citezenship_type"]:checked')) {
    modal.querySelector("#citizenship1").focus();
    alert("Укажите тип резедента");
    return false;
  } 

  if (!modal.querySelector("#salary").value && !modal.querySelector("#pay_per_hour").value ) {
    modal.querySelector("#salary").focus();
    alert("Укажите официальную зарплату или ставку в час");
    return false;
  } 
  
  if (!modal.querySelector("#salary_fact").value) {
    modal.querySelector("#salary_fact").focus();
    alert("Укажите фактическую зарплату");
    return false;
  } 
  
  
  return true;
}

function dateToYMD(date) {
  if (!date) return null;
  var d = date.getDate();
  var m = date.getMonth() + 1; //Month from 0 to 11
  var y = date.getFullYear();
  return '' + y + '-' + (m<=9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
}

function getUpdateFields(id) {
  //console.log(EMPLOYEES[id]);
  const data = 
  {
    'trid' : id,
    'surname' : EMPLOYEES[id].surname,
    'name' : EMPLOYEES[id].name,
    'middlename' : EMPLOYEES[id].middlename,
    'telephone' : EMPLOYEES[id].telephone,
    'email' : EMPLOYEES[id].email,
    'company' : EMPLOYEES[id].company,
    'position' : EMPLOYEES[id].position,
    'direction' : EMPLOYEES[id].direction,
    'department' : EMPLOYEES[id].department,
    'country' : EMPLOYEES[id].country,
    'salary' : EMPLOYEES[id].salary,
    'salary_fact' : EMPLOYEES[id].salary_fact,
    'pay_per_hour' : EMPLOYEES[id].pay_per_hour,
    'contract_type' : EMPLOYEES[id].contract_type,
    'is_tax' : EMPLOYEES[id].is_tax,
    'citezenship_type' : EMPLOYEES[id].citezenship_type,
    'fire_date' : EMPLOYEES[id].fire_date,
    'start_date' : EMPLOYEES[id].start_date,
    'birth_date' : EMPLOYEES[id].birth_date,
  }

  return data;
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

function getNewEmployee() {
  return {
    birth_date: "",
    citezenship_type: "",
    company: "",
    contract_type: "",
    country: null,
    department: "",
    direction: "",
    email: "",
    fire_date: null,
    id: "",
    is_tax: "",
    middlename: "",
    name: "",
    pay_per_hour: "",
    position: "",
    salary: "",
    salary_fact: "",
    start_date: "",
    surname: "",
    telephone: ""
  }
}

function numberWithSpaces(x) {
  x = x ? parseFloat(x).toFixed(2) : 0.00;
  var parts = x.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return parts.join(".");
}