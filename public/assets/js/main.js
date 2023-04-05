$(document).ready(function() {
  $('#account_').DataTable();
  // =============================================================================
  // событие: выбор статьи расхода/прихода
  // =============================================================================
  $('#item_name').on('change', function(){
    //console.log($('option:selected', this).attr('need_agreement'));
    let itemType = $('option:selected', this).attr('item_type');
    let need_agreement = ($('option:selected', this).attr('need_agreement')==='true');
    let need_employee = ($('option:selected', this).attr('need_employee')==='true');
    let need_goods = ($('option:selected', this).attr('need_goods')==='true');

    let companyID = $('#company_id').val();

    //если нужно указать договор
    if(need_agreement){
      $('#agreement').removeAttr('disabled');
      $('#employee').prop('disabled', true);

      url_path = base_url+"/dashboard/get_agreements";
      var data = {
          company_id: companyID,
          item_type: itemType,
      };
      $.ajax({
          url: url_path,
          data: data,
          method: "GET",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function(result){
            //console.log(result);
            let select_str = "<option selected>Выберите</option>";
            $.each( result, function( key, value ) {
              let agreementNum = value.agreement_num;
              let agreementDate = value.agreement_date;
              let agreementName = "Договор №"+ agreementNum + "от" + agreementDate;
              select_str += "<option value='"+value.id+"' agreement_sum='"+value.agreement_sum+"'  agreement_type = '"+value.type+"'>"+agreementName+"</option>";
            });
            $('#agreement').html(select_str);
            $('#agreement').change();
          }
        });
    }

    //если нужно укзаать сотрудника
    if(need_employee){
      $('#employee').removeAttr('disabled');
      $('#agreement').prop('disabled', true);

      //get agreements items
      url_path = base_url+"/dashboard/get_employees";
      var data = {
          company_id: companyID
      };
      $.ajax({
          url: url_path,
          data: data,
          method: "GET",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function(result){
            console.log(result);
            let select_str = "<option selected>Выберите</option>";
            $.each( result, function( key, value ) {
              let emp_id = value.id;
              let name = value.name+" "+value.surname;
              select_str += "<option value='"+emp_id+"'>"+name+"</option>";
            });
            $('#employee').html(select_str);
            $('#employee').change();
          }
        });
    }

    //если нужно укзаать товар
    if(need_goods){
      $('#goods').removeAttr('disabled');
      $('#employee').prop('disabled', true);
      // $('#agreement').prop('disabled', true);

      //get agreements items
      url_path = base_url+"/dashboard/get_goods";
      var data = {
          company_id: companyID
      };
      $.ajax({
          url: url_path,
          //data: data,
          method: "GET",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function(result){
            console.log(result);
            let select_str = "<option selected>Выберите</option>";
            $.each( result, function( key, value ) {
              console.log(key + " = " + value);
              let good_id = value.id;
              let code = (value.code) ? value.code + " | " : "" ;
              let name = code + value.name;
              select_str += "<option value='"+good_id+"'>"+name+"</option>";
            });
            $('#goods').html(select_str);
            $('#goods').change();
          }
        });
    }
  });

  // =============================================================================
  // событие: выбор договора для статьи расхода/прихода
  // =============================================================================
  $('#agreement').on('change', function(){
    var agreement_type = $('option:selected', this).attr('agreement_type');
    console.log("agreement_type ==== "+agreement_type);
    $('#agreement_type').val(agreement_type);
  });

  // =============================================================================
  // событие: удаление статьи прихода/расхода
  // =============================================================================
  $('a[name="delete_item"]').on('click', function(){
    event.preventDefault();

    var record_id = $(this).attr('record_id');
    var deleteModal = new bootstrap.Modal(document.getElementById('modal_deleteItem'));
    console.log('record_id'+record_id);
    var modalTitle = document.getElementById('modal_deleteItemLabel')

    var item_type = $(this).attr('item_type');

    var title = (item_type == 'receipt') ? "Статья прихода" : "Статья расхода";
    modalTitle.textContent =  title;
    $('#delete_item_type').val(item_type);
    $('#delete_record_id').val(record_id);

    deleteModal.show();
  });
  // =============================================================================
  // событие: редактирование суммы статьи прихода/расхода
  // =============================================================================
  $('a[name="edit_item"]').on('click', function(){
    event.preventDefault();
    var record_id = $(this).attr('record_id');
    var editModal = new bootstrap.Modal(document.getElementById('modal_editItem'));
    var modalTitle = document.getElementById('modal_editItemLabel');//editModal.querySelector('.modal-title');

    var item_type = $(this).attr('item_type');
    //console.log("item_type = "+item_type);
    var title = (item_type == 'receipt') ? "Статья прихода" : "Статья расхода";
    modalTitle.textContent =  title;
    $('#record_id').val(record_id);

    $('#modal_editItem_Name').text($('#item_name_'+record_id).val());
    $('#edit_itemName').val($('#item_name_'+record_id).val());

    $('#modal_editItem_Agreement').text($('#agreement_name_'+record_id).val());
    $('#edit_itemAgreement').val($('#agreement_name_'+record_id).val());

    $('#modal_editItem_Employee').text($('#emp_name_'+record_id).val());
    $('#edit_itemEmployee').val($('#emp_name_'+record_id).val());

    $('#modal_editItem_Description').text($('#agreement_descr_'+record_id).val());
    $('#edit_itemDescription').val($('#agreement_descr_'+record_id).val());

    $('#modal_editItem_Sum').text($('#sum_'+record_id).val());
    $('#old_value').val($('#sum_'+record_id).val());
    $('#edit_company_id').val($('#company_id_'+record_id).val());
    $('#edit_item_type').val(item_type);
    //console.log("item_name == "+$('#item_name_'+record_id).val());
    editModal.show();

  });
  // =============================================================================
  // событие: загрузка страницы проверка валидации при сохранении статьи расхода/прихода
  // =============================================================================
  if ($('#show_item_modal').length){
    var show = parseInt($('#show_item_modal').val());
    if( show == 1 ){
      var myModal = new bootstrap.Modal(document.getElementById('modal_addItem'), {keyboard: false});
      myModal.show();
    }
  }
  // =============================================================================
  // событие: загрузка страницы проверка валидации при редактировании суммы статьи расхода/прихода
  // =============================================================================
  if ($('#show_item_modal_edit').length){
    var show = parseInt($('#show_item_modal_edit').val());
    if( show == 1 ){
      var myModal = new bootstrap.Modal(document.getElementById('modal_editItem'), {keyboard: false});
      myModal.show();
    }
  }
  // =============================================================================
  // событие: ввод суммы статьи прихода.расхода
  // =============================================================================
  function addCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return parts.join(".");
  }
  $('#sum').keyup(function(){
    var x = $('#sum').val().replace(/ /g, '');
    $('#sum').val(addCommas(x));
  });
});

