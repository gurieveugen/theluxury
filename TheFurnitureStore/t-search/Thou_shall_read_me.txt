The folder t-search is meant to help you to find articles of orders where the payment has arrived, but for some reason the 
actual items of the order are not associated with the order anymore (happens very seldom e.g. when users close browser during payment). 

Usage:
- Move the folder into the WordPress root directory; this is the directory where you have e.g. the file 'wp-load.php'
- Comment out line 2 (index.php in the t-search folder)
- Very important! PROTECT THE 't-search' FOLDER  with HTACCESS (Via your Hosting Panel - ask your Host if you don't know how)!

Access the search form like so: www.your-site.com/t-search