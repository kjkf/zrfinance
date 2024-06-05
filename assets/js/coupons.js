document.addEventListener("DOMContentLoaded", ev => {
  datepickerInit();
  resetIssuingType();
  setIssuingType();
  blockCouponsIfNoMore();

  issuingGas();
  addGas();
  
  $('#modal_issuingCoupons').on('hide.bs.modal', function () {
    resetIssuingType();
  })

  if ($("#tableReciept") && $("#tableReciept").length > 0) {
    $("#tableReciept").DataTable({
      info: false,
      ordering: false,
      paging: false,
      language: {
        infoEmpty: 'Нет записей!',
        infoFiltered: '(filtered from _MAX_ total records)',
        search: 'Искать:'
    }
    });
  }

  if ($("#tableIssuing") && $("#tableIssuing").length > 0) {
    $("#tableIssuing").DataTable({
      info: false,
      ordering: false,
      paging: false,
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

function addGas() {
  const addGasBtn = document.getElementById("addGas");
  if (addGasBtn) {
    addGasBtn.addEventListener("click", e => {
      const receipt_quantity = document.getElementById("receipt_quantity");
      if (!receipt_quantity.value || receipt_quantity.value <= 0) {
        alert("Укажите сколько бензина поступило");
        receipt_quantity.focus();
        return;
      } 
      const form = document.getElementById("add_coupons"); 
      form.submit();
    });
  }
}

function issuingGas() {
  const issuingGasBtn = document.getElementById("issuingGas");
  console.log("issuingGas", issuingGasBtn);
  if (issuingGasBtn) {
    issuingGasBtn.addEventListener("click", e => {
      const isValidated = validateIssuingFields();
      console.log("11111111", isValidated);
      if (isValidated) {
        const form = document.getElementById("issuing_coupons");
        form.submit();
      } else {
        //alert("ERRORS");
      }
    });
  }
}

function validateField(fieldId, msg) {
  const field = document.getElementById(fieldId);
  console.log(fieldId, field, field.value);
  
  if (!field) return false;
  if (!field.value || field.value < 0) {
    alert(msg);
    field.focus();
    return false;
  }

  return true;
}

function validateIssuingFields() {
  return validateField("issuing_base", "Укажите основания выдачи талона") && isIssuingType() && validateMoneyAndCoupons();
}

function validateMoneyAndCoupons() {
  console.log(document.querySelector('input[name="issuing_type"]:checked').value);
  if (document.querySelector('input[name="issuing_type"]:checked') && document.querySelector('input[name="issuing_type"]:checked').value === "money") { 
    return validateField("issuing_money", "Укажите выданную сумму");
  } else if (document.querySelector('input[name="issuing_type"]:checked') && document.querySelector('input[name="issuing_type"]:checked').value === "coupons") { 
    return validateField("issuing_quantity", "Укажите количество выданных литров");
  } else {
    return isIssuingType();
  }
}

function datepickerInit() {
  const date = new Date();
  const receipt_date = document.getElementById('receipt_date');
  const issuing_date = document.getElementById('issuing_date');
  
  if (receipt_date) {
    receipt_date.value = formatDateTime(date);
  }

  if (issuing_date) {
    issuing_date.value = formatDateTime(date);
  }
  
}

function formatDateTime(date) {
  const time =
    (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) +
    ':' +
    (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());

  return date.toLocaleDateString('ru-RU') + ' ' + time;
}

function resetIssuingType() {
  const radio = document.getElementsByName("issuing_type");
  if (!radio) return false;
  radio.forEach(item => {
    if(item.value === "coupons") {
      item.checked = true;
    } else {
      item.checked = false;
    }    
  });
    
  document.querySelector(".issuing_type-coupons").style.display = "block";
  document.querySelector(".issuing_type-money").style.display = "none";  
}

function setIssuingType() {
  const radio = document.querySelector(".issuing_type");
  if (!radio) return;

  radio.addEventListener("click", e => {
    if (document.querySelector('input[name="issuing_type"]:checked')) {
      const checkedVal = document.querySelector('input[name="issuing_type"]:checked').value;
      const notCheckedVal = document.querySelector('input[name="issuing_type"]:not(:checked)').value;
        
      document.querySelector(`.issuing_type-${checkedVal}`).style.display = "block";
      document.querySelector(`.issuing_type-${notCheckedVal}`).style.display = "none";
    }
  });
}

function isIssuingType() {
  if (!document.querySelector('input[name="issuing_type"]:checked') || !document.querySelector('input[name="issuing_type"]:checked').value) {
    alert("Выберите тип");
    document.querySelector('input[name="issuing_type"]:not(:checked)').focus();
    return false;
  }
  return true;
}

function blockCouponsIfNoMore() {
  const couponsRemainder = document.getElementById("remainder");
  
  console.log(!couponsRemainder.value || parseFloat(couponsRemainder.value) <= 2500);
  if (!couponsRemainder.value || parseFloat(couponsRemainder.value) <= 0) {
    const radio = document.getElementsByName("issuing_type");
    if (!radio) return false;
    radio.forEach(item => {
      console.log(item);
      if(item.value === "coupons") {
        item.checked = false;
        item.setAttribute("disabled", "disabled");
      } else {
        item.checked = true;
      }    
    });
    
    document.querySelector(".issuing_type-coupons").style.display = "none";
    document.querySelector(".issuing_type-money").style.display = "block";  
    
  }
}
