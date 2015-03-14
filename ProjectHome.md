Easy encode independent of PHP version a value to be used on jQuery (JavaScript).

## Example ##

```
<?php
include 'jsonfy/jsonfy.php';

// http://localhost/teste.php?callback=123
if ($jsonfy->hasCallback()) {
    $array = array('sample' => 'value');
    $jsonfy->show($array);
}
// show JSON format output (needed by jQuery):
// 123({"sample": "value"})
?>
```