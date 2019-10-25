<?php if ( ! defined( 'ABSPATH' ) ) exit; 

if (isset( $_GET['thisPostID'] )) {
  $postId = $_GET['thisPostID'];
  $post = get_post($postId);
}elseif(isset( $_GET['post'] )) {
  $postId = $_GET['post'];
}else{
  $postId = $post->ID;
}

$post_slug = $post->post_name;

if ($post_slug == '') {
  $hidePermalink = 'display:none !important;';
}else{
  $hidePermalink = '';
}

$ULPB_CurrentStep = get_post_meta($post->ID, 'ULPB_CurrentStep', true );
$is_front_page = get_post_meta($post->ID, 'ULPB_FrontPage', true );
$loadWpHead = get_post_meta($post->ID, 'ULPB_loadWpHead', true );
$loadWpFooter = get_post_meta($post->ID, 'ULPB_loadWpFooter', true );
$pbPP_iconFolderURL = SMFB_PLUGIN_URL.'/images/icons/';
$ppb_post_status = get_post_status( $post->ID );
$currentStep = '';

if (isset($ULPB_CurrentStep)) {
  $currentStep = $ULPB_CurrentStep;
}

if ($currentStep == '3' || $ppb_post_status == 'draft' || $ppb_post_status == 'publish') {
}else{
?>
  <div id="initializeCampaignSetup" class='stepModal'  style="display: none;">
    <!-- <h1 style="text-align: center;" class="stepCount"> 1 <br>Step</h1> 
    <div class="stepModalContent stepOneContent" >
      <input type="text" class="campaignNameField  stepDataField" placeholder="Enter Campaign Name"> <br> <br>
      <div class="setCampaignName nxtStepBtn"> Next </div>
    </div>
    -->
    <div class="stepModalContent stepTwoContent" style="display: block;">
      <h2>Select Campaign Type</h2>
      <br><br>
      <div class="stepCard" data-OptinType='Inline'>
        <img src="<?php echo $pbPP_iconFolderURL.'optin-types-icon-inline.png'; ?>">
        <br>
        <p>Inline</p>
      </div>
      <div class="stepCard" data-OptinType='PopUp'>
        <img src="<?php echo $pbPP_iconFolderURL.'optin-types-icon-popup.png'; ?>">
        <br>
        <p>PopUp</p>
      </div>
      <div class="stepCard" data-OptinType='Bar'>
        <img src="<?php echo $pbPP_iconFolderURL.'optin-types-icon-bar.png'; ?>">
        <br>
        <p>Bar</p>
      </div>
      <div class="stepCard" data-OptinType='Fly In'>
        <img src="<?php echo $pbPP_iconFolderURL.'optin-types-icon-flyIn.png'; ?>">
        <br>
        <p>FlyIn</p>
      </div>
      <div class="stepCard" data-OptinType='Full Page'>
        <img src="<?php echo $pbPP_iconFolderURL.'optin-types-icon-fullpage.png'; ?>">
        <br>
        <p>Full Page</p>
      </div>
      <div class="stepCard" data-OptinType='Side'>
        <img src="<?php echo $pbPP_iconFolderURL.'optin-types-icon-sidebar.png'; ?>">
        <br>
        <p>Side Bar</p>
      </div>
      <br><br>
      <div class="setCampaignType nxtStepBtn"> Next </div>
      <input type="hidden" class="selectedOptinType">
      <br><br>
    </div>
    <div class="stepThreeContent" style="display: none; margin: 0 auto;height: 1200px; text-align: center;">
      <br><h2 style="font-size: 24px;"> Select A Template </h2><br>
      <div id='templatesInstallDiv' class="templatesInstallDivOne" style="display: none; ">
        <i class='fa fa-circle-o-notch'></i> Get All These Templates <a href='https://pluginops.com/page-builder/?ref=Optin_templates' target='_blank'>Unlock Premium Templates</a>
      </div>
      <br>
      <?php include(SMFB_PLUGIN_PATH.'admin/views/UI/tabs/templates-tab.php'); ?>
      <br>
      <br>
      <div style="padding: 10px 10px 20px 10px; position: fixed; bottom: 0; left:0; right: 0;  width: 100vw; background: #fff;">
        <div class="setTemplateStepPrev prevStepBtn"> Previous </div>
        <div class="setTemplateStep nxtStepBtn updateTemplate"> Next </div>
      </div>
      <br><br><br><br>
    </div>
  </div>
<?php } 


