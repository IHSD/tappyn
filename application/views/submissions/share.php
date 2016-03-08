<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<html>
    <head>
        <title>Tappyn</title>
        <meta property="og:url" content="http://test.tappyn.com/submissions/share/<?php echo $submission->id; ?>">
        <meta property="og:type" content="article">
        <meta property="og:title" content="Check out the ad I just created!">
        <meta property="og:description" content="Tappyn lets content creators create content">
        <meta property="og:image" content="http://test.tappyn.com/public/img/TappynLogo2.png">
    </head>
    <body>
        <script>
            window.location = "<?php echo base_url().'#/contest/'.$submission->contest_id; ?>";
        </script>
    </body>
</html>
