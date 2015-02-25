rsync -avP \
--exclude=".*" \
--exclude="deploy.sh" \
--exclude="upload/" \
--include=".htaccess" \
./ \
digitalserver:/var/www/html/verdi-validator
