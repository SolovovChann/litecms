# **@Solovov Chann's** Litecms
Tiny PHP Content Management System provides you tools for comfort web development work.

## 🏗️ Requirements
---
The following components are required for correct work
1. PHP: v.7.4 or higher
1. Server: Apache v.2.4 + Ngnix 1.19
1. Database: Maria DB v.10.4

## 🤨 What's Lite CMS
---
**Litecms** is a graduate project created to improve programming and computer engineering skills. Litecms's purpose is to organise application's structure and provide the most frequently functions, that used in web development.

## 😎 Why Lite CMS? (Features)
---
**Litecms** has such useful features as:
1. Routing – Allow you to use human-readable urls
1. ORM – Simplifies the work with databases
1. MVC pattern based – Separates application's data and buisness logic from presentation
1. Small weight – LiteCMS weight less than popular frameworks
1. Extensibility – LiteCMS allows to create new functions and features

## 🎬 Quickstart
---
1. Download the archive into your working folder via command in terminal
    ```bash
    $ git clone https://github.com/SolovovChann/litecms.git
    ```
1. Using the file manager, open the *litecms/Settings.php* file and edit *Connection* constant with your connection settings. You can also edit project name as well
1. Open *litecms/controllers/Home.php* file to edit homepage view. Also, you need to edit *templates/home.php* file
1. If you need to add new page to track, open *litecms/routes.php* file and use 
    ```php
    Route::add()
    ```
    For more information read the documentation.
1. Open the page in a web browser