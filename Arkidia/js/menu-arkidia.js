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
        <nav class="navbar navbar-expand-lg navbar-light menu-style fixed-top">
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
              <li v-if="datamenu.logged && datamenu.padre" class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#login">Perfil</a>
              </li>
              <li v-if="datamenu.logged && datamenu.padre" class="nav-item">
                <a class="nav-link" href="hijos.html" >Administrar Arkidians</a>
              </li>
              <li v-if="datamenu.logged && datamenu.hijo" class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#login">Mi perfil</a>
              </li>
              <li v-if="datamenu.logged" class="nav-item">
                <a class="nav-link" @click="hacerLogoff()">Salir</a>
              </li>
            </ul>
          </div>
      </nav>
    </header>
  </div>
    `
    ,
    data() {
        return{
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
