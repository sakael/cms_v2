$(document).ready(function () {
      var all_inbox_notes_table = $('#all_inbox_notes_table').DataTable({
          ajax: {
              url: "/notes//get_all_from_notes",
              complete: function (data, textStatus, jqXHR) {
              }
          },
          columns: [
              {"data": "id"}, //"visible":false
              {"data": "from"},
              {
                  "data": "module",
                  "render": function(data, type, row) {
                    if(row.order_id !=0 ){
                      var url='{{ path_for('OrdersGetSingle',{'id':''})}}' + '' + row.order_id;
                      var temp='<a href="'+url+'" target="_blank">'+row.order_id+' (Order)</a>';
                    }
                    else if(row.product_id !=0 ){
                      var url='{{ path_for('ProductGet',{'id':''})}}' + '' + row.product_id;
                      var temp='<a href="'+url+'" target="_blank">'+row.product_id+' (Product)</a>';
                    }
                    return temp;
                  }
              },
              {"data": "note"},
              {"data": "updated_at"},
              {"data": "created_at"},
              {
                  "data": "action",
                  "render": function(data, type, row) {
                    var temp = '<ul class="action-list">';
                    temp = temp.concat('<li><button class="done-btn btn btn-xs btn-primary" id="done-'+row.id+'"  onclick="changeStatus('+row.id+',1)"');
                    if(row.status==1) temp = temp.concat(' disabled="disabled" ');
                    temp = temp.concat('><i class="fa fa-thumbs-up"></i> JA</button></li>');
                    temp = temp.concat('<li><button class="not-btn btn btn-xs btn-danger" id="not-'+row.id+'" onclick="changeStatus('+row.id+',0)"');
                    if(row.status==1) temp = temp.concat(' disabled="disabled" ');
                    temp = temp.concat('><i class="fa fa-thumbs-down"></i> NEE</button></li>');
                    temp = temp.concat('</ul>');
                    return temp;
                  }
              }
          ],
          "autoWidth": false,
          "aaSorting": [[0, 'DESC']],
          responsive: 'true',
          columnDefs: [{
              "width": "160px",
              "targets": 2
          }]
      });      
});