# Sistema de Gestión de Mascotas (Pet Manager)
MAUDIEL LÓPEZ DÁVILA 12-4
COLEGIO TÉCNICO PROFESIONAL ALAJUELITA 
DESARROLLO DE SOFTWARE


Mi sistema de gestión de mascotas para un veterinaria en PHP para la prueba técnica. Básicamente, es una app web simple para gestionar mascotas en una vet pequeña: registrar mascotas, dueños, visitas médicas, editar, eliminar, buscar y exportar a CSV. Lo hice todo básico pero funcional, siguiendo las instrucciones de la prueba, que se enfoca en OOP, lógica, validaciones y documentación, sin complicaciones como bases de datos. Usé sesiones de PHP para guardar datos temporalmente, y un toque de localStorage en JS para backup local. Nada de DB porque la prueba no lo pide. Aunque en un contexto real evidentemente hubiera usado BD.

Para probarlo, solo necesitas XAMPP o un server local.

## FUNCIONES:

- **Registrar:** Agrega mascotas (nombre, especie, raza, fecha nac, color), dueños (nombre, tel, email, dir) y visitas (fecha, diag, trat, motivo). Puedes asociar dueños a mascotas y visitas a mascotas.
- **Listar y buscar:** Páginas para listas de mascotas, dueños y visitas. Buscador por nombre de mascota que muestra detalles con dueños y historial.
- **Editar y eliminar:** Corrige o borra entradas con confirmaciones para no cagarla.
- **Login y registro:** Sistema simple de usuarios. Regístrate, loguea y logout. Usé hash para passwords por seguridad básica.
- **Exportar:** Descarga CSV de mascotas.
- **Diseño y JS:** CSS y JS para validaciones en vivo (ej: chequea tel mientras escribes), filtros en tablas y notificaciones.

Todo en PHP puro con OOP (clases para Mascota, Dueno, etc.). No DB porque, como dice la prueba, es para modelaje de clases y funcionalidades básicas, no persistencia real. Datos en sesiones y localStorage para simular. Si fuera prod, agregaría MySQL, pero aquí es demo.

## Instalación 

