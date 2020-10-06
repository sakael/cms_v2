//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////                       To credit function                       //////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function Credit(event,orderId) {
    event.preventDefault();
    alert(' Crediteren : ' + orderId);
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
        if (tab == 'returnOrders') returnOrders();
        else if (tab == 'waitSupplierOrders') waitSupplierOrders();
        else if (tab == 'waitExternalSupplierOrders') waitExternalSupplierOrders();
        else if (tab == 'waitCustomerOrders') waitCustomerOrders();
        else if (tab == 'creditOrders') creditOrders();
    } else {
        returnOrders();
    }
    $(".nav-link").click(function () {
        var tab = $(this).data('toggletab');
        if (tab == 'returnOrders') returnOrders();
        else if (tab == 'waitSupplierOrders') waitSupplierOrders();
        else if (tab == 'waitExternalSupplierOrders') waitExternalSupplierOrders();
        else if (tab == 'waitCustomerOrders') waitCustomerOrders();
        else if (tab == 'creditOrders') creditOrders();
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////                      check the active tab and refresh the dattable                         //////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function refreshCurrentTab() {
    var tab = $("ul.orders-nav-tabs li a.active").attr("data-toggletab");
    if (tab == 'returnOrders') returnOrders();
    else if (tab == 'waitSupplierOrders') waitSupplierOrders();
    else if (tab == 'waitExternalSupplierOrders') waitExternalSupplierOrders();
    else if (tab == 'waitCustomerOrders') waitCustomerOrders();
    else if (tab == 'creditOrders') creditOrders();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////                        Return Orders Table                            //////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function returnOrders() {
    if ($.fn.DataTable.isDataTable('#all_return_orders')) {
        $('#all_return_orders').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var all_return_orders = $('#all_return_orders').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/return/data",
            'data': {
                showPaidOrders: showPaidOrders,
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
                "render": function (data, type, row) {
                    return (
                        '<a href="" class="action-icon font-20" target="_blank" title="Crediteren" onclick="Credit(event,' + row.id + ')" data-toggle="tooltip" data-placement="top" data-original-title="Crediteren" title=""> <i class=" text-warning  uil-euro-circle"></i></a>' +
                        '<a href="" class="action-icon font-20" target="_blank" title="Crediteren" onclick="ChangeOrderStatus(event,' + row.id + ',1,this)" data-toggle="tooltip" data-placement="top" data-original-title="Omzetten naar nieuw" title=""> <i class=" text-success uil-refresh"></i></a>' +
                        '<a href="/orders/order/' + row.id + '" class="action-icon font-20" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Bewerken / Bekijken" title=""> <i class="mdi mdi-square-edit-outline text-dark"></i></a>'
                    );
                }
            }
        ],
    });
    all_return_orders.on( 'draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
    } );
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////                       Wait Supplier Orders Table                            //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function waitSupplierOrders() {
    if ($.fn.DataTable.isDataTable('#all_wait_supplier_orders')) {
        $('#all_wait_supplier_orders').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var all_wait_supplier_orders = $('#all_wait_supplier_orders').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/wait_supplier/data",
            'data': {
                showPaidOrders: showPaidOrders,
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
                    return getClaimButtons(row.id);
                }
            }
        ],
    });
    all_wait_supplier_orders.on( 'draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
    } );
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////                       Wait External Supplier Orders Table                            //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function waitExternalSupplierOrders() {
    if ($.fn.DataTable.isDataTable('#all_wait_external_supplier_orders')) {
        $('#all_wait_external_supplier_orders').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var all_wait_external_supplier_orders = $('#all_wait_external_supplier_orders').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/wait_external_supplier/data",
            'data': {
                showPaidOrders: showPaidOrders,
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
                    return getClaimButtons(row.id);
                }
            }
        ],
    });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////                       Wait Customer Orders Table                            //////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function waitCustomerOrders() {
    if ($.fn.DataTable.isDataTable('#all_wait_customer_orders')) {
        $('#all_wait_customer_orders').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var all_wait_customer_orders = $('#all_wait_customer_orders').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/wait_customer/data",
            'data': {
                showPaidOrders: showPaidOrders,
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
                    return getClaimButtons(row.id);
                }
            }
        ],
    });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////                        Credit Orders Table                            //////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function creditOrders() {
    if ($.fn.DataTable.isDataTable('#all_credit_orders')) {
        $('#all_credit_orders').DataTable().destroy();
    }
    var showPaidOrders = 0;
    if (Cookies.get('show_paid_orders')) {
        showPaidOrders = 1;
    } else {
        showPaidOrders = 0;
    }
    var all_credit_orders = $('#all_credit_orders').DataTable({
        language: {
            url: "/assets/js/datatable-langauge.json",
        },
        ajax: {
            url: "/orders/credit/data",
            'data': {
                showPaidOrders: showPaidOrders,
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
                    return getClaimButtons(row.id);
                }
            }
        ],
    });
}