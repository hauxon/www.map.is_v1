<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
//<script lang="javascript">

// Global af því að það er svo gaman!
var headerButtonArray = new Array();
// búum tol JS object til að halda utan um proprty fyrir hnappa í haus
function headerButtonObj(name, icon, icon_hover, icon_selected, captionICE, captionENG, action, offAction){
    this.name = name;
    this.icon = icon;
    this.icon_hover = icon_hover;
    this.icon_selected = icon_selected;
    this.captionICE = captionICE;
    this.captionENG = captionENG;
    this.action = action;
    this.offAction = offAction;
    this.selected = false; //Keeps track og button state
}

function initHeaderButtons()
{
    // Do something, just....
    
    //Let's construct a row of buttons
    // Reads headerButtons.xml to find out what buttons are to be displayed
    var buttonsHTML = "";
    
    <?php
	// Opnum config XML
	$myFile = "../xml/headerButtons.xml";
	$fh = fopen($myFile, 'r');
	$headerButtons = new SimpleXMLElement(fread($fh, filesize($myFile)));
	fclose($fh);
        $counter = 0;
        foreach ($headerButtons->xpath('//button') as $button) 
	{
            
            // Búum til headerBtn óbjekt og dælum inn í fylki
            echo "var HBobj = new headerButtonObj('" . $button->name . "','" . $button->icon . "','" . $button->icon_hover . "','" . $button->icon_selected . "','" . $button->captionICE . "','" . $button->captionENG . "','" . $button->action . "','" . $button->offAction . "');\n";
            echo "headerButtonArray['" . $button->name . "'] = HBobj; \n";    
            
            // do not insert divider if we're on first button
           if($counter != 0)
           {
               echo "buttonsHTML += ' | ';\n";
           }
           // write out the button
           echo "buttonsHTML += '<img id=headerBtn" . $button->name . " class=headerBtnImg src=" . $button->icon . " onclick=javascript:pressHeaderButton(\"" . $button->name  . "\");  onMouseOver=flickHeaderButton(\"" . $button->name  . "\"); onMouseOut=headerButtonOFF(\"" . $button->name  . "\");><div id=headerBtnDiv" . $button->name . " class=headerBtnTxt  onclick=javascript:pressHeaderButton(\"" . $button->name  . "\"); onMouseOver=flickHeaderButton(\"" . $button->name  . "\"); onMouseOut=headerButtonOFF(\"" . $button->name  . "\");>" . $button->captionICE . "</div>';";
           $counter++;
        }
    ?>
    
    $j('#headerButtons').html(buttonsHTML);
}

function flickHeaderButton(buttonID)
{   
    var imageElement = "#headerBtn" + buttonID;
    var DivElement = "#headerBtnDiv" + buttonID;

    if($j(imageElement).attr("src") == headerButtonArray[buttonID].icon_hover)
    {
        // slökkvum á takkanum
        $j(imageElement).attr("src",  headerButtonArray[buttonID].icon);
        $j(DivElement).removeClass('headerBtnTxtON');
    }
    else
    {
        // Kveikjum á takkanum
        $j(imageElement).attr("src",  headerButtonArray[buttonID].icon_hover);
        $j(DivElement).addClass('headerBtnTxtON');
    }
}

function headerButtonON(buttonID)
{   
    var imageElement = "#headerBtn" + buttonID;
    var DivElement = "#headerBtnDiv" + buttonID;
    
    if($j(imageElement).attr("src") == headerButtonArray[buttonID].icon_hover)
    {
        return;  //þarf ekki að kveikja
    }
    
    // Kveikjum á takkanum
    $j(imageElement).attr("src",  headerButtonArray[buttonID].icon_hover);
    $j(DivElement).addClass('headerBtnTxtON');
}

