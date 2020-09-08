$(document).ready(function () {
    "use strict";
    // Default Datatable
    $("#main_generate_tab_table").DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "/product-info/"+type_id+"/types/generate",
        data: {
          type_id: type_id,
        },
        complete: function (data, textStatus, jqXHR) {},
      },
      language: {
        url: "/assets/js/datatable-langauge.json",
      },
      pageLength: 30,
      columns: [
        { orderable: true, data: "product_id" },
        { orderable: true, data: "sku" },
        {orderable: true,data: "title",},
        {
            "data": "yes",
            "orderable": false,
            "render": function(data,type,row){
                var temp='<ul class="action-list">';
                if(data==0){
                    temp = temp.concat('<li><div class="btn btn-xs btn-danger" style="width:150px" onclick="add_child_func(' + row.product_id +','+type_id+')">Nee</div></li>');
                }else{
                    temp = temp.concat('<li><div class="btn btn-xs btn-success remove_type" onclick="removeType(' + row.product_id +')" style="width:150px">Ja</div></li>');
                }
                temp = temp.concat('</ul>');
                return temp;
            }
        }
      ],
      order: [[0, "asc"]],
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
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
  