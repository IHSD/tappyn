<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<html>
    <head>
        <title>Tappyn</title>
        <meta property="og:url" content="http://test.tappyn.com/submissions/share/<?php echo $submission->id; ?>">
        <meta property="og:type" content="article">
        <meta property="og:title" content="Test Title">
        <meta property="og:description" content="Test Description">
        <meta property="og:image:secure_url" content="http://test.tappyn.com/public/img/TappynLogo2.png">
    </head>
    <body>
        <script>
            //window.location.href("<?php echo base_url().'#/contest/'.$submission->contest_id; ?>");
        </script>
    </body>
</html>
