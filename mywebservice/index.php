<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title> Get clothes </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <style>
      #livesearch {
        color:red;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <h1>Escenario 1 <small>La consulta es "dress". </small></h1>
      
      <form>
        <h2>Nombre prenda (inglés)</h2>
        <input type="text" class="form-control" onkeyup="showResult(this.value)">
        <br><br><br>
        <div id="livesearch"></div>
      </form>
    </div>    

     <script>
      /* 
        Next code is based:
        http://www.tutorialspoint.com/php/php_ajax_autocomplete_search.htm
      */
        function showResult(str) {
          if (str.length==0) {
            document.getElementById("livesearch").innerHTML="";
            document.getElementById("livesearch").style.border="0px";
            return;
          }
          
          if (window.XMLHttpRequest) {
            xmlhttp=new XMLHttpRequest();
          }
          else
          {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
          }
          
          xmlhttp.onreadystatechange=function() {
      
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
            }
          }

          xmlhttp.open("GET","getItems/?clothes="+str,true);
          xmlhttp.send();
       }
      </script>
  </body>
</html>