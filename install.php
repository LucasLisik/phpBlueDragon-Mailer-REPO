<?php

// GitHubProjects/phpBlueDragonMailer/

// Pole e-mail
error_reporting(E_ALL & ~E_NOTICE);

session_start();

//echo $_POST['select_language'];

if($_POST['change_language'] == 'yes')
{
    $_SESSION['language_install'] = $_POST['select_language'];
}
    
if($_SESSION['language_install'] == '')
{
    $_SESSION['language_install'] = 'english';
}

$LanguageSelected = $_SESSION['language_install'];
    
$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]attachment (
  attachment_id int(11) NOT NULL AUTO_INCREMENT,
  attachment_user_id int(11) NOT NULL,
  attachment_message_id int(11) NOT NULL,
  attachment_file varchar(255) NOT NULL,
  PRIMARY KEY (attachment_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]config (
  config_id int(11) NOT NULL AUTO_INCREMENT,
  config_name varchar(15) NOT NULL,
  config_value varchar(255) NOT NULL,
  PRIMARY KEY (config_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";


$SqlQuert[] = "INSERT INTO [DBPREFIX]config (config_id, config_name, config_value) VALUES
(1, 'title', 'phpBlueDragon Mailer'),
(2, 'description', 'Mailing system - phpBlueDragon'),
(3, 'keywords', 'mailing, system, mailing system'),
(4, 'root_email', '[USER_EMAIL]'),
(5, 'foot', 'Copyright &copy; 2015-2017 by <a href=\"http://phpbluedragon.eu\">phpBlueDragon Mailer</a> &amp; <a href=\"http://en.lukasz.sos.pl\">Lukas SOS</a>');";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]config_int (
  config_id int(11) NOT NULL AUTO_INCREMENT,
  config_name varchar(25) NOT NULL,
  config_value int(11) NOT NULL,
  PRIMARY KEY (config_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;";

$SqlQuert[] = "INSERT INTO [DBPREFIX]config_int (config_id, config_name, config_value) VALUES
(1, 'cron_howmany', 10);";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]date (
  date_id int(11) NOT NULL AUTO_INCREMENT,
  date_value datetime NOT NULL,
  PRIMARY KEY (date_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;";

$SqlQuert[] = "INSERT INTO [DBPREFIX]date (date_id, date_value) VALUES
(1, '2015-08-29 00:00:00');";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]email (
  email_id int(11) NOT NULL AUTO_INCREMENT,
  email_title varchar(255) NOT NULL,
  email_content text NOT NULL,
  email_what varchar(15) NOT NULL,
  email_desc text NOT NULL,
  PRIMARY KEY (email_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";

if($_SESSION['language_install'] == 'polish')
{
	$SqlQuert[] = "INSERT INTO [DBPREFIX]email (email_id, email_title, email_content, email_what, email_desc) VALUES
	(1, 'Konto utworzone w serwisie phpBlueDragon Mailer', 'Dziękujemy za rejestrację [user_name_and_lastname] konta z adresu: [user_email]\n\nKliknij link\n[link_activate]\naby uaktywnić swoje konto na stronie WWW.\n\nRejestracji dokonano: [user_date]\nz komputera o IP: [user_ip]', 'register', '[user_name_and_lastname] - imię i nazwisko\r\n[user_email] - e-mail\r\n[link_activate] - łącze aktywacji konta\r\n[user_date] - data rejestracji\r\n[user_ip] - IP komputera podczas rejestracji'),
	(2, 'Przypominanie hasła w serwisie phpBlueDragon Mailer', 'Dzień dobry\n\nZażądałeś przypomnienia hasła. W tym celu kliknij w link:\n[change_password]\n\nProśba o przypomnienie została wysłana [user_date]\nz komputera o IP: [user_ip]', 'recpassword', '[change_password] - łącze zmiany hasła\r\n[user_date] - data wysłania\r\n[user_ip] - IP komputera'),
	(3, 'Twoje hasło zostało zmienione w serwisie phpBlueDragon Mailer', 'Nowe hasło do serwisu:\n\n[new_password]\n\nNowe hasło zostało wygenerowane: [user_date]\nz komputera o IP [user_ip]', 'newpass', '[new_password] - hasło do serwisu\r\n[user_date] - data wysłania\r\n[user_ip] - IP komputera'),
	(4, 'Konto utworzone przez administratora w systemie phpBlueDragon Mailer', 'Konto zostało utworzone\n\nLogin: [user_email]\nHasło: [user_password]\n\nZaloguj się na stronie [base_url]', 'regsub', '[user_email] - e-mail\r\n[user_password] - hasło\r\n[base_url] - adres URL strony')
	";
}
else
{
	$SqlQuert[] = "INSERT INTO [DBPREFIX]email (email_id, email_title, email_content, email_what, email_desc) VALUES
	(1, 'Account created on the site phpBlueDragon Mailer', 
	'Thank you for registering [user_name_and_lastname] account with the address: [user_email]\n\nClick on the link\n[link_activate]\nto activate your account on the website.\n\nRegistration was made: [user_date]\nPC IP: [user_ip] ','register','[user_name_and_lastname] - name\r\n[user_email] - e-mail\r\n[link_activate] - link to activate your account\r\n[user_date] - date of registration\r\n[user_ip] - the computer\'s IP when registering'),
	(2, 'Password reminder on the site phpBlueDragon Mailer', 'Good morning,\n\nYou requested a password reminder. To do this, click on the link: \n[change_password]\n\nRequest reminder was sent: [user_date]\nComputer with the IP: [user_ip] ','recpassword',' [change_password] - link to change your password\r\n[user_date] - the date of sending\r\n[user_ip] - computer IP'),
	(3, 'Your password has been changed on the site phpBlueDragon Mailer', 'The new password to the site:\n\n[new_password]\n\nNew password was generated: [user_date]\nPC IP: [user_ip]','newpass','[new_password] - password to the site\r\n[user_date] - the date of sending\r\n[user_ip] - computer IP'),
	(4, 'Account created by the administrator in the system phpBlueDragon Mailer', 'Account created\n\nUsername: [user_email]\nPassword: [user_password]\n\nLog on: [base_url]','regsub','[user_email] - E-mail\r\n[user_password] - password\r\n[base_url] - The URL of the page')
	";
}

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]exclusion (
  exclusion_id int(11) NOT NULL AUTO_INCREMENT,
  exclusion_email varchar(255) NOT NULL,
  PRIMARY KEY (exclusion_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]groups (
  groups_id int(11) NOT NULL AUTO_INCREMENT,
  groups_name varchar(255) NOT NULL,
  groups_many int(11) NOT NULL,
  groups_multi char(1) NOT NULL,
  groups_user int(11) NOT NULL,
  PRIMARY KEY (groups_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";


$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]groups_email (
  email_id int(11) NOT NULL AUTO_INCREMENT,
  email_groups_id int(11) NOT NULL,
  email_email varchar(255) NOT NULL,
  email_date datetime NOT NULL,
  email_title varchar(255) NOT NULL,
  email_name varchar(255) NOT NULL,
  email_lastname varchar(255) NOT NULL,
  email_initial varchar(255) NOT NULL,
  email_address1 varchar(255) NOT NULL,
  email_address2 varchar(255) NOT NULL,
  email_city varchar(255) NOT NULL,
  email_state varchar(255) NOT NULL,
  email_postcode varchar(255) NOT NULL,
  email_country varchar(255) NOT NULL,
  email_firm varchar(255) NOT NULL,
  email_jobtitle varchar(255) NOT NULL,
  email_network varchar(255) NOT NULL,
  email_phone varchar(255) NOT NULL,
  email_fax varchar(255) NOT NULL,
  email_field1 varchar(255) NOT NULL,
  email_field2 varchar(255) NOT NULL,
  email_field3 varchar(255) NOT NULL,
  email_field4 varchar(255) NOT NULL,
  PRIMARY KEY (email_id),
  KEY email_groups_id (email_groups_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]log (
  log_id int(11) NOT NULL AUTO_INCREMENT,
  log_user_id int(11) NOT NULL,
  log_what varchar(255) NOT NULL,
  log_time datetime NOT NULL,
  log_ip varchar(15) NOT NULL,
  PRIMARY KEY (log_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]login (
  login_id int(11) NOT NULL AUTO_INCREMENT,
  login_ip varchar(15) NOT NULL,
  login_time varchar(10) NOT NULL,
  PRIMARY KEY (login_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]messages (
  message_id int(11) NOT NULL AUTO_INCREMENT,
  message_view char(1) NOT NULL,
  message_type char(5) NOT NULL,
  message_title varchar(255) NOT NULL,
  message_html text NOT NULL,
  message_text text NOT NULL,
  message_from int(11) NOT NULL,
  message_to int(11) NOT NULL,
  message_date datetime NOT NULL,
  message_end_date datetime NOT NULL,
  message_planned_date datetime NOT NULL,
  message_user int(11) NOT NULL,
  message_sign int(11) NOT NULL,
  message_stat varchar(100) NOT NULL,
  message_sending char(1) NOT NULL,
  message_howmanysend int(11) NOT NULL,
  message_break char(1) NOT NULL,
  PRIMARY KEY (message_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]password (
  password_id int(11) NOT NULL AUTO_INCREMENT,
  password_user_id int(11) NOT NULL,
  password_hash1 varchar(20) NOT NULL,
  password_hash2 varchar(20) NOT NULL,
  password_time datetime NOT NULL,
  password_ip varchar(15) NOT NULL,
  password_used char(1) NOT NULL,
  PRIMARY KEY (password_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]report (
  report_id int(11) NOT NULL AUTO_INCREMENT,
  report_mailing_id int(11) NOT NULL,
  report_message mediumtext NOT NULL,
  PRIMARY KEY (report_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]send (
  send_id int(11) NOT NULL AUTO_INCREMENT,
  send_name varchar(255) NOT NULL,
  send_name_account varchar(255) NOT NULL,
  send_organization varchar(150) NOT NULL,
  send_from varchar(150) NOT NULL,
  send_reply varchar(150) NOT NULL,
  send_smtp_serwer varchar(255) NOT NULL,
  mailing_port int(11) NOT NULL,
  send_auth char(1) NOT NULL,
  send_break_every int(11) NOT NULL,
  send_break_time int(11) NOT NULL,
  send_login varchar(255) NOT NULL,
  send_pswd varchar(255) NOT NULL,
  send_user int(11) NOT NULL,
  send_port int(11) NOT NULL,
  PRIMARY KEY (send_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]send_email (
  email_id int(11) NOT NULL AUTO_INCREMENT,
  email_email_id int(11) NOT NULL,
  email_message_id int(11) NOT NULL,
  email_send text NOT NULL,
  PRIMARY KEY (email_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]signatures (
  signatures_id int(11) NOT NULL AUTO_INCREMENT,
  signatures_name varchar(255) NOT NULL,
  signatures_html text NOT NULL,
  signatures_text text NOT NULL,
  signatures_user int(11) NOT NULL,
  PRIMARY KEY (signatures_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]track (
  track_id int(11) NOT NULL AUTO_INCREMENT,
  track_mailing_id int(11) NOT NULL,
  track_email_id int(11) NOT NULL,
  track_date datetime NOT NULL,
  track_ip varchar(15) NOT NULL,
  PRIMARY KEY (track_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$SqlQuert[] = "CREATE TABLE IF NOT EXISTS [DBPREFIX]user (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  user_email varchar(90) NOT NULL,
  user_password varchar(255) NOT NULL,
  user_password_clear varchar(255) NOT NULL,
  user_street varchar(200) NOT NULL,
  user_city varchar(150) NOT NULL,
  user_code varchar(10) NOT NULL,
  user_sub char(1) NOT NULL,
  user_sub_id int(11) NOT NULL,
  user_nip varchar(15) NOT NULL,
  user_firm_name varchar(160) NOT NULL,
  user_name_and_lastname varchar(200) NOT NULL,
  user_tel varchar(20) NOT NULL,
  user_fax varchar(20) NOT NULL,
  user_active char(1) NOT NULL,
  user_date_register datetime NOT NULL,
  user_ip varchar(15) NOT NULL,
  user_key varchar(20) NOT NULL,
  user_package int(11) NOT NULL,
  user_package_date datetime NOT NULL,
  user_root char(1) NOT NULL,
  user_ban char(1) NOT NULL,
  PRIMARY KEY (user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;";

$SqlQuert[] = "ALTER TABLE [DBPREFIX]user
ADD UNIQUE(user_email);";

$SqlQuert[] = "INSERT INTO [DBPREFIX]user (user_id, user_email, user_password, user_password_clear, user_street, user_city, user_code, user_sub, user_sub_id, user_nip, user_firm_name, user_name_and_lastname, user_tel, user_fax, user_active, user_date_register, user_ip, user_key, user_package, user_package_date, user_root, user_ban) VALUES
(1, '[USER_EMAIL]', '[USER_PASSWORD]', '', '', '', '', '', 0, '', '', '', '', '', 'y', '2015-07-10 18:21:03', '127.0.0.1', 'JWTSiC04nMnrEj9oiV7X', 0, '0000-00-00 00:00:00', '', ''),
(2, 'CRON', 'cronnull', '', '', '', '', '', 0, '', '', '', '', '', 'n', '2015-07-10 18:21:03', '127.0.0.1', 'cronnull', 0, '0000-00-00 00:00:00', '', '');";

$SqlQuert[] = "ALTER TABLE [DBPREFIX]groups_email
  ADD CONSTRAINT [DBPREFIX]groups_email_ibfk_1 FOREIGN KEY (email_groups_id) REFERENCES [DBPREFIX]groups (groups_id) ON DELETE CASCADE ON UPDATE NO ACTION;";

$SqlQuert[] = "ALTER TABLE [DBPREFIX]send ADD send_access VARCHAR(4) NOT NULL AFTER send_port;";
  

function GetLang($Line)
{
    if($_SESSION['language_install'] == 'polish')
    {
    	$lang['install_title'] = 'Instalator programu';
    	$lang['install_select_language'] = 'Wybór języka';
    	$lang['install_set_language'] = 'Wybierz język: ';
    	$lang['install_nex_step_button'] = 'Następny krok';
    	$lang['install_check_requirements'] = 'Sprawdzenie wymagań';
    	$lang['install_version_php'] = 'Wersja PHP (przynajmniej 7.0.0): ';
    	$lang['install_akcept'] = 'Akceptuje';
    	$lang['install_not_akcept'] = 'Brak lub wersja zbyt niska';
    	$lang['install_function_steam_socket'] = 'Funkcja - stream_socket_client(): ';
    	$lang['install_mod_rewrite'] = 'Mod Rewrite: ';
    	$lang['install_reqbrief'] = 'Oprócz poniższych wymagań serwerem powinien być <strong>Apache</strong>, potrzebuje modułu: <strong>mod_rewrite</strong> oraz bazę danych <strong>MySQL w wersji 5 lub wyższej</strong>. Po stronie klienta (Twoim) powinna znajdować się przeglądarka potrafiąca obsługiwać funkcje <strong>AJAX (jQuery)</strong> (są to wszystkie najpopularniejsze przeglądarki, pamiętaj o tym aby aktywować <strong>JavaScript</strong>, jeżeli jest nieaktywny).';
    	$lang['install_reqdont'] = 'Serwer nie spełnia wymagań minimalnych - instalacja jest niemożliwa.';
    	$lang['install_mysql_lib'] = 'Biblioteka MySQL/MariaDB: ';
    	$lang['install_config_path'] = 'Konfiguracja ścieżki';
    	$lang['install_address_url'] = 'Adres URL lokalizacji systemu: ';
    	$lang['install_url_is_not_valid'] = 'Adres URL jest niepoprawny';
    	$lang['install_config_path_error'] = 'Adres URL lokalizacji systemu';
    	$lang['install_post_server'] = 'Serwer pocztowy';
    	$lang['install_catalog_access'] = 'Dostęp do katalogów';
    	$lang['install_calogname'] = 'Katalog: ';
    	$lang['install_catalogcom1'] = 'Uprawnienia poprawne';
    	$lang['install_catalogcom2'] = 'Brak uprawnień - system nie będzie działał prawidłowo';
    	$lang['install_filename'] = 'Dostęp do plików';
    	$lang['install_catalogbrief'] = 'Dostęp do katalogów powienien zostać ustawiony w sposób, aby można było tworzyć w nich katalogi oraz zapisywać pliki. W systemie Linux możesz nadać im odpowiednie prawa poprzez komendę: <strong>chmod 777 NazwaKatalogu</strong>.';
    	$lang['install_filebrief'] = 'Dostęp do plików powienien zostać ustawiony w sposób, aby można było w nich zapisywać dane. W systemie Linux możesz nadać im odpowiednie prawa poprzez komendę: <strong>chmod 666 NazwaPliku</strong>.';
    	$lang['install_filenameone'] = 'Plik: ';
    	$lang['install_serversmtp'] = 'Serwer SMTP';
    	$lang['install_serversmtpbrief'] = 'Podaj dane do konta z którego mają być wysyłane informacje przeznaczone do wysyłania listów e-mial przez system, z danymi podczas rejestracji konta, przypominaniem hasła itp.';
    	$lang['install_smtpname'] = 'Nazwa osoby/firmy wysyłającej e-mail: ';
    	$lang['install_smtpaddress'] = 'Adres serwera SMTP: ';
    	$lang['install_smtplogin'] = 'Login do serwera SMTP: ';
    	$lang['install_smtppswd'] = 'Hasło do serwera SMTP: ';
    	$lang['install_smtpport'] = 'Port do serwera SMTP: ';
    	$lang['install_smtpname2'] = 'Nazwa osoby/firmy wysyłającej e-mail';
    	$lang['install_smtpaddress2'] = 'Adres serwera SMTP';
    	$lang['install_smtplogin2'] = 'Login do serwera SMTP';
    	$lang['install_smtppswd2'] = 'Hasło do serwera SMTP';
    	$lang['install_smtpport2'] = 'Port do serwera SMTP';
    	$lang['install_db'] = 'Baza danych';
    	$lang['install_dbbrief'] = 'Podaj dane do konfiguracji połączenia z bazą danych MySQL/MariaDB takie jak: host, nazwa bazy danych, login i hasło oraz prefix dla tabel.';
    	$lang['install_dbhostname'] = 'Nazwa serwera: ';
    	$lang['install_dbusername'] = 'Nazwa użytkownika: ';
    	$lang['install_dbpassword'] = 'Hasło użytkownika: ';
    	$lang['install_dbdatabase'] = 'Nazwa bazy danych: ';
    	$lang['install_dbprefix'] = 'Prefix tabel: ';
    	$lang['install_dbhostname2'] = 'Nazwa serwera: ';
    	$lang['install_dbusername2'] = 'Nazwa użytkownika: ';
    	$lang['install_dbpassword2'] = 'Hasło użytkownika: ';
    	$lang['install_dbdatabase2'] = 'Nazwa bazy danych: ';
    	$lang['install_dbprefix2'] = 'Prefix tabel: ';
    	$lang['install_instalsystembutton'] = 'Zainstaluj system';
    	$lang['install_subbar'] = 'Instalator programu phpBlueDragon Mailer';
    	$lang['install_havespaces'] = 'Ciąg zawiera spacje, powinien zostać zapisany bez spacji';
    	$lang['install_noabilityconnecttodb'] = 'Nie można nawiązać połączenia z bazą danych - sprawdź dane które podałeś.';
    	$lang['install_fieldhastobefilled'] = 'Pole musi zostać wypełnione';
    	$lang['install_fieldisnotnumber'] = 'Pole musi zawierać liczbę całkowitą';
    	$lang['install_errorocured'] = 'Wystąpiły błędy, sprawdź pola poniżej!';
    	$lang['install_changelangsubmit'] = 'Zmień język';
        
        // v.1.beta
        $lang['install2_email_address'] = 'E-mail administratora: ';
        $lang['install2_emailaddrisntcorrect'] = 'Adres e-mail jest niepoprawny'; 
        $lang['install2_gratulations'] = '<h2>Gratulacje</h2>Instalacja została zakończona.<br /><br />Login: <strong>[LOGIN]</strong><br />Hasło: <strong>[PASSWORD]</strong><br /><br /><strong>Pamiętaj, należy usunąć plik &quot;install.php&quot; z serwera.</strong>';
        
        // v.2.beta
    	$lang['install3_port'] = 'Port: ';
    	$lang['install3_acces'] = 'Uwierzytalnianie: ';
    	$lang['install3_text'] = 'Czysty tekst';
    	$lang['install3_tls'] = 'TLS';
    	$lang['install3_tableprefixis'] = 'Tabele z podanym prefixem istnieją już w bazie danych';
        
    	$lang['install_'] = '';
    	$lang['install_'] = '';
    	$lang['install_'] = '';
    	$lang['install_'] = '';
    	$lang['install_'] = '';
    }
    else
    {
        $lang['install_title'] = 'Setup'; 
        $lang['install_select_language'] = 'Select Language'; 
        $lang['install_set_language'] = 'Select Language'; 
        $lang['install_nex_step_button'] = 'Next step'; 
        $lang['install_check_requirements'] = 'Checking requirements'; 
        $lang['install_version_php'] = 'PHP version (at least 7.0.0):';
        $lang['install_akcept'] = 'Accept'; 
        $lang['install_not_akcept'] = 'None or too low'; 
        $lang['install_function_steam_socket'] = 'Function - stream_socket_client()'; 
        $lang['install_mod_rewrite'] = 'Mod Rewrite'; 
        $lang['install_reqbrief'] = 'In addition to following the requirements of the server should be <strong>Apache</strong>, needs module: <strong>mod_rewrite</strong> and the database <strong>MySQL/MariaDB version 5/10 or higher</strong>. On the client side (your) should be the browser that can support the functions of the <strong>AJAX (jQuery)</strong> (they are all popular browsers, remember that to activate <strong>JavaScript</strong> if it is inactive). ';
        $lang['install_reqdont'] = 'The server does not meet the minimum requirements - installation is not possible.'; 
        $lang['install_mysql_lib'] = 'Library MySQL/MariaDB';
        $lang['install_config_path'] = 'Setup path &amp; e-mail address'; 
        $lang['install_address_url'] = 'URL location of the system';  
        $lang['install_url_is_not_valid'] = 'URL is incorrect'; 
        $lang['install_config_path_error'] = 'URL location of the system'; 
        $lang['install_post_server'] = 'Mail server'; 
        $lang['install_catalog_access'] = 'Access to the directories'; 
        $lang['install_calogname'] = 'Rirectory'; 
        $lang['install_catalogcom1'] = 'Permissions correct'; 
        $lang['install_catalogcom2'] = 'No permission - the system will not run properly'; 
        $lang['install_filename'] = 'Access to files'; 
        $lang['install_catalogbrief'] = 'Access directories child should be set in a way that you can create in their catalogs and save files. In Linux, you can give them the appropriate rights by the command: <strong>chmod 777 DirectoryName</strong>. '; 
        $lang['install_filebrief'] = 'Access to file child should be set in a way that you can save data in them. In Linux, you can give them the appropriate rights by the command: <strong>chmod 666 Filename</strong>. '; 
        $lang['install_filenameone'] = 'File:'; 
        $lang['install_serversmtp'] = 'SMTP'; 
        $lang['install_serversmtpbrief'] = 'Provide the account from which to send information intended to send e-letters had by the system, with the data during registration, passwords, etc. reminder.'; 
        $lang['install_smtpname'] = 'Name of person/company sending the e-mail'; 
        $lang['install_smtpaddress'] = 'SMTP server address'; 
        $lang['install_smtplogin'] = 'Login to the SMTP server'; 
        $lang['install_smtppswd'] = 'Password for the SMTP server'; 
        $lang['install_smtpport'] = 'Port to an SMTP server'; 
        $lang['install_smtpname2'] = 'Name of person/company sending the e-mail'; 
        $lang['install_smtpaddress2'] = 'SMTP server address'; 
        $lang['install_smtplogin2'] = 'Login to the SMTP server'; 
        $lang['install_smtppswd2'] = 'Password for the SMTP server'; 
        $lang['install_smtpport2'] = 'Port to an SMTP server'; 
        $lang['install_db'] = 'Database'; 
        $lang['install_dbbrief'] = 'Enter the configuration data to connect to the MySQL/MariaDB database, such as: host, database name, username, password and prefix for tables.';
        $lang['install_dbhostname'] = 'Name server'; 
        $lang['install_dbusername'] = 'Username:'; 
        $lang['install_dbpassword'] = 'User password'; 
        $lang['install_dbdatabase'] = 'Database name'; 
        $lang['install_dbprefix'] = 'Prefix for tables:'; 
        $lang['install_dbhostname2'] = 'Name server'; 
        $lang['install_dbusername2'] = 'Username:'; 
        $lang['install_dbpassword2'] = 'User password'; 
        $lang['install_dbdatabase2'] = 'Database name'; 
        $lang['install_dbprefix2'] = 'Prefix for tables:'; 
        $lang['install_instalsystembutton'] = 'Install'; 
        $lang['install_subbar'] = 'Setup phpBlueDragon Mailer'; 
        $lang['install_havespaces'] = 'String contains spaces, it should be written without spaces'; 
        $lang['install_noabilityconnecttodb'] = 'Unable to connect to the database - check the data you have entered.'; 
        $lang['install_fieldhastobefilled'] = 'Field must be filled'; 
        $lang['install_fieldisnotnumber'] = 'Field must contain an integer'; 
        $lang['install_errorocured'] = 'There were errors, check the box below'; 
        $lang['install_changelangsubmit'] = 'Change language';
        
        // v.1.beta
        $lang['install2_email_address'] = 'E-mail for main user: ';
        $lang['install2_emailaddrisntcorrect'] = 'The email address is invalid'; 
        //$lang['install2_gratulations'] = '<h2>Gratulacje</h2>Instalacja została zakończona.<br /><br />Login: <strong>[LOGIN]</strong><br />Hasło: <strong>[PASSWORD]</strong><br /><br /><strong>Pamiętaj, należy usunąć plik &quot;install.php&quot; z serwera.</strong>'; 
        $lang['install2_gratulations'] = '<h2>Congratulations</h2>Installation is complete.<br /><br />Login: <strong>[LOGIN]</strong><br />Password: <strong>[PASSWORD]</strong><br /><br /><strong>Please note, delete the file &quot;install.php&quot; from the server.</strong> ';
        
        // v.2.beta
    	$lang['install3_port'] = 'Port: ';
    	$lang['install3_acces'] = 'Authentication: ';
    	$lang['install3_text'] = 'TEXT';
    	$lang['install3_tls'] = 'TLS';
        $lang['install3_tableprefixis'] = 'The tables with the prefix already exist in the database';
        
        $lang['install_'] = ''; 
        $lang['install_'] = ''; 
        $lang['install_'] = ''; 
        $lang['install_'] = ''; 
        $lang['install_'] = ''; 
        $lang['install_'] = ''; 
        $lang['install_'] = ''; 
        $lang['install_'] = '';
    }
	
	return $lang[$Line];
    
    /*
    X - Grupy - brak maili - pokaz komunikat
    X - Brak grupy lub konta - nie pokazywanie dodawania nowej wiadomości - tylko komunikat
    X - Brak grupy lub konta - edycja wiadomości, bez pokazywania formularza, a tylko okno do zapytania o nową grupę i konto
    X - Terminarz - brak wiadomości - brak określenia terminu
    
    Gratulacje
    Instalacja została zakończona.
    
    Login: lukasz.bluedragon@gmail.com
    Hasło: JjNiqjaHNaxNMNG
    
    Pamiętaj, należy usunąć plik "install.php" z serwera.	
    */
}

function PrintHead()
{
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<title><?php echo GetLang('install_title'); ?></title>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=9" />
		<link rel="stylesheet" type="text/css" href="library/style.css" />
		<link rel="Shortcut icon" href="favicon.ico" />
	</head>
	<body>
		<div class="TopMenu" style="width: 100%; height: 40px;">
			<div style="width: 980px; height: 40px; margin-left: auto; margin-right: auto; padding-top: 0px; text-align: left;">
			
			<h2><?php echo GetLang('install_title'); ?></h2>
			
		 </div>
		</div>

		<div style="width: 100%; height: 30px; background-color: #E7E7E7; bnorder-top: solid 1px #D0D0D0; border-bottom: solid 1px #D0D0D0;">
			<div style="width: 980px; height: 30px; margin-left: auto; margin-right: auto; font-weight: bold; padding-top: 7px; font-family: 'Trebuchet MS'; color: #555E5E;">
			<?php
			echo GetLang('install_subbar');
			?>
			</div>
		</div>

	<div style="width: 980px; margin-left: auto; margin-right: auto; padding-top: 10px;">
	<div style="clear: both;"></div>
	<div>
	<?php
}

function PrintFoot()
{
	?>
	</div>
	</div>
	<?php
	/*echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';*/
	?>
	</body>
	</html>
	<?php
}

PrintHead();

if($_POST['step'] == "")
{

        
	if($_POST['formlogin'] == 'yes')
	{
		$IsError = false;
		
        if($_POST['page_email_addr'] == "")
		{
			$Error['EmailEmptyAddr'] = true;
			$IsError = true;
		}
        
        if($_POST['page_email_addr'] != "")
		{
            if(!filter_var($_POST['page_email_addr'], FILTER_VALIDATE_EMAIL)) 
            {
                $Error['EmailIncorrectAddr'] = true;
                $IsError = true;
            }
        }

		if($_POST['page_url'] == "")
		{
			$Error['PageUrlEmpty'] = true;
			$IsError = true;
		}
		
		if($_POST['page_url'] != "")
		{
			if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $_POST['page_url']))
			{
				$Error['PageUrlNotValid'] = true;
				$IsError = true;
			}
		}
		
		if($_POST['send_email_user_name'] == "")
		{
			$Error['EMPTYsend_email_user_name'] = true;
			$IsError = true;
		}
		
		if($_POST['send_email_stmp_host'] == "")
		{
			$Error['EMPTYsend_email_stmp_host'] = true;
			$IsError = true;
		}
		
		if($_POST['send_email_stmp_username'] == "")
		{
			$Error['EMPTYsend_email_stmp_username'] = true;
			$IsError = true;
		}
		
		if($_POST['send_email_stmp_password'] == "")
		{
			$Error['EMPTYsend_email_stmp_password'] = true;
			$IsError = true;
		}
		
		if($_POST['send_email_stmp_port'] == "")
		{
			$Error['EMPTYsend_email_stmp_port'] = true;
			$IsError = true;
		}
		
		if($_POST['send_email_stmp_port'] != "")
		{
			if(!preg_match('/^\d+$/', $_POST['send_email_stmp_port']))
			{
				$Error['send_email_stmp_portNotValid'] = true;
				$IsError = true;
			}
		}
		
		if($_POST['hostname'] == "")
		{
			$Error['EMPTYhostname'] = true;
			$IsError = true;
		}
		
		if($_POST['username'] == "")
		{
			$Error['EMPTYusername'] = true;
			$IsError = true;
		}
		
		if($_POST['password'] == "")
		{
			$Error['EMPTYpassword'] = true;
			$IsError = true;
		}
		
		if($_POST['database'] == "")
		{
			$Error['EMPTYdatabase'] = true;
			$IsError = true;
		}
		
		if($_POST['dbprefix'] == "")
		{
			$Error['EMPTYdbprefix'] = true;
			$IsError = true;
		}
                
        if($_POST['dbport'] == "")
		{
			$Error['EMPTYdbport'] = true;
			$IsError = true;
		}
        
        if($_POST['dbport'] != "")
		{
			if(!preg_match('/^\d+$/', $_POST['dbport']))
			{
				$Error['database_port_stmp_portNotValid'] = true;
				$IsError = true;
			}
		}
		
		if($_POST['hostname'] != "" AND $_POST['username'] != "" AND $_POST['password'] != "" AND $_POST['database'] != "")
		{			
			$ConnectionLink = mysqli_connect($_POST['hostname'], $_POST['username'], $_POST['password'], $_POST['database'],$_POST['dbport']);

			if (!$ConnectionLink) 
			{
				$Error['NotConnectedToDB'] = true;
				$IsError = true;
			}
			else
			{
                $ReadyQuery = 'SHOW TABLES LIKE "[DBPREFIX]attachment"';
                $ReadyQuery = str_replace('[DBPREFIX]', $_POST['dbprefix'], $ReadyQuery);
                
                if(mysqli_num_rows(mysqli_query($ConnectionLink, $ReadyQuery)) == 1)
                {
                    $Error['DBTableExists'] = true;
    				$IsError = true;
                }
                
				mysqli_close($ConnectionLink);
			}
		}
        
        if($IsError == false)
        {
            //header("Location: install.php?step=data");
            //die();
            $_POST['step'] = 'data';
        }
	}

	if($IsError == true)
	{
		echo '<div style="padding: 10px; color: #600000; font-weight: bold; text-align: center; font-size: 18px;">'.GetLang('install_errorocured').'</div>';
	}
	
	/*echo '<script>
	function ChangeLanguage()
	{
		//var lang = document.getElementsByName("select_language")[0].name;
        //var lang = document.getElementById("select_language").selectedIndex ;
        var lang = select_language.options[select_language.selectedIndex].value;
		window.location.href = "install.php?lang=" + lang;
	}
	</script>';*/

	//
	// 1
	//
    if($_POST['step'] == "")
    {
    	echo '<h2>1. '.GetLang('install_select_language').'</h2>';
    
    	echo '<form action="install.php" method="post">';
        
    	echo '<div class="BorderDiv">';
    
    	if($_SESSION['language_install'] == 'polish')
    	{
    		$VarPlSelected = ' selected="selected" ';
    	}
    	
    	if($_SESSION['language_install'] == 'english')
    	{
    		$VarEnSelected = ' selected="selected" ';
    	}
        
    	echo GetLang('install_set_language').' <br /> '.'
    	<select name="select_language" id="select_language" style = "width: 100%;">
    		<option value="english" '.$VarEnSelected.'>English</option>
    		<option value="polish" '.$VarPlSelected.'>Polski</option>
    	</select>'.'<br />';
    
    	echo '<br />';
        
        echo '<input type="hidden" name="change_language" value="yes" />';
    	echo '<input type="submit" name="change_lang_submit" value="'.GetLang('install_changelangsubmit').'" style = "width: 100%;" />';
        
    	echo '</div>';
        echo '</form>';
        
        echo '<form action="install.php" method="post">';
    	//
    	// 2
    	//
    	echo '<h2 style="margin-top: 15px;">2. '.GetLang('install_check_requirements').'</h2>';
    
    	$ReqIs = true;
    
    	echo '<div class="BorderDiv">';
    
    	echo '<p style="padding: 10px;">'.GetLang('install_reqbrief').'</p>';
    
    	echo '<strong>'.GetLang('install_version_php').'</strong><br />';
    	echo phpversion();
    
    	if (version_compare(PHP_VERSION, '7.0.0') >= 0)
    	{
    		echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_akcept').'</div>';
    	}
    	else
    	{
    		$ReqIs = false;
    		
    		echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_not_akcept').'</div>';
    	}
    
    	echo '<strong>'.GetLang('install_function_steam_socket').'</strong><br />';
    
    	if (function_exists('stream_socket_client')) 
    	{
    		echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_akcept').'</div>';
    	}
    	else
    	{
    		$ReqIs = false;
    		
    		echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_not_akcept').'</div>';
    	}
    
    	echo '<strong>'.GetLang('install_mysql_lib').'</strong><br />';
    
    	if (function_exists('mysqli_connect')) 
    	{
    		echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_akcept').'</div>';
    	}
    	else
    	{
    		$ReqIs = false;
    		
    		echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_not_akcept').'</div>';
    	}
    
    	
    	echo '</div>';
    	
    	//
    	// 3
    	//
        echo '<h2 style="margin-top: 15px;">3. '.GetLang('install_catalog_access').'</h2>';
        
        echo '<div class="BorderDiv">';
        
        echo '<p style="padding: 10px;">'.GetLang('install_catalogbrief').'</p>';
        
        echo GetLang('install_calogname').' <strong>&quot;uploads&quot;</strong>';
        if(is_writable('uploads')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}
        
        echo GetLang('install_calogname').' <strong>&quot;thumbs&quot;</strong>';
        if(is_writable('thumbs')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}
        
        echo GetLang('install_calogname').' <strong>&quot;import&quot;</strong>';
        if(is_writable('import')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}
        
        echo GetLang('install_calogname').' <strong>&quot;captcha&quot;</strong>';
        if(is_writable('captcha')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}
        
        echo GetLang('install_calogname').' <strong>&quot;attachment&quot;</strong>';
        if(is_writable('attachment')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}
        
        echo '</div>';
        
    	//
    	// 4
    	//
        echo '<h2 style="margin-top: 15px;">4. '.GetLang('install_filename').'</h2>';
        
        echo '<div class="BorderDiv">';
        
        echo '<p style="padding: 10px;">'.GetLang('install_filebrief').'</p>';
        
        echo GetLang('install_filenameone').' <strong>&quot;application/config/autoload.php&quot;</strong>';
        if(is_writable('application/config/autoload.php')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}
        
        echo GetLang('install_filenameone').' <strong>&quot;application/config/config.php&quot;</strong>';
        if(is_writable('application/config/config.php')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}
        
        echo GetLang('install_filenameone').' <strong>&quot;application/config/database.php&quot;</strong>';
        if(is_writable('application/config/database.php')){echo '<div style="padding: 10px; color: #27A2CF; font-weight: bold;">'.GetLang('install_catalogcom1').'</div>';}
        else{$ReqIs = false;echo '<div style="padding: 10px; color: #600000; font-weight: bold;">'.GetLang('install_catalogcom2').'</div>';}    
        
        echo '</div>';
        
    	//
    	// 5
    	//
    	echo '<h2 style="margin-top: 15px;">5. '.GetLang('install_config_path').'</h2>';
        
    	echo '<div class="BorderDiv">';
    	
    	if($_POST['page_url'] == '')
    	{
    		$_POST['page_url'] = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    		$_POST['page_url'] = str_replace('install.php', '', $_POST['page_url']);
    	}
    
    	echo GetLang('install_address_url').' <br /><input type="text" name="page_url" style = "width:100%" value = "'.$_POST['page_url'].'" /><br />';
    	if($Error['PageUrlEmpty'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
    	if($Error['PageUrlNotValid'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_url_is_not_valid').'</div>';
    	}
    	
        echo '<br />'.GetLang('install2_email_address').' <br /><input type="text" name="page_email_addr" style = "width:100%" value = "'.$_POST['page_email_addr'].'" /><br />';
    	if($Error['EmailEmptyAddr'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
        
        if($Error['EmailIncorrectAddr'] == true)
    	{
    		echo '<div class="error">'.GetLang('install2_emailaddrisntcorrect').'</div>';
    	}
        
    	echo '</div>';
    	
    	//
    	// 6
    	//
    	
    	echo '<h2 style="margin-top: 15px;">6. '.GetLang('install_serversmtp').'</h2>';
        
        echo '<div class="BorderDiv">';
        
        echo '<p style="padding: 10px;">'.GetLang('install_serversmtpbrief').'</p>';
    
        echo GetLang('install_smtpname').' <br /><input type="text" name="send_email_user_name" style = "width:100%" value = "'.$_POST['send_email_user_name'].'" /><br />';
    	if($Error['EMPTYsend_email_user_name'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    
        echo '<br />';
        
        echo GetLang('install_smtpaddress').' <br /><input type="text" name="send_email_stmp_host" style = "width:100%" value = "'.$_POST['send_email_stmp_host'].'" /><br />';
    	if($Error['EMPTYsend_email_stmp_host'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
        echo '<br />';
        
        echo GetLang('install_smtplogin').' <br /><input type="text" name="send_email_stmp_username" style = "width:100%" value = "'.$_POST['send_email_stmp_username'].'" /><br />';
    	if($Error['EMPTYsend_email_stmp_username'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
        echo '<br />';
        
        echo GetLang('install_smtppswd').' <br /><input type="password" name="send_email_stmp_password" style = "width:100%" value = "'.$_POST['send_email_stmp_password'].'" /><br />';
    	if($Error['EMPTYsend_email_stmp_password'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
        echo '<br />';
        
        if($_POST['send_email_stmp_port'] == "")
        {
            $_POST['send_email_stmp_port'] = 587;
        }
        
        echo GetLang('install_smtpport').' <br /><input type="text" name="send_email_stmp_port" style = "width:50px;" value = "'.$_POST['send_email_stmp_port'].'" /><br />';
    	if($Error['EMPTYsend_email_stmp_port'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
    	if($Error['send_email_stmp_portNotValid'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldisnotnumber').'</div>';
    	}
    	
        echo '<br />';
        
        $SelectValue1 = null;
        $SelectValue2 = null;
        
        if($_POST['smtp_access'] == ""){$SelectValue1 = ' checked="checked" ';}
        if($_POST['smtp_access'] == "text"){$SelectValue1 = ' checked="checked" ';}
        if($_POST['smtp_access'] == "tls"){$SelectValue2 = ' checked="checked" ';}
        
        echo GetLang('install3_acces').' <br />
        <input type="radio" name="smtp_access" value="text" '.$SelectValue1.' /> '.GetLang('install3_text').'
        <input type="radio" name="smtp_access" value="tls" '.$SelectValue2.' /> '.GetLang('install3_tls').'<br />';
        echo '</div>';
        
    	//
    	// 7
    	//
        echo '<h2 style="margin-top: 15px;">7. '.GetLang('install_db').'</h2>';
        
        echo '<div class="BorderDiv">';
    	
        echo '<p style="padding: 10px;">'.GetLang('install_dbbrief').'</p>';
    	
    	echo GetLang('install_dbhostname').' <br /><input type="text" name="hostname" style = "width:100%" value = "'.$_POST['hostname'].'" /><br />';
    	if($Error['EMPTYhostname'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
    	if($Error['NotConnectedToDB'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_noabilityconnecttodb').'</div>';
    	}
    	
        echo '<br />';
    	
    	echo GetLang('install_dbusername').' <br /><input type="text" name="username" style = "width:100%" value = "'.$_POST['username'].'" /><br />';
    	if($Error['EMPTYusername'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
        echo '<br />';
    	
    	echo GetLang('install_dbpassword').' <br /> <input type="password" name="password" style = "width:100%" value = "'.$_POST['password'].'" /><br />';
    	if($Error['EMPTYpassword'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
        echo '<br />';
    	
    	echo GetLang('install_dbdatabase').' <br /> <input type="text" name="database" style = "width:100%" value = "'.$_POST['database'].'" /><br />';
    	if($Error['EMPTYdatabase'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
    	
        echo '<br />';
    	
    	if($_POST['dbprefix'] == "")
    	{
    		$_POST['dbprefix'] = 'mailing_';
    	}
    	echo GetLang('install_dbprefix').' <br /> <input type="text" name="dbprefix" style = "width:150px;" value = "'.$_POST['dbprefix'].'" /><br />';
    	if($Error['EMPTYdbprefix'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
        
        if($Error['DBTableExists'] == true)
        {
            echo '<div class="error">'.GetLang('install3_tableprefixis').'</div>';
        }
    	
        echo '<br />';
    	
        if($_POST['dbport'] == "")
    	{
    		$_POST['dbport'] = '3306';
    	}
    	echo GetLang('install3_port').' <br /> <input type="text" name="dbport" style = "width:150px;" value = "'.$_POST['dbport'].'" /><br />';
    	if($Error['EMPTYdbport'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldhastobefilled').'</div>';
    	}
        
        if($Error['database_port_stmp_portNotValid'] == true)
    	{
    		echo '<div class="error">'.GetLang('install_fieldisnotnumber').'</div>';
    	}
    	
        echo '<br />';
        
        
        echo '</div>';
    	
    	//
    	// 8
    	//
    	echo '<h2 style="margin-top: 15px;">8. '.GetLang('install_instalsystembutton').'</h2>';
    	
    	echo '<div class="BorderDiv">';
    	
    	if($ReqIs)
    	{	
    		echo '<input type="hidden" name="formlogin" value="yes" />';
    		echo '<input type="submit" name="install_aplication" value="'.GetLang('install_instalsystembutton').'" style = "width: 100%;" />';
    	}
    	else
    	{
    		echo '<p style="padding: 10px; color: #600000;">'.GetLang('install_reqdont').'</p>';
    	}
    	
    	echo '</div><br /><br />';
    	
    	echo '</form>';
     }

}

if($_POST['step'] == 'data')
{
	// Konfiguracja frameworka

$ConfigData = 
'<?php

$config[\'base_url\'] = \''.$_POST['page_url'].'\';

$config[\'send_email_user_name\'] = \''.$_POST['send_email_user_name'].'\';
$config[\'send_email_stmp_host\'] = \''.$_POST['send_email_stmp_host'].'\';
$config[\'send_email_stmp_username\'] = \''.$_POST['send_email_stmp_username'].'\';
$config[\'send_email_stmp_password\'] = \''.$_POST['send_email_stmp_password'].'\';
$config[\'send_email_stmp_port\'] = '.$_POST['send_email_stmp_port'].';
$config[\'send_email_access\'] = \''.$_POST['smtp_access'].'\';


$config[\'language\'] = \''.$_SESSION['language_install'].'\';

?>';

	file_put_contents("application/config/config.php", $ConfigData, FILE_APPEND);
	
$DataBaseData = 
'<?php

$db[\'default\'][\'hostname\'] = \''.$_POST['hostname'].'\';
$db[\'default\'][\'username\'] = \''.$_POST['username'].'\';
$db[\'default\'][\'password\'] = \''.$_POST['password'].'\';
$db[\'default\'][\'database\'] = \''.$_POST['database'].'\';
$db[\'default\'][\'dbdriver\'] = \'mysqli\';
$db[\'default\'][\'dbprefix\'] = \''.$_POST['dbprefix'].'\';
$db[\'default\'][\'pconnect\'] = TRUE;
$db[\'default\'][\'db_debug\'] = TRUE;
$db[\'default\'][\'cache_on\'] = FALSE;
$db[\'default\'][\'cachedir\'] = \'\';
$db[\'default\'][\'char_set\'] = \'utf8\';
$db[\'default\'][\'dbcollat\'] = \'utf8_general_ci\';
$db[\'default\'][\'swap_pre\'] = \'{PREFIXDB}\';
$db[\'default\'][\'autoinit\'] = TRUE;
$db[\'default\'][\'stricton\'] = FALSE;
$db[\'default\'][\'port\'] = \''.$_POST['dbport'].'\';


?>';

	file_put_contents("application/config/database.php", $DataBaseData, FILE_APPEND);

	function GenerateRootPassword() 
	{
        $ReadyRandomString = null;
       
	    $Chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	    for ($i=0; $i<15; $i++) 
	    {
	        $ReadyRandomString .= $Chars[rand(0, 39)];
	    }
	    
	    return $ReadyRandomString;
	}
	
    $PasswordString = GenerateRootPassword();
    $SaltPassword = password_hash($PasswordString, PASSWORD_DEFAULT);
     
	// Wgrywanie pliku do bazy danych
	$ConnectionLink = mysqli_connect($_POST['hostname'], $_POST['username'], $_POST['password'], $_POST['database'],$_POST['dbport']);
	
    if(mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL/MariaDB: " . mysqli_connect_error();
    }
    
    //print_r($ConnectionLink);
  
	//$SqlQuery[]
	for($i=0;$i<count($SqlQuert);$i++)
	{
		$SqlQuert[$i] = str_replace('[DBPREFIX]', $_POST['dbprefix'], $SqlQuert[$i]);
		$SqlQuert[$i] = str_replace('[USER_EMAIL]', $_POST['page_email_addr'], $SqlQuert[$i]);
		$SqlQuert[$i] = str_replace('[USER_PASSWORD]', $SaltPassword, $SqlQuert[$i]);

		if(!mysqli_query($ConnectionLink, $SqlQuert[$i]))
		{
            echo $SqlQuert[$i].'<br />';
			echo '<strong>DataBase Error</strong><br />';
            echo mysqli_error($ConnectionLink).'<br />';
		}
	}
			
	mysqli_close($ConnectionLink);
	
	// Wyświetlanie loginu i hasła
    
    $Text = GetLang('install2_gratulations');
    $Text = str_replace('[LOGIN]', $_POST['page_email_addr'], $Text);
    $Text = str_replace('[PASSWORD]', $PasswordString, $Text);
    
    echo $Text;
}

PrintFoot();

/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/

?>