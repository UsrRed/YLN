<!--Source de la POPUP :  https://getbootstrap.com/docs/4.0/components/modal/ -->

<!DOCTYPE html>
<html>

<head>
	<title>SAE501-502-THEOTIME-MARTEL</title>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="logo.jpg" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
	<!-- Pour emêcher de fermet la POPUP-->
	<script>
		$(document).ready(function () {
			$('#myModal').modal({
				backdrop: 'static', /* On empêche de cliquer en dehors de la modale pour la fermer*/
				keyboard: false /*Pareil mais avec le clavier styme touche échape */
			});

			/*Si l'utilisateur clique sur le bouton il est redirigé*/

			$('.modal-backdrop').on('click', function () { 
				window.location.href = '/accueil';
			});
		});
	</script>

	<!-- POPUP -->
	<div class="modal" id="myModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Bienvenue sur notre application de comparaison</h5>
					<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
				</div>
				<div class="modal-body">
					<p>Cette application permet de comparer deux entités en fonction des statistiques de Wikipédia !</p>
					<p>Par Lukas. T, Yohann. D, Nathan. M</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="window.location.href='/accueil'">C'est parti</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
