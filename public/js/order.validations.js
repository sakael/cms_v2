//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////                      Orders Fields Validation                         ////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$(document).ready(function(){

    $("#order-form").validate({
        lang: 'nl',
        rules: {
            payment_firstname: {
                required: true,
            },
            payment_lastname: {
                required: true,
            },
            payment_street: {
                required: true,
            },
            payment_houseNumber: {
                required: true,
            },
            payment_zipcode: {
                required: true,
                postalcodeNL:function(element) {
                    var value=$("#payment_countryCode").val().toLowerCase();
                    if(value == 'nl')
                        return true;
                    else false;
                },
            },
            payment_city: {
                required: true,
            },
            payment_countryCode: {
                required: true,
            },
            //  'shipping_firstname' => 'required_with_all:shipping_lastname,shipping_street,shipping_houseNumber,shipping_zipcode,shipping_city,shipping_countryCode',
            shipping_firstname: {
                required: function(element) {
                    if($("#shipping_lastname").val()!='' || $("#shipping_street").val()!='' ||  $("#shipping_houseNumber").val()!='' || $("#shipping_zipcode").val() !='' || $("#shipping_city").val()!='' || $("#shipping_countryCode").val() !='') {
                        var check=true;
                    }else var check=false;
                    if(check)
                        return true;
                    else false;
                },
            },
            shipping_lastname: {
                required: function(element) {
                    if($("#shipping_firstname").val()!='' || $("#shipping_street").val()!='' ||  $("#shipping_houseNumber").val()!='' || $("#shipping_zipcode").val() !='' || $("#shipping_city").val()!='' || $("#shipping_countryCode").val() !='') {
                        var check=true;
                    }else var check=false;
                    if(check)
                        return true;
                    else false;
                },
            },
            shipping_street: {
                required: function(element) {
                    if($("#shipping_firstname").val()!='' || $("#shipping_lastname").val()!='' ||  $("#shipping_houseNumber").val()!='' || $("#shipping_zipcode").val() !='' || $("#shipping_city").val()!='' || $("#shipping_countryCode").val() !='') {
                        var check=true;
                    }else var check=false;
                    if(check)
                        return true;
                    else false;
                },
            },
            shipping_houseNumber: {
                required: function(element) {
                    if($("#shipping_firstname").val()!='' || $("#shipping_street").val()!='' ||  $("#shipping_lastname").val()!='' || $("#shipping_zipcode").val() !='' || $("#shipping_city").val()!='' || $("#shipping_countryCode").val() !='') {
                        var check=true;
                    }else var check=false;
                    if(check)
                        return true;
                    else false;
                },
            },
            shipping_zipcode: {
                required: function(element) {
                    if($("#shipping_firstname").val()!='' || $("#shipping_street").val()!='' ||  $("#shipping_houseNumber").val()!='' || $("#shipping_lastname").val() !='' || $("#shipping_city").val()!='' || $("#shipping_countryCode").val() !='') {
                        var check=true;
                    }else var check=false;
                    if(check)
                        return true;
                    else false;
                },
                postalcodeNL:function(element) {
                    var value=$("#shipping_countryCode").val().toLowerCase();
                    if(value == 'nl')
                        return true;
                    else false;
                },
            },
            shipping_countryCode: {
                required: function(element) {
                    if($("#shipping_firstname").val()!='' || $("#shipping_street").val()!='' ||  $("#shipping_lastname").val()!='' || $("#shipping_zipcode").val() !='' || $("#shipping_city").val()!='' || $("#shipping_houseNumber").val() !='') {
                        var check=true;
                    }else var check=false;
                    if(check)
                        return true;
                    else false;
                },
            },
            customer_email: {
                required: true,
                email: true
            },
            customer_phone: {
                required: true,
                phoneNL: function(element) {
                    var value=$("#payment_countryCode").val().toLowerCase();
                    if(value == 'nl')
                        return true;
                    else false;
                }
            },
        }
    });
});
