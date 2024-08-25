# Property Reservation - Application Setup Guide

This guide provides the steps to clone and set up a Laravel application running on the VILT stack (Vue.js, Inertia.js, Laravel, Tailwind CSS) locally.

## Prerequisites

Ensure you have the following tools installed on your local machine:

1. **Git**: For cloning the repository.
    ```bash
    sudo apt-get install git
    ```

2. **PHP** (preferably version 8.x): Required for running Laravel.
    ```bash
    sudo apt-get install php
    ```

3. **Composer**: For managing PHP dependencies.
    ```bash
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    ```

4. **Node.js** (preferably version 18.x): Required for running the frontend.
    ```bash
    sudo apt-get install -y nodejs
    ```

5. **NPM or Yarn**: For installing JavaScript dependencies.
    ```bash
    sudo apt-get install npm
    ```

6. **MySQL or MariaDB**: For the database. Install and set up a database.
    ```bash
    sudo apt-get install mysql-server
    ```

7. **Nginx or Apache**: For serving the Laravel application. If using Nginx:
    ```bash
    sudo apt-get install nginx
    ```

8. **PM2**: For managing background Node.js processes (optional but recommended).
    ```bash
    npm install pm2 -g
    ```

## Getting Started
Refer to technical documentation for setting up either through docker or PHP's inbuilt server
