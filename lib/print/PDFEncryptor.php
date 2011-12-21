<?php
/** $Id: PDFEncryptor.php 2426 2006-11-18 19:59:26Z jrust $ */
// {{{ license

// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt                                   |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Jason Rust <jrust@rustyparts.com>                           |
// +----------------------------------------------------------------------+

// }}}
// {{{ PDFEncryptor class

/**
 * A class to encrypt a PDF file and add security permissions to a PDF file.
 *
 * This class can be used stand alone or as a helper class to encrypt and digitally
 * sign PDF files that have been created using HTML_ToPDF.  See the README and examples for
 * more information.
 *
 * @author Jason Rust <jrust@rustyparts.com>
 * @version 3.5
 * @package HTML_ToPDF
 * @copyright The PHP License
 */

// }}}
class PDFEncryptor {
    // {{{ properties

    /**
     * The full path to the PDF we are encrypting 
     * @var string
     */
    var $pdfFile = '';

    /**
     * The temporary directory to save intermediate files
     * @var string
     */
    var $tmpDir = '/tmp';

    /**
     * The path to the java binary
     * @var string
     */
    var $javaPath = '/usr/bin/java';

    /**
     * The path to the iText jar file 
     * @var string
     */
    var $iTextPath;

    /**
     * The path to the encrypt_pdf java class
     * @var string
     */
    var $encryptPdfPath;

    /**
     * Should we allow printing?
     * @var bool
     */
    var $allowPrinting = false;

    /**
     * Should we allow the contents to be modified?
     * @var bool
     */
    var $allowModifyContents = false;

    /**
     * Should we allow the copy command?
     * @var bool
     */
    var $allowCopy = false;

    /**
     * Should we allow modify annotations?
     * @var bool
     */
    var $allowModifyAnnotations = false;

    /**
     * Should we allow screen readers?
     * @var bool
     */
    var $allowScreenReaders = false;

    /**
     * Should we allow assembly?
     * @var bool
     */
    var $allowAssembly = false;

    /**
     * Should we allow fill in?
     * @var bool
     */
    var $allowFillIn = false;

    /**
     * Should we allow degraded printing?
     * @var bool
     */
    var $allowDegradedPrinting = false;

    /**
     * The encryption strength (128 or 40)
     * @var int
     */
    var $encryptionStrength = 128;

    /**
     * The user password
     * @var string
     */
    var $userPassword = '';

    /**
     * The owner password
     * @var string
     */
    var $ownerPassword = '';

    /**
     * The subject (meta-data)
     * @var string
     */
    var $subject;

    /**
     * The title (meta-data)
     * @var string
     */
    var $title;

    /**
     * The author (meta-data)
     * @var string
     */
    var $author;

    /**
     * The creator (meta-data)
     * @var string
     */
    var $creator = 'PDFEncryptor';

    /**
     * The keywords (meta-data)
     * @var string
     */
    var $keywords;

    // }}}
    // {{{ constructor

    /**
     * Initializes the class
     *
     * @param string $in_pdfFile (optional) The full path to the pdf file to encrypt.
     *
     * @access public
     * @return void
     */
    function PDFEncryptor($in_pdfFile)
    {
        $this->pdfFile = $in_pdfFile;
        $this->iTextPath = dirname(__FILE__) . '/lib/itext.jar';
        $this->encryptPdfPath = dirname(__FILE__) . '/lib/encrypt_pdf.java';
    }

    // }}}
    // {{{ setPdfFile()

