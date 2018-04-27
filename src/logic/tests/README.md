##### TESTING HOWTO #####


Base case: sanity test with the test suite. Performs unit testing of key components of the logic layer with industry-standard PHPUnit testing framework.

Commands:

ssh root@192.168.104.250
cd /var/www/html/lupapalvelu_demo_v6/logic/tests/
php tests/phpunit.phar tests/test_suite.php


Advanced usage: custom DEV testing of logic layer functions.

Example commands:

ssh root@192.168.104.250
cd /var/www/html/lupapalvelu_demo_v6/logic
php tests/test_pdf_paatos.php


Steps for writing own test:

1. Add this line to the beginning of the existing logic layer function you want to test:

debug_log($syoteparametrit); // param can be any data type - obj, array, string - whatever

2. Perform the actions needed to trigger this function in the user interface. No UI - then you can skip steps 2 and 3.

3. Checkout /tmp/debug_browser.log to see the $syoteparametrit dump.

4. Copy some existing test e.g. tests/test_paatos_pdf.php, and change function name and parameters set to the data from dev.log.
Note you can use $token variable - testing framework obtains the token for you.

5. Then you can trigger your custom test with the command:
php tests/test_my_function.php

6. If something goes wrong you can add debug_log() to the test_my_function.php file and find the dumped data in /tmp/debug_xterm.log


------

Server setup documentation addon

Install Memcached + php-memcache to 5.3 LAMP stack:

yum install -y libevent libevent-devel

yum install -y memcached

It doesn't run automatically upon installation, we need:
service memcached start

Check if it really runs:
netstat -peanut | grep memcached

Edit /etc/rc.local
Add this line to start memcached daemon after reboot:
service memcached start

yum install php-pecl-memcache

edit /etc/sysconfig/memcached to set cache size:
CACHESIZE="512"

service httpd restart


For php 7 you need:

yum install php-pecl-memcached

and patch presentation/_security.php file (uncomment marked lines)

------