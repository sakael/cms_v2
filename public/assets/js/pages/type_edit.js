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
            "data": null,
            "orderable": false,
            className: "text-center",
            "render": function(data,type,row){
                if(data==0){
                    return '<input type="checkbox" id="type_'+row.product_id+'_'+type_id+'_'+brand_id+'"  data-switch="bool" onclick="add_child_func(this.id,' + row.product_id +','+type_id+','+brand_id+')"/><label for="type_'+row.product_id+'_'+type_id+'_'+brand_id+'" data-on-label="Ja" data-off-label="nee"></label>';
                }else{
                    return '<input type="checkbox" id="type_'+row.product_id+'_'+type_id+'_'+brand_id+'" checked data-switch="bool" onclick="add_child_func(this.id,' + row.product_id +','+type_id+','+brand_id+')"/><label for="type_'+row.product_id+'_'+type_id+'_'+brand_id+'" data-on-label="Ja" data-off-label="nee"></label>';
                }
              
            }
        }
      ],
      order: [[0, "asc"]],
      drawCallback: function () {
        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
      },
    });
  });

function add_child_func(thisId,productID,type_id,brand_id){
    if (document.getElementById(thisId).checked) 
    {
        axios.post("/product/type/add", {
            'brandID': brand_id,
            'product_id': productID,
            'typeID' :type_id,
            '_METHOD': 'POST'
            })
            .then(function (response) {
                if (response.data.status == 'true') {
                    toastr.success(response.data.msg);
                    $('#main_generate_tab_table').DataTable().ajax.reload();
                } else {
                    console.log(response);
                    toastr.warning(response.data.msg);
                }
            });
    } else {
        axios.delete("/product/type/delete",  { data:{
            'product_id': productID,
            'id' :type_id,
            'type' :'type',
            }})
            .then(function (response) {
                if (response.data.status == 'true') {
                    toastr.success(response.data.msg);
                    $('#main_generate_tab_table').DataTable().ajax.reload();
                } else {
                    console.log(response);
                    toastr.warning(response.data.msg);
                }
            });
    }
    
}