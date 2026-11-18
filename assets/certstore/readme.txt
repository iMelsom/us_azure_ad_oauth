Use the following command to generate selfsigne cert: 
openssl req -x509 -newkey rsa:4096 -keyout privatekey.pem -out certificate.crt -sha256 -days 365 -nodes

Upload cert.pem to Entra ID app registration certificates and secrets
Add both certificate.crt and privatekey.pem to entra ID plugin config in Userspice installation 
Use the following path: 
certstore/