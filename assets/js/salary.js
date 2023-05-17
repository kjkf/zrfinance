let CURRENT_TR = null;
let api = null;
document.addEventListener('DOMContentLoaded', ev => {
  //console.log(bonusTypes, finesTypes);

  const tables = $('.employee_salary').DataTable({
    'scrollY': '50vh',
    'scrollX':true,
    'scrollCollapse': true,
      
      fixedColumns: {
        left: 3
    },
    'paging': false,
    'searching': false,
    'order': [[1, 'asc']],
    'info' : false,
    'columns': [
      { className: "td_text clip" },
      { className: "td_col" },
      { className: "td_text" },
      { className: "td_text" },
      { className: "td_text" },
      { className: "td_num" },
      { className: "td_num" },
      { className: "td_money" },
      { className: "td_money" },
      { className: "td_money" },
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
      t_salary = api
          .column(7)
          .data()
          .reduce(function (a, b) {
            b = removeTag(b);
              return intVal(a) + intVal(b);
          }, 0);

      t_worked_salary = api
          .column(8)
          .data()
          .reduce(function (a, b) {
            b = removeTag(b);
              return intVal(a) + intVal(b);
          }, 0);

      t_bonus = api
          .column(9)
          .data()
          .reduce(function (a, b) {
            b = removeTag(b);
              return intVal(a) + intVal(b);
          }, 0);

      t_fines = api
        .column(10)
        .data()
        .reduce(function (a, b) {
          b = removeTag(b);
            return intVal(a) + intVal(b);
        }, 0);

        total = api
        .column(11)
        .data()
        .reduce(function (a, b) {
          b = removeTag(b);
            return intVal(a) + intVal(b);
        }, 0);
        
      // Update footer
      $(api.column(7).footer()).html(numberWithSpaces(t_salary));
      $(api.column(8).footer()).html(numberWithSpaces(t_worked_salary));
      $(api.column(9).footer()).html(numberWithSpaces(t_bonus));
      $(api.column(10).footer()).html(numberWithSpaces(t_fines));
      $(api.column(11).footer()).html(numberWithSpaces(total));
  },
  });
  
  updateTotalSumToPay();


  const salaryContent = document.querySelector('.content.salary');
  const fzpStatus = document.getElementById("fzp_is_approved").value;
  const userRole = document.getElementById("role").value;

  const isEditable = (parseInt(fzpStatus) === 0 || parseInt(fzpStatus) === 2) && parseInt(userRole) ===5;

  salaryContent.addEventListener('click', e => {
    if(!isEditable) return false;
    const target = e.target.closest('.trow');
    
    if (!CURRENT_TR && target) {
      CURRENT_TR = target.dataset.trid;
      showEditEmployeeSalaryModal(target);
      
    }

    $('#modal_editEmployeeSalaryInfo').on('hide.bs.modal', function (e) {
      CURRENT_TR = null;
    });
  });

  const sendBtn = document.getElementById('send');
  if (sendBtn) {
    sendBtn.addEventListener('click', e => {
      const fzp_id = document.getElementById("fzp_id").value;
      changeCurrentFZPStatus (fzp_id, 4);
    });
  }

  const submitBtn = document.getElementById('submit');
  if (submitBtn) {
    submitBtn.addEventListener('click', e => {
      const fzp_id = document.getElementById("fzp_id").value;
      changeCurrentFZPStatus (fzp_id, 1);
    });
  }

  const returnBtn = document.getElementById('return');
  if (returnBtn) {
    returnBtn.addEventListener('click', e => {
      const fzp_id = document.getElementById("fzp_id").value;
      const rej_reason = document.getElementById("rej_reason").value;
      changeCurrentFZPStatus (fzp_id, 2, rej_reason);
    });
  }

  const modal = document.getElementById("modal_editEmployeeSalaryInfo");
  modal.addEventListener('click', e => {
    const target = e.target.closest(".btn-icon");
    
    if (target) {
      const tr = target.closest("tr");
      const id = tr.dataset.fbid;
      let type = target.dataset.type;
      if (!type) {
        const table = target.closest("table");
        type = table.classList.contains("bonus") ? "bonus" : "fines";
      }
      if (target.classList.contains("save-btn")) {
        saveBonusFines(type, modal);   
      } else if (target.classList.contains("edit-btn")) {
        //console.log("edit");
        editBonusFines(type, tr); 
      } else if (target.classList.contains("update-btn")) {
        //console.log("update");
        updateBonusFines(type, tr); 
      } else {
        //console.log("delete");
        deleteBonusFines(id, tr); 
      }      
    }

    const saveEmpInfoBtn = e.target.closest(".save-em-info");
    
    if (saveEmpInfoBtn) {
      saveEmpInfo(modal);
    }
  });

  const setWorkedHoursInput = document.getElementById("worked_hours_fact");
  setWorkedHoursInput.addEventListener('change', e => {
    updateModalInputs(e.currentTarget.value);
  });

  const advancedPay = document.getElementById("advanced_pay");
  advancedPay.addEventListener('change', e => {
    updateModalInputs(setWorkedHoursInput.value);
  });
  
  const holidayPay = document.getElementById("holiday_pay");
  holidayPay.addEventListener('change', e => {
    updateModalInputs(setWorkedHoursInput.value);
  });
});

