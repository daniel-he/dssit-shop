<?php
// HTTP
define('HTTP_SERVER', 'http://localhost/~irischan/dssit-shop/');

// HTTPS
define('HTTPS_SERVER', 'http://localhost/~irischan/dssit-shop/');

// DIR
define('DIR_APPLICATION', '/Users/irischan/Sites/dssit-shop/catalog/');
define('DIR_SYSTEM', '/Users/irischan/Sites/dssit-shop/system/');
define('DIR_DATABASE', '/Users/irischan/Sites/dssit-shop/system/database/');
define('DIR_LANGUAGE', '/Users/irischan/Sites/dssit-shop/catalog/language/');
define('DIR_TEMPLATE', '/Users/irischan/Sites/dssit-shop/catalog/view/theme/');
define('DIR_CONFIG', '/Users/irischan/Sites/dssit-shop/system/config/');
define('DIR_IMAGE', '/Users/irischan/Sites/dssit-shop/image/');
define('DIR_CACHE', '/Users/irischan/Sites/dssit-shop/system/cache/');
define('DIR_DOWNLOAD', '/Users/irischan/Sites/dssit-shop/download/');
define('DIR_LOGS', '/Users/irischan/Sites/dssit-shop/system/logs/');

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'dssitshop');
define('DB_PREFIX', 'oc_');

// CAS
define('CAS_SERVER_CA_CERT_PATH', '/Users/irischan/Sites/dssit-shop/cas.pem');
define('CAS_HOST', 'cas.ucdavis.edu');
define('CAS_CONTEXT', '');
define('CAS_PORT', 443);

// LDAP
define('LDAP_HOST', 'ldap.ucdavis.edu');
define('LDAP_SEARCH_BASE', 'ou=People,dc=ucdavis,dc=edu');

// Sysaid
define('SYSAID_HOST', 'https://sysaid.dss.ucdavis.edu');
define('SYSAID_WSDL', 'https://sysaid.dss.ucdavis.edu//services/SysaidApiService?wsdl');
define('SYSAID_ACCOUNT', 'ucdavis');

// Roles Management
define('ROLES_MANAGEMENT_API', 'roles.dss.ucdavis.edu/API');
define('ROLES_MANAGEMENT_APPNAME', 'DSS Cart');
define('ROLES_MANAGEMENT_APPID', 68);
define('ROLES_MANAGEMENT_SECRET', '6338212d37aff940f4594383cd8ca916');
?>