# secretsanta# Setup

Run ```lando start``` to get your project up and running.

If you apply changes to the .lando.yml file it is recommended to run ```lando rebuild```.

## Security certificates

If this is your first time running Lando, add security certificates for Lando projects with the following command(s).

### Mac

```
sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ~/.lando/certs/lndo.site.pem
```

### Windows
Remember to replace `ME` with your username.
```
certutil -addstore -f "ROOT" C:\Users\ME\.lando\certs\lndo.site.pem
```

### Linux

```
sudo cp -r ~/.lando/certs/lndo.site.pem /usr/local/share/ca-certificates/lndo.site.pem
sudo cp -r ~/.lando/certs/lndo.site.crt /usr/local/share/ca-certificates/lndo.site.crt
sudo update-ca-certificates
```

For more information, visit [Docker on Confluence](https://confluence.hosted-tools.com/display/HRT/Docker).