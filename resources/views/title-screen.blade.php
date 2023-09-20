@extends('layouts.app')

@section('content')
    {{-- custom styles --}}
    <style>
        /* Custom styles to center the container */
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
            /* Add a smooth transition */
        }

        .menu-item:hover {
            animation: blink 0.8s infinite;
            transform: scale(1.1);
            /* Increase size slightly on hover */
        }
    </style>



    <div class="container">

        <!-- Title -->
        <div class="row mb-5">
            <h1>Just 5 Quests</h1>
        </div>

        <!-- Games -->
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('knights-tour') }}">The Knight's Tour</a></h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('longest-sequence') }}">Longest Common Sequence</a>
            </h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('eight-queens') }}">Eight Queens Puzzle</a></h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('tic-tac-toe') }}">Tic Tac Toe</a></h5>
        </div>
        <div class="row">
            <h5 class="menu-item"><a class="nav-anchor" href="{{ route('shortest-path') }}">The Shortest Path</a></h5>
        </div>

    </div>
@endsection
