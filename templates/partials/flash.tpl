<div class="row">
	<div class="col-md-12">
		{% if flash.getMessage('success') %}
			<div class="alert alert-success mt-3 fade show">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<i class="dripicons-checkmark mr-2"></i>
				{{ flash.getMessage('success') | first }}
			</div>
		{% endif %}

		{% if flash.getMessage('warning') %}
			<div class="alert alert-warning mt-3 ml-3 mr-3">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<i class="dripicons-warning mr-2"></i>
				{{ flash.getMessage('warning') | first }}
			</div>

		{% endif %}

		{% if flash.getMessage('error') %}
			<div class="alert alert-danger mt-3 ml-3 mr-3">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<i class="dripicons-wrong mr-2"></i>
				{{ flash.getMessage('error') | first }}
			</div>
		{% endif %}

		{% if flash.getMessage('info') %}
			<div class="alert alert-info mt-3 ml-3 mr-3">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<i class="dripicons-information mr-2"></i>
				{{ flash.getMessage('info') | first }}
			</div>
		{% endif %}
		<div style="clear: both"></div>
		{% if status %}
			<div class="alert alert-success mt-3 ml-3 mr-3">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<ul>
					<li>{{status}}</li>
				</ul>
			</div>
		{% endif %}
		{% if errors %}
			<div class="alert alert-danger mt-3 ml-3 mr-3">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<ul>
					{% for error in errors %}
						<li>{{ error | first}}</li>
					{% endfor %}
				</ul>
			</div>
		{% endif %}
	</div>
</div>
