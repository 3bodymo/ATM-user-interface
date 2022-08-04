# ATM-user-interface
An ATM user interface integrated with NFC technology.

## Introduction
I developed an ATM user interface using HTML, CSS and JavaScript for frontend. And for the backend I used PHP and SQL.

## Setup
* Make sure that you have a web server like Apache to launch the project, or you can use php server (I prefer it).
* I've used SQL to communicate with the database, and I'll tell you the SQL queries you'll need to create a database and create a table.

> To create the database:

```sql
CREATE DATABASE atm;
```

> To create a table:

``` sql
CREATE TABLE clients (
    user_id int NOT NULL PRIMARY KEY,
    first_name varchar(50) NOT NULL,
    last_name varchar(50) NULL,
    account_number varchar(50) NOT NULL,
    pin_code char(4) NOT NULL,
    balance float NOT NULL,
    card_number char(16) NOT NULL,
    card_mm char(2) NOT NULL,
    card_yy char(2) NOT NULL,
    card_cvv char(3) NOT NULL
);
```

> To insert a sample data:

```sql
INSERT INTO clients (user_id, first_name, last_name, account_number, pin_code, balance, card_number, card_mm, card_yy, card_cvv)
VALUES ('1', 'Abdullah', 'Mohamed', '3ba97003', '1234', '1523', '1234123412341234', '11', '23', '452');
```

* After you creating database and table, you have to configure the connection for your database that you are use, so open `config.php` file and edit it.

* For NFC part:
  - At first, you have to install [libnfc](https://github.com/nfc-tools/libnfc).
  - I have used UID of NFC card as account number to identify the user when he login through NFC.

* Now you are ready to launch, just run your web server and open `login.php`.

## Demo
https://user-images.githubusercontent.com/63552083/168093263-5b6973c4-cfbb-4b81-9a4f-4df1282dfc41.mp4

## Resources I used it
* https://gist.github.com/CodeMyUI/e166e6d1af064446e89a9054a970b26c
* https://codepen.io/marcobiedermann/pen/eRNWxQ
* https://codepen.io/lucasyem/pen/ZEEYKdj
