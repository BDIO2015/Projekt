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
	$("input#zapiszOcena").click(function(){
		var ocena=$("#ocena").val();
		var uwagi=$("#uwagiOcena").val();
		wstawOcena(ocena,uwagi);
	})
function rejestruj(username,pass,mail,level,name,surname){
	$.ajax({
		url:'ajaxController.php',
		dataType:'json',
		type:'POST',
		data:{
			"przyciskGosc":"register",
			"password":pass,
			"username":username,
			"email":mail,
			"imie":name,
			"nazwisko":surname,
			"poziom":level			
		},
		success : function(json) {
		if(json["status"]==201)
		{
			alert("Udalo sie, możesz się teraz zalogować");
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
	//------------------------------------------------
	$("input#zmien").click(function(){
		var username=$("#username").val();
		var password=$("#password").val();
		var newpassword=$("#newpassword").val();
		
		zmienHaslo(username,password,newpassword);
	});
	
	function zmienHaslo(username2,password2,newpassword2){
			$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskStudent":"zmien",
					"username":username2,
					"password":password2,
					"newpassword":newpassword2,
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Pomyslna zmiana hasla");
							window.open('index.php','_self');
						}
						else if(json["status"]==400)
						{
							alert("zle param");
							window.open('index.php','_self');
						}
						else
						{
							alert("Nie udało się");
							window.open('index.php','_self');
						}
					},
					error:function(err){
						alert("Blad");
					}
			});
			}
	
	//------------------------------------------------
	$("input#przeslijZmiany").click(function(){
		var imie=$("#imie").val();
		var nazwisko=$("#nazwisko").val();
		var nrtel=$("#nrtel").val();
		var idwydz=$("#idwydz").val();
		var kierunek=$("#kierunek").val();
		var idspec=$("#idspec").val();
		var idkat=$("#idkat").val();
		var idsiec=$("#idsiec").val();
		edytujKontoStudenta(imie,nazwisko,nrtel,idwydz,kierunek,idspec,idkat,idsiec);
	});
	
	$("input#wprowadzZmiany").click(function(){
		var imie=$("#imie").val();
		var nazwisko=$("#nazwisko").val();
		var nrtel=$("#nrtel").val();
		var idwydz=$("#idwydz").val();
		var kierunek=$("#kierunek").val();
		var idspec=$("#idspec").val();
		var idkat=$("#idkat").val();
		var idsiec=$("#idsiec").val();
		edytujKontoUsera(imie,nazwisko,nrtel,idwydz,kierunek,idspec,idkat,idsiec);
	});
	
	function edytujKontoUsera(imie2,nazwisko2,nrtel2,idwydz2,kierunek2,idspec2,idkat2,idsiec2){
			$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskAdmin":"wprowadzZmiany",
					"imie":imie2,
					"nazwisko":nazwisko2,
					"nrtel":nrtel2,
					"idwydz":idwydz2,
					"kierunek":kierunek2,
					"idspec":idspec2,
					"idkat":idkat2,
					"idsiec":idsiec2
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Zaktualizowano pomyślnie");
							window.open('index.php','_self');
						}
						else
						{
							alert("Nie udało się");
							window.open('index.php','_self');
						}
					},
					error:function(err){
						alert("Błąd");
					}
			});
			}
			
	
		function edytujKontoStudenta(imie2,nazwisko2,nrtel2,idwydz2,kierunek2,idspec2,idkat2,idsiec2){
			$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskStudent":"przeslijZmiany",
					"imie":imie2,
					"nazwisko":nazwisko2,
					"nrtel":nrtel2,
					"idwydz":idwydz2,
					"kierunek":kierunek2,
					"idspec":idspec2,
					"idkat":idkat2,
					"idsiec":idsiec2
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Zaktualizowano pomyślnie");
							window.open('index.php','_self');
						}
						else if(json["status"]==400)
						{
							alert("zle param");
							window.open('index.php','_self');
						}
						else
						{
							alert("Nie udało się");
							window.open('index.php','_self');
						}
					},
					error:function(err){
						alert("Blad");
					}
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

