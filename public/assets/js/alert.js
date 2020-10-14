$("#swal-5").click(function() {
    setTimeout(function(){
        swal({
            title: 'User not logged in',
            text: 'Please login or create a new account to comment  ',
            icon: "error"
        }), function(){
            window.location = "/login";
        }
    }, 1000);
});