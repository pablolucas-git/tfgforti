document.addEventListener("DOMContentLoaded", startChat);

var allMessages_new;
var allMessages;
var chatList;
var openedChat;
var openedChatId;
var xhrChatPetition;
var thisUserImg;
var isSearching = false;

function startChat() {
    getThisUserImg();
    let width = document.getElementById('chatbox').clientWidth;
    document.getElementById('chat-screen').style.right = '-' + width + 'px';
    document.getElementById('backChatScreen').addEventListener('click', backPantallaPrincipalChat);
    document.getElementById('new-message-send').addEventListener('click', sendMessage);
    document.getElementById('new-message-input').addEventListener('keypress', enterKeyboard);
    document.getElementById('search-chat').addEventListener('input', searchChat);
    callChatServer();
}

getThisUserImg = () => {
    let xhr = new XMLHttpRequest();
    xhr.onload = function () {
        thisUserImg = JSON.parse(this.responseText);
    }
    xhr.open('POST', '/getThisUserImg');
    xhr.send();
}

function searchChat(event) {
    let string = event.target.value.toLowerCase().trim();
    if (string == undefined || string == null || string == "") {
        isSearching = false;
        setChat(chatList);
        return;
    }
    isSearching = true;
    let xhrSearch = new XMLHttpRequest();
    xhrSearch.open("GET", "/findFriends/" + string, true);
    xhrSearch.onload = function () {
        printSearch(JSON.parse(this.responseText));
    };
    xhrSearch.send();
}


function printSearch(userList) {
    let chatList_element = document.getElementById('chat-list');
    chatList_element.innerHTML = '';
    if (userList.length == 0) {
        let noUserFoundElement = document.createElement('div');
        noUserFoundElement.classList.add('no-user-found');
        noUserFoundElement.innerHTML = 'No se encontraron usuarios';
        chatList_element.appendChild(noUserFoundElement);
        return;
    }
    for (let user of userList) {
        let li = createElement('li', ['chat-list-element', 'search-element']);
        let chatImgContainer = createElement('div', ['chat-img-container', 'img-container']);
        let chatImg = document.createElement('img');
        chatImg.src = '/public/res/imgs/profile-pics/' + user.user.id + user.user.imgType;
        chatImgContainer.appendChild(chatImg);
        let chatTextPreviewContainer = createElement('div', 'chat-text-preview-container');
        let chatName = createElement('p', 'chat-name');
        let chatUsername = createElement('p', 'chat-username');


        chatName.appendChild(document.createTextNode(user.user.name));
        chatUsername.appendChild(document.createTextNode('@' + user.user.username));



        chatTextPreviewContainer.appendChild(chatName);
        chatTextPreviewContainer.appendChild(chatUsername); li.appendChild(chatImgContainer);
        li.appendChild(chatTextPreviewContainer);
        li.setAttribute('aria-user', user.user.username);
        li.setAttribute('friendship-id', user.id);
        chatList_element.appendChild(li);
    }
    for (let chatElement of document.getElementsByClassName('chat-list-element')) {
        chatElement.addEventListener("click", openMessagesChat);
    }
}

function callChatServer(){
    xhrChatPetition = new XMLHttpRequest();
    xhrChatPetition.onload = function(){
        allMessages_new = this.responseText;
        refreshChat()
    }
    xhrChatPetition.open('POST', '/getChats');
    xhrChatPetition.send();
}
function refreshChat() {
    if (JSON.stringify(allMessages) != JSON.stringify(JSON.parse(allMessages_new))) {
        allMessages = JSON.parse(allMessages_new);
        getChatList();
        updateChat();
    }
    callChatServer();
}


function updateChat() {
    if (openedChatId != undefined && openedChatId != null) {
        let scrolledToBottom = (document.getElementById('chat-messages-container').scrollTop == document.getElementById('chat-messages-container').scrollHeight - document.getElementById('chat-messages-container').clientHeight);

        getChatMessages();
        if(scrolledToBottom){
            document.getElementById('chat-messages-container').scrollTop = document.getElementById('chat-messages-container').scrollHeight - document.getElementById('chat-messages-container').clientHeight;
        }
    }
}

function getChatList() {
    let chatList_new = allMessages.map(chat => {
        return {
            id: chat.id,
            user: chat.user,
            text: chat.messages[chat.messages.length - 1].text,
            status: (chat.messages[chat.messages.length - 1].recieved) ? 'read' : 'send'
        };
    });
    if (JSON.stringify(chatList_new) != JSON.stringify(chatList)) {
        chatList = JSON.parse(JSON.stringify(chatList_new));
        setChat(chatList);
    }
}

function getChatMessages() {
    if (openedChatId != undefined && openedChatId != null) {
        openedChat = allMessages.find(chat => chat.id == openedChatId);
        if (openedChat == undefined) {
            let xhrChatInfo = new XMLHttpRequest();
            xhrChatInfo.onload = function () {
                friend = JSON.parse(this.responseText);
                console.log(friend);
                openedChat = {
                    'messages': [],
                    'user': friend
                };
                printMessages(openedChat.messages, openedChat.user.name, openedChat.user.id + openedChat.user.imgType);
            }
            xhrChatInfo.open('POST', '/getChatInfo/' + openedChatId);
            xhrChatInfo.send();
            return;
        }
        printMessages(openedChat.messages, openedChat.user.name, openedChat.user.id + openedChat.user.imgType);
    }
}

