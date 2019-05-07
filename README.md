# IOF worldwide volunteering platform

Requirements:
- PHP 7.2
- MySQL

### Installation

1. mkdir iofvolunteers
2. git clone https://github.com/vasyaabr/iofvolunteers.git iofvolunteers
3. cd iofvolunteers
4. composer install

### Settings

To configure database connection, change **classes/Config.php** constants. On 'DEV' environment **sqlite3** driver is used, 
on 'PROD' environment - **MySQL** driver.

### Testing

Local run: `php -S localhost:8000` 

### Used packages

- Fastroute
- Twig