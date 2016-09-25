<!-- page var -->
<input type="hidden" name="page_key" id="page_key" value="<?php echo page_key();?>" />
<!-- page var -->
<!-- Please Wait Dialog -->
<div class="modal fade" data-backdrop="static" data-keyboard="false" id="pw_modal<?php echo page_key();?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><span id="modalTitle"></span><?php echo page_title(); ?></h4>
                                        </div>
                                        <div class="modal-body">
                                       <!--  <div class="progress progress-striped active progress-sm">
                                    <div style="width: 10%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="10" role="progressbar" class="progress-bar progress-bar-success">
                                        <span class="sr-only">10% Complete</span>
                                    </div>
                                    
                                </div> -->
                                <p id="modalText"></p>
                               <div style="width:100%;text-align: center;">
                                <img width="100px"  src="<?php echo WPHT_PLUGIN_IMGDIR_URL;?>/gears2.gif" /></div>                                        
                                 </div>
                                    </div>
                                </div>
                            </div>
<!-- Please Wait Dialog -->                            
<!-- Model Dialog -->

<div class="modal fade" id="modal<?php echo page_key();?>" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title"><span id="modalTitle"></span> - <?php echo page_title(); ?></h4>
                                        </div>
                                        <div class="modal-body"><p id="modalText"></p></div>
                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-primary" type="button"> Ok</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
<!-- Model Dialog -->
<div class="wrap redux-container">
	<div id="icon-edit-pages" class="icon32"></div>
	<div id="redux-header">
            <div class="display_header">            
            <h2><?php echo $p_data['Name'];?></h2>
                            <span><?php global $p_set,$p_data; echo $p_data['Version'];?></span>            
        </div>
    
    <div class="clear"></div>
</div>
	<div class="wrapper">
		<div class="postbox plugin-wrap <?php echo getPluginShortName(); ?> <?php echo page_css_class(); ?>">
		<header class="panel-heading page-title"><h2><?php echo page_title(); ?><div id="load_bar_ctrl" class="pull-right"><img alt="Loading..." src="<?php echo WPHT_PLUGIN_IMGDIR_URL;?>/loading_bar.gif"></div></h2></header>