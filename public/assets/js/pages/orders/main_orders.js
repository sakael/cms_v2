//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////                          Tabs On click                             ///////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
   
    //show the first active tab
    var tab = $("ul.orders-nav-tabs li a.active").attr("data-toggletab");
    if (tab == 'newOrders') newOrders();
    else if (tab == 'claimedOrders') claimedOrders();
    else if (tab == 'readyForShippingOrders') ReadyForShippingOrders();

    $(".nav-link").click(function () {
        var tab = $(this).data('toggletab');
        if (tab == 'newOrders') newOrders();
        else if (tab == 'claimedOrders') claimedOrders();
        else if (tab == 'readyForShippingOrders') ReadyForShippingOrders();
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////                      check the active tab and refresh the dattable                         //////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function refreshCurrentTab() {
    var tab = $("ul.orders-nav-tabs li a.active").attr("data-toggletab");
    if (tab == 'newOrders') newOrders();
    else if (tab == 'claimedOrders') claimedOrders();
    else if (tab == 'readyForShippingOrders') ReadyForShippingOrders();
}

//851466

function ClaimOrder(event,orderId) {
    event.preventDefault();
    axios.post('/orders/order/claim', {
        id: orderId
    }).then(function (response) {
        if (response.data.status == 'true') {
            refreshCurrentTab();
            toastr.success(response.data.msg);
        } else toastr.warning(response.data.msg);
    })
        .catch(function (error) {
            console.log(error);
        });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////                       label functions                          //////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function PostNl(orderId, package) { ///Daymo

    var id = orderId;
    axios.get("/orders/print/post?id=" + id + "&package=" + package + "&type=post")
        .then(function (response) {
            if (response.data.response == 'True' && package != 1) {
                var status = global_print(response.data);
            } else if (response.data.response == 'Box' || package==1) {
                Box(orderId, 1);
            }
            else console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function Box(orderId, package) { ///Zebra
    var id = orderId;
    axios.get("/orders/print/box?id=" + id + "&handtekening=0&package=" + package + "&type=box")
        .then(function (response) {
            if (response.data.response == 'True') {
                print(response.data.IP, response.data.zpl);
                toastr.success(response.data.msg);
            } else if (response.data.response == 'Post') {
                PostNl(orderId, 0);
                claimedOrders();
                ReadyForShippingOrders();
            } else console.log(response.data.name);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function Signature(orderId, package) {
    var id = orderId;
    axios.get("/orders/print/box?id=" + id + "&handtekening=1&package=" + package + "&type=box")
        .then(function (response) {
            if (response.data.response == 'True') {
                print(response.data.IP, response.data.zpl);
                toastr.success(response.data.msg);
                claimedOrders();
                ReadyForShippingOrders();
            } else if (response.data.response == 'Post') {
                PostNl(orderId, 0);
                claimedOrders();
                ReadyForShippingOrders();
            } else console.log(response.data.name);
        })
        .catch(function (error) {
            console.log(error);
        });
}
function Parcel(orderId, package){
    var id = orderId;
    axios.get("/orders/print/post?id=" + id + "&package=0&type=post")
    .then(function (response) {
        if (response.data.response == 'True') {
            var status = global_print(response.data);
            axios.get("/orders/print/parcel?id="+id)
                .then(function (response_2) {

                }).catch(function (error) {
                        console.log(error);
                });
        }
        else console.log(response.data);
    })
    .catch(function (error) {
        console.log(error);
    });
}
/*********Ending of Label Functions***************/


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////                      Print Picklist                       //////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*********************** Print Picklist ***********************/
function printPickListNew(user_id) {

    axios.get('/orders/print/pick-list', {
        id: user_id,
    }).then(function (data) {
        var currentDate = new Date(),
            day = currentDate.getDate(),
            month = currentDate.getMonth() + 1,
            year = currentDate.getFullYear()
            hours = currentDate.getHours(),
            minutes = currentDate.getMinutes();

        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (1 == 1) {
            var cursor = 0;
            var lineSpace = 0;
            var leftPosition = 0;
            var centerPosition = 0;
            var rightPosition = 0;
            var products = data.data.return;
            var username = data.data.user_name;
            var builder = new StarWebPrintBuilder();
            var request = '';
            request += builder.createInitializationElement();
            request += builder.createAlignmentElement({ position: 'center' });
            request += builder.createTextElement({ width: 3, height: 3, data: '\n' + username + '\n\n' });
            request += builder.createAlignmentElement({ position: 'left' });
            request += builder.createRuledLineElement({ thickness: 'medium' });
            $.each(products, function (index_n, value_n) {

                var row = '';
                var split = index_n.split(" ");

                var count = value_n.Count + 'x';
                var itemnumber = split[0].toUpperCase();
                if (split[1]) {
                    var itemcolor = split[1].toUpperCase();
                } else {
                    var itemcolor = '';
                }

                var itemlocation = value_n['Location'].toUpperCase();
                var totalLength = (count.length) + (itemnumber.length) + (itemcolor.length) + (itemlocation.length);
                var spaces = 46 - totalLength;
                row += count + " " + itemlocation + " " + itemcolor;
                for (i = 0; i < spaces; i++) {
                    row += ' ';
                }
                row += itemnumber;
                row += '\n';
                request += builder.createAlignmentElement({ position: 'left' });
                request += builder.createTextElement({ font: 'font_a', width: 1, height: 1, data: row });
                request += builder.createRuledLineElement({ thickness: 'medium' });
            });
            request += builder.createAlignmentElement({ position: 'center' });
            request += builder.createTextElement({
                width: 1,
                height: 1,
                data: '\n' + day + "/" + month + "/" + year + " - " + hours + ":" + minutes + '\n\n'
            });
            request += builder.createTextElement({ data: '\n\n\n' });
            request += builder.createCutPaperElement({ feed: true, type: 'full' });
            var url = 'http://10.0.0.21/StarWebPRNT/SendMessage';
            var trader = new StarWebPrintTrader({ url: url });
            trader.onReceive = function (response) {
                var msg = 'ERROR:\n';
                if (trader.isCoverOpen({ traderStatus: response.traderStatus })) {
                    msg += '\tKlep open,\n';
                }
                if (trader.isOffLine({ traderStatus: response.traderStatus })) {
                    msg += '\tPrinter offline,\n';
                }
                if (trader.isCompulsionSwitchClose({ traderStatus: response.traderStatus })) {
                    msg += '\tCompulsionSwitchClose,\n';
                }
                if (trader.isHighTemperatureStop({ traderStatus: response.traderStatus })) {
                    msg += '\tOververhit,\n';
                }
                if (trader.isNonRecoverableError({ traderStatus: response.traderStatus })) {
                    msg += '\tNiet te herleiden fout,\n';
                }
                if (trader.isAutoCutterError({ traderStatus: response.traderStatus })) {
                    msg += '\tPapiersnijder fout,\n';
                }
                if (trader.isBlackMarkError({ traderStatus: response.traderStatus })) {
                    msg += '\tThermiek fout,\n';
                }
                if (trader.isPaperEnd({ traderStatus: response.traderStatus })) {
                    msg += '\tRol op,\n';
                }
                if (trader.isPaperNearEnd({ traderStatus: response.traderStatus })) {
                    msg += '\tPapier bijna op,\n';
                }
                if (response.traderSuccess == 'false') {

                }
            }
            trader.onError = function (response) {
                var msg = '- onError -\n\n';
                msg += '\tStatus:' + response.status + '\n';
                msg += '\tResponseText:' + response.responseText;

            }
            trader.sendMessage({ request: request });
            toastr.success('De picklist wordt afgedrukt');
        } else console.log(response.data.name);
    })
        .catch(function (error) {
            console.log(error);
        });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////                      Print Label Zebra                      //////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function print(ip_addr, zpl) {
    var output = document.getElementById("output");
    var url = "http://" + ip_addr + "/pstprnt";
    var method = "POST";
    var async = true;
    var request = new XMLHttpRequest();
    request.onload = function () {
        var status = request.status; // HTTP response status, e.g., 200 for "200 OK"
        var data = request.responseText; // Returned data, e.g., an HTML document.
        output.innerHTML = "Status: " + status + "<br>" + data;
        console.log(data + ' status: ' + status);
    }
    request.open(method, url, async);
    // Actually sends the request to the server.
    request.send(zpl);
    claimedOrders();
    ReadyForShippingOrders();
    return true;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////                        General order datatable functions rows                            ////////////////////////////////////////////////?
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getClaimButtons(id){
    /*var temp = '<ul class="action-list">';
    temp = temp.concat('<li><button class="claim-btn btn btn-xs btn-success claim-order" onclick="ClaimOrder(' + id + ')"><i class="fa fa-list-ul"></i> Claim</button></li>');
    temp = temp.concat('<li><a href="/orders/order/' + id + '" class="btn btn-xs btn-info "><i class="fa fa-edit"></i> bekijken / bewerken</a></li>');
    temp = temp.concat('</ul>');*/
    return (
        '<a href="" class="action-icon" target="_blank" title="Claim" onclick="ClaimOrder(event,' + id + ')"> <i class=" text-success mdi mdi-hand-right"></i></a>' +
        '<a href="/orders/order/' + id + '" class="action-icon" target="_blank"> <i class="mdi mdi-square-edit-outline text-primary"></i></a>'
      );
   // return temp;
}

function getPrintButtons(row){
    var package = 0;
    var orderDeatails = JSON.parse(row.order_details);
    $.each(row.order_items, function (key, order_item) {
        if (order_item.product.package == 1) package = 1;
    });
    var temp = '<ul class="action-list">';
    temp = temp.concat('<li><button class="postnl-btn btn btn-xs btn-primary" onclick="PostNl(' + row.id + ',' + package + ')"><i class="fa fa-envelope-o"></i> PostNL</button></li>');
    if(orderDeatails){
        if(typeof orderDeatails.address.shipping.countryCode !== 'undefined'){
            if (orderDeatails.address.shipping.countryCode == 'NL') {
                temp = temp.concat('<li><button class="box-btn btn btn-xs btn-primary" onclick="Box(' + row.id + ',' + package + ')"><i class="fa fa-archive"></i> DHL</button></li>');
                temp = temp.concat('<li><button class="signature-btn btn btn-xs btn-primary" onclick="Signature(' + row.id + ',' + package + ')"><i class="fa fa-pencil-square-o"></i> Handtekening</button></li>');
            } else {
                temp = temp.concat('<li><button class="signature-btn btn btn-xs btn-primary" onclick="Parcel(' + row.id + ',' + package + ')"><i class="fa fa-pencil-square-o"></i> Parcel</button></li>');
            }
        }
    }
    temp = temp.concat('<li><a href="/orders/order/' + row.id + '" class="btn btn-xs btn-info "><i class="fa fa-edit"></i> bekijken / bewerken</a></li>');
    temp = temp.concat('</ul>');
    return temp;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////                        New Orders Table                            ///////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function newOrders() {
    if ($.fn.DataTable.isDataTable('#all_new_orders')) {
        $('#all_new_orders').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var all_new_orders = $('#all_new_orders').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
          },
        ajax: {
            url: "/orders/new/data",
            'data': {
                showPaidOrders: showPaidOrders,
            },
        },
        pageLength: 30,
        columns: [
            { data: "id"}, //"visible":false
            { data: "shop_name" },
            {
                data : null,
                //"width": "10px",
                "orderable": false,
                "render": function (data, type, row) {
                    return getClient(row.order_details);
                }
            },
            {
                data : null,
                className: "text-center",
                orderable: false,
                render: function (data, type, row) {
                    return getCountry(row.order_details);
                }
            },
            {
                data : null,
                orderable: false,
                render: function (data, type, row) {
                    return productRows(row.order_items,'new')
                }
            },
            { data : "created_at" },
            {
                data : null,
                orderable: false,
                className: "text-center",
                render: function (data, type, row) {
                    return getClaimButtons(row.id);
                }
            }
        ],
        "createdRow": function( row, data, dataIndex ) {
            var payment=JSON.parse(data.payment);
            if(payment){
                if ( parseFloat(payment.amount) != parseFloat(data.gross_price) ) {
                $(row).addClass( 'prices-not-equal' );
                }
            }
          },
        "drawCallback": function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        },
        fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
            $(row).on('mousedown', 'td', function (e) {
                if (e.button == 0 && $(this).index() != 6 && $(this).index() != 4 && $(this).index() != 0) {
                    //$('#order-popup').modal('show').find('.modal-body').load('/orders/order/popup/' + '' + data.id);
                    window.open('/orders/order/' + '' + data.id);
                }
                return true;
            });
        }
    });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////                        Claimed Orders Table                            //////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function claimedOrders() {
    if ($.fn.DataTable.isDataTable('#all_claimed_orders')) {
        $('#all_claimed_orders').DataTable().destroy();
    }
    var byOtherCheck = 0;
    if (Cookies.get('byOthers')) {
        byOtherCheck = 1;
    } else {
        byOtherCheck = 0;
    }
    var all_claimed_orders = $('#all_claimed_orders').DataTable({
        oLanguage: {
            sLengthMenu: "_MENU_",
            sSearch: '',
            sSearchPlaceholder: "Zoek...",
            oPaginate: {
                sPrevious: "voorgaand",
                sNext: "volgende"
            }
        },
        responsive: 'true',
        ajax: {
            url: "/orders/claimed/data",
            'data': {
                byOthers: byOtherCheck,
                // etc..
            },
            complete: function (data, textStatus, jqXHR) {
                //    console.log(data.responseText);
            }
        },
        "autoWidth": false,
        columns: [
            { "data": "id" }, //"visible":false
            { "data": "shop_name" },
            {
                "data": "client",
                //"width": "10px",
                "orderable": false,
                "render": function (data, type, row) {
                    return getClient(row.order_details);
                }
            },
            {
                "data": "country",
                "width": "10px",
                "orderable": false,
                "render": function (data, type, row) {
                    return getCountry(row.order_details);
                }
            },
            {
                "data": "products",
                "render": function (data, type, row) {
                    return productRows(row.order_items);
                }
            },
            { "data": "created_at" },
            {
                "data": "action",
                "width": "490px",
                "orderable": false,
                "render": function (data, type, row) {
                    return getPrintButtons(row);
                }
            }
        ],
        "createdRow": function( row, data, dataIndex ) {
            var payment=JSON.parse(data.payment);
            if(payment && typeof payment.amount !== 'undefined'){
                if ( parseFloat(payment.amount) != parseFloat(data.gross_price) ) {
                $(row).addClass( 'prices-not-equal' );
                }
            }
          },
        fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
            $(row).on('mousedown', 'td', function (e) {
                if (e.button == 0 && $(this).index() != 6 && $(this).index() != 4 && $(this).index() != 0) {
                   // $('#order-popup').modal('show').find('.modal-body').load('/orders/order/popup/' + '' + data.id);
                   window.open('/orders/order/' + '' + data.id);
                }
                return true;
            });
        }
    });

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////                        Shiiped Orders Table                            //////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function ReadyForShippingOrders() {
    if ($.fn.DataTable.isDataTable('#all_ready_for_shipping_orders')) {
        $('#all_ready_for_shipping_orders').DataTable().destroy();
    }
    var byOtherCheck = 0;
    if (Cookies.get('byOthers')) {
        byOtherCheck = 1;
    } else {
        byOtherCheck = 0;
    }
    var all_ready_for_shipping_orders = $('#all_ready_for_shipping_orders').DataTable({
        oLanguage: {
            sLengthMenu: "_MENU_",
            sSearch: '',
            sSearchPlaceholder: "Zoek...",
            oPaginate: {
                sPrevious: "voorgaand",
                sNext: "volgende"
            }
        },
        responsive: true,
        ajax: {
            url: "/orders/ready_for_shipping/data",
            'data': {
                byOthers: byOtherCheck,
                // etc..
            },
            complete: function (data, textStatus, jqXHR) {
                //  console.log(data.responseText);
            }
        },
        "autoWidth": false,
        columns: [
            { "data": "id" }, //"visible":false
            { "data": "shop_name" },
            {
                "data": "client",
                //"width": "10px",
                "orderable": false,
                "render": function (data, type, row) {
                    return getClient(row.order_details);
                }
            },
            {
                "data": "country",
                "width": "10px",
                "orderable": false,
                "render": function (data, type, row) {
                    return getCountry(row.order_details);
                }
            },
            {
                "data": "products",
                "render": function (data, type, row) {
                    return productRows(row.order_items);
                }
            },
            { "data": "created_at" },
            {
                "data": "action",
                "width": "490px",
                "orderable": false,
                "render": function (data, type, row) {
                    return getPrintButtons(row);
                }
            }
        ],
        fnRowCallback: function (row, data, iDisplayIndex, iDisplayIndexFull) {
            $(row).on('mousedown', 'td', function (e) {
                if (e.button == 0 && $(this).index() != 6 && $(this).index() != 3 && $(this).index() != 0 && $(this).index() != 0) {
                   // $('#order-popup').modal('show').find('.modal-body').load('/orders/order/popup/' + '' + data.id);
                   window.open('/orders/order/' + '' + data.id);
                }
                return true;
            });
        }
    });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////                       claim functions                          //////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
    /************claim functions**********/
    $('#claim-by-productgroup-btn').click(function () {
        var value = $('#claim-by-productgroup').val();
        var result = confirm("Weet je het zeker?");
        if (result) {
            axios.get("orders/claim/per-productgroup?value="+value).then(function (response) {
                if (response.data.status == 'true') {
                    $('#claim-by-productgroup').val('');
                    toastr.success('Alle producten in de ' + value + ' productgroep zijn nu geclaimt');
                    $('.orders-nav-tabs a[href="#claimedOrders"]').tab('show');
                    claimedOrders();
                } else if (response.data.status == 'false') {
                    toastr.info(response.data.msg);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    });

    $('#claim-by-claim-by-stelling-btn').click(function () {
        var value = $('#claim-by-stelling').val();
        var result = confirm("Weet je het zeker?");
        if (result) {
            axios.post("/orders/claim/per-stelling", {
                value: value
            }).then(function (response) {
                if (response.data.status == 'true') {
                    $('#claim-by-stelling').val('');
                    toastr.success('Alle producten in de ' + value + 'stelling zijn nu geclaimt');
                    $('.orders-nav-tabs a[href="#claimedOrders"]').tab('show');
                    claimedOrders();
                } else if (response.data.status == 'false') {
                    toastr.info(response.data.msg);
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    });

    $('#claim-by-buitenland-btn').click(function () {
        var value = $('#claim-by-buitenland').val();
        var result = confirm("Weet je het zeker?");
        if (result) {
            axios.post("/orders/claim/per-buitenland", {
                value: value
            })
                .then(function (response) {
                    if (response.data.status == 'true') {
                        $('#claim-by-buitenland').val('');
                        toastr.success('Alle buitenland zendingen zijn geclaimt');
                        $('.orders-nav-tabs a[href="#claimedOrders"]').tab('show');
                        claimedOrders();
                    } else if (response.data.status == 'false') {
                        toastr.info(response.data.msg);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    });
    $('#claim-by-B2B-btn').click(function () {
        var value = $('#claim-by-B2B').val();
        var result = confirm("Weet je het zeker?");
        if (result) {
            axios.post("/orders/claim/per-b2b", {
                value: value
            })
                .then(function (response) {
                    if (response.data.status == 'true') {
                        $('#claim-by-B2B').val('');
                        toastr.success('Alle B2B orders zijn geclaimt');
                        $('.orders-nav-tabs a[href="#claimedOrders"]').tab('show');
                        claimedOrders();
                    } else if (response.data.status == 'false') {
                        toastr.info(response.data.msg);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    });



    /*********ending of claiming orders***************/



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////                       Claimed Orders By others too                   ////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $('#by-others').change(function () {
        if ($('#by-others').prop("checked") == true) {
            Cookies.set('byOthers', 'value', { expires: 7, path: '\orders' });
            refreshCurrentTab();
        }
        else {
            Cookies.remove('byOthers');
            refreshCurrentTab();
        }
    });

    if (Cookies.get('byOthers')) {
        $('#by-others').prop("checked", true);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////                       Show Paid Orders                  //////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $('#show_paid_orders').change(function () {
        if ($('#show_paid_orders').prop("checked") == true) {
            Cookies.set('show_paid_orders', 'value', { expires: 7, path: '\orders' });
            refreshCurrentTab();
        }
        else {
            Cookies.remove('show_paid_orders');
            refreshCurrentTab();
        }
    });

    if (Cookies.get('show_paid_orders')) {
        $('#show_paid_orders').prop("checked", true);
    }

});


//ClaimByProduct
function cliamByProduct(e,product) {
    e.preventDefault();
    var r = confirm('Weet je het zeker?');
    if (r == true) {
        axios.post('/orders/order/claim-orders-by-product', {
            sku: product
        }).then(function (response) {
            if (response.data.status == 'true') {
                refreshCurrentTab();
                toastr.success(response.data.msg);
            } else toastr.warning(response.data.msg);
        })
        .catch(function (error) {
            console.log(error);
        });
    }
 }
