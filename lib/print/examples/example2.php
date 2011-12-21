<?php
/** $Id: example2.php 2426 2006-11-18 19:59:26Z jrust $ */
/**
 * A more complex example. We convert a remote HTML file 
 * into a PDF file. Additionally, we set several options to 
 * customize the look.
 */
?>
<html>
<head>
  <title>Testing HTML_ToPDF</title>
</head>
<body>
  Creating the PDF from remote web page...<br />
<?php
// Require the class
require_once dirname(__FILE__) . '/../HTML_ToPDF.php';

// Full path to the file to be converted (this time a webpage)
// change this to your own domain
$htmlFile = 'http://www.example.com/index.html';
$defaultDomain = 'www.example.com';
$pdfFile = dirname(__FILE__) . '/test2.pdf';
// Remove old one, just to make sure we are making it afresh
@unlink($pdfFile);

$pdf =& new HTML_ToPDF($htmlFile, $defaultDomain, $pdfFile);
// Set that we do not want to use the page's css
$pdf->setUseCSS(false);
// Give it our own css, in this case it will make it so
// the lines are double spaced
$pdf->setAdditionalCSS('
p {
  line-height: 1.8em;
  font-size: 12pt;
}');
// We want to underline links
$pdf->setUnderlineLinks(true);
// Scale the page down slightly
$pdf->setScaleFactor('.9');
// Make the page black and light
$pdf->setUseColor(false);
// Convert the file
$result = $pdf->convert();

// Check if the result was an error
if (is_a($result, 'HTML_ToPDFException')) {
    die($result->getMessage());
}
else {
    echo "PDF file created successfully: $result";
    echo '<br />Click <a href="' . basename($result) . '">here</a> to view the PDF file';
}
?>
</body>
</html> 
