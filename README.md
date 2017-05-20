# SupremeMonitor
Monitors stock of supremenewyork.com

NOTE this program is for research purposes only, and should not be used to strain supreme's servers

Prerequisites: Must have php installed and apache server setup and running.

Instructions:
1. Download and unzip this repository by selecting Clone or Download/Download ZIP and drag the new folder to your desktop.
2. Open the folder, and open the file DBConfig.php with your text editor. Enter the host, username, and password for your SQL server. Save the file and close it.
3. Open terminal and enter:(No quotation marks)
"cd /Desktop/SupMonitor-master && php DBCreate.php"
If you have configured correctly, you should see "Database created successfully," and "Table created successfully" without error.
4. To run the monitor enter:
"cd && cd /Desktop/SupMonitor-master && php supMonitor.php"
The monitor should now run continuously.

5. To restart the monitor, simply open terminal and repeat step 4.

Notes:

If you would like to trigger a restock for testing purposes, open your browser and navigate to (http://localhost/phpmyadmin). Select SupremeStock on the left, then currentstock. You should see supreme's items populated in a table. While the monitor is running, change one of the 'true' values to 'false' (no quotes). Then, check the monitor to see the triggered restock.

To set up tweets on restocks, follow the tutorial at (http://www.pontikis.net/blog/auto_post_on_twitter_with_php) until you obtain your consumer key, secret consumer key, access token, and secret access token. Open up DBConfig.php with your text editor and paste in your values. Save and restart the monitor to allow for auto-tweeting.
