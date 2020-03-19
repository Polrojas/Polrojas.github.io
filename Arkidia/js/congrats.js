var app = new Vue({
    el: '#app',
    data: {
        porcentaje: "10,100",

      deleteHijo:'',
      apiCategorias:[],
      hijos:[],
      logged:false,
      usuarioHijo:"",
      saludo:"",
      categoria:"",
      categoriaStyle:"",
      categoriaStyleBar:"",
      curso:"",
      contenidos:[],
      puntaje:0,
      imagen:"admin/images/imagen-challenge-dark.svg",

      

      

    resumenHijos:[]

    },
    methods:{
      uploadImage(event, contenido) {
        const formData = new FormData();
        formData.append('imagen', event.target.files[0]);
        console.log(contenido)
  
        const options = {
          method: 'POST',
          body: formData,
          };
          contenido.ind_completo = 1
          app.imagen = "images/site/subiendo.svg"
 
      fetch("ApiRes/imagen.php", options)
      .then(function(res){ return res.json(); })
      .then(function(data){ 
  
          app.imagen = data.url
          console.log("termino de subir")
          console.log(data)
  
          bodyApi = "imagen="+data.url+"&id_curso="+sessionStorage.idCurso+"&id_challenge="+contenido.id_challenge+"&usuario_challenge=" +sessionStorage.loggedUser,
          console.log(bodyApi)
          fetch("ApiRes/challenge_alumno.php", {
            method: 'POST',
            body: bodyApi,
            headers: new Headers({
              'Content-Type': 'application/x-www-form-urlencoded'
            })
          })
    
        .then(function(response) {
            if(response.ok) {
                loginResponse = response.json()
                loginResponse.then(function(result) {
                  console.log(result)
                    if (result.resultado==="ERROR"){
                        console.log("ERROR")
                        console.log(result.mensaje)
                    }else{
                      console.log("actualización de imagen")
                      console.log(response)
  
                    }
                })
            } else {
                throw "Error en la llamada Ajax"
            }
        })
  
         })
    
         
      },
        buscarPerfil() {
            fetch(
              "ApiRes/perfil_usuario.php?usuario=" +
              sessionStorage.profileUser
            )
              .then(response => response.json())
              .then(data => {
                  this.perfil = data
                  this.porcentaje = ((this.perfil.puntos /this.perfil.puntos_nivel)*100)+",100"
                  console.log(data)
  
    
              });
          },


        
        buscarPuntaje(){
            var requestCategorias = new XMLHttpRequest()
            requestCategorias.open('GET', 'ApiRes/puntaje.php?usuario='+sessionStorage.loggedUser+"&evento=video", true)
            requestCategorias.onload = function() {
                var data = JSON.parse(this.response)

                if (requestCategorias.status >= 200 && requestCategorias.status < 400) {
                    app.puntaje = data.puntaje
                  } else {
                    console.log('error')
                  }
            }
            requestCategorias.send()
        },

        buscarCategorias(){
            var requestCategorias = new XMLHttpRequest()
            requestCategorias.open('GET', 'ApiRes/categorias.php?usuario='+sessionStorage.loggedUser+"&id_categoria="+sessionStorage.idCategoria, true)
            requestCategorias.onload = function() {
                var data = JSON.parse(this.response)

                if (requestCategorias.status >= 200 && requestCategorias.status < 400) {
                    app.categoria = data
                    app.categoriaStyle= "background-color: " + data.color
                    app.categoriaStyleBar = "background-color: " + data.color + "dd"
                  } else {
                    console.log('error')
                  }
            }
            requestCategorias.send()
        },

        buscarInscripcion(){
            console.log(sessionStorage.curso)
            console.log(sessionStorage.loggedUser)

            fetch(
              "ApiRes/inscripcion.php?usuario=" +
              sessionStorage.loggedUser
               +
                "&id_curso=" +
                sessionStorage.curso
            )
              .then(response => response.json())
              .then(data => {
                console.log(data)
                app.curso.id = data.id_curso;
                app.curso.nombre = data.nombre_curso;
                app.curso.detalle = data.detalle_curso;
                app.curso.ind_completo = data.ind_completo;
                this.formatearContenidos(data.contenido.concat(data.challenge))
                console.log("buscaInscripcionOk")
              });

        },
        formatearContenidos(contenidos){
            for(i=0;i<contenidos.length;i++){
                if (contenidos[i].porcentaje_avance<100){
                    app.contenidos.push(contenidos[i])
                }else if(contenidos[i].ind_completo == 0){
                    app.contenidos.push(contenidos[i])

                }
            }
        },
        
        logOut(){
            sessionStorage.removeItem("typeUser");
            sessionStorage.removeItem("loggedUser");
        },
        verVideo(contenido) {
            sessionStorage.contenido = contenido.id_contenido;
            sessionStorage.video = contenido.url_contenido;
            sessionStorage.avance = contenido.porcentaje_avance;
            window.location.href = "video.html";
          },
    },
    mounted: function(){


        if(sessionStorage.loggedUser==null){
            this.logged=false
        }else{
            if(sessionStorage.typeUser=="HIJO"){
                this.usuarioHijo = sessionStorage.loggedName
                this.logged=true
                this.buscarPuntaje()
                this.buscarCategorias()
                this.buscarInscripcion()
                this.buscarPerfil()
            }else{
                this.logged=false
            }

        }
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })


    }
  })

