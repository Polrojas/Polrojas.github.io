
var app = new Vue({
  
  el: "#app",
  
  data: {
    accionForm: "Solicitar una beca",
    error:{
      nombre:false,
      mail:false,
      telefono:false,
      ciudad:false,
      pais:false,
      especialidad:false,
      matricula: false
    },
    errorForm:false,
    inscripcionOk:false,
    inscripcionDuplicada:false,
    inscripcion:{
      nombre:null,
      mail:null,
      telefono:null,
      ciudad:null,
      pais:null,
      especialidad:null,
      matricula:null
    },
    beca:false,
    curso: {
      titulo: "Curso de actualización ",
      subtitulo: "Manejo y tratamiento de la EPOC",
      objetivos: [
        "Resumir con una visión integral y actualizada la evidencia científica disponible sobre la Enfermedad Pulmonar Obstructiva Crónica (EPOC)",
        "Presentar las Guías GOLD 2019, LatinEPOC 2014 y su actualización incorporando nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019.",
        "Revisar y actualizar los conocimientos sobre broncodilatadores inhalados en EPOC, en términos de mecanismo de acción, eficacia y seguridad, además de las indicaciones de acuerdo a los criterios de severidad."
      ],
      desarrollos: [
        {
          titulo: "Módulo 1",
          subtitulo: "Guías ALAT y GOLD: ¿qué hay de nuevo?",
          docente: "Dra. Prof. Dra. Maria Montes de Oca",
          nacionalidad: "Venezuela",
          contenidos: [
            "Guías GOLD 2019",
            "Guías LatinEPOC 2014",
            "Documento Incorporando nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019."
          ],
          objetivos: [
            "Guías GOLD 2019",
            "Guías LatinEPOC 2014",
            "Documento Incorporando nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019."
          ]
        },
        {
          titulo: "Módulo 2",
          subtitulo: "Actualización sobre broncodilatadores inhalados en EPOC",
          docente: "Docente a confirmar: Dr. Ricardo del Olmo",
          nacionalidad: "Argentina",
          contenidos: [
            "Objetivos y estrategias de tratamiento farmacológico de la EPOC estable según GOLD 2019, LatinEPOC 2014  y nuevas evidencias para el tratamiento farmacológico en la EPOC.",
            "Mecanismos de acción y farmacodinamia fármacos broncodilatadores.",
            "Indicaciones de los broncodilatadores de acción prolongada y de acuerdo a la evaluación de severidad clínica."
          ],
          objetivos: [
            "Objetivos y estrategias de tratamiento farmacológico de la EPOC estable según GOLD 2019, LatinEPOC 2014  y nuevas evidencias para el tratamiento farmacológico en la EPOC.",
            "Mecanismos de acción y farmacodinamia fármacos broncodilatadores.",
            "Indicaciones de los broncodilatadores de acción prolongada y de acuerdo a la evaluación de severidad clínica."
          ]
        },
        {
          titulo: "Módulo 3",
          subtitulo:
            "Estado del arte del uso de corticoides inhalados (ICS) en EPOC",
          docente: "Docente a confirmar: Prof. Dr. Gustavo E Zabert",
          nacionalidad: "Argentina",
          contenidos: [
            "ICS en EPOC: racionalidad y variable de resultados significativos relacionados con el uso de ICS en EPOC.",
            "ICS como monoterapia,  asociados a LABA o en triple terapia vs LAMA y LABA-LAMA :  ensayos clínicos pivotales efectividad y perfil de seguridad.",
            "ICS según GOLD 2019 y nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019"
          ],
          objetivos: [
            "ICS en EPOC: racionalidad y variable de resultados significativos relacionados con el uso de ICS en EPOC.",
            "ICS como monoterapia,  asociados a LABA o en triple terapia vs LAMA y LABA-LAMA :  ensayos clínicos pivotales efectividad y perfil de seguridad.",
            "ICS según GOLD 2019 y nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019"
          ]
        },
        {
          titulo: "Módulo 4",
          subtitulo: "EPOC y comorbilidades",
          docente: "Docente a confirmar: Dr. Alfredo Guerrero",
          nacionalidad: "Perú",
          contenidos: [
            "EPOC como una condición de multi-comorbilidad y de envejecimiento.",
            "Comorbilidad respiratoria y cardiovascular en EPOC.",
            "Comorbilidades musculoesqueléticas, psiquiátricas, metabólicas en EPOC.",
            "Comorbilidades agudas que pueden simular exacerbaciones agudas.",
            "Como las comorbilidades modifican las estrategias terapéuticas en EPOC"
          ],
          objetivos: [
            "EPOC como una condición de multi-comorbilidad y de envejecimiento.",
            "Comorbilidad respiratoria y cardiovascular en EPOC.",
            "Comorbilidades musculoesqueléticas, psiquiátricas, metabólicas en EPOC.",
            "Comorbilidades agudas que pueden simular exacerbaciones agudas.",
            "Como las comorbilidades modifican las estrategias terapéuticas en EPOC"
          ]
        },
        {
          titulo: "Módulo 5",
          subtitulo: "Intervenciones efectivas para el manejo de EPOC",
          docente: "Docente a definir",
          nacionalidad: "",
          contenidos: [
            "EPOC como una condición prevenible y tratable.",
            "Objetivos del tratamiento EPOC y las intervenciones con efectividad demostrada",
            "Prevención primaria y secundaria evitando exposición a tabaco (abandono del consumo= y contaminantes aéreos y prevención de infecciones respiratorias",
            "Intervenciones no farmacológicas (rehabilitación, educación y manejo integral)",
            "Intervenciones farmacológicas (broncodilatadores, reemplazo de AAT y otros) ",
            "Indicación de oxigenoterapia, ventilación no invasiva, tratamientos invasivos quirúrgico (reducción de volumen o trasplante pulmonar) o endoscópicos, cuidados de fin de vida y cuidados paliativos"
          ],
          objetivos: []
        },
        {
          titulo: "Módulo 6",
          subtitulo: "Terapia inhalada",
          docente: "Docente a confirmar Dra. Ana López",
          nacionalidad: "Argentina",
          contenidos: [
            "Principios de administracion de farmacos por via inhalatoria.",
            "Distintas formulaciones para tratamiento por vía inhalada (nebulización, MDI presurizados, polvo seco y nube), sus propiedades y características.",
            "Dispositivos para la administración de medicamentos por vía inhalatoria en la EPOC",
            "Barreras para la adherencia y cumplimiento a los tratamientos inhalatorios, su evaluación y estrategias de optimización del tratamiento (educación al proveedor de salud, al usuario, selección y adaptación de los dispositivos a las diferentes situaciones clínicas)."
          ],
          objetivos: [
            "Principios de administracion de farmacos por via inhalatoria.",
            "Distintas formulaciones para tratamiento por vía inhalada (nebulización, MDI presurizados, polvo seco y nube), sus propiedades y características.",
            "Dispositivos para la administración de medicamentos por vía inhalatoria en la EPOC",
            "Barreras para la adherencia y cumplimiento a los tratamientos inhalatorios, su evaluación y estrategias de optimización del tratamiento (educación al proveedor de salud, al usuario, selección y adaptación de los dispositivos a las diferentes situaciones clínicas)."
          ]
        },
        {
          titulo: "Módulo 7",
          subtitulo: "Actividad física en EPOC en fase estable",
          docente: "Docente a confimar  Dr. Alejandro Casas",
          nacionalidad: "Colombia",
          contenidos: [
            "Niveles de actividad física en pacientes con EPOC y su impacto en calidad de vida, capacidad de ejercicio y pronóstico. ",
            "Determinantes de la inactividad física en los pacientes con EPOC y los componentes de la rehabilitación pulmonar como estrategias para mejorar el estado de salud en EPOC (entrenamiento físico, educación y modificación de conducta)",
            "Efectividad de los broncodilatadores de acción prolongada para mejorar síntomas, capacidad de ejercicio y actividad física.",
            "Evidencias sobre intervenciones multifactoriales para mejorar las variables de resultados en los pacientes con EPOC (programa TOVITO)"
          ],
          objetivos: []
        },
        {
          titulo: "Módulo 8",
          subtitulo: "Manejo de Exacerbación Aguda de EPOC",
          docente: "Docente a confirmar: Prof. Dr. Gustavo E Zabert",
          nacionalidad: "Argentina",
          contenidos: [
            "Diagnóstico de las exacerbaciones agudas (EA) de EPOC ",
            "Etiología y de diagnóstico diferencial de las EA de EPOC.",
            "Objetivos, clasificación y estrategias de tratamiento de las EA la EPOC",
            "Intervenciones terapéuticas con efectividad comprobada en EA (broncodilatadores de acción corta, corticoides sistémicos, antibióticos, oxigenoterapia, ventilación mecánica invasiva y no invasiva)",
            "Efectividad de los broncodilatadores de acción prolongada y corticoides inhalados en la prevención de EA. Selección adecuada según fenotipos."
          ],
          objetivos: []
        }
      ],
      modalidad: [
        "Ocho (8) módulos con presentación teórica, autoestudio y evaluación. Diez (10) horas totales.",
        "Al finalizar la cursada se realizará una evaluación final on-line con la modalidad libro abierto con el análisis de tres (3) casos clínicos.",
        "Duración: Marzo - Septiembre 2020"
      ],
      docentes: [
        {
          nombre: "Prof. Dr. Gustavo E Zabert",
          nacionalidad: "Argentina",
          imagen: "docente.png",
          bio:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        },
        {
          nombre: "Prof. Dra. Maria Montes de Oca",
          nacionalidad: "Venezuela",
          imagen: "docente.png",
          bio:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        },
        {
          nombre: "Dr. Alejandro Casas",
          nacionalidad: "Colombia",
          imagen: "docente.png",
          bio:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        },
        {
          nombre: "Dr. Ricardo del Olmo",
          nacionalidad: "Argentina",
          imagen: "docente.png",
          bio:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        },
        {
          nombre: "Dra. Ana López",
          nacionalidad: "Argentina",
          imagen: "docente.png",
          bio:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        },
        {
          nombre: "Dr. Alfredo Guerrero",
          nacionalidad: "Perú",
          imagen: "docente.png",
          bio:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
        }
      ],
      certificacion: "Con aval y certificación otorgado por ALAT",
      certificado:
        "Será enviado a todos los participantes que hayan completado todo los módulos y hayan aprobado la evaluación final."
    }
  },
  methods: {
/*    switchBeca(){
      app.beca = !app.beca
      if(app.beca){
        app.accionForm = "Inscribirme"
      }else{
        app.accionForm = "Solicitar una Beca"
      }
    },*/
    solicitarBeca(inscripcion){
      error = false
      if(!inscripcion.nombre){
        app.error.nombre = true
        error = true
      }
      if(!inscripcion.mail){
        app.error.mail = true
        error = true
      }
      if(!inscripcion.telefono){
        app.error.telefono = true
        error = true
      }
      if(!inscripcion.ciudad){
        app.error.ciudad = true
        error = true
      }
      if(!inscripcion.pais){
        app.error.pais = true
        error = true        
      }
      if(!inscripcion.matricula){
        app.error.matricula = true
        error = true
      }
      if(!inscripcion.especialidad){
        app.error.especialidad = true
        error = true
      }
      if(!inscripcion.beca){
        inscripcion.beca = "Solicitud de beca"
      }
      
      if(error){
        return
      }
      bodyApi = "nombre=" + inscripcion.nombre + 
              "&mail=" + inscripcion.mail + 
              "&telefono=" + inscripcion.telefono + 
              "&ciudad=" + inscripcion.ciudad + 
              "&pais=" + inscripcion.pais + 
              "&especialidad=" + inscripcion.especialidad +
              "&matricula=" + inscripcion.matricula +
              "&beca=" + inscripcion.beca
      console.log(bodyApi)
      fetch("php/beca.php", {
          method: 'POST',
          body: bodyApi,
          headers: new Headers({
              'Content-Type': 'application/x-www-form-urlencoded'
          })
      })
      .then(function(response) {
          app.errorForm = false
          if(response.ok) {
              loginResponse = response.json()
              loginResponse.then(function(result) {
                  if (result.resultado=="Error"){
                    if(result.mensaje.errorInfo[1]==1062){
                      app.inscripcionDuplicada = true
                      return                     
                    }
                    app.errorForm=true
                  }else{
                    app.inscripcionOk = true
                  }
                  

              })
          } else {
              throw "Error en la llamada Ajax"
          }
       })

    },


    myFunction(id) {
      var x = document.getElementById(id);
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
      } else { 
        x.className = x.className.replace(" w3-show", "");
      }
    }
  },
  created: function(){
    console.log("created")
    console.log(navigator.userAgent)

    window.addEventListener("load",function (){
      console.log("eventlistener")

      const loader = document.querySelector(".loader");
      console.log("hide class")

      loader.className += " hidden";
    })
    //if (navigator.userAgent.search("MSIE") & gt; = 0) {
      // insert conditional IE code here
  //}

  }
});
