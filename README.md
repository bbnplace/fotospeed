# SERVER CREDENTIALS
portal.hostgator.com
username: bomymavathra@yahoo.com
password: Indigo@14

# SHARED HOSTING PLAN
Plan: Baby
Control Panel: https://gator821.hostgator.com:2083
Username: indigoph
Domain: indigophotobook.com
Password: q2cUi[cJgxd3
Nameserver 1: ns1641.hostgator.com
Nameserver 2: ns1642.hostgator.com
Server IP: 174.120.9.194

Email: issac.antony@yahoo.com

DB: https://database.indigoafrica.net/phpmyadmin
Username: indigo
Password: Indigo@14

AWS
signin.aws.amazon.com
Username: issac.antony@yahoo.com
Password: indigo@14

# DIGITALOCEAN DROPLET
Host: 161.35.73.28
Username: root
password: Code2024Lab

# Database
CREATE USER 'booker'@'localhost' IDENTIFIED WITH mysql_native_password BY '9cT@6g#Wx2*pY$Eh';
GRANT ALL PRIVILEGES ON indigo.* TO 'booker'@'localhost';
FLUSH PRIVILEGES;



# Creating Models
php artisan make:model Product -m
php artisan make:model Order -m
php artisan make:model State -m
php artisan make:model Group -m
php artisan make:model Branch -m
php artisan make:model Category -m
php artisan make:model SmsTemplate -m
php artisan make:model EmailTemplate -m
php artisan make:model Login -m
php artisan make:model Item -m
php artisan make:model Report -m
php artisan make:model DailyReport -m
php artisan make:model MonthlyReport -m
php artisan make:model YearlyReport -m

php artisan make:seeder StateSeeder
php artisan make:seeder UserSeeder
# indigo-oms
