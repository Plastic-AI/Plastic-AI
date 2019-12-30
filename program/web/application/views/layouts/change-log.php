<div class="card shadow-base bd-0">
	<div class="card-header bd-gray-200 mg-t-auto">
		<span>Logs</span>
	</div>
	<div class="card-body pd-0">
		<div class="col-md-12 bd-b d-xs-flex align-items-center justify-content-start pd-15">
			<span class="col time">Date</span>
			<span class="col created-by">By</span>
			<span class="col text-right ip-address">IP Address</span>
		</div>
		<div class="ht-300 pos-relative">
			<div id="container-log" class="overflow-y-auto">
			</div>
		</div>
	</div>
</div>

<div id="elm-log-row" style="display:none;">
	<span class="col time"></span>
	<span class="col created-by"></span>
	<span class="col text-right ip-address"></span>
</div>

<script type="text/javascript">
function get_action_log() {
	var query = {
		url 	: '<?php print str_replace($this->controller, 'req_' . $this->controller, uri_string());?>',
	};

	ajax_get('req_log/get_by_controller_action', query, function(result) {
		$('#container-log').empty();
		$.each(result.data, function(key, value) {
			elm =  $('#elm-log-row').clone(true);
			elm.attr({style:'', id:'log-' + value.id, class:'log-row bd-b d-xs-flex align-items-center justify-content-start pd-15'});
			elm.find('.created-by').text(value.created_by);
			elm.find('.time').text(value.created);
			elm.find('.ip-address').text(value.ip);
			elm.appendTo('#container-log');
		});
	});
}
</script>