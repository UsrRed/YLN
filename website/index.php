<!DOCTYPE html>
<html>
<head>
    <title>SAE501-502-THEOTIME-MARTEL</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Pour avoir bootstrap version 4.5.2 : https://getbootstrap.com/docs/4.5/getting-started/introduction/-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body class="bg-light">
<?php 
        include('./includes/header.php'); 
?>
<div class ="container mt-5">
<form method="post" action="./Pages/traitement/trait_comparaison.php"> <!--Pour envoyer les données du formulaire vers la page de traitement de comparaison -->

<div class="row">
          <div class = "col-md-6">
                <input type="text" class="form-control" placeholder="Comparaison 1" name="comparaison1" id="comparaison1"> 
           </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Comparaison 2" name="comparaison2" id="comparaison2">
            </div>
       </div>
        <div class="row mt-3 ">
            <div class="col-md-12 text-center">
VSSSSSSSSSSSSSSSSS
           </div>
        </div>
       <div class=" row mt-3">
            <div class = "col-md-12 text-center">
                <button type="submit" class = "btn btn-danger">Go</button>
            </div>
</div>
</form>
</div>
</body>
</html>

