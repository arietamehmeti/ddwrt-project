<!DOCTYPE html>
<html>
  <head>
    <title>Edit Routers</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
<body>
		<?php

		include("../resources.php");

		include("router_modal.php");

		include("main_router_modal.php");
	?>	

	<div class="container">
		<?php 
			include("../queries.php");
			include("../navigation_bar.php");		
			include("edit_tables.php");			
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
				url: "../queries.php",
			    type: "POST",
			    async: false,
			    data: data,
			    	    success: function (response) { 	
			    	    	console.log("response" + response);
			    	    	location.reload(true);

					},
				error: function (response) {
			    	    console.log(response);
			    	    location.reload();	    	    
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
			url: "../queries.php",
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

	function getRouterById(router_id){

		var result = "";

		main_routers_array.forEach(function(element){
			if(element.id== router_id){
				result = element;

				return result;
			}
		});	

		routers_array.forEach(function(element){
			if(element.id== router_id){

				result =  element;

				return result;
			}
		});

		return false;
	}


	var main_routers_array = <?php echo json_encode(next($main_routers)); ?>;
	var routers_array = <?php echo json_encode(next($routers)); ?>;
	var router_event_listener= "";

	var routers = {"main_router": main_routers_array, "router": routers_array};
	setNavigationPage("home", "../main_page.php");
	setNavigationPage("edit", "edit.php");
	setActive("home");

</script>
</body>
</html>