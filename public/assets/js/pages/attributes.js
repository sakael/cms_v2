$(document).ready(function () {
  "use strict";
  // Default Datatable
  $("#attributes-datatable").DataTable({
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    pageLength: 30,
    columns: [{ orderable: true }, { orderable: true }, { orderable: true }, { orderable: false }],
    order: [[0, "asc"]],
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  });
});