function saveEmpInfo(modal ) {
  if (!CURRENT_TR) return false;
  save_tr();
  $('#modal_editEmployeeSalaryInfo').modal('hide');
}

function updateModalInputs(value) {
  if (!CURRENT_TR) return false;
  value = parseFloat(value);
  const employee = EMPLOYEES[CURRENT_TR];
  const modal = document.getElementById("modal_editEmployeeSalaryInfo");
  modal.querySelector("h4").textContent = employee.surname + " " + employee.name;

  const bonus = isNaN(parseFloat(employee.bonus)) ? 0 : parseFloat(employee.bonus);
  const fines = isNaN(parseFloat(employee.fines)) ? 0 : parseFloat(employee.fines);
  
  const holiday_pays = isNaN(intVal(modal.querySelector("#holiday_pay").value)) ? 0 : parseFloat(intVal(modal.querySelector("#holiday_pay").value));
  const advances = isNaN(intVal(modal.querySelector("#advanced_pay").value)) ? 0 : parseFloat(intVal(modal.querySelector("#advanced_pay").value));

  let official_salary = parseFloat(employee.employee_salary) || 0;
  //console.log(official_salary, employee.direction);
  //console.log(!official_salary && employee.direction === 'Цех');
  //console.log( employee.pay_per_hour, employee.working_hours);
  if (!official_salary && employee.direction === 'Цех') {
    official_salary = employee.pay_per_hour * employee.working_hours;
  }
  
  const worked_salary_off = official_salary / parseFloat(employee.working_hours_per_month) * value + holiday_pays;
  console.log(worked_salary_off);
  //если citezenship_type === 3(студент), то налоги не расчитываются
  const tax_osms = employee.contract_type === '3' ? 0 : calcOSMS(worked_salary_off);
  const tax_opv = employee.contract_type === '3' ? 0 : calcTaxOVP(worked_salary_off);
  const tax_ipn = employee.contract_type === '3' ? 0 : calc_IPN_taxes(worked_salary_off, employee.contract_type,employee.citezenship_type); 

  const taxes = employee.is_tax === "1" ? 0 : employee.is_tax === "2" ? (tax_osms + tax_opv + tax_ipn) : (tax_opv + tax_ipn);

  console.log(bonus, fines, taxes, advances, holiday_pays);
  const worked_salary = parseFloat(employee.employee_salary_fact) / parseFloat(employee.working_hours_per_month) * value;
  const total_salary = worked_salary + bonus - fines - taxes - advances + holiday_pays;
  
  modal.querySelector("#tax_osms").value = numberWithSpaces(Math.round(tax_osms));
  modal.querySelector("#tax_opv").value = numberWithSpaces(Math.round(tax_opv));
  modal.querySelector("#tax_ipn").value = numberWithSpaces(Math.round(tax_ipn));

  modal.querySelector("#worked_salary").value = numberWithSpaces(Math.round(worked_salary));
  modal.querySelector("#total").value = numberWithSpaces(Math.round(total_salary));
}