if (isset( $_GET['thisPostID'] ) ) {
  ?>
  <br><br><br>
  <div id="titlediv">
    <div id="titlewrap">
        <label class="screen-reader-text" id="title-prompt-text" for="title"> </label>
      <input type="text" name="post_title" size="30" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Enter Page Title">
    </div>
    <div class="inside" style="display: none;">
      <div id="edit-slug-box" class="hide-if-no-js" style="<?php echo "$hidePermalink"; ?>">
      <strong>Permalink:</strong>
      <span id="sample-permalink">
        <a href="<?php echo(site_url( ) ); ?>/?post_type=pluginops_forms&amp;p=<?php echo($postId); ?>&amp;preview=true" target="wp-preview-4882"><?php echo(site_url( ) ); ?>/<span id="editable-post-name"><?php echo $post_slug; ?></span>/</a>
      </span>
      ‎<span id="edit-slug-buttons">
        <input type="text" class="editable-post-name-field" style="display: none; width: auto; height:24px; font-size: 13px; ">
        <button type="button" class="edit-slug button button-small hide-if-no-js" aria-label="Edit permalink">Edit</button>
        <button type="button" class="savePermalink  button button-small" style="display: none;">OK</button>
    </span>
      </div>
    </div>
    <span id="editable-post-name-full"><?php echo $post_slug; ?></span>
  </div>
  </div>
  <script type="text/javascript">
    ( function( $ ) {
      $('.edit-slug').click(function(){
          var prevTxt = $('#editable-post-name').text();
          $('.editable-post-name-field').val(prevTxt);
          $('#editable-post-name').css('display','none');
          $('.edit-slug').css('display','none');
          $('.editable-post-name-field').css('display','inline-block');
          $('.savePermalink').css('display','inline-block');
      });

      $('.savePermalink').click(function(){
          $('#editable-post-name').html( $('.editable-post-name-field').val() );
          $('#editable-post-name-full').html( $('.editable-post-name-field').val() );
          $('#editable-post-name').css('display','inline-block');
          $('.edit-slug').css('display','inline-block');
          $('.editable-post-name-field').css('display','none');
          $('.savePermalink').css('display','none');
      });
    })(jQuery);
  </script>
  <?php
}


?>

  <?php include('tabs.php'); ?>
  <?php include('edit-column.php'); ?>
  <?php include('edit-row.php'); ?>
  <?php include('edit-widget.php'); ?>
  <?php include('new-row.php'); ?>
  <?php include('side-panel.php'); ?>
  <?php include('container-options.php'); ?>


<style type="text/css" id="PBPO_customCSS"></style>


<script src="<?php echo SMFB_PLUGIN_URL.'/js/aceSRC/ace.js' ?>" type="text/javascript" charset="utf-8"></script>


<script>
  
    var PbPOaceEditorJS = ace.edit("PbPOaceEditorJS");
    PbPOaceEditorJS.setTheme("ace/theme/dawn");
    PbPOaceEditorJS.getSession().setMode("ace/mode/javascript");

    var PbPOaceEditorCSS = ace.edit("PbPOaceEditorCSS");
    PbPOaceEditorCSS.setTheme("ace/theme/dawn");
    PbPOaceEditorCSS.getSession().setMode("ace/mode/css");

</script>

<div class="pb_loader_container">
  <div class="pb_loader"></div>
</div>

<div class="lpp_modal pb_preview_container" style="">
  <div class="pb_temp_prev" style="text-align: center; overflow: visible;" ></div>
</div>

<div class="lpp_modal popb_confirm_action_popup">
  <div class="popb_confirm_container">
    <h2 class="popb_confirm_message">Are you sure you want to do this ? </h2>
    <h4 class="popb_confirm_subMessage">You will lose any unsaved changes.</h4>
    <div class="confirm_btn confirm_btn_green confirm_yes">Yes</div>
    <div class="confirm_btn confirm_btn_grey confirm_no">Cancel</div>
  </div>
</div>

