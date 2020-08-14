$(document).ready(function () {
  "use strict";
  // Default Datatable
  $("#brands-datatable").DataTable({
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    pageLength: 30,
    columns: [
      { orderable: true },
      { orderable: true },
      {
        orderable: true,
        data: "popular_list",
        render: function (data, type, row) {
          return data == 1 ? '<i class="uil uil-check fs-26 text-success "></i>' : '<span class="badge badge-danger"></span>';
        },
      },
      {
        orderable: true,
        data: "active_menu",
        render: function (data, type, row) {
          return data == 1 ? '<i class="uil uil-check fs-26 text-success "></i>' : '<span class="badge badge-danger"></span>';
        },
      },
      {
        orderable: true,
        data: "active_feed",
        render: function (data, type, row) {
          return data == 1 ? '<i class="uil uil-check fs-26 text-success "></i>' : '<span class="badge badge-danger"></span>';
        },
      },
      { orderable: true },
      { orderable: true },
      { orderable: false },
    ],
    order: [[0, "asc"]],
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  });
});
