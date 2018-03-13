# STudent EVent Manager

Built for the "Φοιτητική Εβδομάδα" festival in the Aristotle University of Thessaloniki, but can be reused for other festivals too.

## Usage
### Installation
```ruby
# Install rvm
xdg-open https://rvm.io/rvm/install

# Select latest ruby version
rvm list known
rvm install ruby-2.5

# Install passenger
xdg-open https://www.phusionpassenger.com/library/config/apache/intro.html
gem install passenger --no-rdoc --no-ri
passenger-install-apache2-module

# Configure apache
sudo nano /etc/apache2/sites-available/your_website.conf
sudo a2enmod passenger
sudo a2ensite your_website
sudo service apache2 restart

# Install bundler
gem bundler install

# Install gems
bundle

# Migrate database
RAILS_ENV=production bin/rails db:migrate
```

#### Adding a new user
```bash
RAILS_ENV=production bin/rails console
```

```ruby
User.new(email: "mail@example.com", password: "YoUrPaSsWoRd").add
```
### Ruby & Gems Update
```ruby
# Update rvm
rvm get head

# Update ruby
rvm list known
rvm use ruby-2.5

# Update gems
gem update --no-rdoc --no-ri

# Update passenger
passenger-install-apache2-module
```
### Importing data with CSV
Ideal format: comma-separated, key-valued, full options, separate
