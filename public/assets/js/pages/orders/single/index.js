
$(document).ready(function() {
    $('.copy_class').on('click',function(){
        var element=$(this).attr("data-copy-text");
        var copyText = document.getElementById(""+element);
        copyText.select();
        //copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Tekst gekopieerd:" + copyText.value);
    });

});

    $(document).ready(function() {
        $("#duplicate").click(function(e) {
            e.preventDefault();
            var StatusId = $("#order_status").val();
            axios.post('/orders/order/duplicate', {
                  id: orderId,
                  _METHOD: 'POST',
                }).then(function(response) {
                if (response.data.status == 'true') {
                    toastr.success(response.data.msg);
                    $(location).attr('href', response.data.url);
                } else toastr.warning(response.data.msg);
            }).catch(function(error) {
                console.log(error);
            });
        });
    });

    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
                  //open MSp status
    ///////////////////////////////////////////////
    ///////////////////////////////////////////////
    $(document).ready(function() {
      $(".msp-control").click(function(e) {
        e.preventDefault();
        var transactionId = $(".msp-control").data("transaction-id");
        if(transactionId && transactionId!=''){
           $('#msp-status-popup').modal('show').find('.modal-body').load('/msp/status/' + transactionId);
        }
          return true;
      });
      $('#a_old_orders').click(function (e) {
          var target = this.href.split('#');
          $('.nav a').filter('a[href="#'+target[1]+'"]').tab('show');
      });
    });

    /////////////////////////////////////////////// Ask Payment //////////////////////////////////////////////
function askForPayment(order){
	var amount = prompt('Welk bedrag dient de klant te betalen?');
	var input = amount;
	var input = input.replace(",","");
	var input = input.replace(".","");

	var url = "https://beta.klanten.i12cover.nl/betaal/"+order+"/"+input;
	prompt('Stuur de volgende link naar de klant',url);
}
    /////////////////////////////////////////////// Force Payment //////////////////////////////////////////////
function forePayment(order){
	if (confirm('LET OP: Er wordt direct een factuur gemaakt van deze bestelling\r\n\r\nIs het zeker dat deze factuur voldaan is?')){
        axios.post("{{path_for('Orders.RegisterPayment')}}" , {
            order_id: order
        })
        .then(function (response) {
            if (response.data.status == 'true') {
                toastr.success('De betaling is geregistreerd');
                location.reload();
            } else if (response.data.status == 'false') {
                toastr.info(response.data.msg);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
	}
}