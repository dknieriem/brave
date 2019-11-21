Born to Be Brave
================



Local setup
-----------

1. Install Lando
2. run 
	```lando init 
		--source remote \
		--remote-url [git repo] \
		--recipe wordpress \
		--webroot . \
		--name brave```

3. `lando start`
4. download a database export
5. `lando db import [path/to/export.sql]`
6. `lando start`
7. `lando wp search-replace borntobebrave.com brave.lndo.site`