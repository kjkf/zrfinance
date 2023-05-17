document.addEventListener('DOMContentLoaded', (e) => {
  datepickerLocaleRu();

  initDatePickerForCreateFZP();

  const createFZPBtn = document.getElementById('create_fzp');
  if (createFZPBtn) {
    createFZPBtn.addEventListener('click', (e) => {
      if (!document.getElementById("fzpDate").value) {
        alert("Выберите месяц и год, для которых хотите создать ФЗП");
        return false;
      }
      createFZP();      
    });
  }
});

function createFZP() {
  const data = {
    fzpMonth : +document.getElementById("fzp_month").value + 1, //в php и mySQL месяц в дате начинается с 1, в js с 0
    fzpYear : document.getElementById("fzp_year").value,  
    fzpDate : new Date(document.getElementById("fzp_year").value, document.getElementById("fzp_month").value, 1),  
  };

  
  const url = base_url + "/salary/create_fzp_by_date";
  $.ajax({
    url: url,
    data: data,
    method: "POST",
    success: function(result) {
      console.log(result);
      let modal = new bootstrap.Modal(
        document.getElementById('modal_chooseDate')
      );
      modal.hide();
      const res = JSON.parse(result);
      if (res.type === 'exist') {
        if (confirm("За выбранный месяц существует ФЗП. Хотите открыть этот документ?")) {
          location.href = base_url + "/salary/fzp/" + res.fzp_id;
        }
      } else {
        location.href = base_url + "/salary/fzp/" + res.fzp_id;
      }
    }
  });
  
}

function initDatePickerForCreateFZP() {
  const d = new Date();

  const minYear = d.getFullYear()- 1;
  const maxYear = d.getFullYear();

  $('.date-picker').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: false,
    minDate: new Date(minYear, 0, 1),
    maxDate: new Date(maxYear, 11, 1),
    dateFormat: 'MM yy',
    onClose: function () {
      var iMonth = $('#ui-datepicker-div .ui-datepicker-month :selected').val();
      var iYear = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
      $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
      document.getElementById("fzp_month").value = iMonth;
      document.getElementById("fzp_year").value = iYear;
    },
    onChangeMonthYear: function () {
      var iMonth = $('#ui-datepicker-div .ui-datepicker-month :selected').val();
      var iYear = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
      $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
      document.getElementById("fzp_month").value = iMonth;
      document.getElementById("fzp_year").value = iYear;
    },
  });
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
