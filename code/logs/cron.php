<?php

// include settings ##
include "../../code.php";

/**
 * A script that can be called from cron to automatically email the content
 * of a PHP error log file to a developer or webmaster.
 *
 * Author: James Caws
 * Website: http://www.jamescaws.co.uk
 *
 * It uses the freely available PHPMailer class available from
 *   http://sourceforge.net/project/showfiles.php?group_id=26031
 *
 * This particular script has been configured to use SMTP, but it's a piece of cake
 * to modify to use mail() and can even use sendmail if you make a few simple
 * changes to configure PHPMailer to do so.
 *
 * This version of the script is using PHPMailer v2.0.2
 * For help on all PHPMailer config issues and errors, refer to their documentation
 * and website at http://phpmailer.codeworxtech.com/
 *
 * Ensure that all of your website PHP scripts include the following through a common
 * include file or at the very top before anything else. 
 *
 *     ini_set('display_errors','0');			// Best practise on production sites
 *     ini_set('log_errors','1');			// We need to log them otherwise this script will be pointless!
 *     ini_set('error_log','/path/to/error_log);	// Full path to a writable file - include the file name
 *     error_reporting(E_ALL ^ E_NOTICE);		// What errors to log - see: http://www.php.net/error_reporting
 *
 * OR alternatively and more highly recommended, adjust php.ini or configure a .htaccess file if you can't do that.
 * See http://perishablepress.com/press/2008/01/14/advanced-php-error-handling-via-htaccess/ for details and examples
 *
 * NOTE : READ ALL OF THE COMMENTS IN THIS FILE TO UNDERSTAND HOW IT WORKS. IT
 * IS PARTICULARLY IMPORTANT YOU DO SO IF YOU DO NOT WANT TO USE THE PHPMAILER
 * CLASS AS YOU WILL NEED TO COMMENT OUT OR REMOVE CODE.
 */

/**
 * Set a few constants for ease of configuration
 */
#$error_path = ini_get('error_log');  #echo $error_path;
define( 'ERROR_NOTIFY_EMAIL', $dbug['error_email'] ); // Where the errors should be emailed
define( 'ERROR_NOTIFY_FROM_EMAIL', $dbug['error_email'] ); // The 'from' address to use in outgoing emails
define( 'ERROR_NOTIFY_FROM_NAME', 'ecoder dbug' ); // The 'from name' to use in outgoing emails
define( 'ERROR_NOTIFY_SUBJECT','ecoder PHP errors' ); // Give the email a subject. Might come in useful if setting up a mailbox filter
define( 'ERROR_LOG_FILE', $dbug['error_path'] ); // full path to your readable + writable file, defined by PHP config 'error_log'
define( 'MAX_CONTENT_SIZE', 1024 ); // As a precaution, set the max size of the error log content to be emailed in case it grows exponentially

/**
 * The following is not required if you are happy for the default PHP mail()
 * function to be used. Some hosts don't allow it though or can be unreliable, so
 * you might want to use SMTP for better reliablity.
 */

#$aMParams["host"] = 'smtp.domain.com';	// - The server to connect. Default is localhost
#$aMParams["port"] = 25;			// - The port to connect. Default is 25
#$aMParams["auth"] = true;		// - Whether or not to use SMTP authentication. Default is FALSE
#$aMParams["username"] = 'username';	// - The username to use for SMTP authentication.
#$aMParams["password"] = 'password'; 	// - The password to use for SMTP authentication.

#define('SMTP_MAIL_PARAMS',serialize($aMParams));	// remove this line if you are not using SMTP

/**
 * Carry out a few basic file checks first. This script will itself run in to problems if the error log
 * file does not exist, cannot be read or writen to.
 */

if (!file_exists(ERROR_LOG_FILE)) {
	mail(ERROR_NOTIFY_EMAIL, 'Error log file does not exist', sprintf("The file '%s' does not exist. Error log monitor cannot continue.", ERROR_LOG_FILE));
	exit;
} else if (!is_readable(ERROR_LOG_FILE)) {
	mail(ERROR_NOTIFY_EMAIL, 'Error log file is not readable', sprintf("The file '%s' is not readable. Error log monitor cannot continue.", ERROR_LOG_FILE));
	exit;
} else if (!is_writable(ERROR_LOG_FILE)) {
	mail(ERROR_NOTIFY_EMAIL, 'Error log file is not writable', sprintf("The file '%s' is not writable. Error log monitor cannot continue.", ERROR_LOG_FILE));
	exit;
}

