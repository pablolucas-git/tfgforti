document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("search-input").addEventListener("keyup", search);
});

function search(event){
    let string = event.target.value.toLowerCase().trim();
    if(string == undefined || string == null || string == ""){
        printUsers([]);
        return;
    }
    let xhrSearch = new XMLHttpRequest();
    xhrSearch.open("GET", "/findUsers/" + string, true);
    xhrSearch.onload = function(){
        printUsers(JSON.parse(this.responseText));
    };
    xhrSearch.send();
}

function printUsers(userList){




    let searchList = document.getElementById("search-list");
    searchList.innerHTML = "";
    for(let user of userList){
        let li = document.createElement("li");
        let div = document.createElement("div");
        div.classList.add("search-item");
        let divImg = document.createElement("div");
        
        divImg.classList.add("search-item-img");
        let divImgContainer = document.createElement("div");
        divImgContainer.classList.add("img-container");
        let imgElement = document.createElement("img");
        imgElement.src = "/public/res/imgs/profile-pics/" + user.id + user.imgType;
        divImgContainer.appendChild(imgElement);
        divImg.appendChild(divImgContainer);
        div.appendChild(divImg);

        let divInfo = document.createElement("div");
        divInfo.classList.add("search-item-info");
        let spanName = document.createElement("span");
        spanName.classList.add("name");
        spanName.innerText = user.name;
        let spanUsername = document.createElement("span");
        spanUsername.classList.add("username");
        spanUsername.innerText = user.username;
        divInfo.appendChild(spanName);
        divInfo.appendChild(spanUsername);
        div.appendChild(divInfo);
        li.appendChild(div);
        if(user.isFriend){
            let divFriend = document.createElement("div");
            divFriend.classList.add("friend-box");
            let i = document.createElement("i");
            i.classList.add("fa-solid", "fa-user-group");
            let spanFriend = document.createElement("span");
            spanFriend.classList.add("friend");
            spanFriend.innerText = "Amigo";
            divFriend.appendChild(i);
            divFriend.appendChild(spanFriend);
            li.appendChild(divFriend);
        }
        li.addEventListener("click", function(){
            window.location.href = "/profile/" + user.username;
        });
        searchList.appendChild(li);

    }
}

