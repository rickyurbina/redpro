<?php

class Conexion{

	public static function conectar(){
// conexion local -------------------------------
		// $bd = "rickurbi_redpro";
		// $servername = "localhost";
		// $username = "rickurbi_redpro";
		// $password = "D7u^nWq.^a$$[E?-";

		$bd = "tapid";
		$servername = "localhost";
		$username = "root";
		$password = "";

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