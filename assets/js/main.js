$(document).ready(function () {
  rememberTab();
  restoreTabs();

  $('#account_').DataTable();
  
  if (!localStorage.getItem("navtab")) {
    localStorage.setItem("navtab", "tab_1");
  }
  // =============================================================================
  // событие: выбор статьи расхода/прихода
  // =============================================================================
  $('#item_name').on('change', function () {
    resetAddModalFields();
    let itemType = $('option:selected', this).attr('item_type');
    let itemName = $('option:selected', this).attr('value');
    let need_agreement =
      $('option:selected', this).attr('need_agreement') === 'true';
    let need_employee =
      $('option:selected', this).attr('need_employee') === 'true';
    let need_contractor = $('option:selected', this).attr('need_contractor');
    let need_goods = $('option:selected', this).attr('need_goods') === 'true';
    let companyID = $('#company_id').val();

    //если нужно указать договор
    if (need_agreement) {
      $('#contractor').removeAttr('disabled');
      $('#block_contractor').toggle();
      $('#employee').prop('disabled', true);
      url_path = base_url + '/dashboard/get_contractors';
      var data = {
        company_id: companyID,
        item_type: itemType,
      };
      $.ajax({
        url: url_path,
        data: data,
        method: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (result) {
          // console.log(result);
          let select_str = '<option selected>Выберите</option>';
          $.each(result, function (key, value) {
            let contractor_id = value.contractor_id;
            let contractor_name = value.contractor_name;

            select_str +=
              "<option value='" + contractor_id + "'" + "need_agreement='1'" +
              "'>" +
              contractor_name + 
              '</option>';
          });
          $('#contractor').html(select_str);
          //$('#contractor').change();
        },
      });
    }

    //если нужно укзаать сотрудника
    if (need_employee) {
      $('#employee').removeAttr('disabled');
      $('#block_employee').toggle();
      $('#agreement').prop('disabled', true);
      url_path = base_url + '/dashboard/get_employees';
      var data = {
        company_id: companyID,
      };
      $.ajax({
        url: url_path,
        data: data,
        method: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (result) {
          // console.log(result);
          let select_str = '<option selected>Выберите</option>';
          $.each(result, function (key, value) {
            let emp_id = value.id;
            let name = value.name + ' ' + value.surname;
            select_str +=
              "<option value='" + emp_id + "'>" + name + '</option>';
          });
          $('#employee').html(select_str);
          $('#employee').change();

          $('#block_official').toggle();
          $('#block_description').toggle();
          $('#block_sum').toggle();
          $('#block_document').toggle();
        },
      });
    }
    //если нужно указать товар
    if (need_goods) {
      $('#goods').removeAttr('disabled');
      $('#block_goods').toggle();
      $('#employee').prop('disabled', true);
      // $('#agreement').prop('disabled', true);

      //get agreements items
      url_path = base_url + '/dashboard/get_goods';
      var data = {
        company_id: companyID,
      };
      $.ajax({
        url: url_path,
        //data: data,
        method: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (result) {
          // console.log(result);
          let select_str = '<option selected>Выберите</option>';
          $.each(result, function (key, value) {
            //console.log(key + " = " + value);
            let good_id = value.id;
            let code = value.code ? value.code + ' | ' : '';
            let name = code + value.name;
            select_str +=
              "<option value='" + good_id + "'>" + name + '</option>';
          });
          $('#goods').html(select_str);
          $('#goods').change();
        },
      });
    }

    //если нужно укзаать контрагента
    if (need_contractor && !need_agreement) {
      $('#contractor').removeAttr('disabled');
      $('#block_contractor').toggle();
      $('#agreement').prop('disabled', true);
      url_path = base_url + '/dashboard/get_contractors_by_category';
      var data = {
        category_id: need_contractor,
      };
      $.ajax({
        url: url_path,
        data: data,
        method: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (result) {
          let select_str = '<option selected>Выберите</option>';
          $.each(result, function (key, value) {
            let contr_id = value.id;
            let name = value.contractor_name;
            select_str +=
              "<option value='" + contr_id + "'>" + name + '</option>';
          });
          $('#contractor').html(select_str);
          //$('#contractor').change();

          //$('#block_official').toggle();
          //$('#block_description').toggle();
          //$('#block_sum').toggle();
          //$('#block_document').toggle();
        },
      });
    }

    //если выбран пункт - Прочие
    if (
      (itemType == 'receipt' && parseInt(itemName) == 3) ||
      (itemType == 'expense' && parseInt(itemName) == 6)
    ) {
      $('#block_official').toggle();
      $('#block_description').toggle();
      $('#block_sum').toggle();
      $('#block_document').toggle();
    }
  });
  // =============================================================================
  // событие: выбор контрагента для статьи расхода/прихода
  // =============================================================================
  $('#contractor').on('change', function () {
    //let itemType = $('option:selected', this).attr('item_type');
    let companyID = $('#company_id').val();
    let contractor = $('option:selected', this).attr('value');
    let need_agreement = $('option:selected', this).attr('need_agreement');

    // console.log("contractor = "+contractor);
    if (contractor && contractor.length > 0 && need_agreement) {
      url_path = base_url + '/dashboard/get_agreements';
      $('#agreement').removeAttr('disabled');
      $('#block_agreement').toggle();
      var data = {
        company_id: companyID,
        item_type: '',
        contractor: contractor,
      };
      $.ajax({
        url: url_path,
        data: data,
        method: 'GET',
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function (result) {
          // console.log(result);
          let select_str = '<option selected>Выберите</option>';
          $.each(result, function (key, value) {
            let agreementNum = value.agreement_num;
            let agreementDate = value.agreement_date;
            let short_name = value.short_name;
            let agreementName =
              'Договор №' + agreementNum + ' от ' + agreementDate;

            select_str +=
              "<option value='" +
              value.id +
              "' agreement_sum='" +
              value.agreement_sum +
              "'  agreement_type = '" +
              value.type +
              "'>" +
              agreementName +
              '(' +
              short_name +
              ')' +
              '</option>';
          });
          $('#agreement').html(select_str);
          $('#agreement').change();
        },
      });
    } else {
      $('#block_official').toggle();
      $('#block_description').toggle();
      $('#block_sum').toggle();
      $('#block_document').toggle();
    }
  });

  // =============================================================================
  // событие: выбор договора для статьи расхода/прихода
  // =============================================================================
  $('#agreement').on('change', function () {
    var agreement_type = $('option:selected', this).attr('agreement_type');
    // console.log("agreement_type ==== "+agreement_type);
    $('#agreement_type').val(agreement_type);
    if ($('option:selected', this).attr('value')) {
      $('#block_official').toggle();
      $('#block_description').toggle();
      $('#block_sum').toggle();
      $('#block_document').toggle();
    }
  });

  // =============================================================================
  // событие: выбор товара расхода/прихода
  // =============================================================================
  $('#goods').on('change', function () {
    if ($('option:selected', this).attr('value')) {
      $('#block_official').toggle();
      $('#block_description').toggle();
      $('#block_sum').toggle();
      $('#block_document').toggle();
    }
  });
  // =============================================================================
  // событие: ввод описания, счетчик символов
  // =============================================================================
  let key_count = 0;
  const key_max = 20;
  $('#description').on('keyup', function () {
    var descr = $(this).val();
    // key_count = key_count + descr.length;
    key_count = key_max - descr.length;
    // console.log('length = '+descr.length);
    if (key_count >= 0) {
      $('#description_key_count').text('Осталось символов:' + key_count);
    }
  });
  $('#user_doc').on('click', function () {
    console.log('clicked');
  });
  // =============================================================================
  // событие: удаление статьи прихода/расхода
  // =============================================================================
  $('a[name="delete_item"]').on('click', function () {
    event.preventDefault();

    var record_id = $(this).attr('record_id');
    var deleteModal = new bootstrap.Modal(
      document.getElementById('modal_deleteItem')
    );
    // console.log('record_id'+record_id);
    var modalTitle = document.getElementById('modal_deleteItemLabel');

    var item_type = $(this).attr('item_type');

    var title = item_type == 'receipt' ? 'Статья прихода' : 'Статья расхода';
    modalTitle.textContent = title;
    $('#delete_item_type').val(item_type);
    $('#delete_record_id').val(record_id);

    deleteModal.show();
  });
  // =============================================================================
  // событие: редактирование суммы статьи прихода/расхода
  // =============================================================================
  $('a[name="edit_item"]').on('click', function () {
    event.preventDefault();
    var record_id = $(this).attr('record_id');
    var editModal = new bootstrap.Modal(
      document.getElementById('modal_editItem')
    );
    var modalTitle = document.getElementById('modal_editItemLabel'); //editModal.querySelector('.modal-title');

    var item_type = $(this).attr('item_type');
    //console.log("item_type = "+item_type);
    var title = item_type == 'receipt' ? 'Статья прихода' : 'Статья расхода';
    modalTitle.textContent = title;
    $('#record_id').val(record_id);

    $('#modal_editItem_Name').text($('#item_name_' + record_id).val());
    $('#edit_itemName').val($('#item_name_' + record_id).val());

    $('#modal_editItem_Agreement').text(
      $('#agreement_name_' + record_id).val()
    );
    $('#edit_itemAgreement').val($('#agreement_name_' + record_id).val());

    $('#modal_editItem_Employee').text($('#emp_name_' + record_id).val());
    $('#edit_itemEmployee').val($('#emp_name_' + record_id).val());
    //console.log('qqqqq', $('#emp_name_' + record_id).val());

    $('#modal_editItem_Description').text($('#descr_' + record_id).val());
    $('#edit_itemDescription').val($('#descr_' + record_id).val());

    var old_val = $('#sum_' + record_id).val();
    old_val = addCommas(old_val);
    $('#modal_editItem_Sum').text(old_val);

    $('#old_value').val($('#sum_' + record_id).val());
    $('#new_value').val(old_val);
    $('#edit_company_id').val($('#company_id_' + record_id).val());
    $('#edit_item_type').val(item_type);
    $('#status').val($('#status_' + record_id).val());
    //console.log("item_name == "+$('#item_name_'+record_id).val());
    editModal.show();
  });

  // =============================================================================
  // событие: загрузка страницы проверка валидации при сохранении статьи расхода/прихода
  // =============================================================================
  if ($('#show_item_modal').length) {
    var show = parseInt($('#show_item_modal').val());
    if (show == 1) {
      var myModal = new bootstrap.Modal(
        document.getElementById('modal_addItem'),
        { keyboard: false }
      );
      myModal.show();
    }
  }
  // =============================================================================
  // событие: загрузка страницы проверка валидации при редактировании суммы статьи расхода/прихода
  // =============================================================================
  if ($('#show_item_modal_edit').length) {
    var show = parseInt($('#show_item_modal_edit').val());
    if (show == 1) {
      var myModal = new bootstrap.Modal(
        document.getElementById('modal_editItem'),
        { keyboard: false }
      );
      myModal.show();
    }
  }
  // =============================================================================
  // событие: ввод суммы статьи прихода.расхода
  // =============================================================================
  function addCommas(x) {
    // console.log('tutu');
    var parts = x.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    return parts.join('.');
  }

  $('#sum').keyup(function () {
    var x = $('#sum').val().replace(/ /g, '');
    $('#sum').val(addCommas(x));
  });

  $('#new_value').keyup(function () {
    var x = $('#new_value').val().replace(/ /g, '');
    $('#new_value').val(addCommas(x));
  });
});

