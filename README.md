<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Pranglimisrakendus

Tere tulemast Pranglimisrakenduse repositooriumisse. Pranglimisrakendus on gümnaasiumi uurimistööks arendatav pranglimiskeskkond, mis väärtustab lihtsat ja modernset kasutajaliidest ja uusi tehnoloogiaid.

## Kasutus

Vaja läheb järgmisi tehnoloogiaid:

1) PHP (https://www.php.net/manual/en/install.php)
2) Composer (https://getcomposer.org/doc/00-intro.md)
3) Node.js/NPM (https://nodejs.org/en)

**Installeerimine:**

1. _Klooni repositoorium arvutisse_
2. composer install
3. npm install
4. cp .env.example .env
5. php artisan key:generate
6. composer require laravel/socialite

**Kasutamine:**

Ava kaks terminali instantsi (nt VS Code seest). Jooksuta ühes terminalis ühte, teises teist käsku.

1. npm run dev
2. php artisan serve

## Litsents

Laraveli raamistik on vabavaraline projekt, mida kaitseb [MIT license](https://opensource.org/licenses/MIT).
