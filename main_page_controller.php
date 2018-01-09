<script>
	function showRouterInformation(router_info){

		var table = document.createElement("table");
		var table_str="table";

		$(table).val("id","table_" + router_info.id);

		var caption = document.createElement("caption);
		
		$(caption).html("Router " + router_info.ssid);		
		// delete router_info(id);
		// delete router_info();

		var connected_users_arr = router_info["connected_users"];

		for (var key in p) {
	    if (p.hasOwnProperty(key)) {
	        console.log(key + " -> " + p[key]);
	    }
	}

	}

</script>