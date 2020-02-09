
var app = new Vue({

  el: "#app",

  data: {
    accionForm: "Solicitar una beca",
    error: {
      nombre: false,
      mail: false,
      telefono: false,
      ciudad: false,
      pais: false,
      especialidad: false,
      matricula: false
    },
    errorForm: false,
    inscripcionOk: false,
    inscripcionDuplicada: false,
    inscripcion: {
      nombre: null,
      mail: null,
      telefono: null,
      ciudad: null,
      pais: null,
      especialidad: null,
      matricula: null
    },
    beca: false,
    curso: {
      titulo: "Curso de actualización online",
      subtitulo: "Manejo y tratamiento de la EPOC",
      objetivos: [
        "Resumir con una visión integral y actualizada la evidencia científica disponible sobre la Enfermedad Pulmonar Obstructiva Crónica (EPOC).",
        "Presentar las Guías GOLD 2020, LatinEPOC 2014 y su actualización incorporando nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019.",
        "Revisar y actualizar los conocimientos sobre broncodilatadores inhalados en EPOC, en términos de mecanismo de acción, eficacia y seguridad, además de las indicaciones de acuerdo a los criterios de severidad."
      ],
      desarrollos: [
        {
          titulo: "Módulo 1",
          subtitulo: "Guías ALAT y GOLD: ¿Qué hay de nuevo?",
          docente: "Prof. Dra. Maria Montes de Oca",
          nacionalidad: "Venezuela",
          contenidos: [
            "Guías GOLD 2020.",
            "Guías LatinEPOC 2014.",
            "Documento incorporando nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019."
          ],
          objetivos: [
            "Conocer las Guías de EPOC 2020 de la Iniciativa GOLD y de la Asociación Latinoamericana del Tórax 2019.",
            "Reconocer los criterios de diagnóstico y clasificación de acuerdo a ambas iniciativas.",
            "Reconocer las semejanzas y diferencias entre ambas guías y las implicancias en el tratamiento de los pacientes."
          ]
        },
        {
          titulo: "Módulo 2",
          subtitulo: "Actualización sobre broncodilatadores inhalados en EPOC",
          docente: "Dra. Ana López",
          nacionalidad: "Argentina",
          contenidos: [
            "Objetivos y estrategias de tratamiento farmacológico de la EPOC estable según GOLD 2020, LatinEPOC 2014  y nuevas evidencias para el tratamiento farmacológico en la EPOC.",
            "Mecanismos de acción y farmacodinamia fármacos broncodilatadores.",
            "Indicaciones de los broncodilatadores de acción prolongada y de acuerdo a la evaluación de severidad clínica."
          ],
          objetivos: [
            "Reconocer los objetivos y estrategias de tratamiento farmacológico de la EPOC estable.",
            "Identificar los fármacos broncodilatadores sus mecanismos de acción y farmacodinamia.",
            "Reconocer las indicaciones de los broncodilatadores de acción prolongada y su indicación de acuerdo a la evaluación de severidad clínica."
          ]
        },
        {
          titulo: "Módulo 3",
          subtitulo:
            "Estado del arte del uso de corticoides inhalados (ICS) en EPOC",
          docente: "Prof. Dr. Gustavo E. Zabert",
          nacionalidad: "Argentina",
          contenidos: [
            "ICS en EPOC: racionalidad y variable de resultados significativos relacionados con el uso de ICS en EPOC.",
            "ICS como monoterapia,  asociados a LABA o en triple terapia vs LAMA y LABA-LAMA :  ensayos clínicos pivotales, efectividad y perfil de seguridad.",
            "ICS según GOLD 2020 y nuevas evidencias para el tratamiento farmacológico en la EPOC ALAT 2019."
          ],
          objetivos: [
            "Describir las evidencias, la racionalidad y el perfil de seguridad de los ICS en los pacientes con EPOC.",
            "Describir las evidencias de los ICS como monoterapia, asociado a LABA y a triple terapia.",
            "Describir las evidencias sobre broncodilatación dual (LABA-LAMA) vs ICS-LABA.",
            "Reconocer los fenotipos y marcadores que se proponen para escalar y desescalar el uso de ICS en EPOC."
          ]
        },
        {
          titulo: "Módulo 4",
          subtitulo: "EPOC y comorbilidades",
          docente: "Dr. Alfredo Guerreros",
          nacionalidad: "Perú",
          contenidos: [
            "EPOC como una condición de multi-comorbilidad y de envejecimiento.",
            "Comorbilidad respiratoria y cardiovascular en EPOC.",
            "Comorbilidades musculoesqueléticas, psiquiátricas y metabólicas en EPOC.",
            "Comorbilidades agudas que pueden simular exacerbaciones agudas.",
            "Cómo las comorbilidades modifican las estrategias terapéuticas en EPOC."
          ],
          objetivos: [
            "Reconocer a la EPOC como una condición de multi-comorbilidad.",
            "Identificar las principales comorbilidades que pueden acompañar a la EPOC.",
            "Reconocer y diagnosticar las comorbilidades agudas que pueden simular exacerbaciones agudas.",
            "Reconocer la importancia y estrategias de manejo de las comorbilidades en la EPOC."
          ]
        },
        {
          titulo: "Módulo 5",
          subtitulo: "Intervenciones efectivas para el manejo de EPOC",
          docente: "Dr. Rafael Silva",
          nacionalidad: "Chile",
          contenidos: [
            "EPOC como una condición prevenible y tratable.",
            "Objetivos del tratamiento EPOC y las intervenciones con efectividad demostrada.",
            "Prevención primaria y secundaria evitando exposición a tabaco (abandono del consumo= y contaminantes aéreos y prevención de infecciones respiratorias).",
            "Intervenciones no farmacológicas (rehabilitación, educación y manejo integral).",
            "Intervenciones farmacológicas (broncodilatadores, reemplazo de AAT y otros).",
            "Indicación de oxigenoterapia, ventilación no invasiva, tratamientos invasivos quirúrgicos (reducción de volumen o trasplante pulmonar) o endoscópicos, cuidados de fin de vida y cuidados paliativos."
          ],
          objetivos: [
            "Reconocer a la EPOC como una condición prevenible y tratable.",
            "Identificar los objetivos del tratamiento y las intervenciones con efectividad demostrada.",
            "Reconocer al abandono del consumo de tabaco, evitar exposición a polutantes, vacunación y la rehabilitación respiratoria como pilares en el manejo de todo paciente con EPOC.",
            "Reconocer a los broncodilatadores como eje central del tratamiento farmacológico.",
            "Reconocer el tratamiento sustitutivo del déficit de AAT.",
            "Reconocer las indicaciones de intervenciones con efectividad en condiciones especiales."
          ]
        },
        {
          titulo: "Módulo 6",
          subtitulo: "Terapia inhalada",
          docente: "Dr. Ricardo del Olmo",
          nacionalidad: "Argentina",
          contenidos: [
            "Principios de administración de fármacos por vía inhalatoria.",
            "Distintas formulaciones para tratamiento por vía inhalada (nebulización, MDI presurizados, polvo seco y nube), sus propiedades y características.",
            "Dispositivos para la administración de medicamentos por vía inhalatoria en la EPOC.",
            "Barreras para la adherencia y cumplimiento a los tratamientos inhalatorios, su evaluación y estrategias de optimización del tratamiento (educación al proveedor de salud, al usuario, selección y adaptación de los dispositivos a las diferentes situaciones clínicas)."
          ],
          objetivos: [
            "Reconocer los principios de administración de fármacos por vía inhalatoria.",
            "Identificar las distintas formulaciones disponibles para tratamiento por vía inhalada.",
            "Reconocer los principales dispositivos para la administración de medicamentos por vía inhalatoria en la EPOC.",
            "Reconocer las barreras para la adherencia a los tratamientos por vía inhalatoria en la EPOC y las posibles soluciones.",
            "Reconocer la importancia de la educación del paciente para optimizar el tratamiento por vía inhalatoria."
          ]
        },
        {
          titulo: "Módulo 7",
          subtitulo: "Actividad física en EPOC en fase estable",
          docente: "Dr. Alejandro Casas",
          nacionalidad: "Colombia",
          contenidos: [
            "Niveles de actividad física en pacientes con EPOC y su impacto en calidad de vida, capacidad de ejercicio y pronóstico.",
            "Determinantes de la inactividad física en los pacientes con EPOC y los componentes de la rehabilitación pulmonar como estrategias para mejorar el estado de salud en EPOC (entrenamiento físico, educación y modificación de conducta).",
            "Efectividad de los broncodilatadores de acción prolongada para mejorar síntomas, capacidad de ejercicio y actividad física.",
            "Evidencias sobre intervenciones multifactoriales para mejorar las variables de resultados en los pacientes con EPOC."
          ],
          objetivos: [
            "Reconocer los bajos niveles de actividad física y la limitación de la capacidad en los pacientes con EPOC.",
            "Identificar a la actividad física como un factor clave en el pronóstico y la evolución de los pacientes con EPOC.",
            "Conocer el impacto de los broncodilatadores en la función pulmonar, síntomas, tolerancia y capacidad de ejercicio y actividad física.",
            "Reconocer la importancia de intervenciones multifactoriales concurrentes, farmacoterapia y rehabilitación pulmonar, en los pacientes con EPOC."
          ]
        },
        {
          titulo: "Módulo 8",
          subtitulo: "Manejo de Exacerbación Aguda de EPOC",
          docente: "Prof. Dr. Gustavo E. Zabert",
          nacionalidad: "Argentina",
          contenidos: [
            "Diagnóstico de las exacerbaciones agudas (EA) de EPOC.",
            "Etiología y de diagnóstico diferencial de las EA de EPOC.",
            "Objetivos, clasificación y estrategias de tratamiento de las EA la EPOC.",
            "Intervenciones terapéuticas con efectividad comprobada en EA (broncodilatadores de acción corta, corticoides sistémicos, antibióticos, oxigenoterapia, ventilación mecánica invasiva y no invasiva).",
            "Efectividad de los broncodilatadores de acción prolongada y corticoides inhalados en la prevención de EA. Selección adecuada según fenotipos."
          ],
          objetivos: [
            "Reconocer y diagnosticar las exacerbaciones agudas (EA) de EPOC.",
            "Identificar las posibles etiologías y de diagnóstico diferencial de las EA de EPOC.",
            "Reconocer los objetivos y estrategias de tratamiento de las EA la EPOC.",
            "Reconocer las intervenciones terapéuticas con efectividad comprobada en la prevención de las exacerbaciones de EPOC."
          ]
        }
      ],
      modalidad: [
        "Ocho (8) módulos con presentación teórica, autoestudio y evaluación. Diez (10) horas totales.",
        "Al finalizar la cursada se realizará una evaluación on-line.",
        "Duración: Marzo - Septiembre 2020."
      ],
      docentes: [
        {
          nombre: "Prof. Dr. Gustavo E Zabert",
          nacionalidad: "Argentina",
          imagen: "DRzabert.png",
          bio: "Presidente 2018-2020 Asociación Latinoamericana del Tórax. Profesor Asociado Regular, Facultad de Ciencias Médicas, Universidad Nacional del Comahue Argentina. Director de Docencia, Clínica Pasteur, Neuquén Argentina. Past Presidente 2007-2008, Asociacion Argentina de Medicina Respiratoria."
        },
        {
          nombre: "Prof. Dra. Maria Montes de Oca",
          nacionalidad: "Venezuela",
          imagen: "DRoca.png",
          bio: "Médico Cirujano. Doctor en Ciencias Médicas. Especialista en Neumonología. 2012-2014 Presidenta Asociación Latino Americana del Thorax (ALAT) Jefe de la Cátedra- Servicio de Neumonología y Cirugía; Hospital Universitario de Caracas, Facultad de Medicina, UCV, Caracas, D.F. de Tórax"
        },
        {
          nombre: "Dr. Alejandro Casas",
          nacionalidad: "Colombia",
          imagen: "DRcasas.png",
          bio: "Médico Especialista en Medicina Interna y Neumología. Doctor (Ph. D ), Bio-Patología en Medicina, Universidad de Barcelona con tesis doctoral laureada SUMMA CUM LAUDE POR UNANIMIDAD 2009. Profesor Titular de Medicina Respiratoria, Universidad del Rosario, Bogotá. Ex-presidente de la Asociación Latino Americana de Tórax (ALAT). Director General Fundación Neumológica Colombiana, Bogotá, Colombia 2015-"
        },
        {
          nombre: "Dr. Ricardo del Olmo",
          nacionalidad: "Argentina",
          imagen: "DRolmo.png",
          bio: "Médico especialista en Medicina Interna y Neumonología (Universidad de Buenos Aires). Integrante del Laboratorio Pulmonar del Hospital María Ferrer de Buenos Aires. Becario del Instituto Clínico del Tórax del Hospital Clinic i Provincial, Barcelona, Catalunya. Integrante del Comité de Espirometrías del Grupo EPOC.AR. Co-Director de la Conferencia Internacional sobre EPOC, Buenos Aires. Honorarios de la industria farmacéutica como consultoría, asistencia a congresos o investigación en los últimos 5 años de: AstraZeneca, Boehringer-Ingelheim, Gador, GSK, MundiPharma, Novartis, Phoenix, Sanofi-Genzyme. No tengo relación con la industria tabacalera ni de E-Cig."
        },
        {
          nombre: "Dra. Ana López",
          nacionalidad: "Argentina",
          imagen: "DRlopez.png",
          bio: "Médico del Servicio de Neumonología del Hospital Universitario Privado de Córdoba. Docente universitario Universidad Nacional de Córdoba y del Instituto Universitario Ciencias Biomédicas de Córdoba. (IUCBC). Director de post-grado en Neumonologia. Ex-presidente de la Asociación Argentina de Medicina Respiratoria (AAMR)."
        },
        {
          nombre: "Dr. Alfredo Guerreros",
          nacionalidad: "Perú",
          imagen: "DRguerrero.png",
          bio: "Miembro titular de la Sociedad Peruana de Tisiología, Neumología y Enfermedades del Tórax.- Miembro titular de la Sociedad Europea de Enfermedades Respiratorias, 2004 a la fecha. Miembro Titular de la Asociación Americana del Tórax, 2006 a la fecha. Docente de la Facultad de Medicina de la Universidad Nacional Mayor de San Marcos, del 1995 a la fecha. Presidente del Comité de Cuidados Intensivos de la Sociedad Peruana de Neumología. Director de la Unidad de Investigación y Docencia Clinica Internacional, del 2006 a la fecha. Director Médico Ambulatorio, Clínica Internacional, 2014 a la fecha"
        },
        {
          nombre: "Dr. Rafael Silva",
          nacionalidad: "Chile",
          imagen: "DRsilva.png",
          bio: "Investigador Centro de Investigación del Maule, Departamento Cardio Respiratorio. Jefe de Medicina Unidad Respiratoria Hospital Regional de Talca (2008- 2017). Vicedecano Facultad de Ciencias de la Salud Universidad Autónoma de Chile (2012-2019)."
        }
      ],
      certificacion: "Con aval y certificación otorgado por ALAT.",
      certificado:
        "Será enviado a todos los participantes que hayan completado todo los módulos y hayan aprobado la evaluación final."
    }
  },
  methods: {
    switchBeca: function () {
      app.beca = !app.beca
      if (app.beca) {
        app.accionForm = "Inscribirme"
      } else {
        app.accionForm = "Solicitar una Beca"
      }
    },
    solicitarBeca: function (inscripcion) {
      error = false
      if (!inscripcion.nombre) {
        app.error.nombre = true
        error = true
      }
      if (!inscripcion.mail) {
        app.error.mail = true
        error = true
      }
      if (!inscripcion.telefono) {
        app.error.telefono = true
        error = true
      }
      if (!inscripcion.ciudad) {
        app.error.ciudad = true
        error = true
      }
      if (!inscripcion.pais) {
        app.error.pais = true
        error = true
      }
      if (!inscripcion.matricula) {
        app.error.matricula = true
        error = true
      }
      if (!inscripcion.especialidad) {
        app.error.especialidad = true
        error = true
      }
      if (!inscripcion.beca) {
        inscripcion.beca = "Solicitud de beca"
      }

      if (error) {
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
        .then(function (response) {
          app.errorForm = false
          if (response.ok) {
            loginResponse = response.json()
            loginResponse.then(function (result) {
              if (result.resultado == "Error") {
                if (result.mensaje.errorInfo[1] == 1062) {
                  app.inscripcionDuplicada = true
                  return
                }
                app.errorForm = true
              } else {
                app.inscripcionOk = true
              }


            })
          } else {
            throw "Error en la llamada Ajax"
          }
        })

    },


    myFunction: function (id) {
      var x = document.getElementById(id);
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
      } else {
        x.className = x.className.replace(" w3-show", "");
      }
    }
  },
  created: function () {
    window.addEventListener("load", function () {

      const loader = document.querySelector(".loader");
      loader.className += " hidden";
    })

  }
});
