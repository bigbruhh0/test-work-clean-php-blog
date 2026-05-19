# Blogy

Simple blog on pure PHP with MySQL and Smarty.

## Stack

- PHP 8.1+
- MySQL
- Smarty
- Docker
- SCSS
- Node.js/npm for SCSS compilation

## Run

Copy environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Start containers:

```bash
docker compose up -d --build
```

Install dependencies:

```bash
docker compose run --rm php composer install
```

Run migrations:

```bash
docker compose run --rm php php bin/console migrate
```

Seed demo data:

```bash
docker compose run --rm php php bin/console seed
```

The site is available at:

```text
http://localhost:8080
```

Images are served by a separate container:

```text
http://localhost:8081
```

## Database

External MySQL connection:

```text
Host: 127.0.0.1
Port: 3307
Database: blog
Username: blog
Password: blog
```

Inside Docker, the application uses:

```text
Host: mysql
Port: 3306
```

## Commands

```bash
make up
make down
make composer-install
make migrate
make seed
make scss
make restart
```

## Styles

SCSS source is stored in:

```text
resources/scss/app.scss
```

The compiled CSS file used by the browser is:

```text
public/assets/css/app.css
```

Compile SCSS:

```bash
make scss
```

This command uses local `npx` and writes the compiled file to `public/assets/css/app.css`.
