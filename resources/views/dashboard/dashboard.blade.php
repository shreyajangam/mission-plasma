<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Mission Plasma</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Header Styles */
        .header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: left;
            display: flex;
            align-items: center;
        }

        .logo {
            width: 50px;
            border-radius: 50%;
            margin-right: 20px;
        }

        /* Dashboard Container Styles */
        .dashboard-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px;
        }

        /* Total Counts Styles */
        .total-counts {
            margin-bottom: 30px;
            text-align: center;
        }

        .count-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }

        /* Button Styles */
        .btn-action {
            margin: 10px;
        }

        /* State-wise Styles */
        .state-wise {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            margin-top: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .state-wise h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .state-list {
            list-style-type: none;
            padding-left: 0;
        }

        .state-item {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 18px;
            color: #333;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Call to Action Section Styles */
        .cta-section {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }

        .cta-section h2 {
            margin-bottom: 20px;
        }

        /* Footer Styles */
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="/storage/images/logo_image.webp" alt="Mission Plasma" class="logo">
        <h1 style="margin: 0;">Mission Plasma</h1>
    </div>

    <div class="dashboard-container">
        <div class="total-counts" style="display: flex; justify-content: space-around;">
            <div>
                <h2>Donors</h2>
                <div class="count-circle">{{ $totalDonors }}</div>
            </div>
            <div>
                <h2>Requesters</h2>
                <div class="count-circle">{{ $totalRequests }}</div>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('request-form') }}" class="btn btn-primary btn-action">Request Plasma</a>
            <a href="{{ route('donor-form') }}" class="btn btn-primary btn-action">Donate Plasma</a>
        </div>
        <div class="state-wise">
            <h2>State-wise</h2>
            <div class="row">
                <div class="col-md-6">
                    <h3>Donors</h3>
                    <ul class="state-list">
                        @foreach($donorStates as $donorState)
                        <li class="state-item">{{ $donorState['state_name'] }} - {{ $donorState['count'] }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3>Requesters</h3>
                    <ul class="state-list">
                        @foreach($requestStates as $requestState)
                        <li class="state-item">{{ $requestState['state_name'] }} - {{ $requestState['count'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="cta-section">
        <h2>Help Us Save Lives!</h2>
        <p>Donate plasma today and make a difference in someone's life.</p>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Mission Plasma. All Rights Reserved.</p>
    </footer>
</body>

</html>
