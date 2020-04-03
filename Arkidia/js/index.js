Vue.component('inicio-arkidia',{
    template:`
    <section id="welcome" style="height: 800px" >
        <div style="display:table-cell; vertical-align: middle; text-align: -webkit-center" >
        <div
            class="logo-size bodymovin"
            data-icon="json/logo.json"
            data-aplay="true"
            data-loop="false"
        ></div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#login" >Iniciar sesión</button>
        <br><br>
        <button class="btn btn-secondary" data-toggle="modal" data-target="#register" >Registrarme</button>

        </div>


    </section>
    `
    ,
    data() {
        return{
        }
    },
    props:{
    },
    methods:{
    },
    computed:{  
    }
})

Vue.component('banner-proyectos',{
    template:`
    <section style="margin-top: 100px">
    <h1 class="titulo">Miles de proyectos con videos paso a paso</h1>
    <img
    src="images/site/challenges.svg"
    id="challenges"
    alt="Miles de proyectos"
    style="padding-top: 50px; padding-bottom:50px"
  />
    
  </section>
    `
    ,
    data() {
        return{
        }
    },
    props:{
    },
    methods:{
    },
    computed:{  
    }
})


Vue.component('footer-inicio',{
    template:`
    <div style="color: white; text-align: center ;background-image: url(images/site/ark-background.svg);background-size:cover; height: 600px;">
    <h1 class="titulo" style="padding-top:150px; padding-left: 20px;padding-right:20px">"Uno aprende haciendo las
        cosas;<br> porque aunque piense 
        que sabe, no tiene la certeza 
        hasta que lo intenta"</h1>
        <p>Sófocles, Siglo 5 A.C.</p>
  </div>
    `
    ,
    data() {
        return{
        }
    },
    props:{
    },
    methods:{
    },
    computed:{  
    }
})

Vue.component('compartir',{
    template:`
    <div style="text-align: center">
    <img
    src="images/site/compartir.svg"
    id="challenges"
    alt="Miles de proyectos"
    style="width:100%; margin-top: 100px"
  />
    <div
    style="
    background-color: #ececec;
    padding-top: 50px;
    padding-bottom: 30px;
    margin-left: 20px;
    margin-right: 20px;
    height: 600px"
    ></div>
    <img
    src="images/site/paint-grey.svg"
    id="challenges"
    alt="Miles de proyectos"
    style=" margin-left: 20px;
    margin-right: 20px;"
  />
  </div>
    `
    ,
    data() {
        return{
        }
    },
    props:{
    },
    methods:{
    },
    computed:{  
    }
})

