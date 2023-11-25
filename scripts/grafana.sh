#!/bin/bash

if [ $# -ne 0 ]; then
	echo "Ce script ne prend pas de paramètre, il doit être exécuté sans paramètres"
	exit 1
fi

podman pull grafana/grafana
podman pull grafana/loki
podman pull grafana/promtail    

echo "Images Grafana, Loki et Promtail récupérée avec succès"
