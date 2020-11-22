#!/bin/bash

# Download and configure geoip db (if you know where to download this in a proper way, please let me know)

mkdir -p /usr/local/share/GeoIP
curl -s https://www.secretsantaorganizer.com/GeoLite2-City.mmdb > /usr/local/share/GeoIP/GeoLite2-City.mmdb
