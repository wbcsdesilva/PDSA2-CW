@extends('layouts.app')

@section('content')
    <style>
        .chessboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .chessboard {
            width: 100%;
            height: 100%;
            padding: 3px;
            display: flex;
            flex-wrap: wrap;
        }

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
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .chessboard .cell:hover {
            background-color: #FF06B7 !important;
            transform: scale(1.1);
        }

        .chessboard .cell.knight:hover {
            background-color: #3C2C3E !important;
            transform: scale(1.1);
        }

        .chessboard .cell:nth-child(odd) {
            background-color: #9dbda9;
        }

        .chessboard .cell:nth-child(even) {
            background-color: #4b5d67;
        }

        .knight {
            background-color: #3C2C3E !important;
            color: #FF06B7;
        }

        .visited {
            background-color: #3C2C3E !important;
        }

        .knight::after {
            content: 'K';
        }

        .retro-swal-popup-dark {
            background-color: #4b5d67;
            color: #bdf2d5;
        }

        .sidebar {
            padding: 20px;
        }
    </style>

    <div class="container-fluid">

        {{-- vue app mount --}}
        <div id="app">

            <div class="row">

                {{-- left --}}
                <div class="col-7">
                    <div class="chessboard-container">

                        <div class="chessboard">
                            @php
                                for ($i = 0; $i < 8; $i++) {
                                    for ($j = 0; $j < 8; $j++) {
                                        $backgroundColor = ($i + $j) % 2 == 0 ? '#9dbda9' : '#4b5d67';
                                        echo "<div class='cell' id='cell_$i$j' name='cell_$i$j' data-index-zero='$i' data-index-one='$j' style='background-color: $backgroundColor;'></div>";
                                    }
                                }
                            @endphp
                        </div>

                    </div>
                </div>

                {{-- right  --}}
                <div class="col-5 retro-bg-dark d-flex flex-column text-center">
                    <div class="sidebar">
                        <h6>The Knight's Tour</h6>
                        <hr>
                    </div>

                    <div class="mt-auto mb-4">
                        <button id="btn_submit" class="btn btn-lg w-50" style="background-color: #9dbda9be">SUBMIT</button>
                    </div>
                </div>

            </div>

        </div>


    </div>

    <script type="module">
        // vue app
        const app = Vue.createApp({
            data() {
                return {
                    playerName: '',
                    knightStart: @json($knightStart),
                    nextValidMoves: '',
                    chessboard: @json($chessboard),
                    tour: @json($tour),
                    playerSolution: '',
                };
            },
            mounted() {
                const cellId = `#cell_${this.knightStart[0]}${this.knightStart[1]}`;
                const startCell = document.querySelector(cellId);
                if (startCell) {
                    startCell.classList.add('knight');
                    startCell.classList.add('visited');
                }
            }
        });

        // mounting vue app
        const vm = app.mount('#app');

        $(document).ready(function() {

            // event listeners
            // ---------------

            $('.cell').on('click', function() {
                placeKnight(this);
            });

            $('#btn_submit').on('click', function() {
                submitSolution();
            });

            // functions
            // ---------

            function placeKnight(cell) {

                let currentIndexZero = parseInt($(cell).data('index-zero'));
                let currentIndexOne = parseInt($(cell).data('index-one'));

                let knightCell = $('.knight');
                let currentKnightIndexZero = parseInt(knightCell.data('index-zero'));
                let currentKnightIndexOne = parseInt(knightCell.data('index-one'));

                console.log(currentIndexZero, currentIndexOne);
                console.log(currentKnightIndexZero, currentKnightIndexOne);

                let validMoves = [
                    [currentKnightIndexZero + 2, currentKnightIndexOne + 1],
                    [currentKnightIndexZero + 1, currentKnightIndexOne + 2],
                    [currentKnightIndexZero - 1, currentKnightIndexOne + 2],
                    [currentKnightIndexZero - 2, currentKnightIndexOne + 1],
                    [currentKnightIndexZero - 2, currentKnightIndexOne - 1],
                    [currentKnightIndexZero - 1, currentKnightIndexOne - 2],
                    [currentKnightIndexZero + 1, currentKnightIndexOne - 2],
                    [currentKnightIndexZero + 2, currentKnightIndexOne - 1]
                ];

                let isValidMove = false;

                for (let i = 0; i < validMoves.length; i++) {
                    if (validMoves[i][0] === currentIndexZero && validMoves[i][1] === currentIndexOne) {
                        isValidMove = true;
                        break;
                    }
                }

                // Check if the move is valid and the cell is not visited
                if (isValidMove && !$(cell).hasClass('visited')) {
                    $(cell).addClass('knight');
                    $(cell).addClass('visited');
                    $(knightCell).removeClass('knight');
                    $(knightCell).addClass('visited');
                } else {
                    Swal.fire({
                        title: 'Invalid move!',
                        text: 'Please place the knight on a reachable unvisited cell',
                        icon: 'error',
                    });
                }


            }

            function submitSolution() {

                Swal.fire({
                    title: 'Game over!',
                    text: 'Oops! You have no valid moves left. Try again?',
                    icon: 'error',
                });


                // axios.post('{{ route('submit_knights_tour_solution') }}', {
                //         playerName: vm.playerName
                //     }).then(response => {

                //     })
                //     .catch(error => {
                //         console.error(error);
                //     });
            }

        });
    </script>
@endsection
