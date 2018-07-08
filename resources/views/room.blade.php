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
                {{$roomInfo['room']->roomId}}
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
                <div class="jumbotron text-center bg-secondary">
                    <h2>Create New Game</h2>
                    <hr/>
                    <form id="newGameForm" class="form">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Orange Team Captain</label>
                                    <select class="form-control" id="orangeCaptain">
                                        <option value="">Select Captain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Blue Team Captain</label>
                                    <select class="form-control" id="blueCaptain">
                                        <option value="">Select Captain</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg btn-default mt-2">All Systems Go!</button>
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