{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}{% endblock %}
{% block cssfiles %}
	<!-- third party css -->
	<link href="/dist/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
	<link
	href="/dist/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
{% endblock %}
{% block page_title %}
	{{page_title}}
{% endblock %}
{% block content %}
	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box">
				<div class="page-title-right">
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item">
							<a href="{{path_for('home')}}">Home</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{path_for('OrdersIndex')}}">Orders</a>
						</li>
						<li class="breadcrumb-item active">{{page_title}}</li>
					</ol>
				</div>
				<h4 class="page-title">{{page_title}}</h4>
			</div>
		</div>
	</div>
	<!-- end page title -->
    <ul class="nav nav-pills bg-nav-pills nav-justified">
		<li class="nav-item">
			<a class="nav-link active" data-toggletab="general" data-toggle="tab" href="#general" role="tab">Algemeen</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="customer" data-toggle="tab" href="#customer" role="tab">Klant</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggletab="history" data-toggle="tab" href="#history" role="tab">Historie (<b>{{ activities | length }}</b>) </a>
		</li>
        <li class="nav-item">
			<a class="nav-link" data-toggletab="old_orders" data-toggle="tab" href="#old_orders" role="tab">Klant bestellingen (<b>{{ order.clinetsOrders | length }}</b>)</a>
		</li>
	</ul>
	<!-- Tab panels -->
	<div
		class="tab-content">
         <!-- .tab-pane general -->
        {% include 'orders/single/tabs/general.tpl' %}
         <!-- .tab-pane customer -->
        {% include 'orders/single/tabs/customer.tpl' %}
	</div>

{% endblock %}
{% block javascript %}
    <!-- third party js --> 
    <script src="/assets/js/accounting.min.js"></script>
	<script src="/dist/assets/js/vendor/jquery.dataTables.min.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.bootstrap4.js"></script>
	<script src="/dist/assets/js/vendor/dataTables.responsive.min.js"></script>
	<script src="/dist/assets/js/vendor/responsive.bootstrap4.min.js"></script>
	<!-- specific page js file -->
    <script>
        var orderId = {{order.id}};
    </script>
    <script src="/assets/js/pages/orders/single/index.js"></script>
	<script src="/assets/js/pages/orders/single/edit.js"></script>
    	
    
    <script type="text/javascript">
    //OrderRows Apps
        var orderRows = new Vue({
            delimiters: ['${', '}'],
            el: "#orderRows",
            data: {
                rows: [],
                product: [],
                price: [],
                products: [],
                shop_id: '',
                url: '{{ path_for('OrdersOrderItemsPostDeleteSingle')}}',
                IMAGE_PATH: "{{ IMAGE_PATH }}",
                attributes:[],   
                color:'',
                size:'',
                color_id:'',
                size_id:{{ order.shop_id }},
                order_id:{{ order.id }}
            },

            mounted: function() {
                {% for order_item in order.order_items %}
                    this.color='';
                    this.color_id='';
                    this.size='';
                    this.size_id='';
                    {% for attribute in order_item.attributes %}
                        {% if attribute.attribute_group_id==1 %}
                            this.color="{{ attribute.title}}";
                            this.color_id="{{ attribute.id}}";
                        {%elseif attribute.attribute_group_id==2%}
                            this.size="{{ attribute.title}}";
                            this.size_id="{{ attribute.id}}";
                        {%endif%}
                        this.attributes.push({
                            id: "{{ attribute.id }}",
                            order_item_id: "{{ attribute.order_item_id }}",
                            attribute_id: "{{ attribute.attribute_id }}",
                            attribute_group_id: "{{attribute.attribute_group_id}}",
                            title: "{{ attribute.title}}",
                        });
                    {% endfor %}
                    this.rows.push({
                        order_item_id: "{{ order_item.id }}",
                        count: "{{ order_item.count }}",
                        product_id: "{{ order_item.product_id }}",
                        product_name: "{{order_item.product_name | raw}}",
                        price: "{{ order_item.price}}",
                        discount: "",
                        url: "https://www.123bestdeal.nl/{{ order_item.product_id }}",
                        img: "{% if order_item.product.images | first.url %}{{ IMAGE_PATH }}/{{ order_item.product.images |first.url }}{% else %}{% endif %}",
                        colors: [],
                        sizes: [],
                        attributes:this.attributes,
                        sku: "{{ order_item.product.sku }}",
                        query: "{{order_item.product_name | raw}}",
                        color: this.color,
                        color_id: this.color_id,
                        size:this.size,
                        size_id:this.size_id,
                        combo_check: "{{ order_item.combo }}"
                    });
                {% endfor %}

                {% for product in products %}
                this.products.push({
                    id: "{{ product.id }}",
                    sku: "{{ product.sku }}",
                }); {% endfor %}
                this.shop_id = "{{ order.shop_id }}";
            }
        });
		new Vue({
			el: '#app_notes',
			data: {
			notes: [],
			user_id: '{{ auth.user.id }}',
			users: [],
			order_id: '{{ order.id }}',
			errors: null,
			url: '{{ path_for('notes.note.add') }}'
			},
			mounted: function (){
				{% for note in order.notes %}
				var note = '{{ note.note |replace({"\n":'', "\r":''}) |raw }}';
				this.notes.push({
				message: note,
				user_id: '{{ note.user_from_name }}',
				user_id_to: '{{ note.user_to.name }}',
				order_id: '{{ note.order_id }}',
				created_at: '{{ note.created_at }}',
				updated_at: '{{ note.updated_at }}'
				});{% endfor %}

				{% for user in users %}
				this.users.push({id: '{{ user.id }}', name: '{{ user.name }}'});{% endfor %}
			}
			});
    </script>
{% endblock %}

