<?php

$lang['email_must_be_array']            = "Der Mail Validierungsmethode muss ein Array übergeben werden.";
$lang['email_invalid_address']          = "Ungültige Mailadresse: %s";
$lang['email_attachment_missing']       = "Der folgende Mailanhang konnte nicht gefunden werden: %s";
$lang['email_attachment_unreadable']    = "Der folgende Anhang konnte nicht geöffnet werden: %s";
$lang['email_no_recipients']            = "Sie müssen die Empfänger angeben: To, Cc, or Bcc";
$lang['email_send_failure_phpmail']     = "Mail konnte nicht mittels PHP mail() versendet werden. Der Server ist möglicherweise nicht so konfiguriert, dass Mails mit dieser Methode versandt werden können.";
$lang['email_send_failure_sendmail']    = "Mail konnte nicht mittels PHP Sendmail versendet werden. Der Server ist möglicherweise nicht so konfiguriert, dass Mails mit dieser Methode versandt werden können.";
$lang['email_send_failure_smtp']        = "Mail konnte nicht mittels PHP SMTP versendet werden. Der Server ist möglicherweise nicht so konfiguriert, dass Mails mit dieser Methode versandt werden können.";
$lang['email_sent']                     = "Ihre Nachricht wurde erfolgreich mit dem folgenden Protokoll: %s, versendet.";
$lang['email_no_socket']                = "Socket zu Sendmail konnte nicht geöffnet werden. Bitte die Einstellungen überprüfen.";
$lang['email_no_hostname']              = "Es wurde kein SMTP Hostname spezifiziert.";
$lang['email_smtp_error']               = "Es trat ein SMTP Fehler auf: %s";
$lang['email_no_smtp_unpw']             = "Fehler: Es muss ein SMTP Nutzername und Passwort zugewiesen werden.";
$lang['email_failed_smtp_login']        = "AUTH LOGIN Befehl konnte nicht gesendet werden. Fehler: %s";
$lang['email_smtp_auth_un']             = "Nutzername konnte nicht authentifiziert werden. Fehler: %s";
$lang['email_smtp_auth_pw']             = "Passwort konnte nicht authentifiziert werden. Fehler: %s";
$lang['email_smtp_data_failure']        = "Daten konnten nicht gesendet werden: %s";
$lang['email_exit_status']              = "Exit Statuscode: %s";


/* End of file email_lang.php */
/* Location: ./system/language/english/email_lang.php */