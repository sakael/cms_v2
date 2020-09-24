<section class="tables">
   <div class="container-fluid">
      <div class="card ">
         <div class="card-header ">
            <div class="row">
               <div class="col-lg-12">
                    Transaction ID ({{paymentStatus.transaction_id}}) - Order ID ({{paymentStatus.order_id}})
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-lg-2">
                 <strong>created:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.created}}
               </div>
               <div class="col-lg-2">
                <strong>amount:</strong>
               </div>
               <div class="col-lg-2">
                 € {{paymentStatus.amount / 100}}
               </div>
               <div class="col-lg-2">
                <strong> amount_refunded:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.amount_refunded}}
               </div>
            </div>
            <div class="row mt-3">
               <div class="col-lg-2">
                 <strong>status:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.status}}
               </div>
               <div class="col-lg-2">
                <strong> financial_status:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.financial_status}}
               </div>
               <div class="col-lg-2">
              <strong>reason:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.reason}}
               </div>
            </div>
            <div class="row mt-3">
               <div class="col-lg-2">
                 <strong>payment type:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.payment_details.type}}
               </div>
               <div class="col-lg-2">
                 <strong>account holder name:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.payment_details.account_holder_name}}
               </div>
               <div class="col-lg-2">
                 <strong>account iban:</strong>
               </div>
               <div class="col-lg-2">
                 {{paymentStatus.payment_details.account_iban}}
               </div>
            </div>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->
      <div class="card ">
         <div class="card-header ">
            <div class="row">
               <div class="col-lg-12">
                  Items
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-lg-12">
                  {{ paymentStatus.items | raw}}
               </div>
            </div>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->
   </div>
   <!-- /.container-->
</section>
