<?php
class Heading{
  private $text;
  private $size;

  function __construct($text,$size) {
    $this->text = $text;
    $this->size = $size;
  }

  public function getHTML(){
    return "<H{$this->size}>{$this->text}</H{$this->size}>";
  }

}


$main = new Heading("Main Headfing","1");

$sub = new Heading("Sub heading","3"); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
<?php echo $main->getHTML(); ?>
</body>
</html>