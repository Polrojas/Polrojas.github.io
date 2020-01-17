Vue.component('cursos-categoria',{
    template:`
    <div id="categoria" >
      <div class=" mb-3 sombra" :style="estilos.categoriaStyleBar">
        <div class="row no-gutters">
          <div class="col-md-12">
            <img :src="estilos.categoria.imagen_categoria" style="width:200px" class="card-img" alt="categoria">
          </div>
          <div class="col-md-12">
            <div class="card-body">
              <h3 class="titulo" style="color: white">{{estilos.categoria.descripcion}}</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="row" style="justify-content: center">
          <div v-for="curso in cursosPorCategoria" :key="curso.id_curso">
            <div @click="iniciarCurso(curso)" class="card card-curso"  style="cursor: pointer">
              <div class="card-body">
                <h4 class="card-title subtitulo">{{curso.nombre_curso}}</h4>
                <p class="card-text">{{curso.detalle_curso}}</p>
              </div>
              <div :id="'demo'+curso.id" class="carousel slide" data-ride="carousel" >
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img :src="curso.contenido[0].url_imagen" style="width:100%;" alt="curso.nombre_curso">
                  </div>
                  <div v-for="contenido in curso.contenido" class="carousel-item">
                    <img :src="contenido.url_imagen" style="width:100%;" alt="curso.nombre_curso">
                  </div>
                </div>
              </div>  
              <div style="padding:15px">
                <img src="images/site/likes.svg" style="width:25px;" alt="curso.nombre">
                <span>0</span>
                <img src="images/site/comments.svg" style="width:25px;" alt="curso.nombre">
                <span>0</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    `
    ,
    data() {
        return{
            deleteHijo:'',
            apiCategorias:[],
            hijos:[],
            logged:false,
            usuarioHijo:"",
            saludo:"",
            cursosPorCategoria:"",
            cursos:[],
            resumenHijos:[]
        }
    },
    props:{
        estilos:{
            categoria:"",
            categoriaStyle:"",
            categoriaStyleBar:"",
        }

    },
    methods:{


        buscarCursosPorCategoria(id_categoria){

          if(sessionStorage.loggedUser==null) {
            fetch("ApiRes/cursos.php?id_categoria=" + id_categoria)
            .then(response => response.json() )
            .then((data)=>{
                 console.log(data)
                 this.cursosPorCategoria = data
              })

          }else{
            fetch("ApiRes/cursos.php?usuario=" + sessionStorage.loggedUser + "&id_categoria=" + id_categoria)
            .then(response => response.json() )
            .then((data)=>{
                 console.log(data)
                 this.cursosPorCategoria = data
              })

          }

        },

        iniciarCurso(curso){
            bodyApi = "usuario=" + sessionStorage.loggedUser + 
                        "&id_curso=" + curso.id_curso
              console.log(bodyApi)
              fetch("ApiRes/inscripcion.php", {
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
                            console.log("inscripto")
                            sessionStorage.idCurso = curso.id_curso
                            window.location.href = "curso.html"
                          }
                      })
                  } else {
                      throw "Error en la llamada Ajax"
                  }
               })
        }

    },
    computed:{
        
    },
    mounted:function(){
        this.buscarCursosPorCategoria(sessionStorage.idCategoria)

    }
})



var app = new Vue({
    el: '#app',
    data: {
        logged:false,
        user:"",
        admin:false,
        padre:false,
        hijo:false,
        nombre:"",
        estilos:{
        categoria:"",
        categoriaStyle:"",
        categoriaStyleBar:"",
        cursosPorCategoria:"",
    }

    },
    methods:{



        buscarCategorias(){
            if(!this.logged){

            }else{
                
            }
            fetch('ApiRes/categorias.php?usuario='+sessionStorage.loggedUser+"&id_categoria="+sessionStorage.idCategoria)
           .then(response => response.json() )
          .then((data)=>{
            this.estilos.categoria = data
            this.estilos.categoriaStyle= "background-color: " + data.color
            this.estilos.categoriaStyleBar = "background-color: " + data.color + "dd"
            })
        },

    },
    mounted: function(){

        this.padre=false
        this.admin=false
        this.hijo=false
        if(sessionStorage.loggedUser>"") {
          this.logged = true
          this.nombre = sessionStorage.loggedName
          if(sessionStorage.typeUser=="ADMINISTRADOR") this.admin = true
          if(sessionStorage.typeUser=="PADRE") this.padre = true
          if(sessionStorage.typeUser=="HIJO") this.hijo = true
        }
        this.buscarCategorias()
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })


    }
  })

