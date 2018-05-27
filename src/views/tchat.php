<!DOCTYPE html>
<html>
<head lang="fr">
    <title>T’chat</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />


    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" type='text/css' id="bootstrap-css">
    <link href='plugins/fonts-googleapis.css' rel='stylesheet' type='text/css'>
    <link href='css/tchat.css' rel='stylesheet' type='text/css'>
    <script src="plugins/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="chat-box row">
        <div class="col-sm-4 col-sm-offset-3 frame">
            <ul id="messages"></ul>
            <div>
                <div class="msj-rta macro">
                    <div class="text text-r" style="background:whitesmoke !important">
                        <input class="mytext" placeholder="Tapez un message"/>
                    </div>

                </div>
                <button href="#" style="background:transparent;" class="btn send-message" type="button">
                    <span class="glyphicon glyphicon-share-alt send-icon"></span>
                </button>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="jumbotron">
                <ul id="users" class="list-unstyled">
                </ul>
                <p class="text-center">
                    <a href="logout" role="button" class="btn btn-default btn-lg">
                        <span class="glyphicon glyphicon-log-out"></span>
                        Se déconnecter
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="js/tchat.js"></script>
</body>
</html>
