<h3>Hello <?= $username ?></h3>

<p>A email was sent to your given address <strong><?= $email ?></strong>.
Please follow the instructions in this mail to finally change your password.
</p>

<p>
You should be redirected to the login page in a few seconds. If not, click
here:<br /><br />
<a href="<?= site_url('myaccount') ?>">&raquo; back to the Login page</a>
</p>

<script type="text/javascript">
<!--
    setTimeout('location.href=\'<?= site_url('myaccount') ?>\'',10000);
//-->
</script>
