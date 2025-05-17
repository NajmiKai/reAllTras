<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../PHPMailer/src/Exception.php';
require '../../../PHPMailer/src/PHPMailer.php';
require '../../../PHPMailer/src/SMTP.php';

session_start();
include '../../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hardcoded for now (you can later use $_POST values)
    $name = "Adib";
    $email = "haniszainee1105@gmail.com";
    $message = "Terima kasih $name, permohonan anda telah diterima dan sedang diproses.";
    $isPreview = isset($_POST['preview']) && $_POST['preview'] == '1';

    if ($isPreview) {
        // Display styled email preview
        echo <<<HTML
        <html>
        <head>
            <title>Email Preview</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    padding: 40px;
                }
                .email-preview {
                    max-width: 600px;
                    margin: auto;
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .email-preview h2 {
                    color: #444;
                    margin-top: 0;
                }
                .email-header {
                    font-size: 14px;
                    color: #555;
                    margin-bottom: 20px;
                }
                .email-body {
                    font-size: 16px;
                    color: #333;
                    line-height: 1.6;
                }
                .confirm-button {
                    margin-top: 30px;
                    text-align: center;
                }
                .confirm-button button {
                    background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    font-size: 16px;
                    border-radius: 6px;
                    cursor: pointer;
                }
                .confirm-button button:hover {
                    background-color: #218838;
                }
            </style>
        </head>
        <body>
            <div class="email-preview">
                <h2>Email Preview</h2>
                <div class="email-header">
                    <p><strong>To:</strong> {$email}</p>
                    <p><strong>Subject:</strong> Permohonan Diterima</p>
                </div>
                <div class="email-body">
                    <p>Terima kasih <strong>{$name}</strong>,</p>
                    <p>Permohonan anda telah diterima dan sedang diproses. Kami akan menghubungi anda untuk tindakan seterusnya.</p>
                    <p>Salam hormat,<br>Admin ALLTRAS</p>
                </div>

                <div class="confirm-button">
                    <form action="send_mail.php" method="POST">
                        <input type="hidden" name="name" value="{$name}">
                        <input type="hidden" name="email" value="{$email}">
                        <input type="hidden" name="preview" value="0">
                        <button type="submit">✅ Confirm & Send Email</button>
                    </form>
                </div>
            </div>
        </body>
        </html>
        HTML;
    } else {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'haniszainee1105@gmail.com';
            $mail->Password = 'eizx afua iazr efrl'; // App password only
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('haniszainee1105@gmail.com', 'Sistem Permohonan');
            $mail->addAddress('2023168781@student.uitm.edu.my', 'Pegawai HR');

            $mail->isHTML(true);
            $mail->Subject = 'Permohonan Diterima';
            $mail->Body    = nl2br($message);

            $mail->send();
            echo "<p style='font-family: Arial; color: green; text-align: center;'>Email berjaya dihantar kepada <strong>$email</strong></p>";
        } catch (Exception $e) {
            echo "<p style='font-family: Arial; color: red; text-align: center;'>Ralat: Email gagal dihantar. " . $mail->ErrorInfo . "</p>";
        }
    }
}
?>
