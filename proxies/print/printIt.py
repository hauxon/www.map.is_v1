#!C:/Python26/python.exe
# -*- coding: utf-8 -*-

#XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
#
# Author: Johan Lahti <johanlahti at gmail com>
# Copyright: Malmö Stadsbyggnadskontor, Malmö Stad, Sweden.
# License: MIT license
# Date: August 10, 2010.
#
# About: This code is meant to be used with the
#     OpenLayers library. It creates an image
#     of the current map, and thereby makes it possible
#     to save the map as an image or pdf. There
#     is some associated client code (javascript) which provides
#     the map configuration. This server side code uses
#     the given map configuration to create an image and
#     to paste icons into it, and draw features on it, at
#     the right place and with provided style, so that it
#     appears the same as the map you see in the browser.
#     Colours could differ a bit between the map in the browser
#     and the map in the image.
#
# Feel free to use and improve the code and to spread it further,
# just check the licenses.
#
# Dependencies (this code): Requires external libraries:
#     - json
#           http://pypi.python.org/pypi/python-json 
#     - Python Imaging Library (PIL)
#           http://www.pythonware.com/products/pil/
#     - aggdraw
#           http://effbot.org/zone/aggdraw-index.htm
#     - ReportLab Toolkit
#           http://www.reportlab.com/software/opensource/rl-toolkit/
#           http://www.reportlab.com/
#
# ---------------- Licenses for used libraries: ------------------------------------------------------------------------------------------
#
# The Python Imaging Library (PIL) is
#
#     Copyright © 1997-2006 by Secret Labs AB
#     Copyright © 1995-2006 by Fredrik Lundh
#
# By obtaining, using, and/or copying this software and/or its associated documentation, you agree that you have read, understood, and will comply with the following terms and conditions:
#
# Permission to use, copy, modify, and distribute this software and its associated documentation for any purpose and without fee is hereby granted, provided that the above copyright notice appears in all copies, and that both that copyright notice and this permission notice appear in supporting documentation, and that the name of Secret Labs AB or the author not be used in advertising or publicity pertaining to distribution of the software without specific, written prior permission.
#
# SECRET LABS AB AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS SOFTWARE, INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL SECRET LABS AB OR THE AUTHOR BE LIABLE FOR ANY SPECIAL, INDIRECT OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
#
# ---------------------------
#
# Aggdraw
# 
# Copyright © Fredrik Lundh
# License: Python (MIT style)
###
# ---------------------------
#
# JSON
#
# Author: Patrick Dlogan <patrickdlogan at stardecisions com>
# Copyright: 
# License: LGPL  
# 	 This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# ---------------------------
#
# ReportLab Toolkit
#
# About: A library for programatically creating documents in PDF format.
# Copyright: © ReportLab 2010
# License: BSD license
#
# -----------------------------------------------------

#XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

import cgi, cgitb
import json
import Image
import aggdraw
import urllib2
import cStringIO
import sys, os
import random
from operator import itemgetter



## webContentPath will added before the local file (image) paths in order
## to get the URL right. For some reason the home dir is set
## to wwwroot when this script is run.

class Storage:
    pass


# ------- "Help" functions -----------------------------------

def makeBlankImage(width, height, savePath=None):
    ''' Make a blank image into which everything
    will be pasted. If path, save it at path. '''
    blankImage = Image.new("RGBA", (width, height), (255,255,255,0))
    if savePath!=None:
        ext = savePath.split(".")[-1].upper()
        savePath = os.path.normpath(savePath) # normalize path
        blankImage.save(savePath, ext)
    return blankImage

def fetchImageWithUrl(url):
    req = urllib2.Request(url)
    # Make a temporary file without saving it on the harddrive.
    f = cStringIO.StringIO(urllib2.urlopen(req).read())
    img = Image.open(f)
    return img

def pasteImageOnPositions(bgImage, topImage, positions, opacity=None):

    # Make a new transparent image
    im = Image.new("RGBA", bgImage.size, (255,255,255,0))

    # We need to keep all images in the same mode.
    if topImage.mode=="P":
        topImage = topImage.convert("RGBA")

    # First, paste topImage into new image at given positions
    w = int(topImage.size[0])
    h = int(topImage.size[1])

    # A layer in OpenLayers which has opacity will no be saved in
    # the image. Therefore we have to add the opacity ourselves to
    # the image.
    if opacity!=None and opacity!=255:
        # Explained: We iterate through all pixels in the transparency/alpha band (no. 3).
        # If alpha is 0 (transparent) we let it be so, otherwise we add the given 'opacity'
        # to all other pixels.
        if topImage.mode=="RGB":
            newAlpha = Image.new("L", topImage.size, opacity)
        elif topImage.mode=="RGBA":    
            newAlpha = topImage.split()[3].point(lambda a: 0 if a==0 else opacity)
        topImage.putalpha(newAlpha)

    for p in positions:        
        bbox = (p[0], p[1], p[0]+int(w), p[1]+int(h))
        im.paste(topImage, bbox)

    # Then, make a mask which protects non-transparent areas (which we want to keep).
    # 0-value in mask means it will be protected, i.e. not be pasted over.
    alpha = im.split()[3]
    mask = alpha.point(lambda a: 255-a) # Invert numbers

    # Lastly, paste the background image on top of the new image using the mask.
    im.paste(bgImage, None, mask)

    return im

