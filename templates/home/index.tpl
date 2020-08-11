{% extends "layouts/base.tpl" %}
{% block cssfiles %}{% endblock %}
{% block content %}
<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box">
         <h4 class="page-title">Dashboard</h4>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row row-eq-height mb-3 home-page-top-cards">
   <div class="col-xl-3 col-lg-3">
      <!-- Messages-->
      <div class="card mb-0">
         <div class="card-body">
            <h4 class="header-title mb-3">Totaaloverzicht</h4>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class="text-secondary" href="{{path_for('OrdersIndex')}}?orderTab=newOrders">Nieuwe orders</a>
                    <span class="badge badge-success badge-pill">{{(orders.newOrders | length)}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                     <a class="text-secondary" href="{{path_for('OrdersIndex')}}?orderTab=claimedOrders">Inpakken</a>
                    <span class="badge badge-primary badge-pill">{{(orders.claimedOrders | length)}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                     <a class="text-secondary" href="{{path_for('OrdersIndex')}}?orderTab=returnOrders">Openstaande credits</a>
                    <span class="badge badge-secondary badge-pill">{{(orders.returnOrders | length)}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                     <a class="text-secondary" href="#">Retour | Omruil</a>
                    <span class="badge badge-warning badge-pill">{{(orders.returnChange | length)}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                     <a class="text-secondary" href="#">Retour | Credit</a>
                    <span class="badge badge-danger badge-pill">{{(orders.returnCredite | length)}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                     <a class="text-secondary" href="{{path_for('OrdersIndex')}}?orderTab=waitSupplierOrders">Wacht op leverancier</a>
                    <span class="badge badge-info badge-pill">{{(orders.waitSupplierOrders | length)}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                     <a class="text-secondary" href="#">Wacht op betaling</a>
                    <span class="badge badge-info badge-pill">{{(orders.waitPaymentOrders | length)}}</span>
                </li>
            </ul>
         </div>
         <!-- end card-body-->
      </div>
      <!-- end card-->
   </div>
   <!-- end col -->
   <div class="col-xl-3 col-lg-3">
      <div class="card mb-0">
         <div class="card-body">
            <h4 class="header-title mb-2">Totaaloverzicht</h4>
            <table class="table table-hover table-centered mb-0 ">
              <thead>
                  <tr>
                      <th></th>
                      <th>nl</th>
                      <th>com</th>
                      <th>bol</th>
                      <th>LVB</th>
                  </tr>
              </thead>
              <tbody>
                  <tr >
                      <td class="mb-5">Afgelopen 10 dagen</td>
                      <td><span class="badge badge-dark">{{orders.tenDays | length}}</span></td>
                      <td></td>
                      <td></td>
                      <td></td>
                  </tr>
                  <tr>
                      <td>Vandaag</td>
                      <td><span class="badge badge-success">{{orders.nlToday | length}}</span></td>
                      <td><span class="badge badge-success">{{orders.comToday | length}}</span></td>
                      <td><span class="badge badge-success">0</span></td>
                      <td><span class="badge badge-success">0</span></td>
                  </tr>
                  <tr>
                      <td>Gisteren</td>
                      <td><span class="badge badge-primary">{{orders.nlYesterday | length}}</span></td>
                      <td><span class="badge badge-primary">{{orders.comYesterday | length}}</span></td>
                      <td><span class="badge badge-primary">0</td>
                      <td><span class="badge badge-primary">0</td>
                  </tr>
                  <tr>
                      <td>Eergisteren</td>
                      <td><span class="badge badge-info">{{orders.nlBeforeYesterday | length}}</span></td>
                      <td><span class="badge badge-info">{{orders.comBeforeYesterday | length}}</span></td>
                      <td><span class="badge badge-info">0</span></td>
                      <td><span class="badge badge-info">0</span></td>
                  </tr>
              </tbody>
          </table>
         </div>
         <!-- end card-body-->
      </div>
      <!-- end card-->
   </div>
   <!-- end col -->
   <div class="col-xl-6 col-lg-6 ">
      <div class="card mb-0">
         <div class="card-body">
            <h4 class="header-title mb-3">Bestellingen per maand</h4>
            <div id="high-performing-orders" class="apex-charts" data-colors="#727cf5,#e3eaef"></div>
         </div>
         <!-- end card-body-->
      </div>
      <!-- end card-->
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <ul class="nav nav-tabs nav-justified nav-bordered mb-3">
               <li class="nav-item">
                  <a href="#latest-50" data-toggle="tab" aria-expanded="true" class="nav-link active">
                  <i class="mdi mdi-home-variant d-md-none d-block"></i>
                  <span class="d-none d-md-block">Laatste 50 webshop orders</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#a2" data-toggle="tab" aria-expanded="false" class="nav-link">
                  <i class="mdi mdi-account-circle d-md-none d-block"></i>
                  <span class="d-none d-md-block">Nog enkele voorradig</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#a3" data-toggle="tab" aria-expanded="false" class="nav-link">
                  <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                  <span class="d-none d-md-block">Binnenkort weer leverbaar</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#a4" data-toggle="tab" aria-expanded="false" class="nav-link">
                  <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                  <span class="d-none d-md-block">Niet op voorraad</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#a5" data-toggle="tab" aria-expanded="false" class="nav-link">
                  <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                  <span class="d-none d-md-block">Niet gepubliceerd</span>
                  </a>
               </li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane show active" id="latest-50">
                  <h4 class="header-title mb-3">Laatste 50 webshop orders</h4>
                  <table class="table table-sm table-centered mb-0">
                     <thead>
                        <tr>
                           <th>Tijdstip</th>
                           <th>ID</th>
                           <th>Kanaal</th>
                           <th>Klant</th>
                           <th>Bedrag</th>
                        </tr>
                     </thead>
                     <tbody>
                        {% for order in orders.latestOrders | slice(0,49)%}
                        <tr>
                           <td class="mb-2">{{order.created_at}}</td>
                           <td><a href="{{ path_for('OrdersGetSingle',{'id': order.id}) }}" target="_blank">{{order.id}}</a></td>
                           <td>{{order.shop_name}}</td>
                           <td>{{order.firstname}} {{order.lastname}}</td>
                           <td>{{order.gross_price}}</td>
                        </tr>
                        {% endfor %}
                     </tbody>
                  </table>
               </div>
               <div class="tab-pane" id="a2">
                  <table class="table table-sm table-centered mb-0">
                     <thead>
                        <tr>
                           <th>Product</th>
                           <th></th>
                     </thead>
                     <tbody>
                        {% for product in someInStock %}
                        <tr>
                           <td class="mb-2"><a href="{{ path_for('ProductGet',{'id': product.id}) }}" target="_blank">{{product.sku}}</a> </td>
                           <td>{{product.title}}</td>
                        </tr>
                        {% endfor %}
                     </tbody>
                  </table>
               </div>
               <div class="tab-pane" id="a3">
                  <table class="table table-sm table-centered mb-0">
                     <thead>
                        <tr>
                           <th>Product</th>
                           <th>Leverdatum</th>
                           <th></th>
                     </thead>
                     <tbody>
                        {% for product in soonDeliver %}
                        <tr>
                           <td class="mb-2"><a href="{{ path_for('ProductGet',{'id': product.id}) }}" target="_blank">{{product.sku}}</a> </td>
                           <td>{{product.delivery_at}}</td>
                           <td>{{product.title}}</td>
                        </tr>
                        {% endfor %}
                     </tbody>
                  </table>
               </div>
               <div class="tab-pane" id="a4">
                  <table class="table table-sm table-centered mb-0">
                     <thead>
                        <tr>
                           <th>Product</th>
                           <th></th>
                     </thead>
                     <tbody>
                        {% for product in outStock %}
                        <tr>
                           <td class="mb-2"><a href="{{ path_for('ProductGet',{'id': product.id}) }}" target="_blank">{{product.sku}}</a> </td>
                           <td>{{product.title}}</td>
                        </tr>
                        {% endfor %}
                     </tbody>
                  </table>
               </div>
               <div class="tab-pane" id="a5">
                  <table class="table table-sm table-centered mb-0">
                     <thead>
                        <tr>
                           <th>Product</th>
                           <th></th>
                     </thead>
                     <tbody>
                        {% for product in notGranted %}
                        <tr>
                           <td class="mb-2"><a href="{{ path_for('ProductGet',{'id': product.id}) }}" target="_blank">{{product.sku}}</a> </td>
                           <td>{{product.title}}</td>
                        </tr>
                        {% endfor %}
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- end tab-content-->
         </div>
         <!-- end card-body-->
      </div>
      <!-- end card-->
   </div>
   <!-- end col-->
</div>
<!-- end row -->


{% endblock %} 
{% block javascript %} 
<script type="text/javascript">
   $(document).ready(function(){
        /// yearly
         var monthlyOrder = {{monthlyOrders | json_encode|raw }};
         var monthes = [];
   
         Object.keys(monthlyOrder).forEach(function(key) {
             monthes.push(monthlyOrder[key]);
         });
   
         var colors = ["#727cf5", "#e3eaef"];
         var dataColors = $("#high-performing-orders").data('colors');
         if (dataColors) {
             colors = dataColors.split(",");
         }
         var options = {
             chart: {
                 height: 323,
                 type: 'bar',
                 stacked: true
             },
             plotOptions: {
                 bar: {
                     horizontal: false,
                     columnWidth: '20%'
                 },
             },
             dataLabels: {
                 enabled: false
             },
             stroke: {
                 show: true,
                 width: 2,
                 colors: ['transparent']
             },
             series: [{
                 name: 'Orders',
                 data: monthes
             }],
             zoom: {
                 enabled: false
             },
             legend: {
                 show: false
             },
             colors: colors,
             xaxis: {
                 categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                 axisBorder: {
                     show: false
                 },
             },
             yaxis: {
                 labels: {
                     formatter: function (val) {
                         return val + ""
                     },
                     offsetX: -15
                 }
             },
             fill: {
                 opacity: 1
             },
             tooltip: {
                 y: {
                     formatter: function (val) {
                         return val
                     }
                 },
             },
         }
   
         var chart = new ApexCharts(
             document.querySelector("#high-performing-orders"),
             options
         );
         chart.render();



   });
</script>
{% endblock %}