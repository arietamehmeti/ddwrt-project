<!DOCTYPE html>
<html>
  <head>
    <title>Edit Routers</title>

	<?php

		if (session_status() == PHP_SESSION_NONE) {
	    session_start();
		}

		$base_path = $_SESSION['base_path'];
		$base_path_url = $_SESSION['base_path_url'];

		include($base_path. "/includes/header_resources.php");
		echo '<link rel="stylesheet" href="' .$base_path_url .'css/main_page.css">';
	 ?>

  </head>
<body>
	<div class="container">
		<?php 
		require $base_path ."/includes/bootstrap_resources.php"; 
		require $base_path ."/includes/navigation_bar.php";
		require "router_modal.php";
		require "main_router_modal.php";				
		require $base_path ."/db/queries.php";
		require "edit_tables.php";
		?>
	</div>

<script>

	 function insert_router(router_type){			

		router_id = $("#"+ getCurrentEventName()). attr("name");
		router_request = "insert";

		parseRequestData(router_type, router_request);
	}

	 function update_router(router_type){			

		router_id = $("#"+ getCurrentEventName()).attr("name");
		router_request = "update";

		var data = parseRequestData(router_type, router_request);
		
	}

	 function insert_main_router(router_type){			

		router_id = $("#"+ getCurrentEventName()).attr("name");
		router_request = "insert";

		parseRequestData(router_type,router_request);
	}

	 function update_main_router(router_type){			

		router_id = $("#"+ getCurrentEventName()).attr("name");
		router_request = "update";

		var data = parseRequestData(router_type, router_request);
		
	}	

	function parseRequestData(router_type, router_request){

		data = {};
		var us = "_";
		var mysql_request = router_request;
		var ip;

		request_str = mysql_request + us + router_type;

		data.request = request_str;		

		ip= $("#ip_router").val();	

		data.ip = ip;
		data.id = $(".form-change").attr("name");

		var router_to_traverse = routers.router_type;

			switch (router_type){
			    case "main_router":
				    	data.in_use = $("#router_in_use").val();
			        break;
			    case "router":
			        data.main_router_id = $("#router_main_select").val();
			        break;
			}
			
			$.ajax({
				url: base_path_url + "db/queries.php",
			    type: "POST",
			    async: false,
			    data: data,
			    	    success: function (response) { 	
			    	    	console.log("response" + response);
			    	    	location.reload(true);

					},
				error: function (response) {
			    	    alert(response);
			    	    location.reload(true);    	    
			    }
			    });

	}

	function checkRouters(elem, table_name){
		var id = $(elem).attr("id");
	
		if($(elem).attr("id") == "checkbox_all_" + table_name){
			$("#table_" + table_name + "input[type=checkbox] ").each(function(elemment) {
				element.checked;
        });

		}
	}

	function deleteButton(router_type){

		var router_id_array = [];

		$('.checkbox_' + router_type).each(function () {
			var checkbox = $(this);

			if(checkbox.prop("checked")){
				router_id_array.push(checkbox.val());			
			}

		});

		$.ajax({
			url: base_path_url + "db/queries.php",
		    type: "POST",
		    async: true,
		    data: {request: 'deleteRouter', router_id_array: router_id_array, router_type: router_type},
		    	    success: function (response) {
		    	    
		    	    console.log(response);
		    	    location.reload();		    	    
		    },
			error: function (response) {
		    	    
		    	    console.log(response);
		    	    location.reload();		    	    
		    }		    
		});	
	}
	
	function checkAll(element, table_name){

		$('.checkbox_' + table_name).each(function () {
					var checkbox = $(this);
					checkbox.prop("checked", $(element).prop("checked"));
		});
	}


	var main_routers_array = <?php echo json_encode(next($main_routers)); ?>;
	var routers_array = <?php echo json_encode(next($routers)); ?>;
	var router_event_listener= "";
	var routers = {"main_router": main_routers_array, "router": routers_array};
	var base_path = <?php echo json_encode($base_path) ?>;
	var base_path_url = <?php echo json_encode($base_path_url) ?>;

</script>
</body>
</html>