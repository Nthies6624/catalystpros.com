<?php

require_once('definitions.php');

echo "<h1>Scoreboard</h1>";

$conn = mysqli_connect(scoreboardDBHost, scoreboardDBUser, scoreboardDBPassword, scoreboardDBName);


if(!$conn){
	echo "<h3>The scoreboard is unavailable.</h3>";
	exit();
}


$scoreboardDataQuery = "SELECT * FROM scores ORDER BY `Name` ASC";

$scoreboardDataResult = mysqli_query($conn, $scoreboardDataQuery);




if(!$scoreboardDataResult){
	echo "<h3>The scoreboard is unavailable.</h3>";
	exit();
}

echo "<a class='btn btn-danger btn-scoreboard-entry' data-toggle='modal' data-target='#scoreboardModal'>Add a User</a>";

$scoreboardDataRows = mysqli_num_rows($scoreboardDataResult);

if(!$scoreboardDataRows){
	echo "<h3>The scoreboard does not have any entries.</h3>";
	exit();
}

echo "<table class='scoreboard-table' style='padding: 10px;'><thead><tr height='50px'><th>Name</th><th>Short Bio</th><th>Sales Goal for the Summer</th><th>Data Stored</th><th>Sales Goal Progress</th><th>Add an Entry</th><th>Sales:Houses Visited Ratio</th></tr></thead><tbody>";
$filter_values='<option disabled>Please Select a Name</option>'; //Hashmi
$showOneID='';
for($i = 0; $i < $scoreboardDataRows; $i++){
	$sale = 0;
	$houses = 0;
	$scoreboardDataRow = mysqli_fetch_assoc($scoreboardDataResult);
	$nname = $scoreboardDataRow["Name"];
	//working by Hashmi Start
	//print_r($scoreboardDataRow);
	$nid   = $scoreboardDataRow["ID"];
	$classID=$nid."_".$nname;
	$classID = str_replace(' ', '_', $classID);
	$filter_values .= "<option value='#".$classID."'>".$nname."</option>";
	if($i==0){
		$showOneID=$classID;
	}
	//working by Hashmi END

	$sqln = "SELECT * FROM entries WHERE `Name`='$nname'";

	$resultn = $conn->query($sqln);


	$store_sales = 0;
	echo "<tr class='ntru' id='".$classID."'><td>".$scoreboardDataRow["Name"]."</td><td>".$scoreboardDataRow["Bio"]."</td><td>".$scoreboardDataRow["Goal"]."</td><td>";
	//$_t_date=date_create("2016-5-30");
if ($resultn->num_rows > 0) {
    while($row = mysqli_fetch_assoc($resultn)) {
		/*echo  "UPDATE  `entries` SET  `time` =  '".date_format($_t_date,"Y-m-d h:m:s")."' WHERE  `ID` =".$row['ID'].";<br>";
		if( date_format($_t_date,"D") == "Sat"){			
			date_add($_t_date,date_interval_create_from_date_string("2 days"));
		}else{
			date_add($_t_date,date_interval_create_from_date_string("1 days"));
		}*/
		$__time  = $row["time"];
		$__time	=date_create($__time);
		$__time	=date_format($__time,"d-M-Y");
		$sale+=$row["Sales"];
		$houses+=$row["Houses"];
        echo "Sales: " . $row["Sales"]. "<br/>People talked to: " . $row["People"]. "<br/>Houses visited: " . $row["Houses"]. "<br/>Date: " . $__time. "<br/><br/>";
        $store_sales = $store_sales + $row["Sales"];
    }
    $progress_percent = ($store_sales * 100) / $scoreboardDataRow["Goal"];

} else {
	$progress_percent=0;
    echo "No sales data yet";
}
	echo "</td><td><div class='kontainer'><div class='progress'><div class='bar' style='width: ".$progress_percent."%;'></div></div> ".$progress_percent."% Completed</div></td><td><a class='btn btn-danger btn-scoreboard-entry' data-toggle='modal' data-target='#entryModal".$scoreboardDataRow["ID"]."'>Add New</a><div class='modal fade' id='entryModal".$scoreboardDataRow["ID"]."' tabindex='-1' role='dialog' aria-labelledby='entryModal".$scoreboardDataRow["ID"]."'><div class='modal-dialog' role='document'>
              <div class='modal-content'>
                  <div class='modal-header'>
                      <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                      <h4 class='modal-title' id='scoreboardModaLabel'>Add New User</h4>
                  </div>

                  <div class='modal-body'>
                      <div class='input-group scoreboard-input-group' style='display:none;''>
                          <input type='text' class='form-control' value='".$scoreboardDataRow["Name"]."' id='scoreboard-name-".$classID."'>
                      </div>
                      <div class='input-group scoreboard-input-group'>
                          <input type='text' class='form-control' placeholder='# of sales' id='scoreboard-sales-".$classID."'>
                      </div>
                      <div class='input-group scoreboard-input-group'>
                          <input type='text' class='form-control' placeholder='People talked to' id='scoreboard-people-".$classID."'>
                      </div>
                      <div class='input-group scoreboard-input-group'>
                          <input type='text' class='form-control' placeholder='Houses visited' id='scoreboard-houses-".$classID."'>
                      </div>
                      <div id='error-container-".$classID."'></div>
                  </div>

                  <div class='modal-footer'>
                      <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                      <button type='button' class='btn btn-primary' id='add-score' onclick='add_score(\"".$classID."\");'>Add entry</button>
                  </div>
              </div>
          </div>

      </div></td><td>".$sale.":".$houses."</td></tr>";
}

echo "</tbody></table>";
$filter_values="<select id='fSelect' style='margin-left: 12px;'>".$filter_values."</select>";
echo '
<style>
#scoreboard>.scoreboard-table th {
	padding: 0 15px;
}
tr.ntru td {
	vertical-align: top;
	/*border: 2px groove black;*/
}
</style>


<script>
	function add_score(cID){
		var name = $("#scoreboard-name-"+cID).val();
		var people = $("#scoreboard-people-"+cID).val();
		var houses = $("#scoreboard-houses-"+cID).val();
		var sales = $("#scoreboard-sales-"+cID).val();
		
		
		$("#error-container-"+cID).load("/scoreboard_entry_push.php", {name : name, people : people, houses : houses, sales : sales});
	}
$(document).ready(function(){
	//<2jnf>
	$("#scoreboard > .btn-scoreboard-entry").after("'.$filter_values.'");
	$(".ntru").hide();
	$("#'.$showOneID.'").show();
	$("#fSelect").on("change",function(){
		$(".ntru").hide();
		$($("#fSelect").val()).show();
	});
	//</2jnf>
});</script>';

?>