<div class="lpp_modal pb_preview_fields_container" style="">
  <div class="pb_fields_prev" style="overflow: visible;position: absolute;background: #fff;width: 70%;margin-top: 5%;margin-left: 15%;border-radius: 4px;" >
    <span class="dashicons dashicons-no formEntriesPreviewClose" style="float: right; font-size:29px; margin: 5px 10px;cursor: pointer;"></span>
    <br><h2 style="text-align: center; color: #333; font-size:24px;">Form Entries</h2>
    <table class='w3-table w3-striped w3-bordered w3-card-4 formFieldsPreviewTable' style="margin-top: 50px;">
    </table>
  </div>
</div>

<input type="hidden" class="draggedWidgetAttributes" value='' >
<input type="hidden" class="draggedWidgetIndex" value='' >
<input type="hidden" class="widgetDroppedAtIndex" value='' >


<input type="hidden" class="mailchimpListIdHolder" value='' >
<input type="hidden" class="mailchimpApiKeyHolder" value='' >


<input type="hidden" class="globalRowRetrievedPostID" value='' >
<input type="hidden" class="globalRowRetrievedAttributes" value='' >

<input type="hidden" class="insertRowBlockAtIndex" value='' >


<input type="hidden" class="allTextEditableWidgetIds">

<input type="hidden" class="checkIfWidgetsAreLoadedInColumn">

<input type="hidden" class="isChagesMade" value="false">


<input type="hidden" class="currentViewPortSize" value="rbt-l">

<input type="hidden" class="currentResizedRowTarget">
<input type="hidden" class="currentResizedRowColTarget">
<input type="hidden" class="currentResizedRowColTargetNext">
<input type="hidden" class="currentResizedRowHeight">

<input type="hidden" class="isAnimateTrue">
<input type="hidden" class="animateWidgetId">


<input type="hidden" class="widgetDroppedRowId">
<input type="hidden" class="widgetDroppedColIndex">
<input type="hidden" class="widgetDroppedIndex">

<input type="hidden" class="widgetDraggedRowId">
<input type="hidden" class="widgetDraggedIndex">
<input type="hidden" class="widgetDraggedColIndex">

<input type="hidden" class="widgetDeletionCompleted" value="false">

<input type="hidden" class="isDroppedOnDroppable">

<input type="hidden" class="deleteRowIndex">
<input type="hidden" class="widgDeleteColIndex">
<input type="hidden" class="widgDeleteIndex">

<input type="hidden" class="currentlyEditedColId">
<input type="hidden" class="currentlyEditedWidgId">
<input type="hidden" class="currentlyEditedThisCol">
<input type="hidden" class="currentlyEditedThisRow">

<div id="pageStatusHolder" style="display: none;">
</div>

<div style="display: none;" class="rowWithNoColumnContainer">
  <div class="rowWithNoColumn" >
    <h5> SELECT COLUMN STRUCTURE </h5>
    <div class=" setColbtn" data-colNumber="1">
      <img src="<?php echo SMFB_PLUGIN_URL.'/images/icons/1.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="2">
      <img src="<?php echo SMFB_PLUGIN_URL.'/images/icons/2.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="3">
      <img src="<?php echo SMFB_PLUGIN_URL.'/images/icons/3.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="4">
      <img src="<?php echo SMFB_PLUGIN_URL.'/images/icons/4.png' ?>">
    </div>
    
  </div>
</div>

<?php $plugData = get_plugin_data(SMFB_PLUGIN_PATH.'/subcribe-me.php',false,true); ?>
<?php 

$pb_current_user = wp_get_current_user(); 

$plugOps_pageBuilder_data_nonce = wp_create_nonce( 'POPB_data_nonce' );

$mcActive = 'false';  $smfb_extension_pack_active = 'false';
if ( is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' ) || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
  $mcActive = 'true';
}

if ( is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' ) || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
  $smfb_extension_pack_active = 'true';
}


