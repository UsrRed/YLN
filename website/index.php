<?php include('/home/includes/header.php'); ?>


<body class="bg-light">
<div class="container mt-5">
        <?php afficher_etat(); ?>
    <form method="post" action="/trait_comparaison">
        <!--Pour envoyer les données du formulaire vers la page de traitement de comparaison -->

        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Comparaison 1" name="comparaison1"
                       id="comparaison1">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Comparaison 2" name="comparaison2"
                       id="comparaison2">
            </div>
        </div>

        <div class="row mt-3 ">
            <div class="col-md-12 text-center">
                VS
            </div>
        </div>
        <div class=" row mt-3">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Go</button>
            </div>
        </div>
    </form>
</div>
</body>