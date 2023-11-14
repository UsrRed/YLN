#!/bin/bash

if [ $# -ne 0 ]; then #Si le nombre n'arguments passés n'est pas de 0, message d'information
	echo "Ce scipt ne prend pas de paramètre, il doit être exécuté sans paramètres"
	exit 1
fi

#Pour installer podman en fonction du SE du de l'utilisateur

installer_podman() {

	if [ -x "$(command -v apt-get)" ]; then
		sudo apt-get update 
		sudo apt-get install -y podman 
	elif [ -x "$(command -v dnf)" ]; then
		sudo dnf install -y podman 
	elif [ -x "$(command -v yum)" ]; then
		sudo yum install -y podman 
	else
		echo "Système d'exploitation inconnu"
		exit 1
		fi
}

#Pour installer podman-compose

installer_podman_compose() {

	if [ -x "$(command -v apt-get)" ]; then
		sudo apt-get install -y podman-compose 
	elif [ -x "$(command -v dnf)" ]; then
		sudo dnf install -y podman-compose 
	elif [ -x "$(command -v yum)" ]; then
		sudo yum install -y podman-compose  
	else
		echo "Système d'exploitation non pris en charge."
		exit 1
	fi
}

#Pour récupérer des images docker.io avec podman

pull_images() {

	#if podman images | grep "docker.io/library/nginx" && podman images | grep "docker.io/library/php" && podman images | grep "docker.io/library/mysql" && podman images | grep "docker.io/library/haproxy"; then
		#echo "Images des conteneurs présentes sur l'OS physique"
	#else
	if podman images | grep 'docker.io/library/nginx.*alpine' && podman images | grep 'docker.io/library/php.*8.2-fpm' && podman images | grep 'docker.io/library/mysql.*latest' && podman images | grep 'docker.io/library/haproxy.*alpine'; then
	echo "Les images sont déjà installés"
	
	else	
		echo "Pulling des images pour le lancement des conteneurs.."

			podman pull docker.io/library/mysql:latest
			podman pull docker.io/library/php:8.2-fpm
			podman pull docker.io/library/nginx:alpine
			podman pull docker.io/library/haproxy:alpine
	fi
}

#On démarre les conteneurs avec podman-compose

demarrage_conteneurs() {
  
	podman-compose -f docker-compose.yaml up -d

}

#On affiche l'adresse IP du conteneur Haproxy donc l'adresse IP de l'application

ip() {

	AdresseIP=$(podman inspect haproxy | grep -oP '"IPAddress": "\K[^"]+') 
	echo "--> L'adresse IP de l'application sur laquelle se rendre est https://$AdresseIP:8443"
	echo "--> Vous pouvez faire un CTRL + [clique gauche] sur l'URL ci-dessus."

}

#Main

main() {
  
	if ! command -v podman &> /dev/null; then
		echo "Installation de podman..."
		installer_podman
	else
		echo "podman est déjà installé."
	fi

	if ! command -v podman-compose &> /dev/null; then
		echo "Installation de podman-compose..."
		installer_podman_compose
	else
		echo "podman-compose est déjà installé."
	fi

	echo "Récupérations des images docker.io avec podman..."
	pull_images

	echo "Démarrage les conteneurs avec podman-compose..."
	demarrage_conteneurs

	echo "Affichage de l'adresse IP..."
	ip

}

main
