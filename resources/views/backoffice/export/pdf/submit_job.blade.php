<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }
        /* @page { size: landscape; } */
        body {
            font-family: 'THSarabunNew';
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            /* ทำให้ body มีความสูงเท่ากับ viewport height เพื่อให้กล่องอยู่ตรงกลางทั้งตัวแนวนอนและตัวแนวตั้ง */
            margin: 0;
        }

        .box {
            width: 600px;
            font-size: 26px;
            border: solid 2px black;
            margin: auto;

        }
        .right_text{
            position: absolute;
            right: 40;
            font-size: 19px
        }
        .left_text{
            position: absolute;
            left: 40;
            font-size: 19px
        }
        .left_text1{
            position: absolute;
            left: 240;
            font-size: 24px
        }
        .header {
            font-size: 30px;
        }

        .text-data {
            border-bottom: 1px dotted;
            text-align: center;
        }
        .text-footer{

            font-size: 18px
        }
        .content-box {
        width: 100%; /* กำหนดความกว้างเท่ากับขนาดของหน้ากระดาษ */
        page-break-inside: avoid; /* ไม่ให้ข้อความตัดหน้า */
        }
        .custom-table {
            border-collapse: collapse;
        }

        .custom-table td, .custom-table th {
            border: 1px solid #000; /* กำหนดสีขอบเส้นตารางเป็นสีดำ */
            padding: 8px; /* เพิ่มระยะห่างระหว่างข้อความและขอบของเซลล์ */
        }

    </style>
<body>
        {{-- <div class="col-md-4"></div> --}}
        <div class="left_text1" style="font-weight: bold;" > รายงาน </div>
        {{-- <div class="col-md-4"></div> --}}
        <br><br>
        
        <table class="left_text">
            <thead>
                <tr>
                    <th style="font-weight: bold;">JOB :</th>
                    <th>&nbsp;&nbsp; JOB1</th>
                </tr>
                <tr>
                    <th style="font-weight: bold;">Project Name :</th>
                    <th>&nbsp;&nbsp; JOB1</th>
                </tr>
                <tr>
                    <th style="font-weight: bold;">Phase :</th>
                    <th>&nbsp;&nbsp; Phase 1</th>
                </tr>
                <tr>
                    <th style="font-weight: bold;">Survay Date :</th>
                    <th>&nbsp;&nbsp; JOB1</th>
                </tr>
                <tr>
                    <th style="font-weight: bold;">Install Date :</th>
                    <th>&nbsp;&nbsp; JOB1</th>
                </tr>
            </thead>
        </table>
</body>
</html>