function saveBonusFines(type, modal) {
  const select = modal.querySelector(".add_"+type+" select");
  const bonus = type === 'bonus' ? modal.querySelector(".add_"+type+" .sum").value : 0;
  const fines = type === 'fines' ? modal.querySelector(".add_"+type+" .sum").value : 0;

  const data = {
    type: type,
    name:  select.options[select.selectedIndex].text,
    type_id: select.value, 
    bonus:  bonus,
    fines:  fines,
    employee_id: CURRENT_TR,
    salary_fzp: document.getElementById("fzp_id").value
  };

  const isValid = validateData(data);
  
  if (isValid) {
    const url_path = base_url + '/salary/add_bonus_fines';
    $.ajax({
      url: url_path,
      data: data,
      method: 'POST',
      success: function (id) {
        data.id = id;
        EMPLOYEES[CURRENT_TR].bonus_fines.push(data);
        let bonus = isNaN(parseFloat(EMPLOYEES[CURRENT_TR].bonus)) ? 0 : EMPLOYEES[CURRENT_TR].bonus;
        let fines = isNaN(parseFloat(EMPLOYEES[CURRENT_TR].fines)) ? 0 : EMPLOYEES[CURRENT_TR].fines;
        bonus += isNaN(parseFloat(data.bonus)) ? 0 : parseFloat(data.bonus);
        fines += isNaN(parseFloat(data.fines)) ? 0 : parseFloat(data.fines);
        EMPLOYEES[CURRENT_TR].bonus =  bonus;
        EMPLOYEES[CURRENT_TR].fines = fines;
        addBonusFines_toTable(data);
  
        modal.querySelector("table.bonus caption span").textContent = EMPLOYEES[CURRENT_TR].bonus;
        modal.querySelector("table.fines caption span").textContent = EMPLOYEES[CURRENT_TR].fines;

        const worked_hours = modal.querySelector("#worked_hours_fact").value;
        updateModalInputs(worked_hours);
      },
    });    
  }
}

function editBonusFines(type, tr) {
  const saveBtn = createBtnForTable("update");
  
  const tds = tr.children;
  const editBtn = tr.querySelector(".edit-btn");
  editBtn.style.display = "none";

  const oldValue = tds[1].innerHTML;
  const span = document.createElement("span");
  span.style.display = "none";
  span.textContent = intVal(oldValue);
  tds[1].innerHTML = "";
  const input = createField(type, 'number');
  input.value = intVal(oldValue);
  tds[1].insertAdjacentElement('beforeend', input);
  tds[1].insertAdjacentElement('beforeend', span);
  tds[2].insertAdjacentElement('afterbegin', saveBtn);
  
}

function updateBonusFines(type, tr) {
  const tds = tr.children;
  const trid = tr.dataset.fbid;
  const value = tds[1].querySelector("input").value;
  const oldVal = tds[1].querySelector("span").textContent;
  
  if (oldVal !== value) {
    if(value > 0) {
      const data = {
        "id" : trid,
        "newVal" : value,
        "type" : type
      }
    const url_path = base_url + '/salary/update_bonus_fines';
    $.ajax({
      url: url_path,
      data: data,
      method: 'POST',
      success: function (result) {
        console.log(result);
        closeEditBonusFinesTr(tr);
      },
    });    
    } else {
      const str = type === 'bonus' ? "прибавки" : "удержания";
      alert("Укажите сумму " + str);
      tds[1].querySelector("input").focus();
    }    
  } else {
    closeEditBonusFinesTr(tr);
  }
  
}

