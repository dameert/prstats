Github PR Stats
========================

PHP command application to retrieve pull request review statistics.

Requirements
------------

    * PHP 7.2.5 or higher
    
Installation
------------

Download this repository and run composer install.

First usage
-----

Copy the [.env.dist](.env.dist) to a new file called **.env**.

Provided the **GIT_ORGANISATION** and **AUTH_TOKEN** in the new [.env](.env) file 


````bash
php run
Pull Request Statistics 5.0.8

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help               Displays help for a command
  list               Lists commands
 stats
  stats:pullrequests  [stats:pr] Get Github pull request statistics
````


