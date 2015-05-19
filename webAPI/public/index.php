
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Project</title>
        <script type="text/javascript" src="public/menu.js"></script>
		<style type="text/css">
			
			* {
				margin: 0;
				padding: 0;
			}
			html, body {
				font-family: arial;
				font-size: 12px; 
				height: 100%;
				text-align: center;
			}
			#main {
				height: auto !important;
				margin-bottom: -50px;
				min-height: 100%;
			}
			#footer, #push {
				clear: both;
				height: 50px;
			}
			table.method-list {
				border: medium none;
				border-collapse: collapse;
				margin: 20px auto auto;
				width: 800px;
			}
			table.method-list td {
				border: 1px solid black;
				padding: 5px;
				text-align: left;
			}
			table.method-list ul {
				margin-left: 20px;
			}
			table.method-list td.method-name {
				background: none repeat scroll 0 0 #25cccc;
				color: #10435b;
				font-weight: bold;
				text-align: center;
				vertical-align: top;
			}
			table.method-list td.method-description {
				background: none repeat scroll 0 0 #25cccc;
				color: #111;
				text-align: justify;
			}
			table.method-list td.method-using-key {
				color: #765899;
			}
			table.method-list td.method-using-val {
				color: #666;
				text-align: left;
			}
			.param-name {
				background: none repeat scroll 0 0 #fdfdfd;
				color: #22720e;
				display: inline-block;
				text-align: left;
			}
            #menu0 dt{
                font-weight: bold;
                font-size: 20px;
                cursor: pointer;
            }
            #menu0{
                margin: 50px;
            }

		</style>
    </head>
    <body>

        

        
        

        <div id="main">
            <div id="header">
               <h2>Dokumentacja WebAPI</h2>
            </div>
            <div id="content">
                http://deveo.pl/efdi/webAPI/

                <dl id="menu0">
                    <dt>User</dt>
                    <dd>
                        <table class="method-list" border="0" cellspacing="0" cellpadding="0">
                            :user:
                        </table>
                    </dd>
                    <dt>Project</dt>
                    <dd>
                        <table class="method-list" border="0" cellspacing="0" cellpadding="0">
                            :project:
                        </table>
                    </dd>
                    <dt>Thread</dt>
                    <dd>
                        <table class="method-list" border="0" cellspacing="0" cellpadding="0">
                            :thread:
                        </table>
                    </dd>
                    <dt>Other</dt>
                    <dd>
                        <table class="method-list" border="0" cellspacing="0" cellpadding="0">
                            :other:
                        </table>
                    </dd>
                    <dt>Message</dt>
                    <dd>
                        <table class="method-list" border="0" cellspacing="0" cellpadding="0">
                            :message:
                        </table>
                    </dd>
                </dl>
                <script type="text/javascript"> new Menu('menu0'); </script>   
                    
            </div>
            <div id="push"></div>
        </div>
        <div id="footer"></div>
    </body>
</html>





