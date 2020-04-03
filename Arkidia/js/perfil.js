Vue.component('challenge',{
    template:`
    <div style="text-align: center">

    <div class="row" style="justify-content: center; background:#ececec; border-radius:15px; margin:40px" >
      <div class="card perfil-nivel">
      <svg class="circle-chart" style="overflow:visible; position:absolute; z-index:1;" viewbox="0 0 33.83098862 33.83098862"
      width="200" height="200" xmlns="http://www.w3.org/2000/svg">
      <circle class="circle-chart__circle" :stroke-dasharray="porcentajeTotal" stroke="#ffffff" stroke-width="20"
          stroke-linecap="round" fill="none" cx="100" cy="100" r="95" />
      <circle class="circle-chart__circle" :stroke-dasharray="porcentaje" stroke="#02a0d0" stroke-width="10"
          stroke-linecap="round" fill="none" cx="100" cy="100" r="95" />

  </svg>
  <img :src="perfil.avatar" style="background-color:#e0e0e0; border-radius:50%; width:200px; padding:20px"
      class="img img-rounded img-fluid" />
      </div>


      <div class="card perfil-nivel">
    <h5 class="titulo" style="display:block">{{perfil.alias}}</h5>
    <p style="margin:0px">nivel</p>
    <p class="subtitulo" style="font-size:18px">{{perfil.nivel}}</p>
    <p style="margin:0px">completado</p>
    <p class="subtitulo" style="font-size:18px">puntos: {{perfil.puntos}} / {{perfil.puntos_nivel}}  </p>

      </div>
  </div>




    <section style="
    background-color: #ececec;
    padding-top: 0px;
    padding-bottom: 30px;
    margin-left: 40px;
    margin-right: 40px;
    border-radius:15px;">
    <h1 class="titulo" style="padding-top:50px; margin-top:50px">En progreso</h1>

    <div class="container">

    <div v-if="cursos_pendientes.length == 0" style="margin-top:30px">
    <h4 class="card-title subtitulo">No hay actividades en progreso</h4>
    <img src="images/site/no-progress.svg" style="max-width:300px" id="challenges" alt="sin actividad"/>
  </div>

      <div class="row" style="justify-content: center" >
        <div v-for="progreso in cursos_pendientes" :key="progreso.idCurso"  >
          <div class="card categoria">
            <img v-if="typeUser == 'HIJO'" @click="accederCurso(progreso)" :src="progreso.url_imagen" width="250px"  style="border-radius:15px; cursor:pointer" alt="Imagen" />
            <img v-if="typeUser == 'PADRE'" :src="progreso.url_imagen" width="250px"  style="border-radius:15px" alt="Imagen" />

            <div class="card-body">
              <p >{{ progreso.nombre_curso }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>




  <section style="
  background-color: #ececec;
  padding-top: 0px;
  padding-bottom: 30px;
  margin-left: 40px;
  margin-right: 40px;
  border-radius:15px;">
  <h1 class="titulo" style="padding-top:50px; margin-top:50px">Desafíos subidos</h1>

  <div class="container">

  <div v-if="desafios_subidos.length == 0" style="margin-top:30px">
  <h4 class="card-title subtitulo">Aún no hay desafíos subidos</h4>
  <img src="images/site/no-challenge.svg" style="max-width:300px" id="challenges" alt="sin actividad"/>
</div>


    <div class="row" style="justify-content: center" >
      <div v-for="desafio in desafios_subidos" >
        <div v-if="desafio.url_contenido>''" class="card categoria">
          <img v-if="typeUser == 'HIJO'" @click="accederDesafio(desafio.id_challenge)" :src="desafio.url_contenido" width="250px"  style="border-radius:15px; cursor:pointer" alt="Imagen" />
          <img v-if="typeUser == 'PADRE'"  :src="desafio.url_contenido" width="250px"  style="border-radius:15px" alt="Imagen" />

        </div>
      </div>
    </div>
  </div>

</section>


<section style="
    background-color: #ececec;
    padding-top: 0px;
    padding-bottom: 30px;
    margin-left: 40px;
    margin-right: 40px;
    margin-bottom: 40px;
    border-radius:15px;
    ">
    <h1 class="titulo" style="padding-top:50px; margin-top:50px">Finalizados</h1>
    <div class="container">

    <div v-if="cursos_hechos.length == 0" style="margin-top:30px">
    <h4 class="card-title subtitulo">No hay actividades finalizadas</h4>
    <img src="images/site/no-finished.svg" style="max-width:300px" id="challenges" alt="sin actividad"/>
  </div>
  

    
      <div class="row" style="justify-content: center" >
        <div v-for="progreso in cursos_hechos" :key="progreso.idCurso"  >
          <div class="card categoria">
            <img v-if="typeUser == 'HIJO'" :src="progreso.url_imagen" @click="accederCurso(progreso)" width="250px" style="border-radius:15px; cursor:pointer" alt="Imagen" />
            <img v-if="typeUser == 'PADRE'" :src="progreso.url_imagen" width="250px" style="border-radius:15px" alt="Imagen" />

            <div class="card-body">
              <p >{{ progreso.nombre_curso }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>

  </div>

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
          perfil:"",
          desafios_subidos:[],
          cursos_pendientes:[],
          cursos_hechos:[],
          porcentaje: "10,100",
          porcentajeTotal: "",
          typeUser:"",

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
      buscarPerfil() {
        fetch(
          "ApiRes/perfil_usuario.php?usuario=" +
          sessionStorage.profileUser
        )
          .then(response => response.json())
          .then(data => {
              this.perfil = data
              this.desafios_subidos = data.desafios_subidos
              this.cursos_pendientes = data.cursos_pendientes
              this.cursos_hechos = data.cursos_hechos
              this.porcentaje = "400,600"
              this.porcentajeTotal = "600,600"
              this.porcentaje = ((this.perfil.puntos /this.perfil.puntos_nivel)*600)+",600"
              
              console.log("porcentaje" + this.porcentaje)
              console.log(data)

          });
      },
      accederCurso(curso){
        console.log(curso)
        sessionStorage.idCurso = curso.id_curso
        window.location.href = "curso.html"
    },
    accederDesafio(desafio){
      console.log(desafio)
      sessionStorage.idChallenge = desafio
      sessionStorage.usuarioChallenge = sessionStorage.loggedUser

      window.location.href = "challenge.html"
  },
    },
    computed:{  
    },
    mounted: function(){
      this.typeUser = sessionStorage.typeUser
      this.buscarPerfil();

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
          porcentaje: "10,100",
          perfil:"",

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
         // this.buscarPerfil()
          window.addEventListener("load",function (){
              const loader = document.querySelector(".loader");
              loader.className += " hidden";
            })
  
  
      }
    })
  
  