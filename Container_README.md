**This is a temporary file for sharing notes about containerization. REMOVE when ready.**

- Base image wp-content/mu-plugins/ content is different from opehuone-stage-new-ui.edu.hel.fi.
- Devops: components/opehuone/vars/opehuone-development.yml & staging wp vars missing

### Plugins and themes

opehuone-stage-new-ui.edu.hel.fi:

`ls -1 wp-content/plugins/

PDFEmbedder-premium
advanced-custom-fields-pro
ajax-load-more
ajax-load-more-for-relevanssi
ajax-load-more-rest-api
better-search-replace
classic-editor
easy-basic-authentication
findkit
jonradio-private-site
onelogin-saml-sso
onesignal-free-web-push-notifications
oppi-school-picker
relevanssi
remove-dashboard-access-for-non-admins
safe-svg
simple-comment-editing
simple-comment-editing-fi.mo
simple-comment-editing-fi.po
simple-page-ordering
smtp-mailer
stadin-ao-articles
stop-emails
super-progressive-web-apps
tablepress
taxonomy-terms-order
wordfence
wordpress-helfi-hds-wp
wordpress-importer
wp-piwik
wp-security-audit-log
wpo365-login
wpo365-login-intranet
wpo365-login-intranet-12.5.zip
wpo365-samesite`

`ls -1 wp-content/themes/

opehuone
opehuone-broken
wordpress-helfi-helsinkiteema`

`ls -1 wp-content/mu-plugins/

hm-require-password-master`

base image:

`ls -1 wp-content/mu-plugins/
LICENSE
composer.json
wordpress-helfi-site-core
wp-mu-autoloader.phpls -1 wp-content/mu-plugins/
LICENSE
composer.json
wordpress-helfi-site-core
wp-mu-autoloader.php`

## Opehuone Dockerfile


### 📦 Plugins

- [ ] PDFEmbedder-premium
- [ ] advanced-custom-fields-pro
- [ ] ajax-load-more
- [ ] ajax-load-more-for-relevanssi
- [ ] ajax-load-more-rest-api
- [ ] better-search-replace
- [ ] classic-editor
- [ ] easy-basic-authentication
- [ ] findkit
- [ ] jonradio-private-site
- [ ] onelogin-saml-sso
- [ ] onesignal-free-web-push-notifications
- [ ] oppi-school-picker
- [ ] relevanssi
- [ ] remove-dashboard-access-for-non-admins
- [ ] safe-svg
- [ ] simple-comment-editing
- [ ] simple-comment-editing-fi.mo
- [ ] simple-comment-editing-fi.po
- [ ] simple-page-ordering
- [ ] smtp-mailer
- [ ] stadin-ao-articles
- [ ] stop-emails
- [ ] super-progressive-web-apps
- [ ] tablepress
- [ ] taxonomy-terms-order
- [ ] wordfence
- [ ] wordpress-helfi-hds-wp
- [ ] wordpress-importer
- [ ] wp-piwik
- [ ] wp-security-audit-log
- [ ] wpo365-login
- [ ] wpo365-login-intranet
- [ ] wpo365-login-intranet-12.5.zip
- [ ] wpo365-samesite

### 🎨 Themes

- [ ] opehuone
- [ ] opehuone-broken
- [ ] wordpress-helfi-helsinkiteema