/**
 * Check the error log filesize - no point carrying on if there is nothing in it.
 */
if (filesize(ERROR_LOG_FILE) == 0) { exit; }

/**
 * OK, so if we're still here there's work to do. 
 */

/**
 * Include the PHPMailer class. You still need this even if you have removed the above SMTP
 * configuration. If you DON'T want to use PHPMailer though, comment out this line and then
 * jump down to the comment 'Email it', remove the associated PHPMailer lines and uncomment
 * the mail() call.
 *
 * REMEMBER : class.phpmailer.php should be in the same location as this script, or
 * adjust the following line if it is somewhere else.
 */
#require_once('class.phpmailer.php');

/**
 * Retrieve the content from the file
 * and assign it to a couple of variables.
 */
$sContent = $sFullContent = file_get_contents(ERROR_LOG_FILE);

/**
 * If we don't want to mail huge amounts of data, extract only the amount we do want.
 */
if (defined('MAX_CONTENT_SIZE') && MAX_CONTENT_SIZE > 0) {
	$sContent = substr($sContent, 0, MAX_CONTENT_SIZE);

	if (strlen($sFullContent) > MAX_CONTENT_SIZE) {
		$sContent .= "\n\n... Consult the error log archive for the full list of errors";
	}
}

/**
 * Email it.
 *
 * IF YOU DO NOT WANT TO USE PHPMAILER AND HAVE REMOVED THE require_once() INCLUSION ABOVE,
 * REMOVE ALL LINES BETWEEN HERE AND THE COMMENT "END OF PHPMAILER"
 */
#$oMail = new PHPMailer();

/**
 * If we have SMTP detail held in a constant, use SMTP. Otherwise PHPMailer will default to using mail();
 */
/* 
if (defined('SMTP_MAIL_PARAMS')) {
	$aSMTP = unserialize(SMTP_MAIL_PARAMS);

	$oMail->IsSMTP();	// set mailer to use SMTP
	$oMail->Host		= $aSMTP['host'];	// specify main and backup server
	$oMail->Port		= $aSMTP['port'];	// specify SMTP port
	$oMail->SMTPAuth	= $aSMTP['auth'];	// turn on SMTP authentication
	$oMail->Username	= $aSMTP['username'];	// SMTP username
	$oMail->Password	= $aSMTP['password'];	// SMTP password
}

$oMail->AddAddress(ERROR_NOTIFY_EMAIL, '');
$oMail->Body = $sContent;
$oMail->From = ERROR_NOTIFY_FROM_EMAIL;
$oMail->FromName = ERROR_NOTIFY_FROM_NAME;
$oMail->Subject = ERROR_NOTIFY_SUBJECT;
$oMail->Send();

if ($oMail->ErrorInfo && strlen($oMail->ErrorInfo)) {
	$sContent = sprintf("This message has been sent via the backup mail() function call as PHPMailer failed reporting: %s\n\n---\n\n%s",
		$oMail->ErrorInfo,
		$sContent
	);
	mail(ERROR_NOTIFY_EMAIL, ERROR_NOTIFY_SUBJECT, $sContent);
}
*/
/** END OF PHPMAILER **/

/**
 * Uncomment this line to use the default PHP mail() function if not using PHPMailer
 */
mail(ERROR_NOTIFY_EMAIL, ERROR_NOTIFY_SUBJECT, $sContent);

/**
 * Truncate the error log so we don't email it all again on the next run.
 */
file_put_contents(ERROR_LOG_FILE,null);

/**
 * We'll copy the full content of the error log to an archive. This is useful for a number
 * of reasons, not least because we are only emailing a portion of the original errors if
 * the log file was larger than our given MAX_CONTENT_SIZE - there could be errors in the
 * full log not included in the email.
 */
$sHistoricalLogName = sprintf('%s.archive', ERROR_LOG_FILE);

$sFullContent = sprintf("----- Full content of error log as mailed @ %s -----\n%s\n", date('d/m/Y H:i:s'), $sFullContent);

file_put_contents($sHistoricalLogName, $sFullContent, FILE_APPEND);

/**
 * And we're done.
 */

exit;

?>

