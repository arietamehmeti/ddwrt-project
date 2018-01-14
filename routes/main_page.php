<!DOCTYPE html>
<html>
<head>
	<title>DDWRT - Tables</title>
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
				require("connection.php");		
			?>

			<div id="overlay">		
			  <div class="progress" style="height: 30px;">
			    <div class="progress-bar progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
			    </div>
			</div>		
			</div>

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
				<input type="submit" id="submit" class="btn mb-3 mt-0" name="Commit Changes">			
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

			<table class="table" id="router_table">
				<caption class="caption mb-5 pt-1">Routers</caption>
				<tr>
					<th>IP</th>
					<th>WAN</th>
					<th>SSID</th>
					<th>Channel Bandwidth</th>
					<th>Channel</th>
					<th>TX Power</th>
					<th>Connected Users</th>
				</tr>
				<tr style="display:none" class="router_row_scaffold">
					<td class="ip"></td>
					<td class="wan"></td>
					<td class="ssid"></td>
					<td class="channel_bandwidth"></td>				
					<td class="channel_value"></td>
					<td class="tx_power"></td>
					<td class="connected_users"></td>
				</tr>			
			</table>		

		</div>
		<button onclick="topFunction()" id="hoverBtn" title="Go to top">Top</button>	
	<script>	
			function updateRouterValues(){

				var json_changes={};

				var router_value = $('#router_select').find(":selected").val();	
				var ssid_value = $('#input_ssid').val();
				var txpwr_value = $('#input_tx_power').val();
				var channel_value = $('#channel_select').find(":selected").val();
				var channel = channels[channel_value-1]; // because channels are stored in an array										
	       		if(ssid_value != ""){
	       			json_changes.ssid = ssid_value;	
	       		}

	       		if(txpwr_value != ""){
	       			json_changes.tx_power = txpwr_value;	       			
	       		}

	       		if(channel_value != ""){
	       			json_changes.channel = channel;
	       			json_changes.channel_value = channel_value;
	       		}			

		       	if(router_value == -1){

		       		if(json_changes.length == 0){
		       			alert("No input given!");
		       			return false;
		       		}

					for(var router_id in connections){
						sendUpdateRequest(connections[router_id], json_changes);
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

		       		if(parseInt(channel_value) ===  parseInt(router.channel_value)){
		       			delete json_changes.channel_value;
		       			delete json_changes.channel;
		       		}
					sendUpdateRequest(router, json_changes);
		        }	

   				removeSelectedRouter(router_value);
        	}


			function sendUpdateRequest(router, json_changes){
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
		    	    			cleanChanges();

				       			updateRouter(response);

								var myVar = setTimeout(connectToRouter, 35000, router);
						       		  },

				    error: function(XMLHttpRequest, textStatus, errorThrown) { 
				        alert("Request failed, please try again");
				    } 					       		  
				});
			}

			function connectToRouter(router){
				$.ajax({
					url: "router_requests.php",
				    type: "POST",
				    async: true,
				    data: {
				    	request:"connect_to_router", router: router
				    },
		    	    success:  function(response){
		    	    			if(response == 1){
		    	    				addSelectedRouter(router);
		    	    				notifyRouterState(router);
		    	    			}
		    	    			else
		    	    				routerError(response);
						       		  },
				    error: function(XMLHttpRequest, textStatus, errorThrown) { 
				        alert("Request failed, please try again");
				    } 					       		  
				});				

			}

			function addSelectedRouter(router){

				if($("#router_select option[value='"+ router.id +"']").length == 1){
					$('#router_select').removeAttr('disabled');
					$('#submit').removeAttr('disabled');
				}else{

					var select_router = $("#router_select");

					var router_option = document.createElement("option");

					$(router_option).val(router.id);
					$(router_option).html(router.ip);
					$(select_router).append(router_option);
					}
			}

			function notifyRouterState(router){

				changeTrColor(router.id, "#DAF7A6");
				changeTrColor(router.id, "transparent");
			}

			function changeTrColor(router_id, color){

				$('html, body').animate({scrollTop:$(document).height()}, 'slow');				

				var s = $("#table_" + router_id).css("background-color",color);
				s.hide(1500).show(1500);			
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
	    						addandCheckOverlay();
					    		site_survey_json  = JSON.parse(response);

				       			showSiteSurveyTable(site_survey_json);
					    	}
					    	catch(e){
					    		routerError(response);
					    	}

				       	},
				    error: function(XMLHttpRequest, textStatus, errorThrown) { 
				        alert("Request failed, please try again");
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
					response_json = JSON.parse(response);

					var router_id = response_json.id;
					delete response_json.id;
					
					var router_info = connections[router_id];

					for(var key in response_json){		
						router_info[key] = response_json[key];
					}

					updateRouterTableInfo(router_info);
				}
				catch(e){
					// alert(e + " " + response);
					routerError(response);
				}
			}

			function routerError(response){

				var div = document.createElement("div");
				$(div).attr("class", "alert alert-danger");

				var message = response;

				$(div).append(message);

				$(".container").append(div);	
				
				$('html, body').animate({scrollTop:$(document).height()}, 'slow');						
			}

			function updateAllRouters(response){
				for(var router_info in connections){
					updateRouter(router_info,response);
				}
			}

			function updateRouterTableInfo(router_info){

				var router_table = $("#table_" + router_info.id);			

				changeTrColor(router_info.id,"#CD5C5C");

				for (var key in router_info) {	
						$(router_table).find("." + key).html(router_info[key]).fadeIn(600);
				}
			}

		    function getValueFromKey(item, index) {
				if(item == this ){
					$("#channel_select").val(index).change();
			    }
			}

			function showRouterInformation(router_info){

				var tr = $(".router_row_scaffold").clone();

				$(tr).prop("style", "");
				$(tr).prop("class", "");

				$(tr).prop("id","table_" + router_info.id);				

				var td_array  = $(tr).find("td");

				for (var i=0; i<td_array.length; i++) {

					var td = td_array[i];
					var td_class= $(td_array[i]).attr("class");

				    	if(router_info[td_class].constructor === Array){

				    		var  conn_users = router_info[td_class];

				    		var users_str = "";

				    		for(var user in conn_users){		    			
				    			users_str += conn_users[user] + "\n";

				    		}

				    		users_str = users_str.substring(0, users_str.length -1);

				    		var pre = document.createElement("pre");
				    		$(pre).html(users_str);

				    		$(td).html(pre);

				    	}else{
				    		$(td).html(router_info[td_class]);
				    	}

				    	$(tr).append(td);
				    }

					$("#router_table").append(tr);		    
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
					$(option).val(i+1);
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
					    		addandCheckOverlay();
					    		parsed_response = JSON.parse(response);
								connections[router_id] = parsed_response;

			    				showRouterInformation(connections[router_id]);

			    				appendToRouterOptions(connections[router_id]);

					    } catch (e) {
					        routerError(response);
					    }		    	    	    	    	
				    },
				    error: function(XMLHttpRequest, textStatus, errorThrown) { 
				        alert("Request failed, please try again");
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

			function removeSelectedRouter(router_id){
				if(router_id == -1){
					$('#router_select').prop('disabled', 'disabled');
					$('#router_select').prop('disabled', 'disabled');
					$('#submit').prop('disabled', 'disabled');
				}else{
					$("#router_select option[value='"+ router_id +"']").remove();
				}								
			}

			$('#router_select').on('change', function() {	        	
				if(this.value != -1){

				var router_channel = connections[this.value].channel_value;
				$tx_power = connections[this.value].tx_power;
				$ssid_value = connections[this.value].ssid;


				$("#input_tx_power").val(parseInt($tx_power));	        
				$("#input_ssid").val($ssid_value); 	        	

				$(".channel_option").each(function(){
			    		var channel_index = $(this).val();

			    		if(channel_index == router_channel){
			    			$("#channel_select").val(channel_index).change();
			    		}
			    	});
					
				}else{
						cleanChanges();
				}

			});

			function cleanChanges(){
					$("#router_select").val(-1);
					$("#channel_select").val(-1);
					$("#input_tx_power").val("");
					$("#input_ssid").val("");		
			}	

			function addandCheckOverlay(){
				requests_responded++;

				var array_length = Object.keys(router_array).length+1;

				var progressPerc = Math.round(requests_responded/(array_length)*100);

				$(".progress-bar").attr("style", "width:"+ progressPerc +"%");
				$(".progress-bar").html("Loading " + progressPerc + " %" );

				if(requests_responded == array_length){
					removeOverLay();
				}
			}

			function removeOverLay(){
				    $("#overlay").fadeOut(300);
					$('html, body').animate({scrollTop:$(document).height()}, 'slow');
			}

			function addOverLay(){
				    $("#overlay").show();
			}

		    var all_value = -1;
		    var channels = <?php echo json_encode($channels); ?>;
		    var router_array = <?php echo json_encode($router_array); ?>;
		    var main_router = <?php echo json_encode($main_router); ?>;
		    var connections = {};
		    var requests_responded = 0;
		    var updates_sent = 0;
		    var base_path = <?php echo json_encode($base_path); ?>;
		    var base_path_url = <?php echo json_encode($base_path_url); ?>;
		    var routers_to_update = 1;

		    addOverLay();
			createChangeTable();
			getSiteSurvey();

			if(router_array.length !== 0){
				for(var key in router_array){
					getRouterInformation(router_array[key]);
				}
			}else{
					("There are no routers available!!");
			}

		// When the user scrolls down 20px from the top of the document, show the button
		window.onscroll = function() {scrollFunction()};

		function scrollFunction() {
		    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		        document.getElementById("hoverBtn").style.display = "block";
		    } else {
		        document.getElementById("hoverBtn").style.display = "none";
		    }
		}

		// When the user clicks on the button, scroll to the top of the document
		function topFunction() {
		    document.body.scrollTop = 0; // For Safari
		    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
		}			

	</script>	
</body>
</html>