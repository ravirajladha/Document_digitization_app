<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Assigned</title>
</head>
<body>
    <p>You have been assigned a document. Please click the link below to verify and access the document:</p>
    <a href="{{ $verificationUrl }}">Verify and Access Document</a>
    <p>This link will expire on {{ $expiresAt }}.</p>
</body>
</html>
