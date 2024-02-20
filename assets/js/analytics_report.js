const expense_info = {};
document.addEventListener('DOMContentLoaded', (e) => {
  datepickerLocaleRu();

  initDatePickers();

  const createReport = document.getElementById('getReport');
  if (createReport) {
    createReport.addEventListener('click', (e) => {
      if (!document.getElementById('reportDate_start').value) {
        alert('Выберите дату начала отчетного периода');
        return false;
      }
      if (!document.getElementById('reportDate_end').value) {
        alert('Выберите дату окончания отчетного периода');
        return false;
      }
      if (
        document.getElementById('reportDate_end').value <
        document.getElementById('reportDate_start').value
      ) {
        alert(
          'Дата окончания отчетного периода не может быть меньше даты начала отчетного периода '
        );
        return false;
      }

      create_report();
    });
  }

  const showNewContractorsBtn = document.querySelector("#showNewContractors");
  if (showNewContractorsBtn) {
    showNewContractorsBtn.addEventListener("click", e => {
      const contractorsList = document.getElementById("newContractorsList").value;
      if (!contractorsList) return false;
      const newContractors = contractorsList.split(",");
      createNewContractorsTable(newContractors);
    });
  }

  const addNewContractorsBtn = document.querySelector("#addNewContractors");
  if (addNewContractorsBtn) {
    addNewContractorsBtn.addEventListener("click", e => {
      addNewContractors();
    });
  }

  const choose_all = document.querySelector("#choose_all");
  if (choose_all) {
    choose_all.addEventListener('click', e => {
      const allCheckbox = document.querySelectorAll("input.ch_box_select_contractor");
      if (!allCheckbox || allCheckbox.length === 0) return false;
      for (let i = 0; i < allCheckbox.length; i++) {
        allCheckbox[i].checked = choose_all.checked;
      }      
    });
  }

  const table = document.querySelector("#analytic_table");
  if (table) {
   table.addEventListener("click", e => {
    const tr = e.target.closest("tr");
    if (!tr) return;
    if (!table.contains(tr)) return;
    const activeTr = table.querySelector("tr.active");
    const expenseId = tr.dataset.cat_id;
    if (activeTr) {
      const activeCatId = activeTr.dataset.cat_id;
      if (expenseId !== activeCatId) {
        removeSubTrs();
        activeTr.dataset.show = "";
        activeTr.classList.remove("active");
      }
    }
        
    const isTrsShown = !!tr.dataset.show;
    if (isTrsShown) {
      removeSubTrs();
      tr.dataset.show = "";
      tr.classList.remove("active");
    } else {
      
      tr.dataset.show = "1";
      tr.classList.add("active");
      if (expenseId) {
        console.log(expenseId);
        if (expense_info[expenseId]) {
          showInfo(expenseId, expense_info[expenseId]);
        } else {
          loadAndShowInfo(expenseId);
        }      
        
      }
    }
   }); 
  }
});

function addNewContractors() {
  console.log("!!addNewContractors!!");
  const modal = document.getElementById("modal_newContractors");
  const checkboxes = modal.querySelectorAll("input.ch_box_select_contractor:checked");
  console.log(checkboxes);

  const data = Array.from(checkboxes).map(item => { 
    const tr = item.closest("tr");
    const index = tr.dataset.index;
    console.log(`#id_short_${index}`, tr.querySelector(`#id_short_${index}`));
    return {
      full_name: tr.getElementsByTagName("td")[2].innerHTML, 
      short_name: tr.querySelector(`#id_short_${index}`).value,
      expense_type: tr.querySelector(`#id_select_${index}`).value,
    }
  })
  const post_data = {
    data
  }
  url_path = base_url + '/funds/save_new_contractors';
  $.ajax({
    url: url_path,
    data: post_data,
    method: 'POST',
    success: function (result) {
      //location.href = base_url + "/analytics";
      console.log(result);
    },
    fail: function(result) {
      console.error(result);
      alert("Error while status update");
    }
  });

  
}

function createNewContractorsTable(newContractors) {
  console.log("!!createNewContractorsTable!!");
  const modal = document.getElementById("modal_newContractors");
  const table = modal.querySelector("#newContractorsTable");
  const tbody = table.querySelector("tbody");
  tbody.innerHTML = "";
  const choose_all = table.querySelector("#choose_all");
  choose_all.checked = false;
  newContractors.forEach((contractor, index) => {
    const tr = create_contractors_row(contractor, index);
    tbody.insertAdjacentElement("beforeend", tr);
  });
}

