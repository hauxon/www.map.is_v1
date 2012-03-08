//<script type="text/javascript">
<?php
/* 
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 * Component: Suggestions
 * Purpose: Suggestions will suggest words when searching.  You will attach
 * the suggestion component to a form inputfield and it will suggest keywords
 * according to the specified keyword database.
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
?>
        
  

function lookup(searchInputString){
    if(searchInputString.length < 2) 
    {
        // Hide the suggestion box.
        $j('#suggestions').hide();
    } else 
    {
        $j.post("db/suggestions_db.php", {queryString: "" + searchInputString + ""}, function(data){
            if(data.length > 0) 
            {
                $j('#suggestions').show();
                $j('#autoSuggestionsList').html(data);
            }
        });
    }
} // lookup

function fill(thisValue) {
    
    log('fill textbox');
    if( typeof(thisValue) != 'undefined' )
    {
        $j('#searchInputString').val(thisValue);
        //alert(thisValue);
        //$j('#searchInputString').val('Thundercats!');
        setTimeout("$j('#suggestions').hide();", 200);
        log('fill calls searchInputEnter()');
        searchInputEnter()
    }
    else
    {
        setTimeout("$j('#suggestions').hide();", 200);
        //$j('#searchInputString').focus();
    }
}


var searchBoxHTML = '<div id="searchDiv" style="position: relative; top: 20px; left: 150px;z-index:1000;width:400px;">';
searchBoxHTML += '<div><form action=javascript:log("sFormSubmitted");setTimeout("$j("#suggestions").hide();",200);searchInputEnter();><input type="text" style="height:30px; width: 323px;font-size: 10pt;" value="" id="searchInputString" AUTOCOMPLETE=OFF />';
searchBoxHTML += '<input type="image" title="Leita" value="" src="img/gui/s_btn_blue2_27x27.png" style="width:27px;height:27px;position:absolute;left:298px;top:5px;z-index:1001;background-image:url(img/gui/s_btn_blue2_27x27.png);"></form></div>';
searchBoxHTML += '<div class="suggestionsBox" id="suggestions" style="display: none;">';
searchBoxHTML += '<img src="img/gui/upArrow.png" style="position: relative; top: -12px; left: 5px;" alt="upArrow" />';
searchBoxHTML += '<div class="suggestionList" id="autoSuggestionsList">&nbsp;</div></div></div>';


// Enter þarf að vera höndlaður sem form submit þar sem IE triggerar ekki event á keycode 13!!!
// Helv fokk
function searchInputEnter()
{
    // Tékkar á hvort suggestions glugginn er opinn og leitar eftir því
    // Leitar annars eftir því sem komið er í input boxið
    log('starting searchInputEnter()');
    if ( $j('#autoSuggestionsList').is(':visible') )
    {
        
        
        var curr = $j('#autoSuggestionsList').find('.current');

        // suggestionbox visible but none is selected (current)
        if(typeof(curr[0]) == 'undefined')
        {
            setTimeout("$j('#suggestions').hide();", 200);
            submitMapSearch();
            return;
        }
        
        
        $j('#searchInputString').val(curr[0].innerHTML);
        setTimeout("$j('#suggestions').hide();", 200);
        submitMapSearch();
    }
    else
    {
        submitMapSearch();
    }
};

function initSuggestions()
{


    // Do stuff
    $j("body").append(searchBoxHTML); // Öddum þessu á domið
    
    $j('#searchInputString').keyup(
        function (e)
        {
                    
            if (e.keyCode == 13) // Enter
            {       
                if ( $j('#autoSuggestionsList').is(':visible') )
                {
                    var curr = $j('#autoSuggestionsList').find('.current');
                    
                    //alert(curr[0].textContent);
                    //debugger;
                    // suggestionbox visible but none is selected (current)
                    if(typeof(curr[0]) == 'undefined')
                    {
                        setTimeout("$j('#suggestions').hide();", 200);
                        submitMapSearch();
                        return;
                    }
                    
                    //alert('debug' + curr[0].textContent);
                    $j('#searchInputString').val(curr[0].innerHTML);
                    setTimeout("$j('#suggestions').hide();", 200);
                    submitMapSearch();
                }
                else
                {
                    
                    submitMapSearch();
                }
                return;
            }
            
           if (e.keyCode == 40 || e.keyCode == 38) // Upp niður takkar
           {
            var curr = $j('#autoSuggestionsList').find('.current');    
            if (e.keyCode == 40)
                {   
                    var curr = $j('#autoSuggestionsList').find('.current');
                    if(curr.length)
                    {
                            $j(curr).attr('class', 'display_box');
                            $j(curr).next().attr('class', 'display_box current');
                    }
                    else{
                        $j('#autoSuggestionsList li:first-child').attr('class', 'display_box current');
                    }    

                }
                if(e.keyCode==38)
                {                                        
                    if(curr.length)
                    {                            
                            $j(curr).attr('class', 'display_box');
                            $j(curr).prev().attr('class', 'display_box current');
                    }
                    else{
                        $j('#autoSuggestionsList li:last-child').attr('class', 'display_box current');
                    }   

                }
            }
            else
            {
                lookup(this.value);
            }
        } 
    )
    
}





//</script>