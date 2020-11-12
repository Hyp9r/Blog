function copyDataToInputTag(counter) {
    var aTag = document.getElementsByClassName('search-item')[counter];
    var text = aTag.childNodes[1].textContent.trim();
    var searchInput = document.getElementById('search_text').value = text;
    var searchButton = document.getElementById('search_submit').click();
}

function follow(targetUser) {
    $.ajax({
        url: '/follow',
        type: 'POST',
        data: {
            'targetUser': targetUser
        },
        success: function (data) {
            changeButtonState(true);
            //update the number of followers
            updateFollowers(+1);
        },
    });
}

function unfollow(targetUser) {
    $.ajax({
        url: '/unfollow',
        type: 'POST',
        data: {
            'targetUser': targetUser
        },
        success: function (data) {
            changeButtonState(false);
            updateFollowers(-1);
        }
    })
}

function updateFollowers(value) {
    let numberOfFollowers = $('#numberOfFollowers').text();
    $('#numberOfFollowers').text(parseInt(numberOfFollowers) + value);
}

function changeButtonState(isFollowing) {
    if (isFollowing) {
        $('#follow').removeClass('btn-primary');
        $('#follow').addClass('btn-danger');
        $('#follow').text('Unfollow');
        $('#follow').attr('id', 'unfollow');
        $('#follow').off('click');
        $('#unfollow').off('click');
        $('#unfollow').click(function () {
            let location = window.location.href;
            let targetUser = location.split('/profile/')[1].split('#')[0];
            unfollow(targetUser);
        });
    } else {
        $('#unfollow').removeClass('btn-danger');
        $('#unfollow').addClass('btn-primary');
        $('#unfollow').text('Follow');
        $('#unfollow').off('click');
        $('#follow').off('click');
        $('#unfollow').attr('id', 'follow');
        $('#follow').click(function () {
            let location = window.location.href;
            let targetUser = location.split('/profile/')[1].split('#')[0];
            follow(targetUser);
        });
    }
}

function showFollowersDialog(string) {
    swal({
        title: string,
        button: 'Close'
    })
}

$(document).ready(function () {
    $('#follow').click(function () {
        let location = window.location.href;
        let targetUser = location.split('/profile/')[1].split('#')[0];
        follow(targetUser);
    });

    $('#unfollow').click(function () {
        let location = window.location.href;
        let targetUser = location.split('/profile/')[1].split('#')[0];
        unfollow(targetUser);
    });

});

// $(document).ready(function () {
//     document.getElementById('error').style.display = 'none';
//     $('#movie_yearRelease').focusout(function(){
//
//         let movieTitle = document.getElementById('movie_title').value;
//         let movieYearRelease = document.getElementById('movie_yearRelease').value;
//         let error = document.getElementById('error');
//         $.ajax({
//             url: '/create-movie/check',
//             type: 'POST',
//             data: {
//                 'movieTitle': movieTitle,
//                 'year' : movieYearRelease
//             },
//             success: function (response) {
//                 error.style.display = 'block';
//                 if(response === 'Movie already exists!'){
//                     error.className = '';
//                     error.classList.add('alert');
//                     error.classList.add('alert-danger')
//                 }else{
//                     error.className = '';
//                     error.classList.add('alert');
//                     error.classList.add('alert-success')
//                 }
//                 error.innerText=response;
//             }
//         })
//     });
// });

