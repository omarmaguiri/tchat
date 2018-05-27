$(function() {
    getLastMessages();
    getConnectedUsers();
    setInterval(function () {
        getLastMessages();
    }, 5000);
    setInterval(function () {
        getConnectedUsers();
    }, 5000);
    $('.send-message').on('click', function() {
        var myText = $('.mytext');
        var message = myText.val();
        if (message) {
            $.when(addMessage(message)).then(function(data, textStatus, jqXHR) {
                myText.val('')
            });
        }
    });
    $('.mytext').on('keydown', function(e) {
        if (e.which == 13){
            var myText = $(this);
            var message = myText.val();
            if (message) {
                $.when(addMessage(message)).then(function(data, textStatus, jqXHR) {
                    myText.val('')
                });
            }
        }
    });
    function insertChat(me, text, date, avatar) {
        var control = "";
        if (!me) {
            control = '<li style="width:100%">' +
            '<div class="msj macro">' +
            '<div class="avatar"><img class="img-circle" style="width:100%;" src="'+ avatar +'" /></div>' +
            '<div class="text text-l">' +
            '<p>'+ text +'</p>' +
            '<p><small>' + date + '</small></p>' +
            '</div>' +
            '</div>' +
            '</li>';
        } else {
            control = '<li style="width:100%;">' +
            '<div class="msj-rta macro">' +
            '<div class="text text-r">' +
            '<p>'+text+'</p>' +
            '<p><small>'+date+'</small></p>' +
            '</div>' +
            '<div class="avatar" style="padding:0px 0px 0px 10px !important"><img class="img-circle" style="width:100%;" src="'+avatar+'" /></div>' +
            '</li>';
        }
        $("#messages").append(control).scrollTop($("#messages").prop('scrollHeight'));
    }
    function getLastMessages() {
        $.ajax({
            url : 'messages',
            type : 'GET',
            dataType : 'json',
            success : function(result, statut){
                resetChat();
                result.messages.forEach(function(message) {
                    var initials = message.name.match(/\b\w/g) || [];
                    initials = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
                    var avatar = 'http://placehold.it/50/' + message.avatar_color + '/fff&text=' + initials;
                    insertChat(message.id_user === result.user.id, message.content, message.date, avatar);
                });
            },
            error : function(resultat, statut, erreur){
            },
            complete : function(resultat, statut){
            }
        });
    }
    function addMessage(message) {
        $.ajax({
            url : 'messages/add',
            type : 'post',
            dataType : 'json',
            data: {message: message},
            success : function(result, statut) {
                console.log(result);
            },
            error: function(resultat, statut, erreur){
            },
            complete: function(resultat, statut){
                getLastMessages();
            }
        });
    }
    function resetChat(){
        $('#messages').empty();
    }
    function insertUser(name, avatar) {
        var control = '<li style="width:100%">'
            + '<div class="row">'
            + '<div class="col-sm-4 avatar"><img class="img-circle" style="width:100%;" src="'+ avatar +'" /></div>'
            + '<div class="col-sm-8"><p class="text-right">'+ name +'</p></div>'
            + '</div>'
            + '</li>';
        $("#users").append(control).scrollTop($("#users").prop('scrollHeight'));
    }
    function getConnectedUsers() {
        $.ajax({
            url : 'users',
            type : 'GET',
            dataType : 'json',
            success : function(result, statut){
                resetUsers();
                result.forEach(function(user) {
                    var initials = user.name.match(/\b\w/g) || [];
                    initials = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
                    var avatar = 'http://placehold.it/50/' + user.avatar_color + '/fff&text=' + initials;
                    insertUser(user.name, avatar);
                });
            },
            error : function(resultat, statut, erreur){
            },
            complete : function(resultat, statut){
            }
        });
    }
    function resetUsers(){
        $('#users').empty();
    }
});
