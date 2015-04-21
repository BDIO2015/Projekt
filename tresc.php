<?php
session_start();
include("head.php");
if(isset($_SESSION['userProjekt'])){ ?>
<body>
	<h3>Zalogowałeś się!</h3>
    <button type="button" id="logOut">
    	<h3>Wyloguj się</h3>
     </button>
</body>
</html>
<?php } else  { header('Location: index.php'); }?>