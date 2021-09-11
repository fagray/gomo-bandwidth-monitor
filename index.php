

<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('datamonitor.db');
    }
}

$db = new MyDB();

$result = $db->query('SELECT * FROM data_usage ORDER BY id desc LIMIT 1');
$row = $result->fetchArray();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gigabyte Internet Services | Data Monitoring</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

</head>
<body>
    
<style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      /* Custom page CSS
        -------------------------------------------------- */
        /* Not required for template or sticky footer method. */

        .container {
        width: auto;
        max-width: 680px;
        padding: 0 15px;
        }

    </style>

    <!-- Custom styles for this template -->
    <link href="sticky-footer.css" rel="stylesheet">
  </head>
  <body class="d-flex flex-column h-100">
    
<!-- Begin page content -->
<main class="flex-shrink-0">
  <div class="container">
  <?php if (number_format($row['remaining_data'],2) == "0.00") { ?>
    <h1 align="center" class="mt-5">Sorry, you have no remaining data.</strong></h1>
  <?php } ?>
  <?php if (number_format($row['remaining_data'],2) > 0) { ?>
    <h1 align="center" class="mt-5">You still have <strong> <?php print $row['remaining_data'] ?></strong></h1>
    <?php } ?>
    <p align="center" class="lead">as of <?php print $row['date'] ?></p>
    <p align="center" class="lead"><i>Refresh this page every <strong> 5 minutes</strong> to see your remaining data. </i></p>
  </div>
</main>

<footer class="footer mt-auto py-3 bg-light">
  <div class="container" align="center">
    <span  class="text-muted">GIGABYTE INTERNET SERVICES. Copyright <?php echo date('Y'); ?></span>
  </div>
</footer>
</body>
</html>