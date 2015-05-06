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
					"nazwaUzytkownika":user,
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
});
