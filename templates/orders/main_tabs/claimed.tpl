<div class="tab-pane" id="claimedOrders">
    <div class="row">
        <div class="col text-right">
            <button id="print-picklist" class="btn btn-sm btn-primary float-right mb-3" onclick="printPickListNew({{ auth.user.id }})"><i class="glyphicon glyphicon-print"></i>&nbsp;Print picklijst</button>
        </div>
    </div>
	<div class="table-responsive">
		<table class="table w-100 dt-responsive nowrap font-13" id="all_claimed_orders">
			<thead class="thead-light text-sm">
				<tr>
					<th>Order</th>
					<th>Winkel</th>
					<th>Klant</th>
					<th><i class="dripicons-flag"></i></th>
					<th>Producten</th>
					<th>Geplaatst op</th>
					<th>
						<i class="fa fa-cog"></i>
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
