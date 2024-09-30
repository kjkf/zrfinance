// =============================================================================
// подлючение дэйтпикера для стр. отчетов
// =============================================================================
$( function() {
  // $( "#reportDate_start, #reportDate_end, #showDate" ).datepicker({
  //   buttonImage: "images/calendar.gif",
  //   buttonImageOnly: true,
  //   buttonText: "Выберите дату",
  //   altField: "#actualDate",
  //   dateFormat: "dd.mm.yy",
  //   dayNamesMin: [ "ПН", "ВТ", "СР", "ЧТ", "ПТ", "СБ", "ВС" ],
  //   monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
  //   onSelect: function(date, datepicker) {
  //                     if (datepicker.id == "reportDate_start") {
  //                         d = new Date();
  //                         $('#reportDate_end').datepicker("setDate", d)
  //                             .datepicker("enable").datepicker("option", "minDate", date)
  //                     }
  //
  //                     if (!$('#reportDate_end').datepicker("isDisabled")) {
  //                         var startDate = $('#reportDate_end').datepicker("getDate");
  //                         var endDate = $('#reportDate_end').datepicker("getDate");
  //                         var diff = endDate.getDate() - startDate.getDate();
  //                         //$('#dayCount').text(diff).parent().show();
  //                     }
  //             }
  // }).filter("#reportDate_end").datepicker("disable");

  $( "#reportDate_start" ).datepicker( "setDate", -30);
  d = new Date();
  $( "#reportDate_end" ).datepicker( "setDate", d);
  // $( "#showDate" ).datepicker( "setDate", d);

  function commify(n) {
      var parts = n.toString().split(".");
      const numberPart = parts[0];
      const decimalPart = parts[1];
      const thousands = /\B(?=(\d{3})+(?!\d))/g;
      return numberPart.replace(thousands, " ") + (decimalPart ? "." + decimalPart : "");
  }

  $("#getReport").on("click", function(){
    //console.log('report is generating');

    let date_start = $("#reportDate_start").val();
    let date_end = $("#reportDate_end").val();

    let url = base_url+"/report/get_report";

    var data = {
        date_start: date_start,
        date_end: date_end,
    };

    $.ajax({
        url: url,
        data: data,
        method: "GET",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(result){
          //------------------------------------------------------
          //main report
          let rows = result['main'];
          $("#report_main > tbody").empty();
          let tableRows = "";
          let ind = 0;
          let prev_name;
          let comp_name = "";
          let top_border = "";
          let dot_border = "style = 'border-bottom: 1px dotted grey;' "
          $.each( rows, function( key, value ) {
            if(value.full_name == prev_name){
              comp_name = "";
              top_border = "";
            }else{
              comp_name = value.full_name;
              top_border = "style = 'border-top: 1px solid grey'"
            }

            tableRows += "<tr "+top_border+">";
            ++ind;
            tableRows += "<td>"+ind+"</td>";
            tableRows += "<td>"+comp_name+"</td>";
            tableRows += "<td "+dot_border+">"+value.name+"</td>";
            let receipt_sum = (value.receipt_sum == null) ? ' - ' : value.receipt_sum;
            let expense_sum = (value.expense_sum == null) ? ' - ' : value.expense_sum;
            tableRows += "<td "+dot_border+">"+receipt_sum+"</td>";
            tableRows += "<td "+dot_border+">"+expense_sum +"</td>";
            let comment = "";
            // console.log("dif:" + (parseFloat(expense_sum.replace(/\s/g,'')) > parseFloat(receipt_sum.replace(/\s/g,''))));
            comment = ((parseFloat(expense_sum.replace(/\s/g,'')) > parseFloat(receipt_sum.replace(/\s/g,''))))? '<i class="fa-solid fa-triangle-exclamation"></i> расходы большие': "" ;

            tableRows += "<td "+dot_border+">"+comment +"</td>";
            tableRows += "</tr>";

            prev_name = value.full_name;
          })
          $("#report_main > tbody").append(tableRows);
          $("#report_resultMain").toggle();
          $("#report_resultMain_title").toggle();
          //------------------------------------------------------
          // byGoods rreport
          let goods_rows = result['report_byGoods'];
          console.table(goods_rows );
          $("#report_byGoods > tbody").empty();
          let tableRows1 = "";
          let ind1 = 0;
          let prev_name1 = "";
          let comp_name1 = "";
          let top_border1 = "";
          //let dot_border = "style = 'border-bottom: 1px dotted grey;' "
          $.each( goods_rows, function( key, value ) {
            if(value.company_name == prev_name1){
              comp_name1 = "";
              top_border1 = "";
            }else{
              comp_name1= value.company_name;
              top_border1 = "style = 'border-top: 1px solid grey'"
            }

            tableRows1 += "<tr "+top_border1+">";
            ++ind1;
            tableRows1 += "<td>"+ind1+"</td>";
            tableRows1 += "<td>"+comp_name1+"</td>";
            tableRows1 += "<td "+dot_border+">"+value.account_name+"</td>";
            tableRows1 += "<td "+dot_border+">"+value.good_name +"</td>";
            let receipt_sum = (value.receipt_sum == null) ? ' - ' : value.receipt_sum;
            tableRows1 += "<td "+dot_border+">"+receipt_sum+"</td>";

            tableRows1 += "</tr>";
            prev_name1 = value.company_name;
          })
          console.log(tableRows1);
          $("#report_byGoods > tbody").append(tableRows1);
          $("#report_resultByGoods").toggle();
          $("#report_resultByGoods_title").toggle();

        }
      });
  });

});
