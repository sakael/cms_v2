{% extends "layouts/base.tpl" %}
{% block cssfiles %}
<!-- third party css -->
<link href="dist/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
<link href="dist/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
<!-- third party css end -->
{% endblock %}
{% block page_title %}{{page_title}}{% endblock %}
{% block content %}
<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box">
         <h4 class="page-title">{{page_title}}</h4>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="row mb-2">
               <div class="col-sm-4">
                  <a href="javascript:void(0);" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle mr-2"></i> Add Products</a>
               </div>
               <div class="col-sm-8">
                  <div class="text-sm-right">
                     <button type="button" class="btn btn-success mb-2 mr-1"><i class="mdi mdi-settings"></i></button>
                     <button type="button" class="btn btn-light mb-2 mr-1">Import</button>
                     <button type="button" class="btn btn-light mb-2">Export</button>
                  </div>
               </div>
               <!-- end col-->
            </div>

            <div class="table-responsive">
               <table class="table table-centered w-100 dt-responsive nowrap" id="products-datatable">
                  <thead class="thead-light">
                     <tr>
                        <th>Id</th>
                        <th class="all">SKU</th>
                        <th>Titel</th>
                        <th>Actief</th>
                        <th>Bol Actief</th>
                        <th>Laatst gewijzigd</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  {#
                  <tbody>
                  {% for product in products %}
                      <tr>
                        <td>
                           {{product.id}}
                        </td>
                        <td>
                        {{product.sku}}
                        </td>
                        <td>
                           <img src="{{IMAGE_PATH}}/{{getThumb(product.url,'cart')}}" alt="contact-img" title="contact-img" class="rounded mr-3" height="48" />
                           <p class="m-0 d-inline-block align-middle font-16">
                              <a href="apps-ecommerce-products-details.html" class="text-body">{{product.title}}</a>
                           </p>
                        </td>
                        <td>
                           {% if product.active == 1 %}
                              <span class="badge badge-success">Actief</span>
                           {% else %}
                              <span class="badge badge-danger">Deactief</span> 
                           {% endif %}
                        </td>
                        <td>
                           
                        </td>
                        <td>
                           {{product.updated_at}}
                        </td>
                        <td class="table-action">
                           <a href="{{SITE_URL}}/{{product.id}}" class="action-icon" target="_blank"> <i class="mdi mdi-eye"></i></a>
                           <a href="{{ path_for('ProductGet',{'id':product.id})}}" class="action-icon" target="_blank"> <i class="mdi mdi-square-edit-outline"></i></a>
                        </td>
                     </tr>
                  {% endfor %}
                  </tbody>
                  #}
               </table>
            </div>
         </div>
         <!-- end card-body-->
      </div>
      <!-- end card-->
   </div>
   <!-- end col -->
</div>
<!-- end row -->        
{% endblock %} 
{% block javascript %}
<!-- third party js -->
<script src="dist/assets/js/vendor/jquery.dataTables.min.js"></script>
<script src="dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
<script src="dist/assets/js/vendor/dataTables.responsive.min.js"></script>
<script src="dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>
<script src="dist/assets/js/vendor/dataTables.checkboxes.min.js"></script>
<script>
   $(document).ready(function() {
       "use strict";
       // Default Datatable
       $('#products-datatable').DataTable({
          "processing": true,
           "serverSide": true,
           "ajax": "{{ path_for('ProductsGetAll')}}",
           "language": {
               url : '/assets/js/datatable-langauge.json',
           },
           "pageLength": 30,
           "columns": [
               {'orderable': true,'data':'id'},
               {'orderable': true,'data':'sku'},
               {
                  'orderable': true,
                  'data':'title',
                  "render": function (data, type, row) {
                      return '<img src="' + row.image + '" alt="contact-img" title="contact-img" class="rounded mr-3" height="48" />'
                              + '<p class="m-0 d-inline-block align-middle font-16">'
                              + '<a href="apps-ecommerce-products-details.html" class="text-body"> ' + row.title + ' </a>'
                              + '</p>';
                  }
               },
               {
                  'orderable': true,
                  'data':'active',
                  "render": function (data, type, row) {
                      if(row.active == 1){
                        return '<span class="badge badge-success">Actief</span>';
                      }
                      else{
                        return '<span class="badge badge-danger">Deactief</span>';
                      }
                  }
               },
               {
                  'orderable': true,
                  'data':'bol',
                  "render": function (data, type, row) {
                      if(row.bol == 3){
                        return '<span class="badge badge-success">Actief</span>';
                      }
                      else{
                        return '<span class="badge badge-danger">Deactief</span>';
                      }
                  }
               },
               {'orderable': true,'data':'updated_at'},
               {
                "data": null,
                "orderable": false,
                "className" : "table-action",
                "render": function (data, type, row) {
                    return '<a href="" class="action-icon" target="_blank"> <i class="mdi mdi-eye"></i></a>'
                           + '<a href="' + row.product_url + '" class="action-icon" target="_blank"> <i class="mdi mdi-square-edit-outline"></i></a>'
                           + '';
                  }
               }
           ],
           "order": [[ 0, "asc" ]],
           "drawCallback": function () {
               $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
           }
       });
   } );
</script>
{% endblock %}