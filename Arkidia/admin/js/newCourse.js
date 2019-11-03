
var app = new Vue({
    el: '#app',
    data: {
        proveedores: [{
            id:1,
            nombre:"Proov1"
            },{
            id:2,
            nombre:"Proov2"
            },   {
            id:3,
            nombre:"Proov3"
            },   {
            id:4,
            nombre:"Proov4"
            },         
      ],
        proveedor:"",
        categorias: [],
        
        contenido:{
            id:"",
            nombre:"",
            orden: "",
            URLContenido:""
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
            fetch("../ApiRes/categorias.php?usuario=" + sessionStorage.loggedUser)
           .then(response => response.json() )
          .then((data)=>{
                data.forEach(element => {
                    app.categorias.push({
                        id: element.id_categoria,
                        nombre: element.descripcion}
                       )
                  })
            })
        },
        buscarContenido(id_curso){
          fetch("../ApiRes/contenido_curso.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+id_curso)
         .then(response => response.json() )
        .then((data)=>{
              data.forEach(element => {
                app.contenidos.push({
                  nombre:element.nombre_contenido,
                  orden:element.orden,
                  URLContenido:element.url_contenido,
                  id:element.id_contenido
              })
                })
          })
      },
      buscarChallenge(id_curso){
        fetch("../ApiRes/challenges_cursos.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+id_curso)
       .then(response => response.json() )
      .then((data)=>{
            data.forEach(element => {
              app.challenges.push({
                nombre:element.nombre_challenge,
                orden:element.orden_challenge,
                explicacion:element.detalle_challenge,
                id:element.id_challenge
            })
              })
        })
    },
        buscarProveedores(){
            console.log("buscando proveedores")
        },
        crearCurso(curso){
          bodyApi = "usuario=" + sessionStorage.loggedUser + 
                    "&id_categoria=" + curso.categoria.id + 
                    "&nombre_curso=" + curso.nombre + 
                    "&detalle_curso=" + curso.detalle + 
                    "&edad_desde=" + curso.edadDesde + 
                    "&edad_hasta=" + curso.edadHasta + 
                    "&id_proveedor=" + curso.proveedor.id  
          console.log(bodyApi)
          fetch("../ApiRes/cursos.php", {
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
                          app.errorCurso=true
                          app.msgErrorCurso = result.mensaje
                          console.log(result.mensaje)
                      }else{
                        app.errorCurso=false
                        app.msgErrorCurso = null
                        app.curso.id = result.curso
                        app.curso.detalleCreado = "ID# " + result.curso
                        app.curso.estado = "B"
                        app.curso.creado = true
                        app.mostrarContenido=true
                        app.mostrarChallenge=true
                      }
                  })
              } else {
                  throw "Error en la llamada Ajax"
              }
           })
        },

        grabarCurso(curso){
          bodyApi = "id_curso=" + curso.id + 
                    "&usuario=" + sessionStorage.loggedUser + 
                    "&id_categoria=" + curso.categoria.id + 
                    "&nombre_curso=" + curso.nombre + 
                    "&detalle_curso=" + curso.detalle + 
                    "&edad_desde=" + curso.edadDesde + 
                    "&edad_hasta=" + curso.edadHasta + 
                    "&id_proveedor=" + curso.proveedor.id +
                    "&estado_curso=" + curso.estado 
          console.log(bodyApi)
          fetch("../ApiRes/cursos.php?"+bodyApi, {
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
                          app.errorCurso=true
                          app.msgErrorCurso = result.mensaje
                          console.log(result.mensaje)
                      }else{
                        console.log("Curso modificado")

                      }
                  })
              } else {
                  throw "Error en la llamada Ajax"
              }
           })
        },

        insertarContenido(contenido){

          bodyApi = "orden=" + contenido.orden + 
                    "&id_curso=" + app.curso.id + 
                    "&nombre_contenido=" + contenido.nombre + 
                    "&url_contenido=" + contenido.URLContenido + 
                    "&usuario=" + sessionStorage.loggedUser
          fetch("../ApiRes/contenido_curso.php", {
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
                          id:result.id_contenido
                      })
                      app.contenido.nombre = ""
                      app.contenido.URLContenido = ""
                      app.contenido.orden = ""
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
          fetch("../ApiRes/contenido_curso.php?id_contenido=" + id + "&usuario=" + sessionStorage.loggedUser, {
            method: "DELETE"
          })
            .then(() => {
              for( var i = 0; i < app.contenidos.length; i++){ 
                if ( app.contenidos[i].id === id) {
                  app.contenidos.splice(i, 1); 
                }
             }
            })
        },

        insertarChallenge(challenge){
          bodyApi = "orden_challenge=" + challenge.orden + 
                    "&id_curso=" + app.curso.id + 
                    "&nombre_challenge=" + challenge.nombre + 
                    "&detalle_challenge=" + challenge.explicacion + 
                    "&usuario=" + sessionStorage.loggedUser
          console.log(bodyApi)
          fetch("../ApiRes/challenges_cursos.php", {
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
          fetch("../ApiRes/challenges_cursos.php?id_challenge=" + id + "&usuario=" + sessionStorage.loggedUser, {
            method: "DELETE"
          })
            .then(() => {
              for( var i = 0; i < app.challenges.length; i++){ 
                if ( app.challenges[i].id === id) {
                  app.challenges.splice(i, 1); 
                }
             }
            })
        },
        volver(){
          window.location.href = "admCursos.html";

        },
        buscarCurso(idCurso){
          console.log(idCurso)

          fetch("../ApiRes/cursos.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+idCurso)
          .then(response => response.json() )
         .then((data)=>{
              console.log(data)
              app.curso.creado = true
              app.curso.detalleCreado = "ID# " + data.id_curso
              app.curso.id = data.id_curso
              app.curso.nombre = data.nombre_curso
              app.curso.detalle = data.detalle_curso
              app.curso.categoria = this.buscarCategoria(data.id_categoria)
              app.curso.proveedor = this.buscarProveedor(data.id_proveedor)

              app.curso.edadDesde = data.edad_desde
              app.curso.edadHasta = data.edad_hasta
              app.curso.estado = data.estado_curso
              app.mostrarContenido = true
              app.mostrarChallenge = true

           })
        },
        buscarCategoria(id){
          for(i=0;i<app.categorias.length;i++){
            if(app.categorias[i].id === id){
              return(app.categorias[i])
            }
          }
        },
        
        buscarProveedor(id){
          for(i=0;i<app.proveedores.length;i++){
            if(app.proveedores[i].id == id){
              return(app.proveedores[i])
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
        this.buscarCategorias()
        console.log(sessionStorage.idCurso)
        if(sessionStorage.idCurso){
          this.buscarCurso(sessionStorage.idCurso)
          this.buscarContenido(sessionStorage.idCurso)
          this.buscarChallenge(sessionStorage.idCurso)
        }


      }

    
      
  })



  
  
