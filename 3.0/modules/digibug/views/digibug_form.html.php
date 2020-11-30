<?php defined("SYSPATH") or die("No direct script access.") ?>
<html>
  <body>
    <?= form::open("http://www.digibug.com/dapi/order.php") ?>
    <?php foreach ($order_params as $key => $value): ?>
    <?= form::hidden($key, $value) ?>
    <?php endforeach ?>
    </form>
    <script type="text/javascript">
     document.forms[0].submit();
    </script>
  </body>
</html>
