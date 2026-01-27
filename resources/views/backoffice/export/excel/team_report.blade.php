<?php
$team = DB::table('tb_team')->get();
$col = count($team);
// ระบุช่วงวันที่จาก JobExport
// dd($start_date);
$start_date = $start_date;
$end_date = $end_date;

$start_date_text = Carbon\Carbon::parse($start_date)->format('F Y');
$end_date_text = Carbon\Carbon::parse($end_date)->format('F Y');
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
                <th colspan="{{$col+2}}" style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> สรุปติดตั้งของทีมช่าง ({{$start_date_text}} - {{$end_date_text}}) </th>
            </tr>
            <tr align="center">
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> เดือน </th>
                @foreach ($team as $t)
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{$t->team_name}}</th>
                @endforeach
                <th style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;"> รวม </th>
            </tr>
        </thead>
        <tbody>
            @php

                $jobs_by_month = DB::table('tb_job')
                                ->where('status_job', 'success')
                                ->whereNotNull('install_date')
                                ->whereBetween(DB::raw("DATE_FORMAT(install_date, '%Y-%m-%d')"), [$start_date, $end_date])
                                ->orderBy('install_date')
                                ->get()
                                ->groupBy(function ($job) {
                                    return Carbon\Carbon::parse($job->install_date)->format('Y-m');
                                });
// dd($jobs_by_month);

            @endphp

            @foreach ($jobs_by_month as $month => $jobs)
            <tr align="center">
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{Carbon\Carbon::parse($month)->format('F')}}</td>
                @foreach ($team as $item)
                <?php
                $count_job_team = $jobs->where('technician', $item->id)->count();
                ?>
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{$count_job_team}}</td>
                @endforeach
                @php
                $count_job = $jobs->count();
                @endphp
                <td style="border: 1px solid black;border-collapse: collapse;width: 80px;text-align: center;">{{$count_job}}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
    @php
// dd($jobs_by_month);

    @endphp
</body>
</html>
