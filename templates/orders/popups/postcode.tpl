<section class="tables">
   <div class="container-fluid">
      <div class="card ">
         <div class="card-header ">
            <div class="row">
               <div class="col-lg-12">
                    Foutieve postcodes
               </div>
            </div>
         </div>
         <div class="card-body">
               <div class="row">
                 <div class="col-lg-3">
                   <strong>ordernummer</strong>
                 </div>
                 <div class="col-lg-3">
                   <strong>postcode</strong>
                 </div>
                 <div class="col-lg-3">
                   <strong>adres</strong>
                 </div>
                 <div class="col-lg-3">
                   <strong>plaats</strong>
                 </div>
               </div>
               {% for order in wrongAddresses %}
                 <div class="row">
                   <div class="col-lg-3">
                     <a href="{{ path_for('OrdersGetSingle',{ 'id': order.id })}}" target="_blank">{{order.id}}</a>
                   </div>
                   <div class="col-lg-3">
                     {{order.order_details.address.shipping.zipcode}}
                   </div>
                   <div class="col-lg-3">
                     {{order.order_details.address.shipping.street}} {{order.order_details.address.shipping.houseNumber}}
                   </div>
                   <div class="col-lg-3">
                     {{order.order_details.address.shipping.city}}
                   </div>
                 </div>
               {% endfor %}
         </div>
         <!-- /.card-body -->
      </div>
      <div class="card mt-5">
         <div class="card-header ">
            <div class="row">
               <div class="col-lg-12">
                    Dubbele postcodes
               </div>
            </div>
         </div>
         <div class="card-body">
               <div class="row">
                 <div class="col-lg-3">
                   <strong>ordernummer</strong>
                 </div>
                 <div class="col-lg-3">
                   <strong>postcode</strong>
                 </div>
                 <div class="col-lg-3">
                   <strong>adres</strong>
                 </div>
                 <div class="col-lg-3">
                   <strong>plaats</strong>
                 </div>
               </div>
               {% for order in doubledAddresses %}
                 <div class="row">
                   <div class="col-lg-3">
                     <a href="{{ path_for('OrdersGetSingle',{ 'id': order.id })}}" target="_blank">{{order.id}}</a>
                   </div>
                   <div class="col-lg-3">
                     {{order.order_details.address.shipping.zipcode}}
                   </div>
                   <div class="col-lg-3">
                     {{order.order_details.address.shipping.street}} {{order.order_details.address.shipping.houseNumber}}
                   </div>
                   <div class="col-lg-3">
                     {{order.order_details.address.shipping.city}}
                   </div>
                 </div>
               {% endfor %}
         </div>
         <!-- /.card-body -->
      </div>
   </div>
   <!-- /.container-->
</section>
