Vue.component('restringido',{
    template:`
    <div class="container">
    <div class="row">
        <div class="col-sm-6">
            <img src="images/site/stop.svg" style="width:80%" alt="STOP"/>
        </div>
        <div class="col-sm-6" style="align-self: center">
            <h1 class="titulo">Acceso restringido a Administradores</h1>
            <p>Para acceder este contenido necesitás <a class="link" href="index.html">iniciar sesión</a></p>  
        </div>
    </div>
  </div>
    `
    ,
    data() {
        return{
        }
    },
    props:{
  
    },
    methods:{
    },
    computed:{  
    }
  })
  
  
  Vue.component("mensajes", {
    template: `
<div style="margin:50px">
<ul class="commentList">

    <h5 v-if="mensajes.length<=0">No hay ningún mensaje a revisar</h5>

    <li v-if="mensajes.length>0" v-for="(comentario,index) in mensajes">
    <div class="commenterImage">
        <img :src="comentario.avatar" />
    </div>
    <div class="commentText">
        <p><b>{{comentario.alias}} </b> dijo:</p>
        <p class="">{{comentario.comentario}}</p> <span class="date sub-text">{{comentario.fechahora}}</span>
        <div>
        <button @click="aprobarComentario(comentario,index)" class="btn btn-default">Aprobar</button>
        <button @click="rechazarComentario(comentario,index)" class="btn btn-default">Rechazar</button>
        </div>


    </div>

    <hr>
    </li>
    </ul>
    </div>
  
    `,
    data() {
      return {
            mensajes:[],
          idCategoria: null,
          logged:false,
          registerError:false,
          registerMsg:null,
          categorias:[],
          newCategory: {
            nombre:null,
            imagen:null,
            color:null,
            link_video:null
          }
      };
    },
    props: {
  
    },
    methods: {
        buscarMensajes(){
            this.mensajes = []
            
            fetch("ApiRes/comentarios.php?usuario="+sessionStorage.loggedUser+"&estado=B")
            .then(response => response.json())
            .then(data => {

                this.mensajes = data
                console.log(data)
    
            });

          },
          
          aprobarComentario(comentario,id){
            bodyApi = "usuario=" + sessionStorage.loggedUser + 
                    "&id_challenge=" + comentario.id_challenge + 
                    "&usuario_challenge=" + comentario.usuario_challenge + 
                    "&secuencia=" + comentario.secuencia + 
                    "&estado=P",
            fetch("ApiRes/comentarios.php?"+bodyApi, {
            method: 'PUT',
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded'
            })
        })
            .then(response=> response.json())
            .then(data => {
              console.log(data)
              console.log(comentario)
              this.mensajes.splice(id,1)

            })
            .catch(() => {
                console.log("error")
            })

          },
          rechazarComentario(comentario,id){
            bodyApi = "usuario=" + sessionStorage.loggedUser + 
            "&id_challenge=" + comentario.id_challenge + 
            "&usuario_challenge=" + comentario.usuario_challenge + 
            "&secuencia=" + comentario.secuencia + 
            "&estado=R",
    fetch("ApiRes/comentarios.php?"+bodyApi, {
    method: 'PUT',
    headers: new Headers({
        'Content-Type': 'application/x-www-form-urlencoded'
    })
})
    .then(() => {
      console.log(comentario)
      this.mensajes.splice(id,1)

    })
    .catch(() => {
        console.log("error")
    })
  }
    

    },
    computed: {
    },
    mounted:function(){
        this.buscarMensajes()
    }
  });

  Vue.component("desafios", {
    template: `
<div style="margin:50px">


    <div v-for="(desafio,index) in desafios">
    <div >
        <img :src="desafio.url_contenido" />
    </div>
    <div class="commentText">
        <a>usuario: {{desafio.usuario}}</a>
        <button @click="aprobarDesafio(desafio,index)" class="btn btn-default">Aprobar</button>
        <button @click="rechazarDesafio(desafio,index)" class="btn btn-default">Rechazar</button>
        <hr>
    </div>
    </div>

    </div>
  
    `,
    data() {
      return {
            desafios:[],
          idCategoria: null,
          logged:false,
          registerError:false,
          registerMsg:null,
          categorias:[],
          newCategory: {
            nombre:null,
            imagen:null,
            color:null,
            link_video:null
          }
      };
    },
    props: {
  
    },
    methods: {
        buscarDesafios(){
            this.desafios = []
            fetch("ApiRes/challenge_alumno.php?administrador="+sessionStorage.loggedUser)
            .then(response => response.json())
            .then(data => {
              this.desafios = data
              console.log(this.desafios)
    
            });

          },
          aprobarDesafio(desafio,id){
            bodyApi = "usuario=" + sessionStorage.loggedUser + 
            "&id_challenge=" + desafio.id_challenge + 
            "&usuario_challenge=" + desafio.usuario + 
            "&ind_aprobado=S",
    fetch("ApiRes/challenge_alumno.php?"+bodyApi, {
    method: 'PUT',
    headers: new Headers({
        'Content-Type': 'application/x-www-form-urlencoded'
    })
})
    .then(response=> response.json())
    .then(data => {
      console.log(data)
      this.desafios.splice(id,1)

    })
    .catch(() => {
        console.log("error")
    })





          },
          rechazarDesafio(desafio,id){
            bodyApi = "usuario=" + sessionStorage.loggedUser + 
            "&id_challenge=" + desafio.id_challenge + 
            "&usuario_challenge=" + desafio.usuario + 
            "&ind_aprobado=R",
    fetch("ApiRes/challenge_alumno.php?"+bodyApi, {
    method: 'PUT',
    headers: new Headers({
        'Content-Type': 'application/x-www-form-urlencoded'
    })
})
    .then(response=> response.json())
    .then(data => {
      if(data.resultado == "ERROR"){
        console.log(data.mensaje)
        return
      }
      console.log(data)
      this.desafios.splice(id,1)

    })
    .catch(() => {
        console.log("error")
    })
}
    

    },
    computed: {
    },
    mounted:function(){
        this.buscarDesafios()
    }
  });
  
  var app = new Vue({
    el: "#app",
    data: {
        mensajes : false,
        desafios : false,
        admin : false,
        hijo : false,
        padre : false

    },
  
    methods: {
        verMensajes(){
            this.mensajes = true
            this.desafios = false
        },
        verDesafios(){
            this.mensajes = false
            this.desafios = true
        },

    },
  
    computed: {},
  
    mounted: function() {
      this.padre = false;
      this.admin = false;
      this.hijo = false;
      this.logged = false;
      this.mensajes = true;
      this.desafios = false;
  
      if (sessionStorage.loggedUser > "") {
        this.logged = true;
        this.nombre = sessionStorage.loggedName;
        if (sessionStorage.typeUser == "ADMINISTRADOR") {
          this.admin = true;
        }
        if (sessionStorage.typeUser == "PADRE") this.padre = true;
        if (sessionStorage.typeUser == "HIJO") this.hijo = true;
      }
      window.addEventListener("load",function (){
        const loader = document.querySelector(".loader");
        loader.className += " hidden";
      })
    }
  });
  