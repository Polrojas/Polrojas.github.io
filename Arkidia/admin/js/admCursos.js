var app = new Vue({
    el: '#app',
    data: {
        logged:false,
        ascending: false,
        sortColumn_curso: '',
        currentPage_curso: 1,
        elementsPerPage_curso: 50,
        categorias:[],
        cursos: [],
        cursoDelete: null,

    },

    methods: {

        buscarCursos(){
          fetch("../ApiRes/cursos.php?usuario=" + sessionStorage.loggedUser)
          .then(response => response.json() )
         .then((data)=>{
              app.cursos= []
              console.log(data)

               data.forEach(element => {

                   app.cursos.push({
                       id: parseInt(element.id_curso),
                       nombre: element.nombre_curso,
                       idCat: element.id_categoria,
                       categoría: element.descripcion,
                       proveedor: element.nombre_proveedor,
                       estado: this.buscarEstado(element.estado_curso)
                      }
                      )
                 })
           })

        },

        buscarEstado(estado){
          if(estado==="B") return "Borrador"
          if(estado==="P") return "Publicado"
        },
        
        editarCurso(curso){
          console.log(curso.id)
          sessionStorage.idCurso = curso.id
          window.location.href = "newCourse.html";

        },

        previsualizarCurso(curso){
          sessionStorage.idCurso = curso.id
          window.location.href = "curso.html";
        },



        eliminarCurso(curso){
          fetch("../ApiRes/cursos.php?id_curso=" + curso.id + "&usuario=" + sessionStorage.loggedUser, {
            method: "DELETE"
            })
            .then(() => {
              console.log("curso eliminado")
            })


            window.location.href = "admCursos.html";

        },


        "sortTablecurso": function sortTablecurso(col) {
          if (this.sortColumn_curso === col) {
            this.ascending = !this.ascending;
          } else {
            this.ascending = true;
            this.sortColumn_curso = col;
          }
    
          var ascending = this.ascending;
    
          this.cursos.sort(function(a, b) {
            if (a[col] > b[col]) {
              return ascending ? 1 : -1
            } else if (a[col] < b[col]) {
              return ascending ? -1 : 1
            }
            return 0;
          })
        },
        "num_pages_curso": function num_pages_curso() {
            return Math.ceil(this.cursos.length / this.elementsPerPage_curso);
          },
        "get_rows_curso": function get_rows_curso() {
            return this.cursos.slice((this.currentPage_curso-1) * this.elementsPerPage_curso, (this.currentPage_curso-1) * this.elementsPerPage_curso + this.elementsPerPage_curso);
          },
        "change_page_curso": function change_page_curso(page_curso) {
            this.currentPage_curso = page_curso;
          }
      },


    computed: {
        "columns_curso": function columns() {
          if (this.cursos.length == 0) {
            return [];
          }
          return Object.keys(this.cursos[0])
        },
      },


      mounted: function(){
        sessionStorage.removeItem("idCurso");
        sessionStorage.logged = false
        if(sessionStorage.loggedUser==null){
            this.logged = false
        }else{
            if(sessionStorage.typeUser=="ADMINISTRADOR"){
                sessionStorage.logged=true
                this.logged = true
                this.buscarCursos()

            }else{
              this.logged = false
            }
        }
        window.addEventListener("load",function (){
          const loader = document.querySelector(".loader");
          loader.className += " hidden";
        })
  


    }
  })



  
  
