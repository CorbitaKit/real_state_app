<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Rejected</title>
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
        </div>
        <div class="text-gray-700 mb-6">
            <p class="mb-4">Hi {{$user->first_name}} {{$user->last_name}},</p>
            <p class="mb-6">
                Thank you for your application. However we cannot approve your application due to {{ $application->reason }} reason.
            </p>
            <div class="text-center mb-8">

            </div>
            <p class="mb-4">
                You are free to visit our office at [Office address]
            </p>
            <p>
                Best regards,<br>
                <strong>[Your Company Name]</strong>
            </p>
        </div>
    </div>
</body>

</html>