<?php 
require 'Clothes.php';
$indexName = "WomenClothes";
$objClothes = new Clothes($indexName);
$infoClothes = $objClothes->getInformationClothes("dress", 10);
?>
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
      <table class="table table-striped">
        <thead> 
          <tr> 
            <th>Name</th> 
            <th>Price</th> 
            <th>Designer</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($infoClothes as $clothes): ?>
            <tr>
              <th>
                <?php echo $clothes["name"]; ?>  
              </th>
              <td>
                <?php echo $clothes["price"]; ?>
              </td>
              <td>
                <?php echo $clothes["designer"]; ?>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <h1>Escenario 2 
        <small>Desplegar en una pagina de HTML en formato texto, 
          el nombre ("name") de las 5 prendas de cualquier consulta 
          que haga el usuario a través de un campo de texto que 
          estará en la misma página.</small></h1>
      
      <form>
        <h2>Nombre prenda (inglés)</h2>
        <input type="text" class="form-control" onkeyup="showResult(this.value)">       
        <div id="livesearch"></div>
      </form>

      <br><br><br><br><br>

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
              document.getElementById("livesearch").style.border="1px solid #A5ACB2";
            }
          }

          xmlhttp.open("GET","livesearch.php?question="+str,true);
          xmlhttp.send();
       }
      </script>
  </body>
</html>