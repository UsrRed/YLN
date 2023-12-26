# default.vcl

vcl 4.0;

backend nginx1 {
    .host = "nginx1";
    .port = "8089";
}

backend nginx2 {
    .host = "nginx2";
    .port = "8089";
}

sub vcl_recv {
    # Choix du backend en fonction de la logique de votre application
    if (client.ip ~ hash_backend()) {
        set req.backend_hint = nginx1;
    } else {
        set req.backend_hint = nginx2;
    }
}

sub vcl_backend_response {
    # Ajoutez ici des règles personnalisées pour le traitement des réponses du backend
}

sub vcl_deliver {
    # Ajoutez ici des règles personnalisées pour le contenu livré au client
}

