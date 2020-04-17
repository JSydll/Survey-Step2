<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 07.03.2020
 */
namespace Step2;

require_once __DIR__ . "/../Step2.php";

/** @brief Representation of a named email contact */
class EmailContact
{
    public $address;
    public $name;

    public function __construct(string $address, string $name)
    {
        $this->address = $address;
        $this->name = $name;
    }
}

/**
 * @brief Checks if the given string is a valid email
 * @param address Email to be checked.
 */
function IsValidEmail(string $address)
{
    return \filter_var($address, FILTER_VALIDATE_EMAIL);
}

/**
 * @brief Checks if the given path refers to a valid file
 * @param path File path to be checked.
 */
function IsValidFile(string $path)
{
    return \is_file($path);
}

/**
 * @brief Creates and sends emails
 */
class Mailer
{
    // Private members
    private $mail;

    /**
     * @brief Sets up a new Mailer for a given SMTP server
     * @param host SMTP server url
     * @param port SMTP server port
     * @param user Username for the SMTP server
     * @param pass Password for the user
     * @param sender Email (and name) that should be shown as sender
     */
    public function __construct(string $host, int $port, string $user, string $pass, EmailContact $sender)
    {
        $this->mail = new \PHPMailer\PHPMailer\PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = $host;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $user;
        $this->mail->Password = $pass;
        $this->mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = $port;

        if (!IsValidEmail($sender->address)) {
            throw new HttpException(
                "Sender mail not valid ('$sender->address').",
                HttpStatusCode::BAD_REQUEST
            );
        }
        $this->mail->setFrom($sender->address, $sender->name);
    }

    /**
     * @brief
     * @param recipients One ore more recipients the mail should be sent to
     * @param subject Subject
     * @param body Html body
     * @param attachments (Optional) attachements to be added
     * @param nonHtmlBody (Optional) Non-html text to be displayed for recipients that disabled html
     *
     */
    public function Send(array $recipients, string $subject, string $body, array $attachments = null, string $nonHtmlBody = "")
    {
        $this->mail->clearAllRecipients();
        $this->mail->clearAttachments();

        if (empty($recipients)) {
            throw new HttpException(
                "No recipients set.",
                HttpStatusCode::BAD_REQUEST
            );
        }
        $this->AddRecipients($recipients);

        $this->mail->Subject = $subject;
        $this->mail->isHTML(true);
        $this->mail->Body = $body;
        $this->mail->AltBody = $nonHtmlBody;

        if (!empty($attachments)) {
            $this->AddAttachments($attachments);
        }

        $this->mail->send();
    }

    /**
     * @brief Send the last email again, as it is
     */
    public function Resend()
    {
        $this->mail->send();
    }

    // Private methods

    private function AddRecipients(array $recipients)
    {
        $contact = new EmailContact('', '');
        foreach ($recipients as $recipient) {
            if ($recipient instanceof EmailContact) {
                $contact = $recipient;
            } else {
                $contact->address = $recipient;
            }
            if (!IsValidEmail($contact->address)) {
                throw new HttpException(
                    "Recipient mail not valid ('$contact->address').",
                    HttpStatusCode::BAD_REQUEST
                );
            }
            $this->mail->addAddress($contact->address, $contact->name);
        }
    }

    private function AddAttachments(array $attachments)
    {
        foreach ($attachments as $file) {
            if (!IsValidFile($file)) {
                throw new HttpException(
                    "Attachement not a valid file.",
                    HttpStatusCode::BAD_REQUEST
                );
            }
            $this->mail->addAttachment($file);
        }
    }
}
