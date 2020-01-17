var app = new Vue({
    el: '#app',
    data: {
        logged:false,
        ascending: false,
        sortColumn_puntaje: '',
        currentPage_puntaje: 1,
        elementsPerPage_puntaje: 50,
        categorias:[],
        puntajes: [],
        puntajeDelete: null,
        alterPuntaje:{
          evento:"",
          puntaje: 0,
        }

    },

    methods: {

        buscarPuntajes(){
          fetch("../ApiRes/puntaje.php?usuario=" + sessionStorage.loggedUser)
          .then(response => response.json() )
         .then((data)=>{
              app.puntajes= data
           })

        },
        modificarPuntaje(puntaje) {
          bodyApi = "usuario=" + sessionStorage.loggedUser + "&evento=" + puntaje.evento + "&puntaje=" + puntaje.puntaje,
              fetch("../ApiRes/puntaje.php?"+bodyApi, {
              method: 'PUT',
              headers: new Headers({
                  'Content-Type': 'application/x-www-form-urlencoded'
              })
          })
              .then(() => {
                  console.log(puntaje)
              })
              .catch(() => {
                  console.log("error")
              })
      },

        "sortTablepuntaje": function sortTablepuntaje(col) {
          if (this.sortColumn_puntaje === col) {
            this.ascending = !this.ascending;
          } else {
            this.ascending = true;
            this.sortColumn_puntaje = col;
          }
    
          var ascending = this.ascending;
    
          this.puntajes.sort(function(a, b) {
            if (a[col] > b[col]) {
              return ascending ? 1 : -1
            } else if (a[col] < b[col]) {
              return ascending ? -1 : 1
            }
            return 0;
          })
        },
        "num_pages_puntaje": function num_pages_puntaje() {
            return Math.ceil(this.puntajes.length / this.elementsPerPage_puntaje);
          },
        "get_rows_puntaje": function get_rows_puntaje() {
            return this.puntajes.slice((this.currentPage_puntaje-1) * this.elementsPerPage_puntaje, (this.currentPage_puntaje-1) * this.elementsPerPage_puntaje + this.elementsPerPage_puntaje);
          },
        "change_page_puntaje": function change_page_puntaje(page_puntaje) {
            this.currentPage_puntaje = page_puntaje;
          }
      },


    computed: {
        "columns_puntaje": function columns() {
          if (this.puntajes.length == 0) {
            return [];
          }
          return Object.keys(this.puntajes[0])
        },
      },


      mounted: function(){
        sessionStorage.logged = false
        if(sessionStorage.loggedUser==null){
            this.logged = false
        }else{
            if(sessionStorage.typeUser=="ADMINISTRADOR"){
                sessionStorage.logged=true
                this.logged = true
                this.buscarPuntajes()

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



  
  
