Library management built with Laravel 5.1.*

## Installation Instructions

1. Download and install VirtualBox and Vagrant

   **Vagrant** - Contains VM installation instructions to closely mimic the production box
   **VirtualBox** - Allows us to run a VM on our local machine

   This version of VirtualBox and Vagrant works well on Mac OS X El Capitan Version 10.11.6. Newer versions might work too as long as both VirtualBox and Vagrant are compatible to each other.

   - VirtualBox: Version 5.0.18 http://download.virtualbox.org/virtualbox/5.0.18/VirtualBox-5.0.18-106667-OSX.dmg
   - Vagrant: Version 1.8.1 https://releases.hashicorp.com/vagrant/1.8.1/vagrant_1.8.1.dmg

2. Go to project root and type `vagrant up`

   ```bash
   $ cd Documents/webapps/laravel-library
   $ vagrant up
   ```

3. Vagrant will now begin setting up on your machine based on instructions on the Vagrantfile
  - Setting up headless VM on VirtualBox
  - Install required software on the VM based on `deploy/vagrant/build.sh`
  - Creates database, run migration and seeder, etc. as per build.sh above

4. Setup should now be completed with this message

   ```bash
   Done, rebooting
   System reboot successful.
   SSH to vagrant and run 'sudo /etc/init.d/vboxadd setup'. Then, 'vagrant reload' on Terminal.
   ```

5. Type `vagrant ssh` to SSH into the VM

   ```bash
   $ vagrant ssh
   ```

6. Type `sudo /etc/init.d/vboxadd setup` to update the Guest Additions. This is required to allow VirtualBox access to the local project root folder

   ```bash
   $ sudo /etc/init.d/vboxadd setup
   ```

7. Exit the VM and type `vagrant reload` to reload the VM with the newly installed Guest Additions

   ```bash
   $ vagrant reload
   ```

   SSH into vagrant to confirm that you are now able to access the local folder from VM

   ```bash
   $ vagrant ssh
   ...
   $ cd /var/www/laravel-library
   $ ls -l
   ```

   Above should list all the files as per your local project root folder

8. run makedb.sh to update sandbox account & sample books
    ```bash
       $ ./makedb.sh
       ```

9. Install application specific bower components
    ```bash
       $ bower install
    ```

*Enjoy!*

## Author

For any issues with installation or getting this to work, send an email to: [mail2asik@gmail.com](mailto:mail2asik@gmail.com)
