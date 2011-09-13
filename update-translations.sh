#!/bin/sh

#write alle php files in file
(find ./data -name "*.php" && find ./system -name "*.php") > update-translations.txt

#call xgettext to create pot file
xgettext -f update-translations.txt -kT_ngettext:1,2 -kT_ --language=PHP -o update-translations.pot
