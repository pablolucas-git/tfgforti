document.addEventListener('DOMContentLoaded', function () {
    if (document.readyState == 'interactive') {
        let sendFriendRequestButtons = document.getElementsByClassName('send-friend-request-button');
        for (let button of sendFriendRequestButtons) {
            if (!button.classList.contains('sent')){
                button.addEventListener('click', sendFriendRequest);
            }
        }
    }
});

function sendFriendRequest(e) {
    let button = e.currentTarget;
    let username = button.getAttribute('data-user-username');
    let xhrFriendRequest = new XMLHttpRequest();
    xhrFriendRequest.onload = function () {
        button.classList.add('sent');
        button.removeEventListener('click', sendFriendRequest);
    }
    xhrFriendRequest.open('POST', '/sendFriendRequest/' + username);
    xhrFriendRequest.send();
}