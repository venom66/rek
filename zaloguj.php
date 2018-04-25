<?php
 
    session_start();
     
    if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
    {
        header('Location: index.php');
        exit();
    }
 
    require_once "connect.php";
 
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
     
    if ($polaczenie->connect_errno!=0)
    {
        echo "Error: ".$polaczenie->connect_errno;
    }
    else
    {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];
		$aktiv = $_POST['aktiv'];
         
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
     
        if ($rezultat = @$polaczenie->query(
        sprintf("SELECT * FROM users WHERE email='%s' AND aktiv=1",
        mysqli_real_escape_string($polaczenie,$login),
        mysqli_real_escape_string($polaczenie,$haslo))))
        {
            $ilu_userow = $rezultat->num_rows;
            if($ilu_userow>0)
            {
				$wiersz = $rezultat->fetch_assoc();
					if(password_verify($haslo, $wiersz['haslo'])) 
				
                $_SESSION['zalogowany'] = true;
                 
                $_SESSION['zalogowany']=true;
				$_SESSION['id'] = $wiersz['id'];
				$_SESSION['user'] = $wiersz['nick'];
				$_SESSION['imie'] = $wiersz['imie'];
				$_SESSION['mail'] = $wiersz['mail'];
				$_SESSION['aktiv'] = $wiersz['aktiv'];
                
                 
                unset($_SESSION['blad']);
                $rezultat->free_result();
                header('Location: news.php');
                 
            } else {
                 
                $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login/ hasło lub brak aktywacji konta!</span>';
                header('Location: index.php');
                 
            }
             
        }
         
        $polaczenie->close();
    }
     
?>