<?php include('/home/includes/header.php'); 

if (!isset($_SESSION['nb_accueil'])) {
    $_SESSION['nb_accueil'] = 0;
}
$_SESSION['nb_accueil']++;

?>

<body class="bg-light">

<div class="container mt-5">
        <?php afficher_etat(); ?>
    <form method="post" action="/trait_comparaison">
        <!--Pour envoyer les données du formulaire vers la page de traitement de comparaison -->

        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Comparaison 1" name="comparaison1"
                       id="comparaison1" required>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Comparaison 2" name="comparaison2"
                       id="comparaison2" required>
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

<!--Pour focer à ce que ce soit en bas -->

<style>
    body {
	display: flex;
	flex-direction: column;
	min-height: 100vh;     
	cursor: crosshair;
	}

    .container {
	flex: 1; 
	}

    .footer {
        background-color: #f8f9fa;
        padding: 20px 0;
        position: relative;
        bottom: 0;
        width: 100%;
    }
</style>

<footer class="footer bg-secondary">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-start"> <!-- Aligné à gauche -->
                
                    
                        <img src="logo.jpg" style="max-width: 90px; height: 90px;">
                    
                
            </div>
            <div class="col-md-4 text-center d-flex align-items-center"> <!-- Centré verticalement -->
                
                    
                        <div class="center" style="margin: auto;">
                            <span>&copy; Dernière MAJ : 17/11/2023</span><br/>
                            <span>Date : <?php echo date("M-Y"); ?></span>
                       </div>
                    
                
            </div>
            <div class="col-md-4" style="display: flex; flex-direction: row-reverse;"> <!-- Aligné à droite -->
                <div style="display: flex; flex-direction: column;">
                    
                        <div>
                            <a href="https://www.linkedin.com/in/yohann-denoyelle/">
                                <img src="logo_l.jpg" style="max-width: 30px; height: auto;">
                            </a>
                            <span>Yohann Denoyelle</span>
                        </div>
                        <div>
                            <a href="https://www.linkedin.com/in/lukas-th%C3%A9otime-3058bb225/">
                                <img src="logo_l.jpg" style="max-width: 30px; height: auto;">
                            </a>
                            <span>Lukas Theotime</span>
                        </div>
                        <div>
                            <a href="https://www.linkedin.com/in/nathan-martel-a62a92290/">
                                <img src="logo_l.jpg" style="max-width: 30px; height: auto;">
                            </a>
                            <span>Nathan Martel</span>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</footer>

</body>