// =============================================================================
// модальное окно - CRUD для статьи расхода/прихода
// =============================================================================
var modal_addItem = document.getElementById('modal_addItem');
modal_addItem.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget;
  // Extract info from data-bs-* attributes
  var itemType = button.getAttribute('data-bs-whatever');
  var companyID = button.getAttribute('company_id');
  var accountID = button.getAttribute('account_id');
  var accountName = button.getAttribute('account_name');

  var modalTitle = modal_addItem.querySelector('.modal-title');
  var modalSubTitle = modal_addItem.querySelector('.modal-subtitle');

  var title = itemType == '@receipt' ? 'Статья прихода' : 'Статья расхода';
  var modalType = itemType == '@receipt' ? 'receipt' : 'expense';

  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();

  today = dd + '.' + mm + '.' + yyyy;

  $('#company_id').val(companyID);
  $('#company_account').val(accountID);
  $('#item_type').val(modalType);

  modalTitle.textContent = title;
  modalSubTitle.textContent = today + ' | ' + accountName;

  if (modalType == 'receipt') {
    //get receipts items
    url_path = base_url + '/dashboard/get_receipt_items';
    $.ajax({
      url: url_path,
      method: 'GET',
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
      success: function (result) {
        // console.log(result);
        let select_str = '<option selected>Выберите</option>';
        $.each(result, function (key, value) {
          let need_agreement = value.need_agreement == 1 ? 'true' : 'false'; //((typeof value.need_agreement !== 'undefined') || (value.need_agreement == null)) ? 'false' : ''
          let need_employee = value.need_employee == 1 ? 'true' : 'false';
          let need_goods = value.need_goods == 1 ? 'true' : 'false';
          //console.log(need_goods);
          select_str +=
            "<option value='" +
            value.id +
            "' item_type='receipt' need_agreement = '" +
            need_agreement +
            "' need_employee = '" +
            need_employee +
            "' need_goods = '" +
            need_goods +
            "'>" +
            value.name +
            '</option>';
        });
        $('#item_name').html(select_str);
      },
    });
  } else if (modalType == 'expense') {
    //get expense items
    url_path = base_url + '/dashboard/get_all_expense_items';
    $.ajax({
      url: url_path,
      method: 'GET',
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
      success: function (result) {
        //console.log(result);
        let select_str = '<option selected>Выберите</option>';
        $.each(result, function (key, value) {
          let need_agreement = value.need_agreement == 1 ? 'true' : 'false'; //((typeof value.need_agreement !== 'undefined') || (value.need_agreement == null)) ? 'false' : ''
          let need_employee = value.need_employee == 1 ? 'true' : 'false';
          let need_contractor = value.need_contractor ? value.need_contractor : 0;
          let title = value.descr ? value.descr : '';
          select_str +=
            "<option value='" +
            value.id +
            "' item_type='expense' need_agreement = '" +
            need_agreement +
            "' need_employee = '" +
            need_employee +
            "' need_contractor = '" +
            need_contractor + 
            "' title='" + title +
            "'>" +
            value.name + 
            '</option>';
        });
        $('#item_name').html(select_str);
      },
    });
  }
});
// =============================================================================
// перезагрузка страницы при закрытии модальных окон
// =============================================================================
// при закрытии модального окна перегружаем страницу - новая запис
modal_addItem.addEventListener('hide.bs.modal', function (event) {
  localStorage.setItem("savetab", 1);
  window.location.href = base_url + '/dashboard';
});
// при закрытии модального окна перегружаем страницу - редактирование записи
var modal_editItem = document.getElementById('modal_editItem');
modal_editItem.addEventListener('hide.bs.modal', function (event) {
  window.location.href = base_url + '/dashboard';
});
// при закрытии модального окна перегружаем страницу - редактирование записи
var modal_deleteItem = document.getElementById('modal_deleteItem');
modal_deleteItem.addEventListener('hide.bs.modal', function (event) {
  window.location.href = base_url + '/dashboard';
});

