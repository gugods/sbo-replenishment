<html lang="en">
<head>
  <title><?php echo $title;?></title>
  <style type="text/css">
    @page {
      margin-top: 50px;
      margin-left: 50px;
      margin-right: 50px;
      margin-bottom: 50px;
    }

    header {
      padding: 0px 0;
      margin-bottom: 0px;
    }

    body {
      font-family: "THSarabunNew";
      font-size: 18px;
      line-height: 18px;
    }

    table {
      width: 100%;
    }

    table,
    th,
    td {
      border-collapse: collapse;
      border-spacing: 0;
      border: 1px solid black;
      padding: 5px 10px;
    }
    th {
      vertical-align: middle;
      font-family: "THSarabunNew-Bold";
      background: #EEEEEE;
    }
    td {
      vertical-align: top;
    }
    .right {
      text-align: right;
    }
    .center {
      text-align: center;
    }
    header {
      border: 2px solid #9d456f;
      padding: 0px 20px 15px 20px;
      border-radius: 10px;
      margin-bottom: 40px;
    }
    header .title {
      color: #9d456f;
      font-size: 30px;
      line-height: 30px;
      float: left;
      font-family: "THSarabunNew-Bold";
    }
    header .branch_name {
      color: #9d456f;
      font-size: 30px;
      line-height: 30px;
      float: right;
      font-family: "THSarabunNew-Bold";
    }
    header .stock_date {
      color: #4a4a4a;
      font-size: 24px;
      line-height: 24px;
    }
    .clear {
      clear: both;
    }
    .total td {
      font-size: 20px;
      line-height: 20px;
      background: #EEEEEE;
      font-family: "THSarabunNew-Bold";
    }
    .page_break { 
      page-break-before: always; 
    }
  </style>
</head>

<body>
  <?php echo $html; ?>
</body>

</html>