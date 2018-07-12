<html>
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122073458-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-122073458-1');
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="CodeMemes is a live multi-player browser game.  Join fiends to play a game about guessing which memes belong to your team.">
    <meta name="keywords" content="CodeMemes, CodeNames, Memes, Game, Multiplayer">
    <meta name="author" content="FallenSilence83">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CodeMemes - @yield('title')</title>
    <link rel="shortcut icon" href="/img/favicon.ico" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Material Design Bootstrap
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.4/css/mdb.min.css" rel="stylesheet">-->
    <link href="/css/mdb-custom.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/master.css">
</head>
<body id="codeMemes" data-spy="scroll" data-target=".navbar" data-offset="60" class="">

    @section('header')
    <nav class="navbar navbar-expand-md navbar-default navbar-fixed-top navbar-header">

        <a class="navbar-brand" href="/"><img src="/img/cm_logo_header.jpeg" height="100"/></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto ml-auto game-nav">
                <li class="nav-item nav-turn-msg">
                    <div class="help-text">Turn:</div>
                    <div class="turn-msg-section">
                        <span class="turn-msg orange-turn-msg">Orange's Turn</span>
                        <span class="turn-msg blue-turn-msg">Blue's Turn</span>
                    </div>
                </li>
                <li class="nav-item nav-score">
                    <div class="help-text">Remaining Memes:</div>
                    <div class="score-board">
                        <span class="score orange-score"></span>
                        -
                        <span class="score blue-score"></span>
                    </div>
                </li>
                <li class="nav-item nav-clue-form">
                    <div class="help-text">Enter a clue for your team:</div>
                    <div class="input-group clue-form ">
                        <input type="text" class="form-control" name="clue_word" id="clueWord" placeholder="Clue Word" aria-label="Clue Word" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <select class="custom-select" name="clue_number" id="clueNumber">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>\
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="0">0</option>
                            </select>
                            <button id="clueSubmit" class="btn " type="button">Submit</button>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-clue">
                    <div class="help-text">
                        <span class="blueTeamLabel">Blue's</span><span class="orangeTeamLabel">Orange's</span>
                        Clues:
                    </div>
                    <div class="display-clue">
                        <span class="clue-available">
                            <span
                                class="display-clue-word"></span><span
                                class="display-clue-number"></span><span
                                class="display-clue-pass">Pass Turn</span>
                        </span>
                        <span class="display-clue-waiting clue-unavailable">Waiting For Captain...</span>
                    </div>
                </li>
                <li class="nav-item nav-game">
                    <div class="help-text">&nbsp;</div>
                    <button class="btn btn-secondary btn-new-game show-new-game" type="button">New Game</button>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item user-nav">
                    @if ($user->displayName != null)
                        <div class="help-text user-actions">
                            <ion-icon class="edit-user" name="create"></ion-icon>
                            <ion-icon class="mute-button"
                                  @if ($user->mute)
                                      name="volume-off"
                                  @else
                                      name="volume-high"
                                  @endif;
                                ></ion-icon>
                            <a class="logout" href="#" title="Reset">
                                <ion-icon name="log-out"></ion-icon>
                            </a>
                        </div>
                        <div class="help-text">
                            You are logged in as: &nbsp;&nbsp;
                        </div>
                        <div>
                        <span class="user-badge badge badge-secondary">
                            {{$user->displayName}}
                        </span>
                        </div>
                    @endif
                </li>
            </ul>
        </div>
    </nav>
    @show


    <div class="wrapper">
        @section('sidebar')

        @show
        <div class="page_content">
            @yield('content')
        </div>
    </div>

    @section('footer')
        <footer class="container-fluid text-center">
            <p>
                <a href="#codeMemes" title="To Top">
                    <ion-icon name="arrow-round-up" size="large"></ion-icon>
                </a>
            </p>
            <p class="footer-text">CodeMemes is a project created by Donald J. Trump's Space Force<sup>&trade;</sup></p>
            <p class="footer-text">
                All credit to our great leader. Complaints? Contact
                <a href="mailto:mueller@fakenews.com?Subject=CodeMemes%20SUX%20LOL">mueller@fakenews.com</a>
            </p>
        </footer>
    @show

    @section('modal')
        <div id="memeModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="memeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content" style="text-align:center;">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src=""/>
                    </div>
                </div>
            </div>
        </div>
        <div id="youTubeModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="youTubeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content" style="text-align:center;">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe width="560" height="315" src="" frameborder="0" allow="autoplay; encrypted-media"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div id="rickModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="rickModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content" style="text-align:center;">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            You've Been Rick Rolled!
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe width="560" height="315" src="" frameborder="0" allow="autoplay; encrypted-media"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div id="editUserModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm" >
                <div class="modal-content" style="text-align:center;">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Edit User
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm" class="form">
                            <div class="form-group">
                                <label for="displayName">Display Name:</label>
                                <input id="displayNameEdit" type="text" class="form-control" value="{{$user->displayName}}"/>
                            </div>
                            <button id="editUserSubmit" type="submit" class="btn btn-sm btn-default mt-2">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @show

    @section('scripts')

    <audio id="successAudio">
        <source src="/audio/success.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    <audio id="failAudio">
        <source src="/audio/fail.mp3" type="audio/mpeg">
    </audio>
    <audio id="popAudio">
        <source src="/audio/pop.mp3" type="audio/mpeg">
    </audio>

    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.4/js/mdb.min.js"></script>
    <script src="https://unpkg.com/ionicons@4.2.4/dist/ionicons.js"></script>
    <script src="/js/master.js"></script>
    @show
</body>
</html>