Vue.component('faq',{
  template:`
  <div style="text-align: center">
  <img
  src="images/site/faq.svg"
  id="challenges"
  alt="Miles de proyectos"
  style="width:100%; margin-top: 100px"
/>
  <div
  style="
  background-color: #ececec;
  padding-top: 50px;
  padding-bottom: 30px;
  margin-left: 20px;
  margin-right: 20px;"
  >


  <div class="accordion" id="accordionExample">
    <div class="card" style="border:0px;background-color: #ececec;">
      <div class="card-header" id="headingOne">
        <h2 class="mb-0">
          <button class="btn titulo" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          ¿Que es Arkidia?
          </button>
        </h2>
      </div>
  
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body">
        Arkidia es una plataforma virtual de entretenimiento educativo para chicos de 6 a 13 años. Nuestra misión es estimular y potenciar el desarrollo de los niños y niñas, más allá de la escuela. Creemos que con la motivación correcta y las condiciones adecuadas siempre queremos aprender y compartir lo que aprendemos. 
        </div>
      </div>
    </div>
    <div class="card" style="border:0px;background-color: #ececec;">
      <div class="card-header" id="headingTwo">
        <h2 class="mb-0">
          <button class="btn titulo collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          ¿Cómo funciona Arkidia?
          </button>
        </h2>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
        <div class="card-body">
        Te registras y registras a los niños y niñas que quieras (que nosotros llamamos Arkidians). Luego los Arkidians acceden a los cursos, ven los videos y comparten lo que aprendieron y crearon. Pueden hacer comentarios y dar likes a las creaciones de otros Arkidians.        </div>
      </div>
    </div>
    <div class="card" style="border:0px;background-color: #ececec;">
      <div class="card-header" id="headingThree">
        <h2 class="mb-0">
          <button class="btn titulo collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          ¿Cuál es su costo?
          </button>
        </h2>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
        Por el momento Arkidia está en etapa de pruebas. Su acceso se encuentra cerrado al público en general y es solamente por invitación. Si te interesa participar en el futuro envíanos un correo a quiero@arkidia.com. 
        </div>
      </div>
    </div>
    <div class="card" style="border:0px;background-color: #ececec;">
      <div class="card-header" id="headingThree">
        <h2 class="mb-0">
          <button class="btn titulo collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          ¿Necesito comprar materiales?
          </button>
        </h2>
      </div>
      <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
        Para la mayoría de los cursos, no. Intentamos que los desafíos de Arkidia puedan realizarse con objetos disponibles en los hogares.
        </div>
      </div>
    </div>

    <div class="card" style="border:0px;background-color: #ececec;">
    <div class="card-header" id="headingThree">
      <h2 class="mb-0">
        <button class="btn titulo collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFour">
        ¿Qué seguridad brinda Arkidia?
        </button>
      </h2>
    </div>
    <div id="collapseFive" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body" style="text-align:left">
      Nos gusta divertirnos mientras aprendemos, pero nos tomamos la seguridad y privacidad muy en serio, ya que construimos Arkidia para que sea un ambiente completamente protegido:<br>
      (1) Reforzamos el comportamiento positivo y constructivo en todas las interacciones de los Arkidians.<br>
      (2) Se requiere un login y el contenido no se accesible fuera de Arkidia.<br>
      (3) Los Arkidians deben utilizar apodos. <br>
      (4) Los comentarios y los desafíos que suben los Arkidians se aprueban antes de ser publicados.<br>
      (5) Guardamos un registro con toda la actividad de los usuarios.<br>
            </div>
    </div>
  </div>

  </div>



</div>
  <img
  src="images/site/paint-grey.svg"
  id="challenges"
  alt="Miles de proyectos"
  style=" margin-left: 20px;
  margin-right: 20px;"
/>
</div>
  `
  ,
  data() {
      return{
      }
  },
  props:{
  },
  methods:{
  },
  computed:{  
  }
})