    /**
     * Set the full path to the PDF we are encrypting 
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setPdfFile($in_value)
    {
        $this->pdfFile = $in_value;
    }

    // }}}
    // {{{ setJavaPath()

    /**
     * Set the path to the java binary
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setJavaPath($in_value)
    {
        $this->javaPath = $in_value;
    }

    // }}}
    // {{{ setITextPath()

    /**
     * Set the path to the iText jar file 
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setITextPath($in_value)
    {
        $this->iTextPath = $in_value;
    }

    // }}}
    // {{{ setEncryptPdfPath()

    /**
     * Set the path to the encrypt_pdf java class 
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setEncryptPdfPath($in_value)
    {
        $this->encryptPdfPath = $in_value;
    }

    // }}}
    // {{{ setAllowPrinting()

    /**
     * Set should we allow printing?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowPrinting($in_value)
    {
        $this->allowPrinting = $in_value;
    }

    // }}}
    // {{{ setAllowModifyContents()

    /**
     * Set should we allow the contents to be modified?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowModifyContents($in_value)
    {
        $this->allowModifyContents = $in_value;
    }

    // }}}
    // {{{ setAllowCopy()

    /**
     * Set should we allow the copy command?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowCopy($in_value)
    {
        $this->allowCopy = $in_value;
    }

    // }}}
    // {{{ setAllowModifyAnnotations()

    /**
     * Set should we allow modify annotations?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowModifyAnnotations($in_value)
    {
        $this->allowModifyAnnotations = $in_value;
    }

    // }}}
    // {{{ setAllowScreenReaders()

    /**
     * Set should we allow screen readers?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowScreenReaders($in_value)
    {
        $this->allowScreenReaders = $in_value;
    }

    // }}}
    // {{{ setAllowAssembly()

    /**
     * Set should we allow assembly?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowAssembly($in_value)
    {
        $this->allowAssembly = $in_value;
    }

    // }}}
    // {{{ setAllowFillIn()

    /**
     * Set should we fill in?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowFillIn($in_value)
    {
        $this->allowFillIn = $in_value;
    }

    // }}}
    // {{{ setAllowDegradedPrinting()

    /**
     * Set should we allow degraded printing?
     *
     * @param bool $in_value The value
     *
     * @access public
     * @return void
     */
    function setAllowDegradedPrinting($in_value)
    {
        $this->allowDegradedPrinting = $in_value;
    }

    // }}}
    // {{{ setEncryptionStrength()

    /**
     * Set the encryption strength (128 or 40)
     *
     * @param int $in_value The value
     *
     * @access public
     * @return void
     */
    function setEncryptionStrength($in_value)
    {
        if ($in_value == 40) {
            $this->encryptionStrength = $in_value;
        }
        else {
            $this->encryptionStrength = 128;
        }
    }

    // }}}
    // {{{ setUserPassword()

    /**
     * Set the user password
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setUserPassword($in_value)
    {
        $this->userPassword = $in_value;
    }

    // }}}
    // {{{ setOwnerPassword()

    /**
     * Set the owner password
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setOwnerPassword($in_value)
    {
        $this->ownerPassword = $in_value;
    }

    // }}}
    // {{{ setSubject()

    /**
     * Set the subject (meta-data)
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setSubject($in_value)
    {
        $this->subject = $in_value;
    }

    // }}}
    // {{{ setTitle()

    /**
     * Set the title (meta-data)
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setTitle($in_value)
    {
        $this->title = $in_value;
    }

    // }}}
    // {{{ setAuthor()

    /**
     * Set the author (meta-data)
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setAuthor($in_value)
    {
        $this->author = $in_value;
    }

    // }}}
    // {{{ setCreator()

    /**
     * Set the creator (meta-data)
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setCreator($in_value)
    {
        $this->creator = $in_value;
    }

    // }}}
    // {{{ setKeywords()

    /**
     * Set the keywords (meta-data)
     *
     * @param string $in_value The value
     *
     * @access public
     * @return void
     */
    function setKeywords($in_value)
    {
        $this->keywords = $in_value;
    }

    // }}}
    // {{{ setTmpDir()

    /**
     * Set the temporary directory path
     *
     * @param string $in_path The full path to the tmp dir 
     *
     * @access public
     * @return void
     */
    function setTmpDir($in_path) {
        $this->tmpDir = $in_path;
    }

    // }}}
    // {{{ encrypt()

