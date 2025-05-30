document.addEventListener('DOMContentLoaded', () => {
  const formulario = document.getElementById('formulario');
  const mensaje = document.getElementById('mensaje');

  const selectMoneda = document.getElementById('moneda');
  const selectBodega = document.getElementById('bodega');
  const selectSucursal = document.getElementById('sucursal');

  // Limpiar opciones anteriores
  selectBodega.innerHTML = ''; 

  // Dejar la opcion en blanco
  const opcionVacia = document.createElement('option');
  opcionVacia.value = '';
  opcionVacia.textContent = '';
  opcionVacia.selected = true;
  selectBodega.appendChild(opcionVacia);

  // Cargar monedas y bodegas desde la Base de datos
  async function cargarOpciones() {
    try {
      const res = await fetch('obtener_opciones.php');
      const data = await res.json();

      data.monedas.forEach(mon => {
        const opt = document.createElement('option');
        opt.value = mon.id;
        opt.textContent = mon.nombre;
        selectMoneda.appendChild(opt);
      });

      data.bodegas.forEach(bod => {
        const opt = document.createElement('option');
        opt.value = bod.id;
        opt.textContent = bod.nombre;
        selectBodega.appendChild(opt);
      });

    } catch (error) {
      console.error('Error al cargar opciones:', error);
    }
  }

  // Cargar sucursales según la bodega seleccionada
  selectBodega.addEventListener('change', async (e) => {
    const bodegaId = e.target.value;
    // Dejar la opcion en blanco
    selectSucursal.innerHTML = '<option value=""></option>';

    //Sin bodega seleccionada no se obtienen sucursales
    if (!bodegaId) return;

    try {
      const res = await fetch('obtener_sucursales.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ bodega_id: bodegaId })
      });

      const sucursales = await res.json();

      //Recorrer sucursales y agregarlas como opciones
      sucursales.forEach(suc => {
        const opt = document.createElement('option');
        opt.value = suc.id;
        opt.textContent = suc.nombre;
        selectSucursal.appendChild(opt);
      });

    } catch (error) {
      console.error('Error al cargar sucursales:', error);
    }
  });

  //Ejecutar la funcion para carga dinamica de datos
  cargarOpciones();

  formulario.addEventListener('submit', async (e) => {
    //Para evitar comportamientos predeterminados
    e.preventDefault();

    //Limpiar la variable utilizada para manejar las alertas
    mensaje.textContent = '';
    mensaje.style.color = 'red';

    //Obtenemos los valores ingresados en el formulario
    const codigo = document.getElementById('codigo').value.trim();
    const nombre = document.getElementById('nombre').value.trim();
    const bodega = selectBodega.value;
    const sucursal = selectSucursal.value;
    const moneda = selectMoneda.value;
    const precio = document.getElementById('precio').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const materiales = Array.from(document.querySelectorAll('input[name="material"]:checked')).map(el => el.value);

    // Validaciones del codigo 

    if (!codigo) {
      mensaje.textContent = 'El código del producto no puede estar en blanco.';
      return;
    }

    //Se utiliza expresiones regulares para revisar que exista como minimo 1 letra y 1 numero
    if (!/[a-zA-Z]/.test(codigo) || !/\d/.test(codigo)) {
      mensaje.textContent = 'El código del producto debe contener letras y números.';
      return;
    }

    if (codigo.length < 5 || codigo.length > 15) {
      mensaje.textContent = 'El código del producto debe tener entre 5 y 15 caracteres.';
      return;
    }

    //La validacion de Unicidad se realiza en la parte inferior del codigo
    //Al momento de guardar el producto


    // Validaciones del nombre

    if (!nombre) {
      mensaje.textContent = 'El nombre del producto no puede estar en blanco.';
      return;
    }

    if (nombre.length < 2 || nombre.length > 50) {
      mensaje.textContent = 'El nombre del producto debe tener entre 2 y 50 caracteres.';
      return;
    }

    // Validaciones del precio

    if (!precio) {
      mensaje.textContent = 'El precio del producto no puede estar en blanco.';
      return;
    }

    //Se utiliza expresiones regulares para revisar que el precio
    // tenga hasta 2 decimales 
    if (!/^\d+(\.\d{1,2})?$/.test(precio) || Number(precio) <= 0) {
      mensaje.textContent = 'El precio del producto debe ser un número positivo con hasta dos decimales.';
      return;
    }

    // Validacion de materiales

    if (materiales.length < 2) {
      mensaje.textContent = 'Debe seleccionar al menos dos materiales para el producto';
      return;
    }

    // Validacion de bodega

    if (!bodega) {
      mensaje.textContent = 'Debe seleccionar una bodega.';
      return;
    }

    // Validacion de sucursal

    if (!sucursal) {
      mensaje.textContent = 'Debe seleccionar una sucursal para la bodega seleccionada.';
      return;
    }

    // Validacion de moneda
    if (!moneda) {
      mensaje.textContent = 'Debe seleccionar una moneda para el producto.';
      return;
    }

    // Validaciones de descripcion

    if (!descripcion) {
      mensaje.textContent = 'La descripción del producto no puede estar en blanco.';
      return;
    }

    if (descripcion.length < 10 || descripcion.length > 1000) {
      mensaje.textContent = 'La descripción del producto debe tener entre 10 y 1000 caracteres.';
      return;
    }

    //Ejecutar verificar_codigo para revisar que el codigo sea unico
    try {
      const respuesta = await fetch('verificar_codigo.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ codigo })
      });

      const data = await respuesta.json();

      if (data.existe) {
        mensaje.textContent = 'El código del producto ya está registrado.';
        return;
      }

      const params = new URLSearchParams();

      //Obtencion de parametros
      params.append('codigo', codigo);
      params.append('nombre', nombre);
      params.append('precio', precio);
      params.append('descripcion', descripcion);
      params.append('bodega', bodega);
      params.append('sucursal', sucursal);
      params.append('moneda', moneda);

      //Se obtienen los materiales , dado que es un array
      materiales.forEach(m => params.append('material[]', m));

      const resGuardar = await fetch('guardar_producto.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: params
      });

      const resultado = await resGuardar.json();

      mensaje.textContent = resultado.mensaje;
      mensaje.style.color = resultado.estado === 'ok' ? 'green' : 'red';

    } catch (err) {
      mensaje.textContent = 'Error al enviar los datos.';
      mensaje.style.color = 'red';
    }
  });
});