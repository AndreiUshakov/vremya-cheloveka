@echo off
REM ====================================================================
REM Скрипт синхронизации контента с сервером (Windows версия)
REM ====================================================================
REM Этот скрипт загружает изменения из src/content/ на хостинг
REM после редактирования файлов локально
REM ====================================================================

setlocal enabledelayedexpansion

REM Конфигурация
set REMOTE_USER=u0557545
set REMOTE_HOST=vremyacheloveka.ru
set REMOTE_BASE=/var/www/u0557545/data/www/vremyacheloveka.ru
set REMOTE_PATH=%REMOTE_BASE%/src/content/
set LOCAL_PATH=.\src\content\

echo ======================================================================
echo   Синхронизация контента с сервером vremyacheloveka.ru
echo ======================================================================
echo.

REM Проверка наличия локальной директории
if not exist "%LOCAL_PATH%" (
    echo [ERROR] Директория %LOCAL_PATH% не найдена
    echo Убедитесь, что вы запускаете скрипт из корня проекта
    pause
    exit /b 1
)

REM Проверка наличия rsync (в Git Bash или WSL)
where rsync >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo [ERROR] rsync не найден
    echo.
    echo Установите один из вариантов:
    echo 1. Git for Windows с Git Bash - https://git-scm.com/download/win
    echo 2. WSL (Windows Subsystem for Linux)
    echo 3. Cygwin - https://www.cygwin.com/
    echo.
    echo После установки используйте Git Bash для запуска sync-content.sh
    pause
    exit /b 1
)

echo [INFO] Локальная директория: %LOCAL_PATH%
echo [INFO] Удалённый сервер: %REMOTE_USER%@%REMOTE_HOST%
echo [INFO] Удалённая директория: %REMOTE_PATH%
echo.

REM Подсчёт файлов
set FILE_COUNT=0
for /r "%LOCAL_PATH%" %%f in (*.md *.json) do set /a FILE_COUNT+=1
echo [INFO] Найдено файлов для синхронизации: %FILE_COUNT%
echo.

set /p CONFIRM="Продолжить синхронизацию? (Y/N): "
if /i not "%CONFIRM%"=="Y" (
    echo [WARNING] Синхронизация отменена
    pause
    exit /b 0
)

echo.
echo [INFO] Начинаем синхронизацию...
echo.

REM Конвертируем Windows пути в Unix формат для rsync
set "LOCAL_PATH_UNIX=%LOCAL_PATH:\=/%"

REM Синхронизация
rsync -avz --progress --delete ^
  --include="*/" ^
  --include="*.md" ^
  --include="*.json" ^
  --exclude="*" ^
  "%LOCAL_PATH_UNIX%" ^
  "%REMOTE_USER%@%REMOTE_HOST%:%REMOTE_PATH%"

if %ERRORLEVEL% equ 0 (
    echo.
    echo [SUCCESS] Синхронизация завершена успешно!
    echo.
    echo Файлы обновлены на сервере. Теперь:
    echo 1. Проверьте админ-панель: https://vremyacheloveka.ru/admin-php/
    echo 2. Пересоберите статический сайт: npm run build
    echo 3. Загрузите обновлённые HTML-файлы на хостинг
) else (
    echo.
    echo [ERROR] Ошибка при синхронизации
    echo.
    echo Проверьте:
    echo 1. SSH доступ к серверу
    echo 2. Правильность путей
    echo 3. Права доступа на сервере
)

echo.
pause