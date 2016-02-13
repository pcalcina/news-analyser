# News Analyser
Web tool for tagging news

It uses [CakePHP](http://cakephp.org/)

## Requirements
* Apache
* PHP
* Sqlite

## Installation

1. Clone this repository (`git clone https://github.com/pcalmtools/news-analyser.git`)

2. Put project under Apache directory (ex. /var/www/html/)

3. Try in browser: `http://localhost/news-analyser/index.php/news`.
   Create a user if needed.

4. Make sure directory <PROJECT_DIRECTORY>app/tmp is writable (`chmod a+rw app/tmp`)

5. Download database file
(`cd app/webroot && wget http://www.ime.usp.br/~pcalcina/protestos.sqlite`)

6. Make sure protestos.sqlite has *read* and *write* permissions (`chmod a+rw protestos.sqlite`)
