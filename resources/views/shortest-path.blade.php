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
                                    <label class="tiny" for="txt_dist_{{ $city }}">{{ $city }}</label>
                                </div>
                                <div class="col-md-3">
                                    <input id="txt_distance_to_{{ $city }}" type="number" min="0"
                                        step="1" data-target-city="{{ $city }}"
                                        class="form-control tiny retro-input-dark text-center distance-input">
                                </div>
                                <div class="col-md-8">
                                    <input id="txt_path_to_{{ $city }}" type="text"
                                        data-target-city="{{ $city }}"
                                        class="form-control tiny retro-input-dark path-input" style="width: 100%;">
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
                    cityPaths: [],
                    cityDistances: [],
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

                // sets player solutions into the arrays
                setPlayerSolution();

                axios.post('{{ route('assess_shortest_path_solution') }}', {
                        cityPaths: vm.cityPaths,
                        cityDistances: vm.cityDistances,
                        startCity: vm.startCity,
                        distanceGraph: vm.distanceGraph
                    })
                    .then(response => {
                        if (!response.data.solutionIsCorrect) {

                            // printing the answer so you can use the console to answer it
                            console.log(response.data.correctSolution);

                            Swal.fire({
                                title: 'Solution incorrect!',
                                text: 'Please try again',
                                icon: 'error',
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
                                                    '{{ route('submit_shortest_path_solution') }}', {
                                                        playerName: inputValue,
                                                        cityPaths: vm.cityPaths,
                                                        cityDistances: vm
                                                            .cityDistances,
                                                        startCity: vm.startCity,
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

            // set the input data into the vue variable
            function setPlayerSolution() {

                let distances = {};
                let paths = {};

                $('.distance-input').each(function() {
                    let targetCity = $(this).data('target-city');
                    let distance = parseInt($(this).val());
                    distances[targetCity] = distance;
                });

                $('.path-input').each(function() {
                    let targetCity = $(this).data('target-city');
                    let path = $(this).val();
                    paths[targetCity] = path;
                });

                vm.cityPaths = paths;
                vm.cityDistances = distances;
            }

        });
    </script>
@endsection
