@extends('layouts.app')

@section('content')
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1.5px solid #4b5d67;
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #4b5d67;
            color: #bdf2d5;
        }

        .sidebar {
            padding: 20px;
        }

        .distance-grid-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .tiny {
            font-size: 14px;
        }

        .retro-input-dark {
            background-color: #bdf2d5;
            color: #4b5d67;
            border: none;
        }
    </style>

    <div class="container-fluid">

        {{-- vue app mount --}}
        <div id="app">

            <div class="row">

                {{-- left --}}
                <div class="col-7">

                    <div class="distance-grid-container">
                        <table class="h-100 w-100">
                            <thead>
                                <tr>
                                    <th></th>
                                    @foreach (range('A', 'J') as $city)
                                        <th>{{ $city }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (range('A', 'J') as $city1)
                                    <tr>
                                        <th>{{ $city1 }}</th>
                                        @foreach (range('A', 'J') as $city2)
                                            @if (isset($distanceGraph[$city1][$city2]))
                                                <td>{{ $distanceGraph[$city1][$city2] }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- right --}}
                <div class="col-5 retro-bg-dark d-flex flex-column text-center">

                    <div class="sidebar">
                        <div> Find the shortest path from </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="h5 mt-4">City {{ $startCity }}</div>
                        </div>
                        <hr>

                        <div class="row mb-2">
                            <div class="col-md-1 tiny">

                            </div>
                            <div class="col-md-3 tiny">
                                Dist
                            </div>
                            <div class="col-md-8 tiny">
                                Path
                            </div>
                        </div>

                        {{-- inputs --}}

                        @foreach (range('A', 'J') as $city)
                            <div class="row mb-2">
                                <div class="col-md-1 d-flex align-items-center">
                                    <label class="tiny" for="input1">{{ $city }}</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control tiny retro-input-dark text-center"
                                        id="input1">
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control tiny retro-input-dark" style="width: 100%;">
                                </div>
                            </div>
                        @endforeach

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
                    playerSolution: [],
                    startCity: @json($startCity),
                    distanceGraph: @json($distanceGraph)
                };
            }
        });

        // mounting vue app
        const vm = app.mount('#app');

        $(document).ready(function() {

            // event listeners
            // ---------------

            $('#btn_submit').on('click', function() {
                submitSolution();
            });


            // functions
            // ---------

            function submitSolution() {
                axios.post('{{ route('assess_shortest_path_solution') }}', {
                        playerSolution: vm.playerSolution,
                        startCity: vm.startCity,
                        distanceGraph: vm.distanceGraph
                    })
                    .then(response => {
                        if (!response.data.solutionIsCorrect) {
                            Swal.fire({
                                title: 'Solution incorrect',
                                text: 'Please try again',
                                icon: 'error',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Invalid solution!',
                            text: 'Please make sure you enter all required data',
                            icon: 'error',
                        });
                    });
            }

        });
    </script>
@endsection
