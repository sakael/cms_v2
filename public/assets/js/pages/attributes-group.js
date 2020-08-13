$(document).ready(function () {
  "use strict";
  // Default Datatable
  $("#attributes-group-datatable").DataTable({
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    pageLength: 30,
    columns: [
      { orderable: true },
      { orderable: true },
      {
        orderable: true,
        data: "active",
        render: function (data, type, row) {
          return data == 1 ? '<i class="uil uil-check fs-26 text-success "></i>' : '<span class="badge badge-danger"></span>';
        },
      },
      { orderable: true },
      { orderable: false },
    ],
    order: [[0, "asc"]],
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  });
});
