<!DOCTYPE html>
<html>
<head>
	
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
	<div class="container">

		<?php
			include("resources.php"); 
			include("navigation_bar.php");
			include("connection.php");
		?>

		<script>
			
			setNavigationPage("home", "main_page.php");
			setNavigationPage("edit", "edit/edit.php");
			setActive("home");
		</script>

	</div>

	<script>

			function createRequestData(request_str, change_value){

				var router_select = "router_select";
				var change_str = "change";
				var us = "_";
				var all_str = "all";
				var change_value_str = "change_value";
				var select_str = "select";
				var router_str = "router";

				var router_value = $('#' + router_str + us + select_str + us + request_str).find(":selected").val();		

			        var data = {};

			       	if(router_value == -1){

						var router_array_stringified = JSON.stringify(connections);

			       		data.request = change_str + us + all_str + us + request_str;	       		
			       		data.change_value = change_value;
			       		data.router_array = router_array_stringified;
			        }
			        else{

				        router_ip = router_array[router_value];

			       		data.request = change_str + us + request_str;
			       		data.change_value = change_value;
			       		data.router_ip = router_ip;
			       		data.router_id = router_value;

			        }

				$.ajax({
					url: "router_requests.php",
				    type: "POST",
				    async: true,
				    data: data,
				    	    success: function (response) { 		
				    	    	alert("the success of the change is " + response);	    	    	
				    }
				});	  			        

			}

		    function changeChannel(){

		    	var request_change ="channel";

		        var channel_value = $('#channel_select').find(":selected").val();
		        channel_frequency = channels[channel_value];

		        createRequestData(request_change, channel_frequency);
      
		    }

		    function changeSSID(){

				var request_change ="ssid";

		        var ssid_value = $('#input_ssid').val();

		        createRequestData(request_change, ssid_value);
	        
		    }

		    function changeTXPower(){
		    	var request_change= "tx_power";

		        var txpwr_value = $('#input_txpwr').val();

		        createRequestData(request_change, txpwr_value);
		    }        

			function changeInformation(){
				$.ajax({
					url: "router_request.php",
				    type: "POST",
				    async: true,
				    data: {request: 'changeTXPower', txpwr_value: txpwr_value, router_ip: router_ip, router_id: router_id},
				    	    success: function (response) { 		
				    	    	alert("the success of the change is " + response);	    	    	
				    }
				});	 
			}

	        function getValueFromKey(item, index) {
				if(item == this ){
					$("#channel_select").val(index).change();
			    }
			}

	        $('#router_select_channel').on('change', function() {
	        	if(this.value !== all_value){

	        		var router_channel = connections[this.value].channel;   	        		

		        	$(".channel_option").each(function(){
		        		var channel_index = $(this).val();

		        		if(channels[channel_index] == router_channel){
		        			$("#channel_select").val(channel_index).change();
		        		}
		        	});

	        	}
   
	        });

	        $('#router_select_ssid').on('change', function() {

	        	if(this.value !== all_value){

	        	$ssid_value = connections[this.value].ssid;

	        	$("#input_ssid").val($ssid_value);	        		        		
	        		
	        	}

	        });		

	        $('#router_select_tx_power').on('change', function() {

	        	if(this.value !== all_value){

	        	$ssid_value = connections[this.value].txpwr;

	        	$("#input_txpwr").val($ssid_value);	        		        		
	        		
	        	}

	        });

	        var all_value = -1;
	        var channels = <?php echo json_encode($channels); ?>;
	        var router_array = <?php echo json_encode($router_array); ?>;
	        var connections = <?php echo json_encode($connections); ?>;

	        var routers = {"main_router": main_routers_array, "router": routers_array};

		
	</script>	
</body>
</html>