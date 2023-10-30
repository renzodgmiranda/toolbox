<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Workorder Assigned</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>New Workorder Assigned</h2>
    <p>Hello {{ $workorder->users->name }},</p>
    <p>You have been assigned a new workorder with the following details:</p>
    <table>
        <tr>
            <th>Workorder Number</th>
            <td>{{ $workorder->wo_number }}</td>
        </tr>
        <tr>
            <th>Problem Description</th>
            <td>{{ $workorder->wo_problem }}</td>
        </tr>
        <!-- Add other fields as required -->
    </table>
    <p>Kindly attend to this as soon as possible. For detailed information or any updates, please visit the portal.</p>
</body>
</html>
