document.addEventListener('DOMContentLoaded', startFunc);
var activeForm = 'login';
function startFunc(){
    if(document.readyState == 'interactive'){
        let changeButtons = document.getElementsByClassName('change-form-button');
        for(let button of changeButtons){
            button.addEventListener('click', changeForm);
        }
        let submitButtons = document.getElementsByClassName('submit-button');
        for(let button of submitButtons){
            button.addEventListener('click', submitForm);
        }
        let forms = document.forms;
        for(let form of forms){
            for(let input of form.getElementsByTagName('input')){
                input.addEventListener('input', restartFormErrors);
            }
        }


    }
}
function restartFormErrors(event, fromSubmit = false){
    if(event != null){
        if(event.target.classList.contains('input-error')){
            event.target.classList.remove('input-error');
        }

    }
    if(fromSubmit){
        for(element of document.getElementsByClassName('input-error')){
            element.classList.remove('input-error');
        }
    }
}

function changeForm(){
    let newActiveForm = (activeForm == 'login') ? 'register' : 'login';
    document.getElementById( 'form-' + activeForm).style.display = 'none';
    document.getElementById('form-'  + newActiveForm).style.display = 'flex';
    document.getElementById('start-form-title').innerHTML = (newActiveForm == 'register') ? "Regístrate" : "Iniciar Sesión";
    activeForm = newActiveForm;
}

function submitForm(e){
    restartFormErrors(null, true);
    if(e.target.id === 'login-button'){
        doLogin();
    }
    else{
        doRegister();
    }
}

function showError(input, register = false) {
    if (input == 'Campos vacios') {
        subtitle = "Por favor, rellena todos los campos";
    }
    else if(register){
        subtitle = "Ya existe una cuenta con ese " + input.toLowerCase();
    }
    else{
        subtitle = (input == "Usuario") ? "Usuario incorrecto" : "Contraseña incorrecta"
    }
    showNotification('Error', subtitle, 'form-failed', 'ERROR');
}

function doLogin(){
    let form = new FormData(document.forms['login']);
    if (form.get('username').trim() == '' || form.get('password').trim() == '') {
        showError('Campos vacios');
        return;
    }
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        switch(JSON.parse(this.responseText).loginResult){
            case 'logged':
                sessionStorage.setItem('user', form.get('username'));
                location.reload();
                break;
            case 'wrongUser':
                document.forms['login']['username'].classList.add('input-error');
                showError('Usuario');
                break;
            case 'wrongPassword':
                document.forms['login']['password'].classList.add('input-error');
                showError('Contraseña');
                break;
        }
    };
    xhr.open("POST", "login");
    xhr.send(form);
}

function doRegister(){
    let form = new FormData(document.forms['register']);
    let registerImg = document.getElementById('registerImage').files[0];

    if (form.get('username').trim() == '' || form.get('email').trim() == '' || form.get('password').trim() == '' || form.get('name').trim() == '' || registerImg == null || registerImg.type.split('/')[0] != 'image' || registerImg == undefined) { 
        showError('Campos vacios');
        return;
    }
    form.append('registerImage', registerImg);
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        switch(JSON.parse(this.responseText).registerResult){
            case 'success':
                changeForm();
                showNotification('Te has registrado con éxito', 'Por favor, inicia sesión', 'register-success', 'SUCCESS');
                break;
            case 'userExists':
                document.forms['register']['username'].classList.add('input-error');
                showError('Nombre de usuario', true);
                break;
            case 'emailExists':
                document.forms['register']['email'].classList.add('input-error');
                showError('Email', true);
                break;
        }
    };
    xhr.open("POST", "register");
    xhr.send(form);
}



                
                   
            