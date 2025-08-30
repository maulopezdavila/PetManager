document.addEventListener("DOMContentLoaded", () => {
  // Cargar datos desde localStorage al iniciar
  cargarDatosLocales()

  // Validaciones en tiempo real para formularios
  configurarValidaciones()

  // Configurar búsqueda en tiempo real
  configurarBusqueda()

  // Guardar datos en localStorage cuando se modifican
  sincronizarDatos()
})

// Función para cargar datos desde localStorage
function cargarDatosLocales() {
  const usuario = obtenerUsuarioActual()
  if (!usuario) return

  // Cargar mascotas del usuario
  const mascotasGuardadas = localStorage.getItem("mascotas_" + usuario)
  if (mascotasGuardadas) {
    try {
      const mascotas = JSON.parse(mascotasGuardadas)
      // Los datos ya están en la sesión de PHP, solo verificamos
      console.log("Datos de mascotas cargados desde localStorage")
    } catch (error) {
      console.error("Error al cargar mascotas desde localStorage:", error)
    }
  }

  // Cargar usuarios registrados
  const usuariosGuardados = localStorage.getItem("usuarios_registrados")
  if (usuariosGuardados) {
    try {
      const usuarios = JSON.parse(usuariosGuardados)
      console.log("Usuarios registrados cargados desde localStorage")
    } catch (error) {
      console.error("Error al cargar usuarios desde localStorage:", error)
    }
  }
}

// Función para obtener el usuario actual
function obtenerUsuarioActual() {
  // Esta información vendría de PHP, por simplicidad usamos una variable global
  const metaUsuario = document.querySelector('meta[name="usuario-actual"]')
  return metaUsuario ? metaUsuario.content : null
}

// Configurar validaciones en tiempo real
function configurarValidaciones() {
  // Validación para formulario de registro de usuario
  const formRegistro = document.getElementById("formRegistro")
  if (formRegistro) {
    const usuarioInput = document.getElementById("usuarioInput")
    const passwordInput = document.getElementById("passwordInput")
    const confirmarPasswordInput = document.getElementById("confirmarPasswordInput")

    if (usuarioInput) {
      usuarioInput.addEventListener("input", function () {
        validarUsuario(this.value)
      })
    }

    if (passwordInput) {
      passwordInput.addEventListener("input", function () {
        validarPassword(this.value)
      })
    }

    if (confirmarPasswordInput) {
      confirmarPasswordInput.addEventListener("input", function () {
        validarConfirmacionPassword(passwordInput.value, this.value)
      })
    }
  }

  // Validación para formulario de mascota
  const formMascota = document.getElementById("formMascota")
  if (formMascota) {
    const nombreInput = document.getElementById("nombre")
    if (nombreInput) {
      nombreInput.addEventListener("input", function () {
        validarNombreMascota(this.value)
      })
    }
  }

  // Validación para formulario de dueño
  const formDueno = document.getElementById("formDueno")
  if (formDueno) {
    const nombreInput = document.getElementById("nombre")
    const telefonoInput = document.getElementById("telefono")

    if (nombreInput) {
      nombreInput.addEventListener("input", function () {
        validarNombrePersona(this.value)
      })
    }

    if (telefonoInput) {
      telefonoInput.addEventListener("input", function () {
        validarTelefono(this.value)
      })
    }
  }
}

// Funciones de validación específicas
function validarUsuario(usuario) {
  const errorDiv = document.getElementById("errorUsuario")
  const patron = /^[a-zA-Z0-9_]+$/

  if (usuario.length < 3) {
    mostrarError(errorDiv, "Debe tener al menos 3 caracteres")
    return false
  } else if (usuario.length > 20) {
    mostrarError(errorDiv, "No puede tener más de 20 caracteres")
    return false
  } else if (!patron.test(usuario)) {
    mostrarError(errorDiv, "Solo puede contener letras, números y guiones bajos")
    return false
  } else {
    limpiarError(errorDiv)
    return true
  }
}

function validarPassword(password) {
  const errorDiv = document.getElementById("errorPassword")

  if (password.length < 8) {
    mostrarError(errorDiv, "Debe tener al menos 8 caracteres")
    return false
  } else {
    limpiarError(errorDiv)
    return true
  }
}

function validarConfirmacionPassword(password, confirmacion) {
  const errorDiv = document.getElementById("errorConfirmar")

  if (password !== confirmacion) {
    mostrarError(errorDiv, "Las contraseñas no coinciden")
    return false
  } else {
    limpiarError(errorDiv)
    return true
  }
}

