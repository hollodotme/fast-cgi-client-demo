# Fast CGI Client Demo

This is a tiny demo application to show the basic concept of using PHP-FPM background workers with 
**[hollodotme/fast-cgi-client](https://github.com/hollodotme/fast-cgi-client)**

Please visit the project site for further documentation.

## Running the demos:

### Get environment up and running

#### On host machine

```bash
# Make folders writable for vagrant
chmod -R 0777 build/logs public/documents

# Start the vagrant box
# The provisioning can take a little while
vagrant up

# Log into vagrant box
vagrant ssh
```

#### On guest machine

```bash
# Go to project dir
cd /vagrant

# Update composer
sudo composer self-update

# Install dependencies
composer update -o -v
```

Browse to http://demo.fast-cgi-client.de
