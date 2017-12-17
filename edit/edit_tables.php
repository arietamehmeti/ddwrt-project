<?php

	function createTable($info_arr, $result_arr){

		$table_str = "table";
		$results_str = "routers";

		$table_name = current($info_arr)->table;

        echo "<table class='table' id='table_$table_name' >";

        echo "<tr>";

		echo "<div class='checkbox'>";		
        
        echo "<th><input type='checkbox' onchange='checkAll(this, \"$table_name\")'></th>";

        echo "</div>";

            foreach($info_arr as $value){
            	 echo "<th>".$value->name ."</th>";
            }        

            echo "<th></th>";
            echo "</tr>";

        // Cycle through the array

            if(sizeof($result_arr)){

		            $i=0;
		        foreach($result_arr as &$value){
		            echo "<tr>";

		            $i++;

		            echo "<td><input type='checkbox' value=\"".$value['id'] ."\" class='checkbox_$table_name'></td>";

		                    $i=0;

		            foreach($value as &$row){
		                echo "<td>";
		                    echo "$row";
		                echo "</td>";
		            }

		            echo "<td><button type='button' id=\"edit_".$value['id']."\" onclick=\"edit_".$table_name  ."(this)\" data-ip=\"".$value['ip']."\" data-id=\"".$value['id']."\" data-toggle=\"modal\" data-target=\"#modal_router\" class='btn btn-default'>Edit</button></td>";    

		            echo "</tr>";

		            echo "edit_" .$table_name;
		        }            	

            }

        echo "</table>";

        echo "<button data-toggle='modal' type='button' \" onclick=\"add_".$table_name  ."()\" data-target='#modal_router' class='btn btn-warning'>Add</button>";

  		echo "<button type='button' onclick=\"deleteButton('$table_name')\"class='btn btn-danger'>Delete</button>";	
	}

	$main_routers = getMainRouter();
	$routers = getAllRouters();

	createTable(current($main_routers), next($main_routers));
	reset($main_routers);
	createTable(current($routers), next($routers));	
	reset($routers);

?>