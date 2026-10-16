const formulario = document.getElementById('formulario');
const tabla = document.getElementById('tabla');
const mensaje = document.getElementById('mensaje');
const modal = document.getElementById('modal');
const selectMunicipio = document.getElementById('municipio');

// Abrir y cerrar el modal
document.getElementById('btnCrear').addEventListener('click', () => {
  mensaje.textContent = '';
  modal.showModal();
});
document.getElementById('btnCerrar').addEventListener('click', () => modal.close());

// Llena el <select> con los municipios que vienen del servidor
async function cargarMunicipios() {
  const res = await fetch('api.php?accion=municipios');
  const json = await res.json();

  json.datos.forEach(nombre => {
    const opcion = document.createElement('option');
    opcion.value = nombre;
    opcion.textContent = nombre;
    selectMunicipio.appendChild(opcion);
  });
}

// Trae las personas y las pinta en la tabla
async function cargarDatos() {
  const res = await fetch('api.php?accion=listar');
  const json = await res.json();

  tabla.innerHTML = '';
  json.datos.forEach(p => {
    const fila = document.createElement('tr');
    [p.id, p.nombre, p.cedula, p.correo, p.telefono, p.municipio, p.creado_en].forEach(valor => {
      const td = document.createElement('td');
      td.textContent = valor; // textContent evita inyección XSS (no interpreta HTML)
      fila.appendChild(td);
    });
    tabla.appendChild(fila);
  });
}

// Al enviar el formulario: lo mandamos a PHP sin recargar la página
formulario.addEventListener('submit', async (e) => {
  e.preventDefault(); // evita que el navegador recargue

  const res = await fetch('api.php?accion=guardar', {
    method: 'POST',
    body: new FormData(formulario), // junta todos los campos con su "name"
  });
  const json = await res.json();

  mensaje.textContent = json.mensaje;
  mensaje.className = json.ok ? 'ok' : 'error';

  if (json.ok) {
    formulario.reset();
    modal.close();
    cargarDatos();
  }
});

cargarMunicipios();
cargarDatos();