function deleteBonusFines(id, tr) {
  const data = {
    "id" : id
  }
  const url_path = base_url + '/salary/delet_bonus_fines';
    $.ajax({
      url: url_path,
      data: data,
      method: 'POST',
      success: function (result) {
        tr.remove();
    },
  });

}

function closeEditBonusFinesTr(tr) {
  const tds = tr.children;
  const newVal = tds[1].querySelector("input").value;
  tds[1].querySelector("span").remove();
  tds[1].querySelector("input").remove();
  tds[1].innerHTML = numberWithSpaces(newVal);

  const updateBtn = tds[2].querySelector(".update-btn");
  updateBtn.remove();

  const editBtn = tds[2].querySelector(".edit-btn");
  editBtn.style.display = "inline";
}

function addBonusFines_toTable(data) {
  const table = document.querySelector("#modal_editEmployeeSalaryInfo table."+ data.type);
  const tbody = table.querySelector("tbody");
  const tfoot = table.querySelector("tfoot");

  const tr = createDataTr(data);
  tbody.insertAdjacentElement('beforeend', tr);

  tfoot.querySelector('select').value = -1;
  tfoot.querySelector('.sum').value = 0;
}

function validateData(data) {
  const str = data.type === "bonus" ? "прибавки" : "удержания";
  const sum = data.type === "bonus" ? data.bonus : data.fines;
  if (data.type_id < 0) {
    alert("Выберите тип " + str);
    return false;
  }

  if (sum <= 0) {
    alert("Укажите сумму " + str);
    return false;
  }
  return true;
}

function showEditEmployeeSalaryModal(tr) {
  if (!tr) return false;

  const trId = tr.dataset.trid;
  prepareEmployeeModalInfo(trId);
  $('#modal_editEmployeeSalaryInfo').modal('show');
}

function prepareEmployeeModalInfo(trid) {
  const employee = EMPLOYEES[trid];
  console.log(employee);
  
  const modal = document.getElementById("modal_editEmployeeSalaryInfo");

  const bonus = parseFloat(employee.bonus) || 0;
  const fines = parseFloat(employee.fines) || 0;

  const tax_osms = parseFloat(employee.tax_OSMS) || 0;
  const tax_opv = parseFloat(employee.tax_OPV) || 0;
  const tax_ipn = parseFloat(employee.tax_IPN) || 0;

  const working_hours_per_month = parseInt(employee.working_hours_per_month) || 0;
  const worked_hours_per_month = parseInt(employee.worked_hours_per_month) || 0;

  const employee_salary_fact = parseInt(employee.employee_salary_fact) || 0;

  let official_salary = parseInt(employee.employee_salary) || 0;
  //console.log(official_salary, employee.direction);
  //console.log(!official_salary && employee.direction === 'Цех');
  //console.log( employee.pay_per_hour, employee.working_hours);
  if (!official_salary && employee.direction === 'Цех') {
    official_salary = employee.pay_per_hour * employee.working_hours;
  }

  const holiday_pays = isNaN(parseFloat(employee.holiday_pays)) ? 0 : parseFloat(employee.holiday_pays);
  const advances = isNaN(parseFloat(employee.advances)) ? 0 : parseFloat(employee.advances);
  const salary = employee_salary_fact / working_hours_per_month * worked_hours_per_month;
  const total_salary = salary + bonus - fines - tax_osms - tax_opv - tax_ipn + holiday_pays - advances;


  modal.querySelector("h4").textContent = employee.surname + " " + employee.name;

  modal.querySelector("#tax_osms").value = numberWithSpaces(Math.round(tax_osms));
  modal.querySelector("#tax_opv").value = numberWithSpaces(Math.round(tax_opv));
  modal.querySelector("#tax_ipn").value = numberWithSpaces(Math.round(tax_ipn));

  modal.querySelector("#advanced_pay").value = numberWithSpaces(Math.round(advances));
  modal.querySelector("#holiday_pay").value = numberWithSpaces(Math.round(holiday_pays));

  modal.querySelector("#working_hours").value = working_hours_per_month;
  modal.querySelector("#worked_hours_fact").value = worked_hours_per_month;
  modal.querySelector("#worked_salary").value = numberWithSpaces(Math.round(salary));
  modal.querySelector("#official_salary").value = numberWithSpaces(Math.round(official_salary));
  modal.querySelector("#salary_fact").value = numberWithSpaces(Math.round(employee_salary_fact));
  modal.querySelector("#total").value = numberWithSpaces(Math.round(total_salary));


  appendRows(employee.bonus_fines);
}

