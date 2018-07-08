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
            <form class="form-inline">
                <div class="input-group">
                    <div class="input-group-btn">
                        <button href="/room" type="button" class="btn btn-lg btn-default linked-btn">Cash me ousside! Howbow dah?</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="jumbotron text-center jumbotron-full jumbotron-alt">
            <h1>Join A Room</h1>
            <p>Do you know da way?</p>
            <form class="form-inline" action="/room">
                <div class="input-group">
                    <input type="text" name="roomId" class="form-control" size="50" placeholder="Room Code" required
                        @if ($user->roomId != null)
                            value="{{$user->roomId}}"
                        @endif
                    />
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-lg btn-danger">Yes, I know da way!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop