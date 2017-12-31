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
	<?php
			include("resources.php");
			include("navigation_bar.php");
	?>

	<div class="container">

		<?php
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

				    	    	// updateRouterTableInfo(response);	
				    	    	alert("the success of the change is " + response);	    	    	
				    }
				});
			}

			function updateRouterTableInfo(router_info){
				var i =0;

				var td_results = $("#table_" + router_id).find("td");

		        var router_info_arr = Object.keys(single_connect).map(function(k) { return single_connect[k] });
		        var i=0;

		        td_arr_length = td_results.length;
				for (var i = 0; i < td_arr_length; i++) {
				    $(td_results[i]).html(router_info_arr[i]); 
				    //Do something
				}
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

	        var key = 64;
	        var single_connect = connections[key];

	        var connections_arr = Object.keys(single_connect).map(function(k) { return single_connect[k] });

	        // alert(JSON.stringify(connections_arr));
	        // var routers = {"main_router": main_routers_array, "router": routers_array};

			// for (var k in target){
			//     if (target.hasOwnProperty(k)) {
			//          alert("Key is " + k + ", value is" + target[k]);
			//     }
			// }
		
	</script>	
</body>
</html>