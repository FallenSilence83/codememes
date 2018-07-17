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
                    Room.refreshRoom();
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
                var clearGame = false;
                var isNewGame = false;
                if(undefined == jsonResponse.roomInfo.game || jsonResponse.roomInfo.game == null){
                    clearGame = true;
                }else{
                    isNewGame = (Room.gameState == null || Room.gameState.gameId != jsonResponse.roomInfo.game.gameId);
                }
                //pop sound if clue state changes
                if (!Room.userState.mute) {
                    try {
                        if(Room.gameState.clueWord != jsonResponse.roomInfo.game.clueWord){
                            var pop = document.getElementById("popAudio");
                            if (pop) {
                                pop.play();
                            }
                        }
                    } catch (error) {
                        console.log(error);
                    }

                }
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
        Room.refreshRoom();
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
        Room.refreshRoom();
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

    refreshRoom: function(){
        //evaluate whether create new game conditions have been met
        var orangeCount = Object.keys(Room.orangeState).length;
        var blueCount = Object.keys(Room.blueState).length;
        var orangeCaptain = $('#orangeCaptain').val();
        var blueCaptain = $('#blueCaptain').val();
        var alertMessages = '';
        var alertIcon = '<ion-icon name="egg"></ion-icon>';
        if(orangeCount < 2){
            alertMessages += '<div>' + alertIcon + ' Not enough folks on the orange team.</div>';
        }
        if(blueCount < 2){
            alertMessages += '<div>' + alertIcon + ' Not enough folks on the blue team.</div>';
        }
        if(orangeCaptain == null || orangeCaptain == ''){
            alertMessages += '<div>' + alertIcon + ' No orange captain selected.</div>';
        }
        if(blueCaptain == null || blueCaptain == ''){
            alertMessages += '<div>' + alertIcon + ' No blue captain selected.</div>';
        }
        $('.new-game-issues').html(alertMessages);
        if(alertMessages == ''){
            //no alerts, enable the submit button and hide the alerts
            $('#newGameAlert').hide();
            $('#newGameButton').attr('disabled', false);
        }else{
            $('#newGameAlert').show();
            $('#newGameButton').attr('disabled', true);
        }
    },

    refreshGame: function(clearGame, isNewGame){
        if(clearGame || isNewGame){
            $('#gameBoard').html('');
            Room.initSidebar();
        }
        if(Room.gameState != null && (Room.gameState.memes || Room.gameState.words)){
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

            //id captains and inject notifier classes
            if(Room.userState.userId == Room.gameState.blueCaptainId || Room.userState.userId == Room.gameState.orangeCaptainId){
                $('body').addClass('captain');
            }else{
                $('body').removeClass('captain');
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
            var mode = null;
            if(Room.gameState.memes && Room.gameState.memes.length) {
                mode = 'memes';
                if (isNewGame) {
                    $.each(Room.gameState.memes, function (key, meme) {
                        $('#gameBoard').append(Room.getMemeCard(meme));
                    });
                    command = "$('.meme-card').removeClass('rollIn');";
                    command += "$('.meme-card').removeClass('animated');";
                    setTimeout(command, 1000);
                    setTimeout("Room.freezeGifs()", 8000);
                } else {
                    $.each(Room.gameState.memes, function (key, meme) {
                        var elemMeme = $('#meme' + meme.memeId);
                        if (elemMeme) {
                            if (meme.selected) {
                                if (!elemMeme.hasClass('selected')) {
                                    elemMeme.addClass('selected');
                                    elemMeme.addClass('animated');
                                    elemMeme.addClass('bounce');
                                    elemMeme.addClass('infinite');
                                    var command = "$('#meme" + meme.memeId + "').removeClass('infinite');";
                                    command += "$('#meme" + meme.memeId + "').removeClass('bounce');";
                                    command += "$('#meme" + meme.memeId + "').removeClass('animated');";
                                    setTimeout(command, 4000);
                                    if (!Room.userState.mute) {
                                        try {
                                            if(Room.gameState.previousGuessResult == 'success'){
                                                successChime = document.getElementById("positiveAudio");
                                                if (successChime) {
                                                    successChime.play();
                                                }
                                            } else if(Room.gameState.previousGuessResult == 'fail'){
                                                failBuzz = document.getElementById("buzzAudio");
                                                if (failBuzz) {
                                                    failBuzz.play();
                                                }
                                            } else {
                                                pop = document.getElementById("popAudio");
                                                if (pop) {
                                                    pop.play();
                                                }
                                            }
                                        } catch (error) {
                                            console.log(error);
                                        }

                                    }
                                }
                            }

                            if (!elemMeme.hasClass(meme.status)) {
                                elemMeme.removeClass('default');
                                elemMeme.removeClass('orange');
                                elemMeme.removeClass('blue');
                                elemMeme.removeClass('neutral');
                                elemMeme.removeClass('rick');
                                elemMeme.addClass(meme.status);
                            }
                        }
                    });
                }
            }else if(Room.gameState.words && Room.gameState.words.length) {
                mode = 'words';
                if (isNewGame) {
                    $.each(Room.gameState.words, function (key, word) {
                        $('#gameBoard').append(Room.getWordCard(word));
                    });
                    command = "$('.word-card').removeClass('rollIn');";
                    command += "$('.word-card').removeClass('animated');";
                    setTimeout(command, 1000);
                } else {
                    $.each(Room.gameState.words, function (key, word) {
                        var elemWord = $('#word' + word.wordId);
                        if (elemWord) {
                            if (word.selected) {
                                if (!elemWord.hasClass('selected')) {
                                    elemWord.addClass('selected');
                                    elemWord.addClass('animated');
                                    elemWord.addClass('bounce');
                                    elemWord.addClass('infinite');
                                    var command = "$('#word" + word.wordId + "').removeClass('infinite');";
                                    command += "$('#word" + word.wordId + "').removeClass('bounce');";
                                    command += "$('#word" + word.wordId + "').removeClass('animated');";
                                    setTimeout(command, 4000);
                                    if (!Room.userState.mute) {
                                        try {
                                            if(Room.gameState.previousGuessResult == 'success'){
                                                successChime = document.getElementById("positiveAudio");
                                                if (successChime) {
                                                    successChime.play();
                                                }
                                            } else if(Room.gameState.previousGuessResult == 'fail'){
                                                failBuzz = document.getElementById("buzzAudio");
                                                if (failBuzz) {
                                                    failBuzz.play();
                                                }
                                            }
                                        } catch (error) {
                                            console.log(error);
                                        }
                                    }
                                }
                            }

                            if (!elemWord.hasClass(word.status)) {
                                elemWord.removeClass('default');
                                elemWord.removeClass('orange');
                                elemWord.removeClass('blue');
                                elemWord.removeClass('neutral');
                                elemWord.removeClass('rick');
                                elemWord.addClass(word.status);
                            }
                        }
                    });
                }
            }
            if(isNewGame) {
                Room.memeBinding();
            }
            $('#welcomePanel').hide();
            $('#createGame').hide();
            if(mode == 'memes'){
                if(!$('#sidebar').hasClass('active')){
                    Room.toggleSidebar();
                }
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
        var icon = (Room.isUserCaptain(user.userId)) ? 'ribbon' : 'person';
        var html = '' +
            '<li class="li-user">' +
            '    <span>' +
            '        <ion-icon name="'+icon+'"></ion-icon>' +
            '        ' + user.displayName + '';
        /*if(Room.roomState.hostId == user.userId){
            html += '            <span class="user-desig">(host)</span>';
        }*/
        html += '' +
            '    </span>' +
            '</li>';
        return html;
    },

    isUserCaptain: function(userId){
        var isCaptain = false;
        if(userId && Room.gameState && (userId == Room.gameState.blueCaptainId || userId == Room.gameState.orangeCaptainId)){
            isCaptain = true;
        }
        return isCaptain;
    },

    getMemeCard: function(meme){
        var html = '<div id="meme'+meme.memeId+'" data-memeid='+meme.memeId+' class="meme-card animated rollIn ';
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

    getWordCard: function(word){
        var html = '<div id="word'+word.wordId+'" data-wordid='+word.wordId+' class="word-card animated rollIn ';
        if(word.selected){
            html += ' selected ';
        }
        html += word.status+' col-sm-3 col-xs-6">';
        html += '<div class="word-thumbnail" title="'+word.text+'">';
        html += word.text;

        html += '<div class="select-line">';
        html += '<ion-icon class="icon-word-select" name="checkmark-circle" data-wordid="'+word.wordId+'"></ion-icon>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    },

    joinTeam: function(team){
        var url = '/room/team?team='+team;
        Room.standardAjax(url);
    },

    newGame: function(orangeCaptain, blueCaptain, gameMode, mods){
        var url='/room/newgame?orangeCaptainId='+orangeCaptain+'&blueCaptainId='+blueCaptain+'&mode='+gameMode+'&mods='+mods;
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

    guessWord: function(wordId){
        var url = '/room/guess?wordId=' + wordId;
        Room.standardAjax(url);
    },

    freezeGifs: function(){
        [].slice.apply(document.images).filter(is_gif_image).map(freeze_gif);
    },

    memeModal: function(label, imageUrl){
        $('#memeModal .modal-title').html(''+label);
        $('#memeModal img').attr('src', imageUrl);
        $('#memeModal').modal('show');
    },

    youTubeModal: function(label, videoKey){
        $('#youTubeModal .modal-title').html(''+label);
        var url = 'https://www.youtube.com/embed/' + videoKey + '?rel=0&amp;autoplay=1';
        $('#youTubeModal iframe').attr('src', url);
        $('#youTubeModal').modal('show');
    },

    rickRoll: function(){
        $('#rickModal').modal('show');
        $('#rickModal iframe').attr('src', 'https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0');
    },

    memeBinding: function(){
        $('.btn-meme-select').on('click', function(e){
            var memeId = $(this).data('memeid');
            Room.guess(memeId);
        });

        $('.icon-word-select').on('click', function(e){
            var wordId = $(this).data('wordid');
            Room.guessWord(wordId);
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
            var gameMode = $("#newGameForm input[name='gameMode']:checked").val();
            var modSelects = $('.mod-checkbox');
            var mods = '';
            var modSep = '';
            $('.mod-checkbox').each(function( index ) {
                if($(this).prop( "checked")){
                    mods += modSep + $(this).val();
                    modSep = '|';
                }
            });
            if(blueCaptain && orangeCaptain){
                Room.newGame(orangeCaptain, blueCaptain, gameMode, mods);
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
            $('#rickModal iframe').attr('src', '/loading');
        });

        $('#youTubeModal').on('hidden.bs.modal', function () {
            $('#youTubeModal iframe').attr('src', '/loading');
        });

        $('#createGame button.close').on('click', function() {
            $('#createGame').hide();
        });

        $('.copy-invite').on('click', function(){
            var copyText = document.getElementById("inviteLink");
            copyText.select();
            document.execCommand("copy");
            $('#copyInvite').html('Copied');
            setTimeout("$('#copyInvite').html('Copy');", 3000);
        });

        $('.invite-toggle').on('click', function(){
            var currentName = $(this).attr('name');
            var hideInvites = null;
            if(currentName == 'eye-off'){
                //hide
                $('.invite-field').hide();
                $('.invite-toggle').attr('name', 'eye');
                hideInvites = "true";
            }else{
                //show
                $('.invite-field').show();
                $('.invite-toggle').attr('name', 'eye-off');
                hideInvites = "false";
            }
            //update the user in session
            $.ajax({
                url: '/user/update',
                method: "POST",
                data: { hideInvites: hideInvites },
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

                }
            });
        });
    },

};

$(document).ready(function(){
    Room.init();
});

function is_gif_image(i) {
    return /^(?!data:).*\.gif/i.test(i.src);
}

function freeze_gif(i) {
    var c = document.createElement('canvas');
    var w = c.width = i.width;
    var h = c.height = i.height;
    c.getContext('2d').drawImage(i, 0, 0, w, h);
    try {
        i.src = c.toDataURL("image/gif"); // if possible, retain all css aspects
    } catch(e) { // cross-domain -- mimic original with all its tag attributes
        for (var j = 0, a; a = i.attributes[j]; j++)
            c.setAttribute(a.name, a.value);
        i.parentNode.replaceChild(c, i);
    }
}