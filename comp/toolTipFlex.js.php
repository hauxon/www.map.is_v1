<?php
/*
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 toolTipFlex.js.php
 Dependencies: tooltipflex.css
 
 Declares no global variables to be run
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
?>
function initToolTipFlex()
{
        var sHtmlToolTip = '<div id="ToolTipFlex">';
        sHtmlToolTip +=    '    <div id="ToolTipFlexContent">';
        sHtmlToolTip +=    '        <table border="0" cellspacing="0" cellpadding="0">';
        sHtmlToolTip +=    '            <tr id="top">';
        sHtmlToolTip +=    '                <td id="starttop">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '                <td id="middletop">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '                <td id="endtop">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '            </tr>';
        sHtmlToolTip +=    '            <tr id="middle">';
        sHtmlToolTip +=    '                <td id="startmiddle">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '                <td id="ToolTipFlexText">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '                <td id="endmiddle">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '            </tr>';
        sHtmlToolTip +=    '            <tr id="bottom">';
        sHtmlToolTip +=    '                <td id="startbottom">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '                <td id="middlebottom">';
        sHtmlToolTip +=    '                    <div id="popout_l_b" ></div>';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '                <td id="endbottom">';
        sHtmlToolTip +=    '                </td>';
        sHtmlToolTip +=    '            </tr>';
        sHtmlToolTip +=    '        </table>';
        sHtmlToolTip +=    '    </div>';
        sHtmlToolTip +=    '    <a id="fancy_close" onclick="var tb = document.getElementById(\'ToolTipFlex\');tb.style.visibility =\'hidden\';"></a>';
        sHtmlToolTip +=    '</div>';
        $j("body").append(sHtmlToolTip);
}