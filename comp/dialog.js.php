<?php
//  Dialog til að 
?>
//<script type="text/javascript">
function initDialog(){

    var dialogCode = '<div id="lm_dialog" title="No title set" style="display: none;">';
    dialogCode += '<div id="lm_dialog_content"></div>';
    dialogCode += '</div>';

    $j("body").append(dialogCode);
}
    
    
function openDialog(titleName, dialogText, isModal, isResizable, isDraggable){
    
    $j('#lm_dialog_content').html(dialogText);
    $j('#lm_dialog').dialog({
      modal: isModal,
      resizable: isResizable,
      draggable: isDraggable,
      width: 'auto',
      title:titleName
    });
    $j('#lm_dialog').dialog('open');
}


function closeDialog(){
    
    $j('#lm_dialog_content').html("Vantar texta");
    $j('#lm_dialog').dialog('close');    
}

//<script>