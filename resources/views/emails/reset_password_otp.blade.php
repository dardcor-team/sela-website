<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Akun SELA</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo, {{ $name }}!</h2>
    <p>Kami menerima permintaan untuk mereset password akun SELA Anda.</p>
    <p>Silakan masukkan 6 digit kode OTP berikut di dalam aplikasi untuk melanjutkan proses reset password:</p>
    
    <div style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; margin: 20px 0;">
        {{ $otp }}
    </div>

    <p>Kode OTP ini hanya berlaku selama 10 menit.</p>
    <p>Jika Anda tidak merasa meminta reset password, abaikan email ini dan pastikan akun Anda aman.</p>
    
    <br>
    <p>Salam hangat,</p>
    <p><strong>Tim Administrator SELA</strong></p>
</body>
</html>