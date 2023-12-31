@extends('layouts.app')

@section('content')
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
        }

        .row {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .menu-item {
            cursor: pointer;
            transition: transform 0.3s;
        }

        .menu-item:hover {
            animation: blink 0.8s infinite;
            transform: scale(1.1);
        }
    </style>



    <div class="container">

        {{-- Title --}}
        <div class="row mb-5">
            <h1>Just 5 Quests</h1>
        </div>

        {{-- Games --}}
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('knights_tour') }}">The Knight's Tour</a></h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('longest_common_sequence') }}">Longest Common
                    Sequence</a>
            </h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('eight_queens') }}">Eight Queens Puzzle</a></h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('tic_tac_toe') }}">Tic Tac Toe</a></h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('shortest_path') }}">The Shortest Path</a></h5>
        </div>

    </div>
@endsection
