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

    if (client.ip ~ hash_backend()) {
        set req.backend_hint = nginx1;
    } else {
        set req.backend_hint = nginx2;
    }
}

sub vcl_backend_response {

}

sub vcl_deliver {
 
}

