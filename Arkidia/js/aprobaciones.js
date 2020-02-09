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
<ul class="commentList">

    <li v-for="(comentario,index) in mensajes">
    <div class="commenterImage">
        <img :src="comentario.avatar" />
    </div>
    <div class="commentText">
        <p><b>{{comentario.alias}} </b> dijo:</p>
        <p class="">{{comentario.comentario}}</p> <span class="date sub-text">{{comentario.fechahora}}</span>
        <button @click="aprobarDesafio(desafio,index)" class="btn btn-default">Aprobar</button>
        <button @click="rechazarDesafio(desafio,index)" class="btn btn-default">Rechazar</button>


    </div>
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
            
            //fetch("ApiRes/comentarios.php?usuario="+sessionStorage.loggedUser)
            fetch("ApiRes/comentarios.php?id_challenge=17&usuario_challenge=Polito12&usuario=Polito12")
            .then(response => response.json())
            .then(data => {
              this.mensajes = [];
              this.mensajes = data
    
            });

          },
          aprobarComentario(comentario,id){
              this.mensajes.splice(id,1)

          },
          rechazarComentario(comentario,id){
            this.mensajes.splice(id,1)          }
    

    },
    computed: {
    },
    mounted:function(){
        this.buscarMensajes()
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
  