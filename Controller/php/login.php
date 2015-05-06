<?php
session_start();

if($_POST['login']=="login")
	{
	$nazwaUzytkownika=$_POST['nazwaUzytkownika'];
	$haslo=$_POST['haslo'];
	$zadanie=curl_init('http://deveo.pl/efdi/webAPI/login');
	$wiadomosc='login='.$nazwaUzytkownika.'&haslo='.$haslo;
	curl_setopt($zadanie, CURLOPT_POSTFIELDS, $wiadomosc);
	curl_setopt($zadanie, CURLOPT_RETURNTRANSFER, true);
	$wynik=curl_exec($zadanie);
	curl_close($zadanie);
	echo $wynik;
	$wynik=json_decode($wynik);
	if($wynik->status==200)
	{
			$_SESSION['userProjekt']=$nazwaUzytkownika;
	}
	}
	
?>