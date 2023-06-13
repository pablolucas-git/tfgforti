let friendRequests;

document.addEventListener('DOMContentLoaded', function () {

   recieveFriendRequests();



});
 

function recieveFriendRequests() {
    let xhrFriendRequests = new XMLHttpRequest();
    xhrFriendRequests.open('GET', '/getFriendRequests');
    xhrFriendRequests.onload = function () {
        if (xhrFriendRequests.status === 200) {
            newFriendRequests = JSON.parse(this.responseText);
            if (JSON.stringify(friendRequests) !== JSON.stringify(newFriendRequests)) {
                friendRequests = JSON.parse(this.responseText);
                printFriendRequests();
            }
        }
        uploadFriendRequests();
    };

    xhrFriendRequests.send();

}

function uploadFriendRequests() {
    recieveFriendRequests();
}
function printFriendRequests() {
    console.log(friendRequests);
    let notificationListElement = document.getElementById('friend-request-list');
    notificationListElement.innerHTML = '';
    friendRequests.forEach(friendRequest => {
        let listItem = document.createElement('li');
        listItem.classList.add('friend-request');
        let friendRequestContainer = document.createElement('div');
        friendRequestContainer.classList.add('friend-request-container');
        let leftContainer = document.createElement('div');
        leftContainer.classList.add('left');
        let imgContainer = document.createElement('div');
        imgContainer.classList.add('img-container');
        let img = document.createElement('img');
        img.src = '/public/res/imgs/profile-pics/' + friendRequest.user.id + friendRequest.user.imgType;
        imgContainer.appendChild(img);
        leftContainer.appendChild(imgContainer);
        let rightContainer = document.createElement('div');
        rightContainer.classList.add('right');
        let h3Element = document.createElement('h3');
        let aElement = document.createElement('a');
        aElement.href = '/profile/' + friendRequest.user.username;
        strongElement = document.createElement('strong');
        strongElement.innerHTML = friendRequest.user.name;
        aElement.appendChild(strongElement);
        let textSent = document.createTextNode(' te ha enviado una solicitud de amistad');
        h3Element.appendChild(aElement);
        h3Element.appendChild(textSent);
        let buttonContainer = document.createElement('div');
        buttonContainer.classList.add('buttons');
        let acceptButton = document.createElement('button');
        acceptButton.classList.add('accept');
        acceptButton.innerHTML = 'Aceptar';
        let declineButton = document.createElement('button');
        declineButton.classList.add('decline');
        declineButton.innerHTML = 'Rechazar';
        buttonContainer.appendChild(acceptButton);
        buttonContainer.appendChild(declineButton);
        rightContainer.appendChild(h3Element);
        rightContainer.appendChild(buttonContainer);
        friendRequestContainer.appendChild(leftContainer);
        friendRequestContainer.appendChild(rightContainer);
        listItem.appendChild(friendRequestContainer);
        notificationListElement.appendChild(listItem);
        acceptButton.addEventListener('click', function () {
            acceptFriendRequest(friendRequest.id);
        });
        declineButton.addEventListener('click', function () {
            declineFriendRequest(friendRequest.id);
        });
    });
}

function acceptFriendRequest(friendshipId) {
    let xhrAcceptFriendRequest = new XMLHttpRequest();
    xhrAcceptFriendRequest.open('POST', '/acceptFriendRequest/' + friendshipId);
    xhrAcceptFriendRequest.send();
}

function declineFriendRequest(friendshipId) {
    let xhrDeclineFriendRequest = new XMLHttpRequest();
    xhrDeclineFriendRequest.open('POST', '/declineFriendRequest/' + friendshipId);
    xhrDeclineFriendRequest.send();
}