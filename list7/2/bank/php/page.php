<?php
$HEADER = <<<EOT
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<title>{{TITLE}}</title>
	<meta name="description" content= "{{DESCRIPTION}}">
	<meta name="author" content="Konrad Czart">
	<meta name="viewport" content = "width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">
{{STYLES}}
{{SCRIPTS}}
{{INNER-STYLE}}
</head>
<body>
EOT;




$FOOTER =<<<EOT
</div>
<footer>
	Wszystkie prawa zastrzeżone &copy; 2018
</footer>
</body>
</html>
EOT;

class MyPage {
	private $title        = "";
	private $description  = "";
	private $root         = "";
	private $cssFiles     = [];
	private $jsFiles      = [];
	private $innerStyle   = "";


  /**
  * Dodaje specyficzne style do strony
  * @param string $filename
  * @return void
  */
	public function addCSS($filename) {
    	$this->cssFiles[] = $filename;
  	}



  /**
  * Ustalamy skrypty umieszczane w nagłówku
  * @param string $filename
  * @return void
  */
	public function addJS($filename) {
		$this->jsFiles[] = $filename;
	}

  /**
  * Ustawienie opisu strony (description)
  * @param string $pageTop
  * @return void
  */
	public function setDescription($description) {
		$this->description = $description;
	}

  /**
  * Dodanie wewnętrznego stylu strony
  * @param string $pageTop
  * @return void
  */
	public function addInnerStyle($style) {
		$this->innerStyle = $style;
  	}

  /**
  * Ustawienie opisu strony
  * @param string $title - tytul strony
  * @param string $root - sciezka do glownego katalogu witryny
  * @return void
  */

	public function __construct($title = "", $root="") {
		$this->title = $title;
		$this->root  = $root;

	}

  /**
  * Zwraca lancuch z poczatkiem strony
  * @return string
  */
	public function begin() {
		global $HEADER;
	    $pageTop = str_replace(["{{TITLE}}", "{{DESCRIPTION}}"], [$this->title, $this->description], $HEADER);

	    //dodajemy style
	    $tmpArray = [];
	    $tmpFiles = $this->cssFiles;
	    $TMP = '	<link rel="stylesheet" href="{{R}}{{CSS}}">' . "\n";
	    for ($i = 0; $i < count($tmpFiles); $i++){
	      $tmpArray[]= (string) str_replace(["{{R}}", "{{CSS}}"], [$this->root, (string) $tmpFiles[$i]], $TMP);
	    }
	    $pageTop= str_replace("{{STYLES}}", join("\n",$tmpArray), $pageTop);

	    // dodajemy skrypty
	    $tmpArray = [];
	    $tmpFiles = $this->jsFiles;
	    $T = '	<script src="{{R}}js/{{JS}}"></script>' . "\n";
	    for ($i = 0; $i < count($this->jsFiles); $i++){
	      $tmpArray[]= str_replace(["{{R}}", "{{JS}}"], [$this->root, (string) $tmpFiles[$i]], $T);
	    }
	    $pageTop= str_replace("{{SCRIPTS}}", join("\n",$tmpArray), $pageTop);

	    // aktualizujemy styl wewnętrzny strony
	    $tmpArray = ($this->innerStyle === "") ? "" : "<style>\n" . $this->innerStyle . "\n</style>\n";
	    $pageTop= str_replace("{{INNER-STYLE}}", $tmpArray, $pageTop);

	    // usuwamy puste linie
	    //return preg_replace('/^[ \t]*[\r\n]+/m', '', $pageTop);
	    return preg_replace('/^\h*\v+/m', '', $pageTop);
	    // \h : dowolny poziomy pusty znak
	    // \v : dowolny pionowy pusty znak
	    // /m : multiline
	}

	public function pageHeader(){
		global $PAGE_HEADER;
		$tmpArray = [];
	    $tmpFiles = $this->h3Header;
	    $TMP = '		<h3>{{R}}</h3>';
	    for ($i = 0; $i < count($tmpFiles); $i++){
	      $tmpArray[]= (string) str_replace("{{R}}", (string) $tmpFiles[$i], $TMP);
	    }

	    $PAGE_HEADER= str_replace("{{H3}}", join("\n",$tmpArray), $PAGE_HEADER);

		return $PAGE_HEADER;
	}
  /**
  * Zwraca lancuch z zamknieciem strony
  * @return string
  */
	public function end() {
		global $FOOTER;
		return $FOOTER;
	}

	public function addMenu() {
		global $MENU;
		return $MENU;
	}

} //class MyPage

?>