?>
<script type="text/javascript">
  var pageBuilderApp = {};
  pageBuilderApp.currentlyEditedColId = '';
  pageBuilderApp.currentlyEditedWidgId = '';
  pageBuilderApp.currentlyEditedThisCol = '';
  pageBuilderApp.currentlyEditedThisRow = '';
  pageBuilderApp.animateWidgetId = '';

  <?php
    if (!function_exists('smfb_available_pro_widgets') ) {
      echo "pageBuilderApp.premActive = 'false';";
    }else{
      echo "pageBuilderApp.premActive = 'true';";
    }
  ?>
  
  var URLL = "<?php echo admin_url('admin-ajax.php?action=smfb_admin_data&page_id='.$postId.'&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>";
  var PageBuilderAdminImageFolderPath = '<?php echo SMFB_PLUGIN_URL."/images/menu/"; ?>';
  var P_ID = "<?php echo $postId; ?>";
  var P_menu  = "";
  var PageBuilder_Version = "<?php echo $plugData['Version']; ?>";
  var admURL = "<?php echo admin_url(); ?>";
  var siteURLpb = "<?php echo site_url(); ?>";
  var isPub = "<?php echo get_post_status( $postId ); ?>";
  var thisPostType = "<?php echo get_post_type($postId); ?>";
  var pbWrapperWidth = jQuery('#container').width();
  var pluginURL  = '<?php echo SMFB_PLUGIN_URL; ?>';
  var admEMail = '<?php echo  $pb_current_user->user_email; ?>';
  var isMCActive = "<?php echo $mcActive; ?>";
  var isGlobalRowActive = "false";
  var premExtensionActive = "<?php echo $smfb_extension_pack_active; ?>";
  var shortCodeRenderWidgetNO = '<?php echo $plugOps_pageBuilder_data_nonce; ?>';

</script>

<script type="text/javascript">
  jQuery(document).ready(function() {

      if (thisPostType == 'pluginops_forms') {
      }

    jQuery('.pluginops-tabs .pluginops-tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        jQuery('.pluginops-tabs ' + currentAttrValue).show().siblings().hide();
 
        jQuery(this).parent('li').addClass('pluginops-active').siblings().removeClass('pluginops-active');
 
        e.preventDefault();
    });
    jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
 
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });

    jQuery('.TemplateTabs .Templatetab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        jQuery('.TemplateTabs ' + currentAttrValue).show().siblings().hide();
 
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });

    jQuery( function() {
      jQuery( "#PB_accordion_forms, .PB_accordion_forms" ).accordion({
        collapsible: true,
        heightStyle: "content"
      });
  });
    jQuery( function() {
      jQuery( "#PB_accordion, .PB_accordion" ).accordion({
        collapsible: true,
        heightStyle: "content"
      });
  });
    jQuery( function() {
      jQuery( "#accordion1" ).accordion({
        collapsible: true,
        heightStyle: "content"
      });
  });

  jQuery( ".sortableAccordionWidget" )
  .accordion({
    header: '> li > h3',
    collapsible: true,
    heightStyle: "content"
  })
  .sortable({
        axis: "y",
        handle: ".handleHeader",
        stop: function( event, ui ) {
          // so trigger focusout handlers to remove .ui-state-focus
          ui.item.children( ".handleHeader" ).triggerHandler( "focusout" );
 
          // Refresh accordion to handle new order
          jQuery( this ).accordion( "refresh" );
        }
      });

    jQuery('.pbicp-auto').iconpicker({ });

    jQuery('.pbIconListPicker').iconpicker({ });
    
  jQuery(document).on('click','.pb_img_thumbnail',function(){
    var clikedElID = jQuery(this).attr('id');
    jQuery('#pb_lightbox'+clikedElID).css('display','block');
  });

  jQuery(document).on('click','.pb_single_img_lightbox',function(){
    jQuery(this).css('display','none');
  });

});
  
  
  
  /* Save Triggers Removed From Here */
  

if (isGlobalRowActive == "true") {
    jQuery('.addNewGlobalRowVisible').parent().css('display','inline-block');
}

jQuery("input").keypress(function (evt) {
  
  var keycode = evt.charCode || evt.keyCode;
  if (keycode  == 13) { //Enter key's keycode
    return false;
  }
});

jQuery('#menuWrap').click(function(){return false;});
jQuery('#lpb_menu_widget').click(function(){return false;});


jQuery(document).ready(function() {
  jQuery('.gFontSelectorulpb').fontselect().change(function(){

  });
});


String.prototype.PBSearchStrcapitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
  }
  
  jQuery('.pbSearchWidget').on('keyup', function(){
    var WidgetSearchQuery =  jQuery(this).val().PBSearchStrcapitalize();
    jQuery('.POPB_widget').hide();
    
    jQuery('.POPB_widget:contains("'+WidgetSearchQuery+'")').show();

  });

  jQuery('.templatesFilterSelector').on('change', function(){
    var WidgetSearchQuery =  jQuery(this).val();
    jQuery('.template-card').hide();
    
    jQuery('.template-card:contains("'+WidgetSearchQuery+'")').show();

    if (WidgetSearchQuery == 'All') {
      jQuery('.template-card').show();
    }

  });

