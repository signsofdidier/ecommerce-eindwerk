# 🛋️ Furni – E-commerce Eindproject

Dit is het eindwerk voor het vak Webontwikkeling: een volledige Laravel-gebaseerde e-commerce webshop met Stripe-integratie, gebruikersauthenticatie, beheerderspaneel en productbeheer.

## ✅ Features

- Producten browsen en detailpagina’s
- Winkelwagen en afrekenen
- Stripe-betalingen (testmodus)
- Gebruikersregistratie & login
- Adminpaneel met productbeheer en veel meer
- E-mailmeldingen (via Mailpit of lokaal)
- Ai integratie met Groq
- Veel meer coole features om te ontdekken!

---

## ⚙️ Vereisten

Zorg dat volgende tools geïnstalleerd zijn:

- PHP >= 8.2
- Composer
- Node.js en NPM
- MySQL
- Laravel CLI (`composer global require laravel/installer`)
- Stripe testaccount (gratis via [https://stripe.com](https://stripe.com))
- Groq account
- Mailpit

---

## 🚀 Installatie-instructies

1. **Repository klonen**
   ```bash
   git clone https://github.com/<jouw-gebruikersnaam>/furni.git
   cd furni

2. **Dependencies installeren**
   ```bash
composer install
npm install && npm run dev

3. **.env bestand instellen**
Kopieer het .env.example bestand naar .env:
   ```bash
cp .env.example .env

Vul daarna onderstaande gegevens aan in .env:
   ```bash
APP_NAME="Furni"
APP_ENV=local
APP_KEY= # ← wordt gegenereerd in de volgende stap
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= # ← vul hier je gewenste database in
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_... # ← Vul hier je stripe key in
STRIPE_SECRET=sk_test_... # ← Vul hier je stripe key in

GROQ_API_KEY=gsk_... # ← Vul hier je groq key in

# ← Mailpit gegevens
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@local.dev
MAIL_FROM_NAME="Furni Ture"

✅ Tip: Gebruik Mailpit om lokaal e-mails te bekijken.

4. **Applicatiesleutel genereren**
   ```bash
php artisan key:generate

5. **Database migreren + seeden**
   ```bash
php artisan migrate:fresh --seed

6. **Start de server**
   ```bash
composer run dev

7. **Admin login**
   ```bash
E-mail: admin@gmail.com
Wachtwoord: password

Voor andere rollen kan je de login email addressen terugvinden bij roles (het passwoord is altijd 'password'

💳 Stripe Testgegevens
Gebruik onderstaande kaartgegevens bij het testen van betalingen:
Kaartnummer: 4242 4242 4242 4242
Vervaldatum: eender welke datum in de toekomst
CVC: 3 cijfers
ZIP/postcode: eender welke
Controleer dat STRIPE_KEY en STRIPE_SECRET correct ingevuld zijn in .env.

ℹ️ Opmerkingen
Mail-functionaliteit werkt enkel als je een SMTP-server zoals Mailpit lokaal hebt draaien of een andere mail functionaliteit gebruikt in de ENV.
