@echo off
set "PATH=C:\xampp\php;C:\xampp\node\node-v20.18.3-win-x64;%PATH%"
cd /d "%~dp0"
echo Starting HJParam Publication server at http://127.0.0.1:8000 ...
php artisan serve --port=8000
