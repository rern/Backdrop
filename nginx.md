```sh
. /srv/http/addonsedit.sh
alias=backdrop
file=/etc/nginx/nginx.conf

string=$( cat <<EOF
        location /backdrop {
            alias  /var/www/backdrop;
            index  index.php index.html;
        }
EOF
)
insertS 'location .pub'

if ! grep -q 'aria' $file; then
    commentS '^\s*rewrite'
    string=$( cat <<'EOF'
            rewrite /css/(.*) /assets/css/$1 break;
            rewrite /fonts/(.*) /assets/fonts/$1 break;
            rewrite /img/(.*) /assets/img/$1 break;
            rewrite /js/(.*) /assets/js/$1 break;
            rewrite /less/(.*) /assets/less/$1 break;
EOF
)
    appendS -n +7 'listen 80 '
fi

systemctl restart nginx

wgetnc https://github.com/rern/Backdrop/archive/master.zip
mkdir -P /srv/http/backdrop
bsdtar -xvf master.zip --strip 1 --exclude '.*' --exclude '*.md' -C /srv/http/backdrop
rm master.zip
chown -R http:http /srv/http/backdrop
chmod -R 755 /srv/http/backdrop
```
