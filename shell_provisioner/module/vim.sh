#!/bin/bash

# Vim

apt-get install -y vim

cat << EOF >/etc/vim/vimrc.local
syntax on
set expandtab
set ts=4
set number
"set cursorline
"set cursorcolumn
highlight CursorLine ctermbg=lightgray
EOF

update-alternatives --set editor /usr/bin/vim.basic
