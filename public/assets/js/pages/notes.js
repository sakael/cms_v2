$(document).ready(function () {
  var all_inbox_notes_table = $('#all_inbox_notes_table').DataTable({
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    ajax: {
      url: "/notes/get_all_from_notes",
    },
    pageLength: 30,
    columns: [
      {data: "id"},
      {data: "from"},
      {
        orderable: false,
        data: null,
        render: function (data, type, row) {
          if (row.order_id != 0) {
            var url = '/orders/order/' + row.order_id;
            var temp = '<a href="' + url + '" target="_blank">' + row.order_id + ' (Order)</a>';
          } else if (row.product_id != 0) {
            var url = '/product/'+ row.product_id;
            var temp = '<a href="' + url + '" target="_blank">' + row.product_id + ' (Product)</a>';
          }
          return temp;
        }
      },
      { orderable: true, data: "note" },
      { orderable: true, data: "updated_at" },
      { orderable: true, data: "created_at" },
      {
        orderable: false,
        data : null,
        render: function (data, type, row) {
        if (row.status == 1) {
            return '<input type="checkbox" id="note_' + row.id +'" checked data-switch="bool" onclick="changeStatus(this.id,' + row.id +')"/><label for="note_' + row.id +'" data-on-label="Ja" data-off-label="nee"></label>';
        }else{
            return '<input type="checkbox" id="note_' + row.id +'"  data-switch="bool" onclick="changeStatus(this.id,' + row.id +')"/><label for="note_' + row.id +'" data-on-label="Ja" data-off-label="nee"></label>';
        }
      }
    }
    ],
    aaSorting: [
      [5, 'DESC']
    ],
    responsive: 'true',
  });

  var all_sent_notes_table = $('#all_sent_notes_table').DataTable({
    language: {
      url: "/assets/js/datatable-langauge.json",
    },
    ajax: {
        url: "/notes/get_all_to_notes",
    },
    columnDefs: [],
    "autoWidth": false,
    columns: [
      { orderable: true, data: "id" },
      { orderable: true, data: "to" },
      { orderable: true, 
        data: null,
        render: function(data, type, row) {
          if(row.order_id !=0 ){
            var url='/orders/order/' + row.order_id;
            var temp='<a href="'+url+'" target="_blank">'+row.order_id+' (Order)</a>';
          }
          else if(row.product_id !=0 ){
            var url= '/product/'+ row.product_id;
            var temp='<a href="'+url+'" target="_blank">'+row.product_id+' (Product)</a>';
          }
          return temp;
        }
      },
      { orderable: true, data: "note" },
      { orderable: true, data: "updated_at" },
      { orderable: true, data: "created_at" },
      {
        orderable: false,
        data : null,
        render: function (data, type, row) {
          if (row.status == 1) {
              return '<input type="checkbox" checked data-switch="bool" disabled /><label for="switch5" data-on-label="Ja" data-off-label="nee"></label>';
          }else{
              return '<input type="checkbox"  data-switch="bool" disabled /><label for="switch5" data-on-label="Ja" data-off-label="nee"></label>';
          }
        }
      }
    ],
    "aaSorting": [[5, 'DESC']],
    responsive: 'true',
  });
  all_sent_notes_table.columns.adjust().draw();
});
  
  function changeStatus(thisId,id) {
    if (document.getElementById(thisId).checked) 
    {
      var status = 1;
    }else{
      var status = 0;
    }
    
    axios.post('/notes/note/update/status', {
        id: id,
        status: status,
        _METHOD: 'PUT'
      }).then(function (response) {
      if (response.data.return == 'Success') {
        if (status == 1) {
          $('#not-' + id).removeAttr("disabled");
          $('#done-' + id).attr("disabled", "disabled");
          var count = $('.notes_count').html();
          count = parseInt(count) - 1;
          $('.notes_count').html(count);
        } else if (status == 0) {
          $('#done-' + id).removeAttr("disabled");
          $('#not-' + id).attr("disabled", "disabled");
          var count = $('.notes_count').html();
          count = parseInt(count) + 1;
          $('.notes_count').html(count);
        }
        toastr.success(response.data.msg);
      } else toastr.warning(response.data.msg);
    }).catch(function (error) {
      console.log(error);
    });
  }

  var app_messages = new Vue({
    el: "#app_new",
    data: {
      user_id: authId,
      users: users,
      url_add: "/notes/add",
      url: "/notes/get_all_selected_Models"
    },
    mounted: function () {
    }
  });