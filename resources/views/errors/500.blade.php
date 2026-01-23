<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | FeedTan CMG</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            margin: 0; 
            padding: 0; 
            background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%); 
            font-family: 'Poppins', sans-serif; 
            color: #333; 
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container { 
            max-width: 600px; 
            margin: 30px auto; 
            background: #ffffff; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08); 
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .header { 
            background: #006400; 
            padding: 30px 25px; 
            text-align: center; 
            color: white; 
        }
        .header .title { 
            font-size: 26px; 
            font-weight: 700; 
            margin-bottom: 5px; 
        }
        .header .sub-title { 
            font-size: 14px; 
            opacity: 0.9; 
        }
        .content { 
            padding: 40px 25px; 
        }
        .error-code { 
            font-size: 120px; 
            font-weight: 700; 
            color: #006400; 
            line-height: 1; 
            margin-bottom: 20px;
        }
        .error-title { 
            font-size: 28px; 
            font-weight: 600; 
            color: #2d3748; 
            margin-bottom: 15px; 
        }
        .error-message { 
            font-size: 16px; 
            color: #4a5568; 
            margin-bottom: 30px; 
        }
        .button-container { 
            text-align: center; 
            margin: 30px 0; 
        }
        .home-button { 
            display: inline-block; 
            padding: 12px 30px; 
            background-color: #006400; 
            color: white !important; 
            font-weight: 600; 
            border-radius: 6px; 
            text-decoration: none; 
            transition: background-color 0.3s ease;
            margin: 5px;
        }
        .home-button:hover { 
            background-color: #2e7d32; 
        }
        .back-button {
            display: inline-block; 
            padding: 12px 30px; 
            background-color: #438a5e; 
            color: white !important; 
            font-weight: 600; 
            border-radius: 6px; 
            text-decoration: none; 
            transition: background-color 0.3s ease;
            margin: 5px;
        }
        .back-button:hover {
            background-color: #2e7d32;
        }
        .footer { 
            background-color: #006400; 
            color: white; 
            text-align: center; 
            padding: 15px; 
            font-size: 12px; 
            letter-spacing: 0.5px; 
            opacity: 0.8; 
        }
        .icon { font-size: 80px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="header">
            <div class="title">FeedTan Community Microfinance Group</div>
            <div class="sub-title">P.O.Box 7744, Ushirika Sokoine Road, Moshi, Kilimanjaro, Tanzania</div>
        </div>
        <div class="content">
            <div class="icon">⚠️</div>
            <div class="error-code">500</div>
            <h1 class="error-title">Internal Server Error</h1>
            <p class="error-message">We're experiencing some technical difficulties. Our team has been notified and is working to fix the issue. Please try again later.</p>
            <div class="button-container">
                <a href="{{ url('/') }}" class="home-button">Go to Homepage</a>
                <a href="javascript:history.back()" class="back-button">Go Back</a>
            </div>
        </div>
        <div class="footer">
            FeedTan CMG System - Error 500
        </div>
    </div>
</body>
</html>

