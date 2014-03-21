lxErrorLoggerPlugin
===================

lxErrorLoggerPlugin allow you to log PHP errors and exceptions that occur on a Symfony project into various format.

![Project Status](http://stillmaintained.com/lexik/lxErrorLoggerPlugin.png)

Available error notifier
------------------------

The plugin provide several error notifier you can separately enable:

* Database notifier: it will save an entry for each error in your database in the `lx_error_logger` table. (work with [Doctrine](http://www.doctrine-project.org/))
* XML notifier: log each error in a xml file.
* Email notifier: send an email each time an error occur.
* Log file notifier: log each error in a basic log file. 
* Hoptoad notifier: send each error to your [Hoptoad](http://hoptoadapp.com/) account.

Setup
------

1. Enable `lxErrorLoggerPlugin` in your ProjectConfiguration
2. Configure plugin through your `app.yml` file to enable error notifier you need.

Plugin configuration description
--------------------------------

Full `app.yml` options (with defaults values):

    all:
      lx_error_logger_plugin:
        enabled:              false                                  # enable/disable the error logger
        php_error_reporting:  <?php echo (E_ALL | E_STRICT)."\n" ?>  # php errors to repport
        reserve_memory:       false                                  # reserve some memory to be able to log memory limit fatal errors.
        service_class:        lxErrorLoggerService                   # error logger service class
        error_class:          lxError                                # error class
        error_html_template:  %SF_PLUGINS_DIR%/lxErrorLoggerPlugin/data/lxErrorHtml.php # template file used to render an error as html.
        rss:
          token:              695da52ee926897d352ad0c05721a8bc       # token to access the rss feed provided by lxErrorNotifierRss module, use your own token.
          items:              20                                     # number of items in the rss feed provided by lxErrorNotifierRss module
        notifier:
          db:
            enabled:          false                                  # enable/disable database notifier
            class:            lxErrorNotifierDb                      # database notifier class
            options:
              similar_error:  true                                   # look for similar error when an error is notified
              dsn:            ~                                      # use a different DB to log errors
              username:       ~                                      # username for the DB
              password:       ~                                      # password for the DB
              driver_options: {}                                     # PDO driver options
          xml:
            enabled:          false                                  # enable/disable notifier xml
            class:            lxErrorNotifierXml                     # xml notifier class
            options:
              file_path:      %SF_DATA_DIR%/lxErrorLoggerPlugin      # xml file path
              file_name:      errors.xml                             # xml file name
              similar_error:  true                                   # look for similar error when an error is notified
          mail:
            enabled:          false                                  # enable/disable mail notifier
            class:            lxErrorNotifierMail                    # email notifier class
            options:
              to:             mail@example.com                       # or several emails > to: [mail@example.com, mail2@exmple.com]
              subject:        A new error occured on your website    # the email subject
              always_send:    true                                   # always send an email even if db or xml notifiers detect the error as similar
          hoptoad:
            enabled:          false                                  # enable/disable Hoptoad notifier
            class:            lxErrorNotifierHoptoad                 # Hoptoad notifier class
            options:
              apiKey:         8a5da52ed126447d359e70c05721a8aa       # your Hoptoad api key
              timeout:        2                                      # time out value for curl request to Hoptoad
              log_response:   false                                  # log Hoptoad response
          logFile:
            enabled:          false                                  # enable/disable log file notifier
            class:            lxErrorNotifierLogFile                 # log file notifier class
            options:
              file_path:      %SF_LOG_DIR%                           # log file path
              file_name:      lxErrorLoggerPlugin.log                # log file name
              line_pattern:   "%created_at% %separator% %environment% %separator% %type% %separator% %message% (%file%:%line%)" # log line pattern

Log file notifier, all `line_pattern` available values:

    %separator%      a '|' char
    %environment%    symfony environment
    %type%           error type
    %url%            resquested url
    %code%           error code
    %class%          execption class name
    %message%        error message
    %file%           error file
    %line%           line in the file
    %module%         symfony module name
    %action%         symfony action name
    %trace%          error trace
    %user_agent%     user agent data
    %server%         $_SERVER content
    %session%        $_SESSION content
    %similar_error%  the similar error id
    %created_at%     the error date (Y-m-d H:i:s)

RSS feed
--------

The plugin provide a RSS feed with latest errors.

1. Enable the lxErrorNotifierRss module in one of your application in the `settings.yml` file.
2. The RSS feed is available on `http://your.domain.com/lx-error/rss.xml?token=695da52ee926897d352ad0c05721a8bc`

This feed is generated only if Database or XML notifier is enabled. If both are enaled the feed is generated from database notifier datas.

License
-------

This plugin is licensed under the terms of the [MIT License](http://en.wikipedia.org/wiki/MIT_License).

Credits
-------

This plugin is developed and maintained by [Lexik](http://www.lexik.fr).
