<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<html>
    <head>
        <title>Tappyn</title>
        <meta property='fb:app_id' content='1685501671707146'>
        <meta property="og:url" content="http://test.tappyn.com/submissions/share/<?php echo $submission->id; ?>">
        <meta property="og:type" content="article">
        <meta property="og:title" content="Check out the ad I just created!">
        <meta property="og:description" content="Tappyn lets content creators create content">
        <meta property="og:image" content="<?php echo base_url().'public/img/subs/sub_'.$submission->id.'.png'; ?>">
    </head>
    <body>
        <script>
            window.location = "<?php echo base_url().'#/contest/'.$submission->contest_id.'?context=share&sid='.$submission->id; ?>";
        </script>
    </body>
</html>