// =============================================================================
// модальное окно - CRUD для статьи расхода/прихода
// =============================================================================
  var modal_addItem = document.getElementById('modal_addItem')
  modal_addItem.addEventListener('show.bs.modal', function (event)
  {
    // Button that triggered the modal
    var button = event.relatedTarget;
    // Extract info from data-bs-* attributes
    var itemType = button.getAttribute('data-bs-whatever');
    var companyID = button.getAttribute('company_id');
    var accountID = button.getAttribute('account_id');
    var accountName = button.getAttribute('account_name');

    var modalTitle = modal_addItem.querySelector('.modal-title');
    var modalSubTitle = modal_addItem.querySelector('.modal-subtitle');

    var title = (itemType == '@receipt') ? "Статья прихода" : "Статья расхода";
    var modalType = (itemType == '@receipt') ? "receipt" : "expense";

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = dd + '.' + mm + '.' + yyyy;

    $('#company_id').val(companyID);
    $('#company_account').val(accountID);
    $('#item_type').val(modalType);

    modalTitle.textContent =  title;
    modalSubTitle .textContent = today + " | " + accountName ;

    if (modalType == "receipt"){
      //get receipts items
      url_path = base_url+"/dashboard/get_receipt_items";
      $.ajax({
          url: url_path,
          method: "GET",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function(result){

            let select_str = "<option selected>Выберите</option>";
            $.each( result, function( key, value ) {
              let need_agreement = (value.need_agreement == 1) ? "true" :  "false";
              let need_employee = (value.need_employee == 1) ? "true" :  "false";
              let need_goods = (value.need_goods == 1) ? "true" :  "false";
              //console.log(need_goods);
              select_str += "<option value='"+value.id+"' item_type='receipt' need_agreement = '"+need_agreement+"' need_employee = '"+need_employee+"' need_goods = '"+need_goods+"'>"+value.name+"</option>";
            });
            $('#item_name').html(select_str);
          }
        });
    }else if(modalType == "expense"){
      //get expense items
      url_path = base_url+"/dashboard/get_all_expense_items";
      $.ajax({
          url: url_path,
          method: "GET",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success: function(result){
            //console.log(result);
            let select_str = "<option selected>Выберите</option>";
            $.each( result, function( key, value ) {
              let need_agreement = (value.need_agreement == 1) ? "true" :  "false";//((typeof value.need_agreement !== 'undefined') || (value.need_agreement == null)) ? 'false' : ''
              let need_employee = (value.need_employee == 1) ? "true" :  "false";
              select_str += "<option value='"+value.id+"' item_type='expense' need_agreement = '"+need_agreement+"' need_employee = '"+need_employee+"'>"+value.name+"</option>";
            });
            $('#item_name').html(select_str);
          }
        });
    }
  });
  // =============================================================================
  // перезагрузка страницы при закрытии модальных окон
  // =============================================================================
  // при закрытии модального окна перегружаем страницу - новая запис
  modal_addItem.addEventListener('hide.bs.modal', function (event) {
    window.location.href = base_url+"/dashboard";
  })
  // при закрытии модального окна перегружаем страницу - редактирование записи
  var modal_editItem = document.getElementById('modal_editItem')
  modal_editItem.addEventListener('hide.bs.modal', function (event) {
    window.location.href = base_url+"/dashboard";
  })
  // при закрытии модального окна перегружаем страницу - редактирование записи
  var modal_deleteItem = document.getElementById('modal_deleteItem')
  modal_deleteItem.addEventListener('hide.bs.modal', function (event) {
    window.location.href = base_url+"/dashboard";
  })

  // =============================================================================
  // открыть историю удаления
  // =============================================================================
  $('a[name="history_deletion"]').on('click', function(){
    var record_id = $(this).attr('record_id');
    var item_type = $(this).attr('item_type');

    url_path = base_url+"/dashboard/get_history_deletion";
    var data = {
        item_type: item_type,
        record_id: record_id
    };
    $.ajax({
        url: url_path,
        data: data,
        method: "GET",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result){
          console.log(result);

        }
      });

  });
