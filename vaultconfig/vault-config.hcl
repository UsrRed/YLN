listener "tcp" {
  address       = "[::]:8200"
  tls_cert_file  = "/vault/tls/server.crt"
  tls_key_file   = "/vault/tls/server.key"
}
