<script type="text/javascript">

	function addOptionsToModal(main_router_id){

		var label = document.createElement("label");
		
		$(label).attr("class", "form-control-label");
		$(label).text("Main Router");

		var select = document.createElement("select");
		$(select).prop('required',true);
		$(select).attr("id", "router_main_select");

		var option = document.createElement("option");

		$(option).val("-1");
		$(option).text("");

		$(select).append(option);

		main_routers_array.forEach(function(router) {    		
			var option = document.createElement("option");

				$(option).val(router.id);

			$(option).text(router.id + " - " +router.ip);		

			$(select).append(option);

			
			if(main_router_id == router.id){				
				$(select).val(router.id).change();
			}
		});
		
		var form_group = document.createElement("div");

		$(form_group).attr("class", "form-group");

		$(form_group).attr("id", "form_option_router");

		$(form_group).append(label);
		$(form_group).append("<br>");
		$(form_group).append(select);

		$(".modal-body").append(form_group);	

	}

	function addInUseToModal(is_in_use){

		var select = document.createElement("select");
		$(select).attr("id", "router_in_use");
		$(select).prop('required',true);

		var label = document.createElement("label");
		
		$(label).attr("class", "form-control-label");
		$(label).text("Set as Main Router");

		for(i=0; i<2; i++){

			var in_use_bool = !!+i;

			var option = document.createElement("option");

			$(option).val(i);
			$(option).text(in_use_bool);

			$(select).append(option);		

			if(i == is_in_use){				
				$(select).val(i).change();
			}
		}

		var option = document.createElement("option");

		var form_group = document.createElement("div");

		$(form_group).attr("class", "form-group");

		$(form_group).attr("id", "form_in_use_router");

		$(form_group).append(label);
		$(form_group).append("<br>");
		$(form_group).append(select);

		$(".modal-body").append(form_group);	

	}

	function resetModalData(){
		if("#form_option_router".length !== 0)
			$("#form_option_router").remove();

		if("#form_in_use_router".length !== 0)
			$("#form_in_use_router").remove();	

		$("#ip_router").val("");

	  	$("#change_form").off( "submit");
	  	// $("#change_form").prop( "onsubmit", null );
	  	// $("#change_form").removeAttr( "onsubmit");

		  	$("#change_form").prop( "submit", null );
	  	$("#change_form").removeAttr( "submit");
	}

</script>