function create_contractors_row(data, index) {
  const row = document.createElement('tr');
  row.dataset.index = ++index;
  
  const td_num = create_td('num', index);
  const td_checkbox = create_td_checkbox(`id_checkbox_${index}`, "ch_box_select_contractor");
  const td_title = create_td('text', data);
  const td_short = create_td_field(`id_short_${index}`);
  const td_expense = create_td_select(`id_select_${index}`, EXPENSE_TYPES, "expense_type");

  row.insertAdjacentElement('beforeend', td_num);
  row.insertAdjacentElement('beforeend', td_checkbox);
  row.insertAdjacentElement('beforeend', td_title);
  row.insertAdjacentElement('beforeend', td_short);
  row.insertAdjacentElement('beforeend', td_expense);

  return row;
}

function create_td_checkbox(id, className) {
  const td = document.createElement("td");
  const checkbox = document.createElement("input");
  checkbox.type = "checkbox";
  checkbox.id = id;
  if (className) checkbox.classList.add(className);
  //checkbox.value = false;
  td.insertAdjacentElement("beforeend", checkbox);

  return td;
}

function create_td_field(id, className) {
  const td = document.createElement("td");
  const input = document.createElement("input");
  input.type = "text";
  input.id = id;
  if (className) input.classList.add(className);
  input.value = "";
  td.insertAdjacentElement("beforeend", input);

  return td;
}

function create_td_select(id, data, key, className) {
  const td = document.createElement("td");
  const select = document.createElement("select");
  select.id = id;
  if (className) select.classList.add(className);
  const empty_option = document.createElement("option");
  empty_option.value = "-";
  empty_option.textContent = "";
  select.insertAdjacentElement("beforeend", empty_option);
  data.forEach(item => {
    const option = document.createElement("option");
    option.value = item.id;
    option.textContent = item[key];

    select.insertAdjacentElement("beforeend", option);
  });
  td.insertAdjacentElement("beforeend", select);

  return td;
}


function create_report() {
  console.log('create_report!!!!');
  const data = {
    date_start: document.getElementById('reportDate_start').value,
    date_end: document.getElementById('reportDate_end').value,
  };
  
  const url = base_url + '/funds/create_expense_report';
  $.ajax({
    url: url,
    data: data,
    method: 'POST',
    success: function (result) {
      
      const data = JSON.parse(result);
      console.log(data);
      create_report_table(data.info);
      create_report_footer(data.total_sum);
    },
  });
}

function create_report_table(data) {
  const table = document.querySelector("#analytic_table tbody");
  table.innerHTML = "";
  let rows = data.map((current, index) => {
    let row = create_row(current, index);
    table.insertAdjacentElement("beforeend", row);
    return row;
  });
}

function create_row(data, index) {
  const row = document.createElement('tr');
  row.dataset.cat_id = data.expense_type;
  row.dataset.show = "";

  const td_num = create_td('num', ++index);
  const td_author = create_td('text', "");
  const td_comments = create_td('text', "");
  const td_contractor = create_td('text', "");
  const td_date = create_td('date', "");
  const td_expense_type = create_td('text', data.expense_type_name);
  const td_number = create_td('text', "");
  const td_operation_type = create_td('text', "");
  const td_sum = create_td('money', data.sum);

  row.insertAdjacentElement('beforeend', td_num);
  row.insertAdjacentElement('beforeend', td_expense_type);
  row.insertAdjacentElement('beforeend', td_date);
  row.insertAdjacentElement('beforeend', td_number);
  row.insertAdjacentElement('beforeend', td_operation_type);
  row.insertAdjacentElement('beforeend', td_sum);
  row.insertAdjacentElement('beforeend', td_contractor);
  row.insertAdjacentElement('beforeend', td_author);
  row.insertAdjacentElement('beforeend', td_comments);

  return row;
}

function create_td(type, value, colspan) {
   
  switch (type) {
    case 'text':
      return create_text_td(value, colspan);
      break;
    case 'money':
      return create_money_td(value, colspan);
      break;
    case 'date':
      return create_date_td(value, colspan);
      break;
    default:
      return create_num_td(value, colspan);
  }
}

function create_text_td(value, colspan) {
  const td = document.createElement('td');
  td.classList.add("td_text");
  td.classList.add("td_long_text");
  td.textContent = value;

  if (colspan && parseInt(colspan) > 0) td.setAttribute("colspan", colspan);

  return td;
}

