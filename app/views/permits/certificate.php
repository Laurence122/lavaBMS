<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Business Permit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .certificate-container {
            border: 10px solid #4CAF50;
            padding: 30px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .official-seal {
            margin: 20px 0;
        }
        .official-seal img {
            width: 100px;
            height: 100px;
        }
        .permit-details p {
            text-align: left;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="header">
            Republic of the Philippines<br>
            Province of [Province]<br>
            City/Municipality of [City/Municipality]<br>
            Barangay [Barangay Name]
        </div>

        <div class="official-seal">
            <img src="https://via.placeholder.com/100" alt="Official Seal">
        </div>

        <h2>BARANGAY BUSINESS PERMIT</h2>

        <div class="permit-details">
            <p><strong>Permit No:</strong> <?= html_escape($permit['id']) ?></p>
            <p><strong>Business Name:</strong> <?= html_escape($permit['business_name']) ?></p>
            <p><strong>Business Address:</strong> <?= html_escape($permit['business_address']) ?></p>
            <p><strong>Permit Type:</strong> <?= html_escape($permit['permit_type']) ?></p>
            <p><strong>Date Issued:</strong> <?= date('F d, Y') ?></p>
            <p><strong>Expiration Date:</strong> <?= date('F d, Y', strtotime('+1 year')) ?></p>
        </div>

        <div style="margin-top: 50px;">
            <div style="display: inline-block; width: 45%; text-align: center;">
                <p>_________________________</p>
                <p><strong>[Barangay Captain's Name]</strong></p>
                <p>Barangay Captain</p>
            </div>
            <div style="display: inline-block; width: 45%; text-align: center;">
                <p>_________________________</p>
                <p><strong>[Applicant's Name]</strong></p>
                <p>Owner/Applicant</p>
            </div>
        </div>

    </div>
</body>
</html>
