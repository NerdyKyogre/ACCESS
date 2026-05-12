# ACCESS
### Adorable Creature Census, Enumeration, and Search System

A system for plushie collectors to catalog and show information about their fluffy families!

Tested on Postgres 16 and PHP 8.2. Should work on most halfway modern versions of either, but use anything else at your own peril.

/phproot/config.ini must contain the following self-explanatory parameters:
```
DB_USER
DB_PASSWORD
DB_PORT
DB_NAME
IMG_PATH
```
Currently, postgres assumes DB_USER will be ``apache``. IMG_PATH is assumed to be symlinked into /wwwroot/images.

The theme is a lightly modified version of [Dopetrope by HTML5UP](https://html5up.net/uploads/demos/dopetrope/). Markdown parsing is handled by [Parsedown](https://parsedown.org/) and search is powered by [Fuse.js](https://www.fusejs.io/). All other work is my own.
