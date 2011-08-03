function objetoAjax(){
 var xmlhttp=false;
  try{
   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  }catch(e){
   try {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }catch(E){
    xmlhttp = false;
   }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
   xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

function Load(host,divname){
 //donde se mostraráos registros
 //divContenido = document.getElementById("contenido-"+host);
 divContenido = document.getElementById(divname);

 ajax=objetoAjax();
 //uso del medoto GET
 //indicamos el archivo que realizarál proceso de paginar
 //junto con un valor que representa el nro de pagina
 ajax.open("GET", "menu_statistic_sub.php?var="+host);
 divContenido.innerHTML= '<img src="icons/progress.gif">';
 ajax.onreadystatechange=function() {
  if (ajax.readyState==4) {
   //mostrar resultados en esta capa
   divContenido.innerHTML = ajax.responseText
  }
 }
 //como hacemos uso del metodo GET
 //colocamos null ya que enviamos 
 //el valor por la url ?pag=nropagina
 ajax.send(null)
}
