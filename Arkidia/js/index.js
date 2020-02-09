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
        <button class="btn btn-primary" data-toggle="modal" data-target="#register" >Registrarme</button>

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
            ¿Cómo funciona Arkidia?
          </button>
        </h2>
      </div>
  
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body">
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
        </div>
      </div>
    </div>
    <div class="card" style="border:0px;background-color: #ececec;">
      <div class="card-header" id="headingTwo">
        <h2 class="mb-0">
          <button class="btn titulo collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            ¿Cuál es su costo?
          </button>
        </h2>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
        <div class="card-body">
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
        </div>
      </div>
    </div>
    <div class="card" style="border:0px;background-color: #ececec;">
      <div class="card-header" id="headingThree">
        <h2 class="mb-0">
          <button class="btn titulo collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            ¿Necesito comprar materiales?
          </button>
        </h2>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
        </div>
      </div>
    </div>
    <div class="card" style="border:0px;background-color: #ececec;">
      <div class="card-header" id="headingThree">
        <h2 class="mb-0">
          <button class="btn titulo collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            ¿Qué elementos de seguridad tiene Arkidia?
          </button>
        </h2>
      </div>
      <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
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
                        <h5 class="titulo">Inmersivo</h5>
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
                        <h5 class="titulo">de Calidad</h5>
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
                        <h5 class="titulo">Seguro</h5>
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
                    <strong>Pamela</strong>
               </p>
               <p>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
              </p>
               <div class="clearfix"></div>
                <p>Excelente plataforma para compartir con mis hijos!!</p>

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
                    <strong>Marcos</strong>
               </p>
               <p>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
              </p>
               <div class="clearfix"></div>
                <p>Me gusta mucho dibujar, y compartirlo con mis amigos y mis papás :)</p>

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
                    <strong>Nico</strong>
               </p>
               <p>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
                  <img src="images/site/star.svg" style="width:20px"/>
              </p>
               <div class="clearfix"></div>
                <p>Mis hijos aprenden y si divierten al mismo tiempo. Me encanta la cantidad y calidad del contenido</p>

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


Vue.component('categorias',{
    template:`
    <div  style="text-align: center; margin-top: 100px">
    <img
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
      <h1 class="titulo">Resumen</h1>
      <div class="card mb-3 card-resumen-hijo">
          <div v-for="resumen in resumenHijos" :key="resumen.id">
          <div class="row ">
            <div class="col-md-4 " style="text-align: center;">
              <img v-bind:src="resumen.avatar" class="card-img" :alt="resumen.nickname" style="max-width:200px">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                  <h5 class="card-title subtitulo" style="text-align: left">{{resumen.nickname}}</h5>
                  <p>BADGES</p>
                  <div v-for="badge in resumen.badges" :key="badge.nombre">
                    <p>{{badge.nombre}} nivel {{badge.nivel}}</p>
                  </div>
                  <p>ULTIMOS CURSOS</p>
                  <div v-for="curso in resumen.ultCursos" :key="curso.nombre">
                    <p>{{curso.nombre}} completado {{curso.porcentaje}}</p>
                  </div>
              </div>
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
                            badges:[{nombre:"pintura",nivel:"5"},{nombre:"cocina",nivel:"2"}],
                            ultCursos:[{nombre:"Cocina una chocotorta",porcentaje:"30%"},{nombre:"Como dibujar un dinosaurio",porcentaje:"100%"}]
                
                        })
                    })
                })
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







var app = new Vue({
    el: '#app',
    data: {
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
        if(sessionStorage.loggedUser>"") {
          this.logged = true
          this.nombre = sessionStorage.loggedName
          if(sessionStorage.typeUser=="ADMINISTRADOR") this.admin = true
          if(sessionStorage.typeUser=="PADRE") this.padre = true
          if(sessionStorage.typeUser=="HIJO") this.hijo = true
        }
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })
    }
  })

