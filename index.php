<?php

    session_start();
     
    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
    {
        header('Location: news.php');
        exit();
    }

 	require_once "connect.php";
	
	$polaczenie =new mysqli($host, $db_user, $db_password, $db_name);
	if($polaczenie->connect_errno!=0)
	{
	echo "Error".$polaczenie->connect_errno. "opis:".$polaczenie->connect_error; 
		
	}
	else
	{

		mysqli_select_db($polaczenie, "zadanie"); 
	
		if(isset($_POST["submit"]))
		{
			$tmpf = $_FILES["file"]["tmp_name"];
			
			$handle = fopen($tmpf, "r");
			
			while(($fileop = fgetcsv($handle, 100, ",")) !== false)
			
			{
				
				$id = $fileop[0];
				$first_name = $fileop[1];
				$last_name = $fileop[2];
				$email = $fileop[3];
				$country = $fileop[4];
				$ip_address = $fileop[5];
		
		

			
			if($polaczenie->query("INSERT INTO csvv VALUES ('$id', '$first_name', '$last_name', '$email', '$country', '$ip_address')")) 
				
						{
    					echo "New record created successfully";
						echo "<br>";
						} 
						else 
						{
    					echo "Error: " .$sql . "<br>" . $polaczenie->error;
						}
			
			
			}
			
		
			
			
		$polaczenie->close();
	}
}
?>



<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset = "utf-8" />
<title> import </title>

<meta http-equiv="X-UA-Compatibile" content="IE=edge,chrome=1" />
<link rel="stylesheet" href="style.css" type="text/css" />


 
</head>

<body>

	<form method="post" action="index.php" enctype="multipart/form-data">
	<input type="file" name="file"/>
	<br/>
	<br/>
	<input type="submit" name="submit" value="importuj"/>
	</form>
	<br/>
	
	<form method="post" action="wykres1.php">
	<input type="submit" name="submit" value="wykres"/>
	<br/>
	<br/>
	<br/>
	<br/>
	
	<a href="rejestracja.php">Rejestracja</a>
	</form>
	
	<br/>
<form action="zaloguj.php" method="post">
     
       	e-mail: <br /> <input type="text" name="login" /> <br />
        Hasło: <br /> <input type="password" name="haslo" /> <br /><br />
        <input type="submit" value="Zaloguj się" />
     
    </form>
     
	<?php
    if(isset($_SESSION['blad']))    echo $_SESSION['blad'];
	?>

	<br/>
	<br/>
	<br/>
	
	
</body>
</html>