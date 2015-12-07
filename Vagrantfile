# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

module OS
    def OS.windows?
        (/cygwin|mswin|mingw|bccwin|wince|emx/ =~ RUBY_PLATFORM) != nil
    end
end

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

#        is_windows_host = "#{OS.windows?}"
#        puts "is_windows_host: #{OS.windows?}"

        # Shared folder over NFS unless Windows
        if OS.windows?
            secretsanta_config.vm.synced_folder ".", "/vagrant"
        else
            secretsanta_config.vm.synced_folder ".", "/vagrant", type: "nfs", mount_options: ['rw', 'vers=3', 'tcp', 'fsc' ,'actimeo=2']
        end

        #secretsanta_config.vm.synced_folder ".", "/vagrant", type: "rsync", rsync__exclude: [".git/", "web/bundles/", "app/cache", "app/logs"]

        secretsanta_config.vm.network "private_network", ip: "192.168.33.10"

        # Shell provisioning
        secretsanta_config.vm.provision :shell, :path => "shell_provisioner/run.sh"
    end
end
