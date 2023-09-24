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
            /* White tiles */
        }

        .chessboard .cell:nth-child(even) {
            background-color: #4b5d67;
            /* Black tiles */
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

        /* Style the sidebar */
        .sidebar {
            padding: 20px;
        }
    </style>

    <div class="container-fluid">

        {{-- vue app div --}}
        <div id="app">

            <div class="row">

                {{-- left --}}
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
            },
            methods: {
                openModal() {
                    this.playerSubmissionModalVisibility = true;
                },
                closeModal() {
                    this.playerSubmissionModalVisibility = false;
                }
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
                submitAnswer();
            });

            $('.cell').each(function() {
                $(this).data('backgroundColor', $(this).css('background-color'));
            });


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


            // sets queen position data
            function setQueenPositions() {

                vm.queenPositions = [];

                $('.queen').each(function() {
                    let indexZero = $(this).data('index-zero');
                    let indexOne = $(this).data('index-one');
                    vm.queenPositions.push([indexZero, indexOne]);
                });

            }

            // submits player answer
            function submitAnswer() {

                setQueenPositions();

                // axios post request to backend

                axios.post('{{ route('validate_eight_queens_answer') }}', {
                        playerAnswer: vm.queenPositions
                    })
                    .then(response => {
                        if (response.data.answerIsIncorrect) {
                            Swal.fire({
                                title: 'Answer is wrong',
                                icon: 'error',
                            });
                        } else if (response.data.answerAlreadyFound) {
                            Swal.fire({
                                title: 'Answer is already found',
                                icon: 'warning',
                            });
                        } else {
                            Swal.fire({
                                title: 'Answer is correct !',
                                icon: 'success',
                                input: 'text',
                                inputPlaceholder: 'Enter your name',
                                showCancelButton: true,
                                confirmButtonText: 'Continue',
                                showLoaderOnConfirm: true,
                                preConfirm: (inputValue) => {
                                    return new Promise((resolve) => {
                                        // Simulate an AJAX request
                                        setTimeout(() => {
                                            if (inputValue === '') {
                                                Swal.showValidationMessage(
                                                    'Please enter your name !'
                                                );
                                            }
                                            resolve();
                                        }, 1000);
                                    });
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire('Your name: ' + result.value);
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        // Handle errors
                    });
            }

        });
    </script>
@endsection
