Vue.component('restringido',{
  template:`
  <div class="container">
  <div class="row">
      <div class="col-sm-6">
          <img src="images/site/stop.svg" style="width:80%" alt="STOP"/>
      </div>
      <div class="col-sm-6" style="align-self: center">
          <h1 class="titulo">Acceso restringido a Administradores</h1>
          <p>Para acceder este contenido necesitás <a class="link" href="index.html">iniciar sesión</a></p>  
      </div>
  </div>
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


var app = new Vue({
    el: '#app',
    data: {
      btnGrabarCurso:null,
      logged: false,
      padre: false,
      admin: false,
      hijo: false,

        proveedores: [],
        proveedor:"",
        categorias: [],
        
        contenido:{
            id:"",
            nombre:"",
            orden: "",
            URLContenido:"",
            URLImagen:""
        },
        challenge:{
          id:"",
          nombre:"",
          orden: "",
          explicacion:""
        },
        mostrarContenido:false,
        mostrarChallenge:false,
        errorCurso:null,
        msgErrorCurso: null,
        errorContenido:false,
        msgErrorContenido: null,
        errorChallenge:false,
        msgErrorChallenge: null,
        contenidos:[],
        challenges:[],
        curso:{
          creado:false,
          detalleCreado:"aún no creado",
          id:null,
          nombre:null,
          detalle:null,
          categoria:null,
          proveedor:null,
          edadDesde:null,
          edadHasta:null,
          estado:null,
        }


    },
    methods: {
        buscarCategorias(){
            fetch("ApiRes/categorias.php?usuario=" + sessionStorage.loggedUser)
           .then(response => response.json() )
          .then((data)=>{
                data.forEach(element => {
                    this.categorias.push({
                        id: element.id_categoria,
                        nombre: element.descripcion}
                       )
                  })
            })
        },
        buscarContenido(id_curso){
          fetch("ApiRes/contenido_curso.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+id_curso)
         .then(response => response.json() )
        .then((data)=>{
              data.forEach(element => {
                this.contenidos.push({
                  nombre:element.nombre_contenido,
                  orden:element.orden,
                  URLContenido:element.url_contenido,
                  URLImagen:element.url_imagen,
                  id:element.id_contenido
              })
                })
          })
      },
      buscarChallenge(id_curso){
        fetch("ApiRes/challenges_cursos.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+id_curso)
       .then(response => response.json() )
      .then((data)=>{
            data.forEach(element => {
              this.challenges.push({
                nombre:element.nombre_challenge,
                orden:element.orden_challenge,
                explicacion:element.detalle_challenge,
                id:element.id_challenge
            })
              })
        })
    },
        buscarProveedores(){
          fetch("ApiRes/proveedores.php?usuario=" + sessionStorage.loggedUser)
          .then(response => response.json() )
         .then((data)=>{
           console.log(data)
           this.proveedores = data

           })
        },
        crearCurso(curso){
          bodyApi = "usuario=" + sessionStorage.loggedUser + 
                    "&id_categoria=" + curso.categoria.id + 
                    "&nombre_curso=" + curso.nombre + 
                    "&detalle_curso=" + curso.detalle + 
                    "&edad_desde=" + curso.edadDesde + 
                    "&edad_hasta=" + curso.edadHasta + 
                    "&id_proveedor=" + curso.proveedor.id_proveedor
          console.log(bodyApi)
          fetch("ApiRes/cursos.php", {
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
                      if (result.resultado==="ERROR"){
                          this.errorCurso=true
                          this.msgErrorCurso = result.mensaje
                          console.log(result.mensaje)
                      }else{
                        this.errorCurso=false
                        this.msgErrorCurso = null
                        this.curso.id = result.curso
                        this.curso.detalleCreado = "ID# " + result.curso
                        this.curso.estado = "B"
                        this.curso.creado = true
                        this.mostrarContenido=true
                        this.mostrarChallenge=true
                      }
                  })
              } else {
                  throw "Error en la llamada Ajax"
              }
           })
        },

        grabarCurso(curso){
          this.btnGrabarCurso = document.getElementById("btnGrabarCurso");
          bodyApi = "id_curso=" + curso.id + 
                    "&usuario=" + sessionStorage.loggedUser + 
                    "&id_categoria=" + curso.categoria.id + 
                    "&nombre_curso=" + curso.nombre + 
                    "&detalle_curso=" + curso.detalle + 
                    "&edad_desde=" + curso.edadDesde + 
                    "&edad_hasta=" + curso.edadHasta + 
                    "&id_proveedor=" + curso.proveedor.id_proveedor +
                    "&estado_curso=" + curso.estado 
          console.log(bodyApi)
          fetch("ApiRes/cursos.php?"+bodyApi, {
              method: 'PUT',
              body: bodyApi,
              headers: new Headers({
                  'Content-Type': 'application/x-www-form-urlencoded'
              })
          })
          .then(function(response) {
              if(response.ok) {
                  loginResponse = response.json()
                  loginResponse.then(function(result) {
                      if (result.resultado==="ERROR"){
                          this.errorCurso=true
                          this.msgErrorCurso = result.mensaje
                          console.log(result.mensaje)
                          this.btnGrabarCurso.style.background = "#444444";
                          this.btnGrabarCurso.innerText = "No fue posible grabar"
                      }else{
                        console.log("Curso modificado")
                        this.btnGrabarCurso.style.background = "#39a82f";
                        this.btnGrabarCurso.innerText = "Grabado"


                      }
                  })
              } else {
                  throw "Error en la llamada Ajax"
              }
           })
        },
        publicar(curso){
          curso.estado = "P"
          this.grabarCurso(curso)
        },
        anularPublicacion(curso){
          curso.estado = "B"
          this.grabarCurso(curso)

        },
        iniciarBoton(){
          this.btnGrabarCurso = document.getElementById("btnGrabarCurso");
          this.btnGrabarCurso.style.background = "#9c27b0";
          this.btnGrabarCurso.innerText = "Grabar Curso"

        },

        insertarContenido(contenido){

          bodyApi = "orden=" + contenido.orden + 
                    "&id_curso=" + this.curso.id + 
                    "&nombre_contenido=" + contenido.nombre + 
                    "&url_contenido=" + contenido.URLContenido + 
                    "&url_imagen=" + contenido.URLImagen + 
                    "&usuario=" + sessionStorage.loggedUser
          fetch("ApiRes/contenido_curso.php", {
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
                      if (result.resultado==="ERROR"){
                          app.errorContenido=true
                          app.msgErrorContenido = result.mensaje
                          console.log(result.mensaje)
                      }else{
                        console.log(result)
                        app.contenidos.push({
                          nombre:contenido.nombre,
                          orden:contenido.orden,
                          URLContenido:contenido.URLContenido,
                          URLImagen: contenido.URLImagen,
                          id:result.id_contenido
                      })
                      app.contenido.nombre = ""
                      app.contenido.URLContenido = ""
                      app.contenido.orden = ""
                      app.contenido.URLImagen = ""
                      }
                  })
              } else {
                  throw "Error en la llamada Ajax"
              }
           })

 
        },
        "get_rows_contenido": function get_rows_contenido() {
            return this.contenidos;
          },
        quitarContenido(id){
          fetch("ApiRes/contenido_curso.php?id_contenido=" + id + "&usuario=" + sessionStorage.loggedUser, {
            method: "DELETE"
          })
            .then(() => {
              for( var i = 0; i < this.contenidos.length; i++){ 
                if ( this.contenidos[i].id === id) {
                  this.contenidos.splice(i, 1); 
                }
             }
            })
        },

        insertarChallenge(challenge){
          bodyApi = "orden_challenge=" + challenge.orden + 
                    "&id_curso=" + this.curso.id + 
                    "&nombre_challenge=" + challenge.nombre + 
                    "&detalle_challenge=" + challenge.explicacion + 
                    "&usuario=" + sessionStorage.loggedUser
          console.log(bodyApi)
          fetch("ApiRes/challenges_cursos.php", {
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
                      if (result.resultado==="ERROR"){
                          app.errorChallenge=true
                          app.msgErrorChallenge = result.mensaje
                          console.log(result.mensaje)
                      }else{
                        console.log(result)
                        app.challenges.push({
                          nombre:challenge.nombre,
                          orden:challenge.orden,
                          explicacion:challenge.explicacion,
                          id:result.id_challenge

                      })
                      app.challenge.nombre = ""
                      app.challenge.orden = ""
                      app.challenge.explicacion = ""
                      }
                  })
              } else {
                  throw "Error en la llamada Ajax"
              }
           })


        },
        "get_rows_challenge": function get_rows_challenge() {
            return this.challenges;
          },
          
        quitarChallenge(id){
          console.log(id)
          fetch("ApiRes/challenges_cursos.php?id_challenge=" + id + "&usuario=" + sessionStorage.loggedUser, {
            method: "DELETE"
          })
            .then(() => {
              for( var i = 0; i < this.challenges.length; i++){ 
                if ( this.challenges[i].id === id) {
                  this.challenges.splice(i, 1); 
                }
             }
            })
        },
        volver(){
          window.location.href = "admCursos.html";

        },
        previsualizar(){
          console.log("voy a desinscribir")
          this.desinscribir()
        //  console.log("voy a inscribir")
        //  this.inscribir()

        },
        desinscribir() {
          fetch("ApiRes/inscripcion.php?usuario=" + sessionStorage.loggedUser + "&id_curso=" + this.curso.id, {
              method: "DELETE"
          })
              .then(() => {

                  console.log("Ya borré")
                  console.log("voy a inscribir")
                  this.inscribir()


              })
      },
        inscribir(){
          sessionStorage.idCurso = this.curso.id
          console.log("categoria " + this.curso.categoria.id)
          var categoria = this.curso.categoria.id

            bodyApi = "usuario=" + sessionStorage.loggedUser + 
                        "&id_curso=" + this.curso.id
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
                          if (result.resultado==="ERROR"){
                              console.log("ERROR")
                              console.log(result.mensaje)
                          }else{
                            console.log("inscripto")
                            console.log(categoria)
                            sessionStorage.idCategoria = categoria
                            window.location.href = "curso.html";
                          }
                      })
                  } else {
                      throw "Error en la llamada Ajax"
                  }
               })
    

        },
        buscarCurso(idCurso){
          console.log(idCurso)

          fetch("ApiRes/cursos.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+idCurso)
          .then(response => response.json() )
         .then((data)=>{
              console.log(data)
              this.curso.creado = true
              this.curso.detalleCreado = "ID# " + data.id_curso
              this.curso.id = data.id_curso
              this.curso.nombre = data.nombre_curso
              this.curso.detalle = data.detalle_curso
              this.curso.categoria = this.buscarCategoria(data.id_categoria)
              this.curso.proveedor = this.buscarProveedor(data.id_proveedor)

              this.curso.edadDesde = data.edad_desde
              this.curso.edadHasta = data.edad_hasta
              this.curso.estado = data.estado_curso
              this.mostrarContenido = true
              this.mostrarChallenge = true

           })
        },
        buscarCategoria(id){
          for(i=0;i<this.categorias.length;i++){
            if(this.categorias[i].id === id){
              return(this.categorias[i])
            }
          }
        },
        
        buscarProveedor(id){
          for(i=0;i<this.proveedores.length;i++){
            if(this.proveedores[i].id_proveedor == id){
              return(this.proveedores[i])
            }
          }        
        },

      },


    computed: {
        "columns_contenido": function columns() {
            if (this.contenidos.length == 0) {
              return [];
            }
            return Object.keys(this.contenidos[0])
          },
          "columns_challenge": function columns() {
            if (this.challenges.length == 0) {
              return [];
            }
            return Object.keys(this.challenges[0])
          },
      },
      mounted: function(){
        this.padre = false;
        this.admin = false;
        this.hijo = false;
        this.logged = false;

        if (sessionStorage.loggedUser > "") {
          this.logged = true;
          this.nombre = sessionStorage.loggedName;
          if (sessionStorage.typeUser == "ADMINISTRADOR") {
            this.buscarCategorias()
            this.buscarProveedores()
            if(sessionStorage.idCurso){
              this.buscarCurso(sessionStorage.idCurso)
              this.buscarContenido(sessionStorage.idCurso)
              this.buscarChallenge(sessionStorage.idCurso)
            }
            this.admin = true;
          }
          if (sessionStorage.typeUser == "PADRE") this.padre = true;
          if (sessionStorage.typeUser == "HIJO") this.hijo = true;
        }




        window.addEventListener("load",function (){
          const loader = document.querySelector(".loader");
          loader.className += " hidden";
        })
  






      }

    
      
  })



  
  
