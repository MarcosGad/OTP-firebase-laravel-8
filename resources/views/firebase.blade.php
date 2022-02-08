<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- https://www.youtube.com/watch?v=saDOHpODLMo --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 8 Phone Number OTP Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
</head>
<body>

    <section class="validOTPForm">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h4 class="text-center">
                                Account verification
                            </h4>
                        </div>
    
    
                        <div class="card-body">
                            <form>
                                @csrf
                                <div class="form-group">
                                    <label for="phone_no">Phone Number</label>
    
                                    <input type="text" class="form-control" name="phone_no" id="number" placeholder="(Code) *******">
                                </div>
                                <div id="recaptcha-container"></div>
                                    <a href="#" id="getcode" class="btn btn-dark btn-sm">Get Code</a>
    
                                    <div class="form-group mt-4">
                                        <input type="text" name="" id="codeToVerify" name="getcode" class="form-control" placeholder="Enter Code">
                                    </div>
    
                                    <a href="#" class="btn btn-dark btn-sm btn-block" id="verifPhNum">Verify Phone No</a>
                            
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>

        $(document).ready(function() {
            const firebaseConfig = {
                apiKey: "{{ config('services.firebase.api_key') }}",
                authDomain: "{{ config('services.firebase.auth_domain') }}",
                projectId: "{{ config('services.firebase.project_id') }}",
                storageBucket: "{{ config('services.firebase.storage_bucket') }}",
                messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
                appId: "{{ config('services.firebase.app_id') }}"
            };

            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);

            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': function (response) {
                    // reCAPTCHA solved, allow signInWithPhoneNumber.
                    console.log('recaptcha resolved');
                }
            });
            onSignInSubmit();
        });


        function onSignInSubmit() {
        $('#verifPhNum').on('click', function() {
            let phoneNo = '';
            var code = $('#codeToVerify').val();
            console.log(code);
            $(this).attr('disabled', 'disabled');
            $(this).text('Processing..');
            confirmationResult.confirm(code).then(function (result) {
                        alert('Succecss');
                var user = result.user;
                console.log(user);


                // ...
            }.bind($(this))).catch(function (error) {
            
                // User couldn't sign in (bad verification code?)
                // ...
                $(this).removeAttr('disabled');
                $(this).text('Invalid Code');
                setTimeout(() => {
                    $(this).text('Verify Phone No');
                }, 2000);
            }.bind($(this)));

        });


        $('#getcode').on('click', function () {
            var phoneNo = $('#number').val();
            console.log(phoneNo);
            // getCode(phoneNo);
            var appVerifier = window.recaptchaVerifier;
            firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
            .then(function (confirmationResult) {

                window.confirmationResult=confirmationResult;
                coderesult=confirmationResult;
                console.log(coderesult);
            }).catch(function (error) {
                console.log(error.message);

            });
        });
        }

        // function getCode(phoneNumber) {
        //     var appVerifier = window.recaptchaVerifier;
        //     firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
        //         .then(function (confirmationResult) {
        //             console.log(confirmationResult);
        //             // SMS sent. Prompt user to type the code from the message, then sign the
        //             // user in with confirmationResult.confirm(code).
        //             window.confirmationResult = confirmationResult;
        //             $('#getcode').removeAttr('disabled');
        //             $('#getcode').text('RESEND');
        //         }).catch(function (error) {
                    
        //             console.log(error);
        //             console.log(error.code);
        //             // Error; SMS not sent
        //             // ...
        //         });
        //   }  

</script>
    
</body>
</html>