<link href="style.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="encode.js"></script>

<script type="text/javascript">

function alerta(){
  alert("hola");
}

function loadExample() {

  document.getElementById('string').value = "May 18 10:22:44 Message forwarded from SERVER01: syslog: OWNER: username ; USER: username ; PWD: /home/username ; COMMAND: sudo visudo";
  document.getElementById('p0').value = "syslog|bash";
  document.getElementById('p1').value = "(?\<=OWNER: )(?:\\w+)";
  document.getElementById('p2').value = "passwd|su+s|bash|visudo";
  document.getElementById('p3').value = "";

  setTimeout ("showP(document.getElementById('string').value,document.getElementById('p0').value,document.getElementById('p1').value,document.getElementById('p2').value,document.getElementById('p3'))", 20);

}

function clean() {

  document.getElementById('string').value = "";
  document.getElementById('p0').value = "";
  document.getElementById('p1').value = "";
  document.getElementById('p2').value = "";
  document.getElementById('p3').value = "";

  setTimeout ("showP(document.getElementById('string').value,document.getElementById('p0').value,document.getElementById('p1').value,document.getElementById('p2').value,document.getElementById('p3'))", 20);

}

function showRegex(str,regex) {


  //str = Base64.encode(str);
  //regex = Base64.encode(regex);

  str = encodeHex(str);
  regex = encodeHex(regex);

  if (str.length==0) { 
    document.getElementById("txtHint").innerHTML="";
    return;
  }

  if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }

  xmlhttp.open("GET","regex_get.php?q="+str+"&x="+regex,true);
  xmlhttp.send();
}

function showP(str,p0,p1,p2,p3) {

  str = encodeHex(str);
  p0 = encodeHex(p0);
  p1 = encodeHex(p1);
  p2 = encodeHex(p2);
  p3 = encodeHex(p3);

  //document.getElementById("debug").innerHTML="regex_get.php?q="+str+"&p0="+p0+"&p0="+p1+"&p0="+p2+"&p0="+p3;

  if (str.length==0) { 
    document.getElementById("txtHint").innerHTML="";
    return;
  }

  if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }

  xmlhttp.open("GET","regex_get.php?q="+str+"&p0="+p0+"&p1="+p1+"&p2="+p2+"&p3="+p3,true);
  xmlhttp.send();
}

</script>


<!--<p><b>Start typing a name in the input field below:</b></p>-->
<p><b>Regex Creator</b> <input type="button" value="Load Example" onclick="loadExample();"> <input type="button" value="Clean" onclick="clean();"></p>
<form> 

<table>

  <tr>
    <td valign=top>p0: </td>
    <td>
      <input type="text" style="width: 380px;" id="p0" name="p0" onkeyup="showP(document.getElementById('string').value,this.value,document.getElementById('p1').value,document.getElementById('p2').value,document.getElementById('p3').value)" size="20" value="" />
    </td>
  </tr>
  <tr>
    <td valign=top>p1: </td>
    <td>
      <input type="text" style="width: 380px;" id="p1" name="p1" onkeyup="showP(document.getElementById('string').value,document.getElementById('p0').value,this.value,document.getElementById('p2').value,document.getElementById('p3').value)" size="20" value="" />
    </td>
  </tr>
  <tr>
    <td valign=top>p2: </td>
    <td>
      <input type="text" style="width: 380px;" id="p2" name="p2" onkeyup="showP(document.getElementById('string').value,document.getElementById('p0').value,document.getElementById('p1').value,this.value,document.getElementById('p3').value)" size="20" value="" />
    </td>
  </tr>
  <tr>
    <td valign=top>p3: </td>
    <td>
        <input type="text" style="width: 380px;" id="p3" name="p3" onkeyup="showP(document.getElementById('string').value,document.getElementById('p0').value,document.getElementById('p1').value,document.getElementById('p2').value,this.value)" size="20" value="" />
    </td>
  </tr>

 <!-- <tr>
    <td><br><br></td>
    <td></td>
  </tr>-->

<!--  <tr>
    <td valign=top>regex: </td>
    <td>
      <textarea class="field" style="width: 500px;" id="regex" name="regex" onkeyup="showRegex(document.getElementById('string').value,this.value)" value="" rows="4" >(?P<p0>(syslog|bash)).+(?P<p1>(?<=OWNER: )(?:\w+)).+(?P<p2>passwd|su+s|bash|visudo)</textarea>
    </td>
  </tr>-->

  <tr>
    <td><br><br></td>
    <td></td>
  </tr>

  <tr>
    <td valign=top>log: </td>
    <td>
      <textarea class="field" style="width: 380px;" id="string" name="string" value="" rows="4" ></textarea>
    </td>
  </tr>

</table>

</form>

<p class="itemsMenu001"></p>

<p><span id="txtHint"></span></p>

