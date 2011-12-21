# This is based on a php script (c) Jason Rust <jrust@rustyparts.com>
# See that script for licensing
# Convert an HTML file to a PDF file using html2ps and ps2pdf

from tempfile import gettempdir, mkstemp
import os
import re
from re import IGNORECASE, DOTALL
from copy import copy

class html2pdf:
  def __init__(self, in_htmlFile, in_domain, in_pdfFile = None):
    """Constructor

    in_htmlFile The full path to the html file to convert
    in_domain The default domain name for images that have a relative path
    in_pdfFile (optional) The full path to the pdf file to output. If not given then we create a temporary name.
    """

    self.htmlFile = '' #The full path to the file we are parsing
    self.pdfFile = '' #The full path to the output file
    self.tmpDir = gettempdir() #The temporary directory to save intermediate files
    self.debug = False #Whether or not we are in debug mode
    self.htmlErrors = False #Whether we output html errors
    self.defaultDomain = '' #The default domain for relative images
    self.html2psPath = '/usr/bin/html2ps' #The path to the html2ps executable
    self.ps2pdfPath = '/usr/bin/ps2pdf' #The path to the ps2pdf executable
    self.getUrlPath = '/usr/bin/curl -i' #The path to your get URL program, including options to get headers
    self.useCSS = True #Whether or not to try and parse the CSS in the html file and use it in creating the pdf
    self.additionalCSS = '' #Other styles to use when parsing the page
    self.pageInColor = True #Show the page in color?
    self.grayScale = False #Show the images be in grayscale?
    self.scaleFactor = 1 #Scale factore for the page
    self.underlineLinks = None #Whether to underline links or not
    self.headers ={} #The header information  # self.headers was array('left' => '$T', 'right' => '$[author]')
    self.footers ={} #The footer information  #self.footers was array('center' => '- $N -')
    self.html2psrc = """
        option:
          titlepage: 0;         /* do not generate a title page */
          toc: 0;               /* no table of contents */
          colour: %pageInColor%; /* create the page in color */
          underline: %underlineLinks%;         /* underline links */
          grayscale: %grayScale%; /* Make images grayscale? */
          scaledoc: %scaleFactor%; /* Scale the document */
        }
        package:
          geturl: %getUrlPath%; /* path to the geturl */
        }
        showurl: 0;             /* do not show the url next to links */"""
     #Default html2ps configuration that we use (is parsed before being used, though)
    self.makeAbsoluteImageUrls = True
    """Whether HTML_ToPDF should replace all relative image paths in the
      input HTML document with the default domain or not. Switch this to
      false if you want to convert a HTML file which is located locally in the
      file system and is not reachable via HTTP but all the images used
      in the HTML file are located correctly according to their relative
      paths."""
    self.ps2pdfIncludePath = '' #Include path for ps2pdf (-I option), for example to specify where to search for font files, etc.
    self._htmlString = ''; #We use this to store the html file to a string for manipulation

    self.htmlFile = in_htmlFile
    self.defaultDomain = in_domain

    if in_pdfFile is None:
      tempFile = mkstemp(prefix = 'PDF-', dir = self.tmpDir)
      os.close(tempFile[0]) #close the file handle which is opened by mkstemp
      self.pdfFile = tempFile[1]
    else:
      self.pdfFile = in_pdfFile

  #==================================================================================
  def addHtml2PsSettings(self, in_settings):
    """Adds on more html2ps settings to the end of the default set of settings."""
    self.html2psrc += "\n" + in_settings

  #==================================================================================
  def setDebug(self, in_debug):
    """Sets the debug variable: true (debugging on) or false (debugging off)."""
    self.debug = in_debug

  #==================================================================================
  def setHeader(self, in_attribute, in_value):
    """Sets a header.

    @param string $in_attribute One of the header attributes that html2ps accepts.  Most
                  common are left, center, right, font-family, font-size, color.
    @param string $in_value The attribute value.  Special values that can be set are $T
                  (document title), $N (page number), $D (current date/time), $U (current
                  url or filename), $[meta-name] (A meta-tag, such as $[author] to get
                  author meta tag)
    """
    self.headers[in_attribute] = in_value

  #==================================================================================
  def setFooter(self, in_attribute, in_value):
    """Sets a footer.

    @param string $in_attribute One of the header attributes that html2ps accepts.  Most
                  common are left, center, right, font-family, font-size, color.
    @param string $in_value The attribute value.  Special values that can be set are $T
                  (document title), $N (page number), $D (current date/time), $U (current
                  url or filename), $[meta-name] (A meta-tag, such as $[author] to get
                  author meta tag)
    """
    self.footers[in_attribute] = in_value

  #==================================================================================
  def setTmpDir(self, in_path):
    """Set the temporary directory path (full path).


    @param string $in_path The full path to the tmp dir
    """
    self.tmpDir = in_path

  #==================================================================================
  def setUseColor(self, in_useColor):
    """Set whether to use color or not when creating the page (bool)."""
    self.pageInColor = in_useColor

  #==================================================================================
  def setUseCSS(self, in_useCSS):
    """Set whether to try and use the CSS in the html page when creating the pdf file. (bool)"""
    self.useCSS = in_useCSS

  #==================================================================================
  def setAdditionalCSS(self, in_css):
    """Set additional CSS to use when parsing the html file. (string)"""
    self.additionalCSS = in_css

  #==================================================================================
  def setGetUrl(self, in_getUrl):
    """Sets the get url which is used for retrieving images from the html file
    needs to be the full path to the file with options to retrive the headers
    as well.

    in_getUrl = string of the get url program path
    """
    self.getUrlPath = in_getUrl

  #==================================================================================
  def setGrayScale(self, in_grayScale):
    """Sets the gray scale option for images (bool: True if images should be grayscale)."""
    self.grayscale = in_grayScale

  #==================================================================================
  def setUnderlineLinks(self, in_underline):
    """Sets the option to underline links or not (bool: True if links should be underlined)."""
    self.underlineLinks = in_underline

  #==================================================================================
  def setScaleFactor(self, in_scale):
    """Sets the scale factor for the page.  Less than one makes it smaller, greater than one enlarges it."""
    self.scaleFactor = in_scale

  #==================================================================================
  def setHtml2Ps(self, in_html2ps):
    """Sets the path to the html2ps program."""
    self.html2psPath = in_html2ps

  #==================================================================================
  def setPs2Pdf(self, in_ps2pdf):
    """Sets the path to the ps2pdf program (string)."""
    self.ps2pdfPath = in_ps2pdf

  #==================================================================================
  def setMakeAbsoluteImageURLs(self, in_makeAbsoluteImageURLs):
    """Sets the makeAbsoluteImageUrls variable (bool).

    Replace relative image URLs in the input HTML file with default domain?
    """
    self.makeAbsoluteImageURLs = in_makeAbsoluteImageURLs

  #==================================================================================
  def setPs2pdfIncludePath(self, in_ps2pdfIncludePath):
    """Sets the ps2pdfIncludePath variable (string) include path for ps2pdf."""
    self.ps2pdfIncludePath = in_ps2pdfIncludePath

  #==================================================================================
  def convert(self):
    """Convert the html file into a pdf file.

    Return the path to the pdf file.
    """

    #read the html file in so we can modify it
    htmlfile = open(self.htmlFile, 'rb')
    self._htmlString = htmlfile.read()
    htmlfile.close()

    #grab extra CSS
    self.additionalCSS += self._getCSSFromFile()

    #modify the conf file
    self._modifyConfFile()
    paperSize = self._getPaperSize()
    orientation = self._getOrientation()

    #try and replace relative images with the default domain
    if self.makeAbsoluteImageUrls:
      reImg = re.compile('<img (.*?)src=["\']((?!http\://).*?)["\']', IGNORECASE)
      absolute = '<img \\1 src="http://' + self.defaultDomain + '/\\2"'
      self._htmlString = reImg.sub(absolute, self._htmlString)

    #html2ps messes up on several form elements
    reInput = re.compile('<input (.*?)type=["\']?(hidden|submit|button|image|reset|file)["\']?.*?>', IGNORECASE)
    self._htmlString = reInput.sub('<input />', self._htmlString)

    a_tmpFiles = {}

    tempFile = mkstemp(prefix = 'CONF-', dir = self.tmpDir)
    os.write(tempFile[0], self.html2psrc)
    os.close(tempFile[0])
    a_tmpFiles['config'] = copy(tempFile[1]) #the conf file has to be an actual file

    self._dumpDebugInfo("html2ps config: self.html2psrc")

    #make the temporary html file.  We need an html extension for at least one version of html2ps
    tempFile = mkstemp(prefix = 'HTML-', dir = self.tmpDir, suffix = '.html')
    os.write(tempFile[0], self._htmlString)
    os.close(tempFile[0])
    a_tmpFiles['html'] = copy(tempFile[1])

    #need a temporary postscript file as well
    tempFile = mkstemp(prefix = 'PS-', dir = self.tmpDir)
    os.close(tempFile[0])
    a_tmpFiles['ps'] = copy(tempFile[1])

    cmd = self.html2psPath + ' ' + orientation + ' -f ' + a_tmpFiles['config'] + ' -o ' + a_tmpFiles['ps'] + ' ' + a_tmpFiles['html'] + ' 2>&1'
    retCode = os.system(cmd)
    self._dumpDebugInfo("html2ps command run: " + cmd)
    #self._dumpDebugInfo("html2ps output: " . result_string))

    if retCode != 0:
      self._cleanup(a_tmpFiles)
      print "Error: there was a problem running the html2ps command.  Error code returned: " + str(retCode) + ".  setDebug() for more information."
      return

    cmd = self.ps2pdfPath + ' -sPAPERSIZE=' + paperSize + ' -I' + self.ps2pdfIncludePath + ' ' + ' -dAutoFilterColorImages=false -dColorImageFilter=/FlateEncode ' + a_tmpFiles['ps'] + " '" + self.pdfFile + "' 2>&1"

    retCode = os.system(cmd)

    self._dumpDebugInfo("ps2pdf command run: " + cmd)
    #self._dumpDebugInfo("ps2pdf output: " + result_string)

    if retCode != 0:
      self._cleanup(a_tmpFiles)
      print "Error: there was a problem running the ps2pdf command.  Error code returned: " + str(retCode) + " setDebug() for more information."
      return

    self._cleanup(a_tmpFiles)
    return self.pdfFile


  #==================================================================================
  def _modifyConfFile(self):
    """Modify the config file and put in our custom variables."""
    #first determine if we should try and figure out underline link option, based on css
    if self.underlineLinks is None:
      reLink = re.compile('a\:link:.*?text-decoration\:(.*?)none(.*?);(.*?)}', IGNORECASE or DOTALL)
      if reLink.search(self.additionalCSS):
        self.underlineLinks = False
      else:
        self.underlineLinks = True

    self.html2psrc = self.html2psrc.replace('%scaleFactor%', str(self.scaleFactor))
    self.html2psrc = self.html2psrc.replace('%getUrlPath%', str(self.getUrlPath))
    #we convert booleans into numbers
    self.html2psrc = self.html2psrc.replace('%pageInColor%', str(int(self.pageInColor)))
    self.html2psrc = self.html2psrc.replace('%grayScale%', str(int(self.grayScale)))
    self.html2psrc = self.html2psrc.replace('%underlineLinks%', str(int(self.underlineLinks)))

    #Add header and footer information
    self.html2psrc += "\nheader:\n" + self._processHeaderFooter(self.headers)
    self.html2psrc += "}\nfooter:\n" + self._processHeaderFooter(self.footers)
    self.html2psrc += '}'

    #Add in paper size if not present to ensure that headers/footer will always show
    rePage = re.compile('@page.*?{.*?size:\s*(.*?);', IGNORECASE or DOTALL)
    if not rePage.search(self.additionalCSS):
      self.additionalCSS += "\n@page:\n"
      self.additionalCSS += "  size: 8.5in 11in;\n"
      self.additionalCSS += "}\n"

    #add the global container
    self.html2psrc = """
        @html2ps:
          """ + self.html2psrc + """
        }
        """ + self.additionalCSS

  #==================================================================================
  def _getCSSFromFile(self):
    """Try to get the CSS from the html file and use it in creating the
       PDF file.  If we find CSS we'll add it to the CSS string.

    return string Any CSS found
    """
    if (self.useCSS):
      cssFound = ''
      #first try to find inline styles
      reStyle = re.compile('<style.*?>(.*?)</style>', IGNORECASE or DOTALL)
      style_matches = reStyle.findall(self._htmlString)
      if style_matches:
        cssFound = style_matches[0]
        #replace it with nothing in the html since it messes up html2ps
        self._htmlString = reStyle.sub('', self._htmlString)
      else:
        reLink = re.compile('<link .*? href=["\'](.*?)["\'].*?text/css.*?>', IGNORECASE)
        link_matches = reLink.findall(self._htmlString)
        if link_matches:      
          reHttp = re.compile('(^(?!http\://).*)', IGNORECASE)
          absolute = 'http://' + self.defaultDomain + '/\\1'
          cssFound = reHttp.sub(absolute, link_matches[0])

          fp = open(cssFound, "rb")
          cssFound = fp.read()
          fp.close()

      #only takes a:link attribute
      reLink = re.compile('a +{', IGNORECASE)
      cssFound = reLink.sub('a:link', cssFound)

      return cssFound
    else:
      return ''

  #==================================================================================
  def _getPaperSize(self):
    """Tries to determine the specified paper size since ps2pdf needs to be told explicitly
    in some cases.  Right now handles letter, ledger, 11x17, and legal.

    return the page size string
    :NOTE: We don't support the html2ps paper block since the @page block
    is the new correct way to do it.
    """
    rePage = re.compile('@page.*?{.*?size:\s*(.*?);', IGNORECASE or DOTALL)
    matches = rePage.findall(self.html2psrc)
    if not matches:
      matches = ['8.5in 11in']
      #Take out any extra spaces
    matches[0] = matches[0].replace(' ', '')
    if matches[0] == '8.5in14in':
      size = 'legal'
    elif matches[0] == '11in17in':
      size = '11x17'
    elif matches[0] == '17in11in':
      size = 'ledger'
    elif matches[0] == 'a4':
      size = 'a4'
    else:
      size = 'letter'
    return size

  #==================================================================================
  def _getOrientation(self):
    """Tries to determine the specified page orientaion since html2ps needs to be told explicitly.

    return The page orientation string
    """
    rePage = re.compile('@page.*?{.*?orientation:\s*(.*?);', IGNORECASE or DOTALL)
    matches = rePage.findall(self.html2psrc)
    if not matches:
      matches = ['portrait']

    if matches[0] == 'landscape':
      orientation = '--landscape'
    else:
      orientation = ''

    return orientation

  #==================================================================================
  def _processHeaderFooter(self, in_data):
    """Process either a set of headers or footers.
    in_data: The header or footer data (dictionary)
    return the html2ps string of data
    """
    s_data = ''
    #If not using odd/even attributes then override them with the main left/right/center keys
    #to ensure that the desired headers/footers get in

    for s_key in ['left', 'right', 'center']:
      if s_key in in_data:
        if not (("odd-" + s_key) in in_data):
          in_data["odd-" + s_key] = in_data[s_key]
        if not (("even-" + s_key) in in_data):
          in_data["even-" + s_key] = in_data[s_key]

    for s in in_data.items():
      s_data += "  " + s[0] + ': "' + s[1] + '"\n'

    return s_data

  #==================================================================================
  def _cleanup(self, in_files):
    """Cleans up the files we created during the script.

    array $in_files The array of temporary files
    """
    for file in in_files.items():
      if (self.debug):
        self._dumpDebugInfo(file[0] + ' file: ' + file[1] + ' (not removed)')
      else:
        os.unlink(file[1])

  #==================================================================================
  def _dumpDebugInfo(self, in_info):
    """If debug is on it dumps the specified debug information to screen.  Uses <pre> tags to save formatting of debug information.

    in_info: the debug info
    """
    if (self.debug):
      if (self.htmlErrors):
        print '<pre><span style="color: red;">DEBUG</span>: ' + in_info + '</pre>'
      else:
        print "DEBUG: " + in_info + "\n"

