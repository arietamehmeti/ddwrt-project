<script type="text/javascript">
	var current_router = "form_change";

	function setModal(element){

		var us= "_";

		var request_type = $(element).data('request');
		var table_name = $(element).data('table');		

		var request_name = request_type + us + table_name;

		switch(request_name){
			case "update_main_router":
				var router_info = getRouterById($(element).data('id'));
				$("#ip_router").val($(element).data('ip'));
				addInUseToModal(router_info.in_use);
				break;
			case "insert_main_router":
				addInUseToModal();	
				break;
			case "insert_router":
				addOptionsToModal();
				break;							
			case "update_router":
				var router_info = getRouterById($(element).data('id'));	
				$("#ip_router").val($(element).data('ip'));
				addOptionsToModal(router_info.main_router_id);				
				break;
		}

		$("#form_change").on("submit",function(){
			window[request_name](table_name);
		});

		$("#form_change").attr("name", $(element).data('id'));

		setFormId(table_name);

		request_type = request_type.toLowerCase().replace(/\b[a-z]/g, function(letter) {
		    return letter.toUpperCase();
		});

		$("#change_btn").html(request_type);
	}		

	function setCurrentEventListener(listener_funct){
		current_router = listener_funct ;
	}

	function getCurrentEventName(){
		return current_router;
	}	

	function setFormId(form_id){
		$("#change_router").attr("id", form_id);
		current_id = form_id;
		current_router = form_id;
	}

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
	  	
	  	$("#" + current_router).off( "submit", current_router);

	  	$("#" + current_router).prop('id', "form_change");

	  	$("#form_change").off( "submit");

		$("#form_change").prop( "submit", null);
	  	$("#form_change").removeAttr( "submit");

	}

</script>