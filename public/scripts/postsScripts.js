document.addEventListener('DOMContentLoaded', function () {
    [...document.getElementsByClassName('comment-button')].forEach(commentButton => {
        commentButton.addEventListener('click', sendComment);

    }
    );
    [...document.getElementsByClassName('all-comments')].forEach(container => {
        container.scrollTop = container.scrollHeight - container.clientHeight;
    }
    );
    [...document.getElementsByClassName('guardadas')].forEach(guardada => {
        guardada.addEventListener('click', () => {
            let isSaved = guardada.getAttribute('data-saved') === 'true';
            let savedNum = Number(guardada.getAttribute('data-saved-num'));
            let postId = guardada.getAttribute('data-post-id');
            /**/
            if (isSaved) {
                let unsaveXhr = new XMLHttpRequest();
                unsaveXhr.open('POST', '/unsavePost/' + postId, true);
                unsaveXhr.setRequestHeader('Content-Type', 'application/json');
                unsaveXhr.onload = function () {
                    if (this.status === 200) {
                        guardada.getElementsByTagName('span')[0].innerHTML = (savedNum - 1) + ' Guardadas';
                        guardada.setAttribute('data-saved-num', savedNum - 1);
                        guardada.getElementsByTagName('i')[0].classList.remove('fa-solid');
                        guardada.getElementsByTagName('i')[0].classList.add('fa-regular');
                    }
                }
                unsaveXhr.send();
            }
            else {
                let saveXhr = new XMLHttpRequest();
                saveXhr.open('POST', '/savePost/' + postId, true);
                saveXhr.setRequestHeader('Content-Type', 'application/json');
                saveXhr.onload = function () {
                    if (this.status === 200) {
                        guardada.getElementsByTagName('span')[0].innerHTML = (savedNum + 1) + ' Guardadas';
                        guardada.setAttribute('data-saved-num', savedNum + 1);
                        guardada.getElementsByTagName('i')[0].classList.remove('fa-regular');
                        guardada.getElementsByTagName('i')[0].classList.add('fa-solid');
                    }
                }
                saveXhr.send();
            }
        }
        );
    }
    );
    
    [...document.getElementsByClassName('heart')].forEach(heart => {
        heart.addEventListener('click', () => {
            let isLiked = heart.getAttribute('data-liked') === 'true';
            let likedNum = Number(heart.getAttribute('data-liked-num'));
            let postId = heart.getAttribute('data-post-id');
            /**/
            if (isLiked) {
                let unlikeXhr = new XMLHttpRequest();
                unlikeXhr.open('POST', '/dislikePost/' + postId, true);
                unlikeXhr.setRequestHeader('Content-Type', 'application/json');
                unlikeXhr.onload = function () {
                    if (this.status === 200) {
                        heart.getElementsByTagName('span')[0].innerHTML = (likedNum - 1) + ' Likes';
                        heart.setAttribute('data-liked-num', likedNum - 1);
                        heart.getElementsByTagName('i')[0].classList.remove('fa-solid');
                        heart.getElementsByTagName('i')[0].classList.add('fa-regular');
                    }
                }
                unlikeXhr.send();
            }
            else {
                let likeXhr = new XMLHttpRequest();
                likeXhr.open('POST', '/likePost/' + postId, true);
                likeXhr.setRequestHeader('Content-Type', 'application/json');
                likeXhr.onload = function () {
                    if (this.status === 200) {
                        heart.getElementsByTagName('span')[0].innerHTML = (likedNum + 1) + ' Likes';
                        heart.setAttribute('data-liked-num', likedNum + 1);
                        heart.getElementsByTagName('i')[0].classList.remove('fa-regular');
                        heart.getElementsByTagName('i')[0].classList.add('fa-solid');
                    }
                };
                likeXhr.send();
            }

            isLiked = !isLiked;
            heart.setAttribute('data-liked', isLiked);
        });
    })
});



function sendComment(e) {
    let commentButton = e.currentTarget;
    let postId = commentButton.getAttribute('data-post-id');
    let commentInput = document.getElementById(postId + '-input');
    let comment = commentInput.value;
    if (comment.trim() != '' && comment != undefined && comment != null) {
        let xhrComment = new XMLHttpRequest();
        xhrComment.open('POST', '/makeComent/' + postId, true);
        xhrComment.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhrComment.onload = function () {
            uploadComments(postId);
        };
        xhrComment.send('text=' + comment);
        commentInput.value = '';
    }
}

function uploadComments(postid) {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/getComents/' + postid, true);
    xhr.onload = function () {
        printComments(JSON.parse(this.responseText), postid);
    };
    xhr.send();
}

function printComments(comments, postid) {
    let userImgSrc = document.getElementById('user-img').src;
    let commentsContainer = document.getElementById('comments-' + postid);
    console.log(comments);
    let scrollPosition = commentsContainer.getElementsByClassName('all-comments')[0].scrollTop;
    commentsContainer.innerHTML = '';
    let allComments = document.createElement('div');
    allComments.classList.add('all-comments');
    comments.forEach(comment => {
        let commentDiv = document.createElement('div');
        commentDiv.classList.add('coment-container');
        let imgContainer = document.createElement('div');
        imgContainer.classList.add('img-container');
        let img = document.createElement('img');
        img.src = '/public/res/imgs/profile-pics/' + comment.user.id + comment.user.imgType;
        imgContainer.appendChild(img);
        commentDiv.appendChild(imgContainer);
        let contentContainer = document.createElement('div');
        contentContainer.classList.add('content-container');
        let usercoment = document.createElement('span');
        usercoment.classList.add('usercoment');
        usercoment.innerHTML = comment.user.name;
        let textcoment = document.createElement('span');
        textcoment.classList.add('textocoment');
        textcoment.innerHTML = comment.comment;
        contentContainer.appendChild(usercoment);
        contentContainer.appendChild(textcoment);
        commentDiv.appendChild(contentContainer);
        allComments.appendChild(commentDiv);
    }
    );
    commentsContainer.appendChild(allComments);
    allComments.scrollTop = scrollPosition;
    let writeComentElement = document.createElement('div');
    writeComentElement.classList.add('writecoment-container');
    let imgContainer = document.createElement('div');
    imgContainer.classList.add('img-container');
    let img = document.createElement('img');
    img.src = userImgSrc;
    imgContainer.appendChild(img);
    let inputContainer = document.createElement('div');
    inputContainer.classList.add('input-coment');
    let input = document.createElement('input');
    input.id = postid + '-input';
    input.type = 'text';
    input.placeholder = 'Escribe un nuevo comentario...';
    input.name = 'coment-text';
    let button = document.createElement('button');
    button.classList.add('comment-button');
    button.setAttribute('data-post-id', postid);
    button.type = 'button';
    let icon = document.createElement('i');
    icon.classList.add('fa-solid');
    icon.classList.add('fa-paper-plane');
    button.appendChild(icon);
    button.addEventListener('click', sendComment);
    inputContainer.appendChild(input);
    inputContainer.appendChild(button);
    writeComentElement.appendChild(imgContainer);
    writeComentElement.appendChild(inputContainer);
    commentsContainer.appendChild(writeComentElement);
    

}
