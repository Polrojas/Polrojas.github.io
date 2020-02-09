Vue.component('menu-arkidia',{
    template:`
    <div>
      <div class="loader">
        <div
            class="logo-size bodymovin"
            data-icon="json/ciencia.json"
            data-aplay="true"
            data-loop="false"
            style="width:500px"
        ></div>
      </div>
      <header class="pantalla-completa" id="index">
        <nav class="navbar navbar-expand-sm navbar-light menu-style fixed-top">
          <a class="navbar-brand" href="index.html">
            <img src="./images/logo.png" width="150px" alt="Arkidia" />
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
           aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li v-if="!datamenu.logged" class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#login">Iniciar sesión</a>
              </li>
              <li v-if="datamenu.logged && datamenu.admin" class="nav-item">
                <a class="nav-link" href="admCursos.html">Administrar Cursos</a>
              </li>
              <li v-if="datamenu.logged && datamenu.admin" class="nav-item">
                <a class="nav-link" href="parametros.html">Parámetros</a>
              </li>
              <li v-if="datamenu.logged && datamenu.admin" class="nav-item">
              <a class="nav-link" href="aprobaciones.html">Aprobaciones</a>
              </li>
              <li v-if="datamenu.logged && datamenu.padre" class="nav-item">
                <a class="nav-link" @click="verPerfil()">Perfil</a>
              </li>
              <li v-if="datamenu.logged && datamenu.padre" class="nav-item">
                <a class="nav-link" href="hijos.html" >Administrar Arkidians</a>
              </li>
              <li v-if="datamenu.logged && datamenu.hijo" class="nav-item">
              <a class="nav-link" @click="verPerfil()">Perfil</a>
              </li>

              <li v-if="datamenu.logged && ( datamenu.hijo || datamenu.padre)" class="nav-item">
              <a class="nav-link" data-toggle="modal" data-target="#notification" ><img src="./images/site/notificacion.svg" width="20px" alt="Arkidia"></img>Notificaciones</a>
              </li>

 

            <li v-if="datamenu.logged" style="float:right; cursor:pointer" class="nav-item">
            <a class="nav-link"  @click="hacerLogoff()">Salir</a>
          </li>



          
            </ul>
          </div>
      </nav>
    </header>



    <div
    class="modal fade"
    id="login"
    tabindex="-1"
    role="dialog"
    aria-labelledby="loginCenterTitle"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body" style="padding:0px">
          <img
            src="images/logo.svg"
            id="icon"
            alt="User Icon"
            style="padding: 20px"
          />
          <!-- Login Form -->
          <form style="margin: 20px">
              <div class="form-group">
                  <label>Usuario</label>
                  <input type="email" class="form-control" v-model="login.usuario"  aria-describedby="emailHelp" placeholder="Ingresá tu correo electrónico o usuario">
              </div>
  
              <div class="form-group">
                  <label>Contraseña</label>
                  <input type="password" class="form-control" v-model="login.password"  placeholder="Ingresá tu contraseña">
              </div>
  
            <h6 v-if="loginError" style="color: darkred">
              {{mensajeErrorLogin}}
            </h6>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="hacerLogin(login)"
            >Ingresar
            </button>
          </form>
  
          <!-- Remind Passowrd -->
          <div id="formFooter">
            <a class="underlineHover" href="correo.php"
              >Olvidé mi contraseña</a
            >
            <a class="underlineHover" data-toggle="modal" data-target="#register" data-dismiss="modal">Registrarme</a>
            <img
              class="login-background"
              src="images/background-bottom.svg"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Registro -->
  <div
    class="modal fade"
    id="register"
    tabindex="-1"
    role="dialog"
  >
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-body">
          <h4>Registrarme en Arkidia</h4>
          <form style="margin: 20px">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" v-model="register.nombre" placeholder="Ingresá tu nombre">
            </div>
            <div class="form-group">
                <label>Apellido</label>
                <input type="text" class="form-control" v-model="register.apellido" placeholder="Ingresá tu apellido">
            </div>
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="text" class="form-control" v-model="register.correo" placeholder="Ingresá tu correo electrónico">
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" class="form-control" v-model="register.password" placeholder="Ingresá tu contraseña">
            </div>
            <div class="form-group">
                <label>Confirmá tu contraseña</label>
                <input type="password" class="form-control" v-model="register.confirm" placeholder="Ingresá tu contraseña nuevamente">
            </div>
            <h6 v-if="registerError" style="color: darkred">
              {{registerMsg}}
            </h6>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="hacerRegistro(register)"
            >Registrarme
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal Notificaciones -->
  <div
    class="modal fade"
    id="notification"
    tabindex="-1"
    role="dialog"
  >
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-body">
          <h4>Notificaciones</h4>
          <div v-for="(notificacion,index) in notificaciones" style="padding:15px">
          <div class="commenterImage">
              <img :src="notificacion.avatar" />
          </div>
          <div class="commentText">
              <p>{{notificacion.texto}}</p>
              <span class="date sub-text">{{notificacion.fecha}}</span>
          </div>
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
          login:{usuario:"",password:""},
          register:{nombre:"",apellido:"",correo:"",password:"",confirm:""},
          loginError: false,
          registerError:false,
          registerMsg:"",
          mensajeErrorLogin:"",
          cambia:false,
          notificaciones:[],
        }
    },
    props:{
      datamenu:{
        logged:false,
        user:"",
        admin:false,
        padre:false,
        hijo:false,
        nombre:""
      }
    },
    methods:{
      verNotificaciones(){
        console.log("Ver notificaciones")
        notificacion = {
          avatar:"images/arkidians/ark3.svg",
          fecha:"2020-02-08 09:53:07",
          alias:"Pablin",
          usuario:"Polito12",
          texto:"A Pablo le gustó tu challenge"
        }
        this.notificaciones.push(notificacion)

      },
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
    verPerfil(){
      sessionStorage.profileUser = sessionStorage.loggedUser
      window.location.href = "perfil.html"
    },
    verAprobaciones(){
      window.location.href = "aprobaciones.html"
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
      hacerLogoff(){
        sessionStorage.removeItem("typeUser")
        sessionStorage.removeItem("loggedUser")
        sessionStorage.removeItem("loggedName")
        this.datamenu.admin=false
        this.datamenu.padre=false
        this.datamenu.hijo=false
        this.datamenu.logged=false
        this.datamenu.nombre=""
        this.datamenu.user=""
        window.location.href = "index.html";
      }
    },
    computed:{
        
    },
    mounted: function(){
      this.verNotificaciones()

    }
})





var menuArkidia = new Vue({
  el: '#menu-arkidia',
  data: {
    datamenu: {
      nombre:"Pol",
      admin:false,
      padre:false,
      hijo:false,
      logged:false
    }
  },
  methods:{

  },
  mounted: function(){
    this.datamenu.padre=false
    this.datamenu.admin=false
    this.datamenu.hijo=false
    if(sessionStorage.loggedUser>"") {
      this.datamenu.logged = true
      this.datamenu.nombre = sessionStorage.loggedName
      if(sessionStorage.typeUser=="ADMINISTRADOR") this.datamenu.admin = true
      if(sessionStorage.typeUser=="PADRE") this.datamenu.padre = true
      if(sessionStorage.typeUser=="HIJO") this.datamenu.hijo = true

    }
  }
})
