$( document ).ready(function() {
    $("#SignupBtn").click( function(e){
        e.preventDefault();

        var firstName = $('#firstName').val();
        var lastName = $('#lastName').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var password2 = $('#password2').val();

        var name = firstName+" "+lastName;
        
        if(firstName === '' ){
            $('.error-firstName').html("Please enter your first name *");
        }else if(lastName === '' ){
            $('.error-lastName').html("Please enter your last name *");
        } else if(email === '' ){
            $('.error-email').html("Please enter a valid email address *");
        }else if( password === ''){
            $('.error-password').html("Please enter a password *");
        }else if( password2 === ''){
            $('.error-password2').html("Please confirm your password *");
        }else if( password2 != password){
            $('.error-password2').html("Passwords do not match *");
        }else{
            $('#spinner').show();
            $('#signupForm').hide();
            $.ajax({
                type: "post",
                url: 'assets/api/register.php',
                data: {
                    name: name,
                    email: email,
                    password: password,
                    password2: parseFloat
                },
                success:function (data) {
                    $('.error-firstName').html('');
                    $('.error-lastName').html('');
                    $('.error-email').html('');
                    $('.error-password').html('');
                    $('.error-password2').html('');

                    setTimeout(function() {
                        if(data === "success/account-created"){
                            $(".error").html("<label class='alert alert-success'>Account created <a href='login.html'>Log in</label>");
                        }else if(data === "error/email-used"){
                            $('.error-email').html("Email address already in use *");
                            $('#email').css("border-bottom", "2px solid red!important;");
                        }else if(data === "error/pass-not-same"){
                            $('.error').html("<label class='alert alert-danger'>Passwords do not match</label>");
                        }else if(data === "error/unknown"){
                            $('.error').html("<label class='alert alert-danger'>Unknown error has occurred </label>");
                        }else if(data === "error/form-empty"){
                            $('.error').html("<label class='alert alert-danger'>Form is empty</label>");
                        }else{
                            $('.error').html("<label class='alert alert-danger'>Unknown error occurred for you</label>");
                        }
            
                        $('#spinner').hide();
                        $('#signupForm').show();
                    }, 2000);
                }
            });
        }
    });
});