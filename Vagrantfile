# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
  config.vm.box = "ctors_squeeze64_2012-11-15"
  config.vm.box_url = "http://ctors.net/squeeze64_2012_11_15.box"

  # Use :gui for showing a display for easy debugging of vagrant
  # config.vm.boot_mode = :gui

  # Some VirtualBoxes seem to need this
  config.vm.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]

  config.vm.define :secretsanta do |secretsanta_config|
    secretsanta_config.vm.host_name = "www.secretsanta.dev"

    secretsanta_config.vm.network :hostonly, "33.33.33.10"

    # Pass custom arguments to VBoxManage before booting VM
    #secretsanta_config.vm.customize [
    #  'modifyvm', :id, '--chipset', 'ich9', # solves kernel panic issue on some host machines
    #]

    # Pass installation procedure over to Puppet (see `puppet/manifests/secretsanta.pp`)
    secretsanta_config.vm.provision :puppet do |puppet|
      puppet.manifests_path = "puppet/manifests"
      puppet.module_path = "puppet/modules"
      puppet.manifest_file = "secretsanta.pp"
      puppet.options = [
        '--verbose',
        # '--debug',
      ]
    end
  end
end
