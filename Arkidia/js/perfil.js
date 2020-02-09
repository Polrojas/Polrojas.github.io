Vue.component('challenge',{
    template:`

    

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
          perfil:"",
          porcentaje: "10,100"

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
      buscarPerfil() {
          fetch(
            "ApiRes/perfil_usuario.php?id_usuario=" +
            sessionStorage.profileUser
          )
            .then(response => response.json())
            .then(data => {
                this.perfil = data
                console.log(data)

  
            });
        },



    
    },
    computed:{  
    },
    mounted: function(){
      //this.buscarPerfil();

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
          porcentaje: "10,100",
          perfil:"",

          logged:false,
          items: [],
  
          curso: {
            id: null,
            nombre: null,
            detalle: null,
            categoria: null,
            ind_completo: null
          }
  
      },
      methods:{
        buscarPerfil() {
            fetch(
              "ApiRes/perfil_usuario.php?usuario=" +
              sessionStorage.profileUser
            )
              .then(response => response.json())
              .then(data => {
                  this.perfil = data
                  this.porcentaje = ((this.perfil.puntos /this.perfil.puntos_nivel)*100)+",100"
                  console.log(data)
  
    
              });
          },
          
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
          this.buscarPerfil()
          window.addEventListener("load",function (){
              const loader = document.querySelector(".loader");
              loader.className += " hidden";
            })
  
  
      }
    })
  
  