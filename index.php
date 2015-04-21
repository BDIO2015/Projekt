<?php
session_start();
include("head.php");
if(!isset($_SESSION['userProjekt'])){ ?>
<body>
	<form>
    	<input type="text" id="nazwaUzytkownika" placeholder="Nazwa użytkownika" />
        <input type="password" id="haslo" placeholder="Hasło" />
        <input type="button" id="login" value="Login"/>
     </form>
</body>
</html>
<?php } else  { header('Location: tresc.php'); }?>
