<?php
header('Content-Type: application/json; charset=utf-8');

/* استقبال البيانات بأمان */
$name    = isset($_POST['name'])    ? trim($_POST['name'])    : '';
$email   = isset($_POST['email'])   ? trim($_POST['email'])   : '';
$phone   = isset($_POST['phone'])   ? trim($_POST['phone'])   : '';
$city    = isset($_POST['city'])    ? trim($_POST['city'])    : '';
$message = isset($_POST['msg'])     ? trim($_POST['msg'])     : '';

/* منع حقن الترويسات عبر حقول المرسل */
$clean = function ($value) {
    return str_replace(array("\r", "\n", "%0a", "%0d", "%0A", "%0D"), '', $value);
};
$name  = $clean($name);
$email = $clean($email);

/* التحقق من الحقول المطلوبة */
if ($name === '' || $email === '' || $message === '') {
    echo json_encode(array(
        'type' => 'danger',
        'msg'  => 'يرجى تعبئة الاسم والبريد الإلكتروني والرسالة.'
    ));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array(
        'type' => 'danger',
        'msg'  => 'يرجى إدخال بريد إلكتروني صحيح.'
    ));
    exit;
}

$email_subject = 'رسالة جديدة من موقع أ.د/ عبد العزيز شاهين';
$mail_subject  = function_exists('mb_encode_mimeheader')
    ? mb_encode_mimeheader($email_subject, 'UTF-8', 'B')
    : '=?UTF-8?B?' . base64_encode($email_subject) . '?=';

$body  = "الاسم: " . $name . "\n";
$body .= "البريد الإلكتروني: " . $email . "\n";
if ($phone !== '') {
    $body .= "رقم الهاتف: " . $phone . "\n";
}
if ($city !== '') {
    $body .= "المدينة: " . $city . "\n";
}
$body .= "\nالرسالة:\n" . $message . "\n";
$body .= "\nأُرسلت في " . date('d/m/Y') . " - " . date('H:i') . "\n";

$headers  = 'From: ' . $email . "\r\n";
$headers .= 'Reply-To: ' . $email . "\r\n";
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";

$sent = mail('dr_m_kassem@yahoo.com', $mail_subject, $body, $headers);

if ($sent) {
    echo json_encode(array(
        'type' => 'success',
        'msg'  => 'تم إرسال رسالتك بنجاح. شكراً لتواصلك معنا.'
    ));
} else {
    echo json_encode(array(
        'type' => 'danger',
        'msg'  => 'تعذر إرسال الرسالة حالياً. يرجى المحاولة لاحقاً أو التواصل عبر البريد الإلكتروني.'
    ));
}
exit;
