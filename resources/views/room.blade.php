@extends('layouts.master')

@section('title', 'Room')

@section('sidebar')
    @parent
        <!-- Sidebar  -->
        <nav id="sidebar">

            <button type="button" id="sidebarCollapse" class="btn sidebar-collapse-btn">
                <ion-icon name="arrow-round-back"></ion-icon>
                <!-- <ion-icon name="arrow-round-forward"></ion-icon> -->
            </button>
            <div class="sidebar-header">
                <h3>
                    Game Room
                    <ion-icon id="sidebarNewGame" class="show-new-game" name="sync" title="New Game"></ion-icon>
                </h3>
                <strong>Room Code:</strong>
                <span class="invite-field" @if ($user->hideInvites)style="display:none;"@endif;>{{$roomInfo['room']->roomId}}</span>
                <ion-icon class="invite-toggle"
                          @if ($user->hideInvites)
                            name="eye"
                          @else
                            name="eye-off"
                          @endif;
                          title="Toggle Invite Visibility"></ion-icon>
            </div>

            <ul class="list-unstyled components">
                <li class="orange-team">
                    <a href="#orangeSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                        <ion-icon name="people"></ion-icon>
                        Orange Team
                        <span class="player-count orange-count"></span>
                    </a>
                    <ul class="collapse list-unstyled show" id="orangeSubmenu">
                        <li class="li-join">
                            <a class='join-team' href="#" data-team="orange">
                                <strong>
                                    <ion-icon name="person-add"></ion-icon> JOIN TEAM
                                </strong>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="blue-team">
                    <a href="#blueSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                        <ion-icon name="people"></ion-icon>
                        Blue Team
                        <span class="player-count blue-count"></span>
                    </a>
                    <ul class="collapse list-unstyled show" id="blueSubmenu">
                        <li class="li-join">
                            <a class='join-team' href="#" data-team="blue">
                                <strong>
                                    <ion-icon name="person-add"></ion-icon> JOIN TEAM
                                </strong>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#playersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <ion-icon name="person"></ion-icon>
                        Players
                        <span class="player-count all-count"></span>
                    </a>
                    <ul class="collapse list-unstyled" id="playersSubmenu">
                    </ul>
                </li>
            </ul>

        </nav>
@stop

@section('content')
    <div class="room-content container-fluid">
        <div class="row game-nav-mobile">
            <div class="col-5 nav-turn-msg">
                <div class="help-text">Turn:</div>
                <div class="turn-msg-section">
                    <span class="turn-msg orange-turn-msg">Orange</span>
                    <span class="turn-msg blue-turn-msg">Blue</span>
                </div>
            </div>
            <div class="col-7 nav-score">
                <div class="help-text">Remaining:</div>
                <div class="score-board">
                    <span class="score orange-score"></span>
                    -
                    <span class="score blue-score"></span>
                </div>
            </div>
            <div class="col-12 nav-clue-form">
                <div class="help-text">Enter a clue for your team:</div>
                <div class="input-group clue-form ">
                    <input type="text" class="form-control clue_word" name="clue_word" placeholder="Clue Word" aria-label="Clue Word" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <select class="custom-select clue_number" name="clue_number">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="0">0</option>
                        </select>
                        <button class="btn clue_submit" type="button">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-12 nav-clue">
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
            </div>
        </div>
        <div id="welcomePanel" class="row pb-2">
            <div class="col-lg-6 col-md-12">
                <div id="orangeTeamWelcome" class="jumbotron text-center ">
                    <h2>Orange Team</h2>
                    <hr/>
                    <ul class="collapse list-unstyled show" id="orangeTeamSelect">
                    </ul>
                    <form class="form-inline">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button href="#" type="button" class="btn btn-lg btn-default join-team" data-team="orange">Join Orange Team</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="blueTeamWelcome" class="col-lg-6 col-md-12">
                <div class="jumbotron text-center  jumbotron-alt">
                    <h2>Blue Team</h2>
                    <hr/>
                    <ul class="collapse list-unstyled show" id="blueTeamSelect">
                    </ul>
                    <form class="form-inline">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-lg btn-default join-team" data-team="blue">Join Blue Team</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="createGame" class="row ">
            <button type="button" class="close" title="Close">
                <span aria-hidden="true">×</span>
            </button>
            <div class="col">
                <div class="jumbotron text-center jumbotron-neutral">
                    <h2>Create New Game</h2>
                    <hr/>
                    <div class="invite-container">
                        <h5 >
                            <span class="invite-label blue-label">Invite Friends</span>
                            <ion-icon class="invite-toggle"
                                      @if ($user->hideInvites)
                                          name="eye"
                                      @else
                                          name="eye-off"
                                      @endif;
                                      title="Toggle Invite Visibility"></ion-icon>
                        </h5>
                        <div class="invite-field input-group" @if ($user->hideInvites)style="display:none;"@endif;>
                            <input id="inviteLink" type="text" class="form-control" value="{{$baseUrl}}/room?roomId={{$roomId}}"/>
                            <div class="input-group-btn">
                                <button id="copyInvite" type="button" class="copy-invite btn btn-lg btn-info">Copy</button>
                            </div>
                        </div>
                    </div>
                    <form id="newGameForm" class="form">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="new-game-label orange-label" for="exampleFormControlSelect1">Orange Team Captain:</label>
                                    <select class="form-control" id="orangeCaptain">
                                        <option value="">Select Captain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="new-game-label orange-label">Choose Game Mode:&nbsp;&nbsp;</label><br/>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gameMode" id="modeMemes" value="memes" checked>
                                        <label class="form-check-label" for="inlineRadio1">Memes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gameMode" id="modeWords" value="words">
                                        <label class="form-check-label" for="inlineRadio2">Words</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="new-game-label blue-label" for="exampleFormControlSelect1">Blue Team Captain:</label>
                                    <select class="form-control" id="blueCaptain">
                                        <option value="">Select Captain</option>
                                    </select>
                                </div>
                                <div id="newGameAlert" class="alert alert-warning ml-auto mr-auto" role="alert">
                                    <strong>The teams aren't quite ready to start a game.</strong>
                                    <div class="new-game-issues">
                                        <div>
                                            <ion-icon name="egg"></ion-icon>
                                            Not enough folks on the blue team
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="new-game-label blue-label">Activate Game Mod?&nbsp;&nbsp;</label><br/>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" class="custom-control-input mod-checkbox" id="modFamous" value="famous">
                                        <label class="custom-control-label" for="modFamous">Famous People</label>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    Games mods add new categories of cards to the game board.
                                    When active, 25&percnt; of cards will come from the mod category.
                                </div>
                            </div>
                        </div>
                        <button id="newGameButton" type="submit" class="btn btn-lg btn-default mt-2" disabled>All Systems Go!</button>
                    </form>
                </div>
            </div>
        </div>
        <div id="gameBoard" class="row">
        </div>
    </div>
@stop

@section('scripts')
    @parent
    <script src="/js/room.js"></script>
@stop