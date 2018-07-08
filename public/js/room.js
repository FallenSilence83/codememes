var Room = window.Room || {
    roomState : null,
    gameState : null,
    playersState : null,
    orangeState : null,
    blueState : null,
    userState : null,
    pause: false,

    init: function(){
        Room.roomEventBinding();

        $.ajax({
            url: '/room/status',
            dataType: "json",
            success: function(result) {
                if(result.error){
                    console.log('json error: ' + result.error);
                }else {
                    if(result.roomInfo){
                        Room.roomState = result.roomInfo.room;
                        Room.gameState = result.roomInfo.game;
                        Room.playersState = result.roomInfo.users;
                        Room.orangeState = result.roomInfo.orangeTeam;
                        Room.blueState = result.roomInfo.blueTeam;
                    }
                    if(result.user){
                        Room.userState = result.user;
                        Room.refreshUser();
                    }
                    Room.initSidebar();

                    var clearGame = false;
                    var isNewGame = false;
                    if(result.roomInfo.game){
                        isNewGame = true;
                    }else{
                        clearGame = true;
                    }
                    Room.refreshGame(clearGame, isNewGame);

                    setTimeout('Room.checkForUpdates();', 5000);
                    setTimeout('Room.extendSession();', 600000);//extend session if we're still here in 10 mins
                }
            },
            error: function() {
                console.log( "todo: error handling" );
            },
            complete: function() {
                //console.log( "complete" );
            }
        });

        //this.refreshFeatures(true);
    },

    extendSession: function(){
        if(!Room.pause) {
            Room.standardAjax('/room/extend');
            $.ajax({
                url: '/room/status',
                dataType: "json",
                success: function (result) {
                    if (result.error) {
                        console.log('json error: ' + result.error);
                    }
                },
                error: function () {
                    console.log("todo: error handling");
                },
                complete: function () {
                    setTimeout('Room.extendSession();', 600000);
                }
            });
        }else{
            setTimeout('Room.extendSession();', 2000);
        }
    },

    checkForUpdates: function(){
        if(!Room.pause) {
            $.ajax({
                url: '/room/status',
                dataType: "json",
                success: function (result) {
                    if (result.error) {
                        console.log('json error: ' + result.error);
                    } else {
                        Room.processUpdates(result);
                        setTimeout('Room.checkForUpdates();', 5000);
                    }
                },
                error: function () {
                    console.log("todo: error handling");
                },
                complete: function () {
                    //console.log( "complete" );
                }
            });
        }else{
            console.log('pause active, skipping');
            setTimeout('Room.checkForUpdates();', 5000);
        }
    },

    /**
     *
     * @param jsonResponse
     */
    processUpdates: function(jsonResponse){
        if(jsonResponse.roomInfo){
            if(JSON.stringify(Room.roomState) != JSON.stringify(jsonResponse.roomInfo.room)){
                Room.roomState = jsonResponse.roomInfo.room;
                console.log('room changed');
            }

            if(JSON.stringify(Room.gameState) != JSON.stringify(jsonResponse.roomInfo.game)){
                var clearGame = (undefined == jsonResponse.roomInfo.game.gameId);
                var isNewGame = (Room.gameState == null || Room.gameState.gameId != jsonResponse.roomInfo.game.gameId);
                console.log('clearGame: ' + clearGame);
                console.log('isNewGame: ' + isNewGame);
                Room.gameState = jsonResponse.roomInfo.game;
                console.log('game changed');
                Room.refreshGame(clearGame, isNewGame);
            }

            if(JSON.stringify(Room.playersState) != JSON.stringify(jsonResponse.roomInfo.users)){
                Room.playersState = jsonResponse.roomInfo.users;
                Room.refreshPlayerSidebar();
                console.log('players changed');
            }

            if(JSON.stringify(Room.orangeState) != JSON.stringify(jsonResponse.roomInfo.orangeTeam)){
                Room.orangeState = jsonResponse.roomInfo.orangeTeam;
                Room.refreshOrangeTeam();
                console.log('orange changed');
            }

            if(JSON.stringify(Room.blueState) != JSON.stringify(jsonResponse.roomInfo.blueTeam)){
                Room.blueState = jsonResponse.roomInfo.blueTeam;
                Room.refreshBlueTeam();
                console.log('blue changed');
            }
        }
        if(jsonResponse.user){
            if(JSON.stringify(Room.userState) != JSON.stringify(jsonResponse.user)){
                Room.userState = jsonResponse.user;
                console.log('user changed');
                Room.refreshUser();
            }
        }
    },

    /**
     * Initialize the room sidebar
     */
    initSidebar: function(){
        Room.refreshPlayerSidebar();
        Room.refreshOrangeTeam();
        Room.refreshBlueTeam();
    },

    refreshPlayerSidebar: function(){
        var userCount = Object.keys(Room.playersState).length;
        $('.all-count').html('(' + userCount + ')');
        $('#playersSubmenu .li-user').remove();
        $.each(Room.playersState, function(key, player){
            $('#playersSubmenu').append(Room.getUserLi(player));
        });
    },

    refreshOrangeTeam: function(){
        var orangeCount = Object.keys(Room.orangeState).length;
        $('.orange-count').html('(' + orangeCount + ')');
        $('#orangeSubmenu .li-user').remove();
        $('#orangeTeamSelect .li-user').remove();
        $('#orangeCaptain option').remove();
        $.each(Room.orangeState, function(key, player){
            $('#orangeSubmenu').append(Room.getUserLi(player));
            $('#orangeTeamSelect').append(Room.getUserLi(player));
            $('#orangeCaptain').append('<option value="'+player.userId+'">'+player.displayName+'</option>');
        });

    },

    refreshBlueTeam: function(){
        var blueCount = Object.keys(Room.blueState).length;
        $('.blue-count').html('(' + blueCount + ')');
        $('#blueSubmenu .li-user').remove();
        $('#blueTeamSelect .li-user').remove();
        $('#blueCaptain option').remove();
        $.each(Room.blueState, function(key, player){
            $('#blueSubmenu').append(Room.getUserLi(player));
            $('#blueTeamSelect').append(Room.getUserLi(player));
            $('#blueCaptain').append('<option value="'+player.userId+'">'+player.displayName+'</option>');
        });
    },

    refreshUser: function(){
        var userBadge = $('.user-badge');
        var body = $('body');
        if(Room.userState.team == 'orange'){
            body.addClass('team-orange');
            body.removeClass('team-blue');
            $('#orangeSubmenu .li-join').hide();
            $('#blueSubmenu .li-join').show();
            $('#orangeTeamWelcome .join-team').hide();
            $('#blueTeamWelcome .join-team').show();
            userBadge.removeClass('badge-secondary');
            userBadge.removeClass('badge-blue');
            userBadge.addClass('badge-orange');
        }else if(Room.userState.team == 'blue'){
            body.removeClass('team-orange');
            body.addClass('team-blue');
            $('#orangeSubmenu .li-join').show();
            $('#blueSubmenu .li-join').hide();
            $('#orangeTeamWelcome .join-team').show();
            $('#blueTeamWelcome .join-team').hide();
            userBadge.removeClass('badge-secondary');
            userBadge.addClass('badge-blue');
            userBadge.removeClass('badge-orange');
        }else{
            body.removeClass('team-orange');
            body.removeClass('team-blue');
            $('#orangeSubmenu .li-join').show();
            $('#blueSubmenu .li-join').show();
            $('#orangeTeamWelcome .join-team').show();
            $('#blueTeamWelcome .join-team').show();
            userBadge.addClass('badge-secondary');
            userBadge.removeClass('badge-blue');
            userBadge.removeClass('badge-orange');
        }
    },

    refreshGame: function(clearGame, isNewGame){
        if(clearGame || isNewGame){
            $('#gameBoard').html('');
        }
        if(Room.gameState != null && Room.gameState.memes){
            var gameOver = (Room.gameState.winningTeam != null);
            if(gameOver && Room.gameState.winningTeam != Room.userState.team && Room.gameState.scoreOrange > 0 && Room.gameState.scoreBlue > 0){
                //death card chosen, trigger rick
                Room.rickRoll();
            }else if(gameOver && Room.gameState.winningTeam != Room.userState.team){
                //loser
                Room.memeModal('You Lose!', 'https://media.giphy.com/media/1ryrwFNXqNjC8/giphy.gif');
            }else if(gameOver && Room.gameState.winningTeam == Room.userState.team){
                //winner
                Room.memeModal('#Winning', 'https://i.kym-cdn.com/photos/images/newsfeed/001/296/357/663.gif');
            }

            $('.game-nav').addClass('active');

            //set the turn color of nav items
            var clueForm = $('.nav-clue-form');
            var clue = $('.nav-clue');
            var body = $('body');
            if(gameOver){
                body.addClass('game-over');
                body.removeClass('turn-orange');
                clueForm.removeClass('orange');
                clue.removeClass('orange');
                clueForm.removeClass('blue');
                clue.removeClass('blue');
                clueForm.hide();
                clue.hide();
            }else if(Room.gameState.turn == 'blue'){
                body.removeClass('game-over');
                body.removeClass('turn-orange');
                body.addClass('turn-blue');
                clueForm.removeClass('orange');
                clue.removeClass('orange');
                clueForm.addClass('blue');
                clue.addClass('blue');
            }else{
                body.removeClass('game-over');
                body.addClass('turn-orange');
                body.removeClass('turn-blue');
                clueForm.addClass('orange');
                clue.addClass('orange');
                clueForm.removeClass('blue');
                clue.removeClass('blue');
            }

            //display the current score
            $('.orange-score').html(Room.gameState.scoreOrange);
            $('.blue-score').html(Room.gameState.scoreBlue);

            //decide what to show in the clue navs
            if(!gameOver && Room.gameState.clueWord){
                clue.show();
                $('.display-clue-word').html(Room.gameState.clueWord);
                $('.display-clue-number').html(Room.gameState.clueNumber);
                $('.clue-available').show();
                $('.clue-unavailable').hide();
                clueForm.hide();
            }else if(!gameOver){
                $('.clue-available').hide();
                $('.clue-unavailable').show();
                //only show the clue form to the active team's captain
                if(Room.gameState.turn == 'blue' && Room.userState.userId == Room.gameState.blueCaptainId){
                    clueForm.show();
                    clue.hide();
                }else if(Room.gameState.turn == 'orange' && Room.userState.userId == Room.gameState.orangeCaptainId){
                    clueForm.show();
                    clue.hide();
                }else{
                    $('#clueWord').val('');
                    $('#clueNumber').val('1');
                    clueForm.hide();
                    clue.show();
                }
            }

            //handle meme displays
            if(isNewGame){
                $.each(Room.gameState.memes, function(key, meme){
                    $('#gameBoard').append(Room.getMemeCard(meme));
                });
            }else{
                //TODO: update the memes already on the board
                $('#gameBoard').html('');
                $.each(Room.gameState.memes, function(key, meme){
                    $('#gameBoard').append(Room.getMemeCard(meme));
                });
            }
            Room.memeBinding();
            $('#welcomePanel').hide();
            $('#createGame').hide();
            if(!$('#sidebar').hasClass('active')){
                Room.toggleSidebar();
            }
        }else{
            $('.game-nav').removeClass('active');
            $('#gameBoard').html('');
            $('#welcomePanel').show();
            $('#createGame').show();
        }
    },

    toggleSidebar: function(){
        $('#sidebar').toggleClass('active');
        if($('#sidebar').hasClass('active')){
            $('#sidebarCollapse ion-icon').attr('name', 'arrow-round-forward');
        }else{
            $('#sidebarCollapse ion-icon').attr('name', 'arrow-round-back');
        }
    },

    /**
     *
     * @param user
     * @returns {string} html
     */
    getUserLi: function(user){
        var html = '' +
            '<li class="li-user">' +
            '    <span>' +
            '        <ion-icon name="person"></ion-icon>' +
            '        ' + user.displayName + '';
        if(Room.roomState.hostId == user.userId){
            html += '            <span class="user-desig">(host)</span>';
        }
        html += '' +
            '    </span>' +
            '</li>';
        return html;
    },
    
    getMemeCard: function(meme){
        var html = '<div id="meme'+meme.memeId+'" data-memeid='+meme.memeId+' class="meme-card ';
        if(meme.selected){
            html += ' selected ';
        }
        html += meme.status+' col-xl-2 col-lg-3 col-md-4 col-sm-6">';
        html += '<div class="thumbnail">';
        html += '<img src="'+meme.thumb+'" alt="'+meme.displayName+'" class="meme-img">';
        html += '<div class="caption">'+meme.displayName+'</div>';

        html += '<div class="select-line">';
        html += '<button class="btn-meme-select btn btn-primary" data-memeid="'+meme.memeId+'" type="button">Select</button>';
        html += '</div>';
        html += '<div class="action-line">';

        if(undefined != meme.youTubeKey){
            html += '<ion-icon class="meme-link meme-youtube" data-label="'+meme.displayName+'" data-ytkey="'+meme.youTubeKey+'" name="logo-youtube" title="YouTube Video"></ion-icon>';
        }
        if(undefined != meme.url) {
            html += '<ion-icon class="meme-link meme-image" data-label="' + meme.displayName + '" data-url="' + meme.url + '" name="images" title="View Larger"></ion-icon>';
        }
        if(undefined != meme.infoUrl) {
            html += '<ion-icon class="meme-link meme-info" data-label="' + meme.displayName + '" data-url="' + meme.infoUrl + '" name="information-circle" title="What is this meme?"></ion-icon>';
        }
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    },

    joinTeam: function(team){
        var url = '/room/team?team='+team;
        Room.standardAjax(url);
    },

    newGame: function(orangeCaptain, blueCaptain){
        var url='/room/newgame?orangeCaptainId='+orangeCaptain+'&blueCaptainId='+blueCaptain;
        Room.standardAjax(url);
    },

    standardAjax: function(url){
        Room.pause = true;
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(result) {
                if(result.error){
                    console.log('json error: ' + result.error);
                }else {
                    Room.processUpdates(result);
                }
            },
            error: function() {
                console.log( "todo: error handling" );
            },
            complete: function() {
                Room.pause = false;
            }
        });
    },

    submitClue: function(){
        var clueWord = $('#clueWord').val();
        var clueNumber = $('#clueNumber').val();
        var url = '/room/clue?clue_word=' + clueWord + '&clue_number=' + clueNumber;
        Room.standardAjax(url);
    },

    passTurn: function(){
        var url = '/room/pass';
        Room.standardAjax(url);
    },

    guess: function(memeId){
        var url = '/room/guess?memeId=' + memeId;
        Room.standardAjax(url);
    },

    memeModal: function(label, imageUrl){
        $('#memeModal .modal-title').html(''+label);
        $('#memeModal img').attr('src', imageUrl);
        $('#memeModal').modal('show');
    },

    youTubeModal: function(label, videoKey){
        $('#youTubeModal .modal-title').html(''+label);
        var url = 'http://www.youtube.com/embed/' + videoKey + '?rel=0&amp;autoplay=1';
        $('#youTubeModal iframe').attr('src', url);
        $('#youTubeModal').modal('show');
    },

    rickRoll: function(){
        $('#rickModal').modal('show');
        $('#rickModal iframe').attr('src', 'http://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&amp;autoplay=1');
    },

    memeBinding: function(){
        $('.btn-meme-select').on('click', function(e){
            var memeId = $(this).data('memeid');
            Room.guess(memeId);
        });

        $('.meme-youtube').on('click', function(){
            console.log('clicked yt');
            var label = $(this).data('label');
            var ytkey = $(this).data('ytkey');
            Room.youTubeModal(label, ytkey);
        });

        $('.meme-image').on('click', function(){
            console.log('clicked image');
            var label = $(this).data('label');
            var url = $(this).data('url');
            Room.memeModal(label, url);
        });

        $('.meme-info').on('click', function(){
            console.log('clicked info');
            var url = $(this).data('url');
            window.open(url, '_blank');
        });
    },

    roomEventBinding: function(){
        $('.join-team').on('click', function (e) {
            e.preventDefault();
            var team = $(this).data('team');
            Room.joinTeam(team)
        });

        $('#newGameForm').on('submit', function (e) {
            e.preventDefault();
            var blueCaptain = $('#blueCaptain').val();
            var orangeCaptain = $('#orangeCaptain').val();
            if(blueCaptain && orangeCaptain){
                Room.newGame(orangeCaptain, blueCaptain);
            }
        });

        $('#sidebarCollapse').on('click', function () {
            Room.toggleSidebar();
        });

        $('.show-new-game').on('click', function(){
            $('#createGame').show();
        });

        $('#clueSubmit').on('click', function(){
            Room.submitClue();
        });

        $('.display-clue-pass').on('click', function(){
            Room.passTurn();
        });

        $('#rickModal').on('hidden.bs.modal', function () {
            $('#rickModal iframe').attr('src', 'http://www.youtube.com/embed/2Z4m4lnjxkY');
        });

        $('#youTubeModal').on('hidden.bs.modal', function () {
            $('#youTubeModal iframe').attr('src', 'http://www.youtube.com/embed/2Z4m4lnjxkY');
        });

        $('#createGame button.close').on('click', function() {
            $('#createGame').hide();
        });

        $('#rickTest').on('click', function(){
            Room.rickRoll();
        })
        $('#youTubeModalTest').on('click', function(){
            Room.youTubeModal('NL Random Video', 'U6pjkAmCbIE');
        });
        $('#memeModalTest').on('click', function(){
            Room.memeModal('Testing Meme', 'https://i.kym-cdn.com/photos/images/newsfeed/001/296/357/663.gif');
        });

        //https://media.giphy.com/media/1ryrwFNXqNjC8/giphy.gif
    },

};

$(document).ready(function(){
    Room.init();
});