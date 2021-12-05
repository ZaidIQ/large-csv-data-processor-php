Proccessing big data into database using Laravel.

this is a laravel process that neatly writes the contents of the JSON file away to a database.

this process use laravel jobs and writes the data in the background so if any jobs failed it will retry automatically and even if we kill the server all failed jobs will be restarted without duplicating the data or needs to send a new request

To get started:

First you need to make the database configration, I used Postgres Sql database in this project because it's fast and powerful database.

After you configure the database run the migrate command.

Second give the start.sh file execuation permission, this file will run the server with all needed commands, after you give the permission 

run this command ./start.sh

The server should be working now and you are ready to go.

send the POST request with JSON data to your-host/api/clients. Now you can check the database using PgAdmin to see the records inside it.

- What if the source file suddenly becomes 500 times larger?

No problem, this process desgined to work with big data, each big file will divide into smaller data and these data will procrss in the background.

- Is the process easily deployed for an XML or CSV file with similar
content?

Yes, we can use the same code and process and make it work with different files types