// =============================================================================
// открыть историю удаления
// =============================================================================
$('a[name="history_deletion"]').on('click', function () {
  var record_id = $(this).attr('record_id');
  var item_type = $(this).attr('item_type');
  //alert("history deletion");
  url_path = base_url + '/dashboard/get_history';
  var data = {
    item_type: item_type,
    record_id: record_id,
    history_type: 'deletion',
  };
  $.ajax({
    url: url_path,
    data: data,
    method: 'GET',
    contentType: 'application/json; charset=utf-8',
    dataType: 'json',
    success: function (result) {
      // console.log(result);
      // console.log(result[0].status_reason);
      var modal_history = new bootstrap.Modal(
        document.getElementById('modal_history'),
        { keyboard: false }
      );
      var agreement = '';
      var agreement_date = '';
      if (result[0].forzr_agr_num) {
        agreement = result[0].forzr_agr_num;
        agreement_date = result[0].forzr_agr_date;
      } else if (result[0].fromzr_agr_num) {
        agreement = result[0].fromzr_agr_num;
        agreement_date = result[0].fromzr_agr_date;
      }
      var history =
        'Наименование статьи: ' +
        result[0].item_name +
        '<br>' +
        'Договор: №' +
        agreement +
        ' от ' +
        agreement_date +
        '<br>' +
        'Описание: ' +
        result[0].description +
        '<br>' +
        'Сумма: ' +
        result[0].sum;
      $('#history_item').html(history);
      $('#history_reason').text(result[0].status_reason);
      $('#history_edition_name').html(
        "<i class='fa-solid fa-trash-can'></i> Удаление "
      );
      $('#history_author').text(
        result[0].emp_surname + ' ' + result[0].emp_name
      );
      $('#aprove_item_type').val(item_type);
      $('#aprove_record_id').val(record_id);

      modal_history.show();
    },
  });
});
// =============================================================================
// открыть историю редактирования
// =============================================================================
$('a[name="history_edition"]').on('click', function () {
  var record_id = $(this).attr('record_id');
  var item_type = $(this).attr('item_type');
  //alert("history deletion");
  url_path = base_url + '/dashboard/get_history';
  var data = {
    item_type: item_type,
    record_id: record_id,
    history_type: 'edition',
  };
  $.ajax({
    url: url_path,
    data: data,
    method: 'GET',
    contentType: 'application/json; charset=utf-8',
    dataType: 'json',
    success: function (result) {
      // console.log(result);
      // console.log(result[0].status_reason);
      var modal_history = new bootstrap.Modal(
        document.getElementById('modal_history'),
        { keyboard: false }
      );
      var agreement = '';
      var agreement_date = '';
      if (result[0].forzr_agr_num) {
        agreement = result[0].forzr_agr_num;
        agreement_date = result[0].forzr_agr_date;
      } else if (result[0].fromzr_agr_num) {
        agreement = result[0].fromzr_agr_num;
        agreement_date = result[0].fromzr_agr_date;
      }
      $('#modal_history_form').attr(
        'action',
        base_url + '/dashboard/approve_edition'
      );
      var history =
        'Наименование статьи: ' +
        result[0].item_name +
        '<br>' +
        'Договор: №' +
        agreement +
        ' от ' +
        agreement_date +
        '<br>' +
        'Описание: ' +
        result[0].description +
        '<br>' +
        'Сумма: ' +
        result[0].sum +
        '<br>' +
        'Документ: ' +
        result[0].document +
        '<br><hr>' +
        'Новая сумма: ' +
        result[0].edition_new_value +
        '<br>' +
        "Новый документ: <a href='" +
        base_url +
        '/uploads/' +
        result[0].edition_doc +
        "'>" +
        result[0].edition_doc +
        '</a>';
      $('#history_item').html(history);
      $('#history_reason').text(result[0].edition_reason);
      $('#history_edition_name').html(
        "<i class='fa-solid fa-pen-to-square'></i> Редактирование "
      );
      $('#history_author').text(
        result[0].emp_surname + ' ' + result[0].emp_name
      );
      $('#aprove_item_type').val(item_type);
      $('#aprove_record_id').val(record_id);
      $('#aprove_new_value').val(result[0].edition_new_value);
      $('#aprove_old_value').val(result[0].edition_old_value);
      $('#aprove_document').val(result[0].edition_doc);

      modal_history.show();
    },
  });
});

