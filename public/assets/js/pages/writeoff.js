$(document).ready(function () {
  $(".datepicker").datepicker({
    maxDate: 0,
    endDate: "0",
    format: "yyyy-mm-dd",
  });

  var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

  $("#date_to").datepicker("setDate", today);
  date.setMonth(date.getMonth() - 1);
  $("#date_from").datepicker("setDate", date);

  var all_write_off_table = $("#all_write_off_table").DataTable({
    ajax: {
      url: "/products/writeoff/data",
      data: function (d) {
        d.data_from = $("#date_from").val();
        d.data_to = $("#date_to").val();
      },
      complete: function (data, textStatus, jqXHR) {},
    },
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    pageLength: 100,
    columns: [
      { data: "sku" },
      {
        orderable: true,
        data: "title",
        render: function (data, type, row) {
          return (
            '<img src="' +
            row.image +
            '" alt="contact-img" title="contact-img" class="rounded mr-3" height="48" />' +
            '<p class="m-0 d-inline-block align-middle font-16">' +
            '<a href="' +
            row.product_url +
            '" class="text-body"> ' +
            row.title +
            " </a>" +
            "</p>"
          );
        },
      },
      { data: "aantal" },
      { data: "totaal" },
    ],
    aaSorting: [[1, "DESC"]],
    columnDefs: [
      { width: "250px", targets: 2 },
      { className: "text-center", targets: [2] },
    ]
  });
  $("#show_writeoff").click(function () {
    all_write_off_table.ajax.reload();
  });
});
