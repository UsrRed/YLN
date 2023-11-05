#!/bin/bash


expediteur="ayressios@gmail.com"

destinataire="nathan.martel@etu.univ-tours.fr"

serveur="smtp.gmail.com"

port="587"

swaks -t "$destinataire" -f "expediteur" --header 'Subject: test' --body 'test' --auth LOGIN --auth-user ayressios@gmail.com --auth-password 'suey uktj coym cbrs' -s "$serveur":"$port" --tls
