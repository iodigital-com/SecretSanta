wget -O /tmp/google-chrome-stable_current_amd64.deb https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
apt install -y /tmp/google-chrome-stable_current_amd64.deb
rm /tmp/google-chrome-stable_current_amd64.deb

version=$(curl -s https://chromedriver.storage.googleapis.com/LATEST_RELEASE)
wget -O /tmp/chromedriver_linux64.zip https://chromedriver.storage.googleapis.com/${version}/chromedriver_linux64.zip
unzip -o /tmp/chromedriver_linux64.zip -d /usr/local/bin