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

        .tic-tac-toe-board {
            width: 100%;
            height: 100%;
            display: flex;
            flex-wrap: wrap;
        }

        .tic-tac-toe-board .cell {
            background-color: #bdf2d5;
            color: #4b5d67;
            width: 33.33%;
            height: 150px;
            box-sizing: border-box;
            float: left;
            text-align: center;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .border-retro-dark {
            border-style: solid;
            border-width: 5px;
            border-color: #4b5d67
        }

        .invisible {
            display: none;
        }
    </style>


    <div class="container retro-bg-dark p-5 rounded">

        {{-- vue app --}}
        <div id="app">

            <h3>Tic Tac Toe</h3>

            <div id="board" class="tic-tac-toe-board border-retro-dark">

                @for ($i = 0; $i < 3; $i++)
                    @for ($j = 0; $j < 3; $j++)
                        <div class="cell border-retro-dark" data-row-index={{ $i }}
                            data-col-index={{ $j }}>
                            @{{ cellDisplay(@json($i), @json($j)) }}
                        </div>
                    @endfor
                @endfor
            </div>

            {{-- visible only after player win --}}
            <div class="mt-3 mb-4">
                <button id="btn_submit" class="btn btn-lg w-50 invisible" style="background-color: #9dbda9be">SUBMIT</button>
            </div>

        </div>

    </div>

    <script type="module">
        // vue app
        const app = Vue.createApp({
            data() {
                return {
                    board: @json($board)
                };
            },
            methods: {
                cellDisplay(i, j) {
                    return this.board[i][j] !== null ? this.board[i][j] : '_';
                }
            }
        });

        // mounting vue app
        const vm = app.mount('#app');

        $(document).ready(function() {

            // event listeners
            // ---------------

            $('.cell').on('click', function() {
                makeMove(this);
            });

            $('#btn_submit').on('click', function() {
                submitGame();
            });

            // makes a move on the baord
            function makeMove(cell) {
                let row = $(cell).data('row-index');
                let col = $(cell).data('col-index');

                axios.post('{{ route('make_move') }}', {
                        row: row,
                        col: col,
                        board: vm.board
                    })
                    .then(response => {
                        if (!response.data.gameOver) {
                            vm.board = response.data.board;
                        } else {

                            // disable cell clicks after game over
                            $('.cell').off('click');

                            vm.board = response.data.board;

                            if (response.data.winner === 'X') {
                                Swal.fire({
                                    title: 'You won!',
                                    text: 'Yay!',
                                    icon: 'success',
                                });

                                // remove invisibility from the submit button when the player wins
                                $('#btn_submit').removeClass('invisible').css('display', '');

                            } else if (response.data.winner === 'O') {

                                Swal.fire({
                                    title: 'You lost!',
                                    text: 'Game over',
                                    icon: 'error',
                                });

                            } else {
                                Swal.fire({
                                    title: 'Draw!',
                                    text: 'Game was tied',
                                    icon: 'warning',
                                });
                            }


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


            function submitGame() {

                Swal.fire({
                    title: 'Submit your game',
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
                                        '{{ route('submit_tic_tac_toe_game') }}', {
                                            board: vm
                                                .board,
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

        });
    </script>
@endsection
