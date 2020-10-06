//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////                       OnmouseOver Load Images                        ////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function dirname(path) {
    return path.match(/.*\//);
}
/********* OnmouseOver Load Images **************/
function loadImage(e) {
    var src = $(e).data("image-src");
    $(e).find('img').attr('src', src);
}
$(document).ready(function() {
    $("#postcode-control").click(function() {
      $('#checking-postcode-popup').modal('show').find('.modal-body').load('/orders/postcode-controller');
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////                       ChangeOrderStatus                       /////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ChangeOrderStatus(event,OrderId, StatusId, el = false) {
    event.preventDefault();
    axios.put('/orders/order/status/update', {
        id: OrderId,
        status_id: StatusId
    }).then(function (response) {
        if (response.data.status == 'true') {
            var table = $(el).closest('table').DataTable().ajax.reload();
            toastr.success(response.data.msg);
        } else toastr.warning(response.data.msg);
    })
        .catch(function (error) {
            console.log(error);
        });
}

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


function getClaimButtons(id){
    return (
        '<a href="" class="action-icon font-20" target="_blank" title="Claim" onclick="ClaimOrder(event,' + id + ')" data-toggle="tooltip" data-placement="top" data-original-title="Claim" title=""> <i class=" text-success mdi mdi-hand-right"></i></a>' +
        '<a href="/orders/order/' + id + '" class="action-icon font-20" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Bewerken / Bekijken" title=""> <i class="mdi mdi-square-edit-outline text-dark"></i></a>'
      );
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////                        General order datatable functions rows                            ////////////////////////////////////////////////?
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function productRows(rows,type=''){
    var temp = '<ul class="list-group list-group-flush order-product-list">';
    var url = '';
    $.each(rows, function (key, order_item) {
        $.each(order_item.product.images, function (image_key, image) {
            if (image.main == 1) url = image.url;
        });
        var attributes='';
        $.each(order_item.attributes, function (attribute_key, attribute) {
            attributes+=' | '+attribute.title;
        });
        if ((url) && url != '') {
            $filename = url.replace(/^.*[\\\/]/, '');
            $filename= $filename.split('.').slice(0, -1).join('.');
            var image = image_url + '/' +dirname(url) + 'thumbs/' +$filename+'_bol.'+url.split('.').pop();
        }
        else image = '';
        temp = temp.concat('<li class="list-group-item products-item-list">');
        if (order_item.count > 1) temp = temp.concat(' count-focus');
        if(order_item.product_id == 99999999){
            temp = temp.concat('<span class="text-secondary" target="_blank">'+order_item.product.sku+ ' - ' + order_item.product_name + '</span>');
            if(order_item.price == 3.99){
                temp = temp.concat( ' | <span class="fast-shipping-be text-danger"> Snelle levering </span>');
            }
            temp = temp.concat( '</li>');
        }else{
            temp = temp.concat('<span class="text-primary mr-1 font-weight-bold">');
            temp = temp.concat(order_item['count'] + 'x </span>');
            temp = temp.concat('<span class="font-weight-bold mr-1">'+order_item.product.location+'</span> <span  class="on-mouse-over-load text-primary"');
            if(type != ''){
                temp = temp.concat('onclick="cliamByProduct(event,\''+order_item.product.sku+'\')"');
            }
            temp = temp.concat('onmouseover="loadImage(this)" data-image-src="' + image + '"> '+order_item.product.sku+ ' - ' + order_item.product_name + '<img src="" class="mouseover-load"></span> <span class="font-weight-bold mr-1">' + attributes + '</span> </li>');
        }
    });
    temp = temp.concat('</ul>');
    return temp;
}

function getCountry(order){
    var package = 0;
    var orderDeatails = JSON.parse(order);

    if(orderDeatails){
        if(typeof orderDeatails.address.shipping.countryCode !== 'undefined'){
            var temp = '<span class="m-0 flag-icon flag-icon-' + orderDeatails.address.shipping.countryCode.toLowerCase() + ' h4"></span>';
        }
    }else{
        var temp = '<span class="m-0 flag-icon flag-icon- h4"></span>';

    }
    return temp;
}
function getClient(order){
    var package = 0;
    var orderDeatails = JSON.parse(order);

    if(orderDeatails){
        temp='';
        if(typeof orderDeatails.address.payment.firstname !== 'undefined'){
            var temp = temp + '<span class="client-info">'+ orderDeatails.address.payment.firstname +' &nbsp;</span>';
        }
        if(typeof orderDeatails.address.payment.lastname !== 'undefined'){
            var temp = temp + '<span class="client-info">'+ orderDeatails.address.payment.lastname +'</span>';
        }
    }else{
        var temp = '<span class="">-</span>';

    }
    return temp;
}