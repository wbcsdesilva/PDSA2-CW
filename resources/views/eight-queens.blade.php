@extends('layouts.app')

@section('content')
    <style>
        /* Adjust chessboard size */
        .chessboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Ensure it takes full viewport height */
        }

        .chessboard {
            width: 100%;
            height: 100%;
            padding: 3px;
            /* Add some padding to separate cells */
            display: flex;
            flex-wrap: wrap;
        }

        /* Style the chessboard grid */
        .chessboard .cell {
            width: 12.5%;
            height: 12.5%;
            box-sizing: border-box;
            float: left;
            text-align: center;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5em;
        }

        .chessboard .cell:nth-child(odd) {
            background-color: #9dbda9;
            /* White tiles */
        }

        .chessboard .cell:nth-child(even) {
            background-color: #4b5d67;
            /* Black tiles */
        }

        /* Style the sidebar */
        .sidebar {
            padding: 20px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-7">
                <div class="chessboard-container">
                    <div class="chessboard">
                        @php
                            for ($i = 0; $i < 8; $i++) {
                                for ($j = 0; $j < 8; $j++) {
                                    // Determine the background color based on row and column indices
                                    $backgroundColor = ($i + $j) % 2 == 0 ? '#9dbda9' : '#4b5d67';
                                    echo "<div class='cell' id='cell_$i$j' name='cell_$i$j' data-index-zero='$i' data-index-one='$j' style='background-color: $backgroundColor;'></div>";
                                }
                            }
                        @endphp
                    </div>
                </div>
            </div>
            <div class="col-5 retro-bg-dark">
                <div class="sidebar">
                    <!-- Buttons: Submit, Reset, Exit -->

                    <div class="d-flex justify-content-center">
                        <h6>The Eight Queens Puzzle</h6>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {


        });
    </script>
@endsection
