<?php if ( ! defined( 'ABSPATH' ) ) exit; 
include(SMFB_PLUGIN_PATH.'admin/views/UI/tabs/form-analytics-function.php');

      if ( is_plugin_active( 'PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php' ) ) {
        $goProLink = '';
      }else{
        $goProLink = '<a href="https://pluginops.com/page-builder/?ref=Optin_editorBar" target="_blank" ><div class="goProButton">Want More Features ?</div></a>';
      } ?>
<script type="text/javascript" src="<?php echo(SMFB_PLUGIN_URL.'/js/Chart.js'); ?>"></script>
<div class="pluginops-tabs">
  <ul class="pluginops-tab-links">
    <li class="pluginops-active"><a href="#tab1" class="pluginops-tab_link">Editor</a></li>
    <li><a href="#tabFormSubmissions" class="pluginops-tab_link formSubmissionsTab">Form Submissions</a></li>
    <li><a href="#tab4" class="pluginops-tab_link">Email Subscribers</a></li>
    <!--<li><a href="#tab2" class="pluginops-tab_link">Templates</a></li>
     <li><a href="#tab3" class="pluginops-tab_link">Page Options</a></li> 
    <li><a href="#tab5" class="pluginops-tab_link exportTemplateTab_ppb" style="display: none;">Insert Template</a></li>
    -->
    <li><a href="#tab6" class="pluginops-tab_link abTestTab_ppb">A/B Testing</a></li>
    <li><a href="#tab7" class="pluginops-tab_link exportTemplateTab_ppb" style="display: none;">Export Template</a></li>
    <li><a href="#tab8" class="pluginops-tab_link">Analytics</a></li>
  </ul>
  <div class="pluginops-tab-content pb_editor_tab_content" style="overflow: hidden; ">
    <div id="tab1" class="pluginops-tab pluginops-active" style="margin: 0 auto !important;">
      <div class="lpp_modal pb_loader_container_pageload" style="display: block;">
        <div class="pb_loader" id='pb_loader_pageinit'></div>
      </div>
      <div id="top-optionsBar">
            <div class=" undo-redo-btn" id="pbbtnUndo" style="" ><span class="dashicons dashicons-undo" title="Undo Changes"></span></div>
            <div class=" undo-redo-btn" id="pbbtnRedo" style="margin-right: 0%; "><span class="dashicons dashicons-redo" title="Redo Changes"></span> </div>
          
          <?php echo "$goProLink"; ?>
          <div class="pb_fullScreenEditorButtonClose">Close Editor <span class="dashicons dashicons-editor-contract"></span> </div>
          <div class="draftBtn SavePage">Save Draft</div>
          <div class="publishBtn SavePage">Publish</div>
      </div>
      <br>
      <div class="pb_fullScreenEditorButton" style="padding: 20px; color:#fff; background-color: #4e82ff; margin: 100px auto 0px; text-align: center; max-width: 175px; font-size: 22px; cursor: pointer; border-bottom: 8px solid rgb(37, 75, 165); display: none; border-radius:10px; "> Open Editor <span class="dashicons dashicons-editor-expand"></span> </div>
      <br>
      <br>
      <br>
      <br>
      <br>
      <div id="pbWrapper">
        <div id="pbWrapperContainerOverlay"></div>
        <ul id="container">
          <script type="text/template" id="item-template"></script>
        </ul>
      </div>
      <div class="newRowBtnContainerVisible" style="display: none;">  
        <div class="newRowBtnContainerSections"> <div class="addNewRowVisible  row-section-btn" style="background:#5AB1F7;" > ADD NEW SECTION</div>
        </div>
        <div class="newRowBtnContainerSections" style="display: none;">    <div class="addNewGlobalRowVisible  row-section-btn" style="background:#F1D204;" > INSERT GLOBAL ROW</div>   
        </div>  
      </div>
      <div id="SavePage" class="btn-green aligncenter large-btn SavePage" style="margin-top: 50px; margin-bottom: 100px;">Save</div>
    </div>
    <div id="tab3" class="pluginops-tab">       
    </div>
    <div id="tab4" class="pluginops-tab" style="background: #fff;">
        <?php include(SMFB_PLUGIN_PATH.'admin/views/UI/widgets/widget-form-subs-list.php'); ?>    
    </div>
    <div id="tab5" class="pluginops-tab" style="width: 100%; background: #fff;">
        <?php include(SMFB_PLUGIN_PATH.'admin/views/UI/tabs/insertTemplate.php'); ?>    
    </div>
    <div id="tab6" class="pluginops-tab" style="width: 100%; background: #fff;">
        <?php include(SMFB_PLUGIN_PATH.'admin/views/UI/tabs/abSelect.php'); ?>    
    </div>
    <div id="tabFormSubmissions" class="pluginops-tab" style="width: 100%; background: #fff;">
        <?php include(SMFB_PLUGIN_PATH.'admin/views/UI/tabs/form-submissions.php'); ?> 
    </div>
    <div id="tab7" class="pluginops-tab" style="width: 100%; background: #fff;">
        <p style="  text-indent: 50px;"> Please click on export template button to get the current template code or paste your template code in field below and click on import template to update current template with imported one.</p>
        <br>
        <br>
        <textarea class="pb_export_template" style="width: 80%; height: 400px;"></textarea>   
        <br>
        <div class="btn updateExportedTemplate large-btn" style="padding: 20px; margin-left: 45px;  "> Import Template</div>
        <div class="btn btn-red populateCurrentTemplate large-btn" style="padding: 20px; "> Export Template</div>
    </div>
    <div id="tab8" class="pluginops-tab" style="width: 100%; background: #fff;">
        <?php include(SMFB_PLUGIN_PATH.'admin/views/UI/tabs/form-analytics.php'); ?>
    </div>
  </div>
  <br>
</div>