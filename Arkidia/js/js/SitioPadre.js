var app = new Vue({
    el: '#app',
    data: {
      deleteHijo:'',
      apiCategorias:[],
      hijos:[],
      logged:false,
      usuarioPadre:"",

    resumenHijos:[]

    },
    methods:{
        buscarHijos(usuarioPadre) {
            fetch("ApiRes/hijos.php?usuario_padre=" + usuarioPadre)
                .then(response => response.json())
                .then((data) => {
                    data.forEach(element => {
                        app.resumenHijos.push({
                            nickname: element.alias,
                            edad: element.edad,
                            avatar: element.avatar,
                            badges:[{nombre:"pintura",nivel:"5"},{nombre:"cocina",nivel:"2"}],
                            ultCursos:[{nombre:"Cocina una chocotorta",porcentaje:"30%"},{nombre:"Como dibujar un dinosaurio",porcentaje:"100%"}]
                
                        })
                    })
                })
        },
        buscarCategorias(){
            var requestCategorias = new XMLHttpRequest()
            requestCategorias.open('GET', 'ApiRes/categorias.php', true)
            requestCategorias.onload = function() {
                var data = JSON.parse(this.response)

                if (requestCategorias.status >= 200 && requestCategorias.status < 400) {
                    console.log(data)
                    data.forEach(element => {
                        app.apiCategorias.push({
                            id: element.id_categoria,
                            nombre: element.descripcion, 
                            imagen: element.imagen_categoria, 
                            styleObject:{ backgroundColor: element.color}}
                            )
                      })
                  } else {
                    console.log('error')
                  }
            }
            requestCategorias.send()
        },
        logOut(){
            sessionStorage.removeItem("typeUser");
            sessionStorage.removeItem("loggedUser");
        }
    },
    mounted: function(){

        if(sessionStorage.loggedUser==null){
            console.log("No hay usuario")
            this.logged=false
        }else{
            console.log("Hay usuario")

            this.usuarioPadre = sessionStorage.loggedUser
            this.logged=true
            this.buscarCategorias()
            this.buscarHijos(this.usuarioPadre)
        }


    }
  })

