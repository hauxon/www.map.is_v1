#************************************************************
# Author: Johan Lahti <johanlahti at gmail com>
# License: MIT License
# Copyright: Malmo Stadsbyggnadskontor, Malmo kommun.
# About: This Script puts an image into a PDF and saves it on disk.
#************************************************************

#!C:/Python26/python.exe
# -*- coding: utf-8 -*-

from PIL import Image

import reportlab
from reportlab.pdfgen import canvas
from reportlab.lib.utils import ImageReader

def makePdf(imagePath, savePath, text=None, legendPath=None):
    c = canvas.Canvas(savePath, reportlab.lib.pagesizes.A4)

    c.setFont('Helvetica', 32)
    
    c.setPageCompression(0)

    if type(imagePath)==type("string"):
        img = Image.open(imagePath)
    else:
        img = imagePath

    # Resize using interpolation to make pic smoother.
    
    #img = img.resize((300,300), Image.BICUBIC)
    ir = ImageReader(img)

    if text!=None:
        c.drawCentredString(295, 660, text)
    
    c.drawImage(ir, 100, 200, width=img.size[0]/1.3, height=img.size[1]/1.3, preserveAspectRatio=True )
    c.showPage()
    c.save()


if __name__=="__main__": 
    pass
    #imagePath = r"img_output.PNG"
    #savePath = r"savedPDF.pdf"
    #makePdf(imagePath, savePath, text="This is my map")
    
    
