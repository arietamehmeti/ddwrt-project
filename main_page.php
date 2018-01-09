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

<!-- 		<tr class="bg-primary">...</tr>
		<tr class="bg-success">...</tr>
		<tr class="bg-warning">...</tr>
		<tr class="bg-danger">...</tr>
		<tr class="bg-info">...</tr>	 -->	

		<script>
			
			setNavigationPage("home", "main_page.php");
			setNavigationPage("edit", "edit/edit.php");
			setActive("home");
		</script>

		<table class="table router_scaffold_table" style="display:none">
			<caption class="caption mb-5 pt-1">Router</caption>
			<tr>
				<th>IP</th>
				<th>WAN</th>
				<th>SSID</th>
				<th>Channel Bandwidth</th>				
				<th>Channel</th>
				<th>TX Power</th>
				<th>Connected Users</th>
			</tr>
			<tr>
				<td class="ip"></td>
				<td class="wan"></td>
				<td class="ssid"></td>
				<td class="channel_bandwidth"></td>				
				<td class="channel"></td>
				<td class="tx_power"></td>
				<td class="connected_users"></td>
			</tr>			
		</table>

		<form id="router_change_form" class="mt-5 mb-0">
			<table class="table" id="router_change_table"  style="display:none">
				<caption class="caption pt-1 pb-0">Router Changes</caption>
				<tr> 
					<th>Router</th>
					<th>SSID</th>
					<th>TX Power</th>
					<th>Channel</th>
				</tr>
				<tr>
					<td><select id="router_select"></select></td>
					<td><input type="text" id="input_ssid"  pattern="^[a-zA-Z0-9]{1,32}" name="SSID"></td>
					<td><input type="number" max="60" id="input_tx_power" min="5" name="TX Power"></td>
					<td><select id="channel_select">
					</select></td>
				</tr>			
			</table>	
			<input type="submit" class="btn mb-3 mt-0" name="Commit Changes">			
		</form>

		<table class="table my-5 table-striped" id="site_survey">
			<caption class="caption">Site Survey</caption>
			<tr>
		        <th>SSID</td>
				<th>BSSID</td>
		        <th>Channel</td>
		        <th>Frequency</td>
		        <th>RSSI</td>
		        <th>Noise</td>
		        <th>Beacon</td>
		        <th>CAP</th>
		        <th>DTIM</td>
		        <th>Rate</td>
		        <th>ENC</td>
			</tr>
			<tr id="survey_results">
				
			</tr>
		</table>	

	</div>

	<script>	

			function updateRouterValues(){

				var json_changes={}

				var router_value = $('#router_select').find(":selected").val();	
				var ssid_value = $('#input_ssid').val();
				var txpwr_value = $('#input_tx_power').val();
				var channel_value = channels[$('#channel_select').find(":selected").val()];		

	       		if(ssid_value != ""){
	       			json_changes.ssid = ssid_value;	
	       		}

	       		if(txpwr_value != ""){
	       			json_changes.tx_power = txpwr_value;	       			
	       		}

	       		if(channel_value != ""){
	       			json_changes.channel = channel_value;	       			
	       		}

		       	if(router_value == -1){

		       		if(json_changes.length == 0){
		       			alert("No input given!");
		       			return false;
		       		}
					
					for(var router_id in connections){
						sendRequest(connections[router_id], json_changes);
					}
				}
		        else{

		       		if(json_changes.length < 3){
		       			alert("Please fill all the inputs and submit again!");
		       			return false;
		       		}

		       		var router = connections[router_value];

		       		if(ssid_value === router.ssid){
		       			delete json_changes.ssid;		       			
		       		}												

		       		if(parseInt(txpwr_value) === parseInt(router.tx_power)){
		       			delete json_changes.tx_power;	       			
		       		}

		       		if(parseInt(channel_value) ===  parseInt(router.channel)){
		       			delete json_changes.channel;		       			
		       		}

					sendRequest(router, json_changes);	
		        }		       				 			        
        	}

			function sendRequest(router, json_changes){

				var data = {};
				data.request = "change";	

   				router_ip = router.ip;
		        router_id = router.id;

				data.json_changes = JSON.stringify(json_changes);
	       		data.router_ip = router_ip;
	       		data.router_id = router_id;				

				$.ajax({
					url: "router_requests.php",
				    type: "POST",
				    async: true,
				    data: data,
		    	    success:  function(response){
				       			updateRouter(response);
				       			cleanChanges();
				       		  }
				});
			}

			function getSiteSurvey(){

				var router_ip = main_router["ip"];
				var router_id = main_router["id"];

				$.ajax({
					url: "router_requests.php",
				    type: "POST",
				    async: true,
				    data: {
				    	request:"get_site_survey",
				    	router_ip:router_ip,
				    	router_id:router_id
						},
				    success:  function(response){
					    	try{
					    		site_survey_json  = JSON.parse(response);

				       			showSiteSurveyTable(site_survey_json);
					    	}
					    	catch(e){
					    		routerError(response);
					    	}

				       	}
				});
			}	
			
			function showSiteSurveyTable(survey_data){				
				survey_table = $("#site_survey");

				for(var wifi in survey_data){

					var current_site_info = survey_data[wifi];

					var tr =document.createElement("tr");

					for(var data in current_site_info){
						var td = document.createElement("td");	
						$(td).html(current_site_info[data]);
						tr.append(td);			
					}

					survey_table.append(tr);
				}

			}		

			function updateRouter(response){
				try{		
					alert(response);
					response_json = JSON.parse(response);

					var router_id = response_json.id;
					delete response_json.id;
					
					var router_info = connections[router_id];

					// alert(JSON.stringify(router_info));

					for(var key in response_json){		
						router_info[key] = response_json[key];
					}

					updateRouterTableInfo(router_info);
				}
				catch(e){
					alert("error" + e);
					routerError(response);
				}
			}

			function routerError(response){

				var div = document.createElement("div");
				$(div).attr("class", "alert alert-danger");

				var message = response;
			
				$(div).append(message);

				$(".container").append(div);
				// alert("Router " + router_info.ip + " failed due to:" + response);
			}

			function updateAllRouters(response){
				for(var router_info in connections){
					updateRouter(router_info,response);
				}
			}

			function updateRouterTableInfo(router_info){

				var router_table = $("#table_" + router_info.id);			

				for (var key in router_info) {					
					
					$(router_table).find("." + key).html(router_info[key]);
				    // $(td_results[i++]).html(router_info[key]); 
				    //Do something
				}
			}

		    function getValueFromKey(item, index) {
				if(item == this ){
					$("#channel_select").val(index).change();
			    }
			}       

			function showRouterInformation(router_info){

				var table = $(".router_scaffold_table").clone();

				$(table).prop("style","");
				$(table).prop("class", "table table-bordered");

				$(table).prop("id","table_" + router_info.id);			
				
				$(table).find("caption").html("Router " + router_info.ssid);
				$(table).find("caption").attr("class", "caption");

				var td_array  = $(table).find("td");
				for (var i=0; i<td_array.length; i++) {

					var td = td_array[i];
					var td_class= $(td_array[i]).attr("class");

				    	if(router_info[td_class].constructor === Array){

				    		var  conn_users = router_info[td_class];

				    		var users_str = "";

				    		for(var user in conn_users){		    			
				    			users_str += conn_users[user] + "\n";
				    			// arr.toString() can be used for showing, not sure if the line will become to long though.

				    		}

				    		users_str = users_str.substring(0, users_str.length -1);

				    		var pre = document.createElement("pre");
				    		$(pre).html(users_str);

				    		$(td).html(pre);

				    	}else{
				    		$(td).html(router_info[td_class]);
				    	}
				    }

					$(".container").append(table);				    
				}

			function capitalizeFirstLetter(word){
				return word.charAt(0).toUpperCase() + word.slice(1);;
			}


			function createChangeTable(){

				var table = $("#router_change_table");
				table.prop("style", "");

				// VALUES

				var select_router = $("#router_select");

				var i =1;

				var router_option = document.createElement("option");

				$(router_option).val("-1");
				$(router_option).html("All");
				$(router_option).attr("selected");
				$(select_router).append(router_option);

				// Channel changing options are being created;

				var channel_select = $("#channel_select");

				var option = document.createElement("option");
				$(option).attr("class", "channel_option");
				$(option).val(-1);
				$(option).text("");
				$(channel_select).append(option);

				for(var i =0; i<channels.length; i++){

					option = document.createElement("option");
					$(option).attr("class", "channel_option");
					$(option).val(i);
					$(option).text((i+1) + " - " + channels[i]);

					$(channel_select).append(option);
				}

				$("#router_change_form").on("submit",function(e){
					e.preventDefault();
					updateRouterValues();
				});
			}

			function getRouterInformation(router_info){

				var router_ip = router_info['ip'];
				var router_id = router_info['id'];
				$.ajax({
					url: "router_requests.php",
				    type: "POST",
				    async: true,
				    data: {request:"get_router_info", router_ip:router_ip, router_id, router_id},
		    	    success: function (response) {
					    try {
					    		parsed_response = JSON.parse(response);
								connections[router_id] = parsed_response;

			    				showRouterInformation(connections[router_id]);

			    				appendToRouterOptions(connections[router_id]);	    				  
					    } catch (e) {
					        routerError(response);
					    }		    	    	    	    	
				    }
				});		
			}

			function appendToRouterOptions(router_info){
				
					var option = document.createElement("option");

					$(option).val(router_info["id"]);
					$(option).html(router_info["ip"]);

					$("#router_select").append(option);
			}

			function parseTitle(title){
				
				var title_array = title.split("_");
				var parsed_title="";

				title_array.forEach(function(word){

					parsed_title += capitalizeFirstLetter(word);
					parsed_title +=" ";
				});

				return parsed_title;
			}

			$('#router_select').on('change', function() {	        	
				if(this.value != -1){

				var router_channel = connections[this.value].channel;
				$tx_power = connections[this.value].tx_power;
				$ssid_value = connections[this.value].ssid;


				$("#input_tx_power").val(parseInt($tx_power));	        
				$("#input_ssid").val($ssid_value); 	        	

				$(".channel_option").each(function(){
			    		var channel_index = $(this).val();

			    		if(channels[channel_index] == router_channel){
			    			$("#channel_select").val(channel_index).change();
			    		}
			    	});
					
				}else{
						cleanChanges();
				}

			});	
			function cleanChanges(){
					$("#channel_select").val(-1);
					$("#input_tx_power").val("");
					$("#input_ssid").val("");		
			}	

		    var all_value = -1;
		    var channels = <?php echo json_encode($channels); ?>;
		    var router_array = <?php echo json_encode($router_array); ?>;
		    var main_router = <?php echo json_encode($main_router); ?>;
		    var connections = {};

			createChangeTable();
			getSiteSurvey();
			if(router_array.length !== 0){
				for(var key in router_array){
					getRouterInformation(router_array[key]);
				}
			}else{
				routerError("There are no routers available!!");
			}

	</script>	
</body>
</html>