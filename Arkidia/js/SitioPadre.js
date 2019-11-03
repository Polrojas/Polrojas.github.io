var app = new Vue({
    el: '#app',
    data: {
      deleteHijo:'',
      apiCategorias:[],
      hijos:[],
      logged:false,
      usuarioPadre:"",
      usuarioPadreNombre:"",
      saludo:"",

    resumenHijos:[]

    },
    methods:{
        buscarHijos(usuarioPadre) {
            console.log(usuarioPadre)

            fetch("ApiRes/hijos.php?usuario_padre=" + usuarioPadre)
                .then(response => response.json())
                .then((data) => {
                    data.forEach(element => {
                        console.log(data)

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
            requestCategorias.open('GET', 'ApiRes/categorias.php?usuario='+sessionStorage.loggedUser, true)
            requestCategorias.onload = function() {
                var data = JSON.parse(this.response)
                if (requestCategorias.status >= 200 && requestCategorias.status < 400) {
                    data.forEach(element => {
                        if(element.estado ==="P"){
                            app.apiCategorias.push({
                                id: element.id_categoria,
                                nombre: element.descripcion, 
                                imagen: element.imagen_categoria, 
                                styleObject:{ backgroundColor: element.color}}
                            )
                        }
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
        var d = new Date()
        var n = d.getHours()
        console.log(n)
        if(n>=3 && n<13){
            this.saludo = "Buenos días"
        }
        if(n>=13 && n<20){
            this.saludo = "Buenas tardes"
        }
        if(n>=20 && n <3){
            this.saludo = "Buenas noches"
        }
        console.log(sessionStorage.loggedName)
        if(sessionStorage.loggedUser==null){
            this.logged=false
        }else{
            console.log(sessionStorage.typeUser)
            if(sessionStorage.typeUser=="PADRE"){
                this.usuarioPadre = sessionStorage.loggedUser
                this.usuarioPadreNombre = sessionStorage.loggedName
                this.logged=true
                this.buscarCategorias()
                this.buscarHijos(this.usuarioPadre)
            }else{
                this.logged=false
            }
        }









    }
  })

