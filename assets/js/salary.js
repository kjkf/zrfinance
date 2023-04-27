let CURRENT_TR = null;
let api = null;
document.addEventListener('DOMContentLoaded', e => {
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
    
    removeActiveTr(CURRENT_TR, target);
    
    if (!CURRENT_TR) {
      setActiveTr(target);
    }
  });

  document.addEventListener('click', e => {
    if(!isEditable) return false; 
    const activeRow = document.querySelector('.content.salary .trow.active');
    const target = e.target.closest('.trow');

    if (activeRow && !target) {
      const table = activeRow.closest("table");
      activeRow.classList.remove('active');
      calculateSalary(activeRow);
      
      const tds = activeRow.children;
      //deleteField(tds[6]);
      //deleteField(tds[9]);
      //deleteField(tds[10]);

      if (isTrValsChanged(tds)){
        save_tr(CURRENT_TR, activeRow);
      } else {
        closeActiveTrWithoutSave(activeRow);
      }

      //CURRENT_TR = null;
    }    
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
});

function setActiveTr(tr) {
  //console.log("setActiveTr");
  if (tr) {
    CURRENT_TR = tr.dataset.trid;
    tr.classList.add('active');
    const tds = tr.children;

    save_previous_vals(tds);

    insertField(tds[6], 'working_days_fact', 'number');
    insertField(tds[9], 'bonus', 'number');
    insertField(tds[10], 'fines', 'number');
    
    setChangeHandlers(tr);
  }
}

function save_previous_vals(tds) {
  const old_worked_hours = tds[6].querySelector("span").innerHTML;
  const old_bonus = tds[9].querySelector("span").innerHTML;
  const old_fines = tds[10].querySelector("span").innerHTML;
  document.getElementById("old_worked_hours").value = old_worked_hours;
  document.getElementById("old_bonus").value = old_bonus;
  document.getElementById("old_fines").value = old_fines;
}

function isTrValsChanged(tds) {
  const old_worked_hours = document.getElementById("old_worked_hours").value;
  const old_bonus = document.getElementById("old_bonus").value;
  const old_fines = document.getElementById("old_fines").value;

  const new_worked_hours = tds[6].querySelector("input").value;
  const new_bonus = tds[9].querySelector("input").value;
  const new_fines = tds[10].querySelector("input").value;

 if (old_worked_hours !== new_worked_hours || old_bonus !== new_bonus || old_fines !== new_fines) {
      return true;
  }

  return false;
}

function removeActiveTr(CURRENT_TR, newTR) {
  const activeRow = document.querySelector('.content.salary .trow.active');
  const newTrId = newTR ? newTR.dataset.trid : CURRENT_TR;
  if (!CURRENT_TR) return false;
  if (activeRow && newTrId !== activeRow.dataset.trid) {
    const tds = activeRow.children;
    if (isTrValsChanged(tds)){
      save_tr(CURRENT_TR, activeRow);
    } else {
      closeActiveTrWithoutSave(activeRow);
    }
    
  }  
}

function closeActiveTrWithoutSave(activeRow) {
  activeRow.classList.remove('active');
  const tds = activeRow.children;
  deleteField(tds[6]);
  deleteField(tds[9]);
  deleteField(tds[10]);

  const table = activeRow.closest("table.employee_salary");
        
  CURRENT_TR = null;
}

function insertField(td, fieldId, type) {
  const span = td.querySelector("span");
  const tdContent = span.innerHTML.trim();
  span.style.display = "none";
  
  const input = createField(fieldId, type);
  input.value = tdContent;
  td.insertAdjacentElement('afterbegin', input);
}

function deleteField(td) {
  const input = td.querySelector('input');
  const span = td.querySelector("span");
  span.textContent = numberWithSpaces(input.value);
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

function setChangeHandlers(tr) {
  const inputs = tr.querySelectorAll("input");
  
  if (inputs && inputs.length > 0) {
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].addEventListener('change', e => {
        calculateSalary(tr);
      });
    }
  }
}

