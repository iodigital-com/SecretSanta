# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.define :secretsanta do |secretsanta_config|
        secretsanta_config.vm.box = "Debian75"
	secretsanta_config.vm.box_url = "http://ctors.net/vagrant/Debian75.box"
        secretsanta_config.vm.provider "virtualbox" do |v|
            # show a display for easy debugging
            v.gui = false

            # RAM size
            v.memory = 2048

            # Allow symlinks on the shared folder
            v.customize ["setextradata", :id, "VBoxInternal2/SharedFoldersEnableSymlinksCreate/v-root", "1"]
        end

        # allow external connections to the machine
        #secretsanta_config.vm.network "forwarded_port", guest: 80, host: 8888

        # Shared folder over NFS
        secretsanta_config.vm.synced_folder ".", "/vagrant", type: "nfs"

        secretsanta_config.vm.network "private_network", ip: "192.168.33.10"

        # Shell provisioning
        secretsanta_config.vm.provision :shell, :path => "shell_provisioner/run.sh"
    end
end
