# PhpStorm File Watchers
To use the File Watchers you'll need the node requirements specified in the main Readme to be installed.

## 1. Place the shared scopes
Put the "scopes" folder into your ".idea" folder of your phpstorm project.

## 2. Import The watchers
Import the watchers (watchers.xml) through the Files Watchers pannel.

## 3. Set paths
- Replace the "$USER_HOME$" by the path to your user folder (ex: C:\users\USERNAME for Windows OS) in the program path for all the watchers. If your node settings are different, just indicate the path to your lessc.cmd.
- In the Project panel, navigate to your *skin folder/css/* and then right click on the "css" folder and mark it as "Sources Root".
- In the Project panel, navigate to your *skin folder/mail/css/* and then right click on the "css" folder and mark it as "Sources Root".

## 4. Debugging
The compilers create 3 files for each case : *.css*, *.css.map* and *.min.css*. The first two are made for development purpose.

Switch mode to *Production* in the configuration to use the minified file.