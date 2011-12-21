<?php
/** $Id: example3.php 2426 2006-11-18 19:59:26Z jrust $ */
/**
 * Here we create an encrypted PDF file based on a dynaically generated page. 
 * We buffer the content of the page and then create the PDF at the end.
 * Then we load up PDFEncryptor and set meta-data, password, and permissions.
 * Finally, we send a header and the file so it opens straight into the
 * browser.
 */

// Require the class
require_once dirname(__FILE__) . '/../HTML_ToPDF.php';
require_once dirname(__FILE__) . '/../PDFEncryptor.php';
// Create a unique filename for the resulting PDF
$linkToPDFFull = $linkToPDF = tempnam(dirname(__FILE__), 'PDF-');
// Remove the temporary file it creates
unlink($linkToPDFFull);
// Give it an extension
$linkToPDFFull .= '.pdf';
$linkToPDF .= '.pdf';
// Make it web accessible
$linkToPDF = basename($linkToPDF);
$defaultDomain = 'www.rustyparts.com';

// Buffer the current html page so we can write it to file later
ob_start();
?>
<html>
<head>
  <title>Testing HTML_ToPDF</title>
  <style type="text/css">
  div.noprint {
    display: none;
  }
  h6 {
    font-style: italic;
    font-weight: bold;
    font-size: 14pt;
    font-family: Courier;
    color: blue;
  }
  /** Change the paper size, orientation, and margins */
  @page {
    size: 8.5in 14in;
    orientation: landscape;
  }
  /** This is a bit redundant, but its works ;) */
  /** odd pages */
  @page:right {
    margin-right: 1.0cm;
    margin-left: 1.0cm;
    margin-top: 1.0cm;
    margin-bottom: 1.0cm;
  }
  /** even pages */
  @page:left {
    margin-right: 1.0cm;
    margin-left: 1.0cm;
    margin-top: 1.0cm;
    margin-bottom: 1.0cm;
  }
  </style>
</head>
<body>
  An example dynamic page that is converted to PDF on 8x14 paper, 
  in landscape mode, with 1.0cm margins!<br /> 
  And what about <sub>subscript</sub> or <sup>superscript</sup>?<br />
  Hmmm...one last test, special characters: &alpha; &copy; &#187;<br /><br />
  This document has been encrypted with the helper PDFEncryptor class so you will need to
  enter "foobar" for the password<br />
  This should open straight into your PDF reader, 
  but, if not, click <a href="<?php echo $linkToPDF; ?>">here</a> to view the PDF file.<br />
  <div class="noprint">This should not show up.</div>
  <h6>
  This demonstrates the use of CSS classes for an element.<br />
  What CSS properties and blocks can be used can be found at 
  <a href="http://www.tdb.uu.se/~jan/html2psug.html">http://www.tdb.uu.se/~jan/html2psug.html</a>
  </h6>
  Inserting a page break..<br /><br />
  <!--NewPage-->
  Now on to page 2!
  A linked image with a relative path:<br />
  <a href="http://rustyparts.com/pb"><img src="tuckered.jpg" /></a>
</body>
</html>
<?php
// Send the class our HTML and the defaultDomain for images, css, etc.
$pdf =& new HTML_ToPDF(ob_get_contents(), $defaultDomain);
// We won't be sending out the HTML to the user
ob_end_clean();
$pdf->setDefaultPath('/scripts/HTML_ToPDF/examples/');
// Could turn on debugging to see what exactly is happening
// (commands being run, images being grabbed, etc.)
// $pdf->setDebug(true);
// Convert the file
$result = $pdf->convert();

// Check if the result was an error
if (is_a($result, 'HTML_ToPDFException')) {
    die($result->getMessage());
}
else {
    // Move the generated PDF to the web accessible file
    copy($result, $linkToPDFFull);
    unlink($result);

    // Set up encryption
    $encryptor =& new PDFEncryptor($linkToPDFFull);
    // Set paths
    $encryptor->setJavaPath('/usr/lib/j2se/1.4/bin/java');
    $encryptor->setITextPath(dirname(__FILE__) . '/../lib/itext-1.3.jar');
    // Set meta-data
    $encryptor->setAuthor('Paul Bunyan');
    $encryptor->setKeywords('HTML_ToPDF, php, encryption of PDF');
    $encryptor->setSubject('Example of HTML_ToPDF with Ecnryption');
    $encryptor->setTitle('Showing its stuff');
    // Set permissions
    $encryptor->setAllowPrinting(false);
    $encryptor->setAllowModifyContents(false);
    $encryptor->setAllowDegradedPrinting(true);
    $encryptor->setAllowCopy(true);
    // Set password
    $encryptor->setUserPassword('foobar');
    $encryptor->setOwnerPassword('barfoo');
    $result = $encryptor->encrypt();
    if (is_a($result, 'PDFEncryptorException')) {
        die($result->getMessage());
    }
}

header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="example.pdf"');
readfile($linkToPDFFull);
unlink($linkToPDFFull);
?>