function appendRows(data) {
  const modal = document.getElementById("modal_editEmployeeSalaryInfo");
  const bonusTable = modal.querySelector("table.bonus tbody");
  const finesTable = modal.querySelector("table.fines tbody");
  bonusTable.innerHTML = "";
  finesTable.innerHTML = "";
  let bonus = 0, fines=0;
  for (let i=0; i < data.length; i++) {
    const tr = createDataTr(data[i]);
    
    if (data[i].type === 'bonus') {
      bonusTable.insertAdjacentElement('beforeend', tr);
      bonus += parseFloat(data[i].bonus);
    } else {
      finesTable.insertAdjacentElement('beforeend', tr);
      fines += parseFloat(data[i].fines);
    }
  }

  modal.querySelector("table.bonus caption span").textContent = numberWithSpaces(bonus);
  modal.querySelector("table.fines caption span").textContent = numberWithSpaces(fines);
}

function createDataTr(data) {
  const tr = document.createElement("tr");
  const sum = data.type === "bonus" ? data.bonus : data.fines;
  tr.dataset.fbid = data.id;
  const sumTd = createDataTd(numberWithSpaces(sum));
  const typeTd = createDataTd(data.name);
  const actionTd = createActionTd();

  tr.insertAdjacentElement('afterbegin', actionTd);
  tr.insertAdjacentElement('afterbegin', sumTd);
  tr.insertAdjacentElement('afterbegin', typeTd);

  return tr;
}

function deleteField(td) {
  const input = td.querySelector('input');
  const span = td.querySelector("span");
  span.textContent = input.value;
  span.style.display = "inline-block";
  input.remove();
}

