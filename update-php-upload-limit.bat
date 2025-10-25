@echo off
echo ========================================
echo PHP Upload Configuration Update Script
echo ========================================
echo.

set PHP_INI=C:\xampp\php\php.ini

if not exist "%PHP_INI%" (
    echo ERROR: php.ini not found at %PHP_INI%
    echo Please update the path in this script
    pause
    exit /b 1
)

echo Creating backup...
copy "%PHP_INI%" "%PHP_INI%.backup.%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%" >nul
echo Backup created successfully!
echo.

echo Current settings:
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;"
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
php -r "echo 'max_execution_time: ' . ini_get('max_execution_time') . PHP_EOL;"
php -r "echo 'memory_limit: ' . ini_get('memory_limit') . PHP_EOL;"
echo.

echo Updating php.ini...
powershell -Command "(Get-Content '%PHP_INI%') -replace 'upload_max_filesize\s*=\s*\d+M', 'upload_max_filesize = 100M' | Set-Content '%PHP_INI%'"
powershell -Command "(Get-Content '%PHP_INI%') -replace 'post_max_size\s*=\s*\d+M', 'post_max_size = 100M' | Set-Content '%PHP_INI%'"
powershell -Command "(Get-Content '%PHP_INI%') -replace 'max_execution_time\s*=\s*\d+', 'max_execution_time = 300' | Set-Content '%PHP_INI%'"
powershell -Command "(Get-Content '%PHP_INI%') -replace 'max_input_time\s*=\s*\d+', 'max_input_time = 300' | Set-Content '%PHP_INI%'"

echo.
echo ========================================
echo IMPORTANT: Restart Apache/Nginx for changes to take effect!
echo ========================================
echo.
echo Press any key to restart Apache (or Ctrl+C to cancel)...
pause >nul

net stop Apache2.4
net start Apache2.4

echo.
echo Done! New settings:
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;"
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
php -r "echo 'max_execution_time: ' . ini_get('max_execution_time') . PHP_EOL;"
echo.
pause
