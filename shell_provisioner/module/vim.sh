#!/bin/bash

# Vim

apt-get install -y vim

cat << EOF >/etc/vim/vimrc.local
syntax on
set expandtab
set tabstop=4
set number
EOF

update-alternatives --set editor /usr/bin/vim.basic
