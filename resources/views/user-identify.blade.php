<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Confirmation de la création de votre compte</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
        Bonjour M. {{$user->lastname}} {{$user->firstname}},

        <p>Bienvenue chez AfterClassroom !</p>

        <p>
            Nous avons le plaisir de vous confirmer la création de votre compte.
        </p>

        <p>
            Veuillez trouver ci-dessous les identifiants pour vous permettre de vous y connecter :
        </p>
        
        <p>
            Identifiant : <strong> {{$user->username}}</strong>
        </p>
        <p>
            Mot de passe : <i>Ce que vous avez saisi</i>
        </p>

        <p>
            <i>L'équipe AfterClassroom</i>
        </p>
    </body>
</html>