function setChat(chatPreview) {
    if (!isSearching) {
        let chatList_element = document.getElementById('chat-list');
        chatList_element.innerHTML = '';
        for (let chat of chatPreview) {
            let li = createElement('li', ['chat-list-element', chat.status]);
            let chatImgContainer = createElement('div', ['chat-img-container', 'img-container']);
            let chatImg = document.createElement('img');
            chatImg.src = '/public/res/imgs/profile-pics/' + chat.user.id + chat.user.imgType;
            chatImgContainer.appendChild(chatImg);
            let chatTextPreviewContainer = createElement('div', 'chat-text-preview-container');
            let chatName = createElement('p', 'chat-name');
            let chatUsername = createElement('p', 'chat-username');
            let chatTextPreview = createElement('p', 'chat-text-preview');


            chatName.appendChild(document.createTextNode(chat.user.name));
            chatUsername.appendChild(document.createTextNode('@' + chat.user.username));
            chatTextPreview.appendChild(document.createTextNode(chat.text));



            chatTextPreviewContainer.appendChild(chatName);
            chatTextPreviewContainer.appendChild(chatUsername);
            chatTextPreviewContainer.appendChild(chatTextPreview);

            if (chat.status === 'unread') {
                let messagesCount = createElement('div', 'unread-messages-count');
                let span = createElement('span');
                span.appendChild(document.createTextNode(chat.unread));
                messagesCount.appendChild(span);
                chatTextPreviewContainer.appendChild(messagesCount);
            }

            if (chat.status === 'send') {
                let readCheckbox = createElement('div', 'read-checkbox');
                let icon = createElement('i', ['fas', 'fa-check']);
                readCheckbox.appendChild(icon);
                chatTextPreviewContainer.appendChild(readCheckbox);
            }

            li.appendChild(chatImgContainer);
            li.appendChild(chatTextPreviewContainer);
            li.setAttribute('aria-user', chat.user.username);
            li.setAttribute('friendship-id', chat.id);
            chatList_element.appendChild(li);
        }
        for (let chatElement of document.getElementsByClassName('chat-list-element')) {
            chatElement.addEventListener("click", openMessagesChat);
        }
    }
}
function enterKeyboard(event){
    if(event.key === "Enter"){
        sendMessage();
    }
}
function openMessagesChat(event) {
    let pantallaChat = document.getElementById('chat-screen');
    openedChatId = event.currentTarget.getAttribute('friendship-id');
    getChatMessages();

    pantallaChat.style.display = "flex";
    setTimeout(() => {
        pantallaChat.style.right = "0";
        let chatContainer = document.getElementById('chat-messages-container');
        chatContainer.scrollTop = chatContainer.scrollHeight - chatContainer.clientHeight;
    }, 100);
}



function printMessages(messages, name, imgsrc) {
    let chatContainer = document.getElementById('chat-messages-container');
    let chatName = document.getElementById('chat-name');
    document.getElementById('opened-chat-img').src = '/public/res/imgs/profile-pics/' + imgsrc;
    chatName.innerHTML = name;
    chatContainer.innerHTML = '';
    let recieved = false;
    if (messages.length != 0) {
        recieved = messages[0].recieved;
    }
    for (let i = 0; i < messages.length;) {
        
        let messageWrapper = document.createElement('div');
        let messageClass = (messages[i].recieved) ? 'recieved-container' : 'send-container';
        let newimgsrc = (messages[i].recieved) ? imgsrc : thisUserImg;
        messageWrapper.classList.add(messageClass);
        let messageContainer = document.createElement('ul');
        for (; i < messages.length && recieved == messages[i].recieved; i++) {
            let li = document.createElement('li');
            li.appendChild(document.createTextNode(messages[i].text));
            messageContainer.appendChild(li);
        }
        if (i < messages.length) {
            recieved = messages[i].recieved;
        }
        let imgElement = document.createElement('img');
        
        let img = document.createElement('div');
        img.classList.add('message-img-container');
        img.classList.add('img-container');
        img.appendChild(imgElement);
        
        imgElement.src = '/public/res/imgs/profile-pics/' + newimgsrc;
        messageContainer.appendChild(img);
        messageWrapper.appendChild(messageContainer);
        chatContainer.appendChild(messageWrapper);
    }

}
function backPantallaPrincipalChat() {
    let width = document.getElementById('chat-screen').clientWidth;
    document.getElementById('chat-screen').style.right = '-' + width + 'px';
    setTimeout(() => {
        document.getElementById('chat-screen').style.display = 'none';
    }, 300);
}

function sendMessage() {
    let inputMessage = document.getElementById('new-message-input');
    let message = inputMessage.value;
    if (message == undefined || message == null || message.trim() == "") {
        return;
    }
    let xhrSendMessage = new XMLHttpRequest();
    xhrSendMessage.open("POST", '/sendMessageTo/' + openedChatId);
    xhrSendMessage.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    xhrSendMessage.send('message=' + message);
    inputMessage.value = '';
}