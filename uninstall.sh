#!/bin/bash

if [ $# -ne 0 ]; then
	echo "Ce script ne prend pas de paramètre, il doit être exécuté sans paramètres"
	exit 1
fi

echo "__   ___     _   _   _   _ _   _ ___ _   _ ____ _____  _    _     _     "
echo "\ \ / / |   | \ | | | | | | \ | |_ _| \ | / ___|_   _|/ \  | |   | |    "
echo " \ V /| |   |  \| | | | | |  \| || ||  \| \___ \ | | / _ \ | |   | |    "
echo "  | | | |___| |\  | | |_| | |\  || || |\  |___) || |/ ___ \| |___| |___ "
echo "  |_| |_____|_| \_|  \___/|_| \_|___|_| \_|____/ |_/_/   \_\_____|_____|"

choix_utilisateur=$(cat recuperation/choix.txt)

if [ "$choix_utilisateur" = "sans" ]; then
	#Application sans gestion de logs

	podman-compose -f docker-compose-sans.yaml down
	
	echo "Voulez-vous supprimer les images des conteneurs utilisées pour l'applicatino YLN ? (O/N)"
	read reponse_image_sans
	if [ "$reponse_image_sans" = "O" ]; then
    		#podman rmi docker.io/library/mysql:latest
		podman rmi -f docker.io/library/php:8.2-fpm
		podman rmi -f localhost/sae501-502-theotime-martel_php
		podman rmi -f docker.io/library/nginx:alpine
		podman rmi -f docker.io/library/haproxy:alpine
		podman rmi -f localhost/sae501-502-theotime-martel_haproxy
		podman rmi -f docker.io/library/mysql:latest
		podman rmi -f docker.io/portainer/portainer-ce:latest
		podman rmi -f docker.io/library/netdata/netdata
		echo "Images supprimées avec succès."

	elif [ "$reponse_image_sans" = "N" ]; then
		echo "Aucune image n'a été supprimée."

	else
		echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."
	fi

	#clear aussi le /etc/hosts mais que les lignes qu'on veut

elif [ "$choix_utilisateur" = "syslog" ]; then
	#Application avec gestion des logs avec le conteneur syslog-ng
	
	podman-compose -f docker-compose-syslog.yaml down

        echo "Voulez-vous supprimer les images des conteneurs utilisées pour l'applicatino YLN ? (O/N)"
        read reponse_image_syslog
        if [ "$reponse_image_syslog" = "O" ]; then
                #podman rmi docker.io/library/mysql:latest
                podman rmi -f docker.io/library/php:8.2-fpm
                podman rmi -f localhost/sae501-502-theotime-martel_php
		podman rmi -f docker.io/library/nginx:alpine
                podman rmi -f docker.io/library/haproxy:alpine
                podman rmi -f localhost/sae501-502-theotime-martel_haproxy
		podman rmi -f docker.io/library/mysql:latest
		podman rmi -f docker.io/portainer/portainer-ce:latest
                podman rmi -f docker.io/netdata/netdata
		podman rmi -f balabit/syslog-ng:latest

                echo "Images supprimées avec succès."

        elif [ "$reponse_image_syslog" = "N" ]; then
                echo "Aucune image n'a été supprimée."

        else
                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."
        fi

	#mv /etc/containers/registries.conf syslog/
	rm -rf /etc/containers/registries.conf
	mv recuperation/registries.conf /etc/containers/registries.conf

	#revoir pour le rsyslog s'il est installé ou non quand on écrase la config
	#revoir les mv
	#remove le rsyslog
	#/etc/hosts


elif [ "$choix_utilisateur" = "grafana" ]; then
	#Application avec gestion des logs avec la stack PLG
	
	podman stop $(podman ps)
        podman rm -f $(podman ps -aq)
	sleep 1
	podman rm -f $(podman ps -aq)

        echo "Voulez-vous supprimer les images des conteneurs utilisées pour l'applicatino YLN ? (O/N)"
        read reponse_image_plg
        if [ "$reponse_image_plg" = "O" ]; then
                podman rmi docker.io/library/mysql:latest
                podman rmi docker.io/library/php:8.2-fpm
                podman rmi docker.io/library/nginx:alpine
                podman rmi docker.io/library/haproxy:alpine
                podman rmi docker.io/portainer/portainer-ce:latest
                podman rmi docker.io/library/netdata:latest
		podman rmi grafana/grafana
		podman rmi grafana/loki		
		podman rmi grafana/promtail

                echo "Images supprimées avec succès."

        elif [ "$reponse_image_plg" = "N" ]; then
                echo "Aucune image n'a été supprimée."

        else
                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."
        fi

	#remiove /etc/hosts


elif [ -z "$choix_utilisateur" ]; then
	echo "L'application n'a pas été lancée, pour la lancer, exécutez le script setup.sh avec la commande suivante : sudo bash setup.sh"

else
	echo "Problème sur la capacité à savoir quelle application a été lancée" 
fi


echo "supprimer les volumes ?"
echo "supprimer le réseau sae ?"

