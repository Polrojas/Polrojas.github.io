var app = new Vue({
    el: '#app',
    data: {
      deleteHijo:'',
      apiCategorias:[],
      hijos:[],
      logged:false,
      usuarioHijo:"",
      saludo:"",
      

    resumenHijos:[]

    },
    methods:{

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
        accederCategoria(idCategoria){
            sessionStorage.idCategoria = idCategoria
            window.location.href = "categoria.html"
        },
        logOut(){
            sessionStorage.removeItem("typeUser");
            sessionStorage.removeItem("loggedUser");
        }
    },
    mounted: function(){
        var d = new Date()
        var n = d.getHours()
        if(n>3 && n<13){
            this.saludo = "Buenos días"
        }
        if(n>13 && n<20){
            this.saludo = "Buenas tardes"
        }
        if(n>=20 && n<3){
            this.saludo = "Buenas noches"
        }

        if(sessionStorage.loggedUser==null){
            this.logged=false
        }else{
            if(sessionStorage.typeUser=="HIJO"){
                this.usuarioHijo = sessionStorage.loggedName
                this.logged=true
                this.buscarCategorias()
            }else{
                this.logged=false
            }

        }
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })


    }
  })

