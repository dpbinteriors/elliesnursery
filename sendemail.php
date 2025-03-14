<?php
// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if form is submitted
if(isset($_POST['submit-form'])) {

    // Get form data and sanitize (PHP 8.1+ compatible method)
    $username = htmlspecialchars(strip_tags($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(strip_tags($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars(strip_tags($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(strip_tags($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

    // Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output (0 for no output, 1-4 for various debug levels)
        $mail->isSMTP();                           // Send using SMTP
        $mail->Host       = 'smtp-relay.brevo.com'; // Set the SMTP server
        $mail->SMTPAuth   = true;                  // Enable SMTP authentication
        $mail->Username   = '87c6b6001@smtp-brevo.com'; // SMTP username
        $mail->Password   = 'hn2g9tJk8WMrdbxF';    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                   // TCP port to connect to

        // Recipients - Use a verified sender address from your Brevo account
        $mail->setFrom('hasan.49.5012@gmail.com', "Ellie's Nursery"); // Change to your verified email in Brevo
        $mail->addAddress('hasan.49.5012@gmail.com'); // Add a recipient email (change this to your email)
        $mail->addReplyTo($email, $username); // Set form submitter as reply-to address

        // Content
        $mail->isHTML(true);                       // Set email format to HTML
        $mail->Subject = $subject;

        // Current date for the email
        $currentDate = date("F j, Y");

        // Creating styled email body with form information
        $emailBody = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Contact Form Submission</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333333;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    background-color: #4CAF50;
                    color: white;
                    padding: 20px;
                    text-align: center;
                    border-radius: 5px 5px 0 0;
                }
                .content {
                    background-color: #f9f9f9;
                    padding: 20px;
                    border-left: 1px solid #ddd;
                    border-right: 1px solid #ddd;
                }
                .footer {
                    background-color: #eeeeee;
                    padding: 15px;
                    text-align: center;
                    font-size: 12px;
                    color: #666666;
                    border-radius: 0 0 5px 5px;
                    border: 1px solid #ddd;
                    border-top: none;
                }
                .message-box {
                    background-color: white;
                    border: 1px solid #ddd;
                    padding: 15px;
                    margin-top: 15px;
                    border-radius: 5px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                table td {
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                }
                table td:first-child {
                    font-weight: bold;
                    width: 30%;
                }
                .date {
                    text-align: right;
                    color: #888;
                    font-size: 12px;
                    margin-bottom: 15px;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Contact Form Submission</h2>
                </div>
                <div class='content'>
                    <div class='date'>Received on: {$currentDate}</div>
                    
                    <table>
                        <tr>
                            <td>Name:</td>
                            <td>{$username}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><a href='mailto:{$email}'>{$email}</a></td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>{$phone}</td>
                        </tr>
                        <tr>
                            <td>Subject:</td>
                            <td>{$subject}</td>
                        </tr>
                    </table>
                    
                    <div class='message-box'>
                        <h3>Message:</h3>
                        <p>{$message}</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>This is an automated message from your website contact form.</p>
                    <p>&copy; " . date('Y') . " Ellie's Nursery. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $mail->Body = $emailBody;

        // Plain text alternative for email clients that don't support HTML
        $mail->AltBody = "Contact Form Submission\n\n".
            "Name: {$username}\n".
            "Email: {$email}\n".
            "Phone: {$phone}\n".
            "Subject: {$subject}\n\n".
            "Message:\n{$message}";

        // Send email
        $mail->send();

        // Success message
        echo "Message has been sent successfully!";
        // Alternatively, redirect:
        // header("Location: thank-you.php");
        // exit;

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    // If someone tries to access this file directly
    echo "Access Denied";
    exit;
}
?>