1. **Descarga:** Clona o descarga este repo: https://github.com/maulopezdavila/SistemaGestionMascotas
2. **Server local:** Ponla en `htdocs` de XAMPP (como sugiere la guía de la prueba). Arranca Apache.
3. **Accede:** Abre `http://localhost/[el_nombre_de_la_carpeta_donde_pusiste_los_arhivos]/.
4. **Regístrate:** Ve a `registro_usuario.php`, crea user (nombre min 3 chars, pass min 8).
5. **Login:** En `iniciar_sesion.php`.
6. **Y LISTO** Registra mascotas y las demás funcionalidades

Nota: Sin DB. La prueba no pide persistencia, solo lógica y OOP, por eso lo dejé así. Para real, usaría PDO con MySQL.

## Cómo usarlo 

- **Home (index.php):** Dashboard con bienvenida, tarjetas para registrar (links a forms), y buscador.
- **Registrar:**
  - Mascota: `registrar/registrar_mascota.php` – Llena campos, asocia dueños si quieres.
  - Dueño: `registrar/registrar_dueno.php` – Asocia a mascota opcional.
  - Visita: `registrar/registrar_visita.php` – Elige mascota.
- **Listas:**
  - Mascotas: `listas/listar_mascotas.php` – Tabla con edit/elim botones.
  - Dueños: `listar_duenos.php`.
  - Visitas: `listar_visitas.php`.
- **Editar/Eliminar:** Desde listas, edit va a `editar/editar_*.php` (prellena form), elim confirma y borra.
- **Buscar:** En home, busca nombre, va a detalles con dueños/visitas.
- **Exportar:** Botón en lista de mascotas.
- **Logout:** Link en header.

Validaciones everywhere: Regex para nombres (solo letras/espacios), tel (dígitos), etc. Errores en vivo via JS. Manejo de errores en PHP con dies y redirects.

## Estructura del proyecto 

Carpeta `mascotas/`:

- **models/**: Clases OOP como pide la prueba (Parte 1: Modelaje).
  - `Mascota.php`: Nombre, especie, raza, etc. Relaciones con arrays para dueños/visitas (uno a muchos).
  - `Dueno.php`: Nombre, tel, email, dir.
  - `VisitaMedica.php`: Fecha, diag, trat, motivo.
  - `Usuario.php`: Para auth, con hash y verify.
- **editar/**: Forms para editar.
- **eliminar/**: Scripts para borrar.
- **listas/**: Páginas de listas.
- **otros/**: Buscador y CSV.
- **procesar/**: Lógica POST (registrar, editar, login).
- **registrar/**: Forms para agregar.
- Raíz: `index.php`, login/registro, CSS/JS.

Organizado para claridad.

## Evolución: De v1.0 a v2.3 

Empecé con la v1.0 del sistema de gestion de mascotas, basada en la plantilla de la prueba. Era rudimentaria, todo en pocos archivos, para probar el concepto rápido. Luego iteré a v2.3 para competir con mis compañeros porque estaban haciendo unos proyectos muy volados. Aquí los cambios y porqués:

### v1.0: Prototipo básico 
- **Estructura:** Plana, todo en raíz. `index.php` con forms y tabla mezclados. Models en subcarpeta.
- **Funcs:** Solo registrar (mascota, dueño, visita), listar en tabla, buscar detalles, exportar CSV. Login simple sin registro.
- **Clases:** Básicas (nombre/especie en Mascota, etc.). Getters/setters mínimos.
- **Diseño/JS:** CSS simple, JS para collapsibles.
- **Problemas:** Caos en una página. No edit/elim. Login weak. No validaciones fuertes. 

Por qué así: Siguiendo la guía de la prueba, empecé con la plantilla para modelaje y funcs básicas. 

### Cambios a v2.3: Upgrades para la prueba 
Refactor total para Parte 3 (validaciones), Parte 4 (doc) y bonus. Modular para escalabilidad.

1. **Estructura modular:**
   - Por qué: v1.0 era spaghetti. Carpetas separan concerns, fácil mantener.
   - Cambios: Registrar/, listas/, etc. Procesar/ para lógica.

2. **Clases mejoradas:**
   - Por qué: Para OOP full (relaciones con arrays, como sugiere extra). Prueba evalúa modelaje correcto.
   - Cambios: Attrs extras (raza, color, etc.). toArray para serializar. Nueva Usuario para auth.

3. **Edit y Elim:**
   - Por qué: v1.0 no tenía, pero para sistema real (Parte 2 completa). Evita datos basura.
   - Cambios: Carpetas dedicadas. Índices para identificar.

4. **Auth mejorada:**
   - Por qué: Bonus de "sistema de login simple". v1.0 era fake; ahora con registro, hash (seguridad básica).
   - Cambios: `registro_usuario.php`, array de users en sesión.

5. **Listas/UI separadas:**
   - Por qué: Index messy en v1.0. Separa para UX limpia (Parte 2: mostrar listados).
   - Cambios: Páginas dedicadas, nav header..

6. **Validaciones/JS:**
   - Por qué: Parte 3: Manejo errores. v1.0 permitía inputs malos.
   - Cambios: Regex en PHP/JS, errores live. Confirms en elim.

7. **Diseño/CSS:**
   - Por qué: Para presentación pro (Parte 4). v1.0 old.
   - Cambios: Variables, anims, icons.

8. **Otras:**
   - Exportar en listas.
   - No DB: Prueba no lo requiere (foco en lógica/OOP, no BD). Sesiones simulan.
   - Por qué general: Iteré para usabilidad, seguridad, mantenibilidad. Learning curve: empecé simple, pulí.

Si lo usara en un cotexto real, agregaría DB.

Gracias por leer el readme
