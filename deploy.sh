yarn run build
rsync -av ./ u94848610@home753406029.1and1-data.host:mesmenusfaciles/ --include=public/build --include=public/.htaccess --exclude-from=.gitignore --exclude=".*"
ssh u94848610@home753406029.1and1-data.host 'cd mesmenusfaciles && php7.3-cli bin/console cache:clear'