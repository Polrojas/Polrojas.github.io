
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
                      
                    }
                     )
                })
          })
      },
      buscarContenido(id_curso){
        fetch("../ApiRes/contenido_curso.php?usuario=" + sessionStorage.loggedUser+"&id_curso="+id_curso)
       .then(response => response.json() )
      .then((data)=>{
            i=0
            data.forEach(element => {
              if(i==0){app.cont0 = element.nombre_contenido}
              if(i==1){app.cont1 = element.nombre_contenido}
              if(i==2){app.cont2 = element.nombre_contenido}
              if(i==3){app.cont3 = element.nombre_contenido}
              if(i==4){app.cont4 = element.nombre_contenido}
              if(i==5){app.cont5 = element.nombre_contenido}
              if(i==6){app.cont6 = element.nombre_contenido}
              if(i==7){app.cont7 = element.nombre_contenido}
              if(i==8){app.cont8 = element.nombre_contenido}
              if(i==9){app.cont9 = element.nombre_contenido}
              if(i==10){app.cont10 = element.nombre_contenido}
              if(i==11){app.cont11 = element.nombre_contenido}

              i++


              })
        })
    },
    completarContenido(){
      console.log("hola")

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

      volver(){
        window.location.href = "admCursos.html";

      },
      empezarCurso(){
        window.location.href = "curso.html";

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
      }


    }

  
    
})


