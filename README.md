# IOF worldwide volunteering platform

### Requirements

- PHP 7.1+
- MySQL 5.7+

### Installation

1. git clone https://github.com/vasyaabr/iofvolunteers.git (or just unzip archive with code)
2. cd iofvolunteers
3. composer install
4. Copy **config_example.php** as **config.php**
5. Configure database connection in **config.php** constants
6. Run `install.php` in core directory

### Project structure

```bash
├── controllers - PHP code
├── css - stylesheets
├── db - database init queries, which runs from install.php
├── images - graphics
├── templates - html templates
├── composer.json
├── composer.lock
├── config_example.php - site config (mysql credentials)
├── index.php
├── install.php
└── README.md - this file
```

### Testing

Local run: `php -S localhost:8000` 
