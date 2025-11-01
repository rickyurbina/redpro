<?php

class Conexion{

	public static function conectar(){
// conexion local -------------------------------
		$bd = "tapid";
		$servername = "localhost";
		$username = "root";
		$password = "";

// conexion Server ------------------------------
		// $bd = "urbinawe_tapid";
		// $servername = "localhost";
		// $username = "urbinawe_tapid";
		// $password = "x_yh}fT3B(2~";

		try {
		    $conn = new PDO("mysql:host=$servername;dbname=$bd", $username, $password);
		    // set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->exec("set names utf8");
			// echo '<script>alert("Connection Seccess !");</script>';
		    }
		catch(PDOException $e)
		    {
		    echo '<script>alert("Connection failed: '.$e->getMessage().'");</script>';

		    }
		return $conn;

	}

}