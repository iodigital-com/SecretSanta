#!/bin/bash

# Vim

apt-get install -y vim

cat << EOF >/etc/vim/vimrc.local
let skip_defaults_vim=1
syntax on
set expandtab
set tabstop=4
set number
EOF

update-alternatives --set editor /usr/bin/vim.basic
