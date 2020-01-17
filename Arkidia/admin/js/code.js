var app = new Vue({
    el: '#app',
    data: {
        logged:false,
        ascending: false,
        sortColumn_curso: '',
        currentPage_curso: 1,
        elementsPerPage_curso: 10,
        sortColumn_hijo: '',
        currentPage_hijo: 1,
        elementsPerPage_hijo: 5,

    cursos: [
        { Curso: "Dibujar un perro", Categoría: "Dibujo", Likes: 5, Comentarios: 4, Preview: 3, Empezado:3, Terminado:4, Proveedor:"ArkidiaContent" },
        { Curso: "Dibujar un gato", Categoría: "Dibujo", Likes: 4, Comentarios: 3, Preview: 3, Empezado:3, Terminado:4, Proveedor:"ArkidiaContent" },
        { Curso: "Dibujar un elefante", Categoría: "Dibujo", Likes: 1, Comentarios: 4, Preview: 3, Empezado:3, Terminado:4, Proveedor:"ArkidiaContent" },
        { Curso: "Dibujar un antílope", Categoría: "Dibujo", Likes: 4, Comentarios: 3, Preview: 3, Empezado:3, Terminado:4, Proveedor:"ArkidiaContent" },
        { Curso: "Dibujar una suricata", Categoría: "Dibujo", Likes: 5, Comentarios: 4, Preview: 3, Empezado:3, Terminado:4, Proveedor:"ArkidiaContent" },
      ],


      hijos: [
        { Usuario:"Pol112", Padre:"PadrePol", Edad: 5, Curso:"Dibujar un elefante", Categoría:"Dibujo", Avance: "60%" },

      ]
    },

    methods: {
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
          },




          "sortTablehijo": function sortTablehijo(col) {
            if (this.sortColumn_hijo === col) {
              this.ascending = !this.ascending;
            } else {
              this.ascending = true;
              this.sortColumn_hijo = col;
            }
      
            var ascending = this.ascending;
      
            this.hijos.sort(function(a, b) {
              if (a[col] > b[col]) {
                return ascending ? 1 : -1
              } else if (a[col] < b[col]) {
                return ascending ? -1 : 1
              }
              return 0;
            })
          },
          "num_pages_hijo": function num_pages_hijo() {
              return Math.ceil(this.hijos.length / this.elementsPerPage_hijo);
            },
          "get_rows_hijo": function get_rows_hijo() {
              return this.hijos.slice((this.currentPage_hijo-1) * this.elementsPerPage_hijo, (this.currentPage_hijo-1) * this.elementsPerPage_curso + this.elementsPerPage_curso);
            },
          "change_page_hijo": function change_page_hijo(page_hijo) {
              this.currentPage_hijo = page_hijo;
            }
      },


    computed: {
        "columns_curso": function columns() {
          if (this.cursos.length == 0) {
            return [];
          }
          return Object.keys(this.cursos[0])
        },
        "columns_hijo": function columns() {
            if (this.hijos.length == 0) {
              return [];
            }
            return Object.keys(this.hijos[0])
          }
      },

      mounted: function(){
        console.log(sessionStorage.typeUser)
        console.log(sessionStorage.loggedUser)
        console.log(sessionStorage.loggedName)
        sessionStorage.logged = false
        if(sessionStorage.loggedUser==null){
            this.logged = false
        }else{
            if(sessionStorage.typeUser=="ADMINISTRADOR"){
                sessionStorage.logged=true
                this.logged = true
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



  
  
