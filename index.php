

<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('datamonitor.db');
    }
}

$db = new MyDB();

$result = $db->query('SELECT * FROM data_usage ORDER BY rowid desc LIMIT 1');
$row = $result->fetchArray();

$resultUsage = $db->query('SELECT * FROM data_usage GROUP BY date');


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
    <p align="center" class="lead"><i>This page updates <strong> daily</strong> to monitor your data usage and your remaining data allocation. </i></p>
    <table class="table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Usage</th>
        <th>Remaining</th>
      </tr>
    </thead>
    <tbody>
      <?php $previousUsage = 0;$i = 0; while($rowUsage = $resultUsage->fetchArray()){  
        
      ?>
      <tr>
        <td><?php print $rowUsage['date'] ?></td>
        <td>

          <?php if ($i == 0) { print "N/A"; };

          // 1GB = 1024 MB
          if( $i > 0 && strpos($rowUsage['remaining_data'], "GB" )) { 

            $previous =  ($previousUsage - (int) ($rowUsage['remaining_data'] * 1024)) / 1024  ; 
            if ($previous * 1024 > 1023) {
              // show in GB format
              print number_format($previous,2) . " GB";

            } else { 
              // show in MB format
              print number_format(abs($previous)) . " MB";
            }
          }

          if( $i > 0 && strpos($rowUsage['remaining_data'], "MB" )) { 

            // check if the result would be in GB format or in MB already
            $remaining = ($previousUsage - (int) ($rowUsage['remaining_data'])) / 1024;
            if ((int) $remaining * 1024 > 1023) {

              // output would still be in GB
              print number_format($remaining,2) . " GB";
            } 

            if ((int) $remaining * 1024 < 1024 ) {
           
              // output would be in MB
             if ( $remaining * 1024 > 0 ) {
              print number_format($remaining * 1024) . " MB";
             } 
            } 
           
          }
          
          ?>
          
          </td>
        <td><?php print $rowUsage['remaining_data'] ?></td>
      </tr>
      <?php 
        if (strpos($rowUsage['remaining_data'], "MB" )) {

          $previousUsage = (int) $rowUsage['remaining_data'];
          
        } else {
          
          // convert GB to MB
          $previousUsage = (int) $rowUsage['remaining_data'] * 1024; 
        }
        $i++; 
      } 
      ?>
    </tbody>
  </table>
  </div>


</main>

<footer class="footer mt-auto py-3 bg-light">
  <div class="container" align="center">
    <span  class="text-muted">GIGABYTE INTERNET SERVICES. Copyright <?php echo date('Y'); ?></span>
  </div>
</footer>
</body>
</html>