function createField(id, type) {
  const fld = document.createElement("input");
  fld.type = type;
  fld.id = id;
  fld.className = "fld";
  if(type == 'number') {
    fld.min = 0;
    if (id = "working_days_fact") {
      fld.step = 10;
      fld.max = 300;
    } else {
      fld.step = 1000;
    }
    
  }

  return fld;
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

function createDataTd(value) {
  const td = document.createElement("td");
  td.textContent = value;

  return td;
}

function createActionTd() {
  const td = document.createElement("td");
  const editBtn = createBtnForTable("edit");
  const deleteBtn = createBtnForTable("delete");

  td.insertAdjacentElement("beforeend", editBtn);
  td.insertAdjacentElement("beforeend", deleteBtn);

  return td;
}

function calculateSalary(tr) {
  const tds = tr.children;
  const salary = parseFloat(EMPLOYEES[CURRENT_TR].salary);
  const salary_fact = parseFloat(EMPLOYEES[CURRENT_TR].employee_salary_fact);
  const work_day_plan = parseInt(EMPLOYEES[CURRENT_TR].working_hours_per_month);
  const bonus = isNaN(parseFloat(EMPLOYEES[CURRENT_TR].bonus)) ? 0 : parseFloat(EMPLOYEES[CURRENT_TR].bonus);
  const fines = isNaN(parseFloat(EMPLOYEES[CURRENT_TR].fines)) ? 0 : parseFloat(EMPLOYEES[CURRENT_TR].fines);
  
  const work_day_fact = parseInt(document.querySelector("#modal_editEmployeeSalaryInfo #worked_hours_fact").value) || 0;
  const tax_osms = intVal(document.querySelector("#modal_editEmployeeSalaryInfo #tax_osms").value) || 0;
  const tax_opv = intVal(document.querySelector("#modal_editEmployeeSalaryInfo #tax_opv").value) || 0;
  const tax_ipn = intVal(document.querySelector("#modal_editEmployeeSalaryInfo #tax_ipn").value) || 0;
  const advances = intVal(document.querySelector("#modal_editEmployeeSalaryInfo #advanced_pay").value) || 0;
  const holiday_pays = intVal(document.querySelector("#modal_editEmployeeSalaryInfo #holiday_pay").value) || 0;
   
  EMPLOYEES[CURRENT_TR].worked_hours_per_month = work_day_fact;
  EMPLOYEES[CURRENT_TR].tax_OSMS = tax_osms;
  EMPLOYEES[CURRENT_TR].tax_OPV = tax_opv;
  EMPLOYEES[CURRENT_TR].tax_IPN = tax_ipn;
  EMPLOYEES[CURRENT_TR].holiday_pays = holiday_pays;
  EMPLOYEES[CURRENT_TR].advances = advances;

  //const current_salary = salary/work_day_plan*work_day_fact;
  const current_salary = salary_fact/work_day_plan*work_day_fact;
  const total = current_salary + bonus - fines - parseFloat(tax_osms) - parseFloat(tax_opv) - parseFloat(tax_ipn) - parseFloat(advances) + parseFloat(holiday_pays);
  tds[6].textContent = work_day_fact;
  tds[8].textContent = numberWithSpaces(Math.round(current_salary));
  tds[9].textContent = numberWithSpaces(Math.round(bonus));
  tds[10].textContent = numberWithSpaces(Math.round(fines));
  tds[11].textContent = numberWithSpaces(Math.round(total));
}

var intVal = function (i) {
  //return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
  return typeof i === 'string' ? i.replace(/ /g, '') * 1 : typeof i === 'number' ? i : 0;
};

function calcTotals(tableId) {
  const companyID = tableId.replace("salary_company_", "");
  
  const res = {
        t_worked_salary: 0,
        t_bonus: 0,
        t_fines: 0,
        total: 0
      };

  for (let key in EMPLOYEES) {
    if (EMPLOYEES[key].company_id == companyID) {
      const bonus = EMPLOYEES[key].bonus ? parseFloat(EMPLOYEES[key].bonus) : 0;
      const fines = EMPLOYEES[key].fines ? parseFloat(EMPLOYEES[key].fines) : 0;
      const salary = EMPLOYEES[key].employee_salary_fact ? parseFloat(EMPLOYEES[key].employee_salary_fact) : 0;

      const working_hours_per_month = EMPLOYEES[key].working_hours_per_month ? parseFloat(EMPLOYEES[key].working_hours_per_month) : 0;
      const worked_hours_per_month = EMPLOYEES[key].worked_hours_per_month ? parseFloat(EMPLOYEES[key].worked_hours_per_month) : 0;
       
      res.t_bonus += bonus;
      res.t_fines += fines;

      const current_salary = salary/working_hours_per_month*worked_hours_per_month;
      const total = current_salary + bonus - fines;
      res.t_worked_salary += current_salary;
      res.total += total;

    }
  }

  return res;
}

function updateTotals(tableId) {
  const table =$("#"+tableId).DataTable();
  
  const res = calcTotals(tableId);

  $(table.column(8).footer()).html(numberWithSpaces(res.t_worked_salary));
  $(table.column(9).footer()).html(numberWithSpaces(res.t_bonus));
  $(table.column(10).footer()).html(numberWithSpaces(res.t_fines));
  $(table.column(11).footer()).html(numberWithSpaces(res.total));

 updateTotalSumToPay();
}

function updateTotalSumToPay() {
  const table = $("#salary_company").DataTable();
  //const pkTable = $("#salary_company_2").DataTable();
  //const tdTable = $("#salary_company_3").DataTable();
  //const montTable = $("#salary_company_4").DataTable();

  //const pkSum = $(pkTable.column(11).footer()).html().replace(/ /g, "");
  //const tdSum = $(tdTable.column(11).footer()).html().replace(/ /g, "");
  //const montSum = $(montTable.column(11).footer()).html().replace(/ /g, "");
  const sum = $(table.column(11).footer()).html().replace(/ /g, "");

  //const sum = parseFloat(pkSum) + parseFloat(tdSum) + parseFloat(montSum);

  document.querySelector(".salary-total span").textContent = numberWithSpaces(sum);
}

function numberWithSpaces(x) {
  x = x ? parseFloat(x).toFixed(2) : 0.00;
  var parts = x.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return parts.join(".");
}

function save_tr() {  
  const activeRow =  document.querySelector('[data-trid="' + CURRENT_TR + '"]')
  calculateSalary(activeRow);

  const data = {
    'fzp_id' : EMPLOYEES[CURRENT_TR].fzp_id,
    'worked_hours_per_month' : EMPLOYEES[CURRENT_TR].worked_hours_per_month,
    'tax_OSMS' : EMPLOYEES[CURRENT_TR].tax_OSMS,
    'tax_OPV' : EMPLOYEES[CURRENT_TR].tax_OPV,
    'tax_IPN' : EMPLOYEES[CURRENT_TR].tax_IPN,
    'advances' : EMPLOYEES[CURRENT_TR].advances,
    'holiday_pays' : EMPLOYEES[CURRENT_TR].holiday_pays,
    'id': CURRENT_TR
  };
  
  url_path = base_url + '/salary/update_employee_salary_calculation';
  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (result) {
      const table = activeRow.closest("table.employee_salary");
            
      CURRENT_TR = null;

      setTimeout(() => {
        updateTotals(table.id);
      }, 100);
      
    },
  });

}

