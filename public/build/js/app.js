let paso=1;const pasoInicial=1,pasoFinal=3,cita={id:"",nombre:"",fecha:"",hora:"",servicios:[]};function iniciarApp(){mostrarSeccion(),tabs(),botonesPaginador(),paginaAnterior(),paginaSiguiente(),consultarAPI(),idCliente(),nombreCliente(),seleccionarFecha(),seleccionarHora()}function mostrarSeccion(){const e=document.querySelector(".mostrar");e&&e.classList.remove("mostrar");const t=`#paso-${paso}`;document.querySelector(t).classList.add("mostrar");const a=document.querySelector(".actual");a&&a.classList.remove("actual");document.querySelector(`[data-paso="${paso}"]`).classList.add("actual")}function tabs(){document.querySelectorAll(".tabs button").forEach((e=>{e.addEventListener("click",(function(e){paso=parseInt(e.target.dataset.paso),mostrarSeccion(),botonesPaginador()}))}))}function botonesPaginador(){const e=document.querySelector("#anterior"),t=document.querySelector("#siguiente");switch(paso){case 1:e.classList.add("ocultar"),t.classList.remove("ocultar");break;case 3:e.classList.remove("ocultar"),t.classList.add("ocultar"),mostrarResumen();break;default:e.classList.remove("ocultar"),t.classList.remove("ocultar")}mostrarSeccion()}function paginaAnterior(){document.querySelector("#anterior").addEventListener("click",(()=>{paso<=pasoInicial||(paso--,botonesPaginador())}))}function paginaSiguiente(){document.querySelector("#siguiente").addEventListener("click",(()=>{paso>=pasoFinal||(paso++,botonesPaginador())}))}async function consultarAPI(){try{const e="/api/servicios",t=await fetch(e);mostrarServicios(await t.json())}catch(e){console.log(e)}}function mostrarServicios(e){e.forEach((e=>{const{id:t,nombre:a,precio:o}=e,n=document.createElement("P");n.classList.add("nombre-servicio"),n.textContent=a;const c=document.createElement("P");c.classList.add("precio-servicio"),c.textContent=o+"€";const r=document.createElement("DIV");r.classList.add("servicio"),r.dataset.idServicio=t,r.onclick=function(){seleccionarServicio(e)},r.appendChild(n),r.appendChild(c);document.querySelector("#servicios").appendChild(r)}))}function seleccionarServicio(e){const{id:t}=e,{servicios:a}=cita,o=document.querySelector(`[data-id-servicio="${t}"]`);a.some((e=>e.id===t))?(cita.servicios=a.filter((e=>e.id!==t)),o.classList.remove("seleccionado")):(cita.servicios=[...a,e],o.classList.add("seleccionado"))}function idCliente(){const e=document.querySelector("#id").value;cita.id=e}function nombreCliente(){const e=document.querySelector("#nombre").value;cita.nombre=e}function seleccionarFecha(){document.querySelector("#fecha").addEventListener("input",(function(e){const t=new Date(e.target.value).getUTCDay();[0,6].includes(t)?(e.target.value="",mostrarAlerta("Fines de semana no permitidos","error","formulario")):cita.fecha=e.target.value}))}function seleccionarHora(){document.querySelector("#hora").addEventListener("input",(function(e){const t=e.target.value.split(":")[0];t<10||t>18?(e.target.value="",mostrarAlerta("Hora no válida","error","formulario")):cita.hora=e.target.value}))}function mostrarAlerta(e,t,a,o=!0){const n=document.querySelector(".alerta");n&&n.remove();const c=document.createElement("DIV");c.textContent=e,c.classList.add("alerta"),c.classList.add(t);document.querySelector(`.${a}`).appendChild(c),o&&setTimeout((()=>{c.remove()}),3e3)}function mostrarResumen(){const e=document.querySelector(".contenido-resumen");for(;e.firstChild;)e.removeChild(e.firstChild);if(Object.values(cita).includes("")||0===cita.servicios.length)return void mostrarAlerta("Faltan datos de servicios, Fecha u Hora","error","contenido-resumen",!1);const{nombre:t,fecha:a,hora:o,servicios:n}=cita,c=document.createElement("H3");c.textContent="Resumen de Servicios",e.appendChild(c),n.forEach((t=>{const{id:a,precio:o,nombre:n}=t,c=document.createElement("DIV");c.classList.add("contenedor-servicio");const r=document.createElement("P");r.textContent=n;const i=document.createElement("P");i.innerHTML=`<span>Precio:</span> $${o}`,c.appendChild(r),c.appendChild(i),e.appendChild(c)}));const r=document.createElement("H3");r.textContent="Resumen de Cita",e.appendChild(r);const i=document.createElement("P");i.innerHTML=`<span>Nombre:</span> ${t}`;const s=new Date(a),d=s.getMonth(),l=s.getDate(),u=s.getFullYear(),m=new Date(Date.UTC(u,d,l)).toLocaleDateString("es-ES",{weekday:"long",year:"numeric",month:"long",day:"numeric"}),p=document.createElement("P");p.innerHTML=`<span>Fecha:</span> ${m}`;const v=document.createElement("P");v.innerHTML=`<span>Hora:</span> ${o}`;const h=document.createElement("BUTTON");h.classList.add("boton"),h.textContent="Reservar Cita",h.onclick=reservarCita,e.appendChild(i),e.appendChild(p),e.appendChild(v),e.appendChild(h)}async function reservarCita(){const{id:e,fecha:t,hora:a,servicios:o}=cita,n=o.map((e=>e.id)),c=new FormData;c.append("usuarioId",e),c.append("fecha",t),c.append("hora",a),c.append("servicios",n);try{const e="/api/citas",t=await fetch(e,{method:"POST",body:c});(await t.json()).resultado&&Swal.fire({icon:"success",title:"Cita Creada",text:"Tú cita fue creada correctamente"}).then((()=>{setTimeout((()=>{window.location.reload()}),2e3)}))}catch(e){Swal.fire({icon:"error",title:"Error",text:"Hubo un error al guardar la cita"})}}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));