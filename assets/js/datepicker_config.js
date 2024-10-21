$(document).ready(function () {

  $( "#reportDate_start, #reportDate_end, #showDate, #attendanceDate_start" ).datepicker({
    firstDay:1,
    buttonImage: "images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Выберите дату",
    altField: "#actualDate",
    dateFormat: "dd.mm.yy",
    dayNamesMin: [ "ВС", "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ" ],
    monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
    onSelect: function(date, datepicker) {
                      if (datepicker.id == "reportDate_start") {
                          d = new Date();
                          $('#reportDate_end').datepicker("setDate", d)
                              .datepicker("enable").datepicker("option", "minDate", date)
                      }

                      if (!$('#reportDate_end').datepicker("isDisabled")) {
                          var startDate = $('#reportDate_end').datepicker("getDate");
                          var endDate = $('#reportDate_end').datepicker("getDate");
                          var diff = endDate.getDate() - startDate.getDate();
                      }

                      if(datepicker.id == "attendanceDate_start"){
                        d = new Date();
                        var week_ = getWeekStartAndEnd($('#attendanceDate_start').datepicker("getDate"));
                        week_start = week_.startOfWeek;
                        week_end = week_.endOfWeek;
                        $('#period_start').val(week_start);
                        $('#period_end').val(week_end);
                        $('#period_label').text(" выбран период с "+week_start+" по "+week_end+"");
                      }
              }
  }).filter("#reportDate_end").datepicker("disable");

  d = new Date();
  $( "#showDate" ).datepicker( "setDate", d);
  $( "#attendanceDate_start" ).datepicker( "setDate", d);

});
