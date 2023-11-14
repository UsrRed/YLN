#!/bin/bash

# Fonction pour installer Podman
install_podman() {
  if [ -x "$(command -v apt-get)" ]; then
    sudo apt-get update
    sudo apt-get install -y podman
  elif [ -x "$(command -v dnf)" ]; then
    sudo dnf install -y podman
  elif [ -x "$(command -v yum)" ]; then
    sudo yum install -y podman
  else
    echo "Système d'exploitation non pris en charge."
    exit 1
  fi
}

# Fonction pour installer Podman-compose
install_podman_compose() {
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

# Fonction pour tirer des images Docker avec Podman
pull_docker_images() {
  # Tirer des images Docker
  podman pull docker.io/library/mysql:latest
  podman pull docker.io/library/php:8.2-fpm
  podman pull docker.io/library/nginx:alpine
  podman pull docker.io/library/haproxy:alpine
}

# Fonction pour démarrer les conteneurs avec Podman-compose
start_containers() {
  podman-compose up -d
}

# Fonction pour afficher l'adresse IP
display_ip() {
  AdresseIP=$(podman inspect haproxy | grep -oP '"IPAddress": "\K[^"]+')
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
  display_ip

  echo "Installation, démarrage et affichage de l'adresse IP terminés."
}

# Appeler la fonction principale
main