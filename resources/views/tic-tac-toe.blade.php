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

        </div>

    </div>

    <script type="module">
        // vue app
        const app = Vue.createApp({
            data() {
                return {
                    playerName: '',
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
                            vm.board = response.data.board;

                            if (response.data.winner === 'X') {
                                Swal.fire({
                                    title: 'You won!',
                                    text: 'Yay!',
                                    icon: 'success',
                                });
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
                        console.error(error);
                    });
            }

        });
    </script>
@endsection