def cropImageToFit(bgImgSize, cropImg, left, top):
    imgW, imgH = bgImgSize
    cropImgW, cropImgH = cropImg.size

    # 1. Find out if (and how much) to crop from top and left

    bboxLeft = 0
    bboxTop = 0

    # The tile wont be visible...
    if left<(-cropImgW) or top<(-cropImgH) or left>imgW or top>imgH:
        return None

    # Cut from the left and top - invert numbers since
    # we are using a bbox (counting from left and top).
    if left<0:
        bboxLeft = -left # cut this much from left
    if top<0:
        bboxTop = -top # cut this much from top

    # 2. Find out if (and how much) we need to crop on the
    # other side (right or bottom)

    # Count overlap on right and bottom side
    # Negative means there is this amount of overlap
    # (-10 remaining width or height means 10 pixels overlap)

    remainingWidth = imgW - (left + cropImgW)
    remainingHeight = imgH - (top + cropImgH)


    bboxRight, bboxBottom = cropImg.size # if no cut is necessary these values will remain...
    
    # If overlap (remaining width/height is negative) - alter
    # the bbox so that this much will be cropped on the right/bottom
    # side.
    if remainingWidth<0:
        bboxRight = cropImgW - remainingWidth.__abs__()
    if remainingHeight<0:
        bboxBottom = cropImgH - remainingHeight.__abs__()

    bbox = (bboxLeft, bboxTop, bboxRight, bboxBottom)
    
    croppedImg = cropImg.crop(bbox)

    return croppedImg

def flatten2dList(lst):
    flatList = []
    for d in lst:
        for dd in d:
            flatList.append(dd)
    return flatList
        

# ------- "Help" functions END -----------------------------------


def getMapConfig():
    f = cgi.FieldStorage()
    width = f["width"].value
    height = f["height"].value
    layers = f["layers"].value
    outputPath = f["outputPath"].value
    quality = f["quality"].value
    headerText = f["headerText"].value
    scale = f["scale"].value

    outputPath = os.path.normpath(outputPath)
    
    # The dir to the folder where we can find the image folder.
    Storage.webContentPath = f["webContentPath"].value
    headerText = headerText if headerText!="null" else None
    
    dec = json.JSONDecoder()
    layersDict = dec.decode(layers)
    
    return int(width), int(height), layersDict, outputPath, int(quality), headerText, float(scale)

def pasteTile(img, t):
    ''' Paste the tiles into a blank image. '''
    
    x = t["x"]
    y = t["y"]
    opacity = t["opacity"]

    opacity = None if opacity=="null" else opacity #Make null into None...

    url = t["url"]

    try:
        tile = fetchImageWithUrl(url)
    except:
        return img
    
    croppedTile = cropImageToFit(img.size, tile, x, y)
    if croppedTile==None:
        return img
    
    # Normalize it to 0 so we can paste the image
    if x<0: x=0
    if y<0: y=0

    img = pasteImageOnPositions(img, croppedTile, [[x, y]], opacity=opacity)
        
    return img

def pasteVector(img, t):
    url = t["url"]
    features = t["features"]

    if url==None:
        # Draw the features

        # Extract the values from the dictionary
        strokeColor = t["strokeColor"]
        strokeOpacity = t["strokeOpacity"]
        strokeWidth = t["strokeWidth"]
        fillOpacity = t["fillOpacity"]
        fillColor = t["fillColor"]
        pointRadius = t["pointRadius"]

        # If fill color -> Create a brush
        if fillColor!=None:
            brush = aggdraw.Brush(fillColor, opacity=fillOpacity)

        # Set pen's draw width
        if t["strokeColor"]!=None:
            pen = aggdraw.Pen(strokeColor,\
                              width=strokeWidth,\
                              opacity=strokeOpacity)

        draw = aggdraw.Draw(img)

        # Iterate thorough features to find what geomType
        # to draw (point=ellipse, line=line, polygon=polygon)
        for f in features:
            geomType = f["geomType"]
            nodes = f["nodes"]

            nodes = flatten2dList(nodes)
            
            if geomType=="point":
                x0 = nodes[0] - pointRadius
                y0 = nodes[1] - pointRadius
                x1 = nodes[0] + pointRadius
                y1 = nodes[1] + pointRadius
                            
                draw.ellipse((x0, y0, x1, y1), pen, brush)

            elif geomType=="line":
                draw.line(nodes, pen)

            elif geomType=="polygon":
                draw.polygon(nodes, pen, brush)
            
            draw.flush()

        del draw
        
    else:
        if url.find("http")==-1:
            # If the URL is a local path, make sure to adapt it to the
            # location of this script so it can find the image folder.
            # preImageFolder is set on the top of this script.

            url = str(Storage.webContentPath) + str(url)
        
        try:
            w = t["graphicWidth"]
            h = t["graphicHeight"]
            size = (w, h)
        except:
            pass

        topImg = Image.open(url)
        
        for f in features:
            # Paste the features
            positions = f["nodes"]
            geomType = f["geomType"]
            fillOpacity = t["fillOpacity"]
                
            # Resize image to given graphic width and height
            try: a = size[0] + size[1]
            except: pass
            else: topImg = topImg.resize(size)
            
            img = pasteImageOnPositions(img, topImg, positions, fillOpacity)
            
    return img

