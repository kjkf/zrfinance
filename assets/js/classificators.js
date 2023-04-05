$(document).ready(function() {
//календарь
$( "#agreement_date" ).datepicker({
  buttonImage: "images/calendar.gif",
  buttonImageOnly: true,
  buttonText: "Выберите дату",
  altField: "#actualDate",
  dateFormat: "dd.mm.yy",
  dayNamesMin: [ "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ", "ВС" ],
  monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
  // onSelect: function(date, datepicker) {
  //                   if (datepicker.id == "reportDate_start") {
  //                       d = new Date();
  //                       $('#reportDate_end').datepicker("setDate", d)
  //                           .datepicker("enable").datepicker("option", "minDate", date)
  //                   }
  //
  //                   if (!$('#reportDate_end').datepicker("isDisabled")) {
  //                       var startDate = $('#reportDate_end').datepicker("getDate");
  //                       var endDate = $('#reportDate_end').datepicker("getDate");
  //                       var diff = endDate.getDate() - startDate.getDate();
  //                       //$('#dayCount').text(diff).parent().show();
  //                   }
  //           }
});


//ловим событие клика по переключателям с наименованиями компаний
$('.agreements-nav .btn').on('click', function(){
  let current_target = $(this).attr('data-bs-target');
  $(this).attr('class', 'btn btn-info')
  $(current_target).show();
  $(".agreements-nav .btn").each(function() {
    if(($(this).attr('data-bs-toggle') == 'collapse') && ($(this).attr('data-bs-target') != current_target)){
      $(this).attr('class', 'btn btn-light');
      $($(this).attr('data-bs-target')).attr('class', "collapse");
      //console.log($(this).attr('data-bs-target')+ " - hidden");
    }
  });
});
//клик на кнопку - добавить договор
$('#add_agreement').on('click', function(){
  $('#form_add_agreement').toggle();
  $(this).toggle();
  const d = new Date();
  $( "#agreement_date" ).datepicker( "setDate", d);
});

});
