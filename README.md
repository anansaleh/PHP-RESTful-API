# PHP-RESTful-API
a PHP RESTful API to work with the queue system in order to insert and retrieve queue entries from the database.

---
## Scenario
We have a council who would like to use an application to queue people at the reception desk. I build a RESTful API to work with the queue system in order to insert and retrieve queue entries from the database.

---
## Requirements
I have run it in PHP 7.0 but I think it will work in PHP 5.6
MySQL

## Methods Accept

| Method | Data Type | Path | Purpose |
|--------|--------|--------|--------|
| GET    | JSON |/queue  |Retrieves all the entries queued today|
| GET    | JSON |/queue/citizen  |Retrieves all the entries queued today filters by Citizen|
| GET    | JSON |/queue/anonymous  |Retrieves all the entries queued today filters by Anonymous|
| POST    | JSON |/queue  |Create a new entry into the queue table with the current timestamp|

## Installations
1.Crete database in your Mysql
2.Run the SQL script in file **system/data.sql** to create the database table with some enteries
3.Open file **system/config.php**
4.Change the configuration of database in array **$config_database**
5.Change the configuration of the host URL in array **$config_setting**
6.Be carfull about the value of **subFolder** read the cooments
7.Open your browser in the url as you write in the **$config_setting** for example *localhost:8000/queue*
8.If the application will run in vhost sub folder then brwoser it *localhost:8000/subFolder/queue*
9.This will return json.

## Folder & Files Structure
| Folder | Files | Description |
|--------|--------|--------|
|..      |index.php|the main page. bootstrp of the application|
|api     | |folder include the application files|
|    |api_controller.php |API Class controller |
|    |queue_model.php |Queue Class Model |
|system     | |folder include the system files|
|    |config.php |The configuration file |
|    |database.php |PDO Database Class|
|    |request.php |Request Class network|

