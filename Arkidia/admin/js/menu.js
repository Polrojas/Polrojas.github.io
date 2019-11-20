var menu = new Vue({
    el: '#menu',
    data: {
      logged:false,
      opciones:[
        {
          link:"admCursos.html",
          etiqueta: "Administración de Cursos"
        },
        {
          link:"parametros.html",
          etiqueta: "Parámetros"
        },

      ],
    },

    methods: {
      logOut(){
        console.log("LOGGING OUT")
        sessionStorage.removeItem("typeUser");
        sessionStorage.removeItem("loggedUser");
        sessionStorage.removeItem("loggedName")
    }

      },

    computed: {

      },

      mounted: function(){

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
    }

  })



  
  
