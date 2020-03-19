Vue.component('challenge',{
    template:`
    <div style="text-align: center">
    <svg class="circle-chart" style="position:absolute; z-index:1;" viewbox="0 0 33.83098862 33.83098862"
        width="200" height="200" xmlns="http://www.w3.org/2000/svg">

        <circle class="circle-chart__circle" :stroke-dasharray="porcentaje" stroke="#00acc1" stroke-width="8"
            stroke-linecap="round" fill="none" cx="100" cy="100" r="95" />

    </svg>
    <img :src="perfil.avatar" style="background-color:grey; border-radius:50%; width:200px"
        class="img img-rounded img-fluid" />

    <h5 class="titulo">{{perfil.alias}}</h5>
    <p>puntos: {{perfil.puntos}}</p>
    <p>nivel: {{perfil.nivel}}</p>
    <p>(te faltan {{perfil.puntos_nivel - perfil.puntos}} más para el próximo nivel)</p>



    <section style="
    background-color: #ececec;
    padding-top: 0px;
    padding-bottom: 30px;
    margin-left: 20px;
    margin-right: 20px;">
    <h1 class="titulo">En progreso</h1>

    <div class="container">
      <div class="row" style="justify-content: center" >
        <div v-for="progreso in perfil.cursos_pendientes" :key="progreso.idCurso"  @click="accederCurso(progreso)">
          <div class="card categoria">
            <img :src="progreso.url_imagen" width="250px" alt="Imagen" />
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
  margin-left: 20px;
  margin-right: 20px;">
  <h1 class="titulo">Desafíos subidos</h1>

  <div class="container">
    <div class="row" style="justify-content: center" >
      <div v-for="desafio in perfil.desafios_subidos" @click="accederDesafio(desafio.id_challenge)">
        <div v-if="desafio.url_contenido>''" class="card categoria">
          <img :src="desafio.url_contenido" width="250px" alt="Imagen" />

        </div>
      </div>
    </div>
  </div>

</section>


<section style="
    background-color: #ececec;
    padding-top: 0px;
    padding-bottom: 30px;
    margin-left: 20px;
    margin-right: 20px;">
    <h1 class="titulo">Finalizados</h1>
    <div class="container">
      <div class="row" style="justify-content: center" >
        <div v-for="progreso in perfil.cursos_hechos" :key="progreso.idCurso"  @click="accederCurso(progreso)">
          <div class="card categoria">
            <img :src="progreso.url_imagen" width="250px" alt="Imagen" />
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
          porcentaje: "10,100"

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
              this.porcentaje = "400,600"
              this.porcentaje = ((this.perfil.puntos /this.perfil.puntos_nivel)*600)+",600"
              console.log(this.porcentaje)
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
  
  