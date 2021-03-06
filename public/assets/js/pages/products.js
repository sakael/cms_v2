$(document).ready(function () {
  "use strict";
  // Default Datatable
  $("#products-datatable").DataTable({
    processing: true,
    serverSide: true,
    ajax: "/products/data",
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    pageLength: 30,
    columns: [
      { orderable: true, data: "id" },
      { orderable: true, data: "sku" },
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
      {
        orderable: true,
        data: "active",
        render: function (data, type, row) {
          return row.active == 1 ? '<i class="uil uil-check fs-26 text-success "></i>' : '';
        },
      },
      {
        orderable: true,
        data: "bol",
        render: function (data, type, row) {
          return row.bol == 3 ? '<i class="uil uil-check fs-26 text-success"></i>' : '';
        },
      },
      { orderable: true, data: "updated_at" },
      {
        data: null,
        orderable: false,
        className: "table-action text-center",
        render: function (data, type, row) {
          return (
            '<a href="" class="action-icon" target="_blank"> <i class="mdi mdi-eye"></i></a>' +
            '<a href="' +
            row.product_url +
            '" class="action-icon" target="_blank"> <i class="mdi mdi-square-edit-outline"></i></a>' +
            ""
          );
        },
      },
    ],
    order: [[0, "asc"]],
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  });
});
