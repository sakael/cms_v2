function getRemainingButtons(id){
    return (
        '<a href="/orders/order/' + id + '" class="action-icon font-20" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Bewerken / Bekijken" title=""> <i class="mdi mdi-square-edit-outline text-dark"></i></a>'
      );
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////                          Tabs On click                             ///////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
    if ((orderTab != '' && orderTab)) {
        var tab = orderTab;
        $('.orders-nav-tabs li.active').tab('hide');
        $('.orders-nav-tabs a[href="#' + orderTab + '"]').tab('show');
        var noHashURL = window.location.href.replace(/#.*$/, '');
        window.history.replaceState('', document.title, noHashURL);
        if (tab == 'returnExchange') returnExchange();
        else if (tab == 'returnShipmentCredit') returnShipmentCredit();
        else if (tab == 'waitPayment') waitPayment();
    } else {
        returnOrders();
    }
    $(".nav-link").click(function () {
        var tab = $(this).data('toggletab');
        if (tab == 'returnExchange') returnExchange();
        else if (tab == 'returnShipmentCredit') returnShipmentCredit();
        else if (tab == 'waitPayment') waitPayment();
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////                      check the active tab and refresh the dattable                         //////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function refreshCurrentTab() {
    var tab = $("ul.orders-nav-tabs li a.active").attr("data-toggletab");
    if (tab == 'returnExchange') returnExchange();
    else if (tab == 'returnShipmentCredit') returnShipmentCredit();
    else if (tab == 'waitPayment') waitPayment();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////                        returnExchange Orders Table                            /////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function returnExchange() {
    if ($.fn.DataTable.isDataTable('#return_exchange')) {
        $('#return_exchange').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var return_exchange = $('#return_exchange').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/return_exchange/data",
            'data': {
                showPaidOrders: showPaidOrders,
                status : 14
            }
        },
        pageLength: 30,
        columns: [
            { 
                data: "id", 
                "render": function (data, type, row) {
                    return '<a href="/orders/order/' + data + '" target="_blank">'+ data +'</a>';
                } 
            },
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
                data: null,
                orderable: false,
                className: "text-center",
                render: function (data, type, row) {
                    return getRemainingButtons(row.id);
                }
            }
        ],
    });
    return_exchange.on( 'draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
    } );
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////                       returnShipmentCredit Supplier Orders Table                            //////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function returnShipmentCredit() {
    if ($.fn.DataTable.isDataTable('#return_shipment_credit')) {
        $('#return_shipment_credit').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var return_shipment_credit = $('#return_shipment_credit').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/return_shipment_credit/data",
            'data': {
                showPaidOrders: showPaidOrders,
                status : 4
            }
        },
        pageLength: 30,
        columns: [
            { 
                data: "id", 
                "render": function (data, type, row) {
                    return '<a href="/orders/order/' + data + '" target="_blank">'+ data +'</a>';
                } 
            },
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
                    return getRemainingButtons(row.id);
                }
            }
        ],
    });
    return_shipment_credit.on( 'draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
    } );
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////                      waitPayment Orders Table                            //////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function waitPayment() {
    if ($.fn.DataTable.isDataTable('#wait_payment')) {
        $('#wait_payment').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var wait_payment = $('#wait_payment').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/wait_payment/data",
            'data': {
                showPaidOrders: showPaidOrders,
                status : 13
            }
        },
        pageLength: 30,
        columns: [
            { 
                data: "id", 
                "render": function (data, type, row) {
                    return '<a href="/orders/order/' + data + '" target="_blank">'+ data +'</a>';
                } 
            },
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
                    return getRemainingButtons(row.id);
                }
            }
        ],
    });
    wait_payment.on( 'draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
    } );
}