Vue.component('banner-beneficios',{
    template:`
    <section style="margin-top: 100px">
            <div class="card-group" style="justify-content: center;">
              <div class="card mb-4" style="min-width: 300px; max-width:500px;border:0px">
                  <div class="row no-gutters" style="text-align:center">
                    <div class="col-md-6" >
                        <img
                        src="images/site/inmersivo.svg"
                        id="challenges"
                        alt="Inmersivo"
                        style="width:200px"
                      />
                    </div>
                    <div class="col-md-6">
                      <div class="card-body">
                        <h5 class="titulo" style="font-size:25px" >Inmersivo</h5>
                        <p class="card-text">Los chicos pasan de ser espectadores a protagonistas</p>
                      </div>
                    </div>
                  </div>
              </div>

              <div class="card mb-4" style="min-width: 300px;max-width:500px;border:0px">
                  <div class="row no-gutters" style="text-align:center">
                    <div class="col-md-6" >
                        <img
                        src="images/site/calidad.svg"
                        id="challenges"
                        alt="Inmersivo"
                        style="width:200px"
                      />
                    </div>
                    <div class="col-md-6">
                      <div class="card-body">
                        <h5 class="titulo" style="font-size:25px">de Calidad</h5>
                        <p class="card-text">El contenido y la experiencia de aprendizaje es la base de todo lo que hacemos.</p>
                      </div>
                    </div>
                  </div>
              </div>

              <div class="card mb-4" style="min-width: 300px;max-width:500px;border:0px">
                  <div class="row no-gutters" style="text-align:center">
                    <div class="col-md-6">
                        <img
                        src="images/site/seguro.svg"
                        id="challenges"
                        alt="Seguro"
                        style="width:200px"
                      />
                    </div>
                    <div class="col-md-6">
                      <div class="card-body">
                        <h5 class="titulo" style="font-size:25px">Seguro</h5>
                        <p class="card-text">El aprendizaje de los chicos se da en un espacio protegido. Sin bullies, sin mayores.</p>
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
        }
    },
    props:{
    },
    methods:{
    },
    computed:{  
    }
})

Vue.component('comentarios',{
    template:`
    <section style="margin-top: 100px">
 
<div class="card" style="border:0px; padding:20px">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="images/site/profile.svg" style="max-width: 100px;" class="img img-rounded img-fluid"/>
            </div>
            <div class="col-md-10">
                <p>
                    <strong>Julieta </strong>madre de una Arkidian de 9 años

               </p>
               <p>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
              </p>
               <div class="clearfix"></div>
                <p>Arkidia es fantástico. Saca el lado creativo de mi hija y desarrolla talentos y habilidades diferentes a los del colegio</p>

            </div>
        </div>
    </div>
</div>
   <div class="card" style="border:0px; padding:20px">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="images/site/profile.svg" style="max-width: 100px;" class="img img-rounded img-fluid"/>
            </div>
            <div class="col-md-10">
                <p>
                    <strong>Martín</strong>, abuelo de un Arkidian de 7 años

               </p>
               <p>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
              </p>
               <div class="clearfix"></div>
                <p>Exploramos con mi nieto todas las categorías, con cursos tan diversos. Hay algo para todos!!</p>

            </div>
        </div>
    </div>
</div>
<div class="card" style="border:0px; padding:20px">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="images/site/profile.svg" style="max-width: 100px;" class="img img-rounded img-fluid"/>
            </div>
            <div class="col-md-10">
                <p>
                    <strong>María Luisa</strong>, madre de dos Arkidians de 8 y 10 años
               </p>
               <p>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
              </p>
               <div class="clearfix"></div>
                <p>Descubrí una gran manera de que usen el celular pero que después tengan que dejarlo para hacer los desafíos que proponen en los videos!!! </p>

            </div>
        </div>
    </div>
</div>


<div class="card" style="border:0px; padding:20px">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="images/site/profile.svg" style="max-width: 100px;" class="img img-rounded img-fluid"/>
            </div>
            <div class="col-md-10">
                <p>
                    <strong>Edison y Lucrecia</strong>, padres de un Arkidian de 9 años
               </p>
               <p>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
              </p>
               <div class="clearfix"></div>
                <p>Desde que nuestro hijo empezó a jugar con Arkidia está más creativo en su tiempo libre, atento a lo que aprendió y a las cosas que puede compartir con otros niños. Bravo! </p>

            </div>
        </div>
    </div>
</div>

        </section>
    `
    ,
    data() {
        return{
        }
    },
    props:{
    },
    methods:{
    },
    computed:{  
    }
})




Vue.component('challenges',{
    template:`

    <div style="text-align: center">
        <img
        src="images/site/crear.svg"
        id="challenges"
        alt="Miles de proyectos"
        style="width:100%; margin-top: 100px"
      />




        <div 
        style="
        background-color: #ececec;
        padding-top: 50px;
        padding-bottom: 30px;
        margin-left: 20px;
        margin-right: 20px;
        "
        >
        
        
        
        <div class="container my-4">    

        <!--Carousel Wrapper-->
    <div id="multi-item-example" class="carousel slide carousel-multi-item" data-ride="carousel">


      <!--Slides-->
      <div class="carousel-inner" role="listbox">

        <!--First slide-->
        <div class="carousel-item active">

          <div class="row">
            <div class="col-md-4">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio1.jpg"
                  alt="Card image cap">
              </div>
            </div>

            <div class="col-md-4 clearfix d-none d-md-block">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio2.jpg"
                  alt="Card image cap">
              </div>
            </div>

            <div class="col-md-4 clearfix d-none d-md-block">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio3.jpg"
                  alt="Card image cap">
              </div>
            </div>
          </div>

        </div>
        <!--/.First slide-->

        <!--Second slide-->
        <div class="carousel-item">

          <div class="row">
            <div class="col-md-4">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio4.jpg"
                  alt="Card image cap">
              </div>
            </div>

            <div class="col-md-4 clearfix d-none d-md-block">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio5.jpg"
                  alt="Card image cap">
              </div>
            </div>

            <div class="col-md-4 clearfix d-none d-md-block">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio6.jpg"
                  alt="Card image cap">
              </div>
            </div>
          </div>

        </div>
        <!--/.Second slide-->

        <!--Third slide-->
        <div class="carousel-item">

          <div class="row">
            <div class="col-md-4">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio7.jpg"
                  alt="Card image cap">
              </div>
            </div>

            <div class="col-md-4 clearfix d-none d-md-block">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio8.jpg"
                  alt="Card image cap">
              </div>
            </div>

            <div class="col-md-4 clearfix d-none d-md-block">
              <div class="card mb-2">
                <img class="card-img-top" src="images/site/desafio9.jpg"
                  alt="Card image cap">
              </div>
            </div>
          </div>

        </div>
        <!--/.Third slide-->

      </div>
      <!--/.Slides-->

    </div>
    <!--/.Carousel Wrapper-->


  </div>
        
        
        
        </div>
        <img
        src="images/site/paint-grey.svg"
        id="challenges"
        alt="Miles de proyectos"
        style=" margin-left: 20px;
        margin-right: 20px;"
      />



   




      
      </div>
    `
    ,
    data() {
        return{
        }
    },
    props:{
    },
    methods:{
    },
    computed:{  
    }
})

Vue.component('cursos-pendientes',{
  template:`
  <div  style="text-align: center; margin-top: 50px">

  <section  style="
  background-color: #ececec;
  padding-top: 50px;
  padding-bottom: 30px;
  margin-left: 20px;
  margin-right: 20px;">
  <h1 class="titulo">Continuar viendo...</h1>

  <div class="container">

  <div class="row" style="justify-content: center; display:block; margin-top:50px">
 

    <div v-if="cursos.length == 0">
      <h4 class="card-title subtitulo">No hay actividades en progreso</h4>
      <img src="images/site/background-no-activity.svg" style="max-width:600px" id="challenges" alt="sin actividad"/>
    </div>
    




        <div class="row" style="justify-content: center">
          <div v-for="curso in cursos" :key="curso.id_curso">
            <div  class="card card-curso"  >
              <div class="card-body">
                <h4 class="card-title subtitulo">{{curso.nombre_curso}}</h4>
                <p class="card-text">{{curso.detalle_curso}}</p>
              </div>
              <div :id="'demo'+curso.id" class="carousel slide" data-ride="carousel" >
                <div @click="accederCurso(curso)" style="cursor: pointer" class="carousel-inner">
                  <div class="carousel-item active">
                    <img :src="curso.url_imagen" style="width:100%;border-radius:15px" alt="curso.nombre_curso">
                  </div>
                </div>
              </div>  

            </div>
          </div>
        </div>
        </div>
  </div>
</section>




<img
src="images/site/paint-grey.svg"
id="challenges"
alt="Miles de proyectos"
style=" margin-left: 20px;
margin-right: 20px;"
/>

</div>
  `
  ,
  data() {
      return{
          apiCategorias:[],
          cursos:"",
      }
  },
  props:{
  },
  methods:{
      buscarCursos(){
        console.log("buscar Cursos")

          fetch("ApiRes/perfil_usuario.php?usuario="+sessionStorage.loggedUser)

         .then(response => response.json() )
        .then((data)=>{
              this.cursos = data.cursos_pendientes
              console.log(data.cursos_pendientes)


          })
      },
      accederCurso(curso){
          console.log(curso)
          sessionStorage.idCurso = curso.id_curso
          window.location.href = "curso.html"
      },
  },
  computed:{  
  },
  mounted: function(){
      this.buscarCursos()

  }
})

Vue.component('ultimos-desafios',{
  template:`
  <div  style="text-align: center; margin-top: 100px">

  <section  style="
  background-color: #ececec;
  padding-top: 50px;
  padding-bottom: 30px;
  margin-left: 20px;
  margin-right: 20px;">
  <h1 class="titulo">Últimos desafíos</h1>

  <div class="container">
  <div class="row" style="justify-content: center">
    <div v-for="desafio in desafios" :key="desafio.id">
      <div class="card card-desafio"  style="cursor: pointer">
        <div >
            <img :src="desafio.url_contenido" @click="verChallenge(desafio)" style="border-radius:15px; width: 150px" class="card-img" alt="categoria">

        </div>


    </div>
  </div>
</div>
</div>
</section>

<img
src="images/site/paint-grey.svg"
id="challenges"
alt="Miles de proyectos"
style=" margin-left: 20px;
margin-right: 20px;"
/>

</div>
  `
  ,
  data() {
      return{
          desafios:"",
      }
  },
  props:{
  },
  methods:{
    buscarDesafios(){
      console.log(sessionStorage.idCurso)
      fetch(
        "ApiRes/challenge_alumno.php?cantidad_challenge=4"
      )
        .then(response => response.json())
        .then(data => {
          console.log("Ultimos desafios")
          console.log(data)
          this.desafios = data
          
        });
    },
    verChallenge(challenge){
      sessionStorage.usuarioChallenge = challenge.usuario
      sessionStorage.idChallenge = challenge.id_challenge
      window.location.href = "challenge.html";
    },
  },
  computed:{  
  },
  mounted: function(){
      this.buscarDesafios()

  }
})

Vue.component('categorias',{
    template:`
    <div  style="text-align: center; margin-top: 100px">
    <img v-if="!typeUser"
    src="images/site/ver.svg"
    id="challenges"
    alt="Miles de proyectos"
    style="width:100%"
  />
    <section v-if="apiCategorias.length>0"       style="
    background-color: #ececec;
    padding-top: 50px;
    padding-bottom: 30px;
    margin-left: 20px;
    margin-right: 20px;">
    <h1 class="titulo">Categorías</h1>
    <div class="container">
      <div class="row" style="justify-content: center" >
        <div v-for="categoria in apiCategorias" :key="categoria.nombre"  @click="accederCategoria(categoria.id)">
          <div class="card categoria" :style="[categoria.styleObject]">
            <img :src="categoria.imagen" width="200px" alt="Arkidia" />
            <div class="card-body">
              <p class="texto-car-categoria">{{ categoria.nombre }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <img
  src="images/site/paint-grey.svg"
  id="challenges"
  alt="Miles de proyectos"
  style=" margin-left: 20px;
  margin-right: 20px;"
/>
  </div>
    `
    ,
    data() {
        return{
            apiCategorias:[],
            typeUser:sessionStorage.typeUser,

        }
    },
    props:{
    },
    methods:{
        buscarCategorias(){
            console.log("Busco las categorias")
            fetch("ApiRes/categorias.php?usuario=aplicacion")

           .then(response => response.json() )
          .then((data)=>{
                data.forEach(element => {
                    if(element.estado ==="P"){
                        this.apiCategorias.push({
                            id: element.id_categoria,
                            nombre: element.descripcion, 
                            imagen: element.imagen_categoria, 
                            styleObject:{ backgroundColor: element.color}}
                        )
                    }
                    })
            })
        },
        accederCategoria(idCategoria){
            console.log(idCategoria)
            sessionStorage.idCategoria = idCategoria
            window.location.href = "categoria.html"
        },
    },
    computed:{  
    },
    mounted: function(){
        this.buscarCategorias()

    }
})

Vue.component('arkidians',{
    template:`
    <div>
    <section v-if="resumenHijos.length==0" id="noArkidians" style="height: 700px" >
        <div style="display:table-cell; vertical-align: middle; text-align: center" >
        <h1 class="titulo">{{saludo}}  {{usuarioPadreNombre}}</h1>
        <h4>Aún no creaste ningún Arkidian</h4>
        <a href="hijos.html" class="btn btn-primary">Administrar Arkidians</a>
        </div>
    </section>

    <section v-if="resumenHijos.length>0" style="padding-top:80px"> 
    <div class="container">
      <div class="row" style="justify-content: center;" >
        <div v-for="resumen in resumenHijos" :key="resumen.usuario"  @click="verPerfil(resumen.usuario)">
          <div class="card categoria" >
            <img :src="resumen.avatar" style="background-color:grey; border-radius:50%; width:200px; margin:10px"
            class="img img-rounded" />
            <h5 class="titulo">{{resumen.nickname}}</h5>
          </div>
        </div>
      </div>
    </div>
  </section>


    </div>

    `
    ,
    data() {
        return{
            deleteHijo:'',
            apiCategorias:[],
            hijos:[],
            usuarioPadre:"",
            usuarioPadreNombre:"",
            saludo:"",
            resumenHijos:[]
        }
    },
    props:{
    },
    methods:{
        buscarHijos(usuarioPadre) {
            fetch("ApiRes/hijos.php?usuario_padre=" + usuarioPadre)
                .then(response => response.json())
                .then((data) => {
                    data.forEach(element => {
                        console.log(data)

                        this.resumenHijos.push({
                            nickname: element.alias,
                            edad: element.edad,
                            avatar: element.avatar,
                            usuario: element.usuario
                        })
                    })
                })
        },
        verPerfil(usuario){
          console.log(usuario)
          sessionStorage.profileUser = usuario
          window.location.href = "perfil.html"
  
        },
    },
    computed:{  
    },
    mounted: function(){
        var d = new Date()
        var n = d.getHours()
        console.log(n)
        if(n>=3 && n<13){
            this.saludo = "Buenos días"
        }
        if(n>=13 && n<20){
            this.saludo = "Buenas tardes"
        }
        if(n>=20 && n <3){
            this.saludo = "Buenas noches"
        }

        this.usuarioPadreNombre = sessionStorage.loggedName
        this.logged=true
        this.buscarHijos(sessionStorage.loggedUser)

    }
})

Vue.component('administrador',{
    template:`
    <section>

    <h1 class="titulo" style="margin-top:100px">Perfil administrador</h1>
    <div class="container">
      <div class="row" style="justify-content: center">
        <div class="card categoria" style="background-color:grey; cursor: pointer" @click="accederAdmCursos()" >
          <div class="card-body">
            <p class="texto-car-categoria">Administrar cursos</p>
          </div>
        </div>
        <div class="card categoria" style="background-color:grey; cursor: pointer"  @click="accederParametros()">
          <div class="card-body">
            <p class="texto-car-categoria">Parámetros de sistema</p>
          </div>
        </div>
        <div class="card categoria" style="background-color:grey; cursor: pointer"  @click="accederDashboard()">
          <div class="card-body">
            <p class="texto-car-categoria">Dashboard</p>
          </div>
        </div>
      </div>
    </div>

  </section>
    `
    ,
    data() {
        return{
        }
    },
    props:{
    },
    methods:{
        accederAdmCursos(){
            window.location.href = 'admCursos.html'
        },
        accederParametros(){
            window.location.href = 'parametros.html'
        },
        accederDashboard(){
            window.location.href = 'dashboard.html'
        }
    },
    computed:{  
    }
})


Vue.component('bienvenida-hijo',{
  template:`
  <section>
    <div style="text-align:center">
  <img :src="perfil.avatar" style="background-color:#ececec; border-radius:50%; width:200px; margin-top:100px; margin-bottom:20px"
  class="img img-rounded img-fluid" />
  <h1 class="titulo" style="background:#ececec; border-radius:30px; margin:auto; width:300px" >Hola {{perfil.alias}}</h1>
  </div>

</section>
  `
  ,
  data() {
      return{
        perfil:""
      }
  },
  props:{
  },
  methods:{
    buscarPerfil() {
      fetch(
        "ApiRes/perfil_usuario.php?usuario=" +
        sessionStorage.loggedUser
      )
        .then(response => response.json())
        .then(data => {
            this.perfil = data


        });
    },
  },
  computed:{  
  },
  mounted: function(){
    this.buscarPerfil()
  }
})




var app = new Vue({
    el: '#app',
    data: {
      classIndex : "",
        logged:false,
        user:"",
        admin:false,
        padre:false,
        hijo:false,
        nombre:"",
      login:{usuario:"",password:""},
      register:{nombre:"",apellido:"",correo:"",password:"",confirm:""},
      loginError: false,
      registerError:false,
      registerMsg:"",
      mensajeErrorLogin:"",
      cambia:false,


    },
    methods:{

        
    },
    mounted: function(){
        this.padre=false
        this.admin=false
        this.hijo=false
        this.classIndex = "index-other"
        if(sessionStorage.loggedUser>"") {
          this.logged = true
          this.nombre = sessionStorage.loggedName
          if(sessionStorage.typeUser=="ADMINISTRADOR") this.admin = true
          if(sessionStorage.typeUser=="PADRE") this.padre = true
          if(sessionStorage.typeUser=="HIJO") {
            this.classIndex = "index-kids"
            this.hijo = true
          }
        }
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })
    }
  })

