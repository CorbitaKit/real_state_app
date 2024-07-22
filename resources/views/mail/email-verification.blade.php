<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200">
    <div class="max-w-2xl mx-auto bg-white p-10 mt-12 rounded-lg shadow-xl">
        <div class="text-center mb-8">
            <img src="https://via.placeholder.com/150" alt="Company Logo" class="mx-auto mb-4">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Verify Your Email</h1>
            <p class="text-gray-600 text-lg">We're excited to have you on board!</p>
        </div>
        <div class="text-gray-700 mb-6">
            <p class="mb-4">Hi [User],</p>
            <p class="mb-6">
                Thank you for signing up with us. To complete your registration, please verify your email address by clicking the button below.
            </p>
            <div class="text-center mb-8">
                <a href="{{ url('/email/verify/' . $user->id . '/' . sha1($user->email)) }}" class="inline-block bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    Verify Email Address
                </a>
            </div>
            <p class="mb-4">
                If you did not create an account, please ignore this email or contact our support team.
            </p>
            <p>
                Best regards,<br>
                <strong>[Your Company Name]</strong>
            </p>
        </div>
    </div>
</body>

</html>