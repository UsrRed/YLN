#!/bin/bash

# Fonction pour installer Podman
install_podman() {
  if [ -x "$(command -v apt-get)" ]; then
    sudo apt-get update || { echo "Erreur lors de la mise à jour"; exit 1; }
    sudo apt-get install -y podman || { echo "Erreur lors de l'installation de Podman"; exit 1; }
  elif [ -x "$(command -v dnf)" ]; then
    sudo dnf install -y podman || { echo "Erreur lors de l'installation de Podman"; exit 1; }
  elif [ -x "$(command -v yum)" ]; then
    sudo yum install -y podman || { echo "Erreur lors de l'installation de Podman"; exit 1; }
  else
    echo "Système d'exploitation non pris en charge."
    exit 1
  fi
}

# Fonction pour installer Podman-compose
install_podman_compose() {
  if [ -x "$(command -v apt-get)" ]; then
    sudo apt-get install -y podman-compose || { echo "Erreur lors de l'installation de Podman-compose"; exit 1; }
  elif [ -x "$(command -v dnf)" ]; then
    sudo dnf install -y podman-compose || { echo "Erreur lors de l'installation de Podman-compose"; exit 1; }
  elif [ -x "$(command -v yum)" ]; then
    sudo yum install -y podman-compose || { echo "Erreur lors de l'installation de Podman-compose"; exit 1; }
  else
    echo "Système d'exploitation non pris en charge."
    exit 1
  fi
}

# Fonction pour tirer des images Docker avec Podman
pull_docker_images() {
  # Tirer des images Docker
  podman pull docker.io/library/mysql:latest || { echo "Erreur lors du tirage de l'image MySQL"; exit 1; }
  podman pull docker.io/library/php:8.2-fpm || { echo "Erreur lors du tirage de l'image PHP"; exit 1; }
  podman pull docker.io/library/nginx:alpine || { echo "Erreur lors du tirage de l'image Nginx"; exit 1; }
  podman pull docker.io/library/haproxy:alpine || { echo "Erreur lors du tirage de l'image HAProxy"; exit 1; }
}

# Fonction pour démarrer les conteneurs avec Podman-compose
start_containers() {
  podman-compose up -d || { echo "Erreur lors du démarrage des conteneurs avec Podman-compose"; exit 1; }
}

# Fonction pour afficher l'adresse IP
display_ip() {
  AdresseIP=$(podman inspect haproxy | grep -oP '"IPAddress": "\K[^"]+') || { echo "Erreur lors de la récupération de l'adresse IP"; exit 1; }
  echo "--> L'adresse IP de l'application sur laquelle se rendre est https://$AdresseIP:8443"
  echo "--> Vous pouvez faire un CTRL + [clique gauche] sur l'URL ci-dessus."
}

# Main function
main() {
  # Vérifier si Podman est installé, sinon l'installer
  if ! command -v podman &> /dev/null; then
    echo "Installation de Podman..."
    install_podman
  else
    echo "Podman est déjà installé."
  fi

  # Vérifier si Podman-compose est installé, sinon l'installer
  if ! command -v podman-compose &> /dev/null; then
    echo "Installation de Podman-compose..."
    install_podman_compose
  else
    echo "Podman-compose est déjà installé."
  fi

  # Tirer des images Docker avec Podman
  echo "Tirer des images Docker avec Podman..."
  pull_docker_images

  # Démarrer les conteneurs avec Podman-compose
  echo "Démarrer les conteneurs avec Podman-compose..."
  start_containers

  # Afficher l'adresse IP
  echo "Affichage de l'adresse IP..."
  display_ip

  echo "Installation, démarrage et affichage de l'adresse IP terminés."
}

# Appeler la fonction principale
main
