Vue.component('recorrido',{
  template:`
  <div>
    <div class="mb-3 sombra" :style="estilos.categoriaStyleBar">
      <div class="row no-gutters" >
        <div class="col-md-12"  >
          <img :src="estilos.categoria.imagen_categoria" style="width:200px" class="card-img" alt="categoria">
        </div>
        <div class="col-md-12">
          <div class="card-body">
            <h3 class="titulo" style="color: white">{{curso.nombre}}</h3>
            <h3 class="subtitulo" style="color:white">{{curso.detalle}}</h3>
          </div>
        </div>
      </div>
    </div>

    <section class="section-curso">
    <main>
      <div v-for="(contenido,index) in contenidos" :key="index" class="cont" :style="[contenido.styleObject]">
        <div v-if="contenido.id_contenido" class="card"  style="border:0px; border-radius: 15px" >
          <img class="card-img contenido-image" :src="contenido.url_imagen" style="border-radius:10px;" alt="Card image">
          <img v-on:click="verVideo(contenido)" v-if="contenido.porcentaje_avance!=100 && contenido.url_contenido != 'NULL' "  style="cursor: pointer" class="card-img-dark" src="admin/images/imagen-video-dark.svg" alt="Card image">
          <img v-on:click="verVideo(contenido)" v-if="contenido.porcentaje_avance==100 && contenido.url_contenido != 'NULL' "  style="cursor: pointer" class="card-img-dark" src="admin/images/imagen-video-hecho.svg" alt="Card image">                  
          <div class="card-body">
            <h4 class="titulo-video" :style="estilos.tituloStyle">{{contenido.nombre_contenido}}</h4>
            <h5 v-if="contenido.url_contenido != 'NULL'" class="card-text" :style="estilos.tituloStyle">{{contenido.porcentaje_avance}}%</h5>
            <img v-if="index%2 == 0" :src="'admin/images/contenido'+index+'.svg'"  width="100px"  style="float:left;margin-left:-40px;margin-bottom:-40px; margin-top:-100px" alt="Arkidia" />
            <img v-if="index%2 != 0" :src="'admin/images/contenido'+index+'.svg'"  width="100px"  style="float:right;margin-right:-40px;margin-bottom:-40px; margin-top:-100px" alt="Arkidia" />
          </div>
        </div>
  
        <div v-if="contenido.id_challenge" class="card" style="border:0px;border-radius: 15px;" @click="seleccionaChallenge(contenido.id_challenge)">
          <div v-if="contenido.ind_completo == 0" class="image-upload">
            <label :for="'file-input'+index" style="display:block;cursor:pointer">
              <img src="admin/images/imagen-challenge-dark.svg"  alt="Card image">                
            </label>
            <input type="file" accept="image/*"  @change="uploadImage($event,contenido)" :id="'file-input'+index">
          </div>
          <img v-if="contenido.ind_completo == 1 && contenido.ind_aprobado == 'N'"  class="card-img-dark" src="images/site/pending.svg" alt="Card image">                  
          <img v-if="contenido.ind_completo == 1 && contenido.ind_aprobado == 'N'"  class="card-img" :src="contenido.url_contenido" style="border-radius:15px;" alt="Card image">
          <img v-if="contenido.ind_completo == 1 && contenido.ind_aprobado == 'S'" @click="verChallenge(contenido)"  class="card-img" :src="contenido.url_contenido" style="border-radius:15px;cursor: pointer;" alt="Card image">
          <img v-if="contenido.ind_aprobado == 'P'"  class="card-img" src="images/site/loading.gif" style="border-radius:15px;" alt="loading">
  
          <div class="card-body">
            <h4 v-if="contenido.ind_completo == 0" class="subtitulo " :style="estilos.tituloStyle">{{contenido.detalle_challenge}}</h4>
            <h4 v-if="contenido.ind_completo == 1" class="subtitulo " :style="estilos.tituloStyle">{{contenido.nombre_challenge}}</h4>
          </div>
          
        </div>
      </div>
    </main>
    </section>
  </div>
  `
  ,
  data() {
      return{
        contenidos: [],
        curso:{
          id: null,
          nombre: null,
          detalle: null,
          categoria: null,
          ind_completo: null
        },
        categoria:"",
        tituloStyle:"",
      }
  },
  props:{
    estilos:{
    categoriaStyle:"",
    categoriaStyleBar:"",
    color:"",
    categoria:"",
  }
  },
  methods:{

    verChallenge(challenge){
      console.log(challenge)
      sessionStorage.usuarioChallenge = sessionStorage.loggedUser
      sessionStorage.idChallenge = challenge.id_challenge
      window.location.href = "challenge.html";
    },


    uploadImage(event, contenido) {
      console.log("contenidos")
      console.log(contenido)
      const formData = new FormData();
      formData.append('imagen', event.target.files[0]);

      const options = {
        method: 'POST',
        body: formData,
        };
        contenido.ind_completo = 1
        contenido.ind_aprobado = "P"

    fetch("ApiRes/imagen.php", options)
    .then(function(res){ return res.json(); })
    .then(function(data){ 
        contenido.url_contenido = data.url
        contenido.ind_aprobado = "N"
        bodyApi = "imagen="+data.url+"&id_curso="+sessionStorage.idCurso+"&id_challenge="+contenido.id_challenge+"&usuario_challenge=" +sessionStorage.loggedUser,
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

    subirChallenge(contenido,imagen){
      
      bodyApi = "imagen="+imagen+"&id_curso="+sessionStorage.idCurso+"&id_challenge="+contenido.id_challenge+"&usuario_challenge=" +sessionStorage.loggedUser,
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

    },
    seleccionaChallenge(contenido){
      console.log("seleccionaChallenge")
      console.log(contenido)

    },
    buscarInscripcion() {
        fetch(
          "ApiRes/inscripcion.php?usuario=" +
          sessionStorage.loggedUser
           +
            "&id_curso=" +
            sessionStorage.idCurso
        )
          .then(response => response.json())
          .then(data => {
            console.log(data)
            this.curso.id = data.id_curso;
            this.curso.nombre = data.nombre_curso;
            this.curso.detalle = data.detalle_curso;
            this.curso.ind_completo = data.ind_completo;
            this.contenidos = data.contenido.concat(data.challenge) ;
            this.challenges = data.challenge;
            this.formatoContenido();

          });
      },
      formatoContenido(){
        for(i=0;i<this.contenidos.length; i++){
          if(this.contenidos[i].porcentaje_avance == 100){
            this.contenidos[i].styleObject = { color: "#ffffff" }
          }else{
            if(this.contenidos[i].ind_completo==1){
              
              console.log(this.contenidos[i])
              this.contenidos[i].styleObject = { color: "#ffffff" }
              console.log(this.categoria.color)
            }else{
            this.contenidos[i].styleObject = { color: "#ffffff66" }
          }
          }
        }
      },
      volver() {
        window.location.href = "admCursos.html";
      },
      verVideo(contenido) {
        sessionStorage.contenido = contenido.id_contenido;
        sessionStorage.video = contenido.url_contenido;
        sessionStorage.avance = contenido.porcentaje_avance;
        sessionStorage.curso = this.curso.id
        window.location.href = "video.html";
      },
  
  },
  computed:{  
  },
  mounted: function(){
    this.buscarInscripcion();


  }
})


Vue.component('challenges',{
  template:`
  <section>
  <div class=" mb-3 sombra" style="background-color:#ffffff33">
      <div class="row no-gutters">
          <div class="col-md-12">
              <div class="card-body">
                  <h3 class="titulo" style="color: white">Mirá los desafíos de otros Arkidians</h3>
              </div>
            </div>
        <div class="col-md-12">
          <img src="images/site/comunity.svg"  class="card-img" alt="categoria">
        </div>

      </div>
    </div>

    <div class="container">

        <div v-if="desafios.length==0">
          <h4 class="card-title subtitulo" style="color:white">¡Subí el primer desafío para este curso!</h4>
          <img src="images/site/subir-challenge.svg" style="max-width:300px" id="challenges" alt="sin actividad"/>
        </div>


        <div class="row" style="justify-content: center">
          <div v-for="desafio in desafios" :key="desafio.id">
            <div class="card card-curso"  style="cursor: pointer">
              <div >
                  <img :src="desafio.url_contenido" @click="verChallenge(desafio)" class="card-img" style="border-radius:15px" alt="categoria">
                <h4 class="subtitulo">{{desafio.alias}} </h4> 

              </div>

            <div style="padding:15px">
              <img v-if="desafio.ind_like==1" src="images/site/liked.svg" @click="quitarLike(desafio)" style="width:25px;" alt="curso.nombre">
              <img v-if="desafio.ind_like==0" src="images/site/likes.svg" @click="darLike(desafio)" style="width:25px;" alt="curso.nombre">

              <span>{{desafio.total_likes}}</span>
              <img src="images/site/comments.svg" style="width:25px;" alt="curso.nombre">
              <span>{{desafio.total_comentarios}}</span>
            </div>
          </div>
        </div>
      </div>
      </div>
</section>
  `
  ,
  data() {
    return{
      desafios:[
        {
          id:32,
          avatar:"images/arkidians/ark4.svg",
          arkidian:"Sola",
          edad: 5,
          url_desafio:"https://comohacerorigami.net/wp-content/uploads/2016/11/estrella-3d-de-papel-1024x768.jpg",
          fecha: "01/01/2019",
          likes: 3,
          comments:5
        }],
    }

  },
  props:{
    estilos:{
      categoriaStyle:"",
      categoriaStyleBar:"",
      color:"",
      categoria:"",
    }
  },
  methods:{

    verChallenge(challenge){
      sessionStorage.usuarioChallenge = challenge.usuario
      sessionStorage.idChallenge = challenge.id_challenge
      window.location.href = "challenge.html";
    },


    quitarLike(desafio){
      console.log("quitarLike")
      fetch("ApiRes/like_challenge.php?" + "usuario_like="+sessionStorage.loggedUser+"&usuario_challenge="+desafio.usuario+"&id_challenge="+desafio.id_challenge+"&secuencia=1", {
        method: "DELETE"
    })
        .then(function(response)  {
          console.log(response)
          desafio.ind_like = 0
          desafio.total_likes--


        })

    },
    darLike(desafio){
      fetch("ApiRes/like_challenge.php",{
        method: 'POST',
        body: "usuario_like="+sessionStorage.loggedUser+"&usuario_challenge="+desafio.usuario+"&id_challenge="+desafio.id_challenge,
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'})
    })
    .then(function(response) {
        if(response.ok) {
            loginResponse = response.json()
            loginResponse.then(function(result) {
              console.log(result)
              if(result.resultado == "OK"){
                desafio.ind_like = 1
                desafio.total_likes++
              }

            })
        } else {
            throw "Error en la llamada Ajax"
        }
     })

    },
    buscarDesafios(){
      console.log(sessionStorage.idCurso)
      fetch(
        "ApiRes/challenge_alumno.php?id_curso=" + sessionStorage.idCurso + "&usuario=" + sessionStorage.loggedUser
      )
        .then(response => response.json())
        .then(data => {
          console.log("Desafios")
          console.log(data)
          this.desafios = data
          
        });
    }
  },
  computed:{  
  },
  mounted: function(){
    this.buscarDesafios()
    
  }
})



var app = new Vue({
    el: '#app',
    data: {
        estilos:{
          categoriaStyle:"",
          categoriaStyleBar:"",
          color:"",
          categoria:"",

        },
        logged:false,
        items: [],

        curso: {
          id: null,
          nombre: null,
          detalle: null,
          categoria: null,
          ind_completo: null
        }

    },
    methods:{
      buscarCategorias(){
        fetch(
          "ApiRes/cursos.php?id_curso=" + sessionStorage.idCurso
        )
          .then(response => response.json())
          .then(data => {
            sessionStorage.idCategoria = data.id_categoria


          });

          console.log("Categoria: " + sessionStorage.idCategoria)


        fetch(
          "ApiRes/categorias.php?usuario=" + sessionStorage.loggedUser +"&id_categoria="+sessionStorage.idCategoria
        )
          .then(response => response.json())
          .then(data => {
            this.estilos.categoria = data
            this.estilos.categoriaStyle= "background-color: " + data.color
            this.estilos.categoriaStyleBar = "background-color: " + data.color + "dd"
            this.estilos.tituloStyle = "color: " + data.color

          });
    },
        
    },
    mounted: function(){
        if(sessionStorage.loggedUser==null){
            this.logged=false
        }else{
            if(sessionStorage.typeUser=="HIJO"){
                this.usuarioHijo = sessionStorage.loggedName
                this.logged=true
            }else{
                this.logged=false
            }

        }
        this.buscarCategorias();
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })


    }
  })