def sortList(layersList):
    """ Sort the list according to z-index. """
    tempList = []
    for t in layersList:
        zIndex = t["zIndex"]
        tempList.append([zIndex, t])
    tempList.sort(key=itemgetter(0))
    
    sortedLayersList = []
    for i in tempList:
        sortedLayersList.append(i[1])
    return sortedLayersList
        

def pasteAllLayers(img, layersList):
    for t in layersList:
        if t["layerType"]=="tile":
            img = pasteTile(img, t)
        elif t["layerType"]=="vector":
            img = pasteVector(img, t)
    return img

def makeLegend(layersList):
    from PIL import ImageFont, ImageDraw, ImageOps
    
    legend = Image.new("RGBA", (400, 200), (255,255,255,255) )

    globX = 0
    globY = 0
    
    for t in layersList:
        layerName = t["layerName"]
        legendImageURL = t["legendImage"]

        if legendImageURL not in ["null", None]:
            icon = Image.open(Storage.webContentPath + "/" + legendImageURL)
            legend.paste(icon, (globX, globY, globX+icon.size[0], globY+icon.size[1]))

            legend = pasteImageOnPositions(legend, icon, [[globX, globY]], opacity=None)
            
            #font = ImageFont.load_path("C:/WINDOWS/Fonts/ARIAL.TTF")
            #font = ImageFont.truetype("arial.ttf", 15)
            #font = ImageFont.load("C:/WINDOWS/Fonts/ARIAL.TTF")
            #font = ImageFont.load_default()
            draw = ImageDraw.Draw(legend)
            draw.text( (globX+40, globY), layerName.encode("utf8"),  font=None, fill=(0,0,0))

            globY += 15
        
    return legend

def makeScaleBar(img, pxRatio):
    import ImageFont
    windir = os.environ.get("WINDIR")
    fontfile = os.path.join(windir, "Fonts", "ProFontWindows.ttf")
    font = ImageFont.load_default() #ImageFont.truetype(fontfile, 14)

    
    width = 100 # pixel width for scalebar
    margin = 10
    
    draw = aggdraw.Draw(img)
    
    pen = aggdraw.Pen("000000", width=2, opacity=255)
    xMax, yMax = img.size
    x = xMax-margin
    y = yMax-margin
    draw.line([x, y, x-width, y], pen)
    draw.line([x, y-5, x, y+5], pen)
    draw.line([x-width, y-5, x-width, y+5], pen)

    textVal = str(round(pxRatio/1000.0 * width)) + " km"

    draw.textsize(textVal, font.font)   #[10, 10], textVal, None) #[x-width/2, y-15]
    draw.flush()
    del draw
    return img

def makeRandomFileName(outputPath):
    # Add a random no. in the end of the filename to prevent
    # (minimize the risk of the same name for to images.
    directory = os.path.abspath(os.path.dirname(outputPath)) + "/"
    fileName = os.path.basename(outputPath)
    ext = fileName.split(".")[-1].upper()
    
    r = str(random.randint(0, 1000000000))
    fileNameArr = fileName.split(".")[:-1]
    fileNameArr.append("_"+r)
    outputPath = directory + "".join(fileNameArr) + "." + ext
    
    return outputPath

def replaceExt(path, newExt):
    if newExt[0]!=".":
        newExt = "." + newExt
    pathList = path.split(".")
    newPath = ".".join(pathList[:-1]) + newExt
    return newPath

def main():
    print "Content-Type: text/plain\n"
	
	print "Checkpoint 1"
	return

    # Get parameters from the client.
    width, height, layersList, outputPath, quality, headerText, scale = getMapConfig()
    img = makeBlankImage(width, height, None)
    layersList = sortList(layersList)
    img = pasteAllLayers(img, layersList)

    # If PDF - paste headerText and image into a new PDF document.
    if ext=="PDF":
        # Replace PDF-extension in outpath by PNG so we
        # can insert the PNG into the pdf later.
        outputPath = replaceExt(outputPath, "PNG")
        quality = 100 # Always use quality 100 for pdf
    # These formats cannot be written in mode RGBA
    elif ext=="BMP" and img.mode=="RGBA":
        img = img.convert("P")
    img.save(outputPath, quality=quality)
	
	

    # Take the saved image and put it into a pdf with a text header.
    if ext=="PDF":
        import toPDF
        pdfOutputPath = replaceExt(outputPath, "PDF")
        toPDF.makePdf(outputPath, pdfOutputPath, headerText)
        fileName = replaceExt(fileName, "PDF")
    
    print fileName # This file name is used for fetching the image from the client.

if __name__=='__main__':
    cgitb.enable() # for debugging
    main()