function create_money_td(value, colspan) {
  const td = document.createElement('td');
  
  td.textContent = numberWithSpaces(value);
  if (colspan && parseInt(colspan) > 0) td.setAttribute("colspan", colspan);

  return td;
}

function create_date_td(value, colspan) {
  const td = document.createElement('td');
  
  td.textContent = value;
  if (colspan && parseInt(colspan) > 0) td.setAttribute("colspan", colspan);

  return td;
}

function create_num_td(value, colspan) {
  const td = document.createElement('td');
  
  td.textContent = value;
  if (colspan && parseInt(colspan) > 0) td.setAttribute("colspan", colspan);

  return td;
}

function initDatePickers() {
  const d = new Date();

  const minYear = d.getFullYear() - 1;
  const maxYear = d.getFullYear();

  $('#reportDate_start').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: false,
    dateFormat: 'd.m.yy',
  });

  $('#reportDate_end').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: false,
    dateFormat: 'd.m.yy',
  });
}

function create_report_footer(data) {
  console.log(data);
  const table = document.querySelector("#analytic_table tfoot");
  table.innerHTML = "";
  const footer = create_footer_row(data[0]);

  table.insertAdjacentElement("beforeend", footer);
}

function create_footer_row(data) {
  const row = document.createElement('tr');

  const td_num = create_td('num', "");
  const td_expense_type = create_td('text', "ИТОГО: ", 4);
  td_expense_type.classList.add("td-text-align-right");
  td_expense_type.classList.add("td-bold");
  const td_number = create_td('text', "", 3);
  const td_sum = create_td('money', data.total);
  td_sum.classList.add("td-bold");

  row.insertAdjacentElement('beforeend', td_num);
  row.insertAdjacentElement('beforeend', td_expense_type);
  row.insertAdjacentElement('beforeend', td_sum);
  row.insertAdjacentElement('beforeend', td_number);

  return row;
}

function loadAndShowInfo(expenseId) {
  const data = {
    expenseId: expenseId,
    date_start: document.getElementById('reportDate_start').value,
    date_end: document.getElementById('reportDate_end').value,
  };
  
  const url = base_url + '/funds/load_expense_info';
  $.ajax({
    url: url,
    data: data,
    method: 'POST',
    success: function (result) {      
      const data = JSON.parse(result);
      console.log(data);
      expense_info[expenseId] = data;
      showInfo(expenseId, expense_info[expenseId]);
    },
  });
}

function showInfo(expenseId, info) {
  console.log(`[data-cat_id="${expenseId}"]`);
  const tr = document.querySelector(`[data-cat_id="${expenseId}"]`);
  if (tr) {
    const trs = create_info_table(info, tr);
  }
}

function removeSubTrs() {
  const sub_trs = document.querySelectorAll("#analytic_table .sub_trs");
  sub_trs.forEach(sub => sub.remove());
}

function create_info_table(info, tr) {
  removeSubTrs();
  
  let index = info.length - 1;

  info.map(current => {
    const subtr = create_info_tr(current, index--);
    
    tr.insertAdjacentElement('afterend', subtr);
  }); 
}

function create_info_tr(data, index) {
  const row = document.createElement('tr');
  row.classList.add("sub_trs");

  //console.log(data.date, typeof data.date);
  const date = new Date(data.date).toLocaleDateString('ru-RU');
  //console.log(dd, typeof dd);

  const td_num = create_td('num', ++index);
  const td_author = create_td('text', data.author);
  const td_comments = create_td('text', data.comments);
  const td_contractor = create_td('text', data.contractor);
  const td_date = create_td('date', date );
  const td_expense_type = create_td('text', data.expense_type);
  const td_number = create_td('text', data.number);
  const td_operation_type = create_td('text', data.operation_type);
  const td_sum = create_td('money', data.sum);

  row.insertAdjacentElement('beforeend', td_num);
  row.insertAdjacentElement('beforeend', td_expense_type);
  row.insertAdjacentElement('beforeend', td_date);
  row.insertAdjacentElement('beforeend', td_number);
  row.insertAdjacentElement('beforeend', td_operation_type);
  row.insertAdjacentElement('beforeend', td_sum);
  row.insertAdjacentElement('beforeend', td_contractor);
  row.insertAdjacentElement('beforeend', td_author);
  row.insertAdjacentElement('beforeend', td_comments);

  return row;
}