
var app = new Vue({
    el: '#app',
    data: {
        proveedores: [{
            id:1,
            nombre:"Proov1"
            },{
            id:2,
            nombre:"Proov2"
            },   {
            id:3,
            nombre:"Proov3"
            },   {
            id:4,
            nombre:"Proov4"
            },         
    ],
        proveedor:"",
        categorias: [],
        categoria:"",

        contenido:{
            nombre:"",
            orden: 1,
            URLContenido:""
        },
        challenge:{
            nombre:"",
            orden: 1,
            explicacion:""
        },
        mostrarContenido:false,
        mostrarChallenge:false,
        contenidos:[],
        challenges:[],
        curso:{
            nombre:"",
            categoria:"",
            nombreCategoria:"",
            proveedor:"",
        }


    },
    methods: {
        buscarCategorias(){
            fetch("../ApiRes/categorias.php")
           .then(response => response.json() )
          .then((data)=>{
                data.forEach(element => {
                    app.categorias.push({
                        id: element.id_categoria,
                        nombre: element.descripcion}
                       )
                  })
            })

        },
        buscarProveedores(){
            console.log("buscando proveedores")
        },
        grabarCurso(){
            app.mostrarContenido=true
            app.mostrarChallenge=true
        },
        insertarContenido(contenido){
            app.contenidos.push({
                nombre:contenido.nombre,
                orden:contenido.orden,
                URLContenido:contenido.URLContenido
            })
            app.contenido.nombre = ""
            app.contenido.URLContenido = ""
            app.contenido.orden += 1
        },
        "get_rows_contenido": function get_rows_contenido() {
            return this.contenidos;
          },
        quitarContenido(orden){
            for( var i = 0; i < app.contenidos.length; i++){ 
                if ( app.contenidos[i].orden === orden) {
                  app.contenidos.splice(i, 1); 
                }
             }
        },

        insertarChallenge(challenge){
            app.challenges.push({
                nombre:challenge.nombre,
                orden:challenge.orden,
                explicacion:challenge.explicacion
            })
            app.challenge.nombre = ""
            app.challenge.orden += 1
            app.challenge.explicacion = ""
        },
        "get_rows_challenge": function get_rows_challenge() {
            return this.challenges;
          },
        quitarContenido(orden){
            for( var i = 0; i < app.challenges.length; i++){ 
                if ( app.challenges[i].orden === orden) {
                  app.challenges.splice(i, 1); 
                }
             }
        },

      },


    computed: {
        "columns_contenido": function columns() {
            if (this.contenidos.length == 0) {
              return [];
            }
            return Object.keys(this.contenidos[0])
          },
          "columns_challenge": function columns() {
            if (this.challenges.length == 0) {
              return [];
            }
            return Object.keys(this.challenges[0])
          },
      },
      mounted: function(){
        this.buscarCategorias()


      }

    
      
  })



  
  
