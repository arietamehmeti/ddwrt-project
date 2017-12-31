<div class="modal fade" id="modal_router" tabindex="-1" role="dialog" aria-labelledby="router" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="router"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
    <form id="form_change" class="form-change" method="post">
		<div class="modal-body">        	
          	<div class="form-group">
				<label for="main_ip" class="form-control-label">IP Address</label>	          	
            	<input type="text" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control" id="ip_router" placeholder="Default Gateway IP Address" max="15" name="routers" min="1" required>
          	</div>
  		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<input type="submit" id="change_btn" class="btn btn-primary change-btn"></button>
      	</div>
    </form>
    
    </div>
  </div>
</div>

<script type="text/javascript">

	function addRouter(router_name){
		var ip_addr_el = '<label for="main_ip" class="form-control-label">IP Address</label><input type="text" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control" id="ip_router" placeholder="Default Gateway IP Address" max="15" name="routers" min="1" required>';

		$(".modal").attr('id', router_name);
		$(".modal-title").attr("id", "Add " + router_name);

		$(".modal-body").find("form").empty();

		$(".modal-footer").find().empty();
	}

	$('#modal_router').on('hidden.bs.modal', function (e) {
	  	resetModalData();
	});
</script>