<script src = "/js/dropzone.js"></script> 
	<!-- third party js -->
	<script src="/dist/assets/js/vendor/jquery.dataTables.min.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.responsive.min.js"></script>
	<script src="/dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>
  <script src="/assets/js/jquery-ui.js"></script>
<script type = "text/javascript">

  $(document).ready(function () {
    // store the active tab when a user has clicked it
    $(".nav-link").click(function () {
      Cookies.set('remember_the_tab', $(this).data('toggletab'), {
        expires: 7,
        path: '{{ path_for('ProductUpdate',{'id':product.id})}}'
      });
    });

    /**************************************************************************************************************************************************
     ******************************************************************( Main Info Part )**************************************************************
     **************************************************************************************************************************************************/
    $('.update-main-information-btn').on('click', function (e) {
      e.preventDefault();
      var productId = "{{ product.id }}";
      var contents = {};
      $("input[data-mapping=contents]").each(function () {
        contents[$(this).data('mappingkey')] = $(this).val();
      });
      $("textarea[data-mapping=contents]").each(function () {
        if ($(this).hasClass('rich-text')) {
          contents[$(this).data('mappingkey')] = tinymce.get($(this).data('mappingkey')).getContent();
        } else {
          contents[$(this).data('mappingkey')] = $(this).val();
        }
      });
      $("select[data-mapping=contents]").each(function () {
        contents[$(this).data('mappingkey')] = $(this).val();
      });

      $("input[data-mapping=characteristics]").each(function (i) {
        if (i == 0) {
          contents['characteristics'] = {};
        }
        contents['characteristics'][$(this).data('mappingkey')] = $(this).val();
      });

      axios.put('{{ path_for('ProductUpdate',{'id':product.id})}}', {
          contents: contents,
          productId: productId,
          type: 'contents'
        }).then(function (response) {
        if (response.data.status == 'true') {
          toastr.success(response.data.msg);
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      })
    });

    $(".select2").select2();
    $(".ui-sortable").sortable({
      items: "div.ui-draggable", // :not(.ui-state-disabled)
      stop: function (event, ui) {
        var items = $(".ui-sortable").sortable("toArray");
        console.log(items);
        $.post("{{ path_for('ProductImageSort',{'id':product.id} ) }}", {
            _METHOD: 'PUT',
            type: 'update_image_order',
            'keys': items
          })
          .done(function (data) {
            //console.log(data);
            toastr.info('Nieuwe volgorde is opgeslagen');
          });
      }
    });

    var uploadStatus = new Dropzone(".file_uploader", {
      maxFilesize: 5,
      success: function (file, response) {
        console.log(file);
        console.log(response);
      },
      url: "{{ path_for('ProductImageAdd',{'id':product.id} ) }}",
      createImageThumbnails: false,
      previewTemplate: '<div style="display:none"></div>'
    });

    uploadStatus.on("addedfile", function (file) {
      $(".file_uploader_helptext").html('Bezig...');
    });
    uploadStatus.on("complete", function (file) {
      //console.log(file);
      location.reload();
    });

    $(".file_uploader_helptext").on('click', function () {
      $('.file_uploader').click();
      return false;
    });

    $(".btn-remove-image").click(function () {
      var image_id = $(this).data('imageid');
      if (confirm('Weet je zeker dat je de afbeelding wilt verwijderen?')) {
        $.post('{{ path_for('ProductUpdate',{'id':product.id})}}', {
              type: 'remove_image',
              image_id: image_id,
              _METHOD: 'PUT'
            })
          .done(function (data) {
            location.reload();
          });
      }
    });

    $(".image_checkbox").click(function () {
      var cbtype = $(this).data('cbtype');
      var image_id = $(this).data('imageid');
      $.post('{{ path_for('ProductUpdate',{'id':product.id} ) }}', {
            type: 'update_image',
            cbtype: cbtype,
            image_id: image_id,
            _METHOD: 'PUT'
          })
        .done(function (data) {
          location.reload();
        });
    });

    $(".image_name").on('focus', function () {
      field_current = $(this).val();
    });
    $(".image_name").on('blur', function () {
      var image_id = $(this).data('imageid');
      var title = $(this).val();
      if (title == field_current)
        return;
      $.post('{{ path_for('ProductUpdate',{'id':product.id}) }}', {
            type: 'update_image_title',
            image_id: image_id,
            title: title,
            _METHOD: 'PUT'
          })
        .done(function (data) {
          toastr.info('Titel van afbeelding is opgeslagen');
          //console.log(data);
        });
    });

    $(".attributes_picker").on('change', function () {
      var group = $(this).data('groupid');
      $.post('{{ path_for('ProductUpdate',{'id':product.id} ) }}', {
            type: 'attributes',
            group: group,
            attributes: $(this).val(),
            _METHOD: 'PUT'
          })
        .done(function (data) {
          toastr.info('Attributen zijn opgeslagen');
        });
    });

    $(".categories_picker").on('change', function () {
      $.post('{{ path_for('ProductUpdate',{'id':product.id} ) }}', {
            type: 'categories',
            categories: $(this).val(),
            _METHOD: 'PUT'
          })
        .done(function (data) {
          toastr.info('Categorieen zijn opgeslagen');
        });
    });

    $(".webshops_picker").on('change', function () {
      $.post('{{ path_for('ProductUpdate',{'id':product.id}) }}', {
            type: 'shops',
            shops: $(this).val(),
            _METHOD: 'PUT'
          })
        .done(function (data) {
          toastr.info('Webshops zijn opgeslagen');
        });
    });

    /*  $(".product-information").on('focus', function () {
          field_current = $(this).val();
      });
      $(".product-information").on('blur', function () {
          var field_id = $(this).data('mappingid');
          var field_contents = $(this).val();

          if (field_contents == field_current)
              return;

          $.post('{{ path_for('ProductUpdate',{'id':product.id} ) }}', {
              type: 'contents',
              field_id: field_id,
              value: $(this).val(),
              _METHOD: 'PUT'
          })
              .done(function (data) {
                  toastr.info('Aanpassing is opgeslagen');
              });
      });*/
  });

/**************************************************************************************************************************************************
 ******************************************************************( Childs Part )*****************************************************************
 **************************************************************************************************************************************************/
function removeType(id) {
  axios.delete("{{ path_for('Product.ProductChildsDelete')}}", {
      data: {
        'product_id': {{product.id}},
        'id': id,
      }
    })
    .then(function (response) {
      if (response.data.status == 'true') {
        toastr.success(response.data.msg);
        $('#main_connections_tab_table').DataTable().ajax.reload();
        $('#main_generate_tab_table').DataTable().ajax.reload();
      } else {
        console.log(response);
        toastr.warning(response.data.msg);
      }
    })
}
function add_child_func(thisId,typeID, brandID){
    if (document.getElementById(thisId).checked) 
    {
      return axios.post("{{ path_for('Product.ProductChildsUpdate')}}", {
        'brandID': brandID,
        'product_id': {{product.id}},
        'typeID': typeID,
        '_METHOD': 'POST'
      })
      .then(function (response) {
        if (response.data.status == 'true') {
          toastr.success(response.data.msg);
          $('#main_connections_tab_table').DataTable().ajax.reload();
          $('#main_generate_tab_table').DataTable().ajax.reload();
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      })
    } else {
        axios.delete("{{ path_for('Product.ProductChildsDelete')}}", {
        data: {
          'product_id': {{product.id}},
          'type': 'type',
          'id': typeID,
        }
      })
      .then(function (response) {
        if (response.data.status == 'true') {
          toastr.success(response.data.msg);
          $('#main_connections_tab_table').DataTable().ajax.reload();
          $('#main_generate_tab_table').DataTable().ajax.reload();
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      })
    }
    
}

async function format(d) {
  console.log(d);
    try {
        var dataTable='';
       let res = await axios.get("{{ path_for('Product.ProductGetChildrenEan')}}" + "?product_id={{product.id}}&type_id="+ d.type_id)
        .then(function (response) {
            if (response.data.status == 'true') {
                //console.log(response.data);
                dataTable=response.data.data;
                if(dataTable != ''){
                    var tableData = '<table class="table table-warning">';
                        dataTable.forEach(function(ean){
                        tableData +='<tr>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td>variatie:<b class="ml-3">'+ean.varation_name+'</b></td>' +
                        '<td>sub variatie:<b class="ml-3">'+ean.varation_sub_name+'</b></td>' +
                        '<td>EAN:<b class="ml-3">'+ean.EAN+'</b></td>' +
                        '</tr>';
                        });
                        tableData +='</table>';
                        return tableData;
                }else{
                    return '<table class="table table-warning">'+
                        '<tr>' +
                        '<td colspan="5">Geen Ean</td>' +
                        '</tr>'+
                        '</table>';
                }
            } else {
                console.log(response);
                toastr.warning(response.data.msg);
                return '<table class="table table-warning">'+
                        '<tr>' +
                        '<td colspan="5">Error</td>' +
                        '</tr>'+
                        '</table>';
            }
        });
        return res;
    }
    catch (err) {
        console.error(err);
    }
}

$(document).ready(function () {
  $('a[data-toggletab="main_connections_tab"]').on('shown.bs.tab', function (e) {
    main_connections_tab_table_function();
  });
  main_connections_tab_table = '';

  function main_connections_tab_table_function() {
    if (!$.fn.DataTable.isDataTable('#main_connections_tab_table')) {
      main_connections_tab_table = $('#main_connections_tab_table').DataTable({
        ajax: {
          url: '{{ path_for('ProductGetChildren',{'id':product.id} ) }}',
          complete: function (data, textStatus, jqXHR) {
          }
        },
        language: {
          url: "/assets/js/datatable-langauge.json",
        },
        pageLength: 20,
        responsive: 'true',
        columns: [
          {
            "data": null,
            "orderable": false,
            "className": 'details-control',
            "render": function (data, type, row) {
              return (
                '<div class="text-center show-row-details"><span class="text-success font-20 hand"><i class="dripicons-plus"></i></span></div>'+
                '<div class="text-center hide-row-details" style="display:none"><span class="text-warning font-20 hand"><i class="dripicons-minus"></i></span></div>'
              );
            }
          },
          {
            "data": "id"
          },
          {
            "data": "brand"
          },
          {
            "data": "type"
          },
          {
            "data": null,
            "orderable": false,
            className: "text-center",
            "render": function (data, type, row) {
              return (
                '<span  class="action-icon hand remove_type" onclick="removeType(' + row.id + ')" data-toggle="tooltip" data-placement="top" data-original-title="Ontkoppelen"><i class="mdi mdi-link-variant-off"></i></span>'
              );
            }
          }
        ],
         order: [
          [2, 'asc'],[ 3, 'asc' ]
        ],
      });
      main_connections_tab_table.on( 'draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    }

  }

  // Add event listener for opening and closing details
  $('#main_connections_tab_table').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = main_connections_tab_table.row(tr);
    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('shown');
      $(this).find('.hide-row-details').hide();
      $(this).find('.show-row-details').show();
    } else {
      format(row.data()).then(res => {
        row.child(res).show();
        tr.addClass('shown');
      });
      $(this).find('.show-row-details').hide();
      $(this).find('.hide-row-details').show();
    }
  });


  $('#add_child').on('click', function (e) {
    e.preventDefault();
    var brandID = $('#select_brand').val();
    var typeID = $('#select_type').val();

    axios.post("{{ path_for('Product.ProductChildsUpdate')}}", {
        'brandID': brandID,
        'product_id': {{product.id}},
        'typeID': typeID,
        '_METHOD': 'POST'
      })
      .then(function (response) {
        if (response.data.status == 'true') {
          toastr.success(response.data.msg);
          main_connections_tab_table.ajax.reload();
          $('select#select_type').html('<option value="" selected>Type...</option>');
          $("#select_brand").val($("#select_brand option:first").val());
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      })
  });

  //Select types in brands
  $('#select_brand').on('change', function () {
    $('#select_type').html("<option value=''>Type...</option>");
    var brandID = $('#select_brand').val();
    axios.get("{{path_for('Types.GetTypesInBrand')}}" + "?brand_id=" + brandID)
      .then(function (response) {
        if (response.data.status == 'true') {
          $('#select_type').empty();
          var options = '<option value="0">Type...</option>';
          for (var x = 0; x < response.data.types.length; x++) {
            options += '<option value="' + response.data.types[x]['id'] + '">' + response.data.types[x]['name'] + '</option>';
          }
          $('select#select_type').html(options);
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      });
  });

  $('a[data-toggletab="main_generate_tab"]').on('shown.bs.tab', function (e) {
    main_generate_tab_table_function();
  });
  var main_generate_tab_table = '';

  function main_generate_tab_table_function() {
    if (!$.fn.DataTable.isDataTable('#main_generate_tab_table')) {
      main_generate_tab_table = $('#main_generate_tab_table').DataTable({
        ajax: {
          url: '{{ path_for('ProductGetTypesGenerate',{'id':product.id} ) }}',
          complete: function (data, textStatus, jqXHR) {}
        },
        language: {
          url: "/assets/js/datatable-langauge.json",
        },
        columns: [
          { orderable: true, data: "brand" },
          { orderable: true, data: "name" },
          {orderable: true,data: "kb"},
          {
            data: 'yes',
            orderable: false,
            className: "text-center",
            render: function(data,type,row){
                if(data==0){
                    return '<input type="checkbox" id="type_'+row.id+'_'+row.brand_id+'"  data-switch="bool" onclick="add_child_func(this.id,' + row.id + ',' + row.brand_id + ')"/><label for="type_'+row.id+'_'+row.brand_id+'" data-on-label="Ja" data-off-label="nee"></label>';
                }else{
                    return '<input type="checkbox" id="type_'+row.id+'_'+row.brand_id+'" checked data-switch="bool" onclick="add_child_func(this.id,' + row.id + ',' + row.brand_id + ')"/><label for="type_'+row.id+'_'+row.brand_id+'" data-on-label="Ja" data-off-label="nee"></label>';
                }
            }
          }
        ],
         order: [
           [0, 'asc'],[ 1, 'asc' ]
        ],
      });
    }
  }




  /**************************************************************************************************************************************************
   ************************************************************( Update  price by shop )*************************************************************
   **************************************************************************************************************************************************/
  //Update price by shop
  $('.update-price-btn ').on('click', function (e) {
    e.preventDefault();
    var shopId = $(this).data("id");
    var productId = "{{ product.id }}";
    var data = {};

    $("input[data-shopid=" + shopId + "]").each(function () {
      data[$(this).data('mappingid')] = $(this).val();
    });
    axios.post('{{ path_for('ProductPriceUpdate')}}', {
          'prices': data,
          'shopId': shopId,
          'productId': productId,
          '_METHOD': 'PUT'
        })
      .then(function (response) {
        if (response.data.status == 'true') {
          toastr.success(response.data.msg);
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      })
  });


  /**************************************************************************************************************************************************
   ************************************************************( Update measurements )***************************************************************
   **************************************************************************************************************************************************/
  $('.update-measurements-btn').on('click', function (e) {
    e.preventDefault();
    var productId = "{{ product.id }}";
    var data = {};

    $("input[data-mapping=measurements]").each(function () {
      data[$(this).data('mappingkey')] = $(this).val();
    });

    axios.post('{{ path_for('ProductMeasurementsUpdate') }}', {
          'measurements': data,
          'productId': productId,
          '_METHOD': 'PUT'
        })
      .then(function (response) {
        if (response.data.status == 'true') {
          toastr.success(response.data.msg);
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      })
  });

  /**************************************************************************************************************************************************
   ****************************************************************( Update Other )******************************************************************
   **************************************************************************************************************************************************/

  //Update Other  Product Info
  $('.update-other-info-btn').on('click', function (e) {
    e.preventDefault();
    var productId = "{{ product.id }}";
    var data = {};

    $("input[data-mapping=other-info],textarea[data-mapping=other-info]").each(function () {
      if ($(this).is(':checkbox')) {
        if ($(this).prop('checked')) {
          data[$(this).data('mappingkey')] = 1;
        } else {
          data[$(this).data('mappingkey')] = 0;
        }
      } else {
        data[$(this).data('mappingkey')] = $(this).val();
      }
    });

    $("select[data-mapping=other-info]").each(function () {
      var val = $("option:selected", this).val();
      data[$(this).data('mappingkey')] = val;
    });

    axios.post('{{ path_for('ProductOtherInfoUpdate') }}', {
          'otherInfo': data,
          'productId': productId,
          '_METHOD': 'PUT'
        })
      .then(function (response) {
        if (response.data.status == 'true') {
          toastr.success(response.data.msg);
        } else {
          console.log(response);
          toastr.warning(response.data.msg);
        }
      })

  });
}); 
</script>


<!--
/**************************************************************************************************************************************************
 ************************************************************( Variations App )********************************************************************
 **************************************************************************************************************************************************/
-->
<script type = "text/javascript" >
  var app_variations = new Vue({
    delimiters: ['${', '}'],
    el: "#main_information_variations",
    data: {
      product_id: '{{product.id}}',
      update_url: "{{ path_for('ProductVariations.Post') }}"
    }
  }); 
  </script> 
<!--
/**************************************************************************************************************************************************
 ************************************************************( product urls App )******************************************************************
 **************************************************************************************************************************************************/

descr
descr_long_uni
descr_long
seo_title
seo_kw
seo_meta
seo_title_bs
seo_meta_bs
seo_h1_uni
seo_h2_uni
seo_h1
seo_h2
kenmerk_1
kenmerk_2
kenmerk_3
kenmerk_4
kenmerk_5


bol_com_title
bol_com_descr
bol_com_descr_uni

-->
<script type = "text/javascript" >
  var app_variations = new Vue({
    el: "#product-urls",
    data: {
      shopDomain: "{{shops[0].domain}}",
      product_id: "{{product.id}}",
      update_url: "{{path_for('Product.UrlsUpdate')}}",
      get_url: "{{path_for('Product.UrlsGet')}}",
    },
  }); 
  </script>



<script type = "text/javascript" >
  var app_combos = new Vue({
    el: "#combo_app",
    data: {
      shopDomain: "{{shops[0].domain}}",
      product_id: "{{product.id}}",
      update_url: "{{path_for('Product.CombosUpdate')}}",
      get_url: "{{path_for('Product.CombosGet')}}",
      lang: "{{ language }}",
      delete_url: "{{path_for('Product.CombosDelete')}}"
    },
  }); 
  </script>

<!--

/**************************************************************************************************************************************************
 *********************************************************( Characteristics App )******************************************************************
 **************************************************************************************************************************************************/
-->
<script type = "text/javascript" >
  var app_caracteristics = new Vue({
    delimiters: ['${', '}'],
    el: "#caracteristics-app",
    data: {
      characteristics: []
    },
    mounted: function () {
      {%for key, characteristic in product.contents.characteristics %}
      this.characteristics.push({
        id: '{{ key }}',
        value: '{{ characteristic }}',
      }); 
      {% endfor %}
    },
  }); 
  </script>
<!--
/**************************************************************************************************************************************************
 ****************************************************************( b2b part )**********************************************************************
 **************************************************************************************************************************************************/
-->

<script type = "text/javascript">
  //OrderRows Apps
  $(document).ready(function () {
    $(".update-b2b-information-btn").click(function (event) {
      event.preventDefault();
      var latest_bought_price = $('#latest_bought_price').val();
      var remarks = $('textarea#remarks').val();
      axios.post('{{ path_for('Product.B2bUpdate')}}', 
      {product_id: {{product.id}},
            latest_bought_price: latest_bought_price,
            remarks: remarks
          })
        .then(function (response) {
          if (response.data.status == 'true') {
            toastr.success(response.data.msg);
          } else {
            toastr.warning(response.data.msg);
          }
        })
        .catch(function (error) {
          toastr.warning(error);
        });
    });
  }); 

  $(document).ready(function () {
    $(".datepicker").datepicker({
      startDate: "today",
      format: "yyyy-mm-dd",
    });

    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    {% if  product.delivery_at %}
     var d = '{{product.delivery_at}}';
      d = d.split(' ')[0];
      $(".datepicker").datepicker("setDate", d);
     {% else %}
       $(".datepicker").datepicker("setDate", today);
    {% endif %}
    
  }); 
  </script>
  