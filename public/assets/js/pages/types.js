$(document).ready(function () {
  "use strict";
  // Default Datatable
  $("#types-datatable").DataTable({
    processing: true,
    serverSide: true,
    ajax: "/product-info/types/data",
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    pageLength: 30,
    columns: [
      { orderable: true, data: "id" },
      {
        orderable: true,
        data: "name",
        render: function (data, type, row) {
          return (
            '<img src="' +
            row.photo +
            '" alt="contact-img" title="contact-img" class="rounded mr-3" height="48" />' +
            '<p class="m-0 d-inline-block align-middle font-16">' +
            '<a href="/product-info/types/type/' +
            row.id +
            '" class="text-body"> ' +
            row.name +
            " </a>" +
            "</p>"
          );
        },
      },
      { orderable: true, data: "brand_name" },
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
          return data == 1 ? '<i class="uil uil-check fs-26 text-success"></i>' : '<span class="badge badge-danger"></span>';
        },
      },
      {
        orderable: true,
        data: "active_feed",
        render: function (data, type, row) {
          return data == 1 ? '<i class="uil uil-check fs-26 text-success"></i>' : '<span class="badge badge-danger"></span>';
        },
      },
      { orderable: true, data: "updated_at" },
      { orderable: true, data: "created_at" },
      {
        data: null,
        orderable: false,
        className: "table-action",
        render: function (data, type, row) {
          return (
            '<a href="' +
            SITE +
            row.brand_name +
            "/" +
            row.slug +
            '" class="action-icon" target="_blank"> <i class="mdi mdi-eye"></i></a>' +
            '<a href="/product-info/types/type/' +
            row.id +
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
