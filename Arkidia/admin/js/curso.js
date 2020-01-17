var app = new Vue({
  el: "#app",
  data: {
    categoriaStyleBar:"",
    categoriaStyle:"",

    categorias: [],
    color:"",
    contenidos: [],
    challenges: [],
    items: [],
    curso: {
      id: null,
      nombre: null,
      detalle: null,
      categoria: null,
      ind_completo: null,
      imagenCategoria:null,
    }
  },
  methods: {
    buscarInscripcion() {

      fetch(
        "../ApiRes/inscripcion.php?usuario=" +
          sessionStorage.loggedUser +
          "&id_curso=" +
          sessionStorage.idCurso
      )
        .then(response => response.json())
        .then(data => {
          console.log("buscar Inscripcion")
          console.log(data)
          app.curso.categoria = this.buscarCategorias(data.id_categoria)
          app.curso.id = data.id_curso
          app.curso.nombre = data.nombre_curso
          app.curso.detalle = data.detalle_curso
          app.curso.ind_completo = data.ind_completo
          app.contenidos = data.contenido.concat(data.challenge) 
          app.challenges = data.challenge
          this.formatoContenido()
        });
    },


    formatoContenido(){
      for(i=0;i<app.contenidos.length; i++){
        if(app.contenidos[i].porcentaje_avance == 100){
          app.contenidos[i].styleObject = { color: app.curso.color }
        }else{
          if(app.contenidos[i].ind_completo>0){
            app.contenidos[i].styleObject = { color: app.curso.color }
          }else{
          app.contenidos[i].styleObject = { color: "#bbbbbb" }
        }
        }
      }
    },
    buscarCategorias(id) {
      console.log("buscarCategorias" + id)
      fetch("../ApiRes/categorias.php?usuario=" + sessionStorage.loggedUser + "&id_categoria=" + id)
        .then(response => response.json())
        .then(data => {
          app.categorias = data
                    
          app.curso.imagenCategoria = "../" + data.imagen_categoria
          //console.log(app.curso.imagenCategoria)
          app.curso.styleObject = { backgroundColor: data.color }
          app.curso.colorObject = { color: data.color }
          app.categoriaStyleBar = "background-color: " + data.color + "dd"
          app.categoriaStyle= "background-color: " + data.color
          app.curso.color = data.color




          console.log(data)




        });
    },
    volver() {
      window.location.href = "admCursos.html";
    },
    verVideo(contenido) {
      sessionStorage.contenido = contenido.id_contenido;
      sessionStorage.video = contenido.url_contenido;
      sessionStorage.avance = contenido.porcentaje_avance;
      window.location.href = "video.html";
    },

    buscarCategoria(id) {
      for (i = 0; i < app.categorias.length; i++) {
        if (app.categorias[i].id_categoria === id) {
          app.nombreCategoria = app.categorias[i].nombre;
          app.styleObject = app.categorias[i].styleObject;
          app.colorObject = app.categorias[i].colorObject;
          app.color = app.categorias[i].color;
          app.imagenCategoria = "../" + app.categorias[i].imagen;






          return app.categorias[i];
        }
      }
    }
  },

  mounted: function() {
    if (sessionStorage.idCurso) {
      this.buscarCategorias();
      this.buscarInscripcion();
      window.addEventListener("load",function (){
        const loader = document.querySelector(".loader");
        loader.className += " hidden";
      })

    }
  }
});



