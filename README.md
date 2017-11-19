# Info

Unfortunately I am unable to maintain this project anymore. I've made it open source so that someone can continue to build it and create a better Lurker than I could've.

This is a fully setup DokuWiki installation with a very large amount of plugins for page listing, advanced editing, easy file upload, data backups, monitoring, page statistics, and administration (ip bans, word blocking, etc). 

# Screenshots

Here are some screenshots of the final design. This can be edited very easily from the admin config page, using an already installed plugin.

![](screenshots/homepage.png)
![](screenshots/wikidir.png)
![](screenshots/article.png)
![](screenshots/editor.png)
![](screenshots/stats1.png)
![](screenshots/stats2.png)
![](screenshots/stats3.png)

# Installation

I recommend downloading a copy of DokuWiki from [the official website](http://dokuwiki.org/) and extracting into the directory of your choosing. While DokuWiki is really nice to older versions mixing with newer versions, I'd say to install **Release 2017-02-19e "Frusterick Manners"** *(the version Lurker was running on)*, and upgrading manually after everything is installed and working. Since you originally installed a new copy of DokuWiki, navigate to `http://{yoursever}.tld/install.php` and check if there are any big errors.  If no errors are present, or it doesn't give you the option to install, you should be fine to then delete `install.php` and login to your new installation of Lurker. The admin account information is: 

Username: Lurker  
Password: admin

If for some reason this information does not work, edit `install-dir/conf/users.auth.php`, replacing the old password hash with a new one for the word "admin": `$1$4fd0ad31$.cId7p1uxI4a.RcrH81On0` 

`lurker:PUT-NEW-HASH-HERE:Lurker:email@localhost:admin,user,editor,moderator`

That should change the password to "admin", allowing you to login. However, this should be the last thing you try. Once logged in, I recommend taking a look at the admin page and getting familiar with what's installed.
