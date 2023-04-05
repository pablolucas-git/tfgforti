
document.addEventListener("readystatechange", asignarEventos);
function asignarEventos(){
    const formLogin = document.getElementById("formlogin");
    const formRegistro = document.getElementById("formregistro");
    document.getElementById("irARegistro").addEventListener("click", cambiarARegistro);
    document.getElementById("irALogin").addEventListener("click", cambiarALogin);

    
    
    function cambiarALogin(e){
        e.preventDefault();
        formRegistro.style.display = "none";
        formLogin.style.display = "block";
    }
    function cambiarARegistro(e){
        e.preventDefault();
        formLogin.style.display = "none";
        formRegistro.style.display ="block";
    }
}