function headerButtonOFF(buttonID)
{   
    var imageElement = "#headerBtn" + buttonID;
    var DivElement = "#headerBtnDiv" + buttonID;
    // slökkvum á takkanum
    $j(imageElement).attr("src",  headerButtonArray[buttonID].icon);
    $j(DivElement).removeClass('headerBtnTxtON');
}

function pressHeaderButton(buttonID)
{  
    var imageElement = "#headerBtn" + buttonID;
    var DivElement = "#headerBtnDiv" + buttonID;

   if(  headerButtonArray[buttonID].selected == false )
   {
        // Hreinsum öll mouseover og mouseout attribjút af
        //fyrst textinn
        $j(DivElement).removeAttr('onMouseOver');
        $j(DivElement).removeAttr('onMouseOut');
        $j(DivElement).removeClass('headerBtnTxtON');
        // svo íkonið
        $j(imageElement).removeAttr('onMouseOver');
        $j(imageElement).removeAttr('onMouseOut');
        
        // Flöggum takkan í selected stöðu
        headerButtonArray[buttonID].selected = true; 
        // Setjum inn selected ikon myndina
        $j(imageElement).attr("src",  headerButtonArray[buttonID].icon_selected); 
        // Setjum inn selected class á textann
        $j(DivElement).addClass('headerBtnTxtSelected');  
        
        // keyrum takka skriptið
        eval(headerButtonArray[buttonID].action);
   }
   else
   {
        //debugger;
        // Tökum selected flaggið af
        headerButtonArray[buttonID].selected = false;
        // Tökum selected classann af
        $j(DivElement).removeClass('headerBtnTxtSelected');
         
        // action fyrir mouseOut
        var mOutVal = "javascript:headerButtonOFF('" + buttonID + "')";
        // action fyrir mouseOver
        var mOverVal = "javascript:headerButtonON('" + buttonID + "')";
        
        // action sett á mouseOver
        $j(DivElement).attr('onMouseOver', mOverVal);
        $j(imageElement).attr('onMouseOver', mOverVal);
        // action sett á mouseOut
        $j(DivElement).attr('onMouseOut', mOutVal);
        $j(imageElement).attr('onMouseOut', mOutVal);
        
        // standard icon sett inn
        $j(imageElement).attr("src",  headerButtonArray[buttonID].icon);
        eval(headerButtonArray[buttonID].offAction);
        
   }

    /* 
    if($j(imageElement).attr("src") != headerButtonArray[buttonID].icon_selected)
    {
        
        $j(imageElement).attr("src",  headerButtonArray[buttonID].icon_selected);
        $j(DivElement).addClass('headerBtnTxtSelected');
        eval(headerButtonArray[buttonID].action);
    }
    else
    {
        //flickHeaderButton(buttonID);
        $j(imageElement).attr("src",  headerButtonArray[buttonID].icon);
        $j(DivElement).removeClass('headerBtnTxtSelected');
    }*/
}


function headerButtonClear(buttonID){
    
    var imageElement = "#headerBtn" + buttonID;
    var DivElement = "#headerBtnDiv" + buttonID;
   //if(  headerButtonArray[buttonID].selected == true )
   //{
        // Tökum selected flaggið af
        headerButtonArray[buttonID].selected = false;
        // Tökum selected classann af
        $j(DivElement).removeClass('headerBtnTxtSelected');

        // action fyrir mouseOut
        var mOutVal = "javascript:headerButtonOFF('" + buttonID + "')";
        // action fyrir mouseOver
        var mOverVal = "javascript:headerButtonON('" + buttonID + "')";

        // action sett á mouseOver
        $j(DivElement).attr('onMouseOver', mOverVal);
        $j(imageElement).attr('onMouseOver', mOverVal);
        // action sett á mouseOut
        $j(DivElement).attr('onMouseOut', mOutVal);
        $j(imageElement).attr('onMouseOut', mOutVal);

        // standard icon sett inn
        $j(imageElement).attr("src",  headerButtonArray[buttonID].icon);
   // }
}