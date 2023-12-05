<img src="https://i.ibb.co/FKmqkwv/Toolbox-Original-with-word.png" alt="Logo" width="300">

## Installation

1. Follow LAMP server installation **[here](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-22-04)**.

2. After installing MySQL change the root password.
```bash
sudo mysql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password by 'mynewpassword';
```

3. After changing root password, run MySQL Secure Installation.
```bash
sudo mysql_secure_installation
```

4. Best to create your own account on MySQL.
```bash
mysql -u root -p
CREATE USER 'new_user'@'localhost' IDENTIFIED BY 'new_password';
```

5. Continue rest of installation from the **[first link](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-22-04)**.

6. Install Composer.
```bash
sudo apt install composer
```

7. Install Laravel.
```bash
composer global require laravel/installer
```

8. Add Laravel path to .bashrc. and reboot server.
```bash
#Laravel Path
export PATH="~/.config/composer/vendor/bin:$PATH"
```

9. After rebooting, check if Laravel is working by running command on CLI.
```bash
laravel
```

10. Install NodeJS via NVM (curl).
```bash
https://github.com/nvm-sh/nvm
sudo apt install curl
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
reboot terminal
nvm install --lts
```

11. Install PHPMYADMIN.
```bash
sudo apt install phpmyadmin
tick "apache2"
```

12. Generate SSH Key using terminal.
```bash
ssh-keygen -t rsa -b 4096 -C "username@domain.com"
```
    A. Go to Bitbucket settings.  
    B. Personal Bitbucket settings.  
    C. Under security, click SSH Keys.  
    D. Add the public key.

13. Set folder ownership before cloning.
```bash
sudo chown -R $USER:$USER /var/www/folder
sudo chmod -R 755 /var/www/folder
```

14. Clone Toolbox.
```bash
git clone git@bitbucket.org:teamspan-global-solutions/toolbox.git
```

15. Run composer update to update all assets.
```bash
composer update
```

16. Grant full access to Toolbox database.
```bash
GRANT ALL ON toolbox_dispatch.* TO 'new_user'@'localhost';
```

17. Migrate Toolbox database.
```bash
php artisan migrate
```

18. Create a test user for Toolbox.
```bash
php artisan nova:user
```

19. For Toolbox testing.
```bash
php artisan serve --host=your_server_ip_address
```



## SAMBA Installation

You may use SAMBA to manage your files locally on your device.

1. Install SAMBA.
```bash
sudo apt install samba
```

2. Create a SAMBA user.
```bash
sudo smbpasswd -a user
```

3. Add the ff. lines on SAMBA configuration.
```bash
sudo nano /etc/samba/smb.conf

#Toolbox Directory
[teamspan-toolbox]
   comment = Toolbox directory
   path = /var/www/html/toolbox
   read only = no
   browsable = yes
```

4. Restart SAMBA service.
```bash
sudo service smbd restart
```

5. You should now be able to map Toolbox on your local drive.
