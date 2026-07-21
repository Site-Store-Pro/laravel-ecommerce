<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Failed - File Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;850&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 24px;
            color: #334155;
        }
        .error-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        .icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background-color: #fef2f2;
            color: #ef4444;
            border-radius: 50%;
            margin-bottom: 24px;
            border: 1px solid #fee2e2;
        }
        h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 12px 0;
        }
        p {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 24px 0;
            line-height: 1.6;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            text-align: left;
            margin-bottom: 24px;
        }
        .info-row {
            margin-bottom: 12px;
            font-size: 13px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #475569;
            display: block;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
        }
        .info-value {
            font-family: monospace;
            background-color: #f1f5f9;
            padding: 6px 12px;
            border-radius: 8px;
            display: block;
            word-break: break-all;
            color: #0f172a;
            border: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            transition: background-color 0.15s ease-in-out;
        }
        .btn:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="icon-container">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="32" height="32">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1>Download File Not Found</h1>
        <p>The system was unable to locate the requested file on the designated storage provider. Please share the details below with your site administrator.</p>
        
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Storage Disk</span>
                <span class="info-value">{{ $diskName }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Database File Location</span>
                <span class="info-value">{{ var_export($location, true) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Server Absolute Path</span>
                <span class="info-value">{{ $absolutePath }}</span>
            </div>
        </div>

        <a href="javascript:history.back()" class="btn">Go Back</a>
    </div>
</body>
</html>
