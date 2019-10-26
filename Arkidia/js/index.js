var app = new Vue({
    el: '#app',
    data: {
      deleteHijo:'',
      apiCategorias:[],
      cat:[],
      login:{usuario:"",password:""},
      register:{nombre:"",apellido:"",correo:"",password:"",confirm:""},
      loginError: false,
      registerError:false,
      registerMsg:""

    },
    methods:{
        hacerLogin(login){
            fetch("ApiRes/login.php",{
                method: 'POST',
                body: "usuario="+login.usuario+"&password="+login.password,
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'})
            })
            .then(function(response) {
                if(response.ok) {
                    loginResponse = response.json()
                    loginResponse.then(function(result) {


                        if (result.page==="PADRE"){
                            window.location.href = "SitioPadre.html";
                            sessionStorage.loggedUser = login.usuario
                            sessionStorage.loggedName = result.user
                            sessionStorage.typeUser = result.page
                        }
                        if (result.page==="HIJO"){
                            window.location.href = "SitioHijo.html";
                            sessionStorage.loggedUser = login.usuario
                            sessionStorage.loggedName = result.user
                            sessionStorage.typeUser = result.page
                        }
                        if (result.page==="ADMINISTRADOR"){
                            window.location.href = "admin/index.html";
                        }
                        if (result.page==="NINGUNA"){
                            app.loginError = true;
                            sessionStorage.removeItem("typeUser");
                            sessionStorage.removeItem("loggedUser");
                        }
                        
                    })
                } else {
                    throw "Error en la llamada Ajax"
                }
             })
        },
        hacerRegistro(register){
            fetch("ApiRes/registracion.php",{
                method: 'POST',
                body: "nombre="+register.nombre+"&apellido="+register.apellido+"&mail="+register.correo+"&password="+register.password+"&confirmacion="+register.confirm,
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'})
            })
            .then(function(response) {
                app.registerError=false
                if(response.ok) {
                    app.registerError=false

                    loginResponse = response.json()
                    loginResponse.then(function(result) {
                        if (result.resultado==="ERROR"){
                            app.registerError=true
                            app.registerMsg = result.mensaje
                        }else{
                        window.location.href = "SitioPadre.html";
                        sessionStorage.loggedUser = register.correo
                        sessionStorage.loggedName = register.nombre
                        sessionStorage.typeUser = "PADRE"
                    }
                    })
                } else {
                    throw "Error en la llamada Ajax"
                }
             })
        },
        buscarCategorias(){
            fetch("ApiRes/categorias.php")
           .then(response => response.json() )
          .then((data)=>{
                data.forEach(element => {
                    app.apiCategorias.push({
                        id: element.id_categoria,
                        nombre: element.descripcion, 
                        imagen: element.imagen_categoria, 
                        styleObject:{ backgroundColor: element.color}}
                       )
                  })
            })
        },
    },
    mounted: function(){
        this.buscarCategorias()

    }
  })

