{% extends "layouts/base.tpl" %}
{% block cssfiles_before %}{% endblock %}
{% block cssfiles %}{% endblock %}
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

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="row mb-2">
                    <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" id="print-div">
                        <h5 class="card-title bold">Vrachtbrief van {{ "now"|date("m/d/Y") }}</h5>
                        <table>
                            <tr>
                                <td valign=top>
                                    123BestDeal<br>Molenstraat 24<br>7491BG Delden
                                </td>
                                <td width=50>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;->
                                </td>
                                <td valign=top>
                                    Selektvracht<br>Atoomweg 30<br>3542AB Utrecht
                                </td>
                            </tr>
                        </table>
                        <h2 style="margin-top:50px;">Totaal colli: {{records | length}} stuks</h2>
                        <table width=100% style="margin-top:20px;">
                            <tr>
                                <td>
                                </td>
                                <td>
                                    Order
                                </td>
                                <td>
                                    Barcode
                                </td>
                                <td>
                                    PC
                                </td>
                                <td>
                                    Huisnummer
                                </td>
                                <td>
                                    toevoeging
                                </td>
                                <td>
                                    Status
                                </td>
                                <td>
                                    Pakketsoort
                                </td>
                            </tr>
                            {% set i = 1 %}
                            {% set waybill_error = false %}
                            {% for record in records %}
                            <tr>
                                <td> {{ loop.index }} </td>
                                <td>{{record.order_id}}</td>
                                <td>{{record.barcode}}</td>
                                <td>{{record.order.order_details.address.shipping.zipcode}}</td>
                                <td>{{record.order.order_details.address.shipping.houseNumber}}</td>
                                <td>{{record.order.order_details.address.shipping.houseNumberSupplement}}</td>
                                <td>
                                    {% if record.order.status_id == 2 %}
                                    <font color=red>Nog niet gescand</font>
                                    {% set waybill_error = true %}
                                    {% else %}
                                    In vracht
                                    {% endif %}
                                </td>
                                <td>{{record.packagetype}}</td>
                            </tr>
                            {%endfor%}
                        </table>
                        {% if waybill_error == false %}
                        <br>
                        <input type="button" onclick="shipment();" id="btn_1" style="" class="btn btn-primary mt-3"
                            value="Pakbon afsluiten en pakketten aanmelden bij Selektvracht">
                        {% else %}
                        <br>
                        <input type="button" onclick="" id="btn_1" style="height: 30px;" value="Controleer
                            bovenstaande fouten, pakbon kan niet afgesloten worden" disabled="true">
                        {% endif %}
                    </div>
                </div>
            </div>
					</div>
				</div>
				<!-- end card-body-->
			</div>
			<!-- end card-->
		</div>
		<!-- end col -->
	</div>
	<!-- end row -->
{% endblock %}
{% block javascript %}
<script>
    function shipment() {
        var b = document.getElementById('btn_1');
        b.style.display = 'none';

        var mywindow = window.open('', 'PRINT', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Pakbon print</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(document.getElementById('print-div').innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        if (confirm('Is het afdrukken gelukt?')) {
            axios.get("{{path_for('Orders.Dhl.Notify')}}")
                .then(function (response) {
                    if (response.data.status == 'true') {
                        toastr.success('Upload Succesvol <br> Je kunt dit venster sluiten'); 
                    } else if (response.data.status == 'false') {
                        toastr.info(response.data.msg);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
        var b = document.getElementById('btn_1');
            b.style.display = 'block';
        return true;
    }
</script>
{% endblock %}

