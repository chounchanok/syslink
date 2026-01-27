<?php

use Carbon\Carbon;

// กำหนดวันที่เริ่มต้นและสิ้นสุด
$startDate = Carbon::parse($start_date)->startOfDay();
$endDate = Carbon::parse($end_date)->endOfDay();

// ดึงข้อมูลงานพร้อมความสัมพันธ์ที่เกี่ยวข้อง
$job = DB::table('tb_job')
    ->leftJoin('tb_team', 'tb_job.technician', '=', 'tb_team.id')
    ->leftJoin('users as engineers', function ($join) {
        $join->on('tb_job.engineer', '=', 'engineers.id')
             ->where('engineers.role', '=', 'engineer');
    })
    ->where('tb_job.type', $project)
    ->whereBetween('tb_job.install_date', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
    ->where('tb_job.status_job', 'success')
    ->select(
        'tb_job.*',
        'tb_team.team_name as technician_name',
        'engineers.name as engineer_name'
    )
    ->get();

// ดึงชื่อโปรเจค
$project_name = DB::table('tb_project')->where('id', $project)->value('name');

// ชื่อเดือนในภาษาไทย
$month_job = $startDate->translatedFormat('F');
$year = $startDate->translatedFormat('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table, th, td {
            margin: auto;
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 20px;
        }
        th, td {
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
                <th colspan="9">สรุปติดตั้งงาน {{$project_name}} ตั้งแต่วันที่ {{$startDate->translatedFormat('j F Y')}} ถึง {{$endDate->translatedFormat('j F Y')}}</th>
            </tr>
            <tr align="center">
                <th>ลำดับ</th>
                <th>วันที่สำรวจ</th>
                <th>วันที่ติดตั้ง</th>
                <th>ชื่อ-สกุล</th>
                <th>เบอร์ติดต่อ</th>
                <th>ที่อยู่ติดตั้ง</th>
                <th>วิศวกร</th>
                <th>ทีมช่าง</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($job as $key => $j)
                <tr align="center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $j->survay_date }}</td>
                    <td>{{ $j->install_date }}</td>
                    <td>{{ $j->customer_name }}</td>
                    <td>{{ $j->tell }}</td>
                    <td>{{ $j->address_customer }}</td>
                    <td>{{ $j->engineer_name }}</td>
                    <td>{{ $j->technician_name }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
