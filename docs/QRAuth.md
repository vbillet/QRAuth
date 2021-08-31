# Protocole QRAuth
Le protocole QRAuth permet d'authentifier des utilisateurs sur ordinateur grâce à leur téléphone portable. QRAuth permet aussi la création de clés de cryptage permettant de sécuriser les échanges de données d'une application web. Les principaux objectif de QRAuth sont : 
* L'identification d'un utilisateur grace à son téléphone
* La création d'une clé de cryptage unique à chaque session
* La protection contre les attaques de types Man In The Middle (MiTM)
* La facilité d'utilisation (Plus de liste de mot de passe compliqués à retenir)

## Aperçu
L'utilisateur commence par ouvrir sur son ordinateur une page d'identification. A l'inverse des pages classiques qui demandent une vérification d'un login et d'un mot de passe, QRAuth affiche un QRCode. A l'aide de l'application QRAuth, il suffit de scanner le QRCode (et les QRCodes suivants) pour **s'identifier** et **créer la clé de cryptage** qui servira à sécuriser les transmissions d'informations de l'application.
## La page d'identification
Lors du chargement de la page d'identification, le serveur d'application demande au serveur QRAuthServer un Token de session. Le serveur d'applicaiotn construit la page de login avec Token, et toutes les informations complémentaires nécessaire à l'application. Le serveur d'application doit inclure le script QRAuth.js. Lorsque la page est chargée sur le navigateur, le script QRAuth demande par WebSocket au serveur QRAuthServer la boite d'authentification grace au Token de session. Le premier QRCode d'identification s'affiche.
### Construction de la page d'identification
La page d'identification se construit comme toutes les pages HTML. Il faut insérer le script QRAuth et l'exécuter de la manière suivante : 
```HTML
<html>
	<head>
		<script src="qrauthserver.com/src/js/qrauth.js"></script>
	</head>
	<body>
		<div id="QRAuthCode"></div>
		<script>
			QRAuth("QRAuthCode","http://myapp.com/qrauth/gettoken.php");
		</script>
	</body>
</html>
```
Les paramètres de QRAuth : 
1) L'id du div qui va contenir les QRCodes
2) L'adresse du script de distribution de token
### Le script de distribution du token
Votre application doit contenir un script de distribution de tokens. Ce script demande un token au serveur QRAuthServer. La réponse doit être remvoyée au format json : 
```json
{
	"QRAuthServer": "qrauthserver.com",
	"QRAtuhWSPort": "21480",
	"QRAuthToken" : "1234567890abcdef"
}
```
Exemple de script php gettoken.php
```php
<?php
$appID="2526846af25dc78c5d7";
echo(file_get_contents("http://qrauthserver.com/gettoken.php?app=$appID"));
?>
```
La variable **$appID** doit être initialisée avec votre appID (voir création d'une application QRAuth)
### Demande et affichage du QRCode
Le script inséré dans la page HTML d'identification va automatiquement demander au script de votre application (gettoken.php) les informations nécessaire à l'identification. Il affichera dans la fouléee un QRCode qui permettra d'initialiser l'authentification.
Le QRCode affiché contient les informations suivante : 
1) L'adresse du serveur QRAuthServer
2) Le port du serveur QRAuthServer
3) Le Token de session
## Initialisation de la création de clé de cryptage
