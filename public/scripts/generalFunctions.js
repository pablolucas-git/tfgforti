function showNotification(title, subtitle, elementClass = null, type){
    let notificationBanner = document.createElement('div');
    notificationBanner.classList.add('notification-banner');
    if(elementClass != null){
        notificationBanner.classList.add(elementClass);
    }
    let iconContainer = document.createElement('div');
    iconContainer.classList.add('notification-icon');
    let icon = document.createElement('i');
    icon.classList.add('fas');
    icon.classList.add('fa-sharp');
    switch(type){
        case 'SUCCESS':
            icon.classList.add('fa-check');
            break;
        case 'ERROR':
            icon.classList.add('fa-xmark');
            break;
    }
    iconContainer.appendChild(icon);
    let notificationTextDiv = document.createElement('div');
    notificationTextDiv.classList.add('notification-text');
    let notificationTitle = document.createElement('p');
    notificationTitle.classList.add('notification-title');
    notificationTitle.appendChild(document.createTextNode(title));
    let notificationSubtitle = document.createElement('p');
    notificationSubtitle.classList.add('notification-subtitle');
    notificationSubtitle.appendChild(document.createTextNode(subtitle));
    notificationTextDiv.appendChild(notificationTitle);
    notificationTextDiv.appendChild(notificationSubtitle);
    notificationBanner.appendChild(iconContainer);
    notificationBanner.appendChild(notificationTextDiv);
    document.body.appendChild(notificationBanner);
    setTimeout(() => {
        notificationBanner.style.top = '10px';
        setTimeout(() => {
            notificationBanner.style.top = '-1000px';
            setTimeout(() =>{
                document.body.removeChild(notificationBanner);
            }, 1000);
        }, 3000);
      }, 1);
}

function createElement(elementType, clases = null){
    let element = document.createElement(elementType);
    if(clases != null){
        if(Array.isArray(clases)){
            for(clase of clases){
                element.classList.add(clase);
            }
        }
        else{
            element.classList.add(clases);
        }
    }
    return element;
}