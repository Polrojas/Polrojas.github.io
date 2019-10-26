var app = new Vue({
    el: '#app',
    data: {
        ascending: false,
        sortColumn_curso: '',
        currentPage_curso: 1,
        elementsPerPage_curso: 10,


    cursos: [
        { IDCurso: "1122112A", Curso: "Dibujar un perro", Categoría: "Dibujo", Proveedor:"ArkidiaContent", Status: "Borrador" },
        { IDCurso: "1122112A", Curso: "Dibujar un perro", Categoría: "Dibujo", Proveedor:"ArkidiaContent", Status: "Activo" },
        { IDCurso: "1122112A", Curso: "Dibujar un perro", Categoría: "Dibujo", Proveedor:"ArkidiaContent", Status: "Pausado" },
        { IDCurso: "1122112A", Curso: "Dibujar un perro", Categoría: "Dibujo", Proveedor:"ArkidiaContent", Status: "Activo" },
        { IDCurso: "1122112A", Curso: "Dibujar un perro", Categoría: "Dibujo", Proveedor:"ArkidiaContent", Status: "Activo" },
      ],


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
  })



  
  
