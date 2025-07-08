### Installation

1. **Clone the Repository:**

```shell
git clone https://github.com/ResiStore-App/ResiStore.git
cd resistore

```
2. **Copy .env file**

```shell
cp .env.example .env
php artisan key:generate

```

3. **Update .env file**
   Edit the following environment variables as needed:

```shell
DB_CONNECTION=mysql
DB_DATABASE=db_resistore
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost

```

4. **Install Dependencies:**

```shell
composer install
npm install
npm run build
```

5. **Turn On your MySQL Server:**
   Make sure your MySQL server is running before proceeding.

6. **Run database migration:**

```shell
php artisan migrate
```

7. **Create the First Filament Admin User:**

```shell
php artisan make:filament-user
```

8. **Run the Application:**

```shell
php artisan serve
```

Access the application at http://localhost:8000
