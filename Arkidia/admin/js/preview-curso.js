
var app = new Vue({
  el: '#app',
  data: {

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
      nombreCategoria:"",
      styleObject:"",
      colorObject:"",
      imagenCategoria:"",

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
      },
      cont0:"",
      cont1:"",
      cont2:"",
      cont3:"",
      cont4:"",
      cont5:"",
      cont6:"",
      cont7:"",
      cont8:"",
      cont9:"",
      cont10:"",
      cont11:"",
      cont12:"",
      cont13:"",
      chal0:"",
      chal1:"",
      chal2:"",
      chal3:"",
      chal4:"",
      chal5:"",


  },
  methods: {
      buscarCategorias(){
          fetch("../ApiRes/categorias.php?usuario=" + sessionStorage.loggedUser)
         .then(response => response.json() )
        .then((data)=>{
              data.forEach(element => {
                  app.categorias.push({
                      id: element.id_categoria,
                      nombre: element.descripcion,
                      color: element.color,
                      video: element.link_video,
                      imagen: element.imagen_categoria,
                      styleObject: { backgroundColor: element.color},
                      colorObject: { color: element.color}
                    }
                     )
                })
          })
      },
      buscarContenido(id_curso){
        fetch("../ApiRes/contenido_curso.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+id_curso)
       .then(response => response.json() )
      .then((data)=>{
            app.contenidos = data


        })
    },
    completarContenido(){
      console.log("hola")

    },
    buscarChallenge(id_curso){
      fetch("../ApiRes/challenges_cursos.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+id_curso)
     .then(response => response.json() )
    .then((data)=>{
      app.challenges = data
     
      })
  },

      volver(){
        window.location.href = "admCursos.html";

      },
      empezarCurso(curso){
        bodyApi = "usuario=" + sessionStorage.loggedUser + 
                    "&id_curso=" + curso.id
          console.log(bodyApi)
          fetch("../ApiRes/inscripcion.php", {
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
                    console.log(result)
                      if (result.resultado==="ERROR"){
                          console.log(result.mensaje)
                      }else{
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

        fetch("../ApiRes/cursos.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+idCurso)
        .then(response => response.json() )
       .then((data)=>{
            console.log(data)
            app.curso.id = data.id_curso
            app.curso.nombre = data.nombre_curso
            app.curso.detalle = data.detalle_curso
            app.curso.categoria = this.buscarCategoria(data.id_categoria)
            app.curso.edadDesde = data.edad_desde
            app.curso.edadHasta = data.edad_hasta
            app.curso.estado = data.estado_curso


         })
      },
      buscarCategoria(id){
        for(i=0;i<app.categorias.length;i++){
          if(app.categorias[i].id === id){
            console.log("categoria " + app.categorias[i])
            app.nombreCategoria = app.categorias[i].nombre
            app.styleObject = app.categorias[i].styleObject
            app.colorObject = app.categorias[i].colorObject
            app.imagenCategoria = "../"+app.categorias[i].imagen
            return(app.categorias[i])
          }
        }
      },
      

    },


    mounted: function(){
      this.buscarCategorias()
      console.log(sessionStorage.idCurso)
      if(sessionStorage.idCurso){
        this.buscarCurso(sessionStorage.idCurso)
        this.buscarContenido(sessionStorage.idCurso)
        this.completarContenido()
        this.buscarChallenge(sessionStorage.idCurso)
        window.addEventListener("load",function (){
          const loader = document.querySelector(".loader");
          loader.className += " hidden";
        })
  

      }


    }

  
    
})


