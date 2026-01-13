FROM helsinki.azurecr.io/openshift-wordpress-base:latest

COPY .user.ini wordfence-waf.php /opt/app-root/src/

ARG MOUNT_SECRET="false"
ARG COMPOSER_AUTH="{}"

# Define plugin versions
ARG WP_PLUGIN_VERSION_WORDFENCE=""
ARG WP_PLUGIN_VERSION_FINDKIT=""
ARG WP_PLUGIN_VERSION_SIMPLE_COMMENT_EDITING=""
ARG WP_PLUGIN_VERSION_SAFE_SVG=""
ARG WP_PLUGIN_VERSION_SIMPLE_PAGE_ORDERING=""
ARG WP_PLUGIN_VERSION_WPO365_LOGIN=""
ARG WP_PLUGIN_VERSION_WPO365_SAMESITE=""
ARG WP_PLUGIN_VERSION_TAXONOMY_TERMS_ORDER=""
ARG WP_PLUGIN_VERSION_WP_SENTRY_INTEGRATION=""
ARG WP_PLUGIN_VERSION_WP_SECURITY_AUDIT_LOG=""
ARG WP_PLUGIN_VERSION_CONNECT_MATOMO=""
ARG WP_PLUGIN_VERSION_OPPI_SCHOOL_PICKER=""
ARG WP_PLUGIN_VERSION_ACTIVITY_LOG=""
ARG WP_PLUGIN_VERSION_TABLEPRESS_PREMIUM=""
ARG WP_PLUGIN_VERSION_SERVICENOW_TABLEPRESS_SYNC=""
ARG WP_THEME_VERSION_HELSINKITEEMA=""
ARG WP_THEME_VERSION_OPEHUONE=""

RUN mkdir -m 777 /tmp/wflogs

# build volume auth
RUN mkdir -p /opt/app-root/src/.config/composer && \
    if [ -n "$MOUNT_SECRET" ] && [ "${MOUNT_SECRET,,}" = "true" ]; then \
        cp /mnt/secrets/* /opt/app-root/src/.config/composer; \
    fi

# Install plugins via Composer
RUN composer config repositories.opehuone vcs https://github.com/City-of-Helsinki/opehuone2025 && \
    composer require city-of-helsinki/opehuone:$WP_THEME_VERSION_OPEHUONE && \
    composer config repositories.wordpress-helfi-hds-wp vcs https://github.com/City-of-Helsinki/wordpress-helfi-hds-wp && \
    composer require city-of-helsinki/wordpress-helfi-hds-wp && \
    composer config repositories.wordpress-helfi-helsinkiteema vcs https://github.com/City-of-Helsinki/wordpress-helfi-helsinkiteema && \
    composer require city-of-helsinki/wordpress-helfi-helsinkiteema:$WP_THEME_VERSION_HELSINKITEEMA && \
    composer config repositories.oppi-school-picker vcs https://github.com/City-of-Helsinki/wordpress-helfi-plugin-oppi-school-picker && \
    composer require city-of-helsinki/oppi-school-picker:$WP_PLUGIN_VERSION_OPPI_SCHOOL_PICKER && \
    composer config repositories.activity-log vcs https://github.com/City-of-Helsinki/wordpress-helfi-plugin-activity-log && \
    composer require city-of-helsinki/activity-log:$WP_PLUGIN_VERSION_ACTIVITY_LOG && \
    composer config repositories.advanced-custom-fields-pro vcs https://github.com/City-of-Helsinki/wordpress-helfi-plugin-advanced-custom-fields-pro && \
    composer require acf/advanced-custom-fields-pro && \
    composer config repositories.wpo365-integrate vcs https://github.com/City-of-Helsinki/wordpress-helfi-plugin-wpo365-integrate && \
    composer require wpo365/wpo365-integrate && \
    composer config repositories.tablepress vcs https://github.com/City-of-Helsinki/wordpress-helfi-plugin-tablepress-premium && \
    composer require tablepress/tablepress-premium:$WP_PLUGIN_VERSION_TABLEPRESS_PREMIUM && \
    composer config repositories.servicenow-tablepress-sync vcs https://github.com/City-of-Helsinki/servicenow-tablepress-sync && \
    composer require city-of-helsinki/servicenow-tablepress-sync:$WP_PLUGIN_VERSION_SERVICENOW_TABLEPRESS_SYNC && \
    composer config repositories.wpackagist composer https://wpackagist.org && \
    composer require wpackagist-plugin/wordfence:$WP_PLUGIN_VERSION_WORDFENCE && \
    composer require wpackagist-plugin/findkit:$WP_PLUGIN_VERSION_FINDKIT && \
    composer require wpackagist-plugin/simple-page-ordering:$WP_PLUGIN_VERSION_SIMPLE_PAGE_ORDERING && \
    composer require wpackagist-plugin/simple-comment-editing:$WP_PLUGIN_VERSION_SIMPLE_COMMENT_EDITING && \
    composer require wpackagist-plugin/safe-svg:$WP_PLUGIN_VERSION_SAFE_SVG && \
    composer require wpackagist-plugin/wpo365-login:$WP_PLUGIN_VERSION_WPO365_LOGIN && \
    composer require wpackagist-plugin/wpo365-samesite:$WP_PLUGIN_VERSION_WPO365_SAMESITE && \
    composer require wpackagist-plugin/taxonomy-terms-order:$WP_PLUGIN_VERSION_TAXONOMY_TERMS_ORDER && \
    composer require wpackagist-plugin/wp-sentry-integration:$WP_PLUGIN_VERSION_WP_SENTRY_INTEGRATION && \
    composer require wpackagist-plugin/wp-security-audit-log:$WP_PLUGIN_VERSION_WP_SECURITY_AUDIT_LOG && \
    composer require wpackagist-plugin/wp-piwik:$WP_PLUGIN_VERSION_CONNECT_MATOMO && \
    rm -f /opt/app-root/src/.config/composer/auth.json
