$(document).ready(function () {
  "use strict";
  // Default Datatable
  var all_brand_types_table = $("#all_brand_types_table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "/product-info/brands/brand/types/data",
      data: {
        product_brand_id: product_brand_id,
      },
      complete: function (data, textStatus, jqXHR) {},
    },
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
        className: "table-action text-center",
        render: function (data, type, row) {
          return (
            '<a href="' +
            SITE +
            row.brand_name +
            "/" +
            row.slug +
            '" class="action-icon" target="_blank" title="Open" data-toggle="tooltip" data-placement="top" data-original-title="Open"> <i class="mdi mdi-eye"></i></a>' +
            '<a href="/product-info/types/type/' +
            row.id +
            '" class="action-icon " target="_blank" title="Bewerk" data-toggle="tooltip" data-placement="top" data-original-title="Bewerk"> <i class="mdi mdi-square-edit-outline"></i></a>' +
            '<a href="#" class="action-icon remove_type" data-id="'+row.id+'" title="Ontkoppelen" data-toggle="tooltip" data-placement="top" data-original-title="Ontkoppelen"> <i class="mdi mdi-link-variant-off"></i></a>'
          );
        },
      },
    ],
    order: [[0, "asc"]],
    drawCallback: function () {
      $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
    },
  });

  all_brand_types_table.on( 'draw', function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $("#all_brand_types_table").on("click", ".remove_type", function(e){
      e.preventDefault();
    var id=$(this).data("id");
    axios.put("/product-info/brands/type/remove",  {
        'id' :id,
    })
      .then(function (response) {
          if (response.data.status == 'true') {
              toastr.success(response.data.msg);
              $('#all_brand_types_table').DataTable().ajax.reload();
          } else {
              console.log(response);
              toastr.warning(response.data.msg);
          }
      })
  });
});
