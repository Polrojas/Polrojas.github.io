var app = new Vue({
    el: '#app',
    data: {
      logged:false,
      ascending: false,
      sortColumn_nivel: '',
      currentPage_nivel: 1,
      elementsPerPage_nivel: 50,
      categorias:[],
      niveles: [],
      nivelDelete: null,
      nuevoNivel:{
          nombre_nivel:"",
          puntaje_maximo:""

      },
      alterNivel:{
        nombre_nivel:"",
        puntaje_maximo: 0,
      },
      nivelDelete:{
          nombre_nivel:"",
          puntaje_maximo:0,

      }
    },

    methods: {


        crearNivel(nivel){
            bodyApi = "usuario=" + sessionStorage.loggedUser + "&nombre_nivel=" + nivel.nombre_nivel + "&puntaje_maximo=" + nivel.puntaje_maximo 
            console.log(bodyApi)
            fetch("../ApiRes/niveles.php", {
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
                            app.registerError=true
                            app.registerMsg = result.mensaje
                            console.log(result.mensaje)
                        }else{
                            app.niveles = []
                            fetch("../ApiRes/niveles.php?usuario=" + sessionStorage.loggedUser)
                            .then(response => response.json() )
                           .then((data)=>{
                               console.log(data)
                                app.niveles= data
                             })
                        }
                        

                    })
                } else {
                    throw "Error en la llamada Ajax"
                }
             })

        },
        eliminarNivel(nivel){
            fetch("../ApiRes/niveles.php?nombre_nivel=" + nivel.nombre_nivel + "&usuario=" + sessionStorage.loggedUser, {
              method: "DELETE"
              })
              .then(() => {
                app.niveles = []
                fetch("../ApiRes/niveles.php?usuario=" + sessionStorage.loggedUser)
                .then(response => response.json() )
               .then((data)=>{
                   console.log(data)
                    app.niveles= data
                 })
              })
  
  
          },
        buscarNiveles(){
            fetch("../ApiRes/niveles.php?usuario=" + sessionStorage.loggedUser)
            .then(response => response.json() )
           .then((data)=>{
               console.log(data)
                app.niveles= data
             })
  
          },
        modificarNivel(nivel) {
          bodyApi = "usuario=" + sessionStorage.loggedUser + "&nombre_nivel=" + nivel.nombre_nivel + "&puntaje_maximo=" + nivel.puntaje_maximo,
              fetch("../ApiRes/niveles.php?"+bodyApi, {
              method: 'PUT',
              headers: new Headers({
                  'Content-Type': 'application/x-www-form-urlencoded'
              })
          })
              .then(() => {
                  console.log(nivel)
              })
              .catch(() => {
                  console.log("error")
              })
      },

        "sortTablenivel": function sortTablepuntaje(col) {
          if (this.sortColumn_nivel === col) {
            this.ascending = !this.ascending;
          } else {
            this.ascending = true;
            this.sortColumn_puntaje = col;
          }
    
          var ascending = this.ascending;
    
          this.nivel.sort(function(a, b) {
            if (a[col] > b[col]) {
              return ascending ? 1 : -1
            } else if (a[col] < b[col]) {
              return ascending ? -1 : 1
            }
            return 0;
          })
        },
        "num_pages_niveles": function num_pages_puntaje() {
            return Math.ceil(this.niveles.length / this.elementsPerPage_nivel);
          },
        "get_rows_nivel": function get_rows_puntaje() {
            return this.niveles.slice((this.currentPage_nivel-1) * this.elementsPerPage_nivel, (this.currentPage_nivel-1) * this.elementsPerPage_nivel + this.elementsPerPage_nivel);
          },
        "change_page_nivel": function change_page_puntaje(page_puntaje) {
            this.currentPage_nivel = page_nivel;
          }
      },


    computed: {
        "columns_nivel": function columns() {
          if (this.niveles.length == 0) {
            return [];
          }
          return Object.keys(this.niveles[0])
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
                this.buscarNiveles()

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



  
  
