<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>      
  
        if( client_select_wfs_arr.length > 0 )
        {
            var ttoptions = {
                    hover: true,
                    onSelect: onClientSelectCallback,
                    onUnselect: onClientUnselectCallback,
                    clickFeature: onClientClickCallback	
            }        
            var the_select = new OpenLayers.Control.SelectFeature([client_select_wfs_arr], ttoptions);
            map.addControl(the_select);

            the_select.activate();	        
        }