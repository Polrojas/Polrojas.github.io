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



Vue.component('categorias',{
    template:`
    <section v-if="apiCategorias.length>0">
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
        hacerLogin(login){
            fetch("ApiRes/login.php",{
                method: 'POST',
                body: "usuario="+login.usuario+"&password="+login.password,
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'})
            })
            .then(function(response) {
                if(response.ok) {
                    loginResponse = response.json()
                    loginResponse.then(function(result) {
                        if (result.resultado ==="ERROR"){
                            sessionStorage.removeItem("typeUser")
                            sessionStorage.removeItem("loggedUser")
                            app.mensajeErrorLogin = result.mensaje
                            app.loginError = true
                            return
                        }


                        if (result.page==="PADRE"){
                            window.location.href = "index.html";
                            sessionStorage.loggedUser = login.usuario
                            sessionStorage.loggedName = result.user
                            sessionStorage.typeUser = result.page
                            return
                        }
                        if (result.page==="HIJO"){
                            window.location.href = "index.html";
                            sessionStorage.loggedUser = login.usuario
                            sessionStorage.loggedName = result.user
                            sessionStorage.typeUser = result.page
                            return
                        }
                        if (result.page==="ADMINISTRADOR"){
                            sessionStorage.loggedUser = login.usuario
                            sessionStorage.loggedName = result.user
                            sessionStorage.typeUser = result.page
                            window.location.href = "index.html"
                            return
                        }

                    })
                } else {
                    throw "Error en la llamada Ajax"
                }
             })
        },
        hacerRegistro(register){
            fetch("ApiRes/registracion.php",{
                method: 'POST',
                body: "nombre="+register.nombre+"&apellido="+register.apellido+"&mail="+register.correo+"&password="+register.password+"&confirmacion="+register.confirm,
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'})
            })
            .then(function(response) {
                app.registerError=false
                if(response.ok) {
                    app.registerError=false

                    loginResponse = response.json()
                    loginResponse.then(function(result) {
                        if (result.resultado==="ERROR"){
                            app.registerError=true
                            app.registerMsg = result.mensaje
                        }else{
                        window.location.href = "SitioPadre.html";
                        sessionStorage.loggedUser = register.correo
                        sessionStorage.loggedName = register.nombre
                        sessionStorage.typeUser = "PADRE"
                    }
                    })
                } else {
                    throw "Error en la llamada Ajax"
                }
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
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })
    }
  })

