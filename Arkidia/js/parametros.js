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
  
  
  Vue.component("categorias", {
    template: `
    <div>
    <section>
    <h1 class="titulo" style="margin-top:60px">Administración de Categorías</h1>

        <div class="container">
            <div class="row" style="justify-content: center">
                <div v-for="categoria in categorias" :key="categoria.nombre">
                    <div class="card categoria" :style="[categoria.styleObject]">
                        <a class="estado-borrador" v-if="categoria.estado==='B'">borrador</a>
                        <a class="estado-publicado" v-if="categoria.estado==='P'">publicado</a>

                    <img :src="categoria.url_imagen" width="200px" alt="Arkidia" />
                        <div class="card-body">
                            <p class="texto-car-categoria">{{ categoria.nombre }}</p>
                            <a href="#" @click="alterCategory = categoria" 
                            data-toggle="modal"
                            data-target="#modificarCategoria"
                            style="color:white" >Modificar</a> 
                            <a href="#" @click="idCategoria = categoria.id" 
                                data-toggle="modal"
                                data-target="#eliminarCategoria" 
                                style="color:white">Eliminar</a>
                        </div>
                    </div>

                </div>
                <div class="card categoria" data-target="#newCategory" data-toggle="modal" style="background-color: grey">
                        <img src="images/arkidians/nuevo.svg" width="200px" alt="Arkidia" />
                        <div class="card-body">
                            <a class="texto-car-categoria">Nueva categoría</a>
                        </div>
                    </div>
            </div>
        </div>
</section>



      <!-- Modal nueva categoria-->
    <div
      class="modal fade"
      id="newCategory"
      tabindex="-1"
      role="dialog"
      aria-labelledby="loginCenterTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body" style="padding:0px">


            <form style="margin: 20px">
                <h1>Nueva categoría</h1>
                    <h6 v-if="registerError" style="color: darkred">
                    {{registerMsg}}
                    </h6>
                <div class="form-group">
                    <label>Nombre de la categoría</label>
                    <input type="text" class="form-control" v-model="newCategory.nombre"  placeholder="Ingresá el nombre de la categoría">
                </div>
                <div class="form-group">
                    <label>Imagen</label>
                    <input type="text" class="form-control" v-model="newCategory.imagen"  placeholder="Ingresá el path de la imagen">
                </div>
                <div class="form-group">
                    <label>Color</label>
                    <input type="text" class="form-control" v-model="newCategory.color"  placeholder="Ingresá el color en hexadecimal">
                </div>
                <div class="form-group">
                    <label>Link al video de la categoría</label>
                    <input type="text" class="form-control" v-model="newCategory.link_video"  placeholder="Ingresá el path del video de la categoría">
                </div>

              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >Cerrar
              </button>
              <button
                type="button"
                class="btn btn-primary"
                data-dismiss="modal"

                @click="crearCategoria(newCategory)"
              >Crear categoría
              </button>
            </form>


          </div>
        </div>
      </div>
    </div>


      <!-- Modal modificar categoria-->
      <div
      class="modal fade"
      id="modificarCategoria"
      tabindex="-1"
      role="dialog"
      aria-labelledby="loginCenterTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">

            <form style="margin: 20px">
                <div class="form-group">
                    <label>Nombre de la categoría</label>
                    <input type="text" class="form-control" v-model="alterCategory.nombre"  placeholder="Ingresá el nombre de la categoría">
                </div>
                <div class="form-group">
                    <label>Imagen</label>
                    <input type="text" class="form-control" v-model="alterCategory.imagen"  placeholder="Ingresá el path de la imagen">
                </div>
                <div class="form-group">
                    <label>Color</label>
                    <input type="text" class="form-control" v-model="alterCategory.color"  placeholder="Ingresá el color en hexadecimal">
                </div>
                <div class="form-group">
                    <label>Link al video de la categoría</label>
                    <input type="text" class="form-control" v-model="alterCategory.link_video"  placeholder="Ingresá el path del video de la categoría">
                </div>


                <input type="radio" id="borrador" value="B" v-model="alterCategory.estado">
                <label for="borrador">Borrador</label>
                <br>
                <input type="radio" id="publicado" value="P" v-model="alterCategory.estado">
                <label for="publicado">Publicado</label>
                <br>




            </form>

            <div style="text-align: center">
                    <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >Cerrar
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="modificarCategoria(alterCategory)"
                    data-dismiss="modal"

                >Modificar categoría
                </button>
            </div>
          </div>
        </div>
      </div>
    </div>



<!-- Modal ELIMINAR Categoría -->
<div class="modal fade" id="eliminarCategoria" tabindex="-1" role="dialog" aria-labelledby="eliminarCategoriaCenterTitle"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
 <div class="modal-content">
   <div class="modal-header">
     <h5 class="modal-title" id="eliminarCategoriaCenterTitle">
       Eliminar categoría
     </h5>
     <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
       <span aria-hidden="true">&times;</span>
     </button>
   </div>
   <div class="modal-body">
     Estás seguro que querés eliminar la categoría?
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-secondary" data-dismiss="modal">
       Cerrar
     </button>
     <button type="button" class="btn btn-primary" data-dismiss="modal" @click="eliminarCategoria(idCategoria)">
       Eliminar categoría
     </button>
   </div>
 </div>
</div>
</div>
</div>
  
    `,
    data() {
      return {
        alterCategory: {
            id_categoria:null,
            imagen: null,
            nombre: null,
            color: null,
            link_video: null,
            estado:null
          },
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
        buscarCategorias(){
            this.categorias = []

            fetch("ApiRes/categorias.php?usuario=aplicacion")
            .then(response => response.json())
            .then(data => {
              this.cursos = [];
              console.log(data);
    
              data.forEach(element => {
                this.categorias.push({
                    id: element.id_categoria,
                    nombre: element.descripcion, 
                    url_imagen: element.imagen_categoria, 
                    imagen: element.imagen_categoria,
                    styleObject: { backgroundColor: element.color},
                    color: element.color.substr(1),
                    link_video: element.link_video,
                    estado: element.estado
                  })
              });
            });

          },
    
          eliminarCategoria(idCategoria) {
            fetch("ApiRes/categorias.php?id_categoria=" + idCategoria + "&usuario=" + sessionStorage.loggedUser , {
                method: "DELETE"
            })
                .then(() => {
                    this.categorias = []
                    this.buscarCategorias()
                })
            this.idCategoria = null
          },
    
          crearCategoria(newCat){
            bodyApi = "usuario=" + sessionStorage.loggedUser + "&descripcion=" + newCat.nombre + "&color=" + newCat.color + "&imagen_categoria=" + newCat.imagen + "&link_video=" + newCat.link_video  
            fetch("ApiRes/categorias.php", {
                method: 'POST',
                body: bodyApi,
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'
                })
            })
            .then(response => response.json())
            .then(data => {
                this.buscarCategorias()
            });
          },
          modificarCategoria(categoria) {
    
            bodyApi = "usuario=" + sessionStorage.loggedUser + 
                      "&id_categoria=" + categoria.id +
                      "&descripcion=" + categoria.nombre +
                      "&imagen_categoria=" + categoria.imagen +
                      "&color=" + categoria.color +
                      "&link_video=" + categoria.link_video +
                      "&estado=" + categoria.estado
                      ,
                fetch("ApiRes/categorias.php?"+bodyApi, {
                method: 'PUT',
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'
                })
            })
            .then(response => response.json())
            .then(data => {
                this.buscarCategorias()
            });
        },
    },
    computed: {
    },
    mounted:function(){
        this.buscarCategorias()
    }
  });

  Vue.component('puntajes',{
    template:`
    <div>
        <section style="margin:10px"> 
            <h1 class="titulo" style="margin-top:60px">Administración de puntaje por evento</h1>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class=" text-primary">
                                                <tr>
                                                    <th v-for="col in columns_puntaje" v-on:click="sortTablepuntaje(col)">{{col}}
                                                    <div class="arrow" v-if="col == sortColumn_puntaje" v-bind:class="ascending ? 'arrow_up' : 'arrow_down'"></div>
                                                    </th>
                                                    <th>Acciones</th>
                                                </tr>  
                                            </thead>
                                            <tbody>
                                                <tr v-for="puntaje in get_rows_puntaje()">
                                                    <td v-for="col in columns_puntaje">{{puntaje[col]}}</td>
                                                    <td>
                                                        <button class="btn" @click="alterPuntaje = puntaje" data-toggle="modal" data-target="#modificarPuntaje"><span>Editar</span></button>       
                                                    </td>                                             
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>      
                </div>
            </div>
        </section>

<!-- Modal Modificar puntaje -->
        <div class="modal fade" id="modificarPuntaje" tabindex="-1" role="dialog" aria-labelledby="modificarPuntajeCenterTitle"
aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearPuntajeCenterTitle">
                            Modificar Puntaje
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>
                        {{alterPuntaje.evento}}
                        </h5>      
                        <div class="form-group">
                            <label>Puntaje</label>
                            <input type="number" class="form-control" v-model="alterPuntaje.puntaje" placeholder="Ingresá el puntaje">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="modificarPuntaje(alterPuntaje)">
                            Modificar puntaje
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    `
    ,
    data() {
        return{
            logged:false,
            ascending: false,
            sortColumn_puntaje: '',
            currentPage_puntaje: 1,
            elementsPerPage_puntaje: 50,
            categorias:[],
            puntajes: [],
            puntajeDelete: null,
            alterPuntaje:{
              evento:"",
              puntaje: 0,
            }
        }
    },
    props:{
  
    },
    methods:{

        buscarPuntajes(){
            fetch("ApiRes/puntaje.php?usuario=" + sessionStorage.loggedUser)
            .then(response => response.json() )
           .then((data)=>{
                this.puntajes= data
             })
  
          },
          modificarPuntaje(puntaje) {
            bodyApi = "usuario=" + sessionStorage.loggedUser + "&evento=" + puntaje.evento + "&puntaje=" + puntaje.puntaje,
                fetch("ApiRes/puntaje.php?"+bodyApi, {
                method: 'PUT',
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'
                })
            })
                .then(() => {
                    console.log(puntaje)
                })
                .catch(() => {
                    console.log("error")
                })
        },
  
          "sortTablepuntaje": function sortTablepuntaje(col) {
            if (this.sortColumn_puntaje === col) {
              this.ascending = !this.ascending;
            } else {
              this.ascending = true;
              this.sortColumn_puntaje = col;
            }
      
            var ascending = this.ascending;
      
            this.puntajes.sort(function(a, b) {
              if (a[col] > b[col]) {
                return ascending ? 1 : -1
              } else if (a[col] < b[col]) {
                return ascending ? -1 : 1
              }
              return 0;
            })
          },
          "num_pages_puntaje": function num_pages_puntaje() {
              return Math.ceil(this.puntajes.length / this.elementsPerPage_puntaje);
            },
          "get_rows_puntaje": function get_rows_puntaje() {
              return this.puntajes.slice((this.currentPage_puntaje-1) * this.elementsPerPage_puntaje, (this.currentPage_puntaje-1) * this.elementsPerPage_puntaje + this.elementsPerPage_puntaje);
            },
          "change_page_puntaje": function change_page_puntaje(page_puntaje) {
              this.currentPage_puntaje = page_puntaje;
            }
    },
    computed:{  
        "columns_puntaje": function columns() {
            if (this.puntajes.length == 0) {
              return [];
            }
            return Object.keys(this.puntajes[0])
          },
    },
    mounted: function(){
        this.buscarPuntajes()
    }
  })

  Vue.component('niveles',{
    template:`
    <div>
        <section style="margin:10px"> 
            <h1 class="titulo" style="margin-top:60px">Administración de niveles de Arkidians</h1>
            <button class="btn btn-primary btn-round" data-toggle="modal" data-target="#crearNivel"><span>Nuevo Nivel</span></button>                                                    
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class=" text-primary">
                                                <tr>
                                                    <th v-for="col in columns_nivel" v-on:click="sortTablenivel(col)">{{col}}
                                                        <div class="arrow" v-if="col == sortColumn_nivel" v-bind:class="ascending ? 'arrow_up' : 'arrow_down'"></div>
                                                    </th>
                                                    <th>Acciones</th>
                                                </tr>  
                                            </thead>
                                            <tbody>
                                                <tr v-for="nivel in get_rows_nivel()">
                                                    <td v-for="col in columns_nivel">{{nivel[col]}}</td>
                                                    <td>
                                                        <button class="btn btn-danger" data-toggle="modal" data-target="#eliminarNivel" @click="nivelDelete = nivel"><span>Eliminar</span></button> 
                                                        <button class="btn" @click="alterNivel = nivel" data-toggle="modal" data-target="#modificarNivel"><span>Editar</span></button>    
                                                    </td>                                                
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>      
                </div>
            </div>
        </section>
        <!-- Modal Modificar Nivel -->
        <div class="modal fade" id="modificarNivel" tabindex="-1" role="dialog" aria-labelledby="modificarNivelCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearNivelCenterTitle">
                            Modificar Nivel
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>{{alterNivel.nombre_nivel}}</h5>      
                        <div class="form-group">
                            <label>Puntaje Máximo</label>
                            <input type="number" class="form-control" v-model="alterNivel.puntaje_maximo" placeholder="Ingresá el puntaje máximo del nivel">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="modificarNivel(alterNivel)">
                            Modificar Nivel
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Crear Nivel -->
        <div class="modal fade" id="crearNivel" tabindex="-1" role="dialog" aria-labelledby="crearNivelCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearNivelCenterTitle">
                            Crear Nivel
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre del nivel</label>
                            <input type="text" class="form-control" v-model="nuevoNivel.nombre_nivel" placeholder="Nivel">
                            <small class="form-text text-muted">Ingresá el nombre del nivel</small>
                        </div>
                        <div class="form-group">
                            <label>Puntaje máximo</label>
                            <input type="text" class="form-control" v-model="nuevoNivel.puntaje_maximo" placeholder="Ingresá el puntaje máximo del nivel">
                        </div>
                    </div>
        
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="crearNivel(nuevoNivel)">
                            Crear nivel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Eliminar Nivel-->
        <div class="modal fade" id="eliminarNivel" tabindex="-1" role="dialog" aria-labelledby="eliminarNivelCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eliminarNivelCenterTitle">
                            Eliminar Nivel
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Estás seguro que querés eliminar el nivel?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="eliminarNivel(nivelDelete)">
                            Eliminar nivel
                        </button>
                    </div>
                </div>
            </div>
        </div>  

    </div>
    `
    ,
    data() {
        return{
            logged:false,
            ascending: false,
            sortColumn_nivel: '',
            currentPage_nivel: 1,
            elementsPerPage_nivel: 50,
            categorias:[],
            niveles: [],
            nivelDelete: null,
            nuevoNivel:{
                nombre_nivel:"",
                puntaje_maximo:""
      
            },
            alterNivel:{
              nombre_nivel:"",
              puntaje_maximo: 0,
            },
            nivelDelete:{
                nombre_nivel:"",
                puntaje_maximo:0,
      
            }
        }
    },
    props:{
  
    },
    methods:{

        crearNivel(nivel){
            bodyApi = "usuario=" + sessionStorage.loggedUser + "&nombre_nivel=" + nivel.nombre_nivel + "&puntaje_maximo=" + nivel.puntaje_maximo 
            console.log(bodyApi)
            fetch("ApiRes/niveles.php", {
                method: 'POST',
                body: bodyApi,
                headers: new Headers({
                    'Content-Type': 'application/x-www-form-urlencoded'
                })
            })
            .then(response => response.json())
            .then(data => {
                this.buscarNiveles()
            });


        },
        eliminarNivel(nivel){
            fetch("ApiRes/niveles.php?nombre_nivel=" + nivel.nombre_nivel + "&usuario=" + sessionStorage.loggedUser, {
              method: "DELETE"
              })
              .then(() => {
                this.niveles = []
                fetch("ApiRes/niveles.php?usuario=" + sessionStorage.loggedUser)
                .then(response => response.json() )
               .then((data)=>{
                   console.log(data)
                    this.niveles= data
                 })
              })
  
  
          },
        buscarNiveles(){
            this.niveles=[]
            fetch("ApiRes/niveles.php?usuario=" + sessionStorage.loggedUser)
            .then(response => response.json() )
           .then((data)=>{
               console.log(data)
                this.niveles= data
             })
  
          },
        modificarNivel(nivel) {
          bodyApi = "usuario=" + sessionStorage.loggedUser + "&nombre_nivel=" + nivel.nombre_nivel + "&puntaje_maximo=" + nivel.puntaje_maximo,
              fetch("ApiRes/niveles.php?"+bodyApi, {
              method: 'PUT',
              headers: new Headers({
                  'Content-Type': 'application/x-www-form-urlencoded'
              })
          })
              .then(() => {
                  console.log(nivel)
              })
              .catch(() => {
                  console.log("error")
              })
      },

        "sortTablenivel": function sortTablepuntaje(col) {
          if (this.sortColumn_nivel === col) {
            this.ascending = !this.ascending;
          } else {
            this.ascending = true;
            this.sortColumn_puntaje = col;
          }
    
          var ascending = this.ascending;
    
          this.nivel.sort(function(a, b) {
            if (a[col] > b[col]) {
              return ascending ? 1 : -1
            } else if (a[col] < b[col]) {
              return ascending ? -1 : 1
            }
            return 0;
          })
        },
        "num_pages_niveles": function num_pages_puntaje() {
            return Math.ceil(this.niveles.length / this.elementsPerPage_nivel);
          },
        "get_rows_nivel": function get_rows_puntaje() {
            return this.niveles.slice((this.currentPage_nivel-1) * this.elementsPerPage_nivel, (this.currentPage_nivel-1) * this.elementsPerPage_nivel + this.elementsPerPage_nivel);
          },
        "change_page_nivel": function change_page_puntaje(page_puntaje) {
            this.currentPage_nivel = page_nivel;
          }
    },
    computed:{  
        "columns_nivel": function columns() {
            if (this.niveles.length == 0) {
              return [];
            }
            return Object.keys(this.niveles[0])
          },
    },
    mounted: function(){
        this.buscarNiveles()
    }
  })

  
  var app = new Vue({
    el: "#app",
    data: {
      cursoDelete:null,
      logged: false,
      padre: false,
      admin: false,
      hijo: false,
      categorias : false,
      puntaje : false,
      niveles : false,
    },
  
    methods: {
        verCategorias(){
            this.categorias = true
            this.puntaje = false
            this.niveles = false
        },
        verPuntaje(){
            this.categorias = false
            this.puntaje = true
            this.niveles = false
        },
        verNiveles(){
            this.categorias = false
            this.puntaje = false
            this.niveles = true
        }
    },
  
    computed: {},
  
    mounted: function() {
      this.padre = false;
      this.admin = false;
      this.hijo = false;
      this.logged = false;
  
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
  