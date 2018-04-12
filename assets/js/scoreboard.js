$(document).ready(function(){
	$("#scoreboard").load("../../scoreboard_pull.php");
	
	$("#add-entry").click(function(){
		var name = $("#scoreboard-name").val();
		var bio = $("#scoreboard-bio").val();
		var sales = $("#scoreboard-sales").val();
		
		console.log(name+bio+sales);
		$("#error-container").load("../../scoreboard_push.php", {name : name, bio : bio, sales : sales});
	});
	$("#add-score").click(function(){
		var name = $("#scoreboard-name").val();
		var people = $("#scoreboard-people").val();
		var houses = $("#scoreboard-houses").val();
		var sales = $("#scoreboard-sales").val();
		
		
		$("#error-container").load("../../scoreboard_entry_push.php", {name : name, people : people, houses : houses, sales : sales});
	});
});