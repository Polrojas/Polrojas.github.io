var app = new Vue({
    el: '#app',
    data: {
        deleteHijo: '',
        hijos: [],
        usuarioPadre: "",
        logged:false,
        avatarSeleccionado: "",
        nuevoHijo: {
            usuario: "",
            alias: "",
            usuario_padre: "",
            fecha: "",
            password: "",
            avatar: "",
            edad:null
        },
        alterHijo: {
            usuario: "",
            password: "",
            alias: "",
            fecha:"",
            avatar:""
        },
        registerError:false,
        registerMsg:"",

        avatars: [{ imagen: "images/arkidians/ark1.svg", selected: false },
        { imagen: "images/arkidians/ark2.svg", selected: false },
        { imagen: "images/arkidians/ark3.svg", selected: false },
        { imagen: "images/arkidians/ark4.svg", selected: false },
        { imagen: "images/arkidians/ark5.svg", selected: false },
        { imagen: "images/arkidians/ark6.svg", selected: false }
        ]


    },
    methods: {
        buscarHijos(usuarioPadre) {
            fetch("ApiRes/hijos.php?usuario_padre=" + usuarioPadre)
                .then(response => response.json())
                .then((data) => {
                    console.log(data)

                    data.forEach(element => {
                        app.hijos.push({
                            usuario: element.usuario,
                            alias: element.alias,
                            password: element.password,
                            fecha: element.fecha_nacimiento,
                            avatar: element.avatar,
                            edad: element.edad
                        })
                    })
                })
        },
        eliminarHijo(hijo) {
            fetch("ApiRes/hijos.php?usuario=" + hijo.usuario, {
                method: "DELETE"
            })
                .then(() => {
                    this.hijos = []
                    this.buscarHijos(this.usuarioPadre)
                })
        },
        crearHijo(hijo) {
            bodyApi = "usuario=" + hijo.usuario + "&alias=" + hijo.alias + "&usuario_padre=" + hijo.usuario_padre + "&fecha=" + hijo.fecha + "&password=" + hijo.password + "&avatar=" + hijo.avatar
            console.log(bodyApi)
            fetch("ApiRes/hijos.php", {
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
                            window.location.href = "hijos.html";
                        }
                        

                    })
                } else {
                    throw "Error en la llamada Ajax"
                }
             })
        },
        modificarHijo(hijo) {
            bodyApi = "usuario=" + hijo.usuario + "&alias=" + hijo.alias + "&fecha_nacimiento=" + hijo.fecha + "&password=" + hijo.password + "&avatar=" + hijo.avatar,
                fetch("ApiRes/hijos.php?"+bodyApi, {
                method: 'PUT',
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'
                })
            })
                .then(() => {
                    console.log(hijo)
                    this.hijos = []
                    this.buscarHijos(this.usuarioPadre)
                })
                .catch(() => {
                    console.log("error")
                })
        },
        selectAvatar(seleccion) {
            nuevoHijo.avatar = seleccion
        }



    },
    mounted: function () {

        if(sessionStorage.loggedUser===null){
            console.log("No hay usuario")
            this.logged=false
        }else{
            this.usuarioPadre = sessionStorage.loggedUser
            this.logged=true
            this.buscarHijos(this.usuarioPadre)
        }
        window.addEventListener("load",function (){
            const loader = document.querySelector(".loader");
            loader.className += " hidden";
          })
        


    }
})

