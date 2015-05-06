<?php
require_once "./View/Gui.php";
class goscController{
	protected function login()
	{
			$nazwaUzytkownika=$_POST['nazwaUzytkownika'];
			$haslo=$_POST['haslo'];
			$zadanie=curl_init('http://deveo.pl/efdi/webAPI/login');
			$wiadomosc='login='.$nazwaUzytkownika.'&haslo='.$haslo;
			curl_setopt($zadanie, CURLOPT_POSTFIELDS, $wiadomosc);
			curl_setopt($zadanie, CURLOPT_RETURNTRANSFER, true);
			$wynik=curl_exec($zadanie);
			curl_close($zadanie);
			$wynik=json_decode($wynik);
			if($wynik->status==200)
			{
					$_SESSION['userProjekt']=$nazwaUzytkownika;
			}
			$wynik=json_encode($wynik);
			return $wynik;
	}
	protected function register()
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$email=$_POST['email'];
		$name=$_POST['imie'];
		$surname=$_POST['nazwisko'];
		$poziom=$_POST['poziom'];
		$zadanie=curl_init('http://deveo.pl/efdi/webAPI/createUser');
		$wiadomosc='email='.$email.'&login='.$username.'&haslo='.$password.'&poziom='.$poziom.'&imie='.$name.'&nazwisko='.$surname;
		curl_setopt($zadanie, CURLOPT_POSTFIELDS, $wiadomosc);
		curl_setopt($zadanie, CURLOPT_RETURNTRANSFER, true);
		$wynik=curl_exec($zadanie);
		curl_close($zadanie);
		$wynik=json_decode($wynik);
		if($wynik->status==201)
		{
		$_SESSION['userProjekt']=$username;
		}
		$wynik=json_encode($wynik);
		return $wynik;
	}
	public function goscLoad()
	{
		$gui= new Gui();
		if(isset($_POST['przyciskGosc']))
		{
			switch($_POST['przyciskGosc'])
			{
				case "login":															
						return $this->login();
				break;
				case "register":															
						return $this->register();
				break;
				case "Nowe konto":
					$gui->setContainer("View/gosc/container/containerRejestracjiGosc.html");
				break;
			}
		}
		else
			{
				$gui->setHead("View/gosc/head/headGosc.html");
				$gui->setMenu("View/gosc/menu/menuGosc.html");
				$gui->setContainer("View/gosc/container/containerLogowaniaGosc.html");															
				$gui->setFooter("View/gosc/footer/footer.html"); 
			}
			$gui->showGui();
	}
}
?>