</script> 


<script type="text/javascript">

  jQuery('.insertTemplateFormSubmit').on('click', function(e)  {

    var confirmIt =  confirm('Are you sure ? It will insert the temlate below your existing content.');
    if (confirmIt == true) {

        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=smfb_insert_template'); ?>&insertTemplateNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('.insertTemplateForm');

        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
                resonse = JSON.parse(result);
                if (resonse['Message'] == 'Success'){
                  jQuery.each(resonse['Rows'], function(index,val){
                    val['rowID'] = 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100);
                    collectionSize = pageBuilderApp.rowList.length;
                    pageBuilderApp.rowList.add(val, {at: collectionSize+1} );
                  });
                  alert('Selected Template Added Successfully.');
                }else{
                  jQuery('.upt_response').html('There is some bug which is preventing this page to be updated, Contact the <a href="https://wordpress.org/support/plugin/mailchimp-subscribe-sm" target="_blank" > Bug Killers </a>');
                }
            }
        });
         
        
    }
        return false;
    });



  (function($){
    $(document).ready(function() {
    $('.empty_button_form').on('submit',function(){
         
         
        $('#response').text('Processing'); 
         
        var form = $(this);
        $.ajax({
            url: form.attr('action')+'&subsListEmpty='+shortCodeRenderWidgetNO,
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
                $('.download_file_link').css('display','none');
                if (result == 'Success'){
                    $('#response').text(result);  
                }else {
                    $('#response').text(result);
                }
            }
        });
         
        
        return false;   
    });

    // Ajax Requests For form builder.
    jQuery('.emptyFormDataBtn').on('click', function(e)  {

      var confirmIt =  confirm('Are you sure ? It will delete all your form data for eternity.');
      if (confirmIt == true) {

        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=smfb_empty_form_builder_data'); ?>&submitNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('#formBuilderDataListEmpty');

        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
                if (result == 'Success'){

                  $('.emptyFormDataBtn').hide();
                  $('#formBuilderDataListEmpty p ').text('All data has been dumped successfully.');
                }else{
                  $('#formBuilderDataListEmpty p ').text('Already empty.');
                }
            }
        });
         
      }
        return false;
    });

    jQuery('.entryDeleteBtn').on('click', function(e)  {

      var entryIndex = $(this).attr('data-entryIndex');
      var confirmIt =  confirm('Are you sure ? It will delete this data entry for eternity.');
      if (confirmIt == true) {

        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=smfb_delete_form_builder_entry'); ?>&postID="+P_ID+"&dataEntryIndex="+entryIndex+"&submitNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('#formBuilderDataListEmpty');
        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
                if (result == 'success'){
                  $('.edb-'+entryIndex).parent().parent().hide();
                  console.log($('.edb-'+entryIndex));
                }else{
                  $('#formBuilderDataListEmpty p ').text('Already empty.');
                }
            }
        });    
      }
        return false;
    });


    jQuery('#resetAnalyticsBtn').on('click', function(e)  {

      var confirmIt =  confirm('Are you sure ? It will delete this data for eternity.');
      if (confirmIt == true) {

        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=smfb_delete_optin_analytics'); ?>&postID="+P_ID+"&actionConfirmed="+confirmIt+"&submitNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('#formBuilderDataListEmpty');
        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
                if (result == 'success'){
                  $('#resetAnalyticsBtn').text('Analytics reset completed.');
                }else{
                  $('#resetAnalyticsBtn').text('Some error occurred!');
                }
            }
        });
         
       
      }
        return false;
    });

    jQuery('.analyticsDateRange').on('change', function(e)  {

      var confirmIt =  true;
      if (confirmIt == true) {

        var dateRange = jQuery('.analyticsDateRange').val();
        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=smfb_get_new_analytics'); ?>&postID="+P_ID+"&actionConfirmed="+confirmIt+"&dateRange="+dateRange+"&submitNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('#formBuilderDataListEmpty');
        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
              result = JSON.parse(result);
                if (result['message'] == 'success'){
                  $('#mainAnalyticsContainer').html(' ');
                  $('#mainAnalyticsContainer').html(result['analytics']);
                }else{
                  $('#resetAnalyticsBtn').text('Some error occurred!');
                }
            }
        });
         
       
      }
        return false;
    });

    jQuery(document).ready(function(){  jQuery('.analyticsDateRange').trigger('change');  });


    $('.popb_checkbox').checkboxradio({
      icon: false
    });

  $('#aweberConnectButton').click(function(){
        $('.aweberLoader').text('Connecting...');
        $('.aweberLoader').show();
        var form = $('#aweberConnectForm');
        $.ajax({
            url: "<?php echo admin_url('admin-ajax.php?action=smfb_aweber_connect&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>",
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
                var parsedResult= JSON.parse(result);
                if (parsedResult['queryMessage'] == 'success' ) {
                  $('.aweberLoader').hide();
                  $('.aweberConnectionSetupOne').hide('slow');
                  $('.aweberConnectionSetupTwo').show('slow');
                  $('#formBuilderAweberList').html(parsedResult['allLists']);
                }else{
                  $('.aweberLoader').text('Connection unsuccesful, Please try getting your authorization code again.');
                }
                
            }
        });
  });

  $('#mcGetGrpsBtn').click(function(){
        $('.mcGroupsLoader').text('Connecting...');
        $('.mcGroupsLoader').show();
        var form = $('#aweberConnectForm');

        apiKey = $('.formBuilderMCApiKey').val();
        listID = $('.formBuilderMCAccountName').val();

        $('.mcgrpsContainer').css('display','none');

        if (apiKey == '' || listID == '') {
          $('.mcGroupsLoader').text("Api key or List ID can't be empty");
        }else{
          $.ajax({
              url: "<?php echo admin_url('admin-ajax.php?action=smfb_getMCGroupIds&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>&apiKey="+apiKey+"&listID="+listID+"",
              method: form.attr('method'),
              data: form.serialize(),
              async:false,
              success: function(result){
                var parsedResult= JSON.parse(result);
                thisMcAllGroups = '';
                if (typeof( parsedResult['success']) != 'undefined' ) {
                  
                  groups = parsedResult['success'];
                  $.each(groups, function(title,arrayVal){
                    thisOptGroup = '<optgroup label="'+title+'">';

                    thisgrpFields = '';
                    $.each(arrayVal, function(index, val){
                      $.each(val,function(grpId, grpName){
                        thisField = '<option value="'+grpName+'"> '+grpId +' </option>';
                        thisgrpFields =  thisgrpFields + thisField;
                      });
                    });

                    thisCompleteOptGroup = thisOptGroup + thisgrpFields + "</optgroup>";

                    thisMcAllGroups = thisMcAllGroups + thisCompleteOptGroup;

                  });

                  $('.formBuilderMCGroups').html( "<option value='false'>None</option>" + thisMcAllGroups);
                  $('.mcgrpsContainer').css('display','block');
                  $('.mcGroupsLoader').text('');

                }else{
                  $('.mcGroupsLoader').text(parsedResult['error']);
                }
                
              }
          });
        }

          
  });

  $(document).ready( function() {
    $.ajax({
            url: "<?php echo admin_url('admin-ajax.php?action=smfb_aweber_connection_check&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>",
            method: 'post',
            data: '',
            success: function(result){
                var parsedResult= JSON.parse(result);
                if (parsedResult['queryMessage'] == 'success' ) {

                  $('.aweberConnectionSetupOne').hide('slow');
                  $('.aweberConnectionSetupTwo').show('slow');
                  $('#formBuilderAweberList').html(parsedResult['allLists']);
                }else{
                  $('.aweberLoader').text(' ');
                  $('.aweberConnectionSetupOne').show('slow');
                  $('.aweberConnectionSetupTwo').hide('slow');
                }
                
            }
    });
  });


  });

  })(jQuery);

</script>


<div id="fontLoaderContainer"></div>
<style type="text/css">
  #PbaceEditorJS, #PbaceEditorCSS,#PbColaceEditorCSS, #PbPOaceEditorCSS, #PbPOaceEditorJS { 
        padding: 20px; margin: 20px;
        width: 80%; min-height: 450px;
    }
</style>


<style type="text/css" id="POPBGlobalStylesTag"></style>

<style type="text/css" id="POPBDeafaultResponsiveStylesTag"></style>

<style type="text/css" id="POPBBodyHoverStylesTag"></style>


<script src="<?php echo SMFB_PLUGIN_URL.'/js/fa.js'; ?>"></script>