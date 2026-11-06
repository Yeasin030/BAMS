<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8faff, #edf1ff);
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .error-card {
            max-width: 580px;
            width: 100%;
            background: #fff;
            border-radius: 24px;
            padding: 45px 35px;
            text-align: center;
            box-shadow: 0 15px 50px rgba(30, 41, 100, .09);
        }

        .icon-circle {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            border-radius: 50%;
            background: #eef0ff;
            color: #4f46e5;
            font-size: 45px;
        }

        .error-code {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 30px;
            background: #fff1f0;
            color: #c2413b;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        h1 {
            margin-top: 20px;
            font-weight: 800;
            color: #172554;
        }

        .description {
            color: #64748b;
            line-height: 1.8;
            margin: 15px 0 25px;
        }

        .notice {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            color: #475569;
            margin-bottom: 25px;
        }

        .btn-home {
            background: #4f46e5;
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-home:hover {
            background: #3730a3;
            color: white;
        }

        .btn-back {
            display: inline-block;
            padding: 12px 20px;
            color: #475569;
            text-decoration: none;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #94a3b8;
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 35px 22px;
            }

            .actions a {
                margin: 5px;
            }
        }
    </style>
</head>

<body>
    <main class="error-card">
        <div class="icon-circle">
            <i class="fa-solid fa-shield-halved"></i>
        </div>

        <span class="error-code">ERROR 403 · FORBIDDEN</span>

        <h1>Access Denied</h1>

        <p class="description">
            Sorry! Your current user role does not have permission
            to access this page. Please contact your administrator
            if you need access.
        </p>

        <div class="notice">
            <i class="fa-solid fa-lock me-2"></i>
            <strong>Restricted Area</strong>
            <div class="small mt-1">
                Your account is not authorized for this resource.
            </div>
        </div>
        <?php $url =  $_SESSION['url']; ?>
        <div class="actions">
            <a href="<?php echo $url; ?>" class="btn-home">
                <i class="fa-solid fa-house me-2"></i>
                Go to Dashboard
            </a>

            <a href="javascript:history.back()" class="btn-back">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Go Back
            </a>
        </div>

        <div class="footer">
            Error Code: 403 &bull; Forbidden Access
        </div>
    </main>
</body>

</html>