Vue.component('challenge',{
    template:`
    <div>
    <div class="card" style="border:0px; margin:5%">
    <h1 class="titulo">{{desafio.detalle_challenge}}</h1>
    <h5 class="titulo" style="font-size:15px">del curso "{{desafio.nombre_curso}}"</h5>

    <div class="row no-gutters">
      <div class="col-md-3" style="max-width:100px">
        <img :src="desafio.avatar" style="background-color:grey; border-radius:50%" class="img img-rounded img-fluid"/>
      </div>
      <div class="col-md-10">
        <div class="card-body">
        <h5 @click="verPerfil(desafio.usuario)" style="cursor:pointer">{{desafio.alias}}</h5>
          <p class="card-text"><small class="text-muted">{{desafio.fechahora}}</small></p>
        </div>
      </div>
    </div>
  </div>

    <img :src="desafio.url_challenge" style="width:90%; margin-left:5%; border-radius:15px" >

<div style="width:90%; margin-left:5%; margin-top:20px">
  <img v-if="desafio.ind_like==0" src="images/site/likes.svg" @click="darLike(desafio)" style="width:25px;" alt="curso.nombre">
  <img v-if="desafio.ind_like==1" src="images/site/liked.svg" @click="quitarLike(desafio)" style="width:25px;" alt="curso.nombre">
  <span>{{desafio.total_likes}}</span>
  <img src="images/site/comments.svg" style="width:25px;" alt="curso.nombre">
  <span>{{desafio.total_comentarios}}</span>
  </div>
</div>
    `
    ,
    data() {
        return{
          contenidos: [],
          curso:{
            id: null,
            nombre: null,
            detalle: null,
            categoria: null,
            ind_completo: null
          },
          categoria:"",
          tituloStyle:"",
          desafio:"",
        }
    },
    props:{
      estilos:{
      categoriaStyle:"",
      categoriaStyleBar:"",
      color:"",
      categoria:"",
    }
    },
    methods:{
      buscarChallenge() {
          fetch(
            "ApiRes/challenge_alumno.php?usuario=" +
            sessionStorage.loggedUser
             +
              "&id_challenge=" +
              sessionStorage.idChallenge
              +
              "&usuario_challenge=" +
              sessionStorage.usuarioChallenge
          )
            .then(response => response.json())
            .then(data => {
                this.desafio = data
                console.log(data)

  
            });
        },
        buscarLikes() {
            fetch(
              "ApiRes/like_challenge.php?usuario_challenge=" +
              sessionStorage.usuarioChallenge
               +
                "&id_challenge=" +
                sessionStorage.idChallenge

            )
              .then(response => response.json())
              .then(data => {
                  console.log(data)
  
    
              });
          },
          verPerfil(usuario){
            sessionStorage.profileUser = usuario
            window.location.href = "perfil.html"
          },
          quitarLike(desafio){
            console.log("quitarLike")
            console.log(sessionStorage.loggedUser)
            console.log(desafio)
            fetch("ApiRes/like_challenge.php?" + "usuario_like="+sessionStorage.loggedUser+"&usuario_challenge="+desafio.usuario+"&id_challenge="+sessionStorage.idChallenge+"&secuencia=1", {
              method: "DELETE"
          })
              .then(function(response)  {
                console.log(response)
                desafio.ind_like = 0
                desafio.total_likes--
      
      
              })
      
          },
          darLike(desafio){
            fetch("ApiRes/like_challenge.php",{
              method: 'POST',
              body: "usuario_like="+sessionStorage.loggedUser+"&usuario_challenge="+desafio.usuario+"&id_challenge="+sessionStorage.idChallenge,
              headers: new Headers({
                  'Content-Type': 'application/x-www-form-urlencoded'})
          })
          .then(function(response) {
              if(response.ok) {
                  loginResponse = response.json()
                  loginResponse.then(function(result) {
                    console.log(result)
                    if(result.resultado == "OK"){
                      desafio.ind_like = 1
                      desafio.total_likes++
                    }
      
                  })
              } else {
                  throw "Error en la llamada Ajax"
              }
           })
      
          },


    
    },
    computed:{  
    },
    mounted: function(){
      this.buscarChallenge();
      this.buscarLikes();
    }
  })
  


  Vue.component('mensajes',{
    template:`
    <div class="detailBox">
    <div class="actionBox" >
        <ul v-if="comentarios.length>0" style="padding-left:0px" >
            <div v-for="(comentario,index) in comentarios" style="padding:15px">
                <div @click="verPerfil(comentario.usuario_comentario)" style="cursor:pointer" class="commenterImage">
                    <img :src="comentario.avatar" />
                </div>
                <div class="commentText">
                    <p ><b @click="verPerfil(comentario.usuario_comentario)" style="cursor:pointer">{{comentario.alias}}</b> dijo:</p>
                    <p style="width:70%;display:inline-table" class="">{{comentario.comentario}}</p> 
                    <img v-if="comentario.ind_comentario == 1" src="images/site/delete.svg" @click="eliminarComentario(comentario,index)" style="width:30px; vertical-align:super; cursor:pointer" />

                    <span style="display:block" class="date sub-text">{{comentario.fechahora}}</span>


                </div>
            </div>
        </ul>
        
            <div >
                <textarea v-model="newComment" v-on:keyup.enter="comentar()"  type="text" placeholder="Dejá un comentario" style="width:80%" />
                <img v-if="newComment==''" src="images/site/no-comment.svg" style="width:40px; vertical-align:super; cursor:no-drop" />
                <img v-if="newComment>''" @click="comentar()" src="images/site/send-comment.svg" style="width:40px; vertical-align:super; cursor:pointer" />

            </div>

      
        <div >
        </div>
    </div>
</div>
    `
    ,
    data() {
        return{
          newComment:"",
          avatar:null,
          comentarios:[{
            usuario_comentario: null,
            alias: null,
            avatar: null,
            fechahora: null,
            comentario: null,
            ind_comentario: null,
            secuencia: null
          }],

        }
    },
    props:{

    },
    methods:{
      verPerfil(usuario){
        sessionStorage.profileUser = usuario
        window.location.href = "perfil.html"

      },
      buscarHijo(usuario){
        fetch("ApiRes/hijos.php?usuario=" + usuario+"&accion=avatar")
        .then(response => response.json())
        .then((data) => {
          console.log(data)
            this.avatar = data.avatar
        })        
      },

      buscarComentarios() {

        fetch("ApiRes/comentarios.php?id_challenge=" + sessionStorage.idChallenge 
               + "&usuario_challenge=" + sessionStorage.usuarioChallenge
               + "&usuario=" + sessionStorage.loggedUser)
            .then(response => response.json())
            .then((data) => {
              console.log("buscar comentarios")
                this.comentarios = data
            })
    },
      eliminarComentario(comentario,index){

        fetch("ApiRes/comentarios.php?id_challenge="+  sessionStorage.idChallenge +
        "&usuario="+sessionStorage.loggedUser + 
        "&usuario_challenge=" + sessionStorage.usuarioChallenge +
        "&secuencia="+comentario.secuencia, {
          method: "DELETE"
      })
      .then(response => response.json())
      .then((data) => {
        this.comentarios.splice(index,1)

          console.log(data)

      })

      },
      comentar(){
        if(this.newComment>' '){
          console.log(this.newComment)
        fetch("ApiRes/comentarios.php",{
            method: 'POST',
            body: "usuario_challenge="+sessionStorage.usuarioChallenge
            +"&id_challenge="+sessionStorage.idChallenge
            +"&usuario_comentario="+sessionStorage.loggedUser
            +"&comentario="+this.newComment,
            
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded'})
        })
        .then(response => response.json())
        .then((data) => {  
            console.log(data)
            nuevoComentario = {
              usuario_comentario: sessionStorage.loggedUser,
              alias: sessionStorage.loggedName,
              avatar: this.avatar,
              fechahora: "recién",
              comentario: this.newComment,
              ind_comentario: "1",
              secuencia: data.comentario
            }

            this.comentarios.push(nuevoComentario)
            this.newComment=""
        })
      }else{
        this.newComment=""
      }
    },

    },
    computed:{  
    },
    mounted: function(){
      console.log("buscarComentarios")
      this.buscarComentarios() 
      this.buscarHijo(sessionStorage.loggedUser)

    }
  })

  var app = new Vue({
      el: '#app',
      data: {
          estilos:{
            categoriaStyle:"",
            categoriaStyleBar:"",
            color:"",
            categoria:"",
          },
          logged:false,
          items: [],
  
          curso: {
            id: null,
            nombre: null,
            detalle: null,
            categoria: null,
            ind_completo: null
          },

      },
      methods:{
          
      },
      mounted: function(){
        if(sessionStorage.loggedUser==null){
            this.logged=false
        }else{
            if(sessionStorage.typeUser=="HIJO"){
                this.usuarioHijo = sessionStorage.loggedName
                this.logged=true
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
  
  