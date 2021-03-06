Notes on distribution and installation
========================================

Perparing the distribution zip file
-------------------------------------
Some files in this directory are not under version control but are prepared when bundling the distribution zip.
These tasks are performed in the pnut-build project.

There following files in the application directory are created in the package installation process.
To prepare the distribution, peanut specfic files in the application directory of the development environment are placed in directories under
packages/knockout_view/installation.

The following files are in a zip file, packages/knockout_view/installation/application.zip
application/install/*
application/mvvm/*
application/assets/*
application/config/peanut-bootstrap.php
application/config/settings.php

Three ini files in application/config are placed in packages/knockout_view/installation/ini
classes.ini
settings.ini
viewmodel.ini

Peanut specific changes to installation/bootstrap/app.php are extracted and placed in installation/bootstrap/app-php.txt.
The app-php.txt files is created by extracting lines indicated by the Peanut comments in application/bootstrap/app.php.
Lines indicated by comments as for development only are removed.


Package install/uninstall process
-------------------------------------------
When the package is installed under Concrete5, application.zip is extracted to the application directory and the ini files are copied to application/config, or merged with any existing files.

Additionally Concrete5 groups and database tables are created and populated.  For a list, see application/install/install.ini.

Finally, the peanut routing commands from app-php.txt are inserted into application/bootstrap/app.php.

The uninstall process rolls back all of the above.
