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
          <div v-if="contenido.id_contenido" class="card" v-on:click="verVideo(contenido)" style="border:0px;cursor: pointer; border-radius: 15px" >
              <img class="card-img contenido-image" :src="contenido.url_imagen" style="border-radius:10px;" alt="Card image">
              <img v-if="contenido.porcentaje_avance!=100"  class="card-img-dark" src="admin/images/imagen-video-dark.svg" alt="Card image">
              <img v-if="contenido.porcentaje_avance==100" class="card-img-dark" src="admin/images/imagen-video-hecho.svg" alt="Card image">                  
              <div class="card-body">
                  <h4 class="titulo-video" :style="estilos.tituloStyle">{{contenido.nombre_contenido}}</h4>
                  <h5 class="card-text" :style="estilos.tituloStyle">{{contenido.porcentaje_avance}}%</h5>
                  <img v-if="index%2 == 0" :src="'admin/images/contenido'+index+'.svg'"  width="100px"  style="float:left;margin-left:-40px;margin-bottom:-40px; margin-top:-100px" alt="Arkidia" />
                  <img v-if="index%2 != 0" :src="'admin/images/contenido'+index+'.svg'"  width="100px"  style="float:right;margin-right:-40px;margin-bottom:-40px; margin-top:-100px" alt="Arkidia" />
              </div>
            </div>
  
            <div v-if="contenido.id_challenge" class="card" style="border:0px;cursor: pointer;border-radius: 15px" >
                <img class="card-img" src="admin/images/challenge.jpg" style="border-radius:15px" alt="Card image">
                <img v-if="contenido.porcentaje_avance!=100"class="card-img-dark" src="admin/images/imagen-challenge-dark.svg" style="position: absolute;" alt="Card image">
                <img v-if="contenido.porcentaje_avance==100" class="card-img-dark" src="admin/images/imagen-challenge-dark.svg" style="position: absolute;" alt="Card image">                
                <div class="card-body">
                    <h4 class="subtitulo " :style="estilos.tituloStyle">{{contenido.detalle_challenge}}</h4>
                    <a href="#" class="btn btn-primary">{{contenido.nombre_challenge}}</a>
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
            if(this.contenidos[i].ind_completo>0){
              this.contenidos[i].styleObject = { color: this.categoria.color }
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
        <div class="row" style="justify-content: center">
          <div v-for="desafio in desafios" :key="desafio.id">
            <div class="card card-curso"  style="cursor: pointer">
              <div >
                  <img :src="desafio.url_desafio"  class="card-img" alt="categoria">
                <h4 class="subtitulo">{{desafio.arkidian}} </h4> 

              </div>

            <div style="padding:15px">
              <img src="images/site/likes.svg" style="width:25px;" alt="curso.nombre">
              <span>{{desafio.likes}}</span>
              <img src="images/site/comments.svg" style="width:25px;" alt="curso.nombre">
              <span>{{desafio.comments}}</span>
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

  },
  computed:{  
  },
  mounted: function(){
    
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