function openCurrentTab() {
  const navTab = localStorage.getItem('navtab') || "tab_1";
  
  if (navTab) {
    const tabs = document.getElementById('myTab');
    const tabsContent = document.getElementById('myTabContent');
    if (tabs) {
      const activeTabHead = tabs.querySelector('.nav-link.active');
      let tabContentSelector = activeTabHead.getAttribute('data-bs-target');
      const activeTabContent = tabsContent.querySelector(tabContentSelector);

      activeTabHead.classList.remove('active');
      activeTabContent.classList.remove('active');
      activeTabContent.classList.remove('show');

      const tabHead = tabs.querySelector('#' + navTab);
      tabContentSelector = tabHead.getAttribute('data-bs-target');
      const tabContent = tabsContent.querySelector(tabContentSelector);

      tabHead.classList.add('active');
      tabContent.classList.add('active');
      tabContent.classList.add('show');
    }
  }
}

function openCurrentAccordionTab() {
  const accoTab = localStorage.getItem('accoTab');
  const navTab = localStorage.getItem('navtab') || "tab_1";
  
  if (accoTab) {
    const tabHead = document.querySelector("[aria-controls='" + accoTab + "']");
    const tabsContent = document.getElementById(accoTab);
    const tab = tabHead.closest(".tab-pane");
    const currentTab = tab.getAttribute("aria-labelledby");
    
    if (tab && currentTab === navTab){
      if (tabHead && tabsContent) {
        tabHead.classList.remove('collapsed');
        //tabsContent.classList.remove('collapse');
        tabsContent.classList.add('show');
        //localStorage.setItem('accoTab', '');
      }
    }
  }
}

