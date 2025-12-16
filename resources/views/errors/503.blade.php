<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable</title>
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #1f2937;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            max-width: 28rem;
            width: 90%;
        }
        .icon-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .icon {
            width: 4rem;
            height: 4rem;
            color: #ef4444; /* Red color to indicate outage/attention */
        }
        h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0 0 1rem 0;
            color: #111827;
        }
        p {
            margin: 0;
            line-height: 1.625;
            color: #6b7280;
        }
        .retry-btn {
            margin-top: 1.5rem;
            display: inline-block;
            background-color: #111827;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .retry-btn:hover {
            background-color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-container">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1>Service Unavailable</h1>
        <p>
            {{ config('app.name') }} is currently undergoing maintenance. <br>
            Please check back again shortly.
        </p>
        <a href="{{ url('/') }}" class="retry-btn">Retry</a>
    </div>
</body>
</html>
