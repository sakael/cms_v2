$(document).ready(function () {
  "use strict";
  // Default Datatable
  $("#all_attributes_table").DataTable({
    ajax: {
      url: "/product-info/attributes/data",
      data: { attribute_group_id: id },
      complete: function (data, textStatus, jqXHR) {},
    },
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    pageLength: 30,
    aaSorting: [[1, "asc"]],
    columns: [
      {
        data: "id",
      }, //"visible":false
      {
        data: "name",
      },
      {
        data: "attribute_group_name",
      },
      {
        data: null,
        orderable: false,
        className: "table-action text-center",
        render: function (data, type, row) {
          return '<a href="#" class="action-icon remove_type text-danger" data-id="' + row.id + '"><i class="mdi mdi-delete-off-outline"></i></a>';
        },
      },
    ],
  });

  $("#all_attributes_table").on("click", ".remove_type", function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    axios
      .delete("/product-info/attributes/delete/attribute", {
        data: {
          id: id,
        },
      })
      .then(function (response) {
        if (response.data.status == "true") {
          toastr.success(response.data.msg);
          $("#all_attributes_table").DataTable().ajax.reload();
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      });
  });
});