function validarNombreMascota(nombre) {
  const errorDiv = document.getElementById("errorNombre")
  const patron = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/

  if (nombre.length === 0) {
    limpiarError(errorDiv)
    return true // Campo vacío es válido hasta que se envíe
  } else if (!patron.test(nombre)) {
    mostrarError(errorDiv, "Solo puede contener letras y espacios")
    return false
  } else {
    limpiarError(errorDiv)
    return true
  }
}

function validarNombrePersona(nombre) {
  const errorDiv = document.getElementById("errorNombre")
  const patron = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/

  if (nombre.length === 0) {
    limpiarError(errorDiv)
    return true
  } else if (!patron.test(nombre)) {
    mostrarError(errorDiv, "Solo puede contener letras y espacios")
    return false
  } else {
    limpiarError(errorDiv)
    return true
  }
}

function validarTelefono(telefono) {
  const errorDiv = document.getElementById("errorTelefono")
  const patron = /^[0-9\-+$$$$\s]+$/

  if (telefono.length === 0) {
    limpiarError(errorDiv)
    return true
  } else if (!patron.test(telefono)) {
    mostrarError(errorDiv, "Formato de teléfono inválido")
    return false
  } else {
    limpiarError(errorDiv)
    return true
  }
}

// Funciones auxiliares para mostrar errores
function mostrarError(elemento, mensaje) {
  if (elemento) {
    elemento.textContent = mensaje
    elemento.style.display = "block"
  }
}

function limpiarError(elemento) {
  if (elemento) {
    elemento.textContent = ""
    elemento.style.display = "none"
  }
}

// Configurar búsqueda en tiempo real
function configurarBusqueda() {
  const inputBusqueda = document.getElementById("buscarMascota")
  if (inputBusqueda) {
    inputBusqueda.addEventListener("input", function () {
      filtrarTabla(this.value)
    })
  }
}

// Función para filtrar la tabla de mascotas
function filtrarTabla(termino) {
  const tabla = document.querySelector(".tabla-datos tbody")
  if (!tabla) return

  const filas = tabla.getElementsByTagName("tr")

  for (let i = 0; i < filas.length; i++) {
    const fila = filas[i]
    const celdas = fila.getElementsByTagName("td")
    let mostrar = false

    // Buscar en nombre, especie y raza
    for (let j = 0; j < 3; j++) {
      if (celdas[j]) {
        const texto = celdas[j].textContent.toLowerCase()
        if (texto.includes(termino.toLowerCase())) {
          mostrar = true
          break
        }
      }
    }

    fila.style.display = mostrar ? "" : "none"
  }
}

// Función para ver detalles de una mascota
function verDetalles(nombreMascota) {
  window.location.href = "buscar_mascota.php?nombre=" + encodeURIComponent(nombreMascota)
}

// Sincronizar datos con localStorage
function sincronizarDatos() {
  // Esta función se ejecutaría después de operaciones CRUD
  // Por simplicidad, se maneja desde PHP con JavaScript embebido
}

// Función para guardar en localStorage (llamada desde PHP)
function guardarEnLocalStorage(clave, datos) {
  try {
    localStorage.setItem(clave, JSON.stringify(datos))
    return true
  } catch (error) {
    console.error("Error al guardar en localStorage:", error)
    alert("Error al guardar los datos localmente. El navegador puede estar lleno.")
    return false
  }
}

// Función para cargar desde localStorage
function cargarDesdeLocalStorage(clave) {
  try {
    const datos = localStorage.getItem(clave)
    return datos ? JSON.parse(datos) : null
  } catch (error) {
    console.error("Error al cargar desde localStorage:", error)
    return null
  }
}

// Configurar navegación móvil
function configurarNavegacionMovil() {
  const botonMenu = document.querySelector(".boton-menu-movil")
  const navegacion = document.querySelector(".navegacion")

  if (botonMenu && navegacion) {
    botonMenu.addEventListener("click", () => {
      navegacion.classList.toggle("navegacion-abierta")
    })
  }
}

// Mostrar confirmación antes de eliminar
function confirmarEliminacion(tipo, nombre) {
  return confirm(`¿Estás seguro de que quieres eliminar ${tipo} "${nombre}"? Esta acción no se puede deshacer.`)
}

// Mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = "info") {
  const notificacion = document.createElement("div")
  notificacion.className = `notificacion notificacion-${tipo}`
  notificacion.textContent = mensaje

  document.body.appendChild(notificacion)

  // Mostrar la notificación
  setTimeout(() => {
    notificacion.classList.add("mostrar")
  }, 100)

  // Ocultar después de 3 segundos
  setTimeout(() => {
    notificacion.classList.remove("mostrar")
    setTimeout(() => {
      document.body.removeChild(notificacion)
    }, 300)
  }, 3000)
}
