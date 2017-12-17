<div class="modal fade" id="modal_router" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
				<label for="main_ip" class="form-control-label">IP Address</label>	          	
            <input type="text" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control" id="ip_router" placeholder="Default Gateway IP Address" max="15" name="routers" min="1" required>
          </div>    
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="editRouter()" class="btn btn-primary">Edit</button>
      </div>
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




	// 	var modal_fade_div = document.createElement("div");
	// 	modal_fade.setAttribute("id", "modal_" + router_name);
	// 	modal_fade.setAttribute("tabindex", "-1");
	// 	modal_fade.setAttribute("role", "dialog");
	// 	modal_fade.setAttribute("aria-labelledby", "exampleModalLabel");
	// 	modal_fade.setAttribute("aria-hidden", "true");


	// 	var modal_dialog_div = document.createElement("div");
	// 	modal_fade.setAttribute("class", "modal_dialog");
	// 	modal_fade.setAttribute("role", "document");

	// 			var modal_dialog_div = document.createElement("div");
	// 	modal_fade.setAttribute("class", "modal_dialog");
	// 	modal_fade.setAttribute("role", "document");
	// }

	// $('#modal_router').on('show.bs.modal', function (event) {
	//   var button = $(event.relatedTarget); // Button that triggered the modal
	//   var recipient = button.data('whatever');

	//   // Extract info from data-* attributes
	//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	//   var modal = $(this);
	//   modal.find('.modal-title').text('New message to ' + recipient);
	//   modal.find('#ip_router').val(recipient);
	// });
</script>