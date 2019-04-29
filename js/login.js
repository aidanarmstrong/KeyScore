$( document ).ready(function() {
    $("#loginBtn").click( function(e){
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        if(email === '' ){
            $('.error-email').html("Please enter your email address *");
        }else if( password === ''){
            $('.error-password').html("Please enter your password *");
        }else{
            $('#spinner').show();
            $('#loginForm').hide();
            $.ajax({
                type: "post",
                url: 'assets/api/process.php',
                data: {
                    email: email,
                    password: password
                },
                success:function (data) {
                    $('.error-email').html('');
                    $('.error-password').html('');
                    setTimeout(function() {
                        $('#loginForm').show();
                        if(data === "success/user-auth"){
                            window.location = "./Dashboard/";
                        }else if(data === "error/no-auth"){
                            $('.error').html("<label class='alert alert-danger'>Email address or password incorrect </label>");
                            $('#spinner').hide();
                        }else{
                            $('.error').html("<label class='alert alert-danger'>Unknown error has occurred </label>");
                            $('#spinner').hide();
                        }
                    }, 2000);
                }
            });
        }
    });
});