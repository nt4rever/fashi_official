$(document).ready(function () {
  var start_csv, end_csv;
  var url = $('input[name=my_url').val();
  $('#reservationdate').datetimepicker({
    format: 'YYYY-MM-DD'
  });
  $('#reservationdate1').datetimepicker({
    format: 'YYYY-MM-DD'
  });
  $('#daterange-btn').daterangepicker(
    {
      ranges: {
        // 'Today': [moment(), moment()],
        // 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 ngày gần nhất': [moment().subtract(6, 'days'), moment()],
        '30 ngày gần nhất': [moment().subtract(29, 'days'), moment()],
        'Tháng này': [moment().startOf('month'), moment().endOf('month')],
        'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(6, 'days'),
      endDate: moment()
    },
    function (start, end) {
      $('#reportrange').html('(' + start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD') + ')')
      var start = start.format('YYYY-MM-DD');
      var end = end.format('YYYY-MM-DD');
      if (start == "" || end == "") {
        swal('vui lòng chọn khoảng khoảng thời gian!');
        return false;
      } else {
        start_csv = start;
        end_csv = end;
      }
      var _token = $('input[name=_token]').val();
      $.ajax({
        url: url + '/ad/filter-by-date',
        method: 'POST',
        dataType: 'JSON',
        data: { start: start, end: end, _token: _token },
        success: function (data) {
          var lable = [];
          var sales = [];
          var order = [];
          var quantity = [];
          $.each(data, function (key, value) {
            lable.push(value.preiod);
            sales.push(value.sales);
            order.push(value.order);
            quantity.push(value.quantity);
          });
          myChart.data.labels = lable;
          myChart.data.datasets[0].data = sales;
          myChart.data.datasets[1].data = order;
          myChart.data.datasets[2].data = quantity;
          myChart.update()
        },
        error: function (data) {
          console.log(data);
        }
      });
    }
  );

  /*** Gradient ***/
  var ctx = document.getElementById('chart_area').getContext('2d');
  // var gradientFill = ctx.createLinearGradient(0, 0, 0, 500);
  // gradientFill.addColorStop(0, "rgba(128, 182, 244, 0.6)");
  // gradientFill.addColorStop(1, "rgba(244, 144, 128, 0.6)");
  const gradient = ctx.createLinearGradient(0, 0, 0, 500);
  gradient.addColorStop(0, 'rgba(250,174,50,1)');   
  gradient.addColorStop(1, 'rgba(250,174,50,0)');

  var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
  gradientStroke.addColorStop(0, "#80b6f4");
  gradientStroke.addColorStop(1, "#f49080");

  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [],
      datasets: [{
        label: 'doanh thu',
        data: [],
        backgroundColor: gradient,
        borderColor: gradientStroke,
        pointBorderColor: gradientStroke,
        pointBackgroundColor: gradientStroke,
        pointHoverBackgroundColor: gradientStroke,
        pointHoverBorderColor: gradientStroke,
        pointRadius: 3,
        // backgroundColor: "transparent",
        // borderColor: "#f6d365",
        borderWidth: 2
      },
      {
        label: 'đơn hàng',
        data: [],
        backgroundColor: "transparent",
        borderColor: "#15ca20",
        borderWidth: 2,
        borderDash: [4, 4],
        hidden: true,
      }
        ,
      {
        label: 'sản phẩm',
        data: [],
        backgroundColor: "transparent",
        borderColor: "#ff6c23",
        borderWidth: 2,
        hidden: true,

        // borderDash: [4, 4]
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            callback: function (value, index, values) {
              if (parseInt(value) >= 1000) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              } else {
                return value;
              }
            }
          }
        }]
      }
      ,
      title: {
        display: true,
        text: 'Biểu đồ thống kê doanh thu và số lượng đơn hàng'
      },
      tooltips: {
        intersect: false,
        callbacks: {
          label: function (tooltipItem, data) {
            var label = data.datasets[tooltipItem.datasetIndex].label || '';
            if (label) {
              label += ': ';
            }
            label += tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            return label;
          }
        }
      },
    }
  });




  function load_chart() {
    var _token = $('input[name=_token]').val();
    $.ajax({
      url: url + '/ad/dashboard-chart',
      method: 'POST',
      dataType: 'JSON',
      data: { _token: _token },
      success: function (data) {
        var lable = [];
        var sales = [];
        var order = [];
        var quantity = [];
        $.each(data, function (key, value) {
          lable.push(value.preiod);
          sales.push(value.sales);
          order.push(value.order);
          quantity.push(value.quantity);
        });
        myChart.data.labels = lable;
        myChart.data.datasets[0].data = sales;
        myChart.data.datasets[1].data = order;
        myChart.data.datasets[2].data = quantity;
        myChart.update()
      },
      error: function (data) {
        console.log(data);
      }
    });
  };
  load_chart();
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $("#example2").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false,
    //   "responsive": true,
    // });
  });

  $('#export-csv').on('click', function () {
    if (start_csv == null || end_csv == null) {
      swal('Bạn chưa nhập dữ liệu!');
      return false;
    }
    window.open(url + '/ad/statistic-csv?start=' + start_csv + '&end=' + end_csv);
  });
});

