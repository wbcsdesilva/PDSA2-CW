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
    </style>


    <div class="container retro-bg-dark p-5 rounded">

        {{-- vue app mount --}}
        <div id="app">
            <div>
                <h6>Find the Longest Common Subsequence</h6>
            </div>

            <div class="m-5">
                <h2>@{{ str1 }}</h2>
                <h5 class="mt-3 mb-3">&</h5>
                <h2>@{{ str2 }}</h2>
            </div>

            <input id="txt_player_solution" type="text" v-model="playerSolution" class="form-control mb-5"
                placeholder="Enter text" maxlength="10">

            <div class="d-flex flex-row justify-content-between">
                <button id="btn_reroll" class="btn btn-lg w-50 me-2" style="background-color: #9dbda9be">REROLL</button>
                <button id="btn_submit" class="btn btn-lg w-50 ms-2" style="background-color: #9dbda9be">SUBMIT</button>
            </div>

        </div>

    </div>

    <script type="module">
        // vue app
        const app = Vue.createApp({
            data() {
                return {
                    playerSolution: '',
                    str1: @json($str1),
                    str2: @json($str2),
                };
            },
            methods: {
                setStrings(str1, str2) {
                    this.str1 = str1;
                    this.str2 = str2;
                }
            },
        });

        // mounting vue app
        const vm = app.mount('#app');

        $(document).ready(function() {

            // event listeners
            // ---------------

            $('#btn_reroll').on('click', function() {
                rerollStrings();
            });

            $('#btn_submit').on('click', function() {
                submitSolution();
            });


            // functions
            // ---------

            function rerollStrings() {
                axios.post('{{ route('reroll_lcs_strings') }}')
                    .then(response => {
                        vm.setStrings(response.data.str1, response.data.str2);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            function submitSolution() {
                axios.post('{{ route('assess_lcs_solution') }}', {
                        str1: vm.str1,
                        str2: vm.str2,
                        playerSolution: vm.playerSolution
                    })
                    .then(response => {
                        if (!response.data.solutionIsCorrect) {
                            Swal.fire({
                                title: 'Solution incorrect',
                                text: 'Please try again',
                                icon: 'error',
                            });
                            console.log(response.data.strLCS);
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
                                                    '{{ route('submit_lcs_solution') }}', {
                                                        str1: vm.str1,
                                                        str2: vm.str2,
                                                        playerSolution: vm
                                                            .playerSolution,
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
                        // Validation responses at solution assessment
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
