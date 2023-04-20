let CURRENT_TR = null;

document.addEventListener('DOMContentLoaded', e => {
  $('.employee_salary').DataTable({
    'scrollY': '50vh',
    'scrollX':true,
    'scrollCollapse': true,
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
    ]
  });

  const salaryContent = document.querySelector('.content.salary');
  salaryContent.addEventListener('click', e => {
    const target = e.target.closest('.trow');
    
    removeActiveTr(CURRENT_TR, target);
    
    if (!CURRENT_TR) {
      setActiveTr(target);
    }
  });

  document.addEventListener('click', e => {
    const activeRow = document.querySelector('.content.salary .trow.active');
    const target = e.target.closest('.trow');
    if (activeRow && !target) {
      activeRow.classList.remove('active');
      calculateSalary(activeRow);
      const tds = activeRow.children;
      deleteField(tds[6]);
      deleteField(tds[9]);
      deleteField(tds[10]);
      
      CURRENT_TR = null;
    }
    
  });
});

function setActiveTr(tr) {
  console.log("setActiveTr");
  if (tr) {
    CURRENT_TR = tr.dataset.trid;
    tr.classList.add('active');
    const tds = tr.children;
    insertField(tds[6], 'working_days_fact', 'number');
    insertField(tds[9], 'bonus', 'number');
    insertField(tds[10], 'fines', 'number');
    
    setChangeHandlers(tr);
  }
}

function removeActiveTr(CURRENT_TR, newTR) {
  const activeRow = document.querySelector('.content.salary .trow.active');
  const newTrId = newTR ? newTR.dataset.trid : CURRENT_TR;
  if (!CURRENT_TR) return false;
  if (activeRow && newTrId !== activeRow.dataset.trid) {
    save_tr(CURRENT_TR, activeRow);
    
  }  
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
  EMPLOYEES[CURRENT_TR].fines = fines;
  EMPLOYEES[CURRENT_TR].work_day_fact = work_day_fact;
  const current_salary = salary/work_day_plan*work_day_fact;
  const total = current_salary + bonus - fines;
  tds[8].textContent = numberWithSpaces(current_salary.toFixed(2));
  tds[11].textContent = numberWithSpaces(total.toFixed(2));
}

function numberWithSpaces(x) {
  var parts = x.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return parts.join(".");work_day_fact
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
      console.log("ajax success");
      activeRow.classList.remove('active');
      const tds = activeRow.children;
      deleteField(tds[6]);
      deleteField(tds[9]);
      deleteField(tds[10]);
      
      CURRENT_TR = null;
    },
  });

}