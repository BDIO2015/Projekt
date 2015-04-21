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
	$("button#logout").click(function(){ //wylogowanie sie
		window.open('Controller/php/logout.php','_self');
	})
});
function login(user,password){
			$.ajax({
				url:'Controller/php/login.php',
				dataType:'json',
				type:'POST',
				data:{
					"login":"login",
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