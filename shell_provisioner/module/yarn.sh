#!/bin/bash

# Nodejs

curl -sL https://deb.nodesource.com/setup_8.x | bash -
apt install -y nodejs

# Yarn

curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

apt update
apt install -y yarn

