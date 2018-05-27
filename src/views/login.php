<!DOCTYPE html>
<html>
<head lang="fr">
    <title>Authentification</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" type='text/css' id="bootstrap-css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" type='text/css'>
    <link href='plugins/fonts-googleapis.css' rel='stylesheet' type='text/css'>
    <link href='css/login.css' rel='stylesheet' type='text/css'>
    <script src="plugins/jquery.min.js"></script>
</head>
<body>
<!-- LOGIN FORM -->
<div class="text-center" style="padding:50px 0">
    <div class="logo" id="auth">Authentification</div>
    <!-- Main Form -->
    <div class="login-form-1">
        <form id="login-form" class="text-left" method="post" action="sign_in">
            <div class="login-form-main-message <?= $error === 'signin' ? 'show error' : '' ?>">
                <?= $error === 'signin' ? $errorMsg : '' ?>
            </div>
            <div class="main-login-form">
                <div class="login-group">
                    <div class="form-group">
                        <label for="lg_username" class="sr-only">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="lg_username" name="lg_username" placeholder="Nom d'utilisateur">
                    </div>
                    <div class="form-group">
                        <label for="lg_password" class="sr-only">Mot de passe</label>
                        <input type="password" class="form-control" id="lg_password" name="lg_password" placeholder="Mot de passe">
                    </div>
                </div>
                <button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="etc-login-form">
                <p>nouvel utilisateur? <a href="#ins">créer un nouveau compte</a></p>
            </div>
        </form>
    </div>
    <!-- end:Main Form -->
</div>

<!-- REGISTRATION FORM -->
<div class="text-center" style="padding:50px 0">
    <div class="logo" id="ins">Inscription</div>
    <!-- Main Form -->
    <div class="login-form-1">
        <form id="register-form" class="text-left" method="post" action="register">
            <div class="login-form-main-message <?= $error === 'register' ? 'show error' : '' ?>">
                <?= $error === 'register' ? $errorMsg : '' ?>
            </div>
            <div class="main-login-form">
                <div class="login-group">
                    <div class="form-group">
                        <label for="reg_fullname" class="sr-only">Votre Nom</label>
                        <input type="text" class="form-control" id="reg_fullname" name="reg_name" placeholder="Nom">
                    </div>
                    <div class="form-group">
                        <label for="reg_username" class="sr-only">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="reg_username" name="reg_username" placeholder="Nom d'utilisateur">
                    </div>
                    <div class="form-group">
                        <label for="reg_password" class="sr-only">Mot de passe</label>
                        <input type="password" class="form-control" id="reg_password" name="reg_password" placeholder="Mot de passe">
                    </div>

                    <div class="form-group login-group-checkbox">
                        <input type="radio" class="" name="reg_gender" id="male" value="0">
                        <label for="male">mâle</label>

                        <input type="radio" class="" name="reg_gender" id="female" value="1">
                        <label for="female">femelle</label>
                    </div>
                </div>
                <button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="etc-login-form">
                <p>Vous avez déjà un compte? <br /><a href="#auth">se connecter</a></p>
            </div>
        </form>
    </div>
    <!-- end:Main Form -->
</div>


<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="plugins/jquery.validate.min.js"></script>
<script src="js/login.js"></script>
</body>
</html>