$("input#stworzWatek").click(function(){ //zdarzenie obslugujące przycisk archiwizuj
	var tresc=$("#trescWatku").val();;
	if(tresc.length!=0)
	{
		nowyWatek(tresc);
	}
	});
	function nowyWatek(trescWatku){
		$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"Stwórz Wątek",
					"tresc":trescWatku
					},
				success : function(json) {
           				 if(json["status"]==201)
						 {
							alert("Wątek został dodany");
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
					
	$("input#dodajDoProjektu").click(function(){
	
		dolaczenieDoProjektu();
	});
		function dolaczenieDoProjektu(){
			$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskStudent":"Dodaj mnie do projektu"
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Poczekaj na zatwierdzenie");
							window.open('index.php','_self');
						}
						else if(json["status"]==400)
						{
							alert("zle param");
							window.open('index.php','_self');
						}
						else
						{
							alert("Nie udało się");
							window.open('index.php','_self');
						}
					},
					error:function(err){
						alert("Blad");
					}
			});
			}	
			
			$("input#akceptujStudentaPrzycisk").click(function(){
	
		akceptujStudenta();
	});
		function akceptujStudenta(){
			$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"akceptujStudentaPrzycisk"
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Student zostal dolaczony");
							window.open('index.php','_self');
						}
						else if(json["status"]==400)
						{
							alert("zle param");
							window.open('index.php','_self');
						}
						else
						{
							alert("Nie udało się");
							window.open('index.php','_self');
						}
					},
					error:function(err){
						alert("Blad");
					}
			});
			}
		
	function wstawOcena(ocena,uwagiOcena){
			$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"wstawOcena",
					"ocena":ocena,
					"komentarz":uwagiOcena					
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Ocena dodana");
							window.open('index.php','_self');
						}
						else if(json["status"]==400)
						{
							alert("Złe dane");
						}
						else if(json.status==401)
						{
							alert("Nie masz dostępu do tego projektu");
						}
						else
						{
							alert("Nie udało się");
						}
					},
					error:function(err){
						alert("Blad");
					}
			});
			}	
		

		function sortUnicode(a,b){return a[0].toLowerCase().localeCompare(b[0].toLowerCase());} //funkcje do sortowania tabel
		
		function sortIt(o,s,r,n,t,i) {
		o.ord=!o.ord;
		n=o.parentNode.cellIndex;
		r=o.offsetParent.offsetParent.rows;
		var rows=[],cols=[];s=s||1;
		for(i=0;t=r[s+i];i++){
			rows.push(t.cloneNode(true));
			cols.push([t.cells[n].firstChild.nodeValue,i]);
		}
		cols.sort(sortUnicode);
		if(o.ord)cols.reverse()
		for(i=0;t=r[s+i];i++){
			var j = rows[cols[i][1]];
			t.parentNode.replaceChild(j,t);
			j.className=i%2?'odd':'even';
		}
	}
	$("input#dodajKomentarz").click(function(){ //zdarzenie obslugujące przycisk dodajKomentarz
		
		var tresc=$("#trescKomentarza").val();
		if(tresc.length!=0)
		{
			nowyKomentarz(tresc);
		}
		
	});
	function nowyKomentarz(tresc){
		$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"Dodaj komentarz",
					"trescKomentarza":tresc
					},
					success:function(json){
						if(json["status"]==201)
						{
							alert("Dodano komentarz");
							window.open('index.php','_self');
						}
						else if(json["status"]==400)
						{
							alert("zle param");
							window.open('index.php','_self');
						}
						else
						{
							alert("Nie udało się");
							window.open('index.php','_self');
						}
					},
					error:function(err){
						alert("Blad");
					}
			});
	}
	$("input#usunKomentarz").click(function(){ //zdarzenie obslugujące przycisk usunKomentarz
		usunKomentarz();
	})
	function usunKomentarz(){
		$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"usunKomentarz"
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Usunięto komentarz");
							
						}
						else if(json["status"]==400)
						{
							alert("zle param");
						
						}
						else
						{
							alert("Nie udało się");
						
						}
					},
					error:function(err){
						alert("Blad");
					}
			});
	}
	$("input#usunWatek").click(function(){ //zdarzenie obslugujące przycisk usunWatek
		usunWatek();
	})
	function usunWatek(){
		$.ajax({
				url:'ajaxController.php',
				dataType:'json',
				type:'POST',
				data:{
					"przyciskProwadzacy":"Usuń Wątek"
					},
					success:function(json){
						if(json["status"]==200)
						{
							alert("Usunięto wątek");
							
						}
						else if(json["status"]==400)
						{
							alert("zle param");
						
						}
						else
						{
							alert("Nie udało się");
						
						}
					},
					error:function(err){
						alert("Blad");
					}
			});
	}


});