<?php
$month1=Carbon\Carbon::create()->month($month);
$month_job = $month1->thaidate('F');
$year = Carbon\Carbon::now()->thaidate('Y');
    // สร้างวันแรกของเดือนที่กำหนด
    $startDate = Carbon\Carbon::create(now()->year, $month, 1, 0, 0, 0)->startOfDay();

    // หาวันสุดท้ายของเดือนนี้
    $endDate = $startDate->copy()->endOfMonth()->endOfDay();

    // รูปแบบวันที่
    $formattedStartDates = $startDate->format('Y-m-d H:i:s');
    $formattedEndDates = $endDate->format('Y-m-d H:i:s');
    // dd($formattedStartDates,$formattedEndDates);
$job = DB::table('tb_job')
        ->where('type','CCTV')
        ->whereBetween('install_date', [$formattedStartDates, $formattedEndDates])
        ->where('status_job','success')
        ->get();
        // dd($job);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table,th,td {
                    margin: auto;
                    border: 1px solid black;
                    border-collapse: collapse;
                    font-size: 20px;
                }
                th, td {
        border: 1px solid black; /* กำหนดเส้นขอบของเซลล์ */
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    </style>
</head>
<body>
    <table>
        <thead>

            <tr align="center">
                <th colspan="5" style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> สรุปงานติดตั้ง CCTV ประจำเดือน {{$month_job}} {{$year}}</th>
            </tr>
            <tr align="center">
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> ลำดับ </th>
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> ชื่อ-สกุล </th>
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> Sale </th>
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> วันที่แก้ไข </th>
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> หมายเหตุ </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($job as $key => $j )
            <tr align="center">
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{$key+1}}</td>
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{$j->customer_name}}</td>
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{$j->sale}}</td>
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{$j->install_date}}</td>
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"></td>
            </tr>
            @endforeach

        </tbody>
    </table>
</body>
</html>
