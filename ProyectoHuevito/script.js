    // function mostrarMensaje() {
    //   var popup = document.getElementById("popup");
    //   popup.style.display = "block";
    // }

    // function cerrarMensaje() {
    //   var popup = document.getElementById("popup");
    //   popup.style.display = "none";
    // }
// function mostrarMensaje() {
//   var popup = document.getElementById("popup");
//   popup.style.display = "block";
// }

// function cerrarMensaje() {
//   var popup = document.getElementById("popup");
//   popup.style.display = "none";
// }

const formulario = document.getElementById('formRegistro');
const inputs = document.querySelectorAll('#formRegistro input');
const select = document.getElementById('tipo_uso');

const expresiones = {
	usuario: /^[a-zA-Z0-9\_\-]{4,16}$/, // Letras, numeros, guion y guion_bajo
	nombre: /^[a-zA-ZÀ-ÿ\s]{1,40}$/, // Letras y espacios, pueden llevar acentos.
	password: /^.{4,12}$/, // 4 a 12 digitos.
	correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
}

const campos = {
	usuario: false,
	nombre: false,
	apellido: false,
	password: false,
	tipo_uso: false,
	correo: false
}

const validarFormulario = (e) => {
	switch (e.target.name) {
		case "usuario":
			validarCampo(expresiones.usuario, e.target, 'usuario');
		break;
		case "nombre":
			validarCampo(expresiones.nombre, e.target, 'nombre');
		break;
    case "apellido":
			validarCampo(expresiones.nombre, e.target, 'apellido');
		break;
		case "password":
			validarCampo(expresiones.password, e.target, 'password');
		break;
		case "tipo_uso":
			validarTipoUso(e.target);
		break;
    case "correo":
			validarCampo(expresiones.correo, e.target, 'correo');
		break;
	}
}

const validarCampo = (expresion, input, campo) => {
	if(expresion.test(input.value)){
		document.getElementById(`grupo_${campo}`).classList.remove('form-grupo-incorrecto');
		document.getElementById(`grupo_${campo}`).classList.add('form-grupo-correcto');
		document.querySelector(`#grupo_${campo} i`).classList.add('bi-check-circle-fill');
		document.querySelector(`#grupo_${campo} i`).classList.remove('bi-x-circle-fill');
		// document.querySelector(`#grupo-${campo} .formulario__input-error`).classList.remove('formulario__input-error-activo');
		campos[campo] = true;
    // console.log(true);
	} else {
		document.getElementById(`grupo_${campo}`).classList.add('form-grupo-incorrecto');
		document.getElementById(`grupo_${campo}`).classList.remove('form-grupo-correcto');
		document.querySelector(`#grupo_${campo} i`).classList.add('bi-x-circle-fill');
		document.querySelector(`#grupo_${campo} i`).classList.remove('bi-check-circle-fill');
		// document.querySelector(`#grupo-${campo} .formulario__input-error`).classList.add('formulario__input-error-activo');
		campos[campo] = false;
    // console.log(false);
	}
}

const validarTipoUso = (input) => {
	if(input.value !== ""){
		campos['tipo_uso'] = true;
    // console.log(true);
	} else {
		campos['tipo_uso'] = false;
    // console.log(false);
	}
}

inputs.forEach((input) => {
	input.addEventListener('keyup', validarFormulario);
	input.addEventListener('blur', validarFormulario);
});

select.addEventListener('change', validarFormulario);

formulario.addEventListener('submit', (e) => {
	e.preventDefault();

	if(campos.usuario && campos.nombre && campos.apellido && campos.password && campos.tipo_uso && campos.correo){
		// formulario.reset();

		// document.getElementById('formulario__mensaje-exito').classList.add('formulario__mensaje-exito-activo');
		// setTimeout(() => {
		// 	document.getElementById('formulario__mensaje-exito').classList.remove('formulario__mensaje-exito-activo');
		// }, 5000);

		// document.querySelectorAll('.formulario__grupo-correcto').forEach((icono) => {
		// 	icono.classList.remove('formulario__grupo-correcto');
		// });
    formulario.submit();
	} else {
    alert('Complete los campos correctamente');
		// document.getElementById('formulario__mensaje').classList.add('formulario__mensaje-activo');
	}
});