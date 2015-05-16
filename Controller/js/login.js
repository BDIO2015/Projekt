// JavaScript Document
$(document).ready(function(e) {
    $("input#login").click(function(){ //zdarzenie obslugujące przycisk LOGIN
		var nazwaUzytkownika=$("#nazwaUzytkownika").val();
		var haslo=$("#haslo").val();
		if(nazwaUzytkownika.length!=0 && haslo.length!=0)
		{
			login(nazwaUzytkownika,haslo);
		}
	});
	$("input#zarejestruj").click(function(){ //zdarzenie obslugujące przycisk ZAREJESTRUJ
	if($("#poziom").prop('checked'))
		var login=$("#username").val();
		var password=$("#password").val();
		var email=$("#email").val();
		if($("#poziom").prop('checked'))
		var poziom=2;
		else
		var poziom=1;
		var name=$("#imie").val();
		var surname=$("#nazwisko").val();
		if(username.length!=0 && password.length!=0 && email.length!=0)
		{
		rejestruj(login,password,email,poziom,name,surname);
		}
	});
$("input#wyslijWiadomosc").click(function(){ //zdarzenie obslugujące przycisk wyslijWiadomosc
		var odbiorca=$("#nazwaUzytkownika").val();
		var tytul=$("#tytul").val();
		var tresc=$("#trescWiadomosci").val();
		if(tresc.length!=0 && odbiorca.length!=0 && tytul.length!=0)
		{
			var wynik=sprawdz(odbiorca);
			wynik.success(function(wynik){
			if( wynik.status==200 )
			{
				wyslij(wynik.result.id_uzytkownik,tytul,tresc);
			}
			else
			{
				alert("Taki użytkownik nie istnieje");
			}
			});
			
		}
		
	});
function rejestruj(username,pass,mail,level,name,surname){
	$.ajax({
		url:'ajaxController.php',
		dataType:'json',
		type:'POST',
		data:{
			"przyciskGosc":"register",
			"username":username,
			"password":pass,
			"email":mail,
			"imie":name,
			"nazwisko":surname,
			"poziom":level			
		},
		success : function(json) {
		if(json["status"]==201)
		{
			alert("Udalo sie");
			window.open('index.php','_self');
		}
		else
		{
			$(".input_form").css({ border:"‪#‎FF0033‬ solid 2px"});
			alert("Nie udalo sie Emotikon frown");
			window.open('index.php','_self');
		}
		},
			error : function(err) {
			alert("Blad");
		} 
		});
}
function login(user,password){
			$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskGosc":"login",
					"username":user,
					"haslo":password
					},
				success : function(json) {
           				 if(json["status"]==200)
						 {
							 window.open('index.php','_self');
						 }
						 else
						 {
							 $(".input_form").css({ border:"#FF0033 solid 2px"});
							 alert("Nie udalo sie :(");
						 }
				},
				error : function(err) {
					alert("Blad");
					}		
			})
}
function wyslij(adresat,temat,wiadomosc){
			$.ajax({
			 url:"ajaxController.php",
			 dataType:'json',
			 type:'POST',
			 data:
			 {
				"przyciskStudent":"wyslijWiadomosc",
				"nazwaUzytkownika":adresat,
				"tytul":temat,
				"trescWiadomosci":wiadomosc
			 },
			 success : function(json)
					 {
						 alert("Wiadomość została wysłana");
						 window.open('index.php','_self');
					 }
			 });
}
function sprawdz(adresat){
			return $.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskStudent":"sprawdzCzyIstnieje",
					"nazwaUzytkownika":adresat
					},
			});
}
 $("input#stworz").click(function(){ //zdarzenie obslugujące przycisk stworz (nowy projekt)
		var nazwaProjektu=$("#nazwaProjektu").val();
		var opisProjektu=$("#opisProjektu").val();
		if(nazwaProjektu.length!=0 && opisProjektu.length!=0)
		{
			stworzProjektProwadzacy(nazwaProjektu,opisProjektu);
		}
		else
		{
			alert("Uzupełnij wszystkie dane!");
		}
	});
	
	function stworzProjektProwadzacy(nazwa,opis){
		return $.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"stworz",
					"menuOpcjaProwadzacy":"stworz",
					"nazwaProjektu":nazwa,
					"opisProjektu":opis
					},
				success : function(json) {
           				 if(json["status"]==201)
						 {
							alert("Projekt stworzono pomyślnie");
							window.open('index.php','_self');
						 }
						 else
						 {
							 $(".input_form").css({ border:"#FF0033 solid 2px"});
							 alert("Nie udalo sie :(");
						 }
				},
				error : function(err) {
					alert("Blad");
					}		
			});
	}

	function czyProjektIstnieje(){
	return $.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskStudent":"czyProjektIstnieje"
					},
			});
	}


$("input#archiwizuj").click(function(){ //zdarzenie obslugujące przycisk archiwizuj
		archiwizuj();
	});
	
function archiwizuj(){
		$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"Archiwizuj"
					},
				success : function(json) {
           				 if(json["status"]==201)
						 {
							alert("Projekt zarchiwizowano pomyślnie");
							window.open('index.php','_self');
						 }
						 else
						 {
							 $(".input_form").css({ border:"#FF0033 solid 2px"});
							 alert("Nie udalo sie :(");
						 }
				},
				error : function(err) {
					alert("Blad");
					}	
			});
	}
$("input#usunProjekt").click(function(){ //zdarzenie obslugujące przycisk archiwizuj
	usunProjekt();
	});
	
function usunProjekt(){
		$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"Usuń projekt"
					},
				success : function(json) {
           				 if(json["status"]==200)
						 {
							alert("Projekt usunięty pomyślnie");
							window.open('index.php','_self');
						 }
						 else
						 {
							 $(".input_form").css({ border:"#FF0033 solid 2px"});
							 alert("Nie udalo sie :(");
						 }
				},
				error : function(err) {
					alert("Blad");
					}
});					
			
}
});