<?php

require_once('definitions.php');

$conn = mysqli_connect(scoreboardDBHost, scoreboardDBUser, scoreboardDBPassword, scoreboardDBName);

if(!$conn){
	echo "<div class='alert alert-danger' role='alert'>Cannot connect to the database.</div>";
	exit();
}

if(!isset($_POST["name"]) || !isset($_POST["bio"]) || !isset($_POST["sales"])){
	echo "<div class='alert alert-danger' role='alert'>Please fill out all of the fields!</div>";
	exit();
}

$name = $_POST["name"];
$bio = $_POST["bio"];
$sales = $_POST["sales"];
$_time = Date("Y-m-d h:m:sa");


if($name == "" || $name == null || $bio == "" || $bio == null || $sales == "" || $sales == null){
	echo "<div class='alert alert-danger' role='alert'>Please fill out all the fields with valid data.</div>";
	exit();
}

$name_escaped = htmlspecialchars($name, ENT_QUOTES);
$bio_escaped = htmlspecialchars($bio, ENT_QUOTES);

$scoreboardPush_statement = $conn -> prepare("INSERT INTO scores(Name, Bio, Goal, time) VALUES (?, ?, ?, ?)");
$scoreboardPush_statement -> bind_param("ssss", $name_escaped, $bio_escaped, $sales, $_time);

if($scoreboardPush_statement -> execute()){
	echo "<div class='alert alert-success' role='alert'>Entry submitted.</div><script>location.reload();</script>";
	exit();
} else {
	echo "<div class='alert alert-danger' role='alert'>There was an error while submitting your entry. Please try again!</div>";
	exit();
}

?>