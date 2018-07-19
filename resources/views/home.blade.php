@extends('layouts.master')

@section('title', 'Home')

@section('header')
    @parent
<!--
    <p>This is appended to the master sidebar.</p>
    -->
@stop

@section('content')
    <div class="homepage">
        <div class="jumbotron text-center jumbotron-full">
            <h1>Create A Room?</h1>
            <div class="mobile-show alert alert-danger">
                <strong>This site is currently not optimized for your screen size.</strong><br/>
                Please revisit us in a desktop browser before attempting to play.
            </div>
            <form class="form-inline">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button href="/room?new=true" type="button" class="btn btn-lg btn-default linked-btn">Cash me ousside! Howbow dah?</button>
                    </div>
                </div>
            </form>
            <div class="help-text">
                Need to learn how to play?
                <a href="#howTo">Click here.</a>
            </div>
        </div>
        <div class="jumbotron text-center jumbotron-full jumbotron-alt">
            <h1>Join A Room</h1>
            <p>Do U No Da Wae?</p>
            <form class="form-inline" action="/room">
                <div class="input-group">
                    <input type="text" name="roomId" class="form-control" size="50" placeholder="Room Code" required
                        @if ($user && $user->roomId != null)
                            value="{{$user->roomId}}"
                        @endif
                    />
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-lg btn-danger">Yes, I No Da Wae!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="howTo" class="container-fluid">
        <a name="howTo"></a>
        <div class="text-center">
            <h1>How To Play</h1>
            <h4>tl;dr: This is the section for n00bs</h4>
        </div>
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h1>Setting up your player</h1>
                    </div>
                    <div class="panel-body">
                        <p>
                            When you arrived at codememes.fun we assigned you a random player name.
                            You'll see your player badge on the top right corner of this site.
                        </p>
                        <p class="text-center">
                            <img class='img-tutorial-user' src="/img/tutorial-user.png"/>
                        </p>
                        <p>
                            If you don't like the name we gave you then change it by
                            clicking on the <ion-icon class="edit-user" name="create"></ion-icon> icon.
                        </p>

                        <p>
                            This site plays sounds during gameplay.
                            To mute these sounds click the <ion-icon name="volume-high"></ion-icon> icon.
                        </p>

                        <p>
                            If you want to regenerate your player session,
                            click the <ion-icon name="log-out"></ion-icon> icon.
                        </p>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h1>Game rooms</h1>
                    </div>
                    <div class="panel-body">
                        <p>
                            To create a new room use the button on the homepage.
                        </p>
                        <p>
                            You'll need a minimum of <strong>4 players</strong> to start a game.
                            Invite some friends to your room by giving them this link:
                        </p>
                        <p class="text-center">
                            <img class="tutorial-img" src="/img/tutorial-invite.png"/>
                        </p>
                        <p>
                            or sending them the room code.
                        </p>
                        <p class="text-center">
                            <img class="tutorial-img" src="/img/tutorial-roomcode.png" style="max-width:200px;"/>
                        </p>
                        <div class="alert alert-info">
                            <strong>Streamer note:</strong>
                            you can hide your room code and invite links by
                            clicking on the <ion-icon name="eye"></ion-icon> icon.
                        </div>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h1>Starting a game</h1>
                    </div>
                    <div class="panel-body">
                        <h5>Join A Team</h5>
                        <p>
                            When you enter the room you'll be prompted to join either the blue or orange team.
                        </p>
                        <p>
                            Coordinate with the other players to create teams of near equal size.<br/>
                            Once you have a minimum of 2 players on each team your ready to start the game.<br/>
                            All players can see the New Game form.  Pick a designated player to setup the game.
                        </p>
                        <h5>Choose your captains</h5>
                        <p>
                            You'll be prompted to choose a captain for each team.  <br/>
                            The captain will be tasked with providing clues to their teammates.  <br/>
                            Non-captains will use those clues to guess which cards belong to their team.
                        </p>
                        <h5>Choose your game mode</h5>
                        <p>
                            You have two options for game modes.<br/>
                            In Memes mode your board will consist of images.  <br/>
                            In Words mode your board will consist of random words.
                        </p>
                        <h5>Start the game</h5>
                    </div>
                    <div class="panel-footer">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h1>The Game Board</h1>
                    </div>
                    <div class="panel-body">
                        <p>
                            When a new game starts a random board of 24 cards are added to your game board.  Each card will have one of the following designations.
                            <li>Card belongs to Orange Team</li>
                            <li>Card belongs to Blue Team</li>
                            <li>Card is Neutral</li>
                            <li>Card is a Rick Roll</li>
                        </p>
                        <p>
                            Only captains can see the designation of each card.  Non-captains will only see card designations after a card has been selected.<br/>
                            The game board will consist of:
                            <li>8 or 9 Orange Cards</li>
                            <li>8 or 9 Blue Cards</li>
                            <li>6 Neutral Cards</li>
                            <li>1 Rick Roll</li>
                        </p>
                        <div class="alert alert-info">
                            <strong>Note:</strong> The team that goes first will have 1 additional card.
                        </div>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h1>Gameplay Header</h1>
                    </div>
                    <div class="panel-body">
                        <h5>Turn Indicator</h5>
                        <p>
                            The turn indicator appears above the game board. It looks like this or this.
                        </p>
                        <p class="text-center">
                            <img src="/img/tutorial-turn.png" style="width:175px;"/>
                        </p>

                        <h5>Score Board</h5>
                        <p>
                            The score board will appear above the game board.  The colored numbers indicate how many cards each team has remaining on the board.  The first team to reach 0 (without selecting the Rick Roll) wins.
                        </p>
                        <p class="text-center">
                            <img src="/img/tutorial-score.png" style="width:150px;"/>
                        </p>

                        <h5>Captain's Clue Input</h5>
                        <p>
                            When a turn begins the corresponding team's captain will be prompted to enter a clue for their teammates.  The clue has two parts.
                            <ol>
                                <li>Enter a single word that is associated with one or more of their team's cards on the board.</li>
                                <li> Choose the number of cards that clue applies to.</li>
                            </ol>
                        </p>
                        <p class="text-center">
                            <img class='img-tutorial-clueform' src="/img/tutorial-clue-form.png"/>
                        </p>

                        <h5>Clue Display</h5>
                        <p>
                            When the clue is submitted it will be distributed to all players in the game.
                        </p>
                        <p class="text-center">
                            <img class='img-tutorial-clue' src="/img/tutorial-clue-view.png"/>
                        </p>

                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h1>Guessing Cards</h1>
                    </div>
                    <div class="panel-body">
                        <p>
                            After the team captain submits the clue all non-captain players on the corresponding team
                            will have the opportunity to select cards they believe match.
                        </p>
                        <p>
                            A team may take one guess for each card indicated by the captain in the clue.
                            They may take one additional bonus guess before the turn will be automatically ended.<br/>
                            <i>i.e. If the captain's clue was "Wet+2" then the team may make a total of 3 guesses.</i>
                        </p>
                        <p class="text-center">
                            <span class="help-text">In Memes Mode click the 'Select' button</span><br/>
                            <img src="/img/tutorial-meme-select.png" style="width:275px;"/>
                            <br/>
                            <br/>
                            <span class="help-text">In Words Mode click on the check icon</span><br/>
                            <img src="/img/tutorial-word-select.png" style="width:275px;"/>
                        </p>
                        <h5>Possible results of a guess:</h5>
                        <ul>
                            <li>
                                If the card belongs to the active team, they score and are allowed to continue their turn
                                (as long as they still have remaining guesses)
                            </li>
                            <li>
                                If the card belongs to the opposing team, the other team scores and the turn immediately ends.
                            </li>
                            <li>
                                If the card is neutral the turn immediately ends.
                            </li>
                            <li>
                                If the card is the Rick Toll the team immediately loses.
                            </li>
                        </ul>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h1>#Winning</h1>
                    </div>
                    <div class="panel-body">
                        <p>
                            The game ends when:
                            <ul>
                                <li>One team guesses all their cards</li>
                                <li>One team guesses the Rick Roll and immediately loses</li>
                            </ul>
                        </p>
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>








@stop