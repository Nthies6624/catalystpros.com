<?php

require_once('definitions.php');

$conn = mysqli_connect(scoreboardDBHost, scoreboardDBUser, scoreboardDBPassword, scoreboardDBName);

if(!$conn){
	echo "<div class='alert alert-danger' role='alert'>Cannot connect to the database.</div>";
	exit();
}

if(!isset($_POST["name"]) || !isset($_POST["people"]) || !isset($_POST["houses"]) || !isset($_POST["sales"])){
	echo "<div class='alert alert-danger' role='alert'>Please fill out all of the fields!</div>";
	exit();
}

$name = $_POST["name"];
$people = $_POST["people"];
$houses = $_POST["houses"];
$sales = $_POST["sales"];
$_time = Date("Y-m-d h:m:sa");


if($name == "" || $name == null || $houses == "" || $houses == null || $people == "" || $people == null || $sales == "" || $sales == null){
	echo "<div class='alert alert-danger' role='alert'>Please fill out all the fields with valid data.</div>";
	exit();
}

$name_escaped = htmlspecialchars($name, ENT_QUOTES);

$scoreboardPush_statement = $conn -> prepare("INSERT INTO entries(Name, Sales, People, Houses, time) VALUES (?, ?, ?, ?, ?)");
$scoreboardPush_statement -> bind_param("sssss", $name_escaped, $sales, $people, $houses, $_time);

if($scoreboardPush_statement -> execute()){
	echo "<div class='alert alert-success' role='alert'>Entry submitted.</div><script>location.reload();</script>";
	exit();
} else {
	echo "<div class='alert alert-danger' role='alert'>There was an error while submitting your entry. Please try again!</div>";
	exit();
}

?>