function calculateSalary(tr) {
  const tds = tr.children;
  const salary = EMPLOYEES[CURRENT_TR].salary;
  const work_day_plan = parseInt(tds[5].textContent);
  const work_day_fact = parseInt(tr.querySelector("#working_days_fact").value) || 0;
  const bonus = parseFloat(tr.querySelector("#bonus").value) || 0;
  const fines = parseFloat(tr.querySelector("#fines").value) || 0;

  EMPLOYEES[CURRENT_TR].bonus = bonus;
  EMPLOYEES[CURRENT_TR].increase_payments = bonus;
  EMPLOYEES[CURRENT_TR].fines = fines;
  EMPLOYEES[CURRENT_TR].decrease_payments = fines;
  EMPLOYEES[CURRENT_TR].work_day_fact = work_day_fact;
  EMPLOYEES[CURRENT_TR].worked_hours_per_month = work_day_fact;
  const current_salary = salary/work_day_plan*work_day_fact;
  const total = current_salary + bonus - fines;
  tds[8].textContent = numberWithSpaces(current_salary.toFixed(2));
  tds[11].textContent = numberWithSpaces(total.toFixed(2));

}

var intVal = function (i) {
  //return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
  return typeof i === 'string' ? i.replace(/ /g, '') * 1 : typeof i === 'number' ? i : 0;
};

function calcTotals(tableId) {
  const companyID = tableId.replace("salary_company_", "")
  const res = {
        //t_salary: 0,
        t_worked_salary: 0,
        t_bonus: 0,
        t_fines: 0,
        total: 0
      };

  for (let key in EMPLOYEES) {
    if (EMPLOYEES[key].company_id == companyID) {
      const bonus = EMPLOYEES[key].increase_payments ? parseFloat(EMPLOYEES[key].increase_payments) : 0;
      const fines = EMPLOYEES[key].decrease_payments ? parseFloat(EMPLOYEES[key].decrease_payments) : 0;
      const salary = EMPLOYEES[key].employee_salary ? parseFloat(EMPLOYEES[key].employee_salary) : 0;
       
      res.t_bonus += bonus;
      res.t_fines += fines;

      const current_salary = salary/EMPLOYEES[key].working_hours_per_month*EMPLOYEES[key].worked_hours_per_month;
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
  const pkTable = $("#salary_company_2").DataTable();
  const tdTable = $("#salary_company_3").DataTable();
  const montTable = $("#salary_company_4").DataTable();

  const pkSum = $(pkTable.column(11).footer()).html().replace(/ /g, "");
  const tdSum = $(tdTable.column(11).footer()).html().replace(/ /g, "");
  const montSum = $(montTable.column(11).footer()).html().replace(/ /g, "");

  const sum = parseFloat(pkSum) + parseFloat(tdSum) + parseFloat(montSum);

  document.querySelector(".salary-total span").textContent = numberWithSpaces(sum);
}

//function calcColTotals(table, colIndex) {
//  let removeTag = function(str) {
//    return str.replace(/<\/?[^>]+(>|$)/g, "");
//  }
//  let colTotal = 0;
  
//  colTotal = table
//      .column(colIndex)
//      .data()
//      .reduce(function (a, b) {
//        b = removeTag(b);
//        //console.log(b);
//          return intVal(a) + intVal(b);
//      }, 0);
//  console.log(colIndex, colTotal);
//  return colTotal;
//}

function numberWithSpaces(x) {
  x = x ? parseFloat(x).toFixed(2) : 0.00;
  var parts = x.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return parts.join(".");
}

function save_tr(trid, activeRow) {
  const data = {
    'fzp_id' : EMPLOYEES[trid].fzp_id,
    'worked_hours_per_month' : EMPLOYEES[trid].work_day_fact,
    'increase_payments' : EMPLOYEES[trid].bonus,
    //'increase_explanation' : EMPLOYEES[trid].,
    'decrease_payments' : EMPLOYEES[trid].fines,
    //'decrease_explanation' : EMPLOYEES[trid].,
    'id': trid
  };
  calculateSalary(activeRow);
  
  url_path = base_url + '/salary/update_employee_salary_calculation';
  $.ajax({
    url: url_path,
    data: data,
    method: 'POST',
    success: function (result) {
      //console.log("ajax success");
      activeRow.classList.remove('active');
      const tds = activeRow.children;
      deleteField(tds[6]);
      deleteField(tds[9]);
      deleteField(tds[10]);

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