<!DOCTYPE html>
<html>
<head>
    <title>E-mail Laravel</title>
</head>
<body>
<h1>Voici votre code de vérification : {{ $code }}</h1>
<p>Pour valider votre email vous pouvez cliquer ici : {{ $url."/verifyEmail/".$ID."/".$code }}</p>
</body>
</html>
