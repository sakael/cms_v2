//OrderRows Apps
$(document).ready(function() {
    $("#orderitems_form").submit(function(event) {
      event.preventDefault();
        var data = $("#orderitems_form").serialize();
        axios.post('/orders/order/order_items/update', data)
            .then(function(response) {
                if (response.data.status == 'true') {
                    toastr.success(response.data.msg);
                    $.each(orderRows.rows, function(key, value) {
                        orderRows.rows[key].new = 1;
                    });
                } else {
                    toastr.warning(response.data.msg);
                }
            })
            .catch(function(error) {
                toastr.warning(error);
            });
    });
});

$(document).ready(function() {
    $("#StatusChanges").click(function(e) {
        e.preventDefault();
        var StatusId = $("#order_status").val();
        var informUser=0;
        if($('#inform_user').prop("checked") == true){
            informUser=1;
        }
        axios.put('/orders/order/status/manually_update', {
                id:orderId,
                inform:informUser,
                status_id: StatusId
            }).then(function(response) {
                console.log(response);
            if (response.data.status == 'true') {
                toastr.success(response.data.msg);
                var addClasses = '';
                switch (StatusId){
                    case '1':
                        addClasses='btn-primary';
                        break;
                    case '3':
                        addClasses='btn-success';
                        break;
                    case '4':
                        addClasses='btn-info';
                        break;
                    default:
                        addClasses = 'btn-warning';
                }
                $('.current-status-info').html('<button type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="HUIDIGE STATUS" class="btn disabled '+addClasses+' ">'+response.data.statusOrder + '<br>( ' + response.data.user +' ) <br> '+ response.data.at +' </button>');
                //$('.current-status-info').html(' <p class="btn btn-sm white-space-normall '+ addClasses +' id="">'+response.data.statusOrder + '( ' + response.data.user +' ) <br> om '+ response.data.at +' </p>');
            } else toastr.warning(response.data.msg);
        }).catch(function(error) {
            console.log(error);
        });
    });
});

$(document).ready(function() {
    $('#history-table').DataTable({order : [[0, 'DESC']]});
});