var app = new Vue({
  el: "#app",
  data: {
    categorias: [],
    nombreCategoria: "",
    styleObject: "",
    colorObject: "",
    color:"",
    imagenCategoria: "",
    contenidos: [],
    challenges: [],
    curso: {
      id: null,
      nombre: null,
      detalle: null,
      categoria: null,
      ind_completo: null
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
          app.curso.id = data.id_curso;
          app.curso.nombre = data.nombre_curso;
          app.curso.detalle = data.detalle_curso;
          app.curso.categoria = this.buscarCategoria(data.id_categoria);
          app.curso.ind_completo = data.ind_completo;
          app.contenidos = data.contenido;
          app.challenges = data.challenge;
          this.formatoContenido();
        });
    },
    formatoContenido(){
      console.log(app.contenidos)

      for(i=0;i<app.contenidos.length; i++){
        console.log(app.contenidos[i].porcentaje_avance)
        if(app.contenidos[i].porcentaje_avance == 100){
          app.contenidos[i].styleObject = { color: app.color }
        }else{
          app.contenidos[i].styleObject = { color: "#DDDDDD" }
        }
      }

    },
    buscarCategorias() {
      fetch("../ApiRes/categorias.php?usuario=" + sessionStorage.loggedUser)
        .then(response => response.json())
        .then(data => {
          data.forEach(element => {
            app.categorias.push({
              id: element.id_categoria,
              nombre: element.descripcion,
              color: element.color,
              video: element.link_video,
              imagen: element.imagen_categoria,
              styleObject: { backgroundColor: element.color },
              colorObject: { color: element.color },
              color: element.color
            });
          });
        });
    },
    volver() {
      window.location.href = "admCursos.html";
    },
    verVideo(contenido) {
      sessionStorage.contenido = contenido.id_contenido;
      sessionStorage.video = contenido.url_contenido;
      window.location.href = "contenido.html";
    },

    buscarCategoria(id) {
      for (i = 0; i < app.categorias.length; i++) {
        if (app.categorias[i].id === id) {
          console.log("categoria " + app.categorias[i]);
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
    this.buscarCategorias();
    console.log(sessionStorage.idCurso);
    if (sessionStorage.idCurso) {
      this.buscarInscripcion();
    }
  }
});
