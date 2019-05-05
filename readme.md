/***************************************************************************
*
*                            Facebook - Auto Event-Importer
*                      
*     copyright            : (C) 2012 Sven Goessling / SmileAndGo.de
*     website              : http://www.sven-goessling.de
*
*     IMPORTANT: This is a commercial product made by Sven Goessling and cannot be modified for other than personal usage. 
*     This product cannot be redistributed for free or redistribute it and/or modify without written permission from Sven Goessling. 
*     This notice may not be removed from the source code.
*     See license.txt file; if not, write to info@emsland-party.de 
***************************************************************************/

Hello,

Easy Install:

1.Step:
copy the folder "goesi" to /modules/

2.Step:
Go to the Admin Console, Tools -> Module, and check the Fbook-Event-Importer and click on install.

3.Step:
Go to the module Fbook-Event-Importer.

4.Step:
Create a fresh Facebook Account and put the login-email and pw to the importer.

5.Step:
Open /classes/GoesiFbCron.php with a editor ->
Add your Admin Data, this is importent when you will use the cron funktion for Auto Update.

6.Step:
Click the Activate Link at the Bottom of the Link List. without this step, you cannot import links.

7.Step:
Check the Tags and Categorie, ok? LETS ADD LINKS!




This Links will work:
Places:
https://www.facebook.com/index.disco?sk=events
https://www.facebook.com/Diskothekkuper?sk=events

Search-Places:
https://www.facebook.com/search/results.php?q=Emsland&type=events
https://www.facebook.com/search/results.php?q=HongKong&type=events

Single Events:
https://www.facebook.com/events/161891127260201/
https://www.facebook.com/events/234167569988217/




And this will not work:
https://www.facebook.com/profile.php?id=1738727127
https://www.facebook.com/search/results.php?q=ho&init=quick&tas=0.8520800227054062
http://what-ever.com



INFO:

This script runs with cronjobs. The Script runs every 3.00 hours.  When you will that the script run at another time, go to the install.sql and change the time at cronjob.
The Script checks every 2 Days if your events actually.

For better and faster Support, Send me a Mail : Sven.Goessling@Emsland-Party.de