$(document).ready(function () {
  d = new Date();
  var week_ = getWeekStartAndEnd(d);
  week_start = week_.startOfWeek;
  week_end = week_.endOfWeek;
  $('#period_label').text(" выбран период с "+week_start+" по "+week_end+"");
  $('#period_start').val(week_start);
  $('#period_end').val(week_end);
});

  $('#attendanceDate_start').on('change', function () {
    // console.log("date is picked");
  });

  //$('#depart_name').on('change', function(){
    $('#getattendance').on('click', function(){
      //console.log('report is generating');


      let date_start = $("#period_start").val();
      let date_end = $("#period_end").val();
      let depart_id = $('option:selected', '#depart_name').attr('value');
      console.log("depart_id = "+depart_id);

      let url = base_url+"/attendance/getempattendance";

      var data = {
          date_start: date_start,
          depart_id: depart_id
      };

      $.ajax({
          url: url,
          data: data,
          method: "GET",
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          success:  function (result) {
            let rows = result;
            let ind = 0;
            let tableRows = "";

            let w_days = 5;
            let w_hours = 8*w_days;
            $.each( rows, function( key, value ) {
              console.log('key = '+key+"; val = "+value.name+"; |||  ");
              tableRows += "<tr >";
              ++ind;
              tableRows += "<td>"+ind+"</td>";
              tableRows += "<td>"+value.surname+" "+value.name+"</td>";
              tableRows += "<td>"+value.position_name+"</td>";
              tableRows += "<td> <input type='text' id='hours_"+value.id+"' emp_id='"+value.id+"' value='"+w_hours+"' ></td>";
              tableRows += "<td> <button class = 'btn btn-primary' type='button' name='button' id='save_attendance_"+value.id+"' emp_id='"+value.id+"'> Сохранить </button> </td>";
              tableRows += "</tr>";
            });
            $("#attendance_main > tbody").append(tableRows);
          }
          
        });











  })


  function getWeekStartAndEnd(date) {
    const dayOfWeek = date.getDay(); // Получаем день недели (0 - воскресенье, 6 - суббота)
    const diffToMonday = (dayOfWeek === 0 ? -6 : 1) - dayOfWeek; // Смещение до понедельника
    const startOfWeek = new Date(date);
    startOfWeek.setDate(date.getDate() + diffToMonday); // Начало недели - понедельник
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6); // Конец недели - воскресенье

    return {
        startOfWeek: formatDateToDDMMYYYY(startOfWeek),
        endOfWeek: formatDateToDDMMYYYY(endOfWeek)
    };
  }

  function formatDateToDDMMYYYY(date) {
    let day = date.getDate();
    let month = date.getMonth() + 1; // Месяцы в JavaScript считаются с 0, поэтому добавляем 1
    let year = date.getFullYear();

    // Добавляем ведущий ноль для дней и месяцев, если нужно
    day = (day < 10) ? '0' + day : day;
    month = (month < 10) ? '0' + month : month;

    return day + '.' + month + '.' + year;
}
// });