function changeCurrentFZPStatus (id, status, reason="") {
  const data = {
    "id": id,
    "is_approved" : status,
    "rejection_reason" : reason
  };
  url_path = base_url + '/salary/update_fzp_status';
  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (result) {
      location.href = base_url + "/salary";
    },
    fail: function(result) {
      console.error(result);
      alert("Error while status update");
    }
  });
}

function calcTaxOVP(salary) {
  return salary * 0.1;
}

function calcOSMS(salary) {
  return salary * 0.02;
}

function prepareCalcIPN(salary) {
  const mrp = parseFloat(document.getElementById("mrp").value) || 0;
  const min_zp = parseFloat(document.getElementById("min_zp").value) || 0;
  if (salary <= min_zp) {
    return calcIPN_with_90_cor(salary, mrp);
  } else {
    return calcIPN(salary, mrp);
  }
}

function calcIPN_with_90_cor(salary, mrp) {
  const res = salary - (salary * 0.1) - (salary * 0.02) - 14 * mrp;
  console.log(res);
  return (res - res * 0.9) * 0.1;
}

function calcIPN(salary, mrp) {
  const res = salary - (salary * 0.1) - (salary * 0.02) - 14 * mrp;
  return res * 0.1;
}

function calcGPH_IPN(salary) {
  const res = salary - (salary * 0.1) - (salary * 0.02);
  return res * 0.1;
}

function nonResidentIPN(salary) {
  return salary * 0.1;
}

function calc_VNJ_IPN(salary) {
  const res = salary - (salary * 0.1) - (salary * 0.02);
  return res * 0.1;
}

function calc_IPN_taxes(worked_salary_off, contract_type, citezenship_type) {
  //console.log(worked_salary_off, contract_type, citezenship_type);
  if (citezenship_type === "2") {
    return contract_type === '1' ? prepareCalcIPN(worked_salary_off) : calcGPH_IPN(worked_salary_off);
  } else if (citezenship_type === "4") {
    return calc_VNJ_IPN(worked_salary_off);
  }  else {
    return nonResidentIPN(worked_salary_off);
  }
}