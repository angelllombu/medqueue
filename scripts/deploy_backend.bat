@echo off
REM Menyalin folder backend\ ke htdocs XAMPP. Jalankan setiap habis merge ke dev.
REM XAMPP di laptop ini ada di C:\xamppp (tiga p). Ubah XAMPP_DIR kalau berbeda.

set XAMPP_DIR=C:\xamppp
set TARGET=%XAMPP_DIR%\htdocs\medqueue_api
set SOURCE=%~dp0..\backend

echo Deploy dari: %SOURCE%
echo Deploy ke  : %TARGET%

if not exist "%XAMPP_DIR%\htdocs" (
  echo ERROR: folder %XAMPP_DIR%\htdocs tidak ditemukan. Cek XAMPP_DIR di file ini.
  pause
  exit /b 1
)

REM /MIR menyamakan isi target dengan sumber. Folder postman tidak perlu ikut.
robocopy "%SOURCE%" "%TARGET%" /MIR /XD postman /NFL /NDL /NJH /NJS /NP
if %ERRORLEVEL% GEQ 8 (
  echo ERROR: robocopy gagal, kode %ERRORLEVEL%
  pause
  exit /b 1
)

echo.
echo Selesai. Tes di browser: http://localhost/medqueue_api/ping.php
pause
