# PhpStorm File Watchers
To use the File Watchers you'll need the node requirements specified in the main Readme to be installed.

## 1. Place the shared scopes
Put the "scopes" folder into your ".idea" folder of your phpstorm project.

## 2. Import The watchers
Import the watchers (watchers.xml) through the Files Watchers pannel.

## 3. Set paths
- Replace the "$USER_HOME$" by the path to your user folder (ex: C:\users\USERNAME for Windows OS) in the program path for all the watchers. If your node settings are different, just indicate the path to your lessc.cmd.
- In the Project panel, navigate to your *skin folder/css/src/* and then right click on the "src" folder and mark it as "Sources Root".

## 4. Debugging
There is two file watchers available for each case (mobile,tablet and desktop). One which will create a sourcemap (usefull in a dev environment) and one which will not.