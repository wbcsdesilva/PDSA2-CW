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
                                        echo "<div class='cell' id='cell_${i}_${j}' name='cell_$i$j' data-index-zero='$i' data-index-one='$j' style='background-color: $backgroundColor;'></div>";
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

                    <div class="mt-auto">
                        <button id="btn_autoplay" class="btn btn-lg w-50"
                            style="background-color: #9dbda9be">AUTOPLAY</button>
                    </div>
                    <div class="mt-3 mb-4">
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
                    knightStart: @json($knightStart),
                    tour: @json($tour),
                    playerSolution: [@json($knightStart)],
                };
            },
            methods: {
                initializeBoard() {
                    const startCellId = `#cell_${this.knightStart[0]}_${this.knightStart[1]}`;
                    const startCell = document.querySelector(startCellId);
                    if (startCell) {
                        startCell.classList.add('knight');
                        startCell.classList.add('visited');
                    }
                },
                addMovePosition(move) {
                    this.playerSolution.push(move);
                }
            },
            mounted() {
                this.initializeBoard();
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

            $('#btn_autoplay').on('click', function() {
                Swal.fire({
                    title: 'Auto Play',
                    text: 'Running auto play will run the knight on its tour for you. Are you sure you want to start the auto play?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        autoPlay();
                    }
                });
            });

            // functions
            // ---------

            function placeKnight(cell) {

                let currentIndexZero = parseInt($(cell).data('index-zero'));
                let currentIndexOne = parseInt($(cell).data('index-one'));

                let knightCell = $('.knight');
                let currentKnightIndexZero = parseInt(knightCell.data('index-zero'));
                let currentKnightIndexOne = parseInt(knightCell.data('index-one'));

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

                    // add the move position to the player solution
                    let move = [$(cell).data('index-zero'), $(cell).data('index-one')];

                    vm.addMovePosition(move);

                } else {
                    Swal.fire({
                        title: 'Invalid move!',
                        text: 'Please place the knight on a reachable unvisited cell',
                        icon: 'error',
                    });
                }


            }

            // autoplays the game for you. (put this up to demonstrate the algorithm at the VIVA)
            function autoPlay() {

                let tour = vm.tour;

                if (tour != null) {

                    // first reset the board before you start
                    resetBoard();

                    for (let moveNum = 1; moveNum <= 63; moveNum++) {
                        let found = false;

                        // Iterate through the tour to find the index position of the current moveNum
                        for (let i = 0; i < 8; i++) {
                            for (let j = 0; j < 8; j++) {
                                if (tour[i][j] === moveNum) {

                                    // found the cell id !
                                    const cellId = `cell_${i}_${j}`;
                                    const cell = $(`#${cellId}`);

                                    // call place knight using the cell
                                    // Delay each move by 500 milliseconds
                                    setTimeout(() => {
                                        placeKnight(cell);
                                    }, 500 * moveNum);

                                    found = true;
                                    break;
                                }
                            }

                            if (found) {
                                break;
                            }
                        }
                    }

                } else {
                    Swal.fire({
                        title: 'No tour available!',
                        text: 'Uh oh! Looks like there are no valid tours available for this position. Refresh the page and try again.',
                        icon: 'error',
                    });
                }
            }


            // TODO: set up submit solution function
            function submitSolution() {

                axios.post('{{ route('assess_knights_tour_solution') }}', {
                        knightStart: vm.knightStart,
                        playerSolution: vm.playerSolution,
                    })
                    .then(response => {
                        if (!response.data.solutionIsCorrect) {
                            Swal.fire({
                                title: 'Solution incorrect!',
                                text: 'Please try again',
                                icon: 'error',
                            });
                        } else {

                            Swal.fire({
                                title: 'Tour complete!',
                                icon: 'success',
                                input: 'text',
                                inputPlaceholder: 'Enter your name',
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                showLoaderOnConfirm: true,
                                preConfirm: (inputValue) => {
                                    return new Promise((resolve, reject) => {
                                        setTimeout(() => {
                                            axios.post(
                                                    '{{ route('submit_knights_tour_solution') }}', {
                                                        playerName: inputValue,
                                                        knightStart: vm.knightStart,
                                                        playerSolution: vm
                                                            .playerSolution,
                                                    })
                                                .then(response => {
                                                    resolve(response.data);
                                                    console.log(response.data
                                                        .message);
                                                })
                                                .catch(error => {
                                                    reject(
                                                        'Error: Unable to save player data :('
                                                    );
                                                });
                                        }, 1000);
                                    });
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire('Done', 'Your player data was saved successfully',
                                        'success').then(() => {
                                        window.location.reload();
                                    });
                                }
                            }).catch((error) => {
                                Swal.fire('Oops', 'Your player data could not be saved', 'error');
                            });

                        }
                    })
                    .catch(error => {

                        // 400 : ValidationException handling
                        if (error.response && error.response.status === 400) {

                            let exceptionData = error.response.data;

                            Swal.fire({
                                title: 'Solution Invalid!',
                                text: exceptionData.message,
                                icon: 'error',
                            });
                        } else if (error.response) {
                            // 500 : General exception handling
                            Swal.fire({
                                title: 'Something went wrong !',
                                text: 'Please make sure everything is okay and try again',
                                icon: 'error',
                            });
                        }

                    });
            }


            // resets the board back to it's original state (remove knight, visits, and reinitialize)
            function resetBoard() {
                $('.cell').removeClass('knight visited');
                vm.initializeBoard();
            }

        });
    </script>
@endsection
