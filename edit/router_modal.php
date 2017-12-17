<div class="modal fade" id="modal_router" tabindex="-1" role="dialog" aria-labelledby="router" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="router"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
    <form id="change_form">
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

	// $('#exampleModal').on('show.bs.modal', function (event) {
	//   var button = $(event.relatedTarget) // Button that triggered the modal
	//   var recipient = button.data('whatever') // Extract info from data-* attributes
	//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	//   var modal = $(this)
	//   modal.find('.modal-title').text('New message to ' + recipient)
	//   modal.find('.modal-body input').val(recipient)
	// })	

	$('#modal_router').on('hidden.bs.modal', function (e) {
	  	resetModalData();
	});
</script>