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


Vue.component("cursos", {
  template: `
  <div>
  <section> 
  <h1 class="titulo" style="margin-top:100px">Administración de cursos</h1>
  <a href="newCourse.html" class="btn btn-primary btn-round" role="button">Nuevo curso</a>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title ">Agrupamiento por curso</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <thead class=" text-primary">
                    <tr>
                      <th v-for="col in columns_curso" v-on:click="sortTablecurso(col)">{{col}}
                        <div class="arrow" v-if="col == sortColumn_curso" v-bind:class="ascending ? 'arrow_up' : 'arrow_down'">
                          </div>
                      </th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="curso in get_rows_curso()">
                      <td v-for="col in columns_curso">{{curso[col]}}</td>
                      <td>
                          <button class="btn btn-danger" data-toggle="modal"
                          data-target="#eliminarCurso" @click="cursoDelete = curso"><span>Eliminar</span></button> 
                          <button class="btn" @click="editarCurso(curso)"><span>Editar</span></button>
                          <button class="btn" @click="previsualizarCurso(curso)"><span>Previsualizar</span></button>
                      </td> 
                    </tr>
                  </tbody>
                </table>
                <div class="pagination">
                  <div class="number"
                    v-for="i in num_pages_curso()"
                    v-bind:class="[i == currentPage_curso ? 'active' : '']"
                    v-on:click="change_page_curso(i)">{{i}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>      
    </div>
  </div>
</section>

<!-- Modal Eliminar curso-->
<div class="modal fade" id="eliminarCurso" tabindex="-1" role="dialog" aria-labelledby="eliminarCursoCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarCursoCenterTitle">
          Eliminar Curso
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Estás seguro que querés eliminar el curso?
        Si continuás se eliminarán además los contenidos y challenges asociados. 
        Si hay Arkidians que hicieron este curso, no podrán ver más el contenido
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Cerrar
        </button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="eliminarCurso(cursoDelete)">
          Eliminar curso
        </button>
      </div>
    </div>
  </div>
</div>
</div>


  `,
  data() {
    return {
      currentPage_curso: 1,
      elementsPerPage_curso: 50,
      categorias: [],
      cursos: [],
      ascending: false,
      sortColumn_curso: "",
      cursoDelete: null,

    };
  },
  props: {

  },
  methods: {
    buscarCursos() {
      fetch("ApiRes/cursos.php?usuario=" + sessionStorage.loggedUser)
        .then(response => response.json())
        .then(data => {
          this.cursos = [];
          console.log(data);

          data.forEach(element => {
            this.cursos.push({
              id: parseInt(element.id_curso),
              nombre: element.nombre_curso,
              idCat: element.id_categoria,
              categoría: element.descripcion,
              proveedor: element.nombre_proveedor,
              estado: this.buscarEstado(element.estado_curso)
            });
          });
        });
    },

    buscarEstado(estado) {
      if (estado === "B") return "Borrador";
      if (estado === "P") return "Publicado";
    },

    editarCurso(curso) {
      console.log(curso.id);
      sessionStorage.idCurso = curso.id;
      window.location.href = "newCourse.html";
    },

    previsualizarCurso(curso) {
      sessionStorage.idCurso = curso.id;
      sessionStorage.idCategoria = curso.idCat
      window.location.href = "curso.html";
    },

    eliminarCurso(curso) {
      console.log(curso)
      fetch(
        "ApiRes/cursos.php?id_curso=" +
          curso.id +
          "&usuario=" +
          sessionStorage.loggedUser,
        {
          method: "DELETE"
        }
      ).then(() => {
        console.log("curso eliminado");
      });

      window.location.href = "admCursos.html";
    },

    sortTablecurso: function sortTablecurso(col) {
      if (this.sortColumn_curso === col) {
        this.ascending = !this.ascending;
      } else {
        this.ascending = true;
        this.sortColumn_curso = col;
      }

      var ascending = this.ascending;

      this.cursos.sort(function(a, b) {
        if (a[col] > b[col]) {
          return ascending ? 1 : -1;
        } else if (a[col] < b[col]) {
          return ascending ? -1 : 1;
        }
        return 0;
      });
    },
    num_pages_curso: function num_pages_curso() {
      return Math.ceil(this.cursos.length / this.elementsPerPage_curso);
    },
    get_rows_curso: function get_rows_curso() {
      return this.cursos.slice(
        (this.currentPage_curso - 1) * this.elementsPerPage_curso,
        (this.currentPage_curso - 1) * this.elementsPerPage_curso +
          this.elementsPerPage_curso
      );
    },
    change_page_curso: function change_page_curso(page_curso) {
      this.currentPage_curso = page_curso;
    }
  },
  computed: {
    columns_curso: function columns() {
      if (this.cursos.length == 0) {
        return [];
      }
      return Object.keys(this.cursos[0]);
    },

  },
  mounted: function() {
    this.buscarCursos();
  }
});

var app = new Vue({
  el: "#app",
  data: {
    cursoDelete:null,
    logged: false,
    padre: false,
    admin: false,
    hijo: false
  },

  methods: {
  },

  computed: {},

  mounted: function() {
    this.padre = false;
    this.admin = false;
    this.hijo = false;
    this.logged = false;

    if (sessionStorage.loggedUser > "") {
      this.logged = true;
      this.nombre = sessionStorage.loggedName;
      if (sessionStorage.typeUser == "ADMINISTRADOR") {
        this.admin = true;
      }
      if (sessionStorage.typeUser == "PADRE") this.padre = true;
      if (sessionStorage.typeUser == "HIJO") this.hijo = true;
    }
    sessionStorage.removeItem("idCurso");
  }
});
