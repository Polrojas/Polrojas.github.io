var app = new Vue({
    el: '#app',
    data: {
        alterCategory: {
          id_categoria:null,
          imagen: null,
          nombre: null,
          color: null,
          link_video: null,
          estado:null
        },
        idCategoria: null,
        logged:false,
        registerError:false,
        registerMsg:null,
        categorias:[],
        newCategory: {
          nombre:null,
          imagen:null,
          color:null,
          link_video:null
        }

    },

    methods: {
      buscarCategorias(){
        var requestCategorias = new XMLHttpRequest()
        requestCategorias.open('GET', '../ApiRes/categorias.php?usuario=aplicacion', true)
        requestCategorias.onload = function() {
            var data = JSON.parse(this.response)

            if (requestCategorias.status >= 200 && requestCategorias.status < 400) {
                data.forEach(element => {
                  app.categorias.push({
                    id: element.id_categoria,
                    nombre: element.descripcion, 
                    url_imagen: "../"+element.imagen_categoria, 
                    imagen: element.imagen_categoria,
                    styleObject: { backgroundColor: element.color},
                    color: element.color.substr(1),
                    link_video: element.link_video,
                    estado: element.estado
                  },
                    
                    
                        )
                  })
              } else {
                console.log('error')
              }
        }
        requestCategorias.send()
      },

      eliminarCategoria(idCategoria) {
        console.log(idCategoria)
        fetch("../ApiRes/categorias.php?id_categoria=" + idCategoria + "&usuario=" + sessionStorage.loggedUser , {
            method: "DELETE"
        })
            .then(() => {
                this.categorias = []
                this.buscarCategorias()
            })
        this.idCategoria = null
      },

      crearCategoria(newCat){
        bodyApi = "usuario=" + sessionStorage.loggedUser + "&descripcion=" + newCat.nombre + "&color=" + newCat.color + "&imagen_categoria=" + newCat.imagen + "&link_video=" + newCat.link_video  
        console.log(bodyApi)
        fetch("../ApiRes/categorias.php", {
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
                    if (result.resultado==="ERROR"){
                        app.registerError=true
                        app.registerMsg = result.mensaje
                        console.log(result.mensaje)
                    }else{
                        window.location.href = "categorias.html";
                    }
                    

                })
            } else {
                throw "Error en la llamada Ajax"
            }
         })

      },

      modificarCategoria(categoria) {

        bodyApi = "usuario=" + sessionStorage.loggedUser + 
                  "&id_categoria=" + categoria.id +
                  "&descripcion=" + categoria.nombre +
                  "&imagen_categoria=" + categoria.imagen +
                  "&color=" + categoria.color +
                  "&link_video=" + categoria.link_video +
                  "&estado=" + categoria.estado
                  ,
            fetch("../ApiRes/categorias.php?"+bodyApi, {
            method: 'PUT',
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded'
            })
        })
            .then(() => {
              console.log("modificado!")
                window.location.href = "categorias.html";

            })
            .catch(() => {
                console.log("error")
            })
    },






 
      },


    computed: {
  
      },
      mounted: function(){

        sessionStorage.logged = false
        if(sessionStorage.loggedUser==null){
            this.logged = false
        }else{
            if(sessionStorage.typeUser=="ADMINISTRADOR"){
                sessionStorage.logged=true
                this.logged = true
            }else{
              this.logged = false
            }
        }
        this.buscarCategorias()

    }
  })



  
  