    /**
     * Encrypt the PDF file, add meta-data, and set permissions 
     *
     * @access public
     * @return mixed True on success, pear error on problem 
     */
    function encrypt()
    {
        // make sure pdf file exists
        if (!file_exists($this->pdfFile)) {
            return new PDFEncryptorException("Error: The PDF file does not exist: $this->pdfFile");
        }

        // make sure iText jar file exists
        if (!file_exists($this->iTextPath)) {
            return new PDFEncryptorException("Error: The iText jar file does not exist: $this->iTextPath.  You can download it at http://www.lowagie.com/iText");
        }

        // make sure encryptPdf file exists
        if (!file_exists($this->encryptPdfPath)) {
            return new PDFEncryptorException("Error: The encrypt_pdf java class does not exist: $this->encryptPdfPath.");
        }

        // make sure the java binary exists 
        if (!@is_executable($this->javaPath)) {
            return new PDFEncryptorException("Error: java [$this->javaPath] is not executable.  You can download it at http://java.sun.com");
        }

        // this can take a while with large files
        set_time_limit(60);

        // Copy file so it can be worked on
        $tmp_file = tempnam($this->tmpDir, 'PDF-');
        copy($this->pdfFile, $tmp_file);
        $s_encryption = $this->_boolToInt($this->allowPrinting);
        $s_encryption .= $this->_boolToInt($this->allowModifyContents);
        $s_encryption .= $this->_boolToInt($this->allowCopy);
        $s_encryption .= $this->_boolToInt($this->allowModifyAnnotations);
        $s_encryption .= $this->_boolToInt($this->allowFillIn);
        $s_encryption .= $this->_boolToInt($this->allowScreenReaders);
        $s_encryption .= $this->_boolToInt($this->allowAssembly);
        $s_encryption .= $this->_boolToInt($this->allowDegradedPrinting);

        $s_properties = $this->_getProperty('Subject', $this->subject);
        $s_properties .= $this->_getProperty('Title', $this->title );
        $s_properties .= $this->_getProperty('Author', $this->author);
        $s_properties .= $this->_getProperty('Creator', $this->creator);
        $s_properties .= $this->_getProperty('Keywords', $this->keywords);

        // execute argument
        $s_arg = $this->javaPath . ' -cp ' . $this->iTextPath . ':' . dirname($this->encryptPdfPath) . 
                 ' encrypt_pdf ' .  $tmp_file . ' ' .  $this->pdfFile . ' "' . 
                 addcslashes($this->userPassword, '"') .  '" "' .  addcslashes($this->ownerPassword, '"') . 
                 '" ' . $s_encryption . ' ' .  $this->encryptionStrength .  ' ' . $s_properties;

        exec($s_arg);
        unlink($tmp_file);
        return true;
    }

    // }}}
    // {{{ _boolToInt()

    /**
     * Converts a boolean to an integer
     *
     * @param bool $in_bool The boolean
     *
     * @access private
     * @return int The integer
     */
    function _boolToInt($in_bool)
    {
        return $in_bool ? 1 : 0;
    }

    // }}}
    // {{{ _getProperty()

    /**
     * Gets a property if it has been set
     *
     * @param string $in_key The key property 
     * @param string $in_value The value of the key
     *
     * @access public
     * @return string The key value pair
     */
    function _getProperty($in_key, $in_value)
    {
        if (!is_null($in_value)) {
            return $in_key . ' "' . addcslashes($in_value, '"') . '" ';
        }
        else {
            return '';
        }
    }

    // }}}
}

// {{{ PDFEncryptorException 

class PDFEncryptorException {
    var $classname             = 'PDFEncryptor';
    var $message               = '';

    function PDFEncryptor($message)
    {
        $this->message = $message;
    }

    function getMessage()
    {
        return "{$this->classname} {$this->message}";
    }
}

// }}}
// {{{ is_executable()

if (!function_exists('is_executable')) {
    /**
     * Because is_executable() doesn't exist on windows until php 5.0 we define it as a dummy
     * function here that just runs file_exists.
     *
     * @param string $in_filename The filename to test
     *
     * @access public
     * @return bool If the file exists 
     */
    function is_executable($in_filename)
    {
        return file_exists($in_filename);
    }
}

// }}}