function restoreTabs() {
  const isTabSaved = localStorage.getItem("savetab");
  //console.log(isTabSaved);
  if (parseInt(isTabSaved)) {
    openCurrentTab();
    openCurrentAccordionTab();
    localStorage.setItem("savetab", 0);
  }
}

function rememberTab() {
  document.addEventListener('click', (e) => {
    const target = e.target;

    if (target.classList.contains('nav-link')) {
      localStorage.setItem('navtab', target.id);
    }

    if (target.classList.contains('accordion-button')) {
      localStorage.setItem('accoTab', target.getAttribute('aria-controls'));
    }
  });
}

function resetAddModalFields() {
  $('#contractor').prop('disabled', true);
  $('#contractor').html("");
  $('#block_contractor').css("display", "none");

  $('#employee').prop('disabled', true);
  $('#employee').html("");
  $('#block_employee').css("display", "none");

  $('#agreement').prop('disabled', true);
  $('#agreement').html("");
  $('#block_agreement').css("display", "none");

  $('#block_official').css("display", "none")
  $('#block_description').css("display", "none")
  $('#block_sum').css("display", "none")
  $('#block_document').css("display", "none");

  $('#goods').prop('disabled', true);
  $('#goods').html("");
  $('#block_goods').css("display", "none");

  $("#description").val("");
  $("#sum").val("");
  $("#description").val("");

  const modal = document.getElementById("modal_addItem");
  const docInput = modal.querySelector("#user_doc");
  //docInput.files = [];
}