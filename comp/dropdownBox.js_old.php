<?php if(1==2){?>//<script type="text/javascript"><?php }?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
function initDropdownBox(){

    //$j("#signinhenda").hide();
    var sHtml = "";
    sHtml += "<div id='sharecontainer' class='shareit'>";
    sHtml += "<fieldset id='share_menu'>";
    sHtml += "<div id='phTinyurl'><div>";
    sHtml += "<div id='shareSendIcon'><a href='#modalpopup' title='Smelltu hér til að senda kortið í tölvupósti'><img  src='img/poi/send_icon.gif' border='0'></a></div>";
    sHtml += "<div id='shareSendText'><a href='#modalpopup' title='Smelltu hér til að senda kortið í tölvupósti'>Senda</a></div>";
    sHtml += "<div id='shareFacebookIcon'><a rel='nofollow' onclick='shareFacebook();' href='#' title='Smelltu hér til að deila kortinu á Facebook'><img  src='img/poi/facebook_icon.gif' border='0'></a></div>";
    sHtml += "<div id='shareFacebookText'><a rel='nofollow' onclick='shareFacebook();' href='#' title='Smelltu hér til að deila kortinu á Facebook'>Facebook</a></div>";
    sHtml += "<div id='shareTwitterIcon'><a rel='nofollow' onclick='shareTwitter();' href='#' title='Smelltu hér til að deila kortinu á Twitter'><img src='img/poi/twitter_icon.gif' border='0'></a></div>";
    sHtml += "<div id='shareTwitterText'><a rel='nofollow' onclick='shareTwitter();' href='#' title='Smelltu hér til að deila kortinu á Twitter'>Twitter</a></div>";
    sHtml += "</fieldset>";
    sHtml += "</div>";
    
    $j("body").append(sHtml);    
    
    $j(document).ready(function() {        

        $j("fieldset#share_menu").mouseup(function() {
            return false;
        }); 
        
        $j(document).mouseup(function(e) {
            if( $j(e.target).is("#headerBtnDivShare") || $j(e.target).is("#headerBtnShare") ){
               if( $j("fieldset#share_menu").hasClass('menu-open') ) {
                    headerButtonClear("Share");
                }                
            }else{
                
                if( $j("fieldset#share_menu").hasClass('menu-open') ) {
                    headerButtonClear("Share");
                    $j("fieldset#share_menu").toggle();
                    $j("fieldset#share_menu").toggleClass("menu-open");                 
                }
            }
        });
    });
    /*$j(function() {
	  $j('#forgot_username_link').tipsy({gravity: 'w'});   
    });   */ 
}


function doShare(){
    $j("#sendMailDescriptDiv").remove();
    $j("#share_menu").prepend(getPermaText());
    
    if( $j("fieldset#share_menu").hasClass('menu-open') ) {
        headerButtonClear("Share"); 
    }
    
    $j("fieldset#share_menu").toggle();
    $j("fieldset#share_menu").toggleClass("menu-open");  
    //addthis_share.email_vars.permalinkurinn = $j("#inputLinkEmail").val();
    /*$j("#facebook_share").attr("href", "http://platform.twitter.com/widgets/tweet_button.html?href=" + $j("#inputLinkEmail").val() );
    $j("#twitter_share").attr("href", "http://platform.twitter.com/widgets/tweet_button.html?text=www.map.is&url=" + $j("#inputLinkEmail").val() );*/
        
    var linkur = sendSyncAJAXRequest('proxies/tinyUrl_proxy.php?longURL=' + escape( viewLink.div.childNodes[0].toString().replace("#","") ) );
    strHTML = "<div class='sendMailText' id='sendMail' style='background:#FFFFFF'>";
    strHTML += "<div class='sendMailToDivTitle' id=''>Hér getur þú sent slóð á kortið á tölvupóstfang</div>";
    strHTML += "<div class='sendMailToDiv' id=''><div><b>Til:</b></div><div><input type='text' class='sendMailTo'  id='sendMailToSendMail'/></div>Aðskiljið netföng með, eða ; &nbsp;&nbsp;&nbsp;<i> (t.d. abc@gmail.com; def@gmail.com)</i></div>";
    strHTML += "<div class='sendMailFromDiv'><div><b>Frá:</b></div><div><input type='text' class='sendMailFrom' id='sendMailFromSendMail'/> <b>Ath. þennan reit verður að fylla út</b></div><input type='checkbox' id='sendMailCopy2MeSendMail' /> Senda afrit á netfangið mitt</div>";
    strHTML += "<div class='sendMailMessageDiv'><div><b>Skilaboð:</b></div><div><textarea class='sendMailMessage' id='sendMailMessageSendMail'>Hæ, mig langar að deila með þér þessu korti af www.map.is - Loftmyndir ehf. Vefslóð(stytt):<" + linkur + "></textarea><div></div>";
    strHTML += "<div class='sendMailButtonsDiv'><input type='button' value='Senda' onclick='sendMail();closeModalWindow();' /> <input type='button' value='Hætta við' onclick='closeModalWindow();' /></div>";    
    strHTML += "</div>";        
    $j("#modalpopup").html(strHTML); 
    doModal($j('#shareSendIcon a')); 
    doModal($j('#shareSendText a'));        
}

//</script>
