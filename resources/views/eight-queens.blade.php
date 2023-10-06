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

        .chessboard .cell.queen:hover {
            background-color: #3C2C3E !important;
            transform: scale(1.1);
        }

        .chessboard .cell:nth-child(odd) {
            background-color: #9dbda9;
        }

        .chessboard .cell:nth-child(even) {
            background-color: #4b5d67;
        }

        .queen {
            background-color: #3C2C3E !important;
            color: #FF06B7;
        }

        .queen::after {
            content: 'Q';
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
                        <h6>The Eight Queens Puzzle</h6>
                        <hr>
                        <div> Queens left</div>
                        <div class="d-flex justify-content-center align-items-center"
                            :class="{ 'text-danger': queensLeft === 0 }">
                            <div class="h1">♕</div>
                            <div>&nbsp;x @{{ queensLeft }}</div>
                        </div>
                        <hr>
                    </div>

                    <div class="mt-auto">
                        <button id="btn_recall" class="btn btn-lg w-50" style="background-color: #9dbda9be">RECALL</button>
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
                    playerName: '',
                    queensLeft: 8,
                    queenPositions: []
                };
            }
        });

        // mounting vue app
        const vm = app.mount('#app');


        $(document).ready(function() {

            // event listeners
            // ---------------

            $('.cell').on('click', function() {
                placeQueen(this);
            });

            $('#btn_recall').on('click', function() {
                recallQueens();
            });

            $('#btn_submit').on('click', function() {
                submitSolution();
            });

            $('.cell').each(function() {
                $(this).data('backgroundColor', $(this).css('background-color'));
            });


            // functions
            // ---------

            // places a queen on a selected cell
            function placeQueen(cell) {

                if (vm.queensLeft > 0 || $(cell).hasClass('queen')) {
                    if ($(cell).hasClass('queen')) {
                        $(cell).removeClass('queen');
                        vm.queensLeft++;
                    } else {
                        $(cell).addClass('queen');
                        vm.queensLeft--;
                    }
                } else {

                    Swal.fire({
                        title: 'Out of queens',
                        text: 'You can only place up to 8 queens at once',
                        icon: 'warning',
                        customClass: {
                            popup: 'retro-swal-popup-dark'
                        }
                    });
                }

            }

            // recalls all placed queens
            function recallQueens() {
                $('.queen').removeClass('queen');
                vm.queensLeft = 8;
            }


            // sets queen position data, into our Vue app.
            function setQueenPositions() {

                vm.queenPositions = [];

                $('.queen').each(function() {
                    let indexZero = $(this).data('index-zero');
                    let indexOne = $(this).data('index-one');
                    vm.queenPositions.push([indexZero, indexOne]);
                });

            }

            // submits player solution
            function submitSolution() {

                setQueenPositions();

                axios.post('{{ route('assess_eight_queens_solution') }}', {
                        playerSolution: vm.queenPositions
                    })
                    .then(response => {
                        if (response.data.solutionIsIncorrect) {
                            Swal.fire({
                                title: 'Solution incorrect',
                                text: 'Please try again',
                                icon: 'error',
                            });
                        } else if (response.data.solutionAlreadyFound) {
                            Swal.fire({
                                title: 'Solution already found',
                                text: 'A player previously found this solution',
                                icon: 'warning',
                            });
                        } else {

                            Swal.fire({
                                title: 'Solution is correct !',
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
                                                    '{{ route('submit_eight_queens_solution') }}', {
                                                        playerSolution: vm
                                                            .queenPositions,
                                                        playerName: inputValue
                                                    })
                                                .then(response => {
                                                    resolve(response.data);
                                                })
                                                .catch(error => {

                                                    // Validation responses at solution submission

                                                    let exceptionData = error
                                                        .response.data;

                                                    if (error.response && error
                                                        .response.status ===
                                                        400 && exceptionData
                                                        .type ===
                                                        'VALIDATION_EXCEPTION'
                                                    ) {

                                                        Swal.fire({
                                                            title: 'Invalid name!',
                                                            text: exceptionData
                                                                .message,
                                                            icon: 'error',
                                                        });

                                                    } else if (error.response &&
                                                        error.response
                                                        .status === 400 &&
                                                        exceptionData
                                                        .type ===
                                                        'QUERY_EXCEPTION') {

                                                        Swal.fire({
                                                            title: 'Submission error',
                                                            text: 'There was an error when trying to submit your data into the database! Please try again',
                                                            icon: 'error',
                                                        });

                                                    } else if (error.response) {
                                                        // 500 : General exception handling
                                                        Swal.fire({
                                                            title: 'Oops !',
                                                            text: 'Something went wrong. Please make sure everything is okay and try again',
                                                            icon: 'error',
                                                        });
                                                    }

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
                            });
                        }
                    })
                    .catch(error => {
                        // Validation repsonses at assessing the solution
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

        });
    </script>
@endsection
