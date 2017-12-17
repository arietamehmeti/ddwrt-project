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

	 function insertRouter(router_type){			

		router_id = $("#change_form").attr("name");
		router_request = "insert";

		parseRequestData(router_type, router_id, router_request);
		
	}

	 function editRouter(router_type){			

		router_id = $("#change_form").attr("name");
		router_request = "update";

		var data = parseRequestData(router_type, router_id, router_request);
		
	}

	function parseRequestData(router_type, router_id, router_request){

		data = {};
		var us = "_";
		var mysql_request = router_request;
		var ip;

		request_str = mysql_request + us + router_type;

		data.request = request_str;		

		ip= $("#ip_router").val();		

		data.ip = ip;
		data.id = router_id;
		alert("the id is" + data.id);

		var router_to_traverse = routers.router_type;

		router_info = main_routers_array.forEach(function(element){
				if(element.id == router_id){
					return element;
				}
			});

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
			    async: true,
			    data: data,
			    	    success: function (response) { 	

			    	    	alert(response);
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

	function edit_main_router(element){

		change_button_attr("edit", "main_router");

		$("#ip_router").val($(element).data('ip'));

		var router_info = getRouterById($(element).data('id'));

		addInUseToModal(router_info.in_use);

		$( "#change_form" ).on( "submit", function() {
		  	editRouter("main_router");
		  	// resetModalData();
		});			

		$( "#change_form" ).prop("name", $(element).data('id'));
	}

	function edit_router(element){

		change_button_attr("edit", "router");

		$("#ip_router").val($(element).data('ip'));

		var router_info = getRouterById($(element).data('id'));

		addOptionsToModal(router_info.main_router_id);

		$( "#change_form" ).on( "submit", function() {
		  	editRouter("router");
		  	// resetModalData();
		});

		$("#change_form").prop("name",  $(element).data('id'));
	}

	function add_main_router(element){

		change_button_attr("add", "main_router");

		var router_info = getRouterById($(element).data('id'));	

		addInUseToModal(router_info.in_use);

		$( "#change_form" ).on( "submit", function() {
		  	insertRouter("main_router");
		  	// resetModalData();
		});
	}

	function add_router(){

		change_button_attr("add", "router");
		addOptionsToModal();

		$( "#change_form" ).on( "submit", function() {
		  	insertRouter("router");
		  	// resetModalData();
		});		
	}

	function getRouterById(router_id){

		var result = "";

		main_routers_array.forEach(function(element){
			if(element.id== router_id)
				result = element;
		});	


		routers_array.forEach(function(element){
			if(element.id== router_id)
				result =  element;
		});

		return result;
	}

	function change_button_attr(btn_value, table_name){

		resetModalData();

		// $("#change_form").prop("onsubmit", btn_value + "Router('"+ table_name + "')");

		btn_value = btn_value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
		    return letter.toUpperCase();
		});

		$("#change_btn").html(btn_value);
	}

	var main_routers_array = <?php echo json_encode(next($main_routers)); ?>;
	var routers_array = <?php echo json_encode(next($routers)); ?>;

	var routers = {"main_router": main_routers_array, "router": routers_array};
	setNavigationPage("home", "../main_page.php");
	setNavigationPage("edit", "edit.php");
	setActive("home");

</script>
</body>
</html>