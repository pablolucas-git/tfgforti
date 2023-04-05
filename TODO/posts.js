function llamadaAJAX() {
    const xmlhttp = new XMLHttpRequest();
    let posts;
    xmlhttp.onload = function () {
        posts = JSON.parse(this.responseText);
        crearPosts(posts);
        reducirPosts();
    }
    xmlhttp.open("POST", "getPosts.php");
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    userid = 4449;
    xmlhttp.send("userid=" + userid + "&accion=postsAmigos");
}


document.addEventListener("readystatechange", function () {
    if (document.readyState == "complete") {
        llamadaAJAX();
    }
});


function reducirPosts() {

    let posts = document.getElementsByClassName("post");
    for (post of posts) {
        let textos = post.getElementsByClassName("texto");

        for (texto of textos) {
            if (texto.getElementsByTagName("p")[0].offsetHeight > 100) {
                texto.getElementsByTagName("p")[0].style.height = "100px";
                texto.getElementsByTagName("p")[0].style.overflow = "hidden";
                texto.innerHTML += "<button>Leer más</button>";
                texto.getElementsByTagName("button")[0].addEventListener("click", function () {
                    this.previousElementSibling.style.height = "auto";
                    this.previousElementSibling.style.overflow = "visible";
                    this.remove();
                });
            }
        }

        let comentarios = post.getElementsByClassName("comentario");
        if (comentarios.length >= 1) {
            for (let i = 1; i < comentarios.length; i++) {
                comentarios[i].style.display = "none";
            }
            comentarios[0].parentElement.innerHTML += "<button>Ver más comentarios</button>";
            comentarios[0].parentElement.getElementsByTagName("button")[0].addEventListener("click", function () {
                for (comentario of comentarios) {
                    comentario.style.display = "flex";
                }
                this.remove();
            });
        }
    }





}



function crearPosts(posts) {
    publicaciones = document.getElementById("publicaciones");
    for (post of posts) {
        let postDiv = document.createElement("div");
        postDiv.classList.add("post");


        let header = document.createElement("header");

        let foto = document.createElement("img");
        foto.src = "perfiles/" + post.header.foto;

        let nombre = document.createElement("p");
        nombre.textContent = post.header.nombre;

        header.appendChild(foto);
        header.appendChild(nombre);
        postDiv.appendChild(header);


        let contenido = document.createElement("div");
        contenido.classList.add("contenido");

        let textoDiv = document.createElement("div");
        textoDiv.classList.add("texto");
        let texto = document.createElement("p");
        texto.textContent = post.contenido.texto;
        textoDiv.appendChild(texto);
        contenido.appendChild(textoDiv);
        if (post.contenido.imagen != null) {
            let imagenDiv = document.createElement("div");
            imagenDiv.classList.add("imagen");

            let imagen = document.createElement("img");
            imagen.src = "publicaciones/" + post.contenido.imagen;
            imagenDiv.appendChild(imagen);
            contenido.appendChild(imagenDiv);
        }


        postDiv.appendChild(contenido);


        let acciones = document.createElement("div");
        acciones.classList.add("acciones");
        let megusta = document.createElement("button");
        megusta.classList.add("megusta");
        megusta.textContent = "Me gusta";
        let comentar = document.createElement("button");
        comentar.classList.add("comentar");
        comentar.textContent = "Comentar";
        let compartir = document.createElement("button");
        compartir.classList.add("compartir");
        compartir.textContent = "Compartir";

        acciones.appendChild(megusta);
        acciones.appendChild(comentar);
        acciones.appendChild(compartir);

        postDiv.appendChild(acciones);

        if (post.comentarios != null) {
            let comentarios = document.createElement("div");
            comentarios.classList.add("comentarios");
            for (comentario of post.comentarios) {
                let comentarioDiv = document.createElement("div");
                comentarioDiv.classList.add("comentario");

                let foto = document.createElement("img");
                foto.src = "perfiles/" + comentario.foto;

                let comentarioContenido = document.createElement("div");
                comentarioContenido.classList.add("comentario-contenido");

                let nombre = document.createElement("p");
                nombre.textContent = comentario.nombre;

                let textoComentario = document.createElement("p");
                textoComentario.classList.add("textoComentario");
                textoComentario.textContent = comentario.texto;

                comentarioContenido.appendChild(nombre);
                comentarioContenido.appendChild(textoComentario);

                comentarioDiv.appendChild(foto);
                comentarioDiv.appendChild(comentarioContenido);
                comentarios.appendChild(comentarioDiv);
            }
            postDiv.appendChild(comentarios);
        }
        publicaciones.appendChild(postDiv);

    }
}