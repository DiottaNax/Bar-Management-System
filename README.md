# My-Management-System

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://github.com/DiottaNax/KittyGram/blob/main/LICENSE)
[![GitHub stars](https://img.shields.io/github/stars/DiottaNax/KittyGram)](https://github.com/DiottaNax/KittyGram/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/DiottaNax/KittyGram)](https://github.com/DiottaNax/KittyGram/network)
[![GitHub issues](https://img.shields.io/github/issues/DiottaNax/KittyGram)](https://github.com/DiottaNax/KittyGram/issues)

## Description

The **Bar Management System** is a database application designed to automate the management of a bar or restaurant. It tracks orders, products, and payments, and provides tools for inventory management and sales analysis.

Key features include:

- Order management with product tracking and table assignment
- Inventory management for products and purchase prices
- Receipt generation and payment tracking
- Sales reports and data visualization
- User roles with different permissions (e.g., waiters, bartenders, cooks, and stock keepers)

## Technical Details

- Database: MySQL
- Schema: Fully normalized relational database design
- Documentation: Comprehensive ER diagrams and schema descriptions
- API: Planned RESTful API for integration with front-end applications

## Project Structure

- `/db`: Contains SQL scripts for database creation and the PHP queries' implementation
- `/report`: Includes detailed project documentation and ER diagrams
- `/api`: Contains API implementation

## Getting Started

To create the database use the [My-Management-System.sql](https://github.com/DiottaNax/Bar-Management-System/blob/main/db/My-Management-System.sql) script.

> If you are using xampp you can directly drop the script in the sql console from phpmyadmin

You can access the Web Application from the `index.php`.

To test and access the Application you are provided with a [populate.sql](https://github.com/DiottaNax/Bar-Management-System/blob/main/db/My-Management-System.sql) script.

If you used the `populate.sql` script you will have 3 different account to test the application with:

- Admin
    - email: proprietario@volume.it
    - password: admin
- Waiter
    - email: cameriere@volume.it
    - password: cameriere
- Storekeeper:
    - email: cucina@volume.it
    - password: cucina

