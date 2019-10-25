<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

  $this_widget_form_builder = $thisWidget['widgetFormBuilder'];

  $reCaptchaHtml = '';
  if (isset($this_widget_form_builder['widgetPbFbFormMailChimp']['fbgreCaptcha'] )) {
    if ( $this_widget_form_builder['widgetPbFbFormMailChimp']['fbgreCaptcha'] == 'true' ) {
      echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
      $reCaptchaHtml =  "<br><br><div class='g-recaptcha' data-sitekey='".$this_widget_form_builder['widgetPbFbFormMailChimp']['fbgreCSiteKey']."'></div>";
    }
  }

  $widgetPbFbFormFeilds = $this_widget_form_builder['widgetPbFbFormFeilds'];
  $widgetPbFbFormFeildOptions = $this_widget_form_builder['widgetPbFbFormFeildOptions'];
  $widgetPbFbFormSubmitOptions = $this_widget_form_builder['widgetPbFbFormSubmitOptions'];
  $widgetPbFbFormEmailOptions = $this_widget_form_builder['widgetPbFbFormEmailOptions'];

  $formBuilderFieldSize = 'pbField-'.$widgetPbFbFormFeildOptions['formBuilderFieldSize'];

  $formBuilderFieldVGap = '2'; $formBuilderFieldHGap = '2.5';
  if (isset($widgetPbFbFormFeildOptions['formBuilderFieldVGap']) ) {
    
    if ($widgetPbFbFormFeildOptions['formBuilderFieldVGap'] != '') {
      $formBuilderFieldVGap = $widgetPbFbFormFeildOptions['formBuilderFieldVGap'];
    }
    if ($widgetPbFbFormFeildOptions['formBuilderFieldHGap'] != '') {
      $formBuilderFieldHGap = $widgetPbFbFormFeildOptions['formBuilderFieldHGap'];
    }
  }

  $formBuilderBtnVGap = ''; $formBuilderBtnHGap = '2.5';
  if (isset($widgetPbFbFormSubmitOptions['formBuilderBtnVGap']) ) {
    
    if ($widgetPbFbFormSubmitOptions['formBuilderBtnVGap'] != '') {
      $formBuilderBtnVGap = $widgetPbFbFormSubmitOptions['formBuilderBtnVGap'];
    }
    if ($widgetPbFbFormSubmitOptions['formBuilderBtnHGap'] != '') {
      $formBuilderBtnHGap = $widgetPbFbFormSubmitOptions['formBuilderBtnHGap'];
    }
  }

  $pbFormBuilderUniqueId = 'pb_FormBuilder_'.(rand(10,99)*120+200);
  
  $formBuilderbtnIcon = '';$formBuilderbtnIconBefore = '';$formBuilderbtnIconAfter = '';$formBuilderbtnIconAnimation = '';$formBuilderbtnIconHoverAnimationScript = '';
  if (isset($widgetPbFbFormSubmitOptions['formBuilderbtnSelectedIcon'])) {
    $formBuilderbtnSelectedIcon = $widgetPbFbFormSubmitOptions['formBuilderbtnSelectedIcon'];
    $formBuilderbtnIconPosition = $widgetPbFbFormSubmitOptions['formBuilderbtnIconPosition'];
    $formBuilderbtnIconAnimation = $widgetPbFbFormSubmitOptions['formBuilderbtnIconAnimation'];
    $formBuilderbtnIconGap = $widgetPbFbFormSubmitOptions['formBuilderbtnIconGap'];
    if ($formBuilderbtnSelectedIcon != '') {
      if ($formBuilderbtnIconPosition == 'before') {
        $formBuilderbtnIconGap = 'margin-right:'.$formBuilderbtnIconGap.
        'px;';
      } else {
        $formBuilderbtnIconGap = 'margin-left:'.$formBuilderbtnIconGap.
        'px;';
      }
      $formBuilderbtnIcon = '<i style="'.$formBuilderbtnIconGap.'" class="fa '.$formBuilderbtnSelectedIcon.'  btnIcon-'.$pbFormBuilderUniqueId.'"></i>';
      if ($formBuilderbtnIconAnimation != '') {
        $formBuilderbtnIconHoverAnimationScript = " <script>
          jQuery('.form-btn-".$pbFormBuilderUniqueId."').mouseenter(function(){
            jQuery('.btnIcon-".$pbFormBuilderUniqueId."').addClass('animated ".$formBuilderbtnIconAnimation."').one('animationend oAnimationEnd mozAnimationEnd webkitAnimationEnd',function(){ 
                jQuery('.btnIcon-".$pbFormBuilderUniqueId."').removeClass('animated ".$formBuilderbtnIconAnimation."') 
            });
          });
          </script> " ;
      }
    } else {
      $formBuilderbtnIcon = '';
    }
    if ($formBuilderbtnIconPosition == 'before') {
      $formBuilderbtnIconBefore = $formBuilderbtnIcon;
      $formBuilderbtnIconAfter = '';
    } else {
      $formBuilderbtnIconAfter = $formBuilderbtnIcon;
      $formBuilderbtnIconBefore = '';
    }
  }

  if (isset($widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily'])) {
    array_push($thisColFontsToLoad, $widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily']);
  }else{
    $widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily'] = ' ';
  }
  if (isset($widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'])) {
    array_push($thisColFontsToLoad, $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily']);
  }else{
    $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] = ' ';
  }

  $widgetFALoadScripts = true;
  ob_start();

  $widgetPbFbFormFeildsIndex = 0;
  foreach($widgetPbFbFormFeilds as $widgetPbFbFormFeild){

    $thisFieldOptions = $widgetPbFbFormFeild['thisFieldOptions'];
    if (!isset($thisFieldOptions['displayFieldsInline'])) {
      $thisFieldOptions['displayFieldsInline'] = 'block';
    }
    $fbFieldRequired = '';
    if ($thisFieldOptions['fbFieldRequired'] == 'true') {
      $fbFieldRequired = 'required="required"';
    }

    $presetValue = '';
    if (isset($thisFieldOptions['fbFieldPreset']) && !empty($thisFieldOptions['fbFieldPreset']) ) {
      $presetValue = ' value="'.$thisFieldOptions['fbFieldPreset'].'" ';
    }

    if (! isset( $thisFieldOptions['displayFieldsInline'] )) {
      $thisFieldOptions['displayFieldsInline'] = '';
    }

    $thisFieldAttr = 'style="width:99%;  "  placeholder="'.$thisFieldOptions['fbFieldPlaceHolder'].'" '.$fbFieldRequired.' "  id="fieldID-'.$widgetPbFbFormFeildsIndex.'" '.$presetValue.' ' ;
    $multiFieldStyleAttr = 'style="margin-right:25px; display:'.$thisFieldOptions['displayFieldsInline'].'; line-height:1.4em; "';
    $thisFieldName = $thisFieldOptions['fbFieldLabel'];

    if ($thisFieldName == '') {
      $thisFieldName = $widgetPbFbFormFeild['fbFieldType'];
    }

    switch ($widgetPbFbFormFeild['fbFieldType']) {
      case 'textarea':
           $pbThisFormField = '<textarea rows="'.$thisFieldOptions['fbtextareaRows'].'" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'" '.$thisFieldAttr.' class="pbFormField  '.$formBuilderFieldSize.'" ></textarea>';
      break;
      case 'radio':

           $multiOptionFieldValues = explode("\n", $thisFieldOptions['multiOptionFieldValues']);
           $allRadioItems = '';

           for ($pb_widget_form_loopi =0; $pb_widget_form_loopi< count($multiOptionFieldValues); $pb_widget_form_loopi++) {
             $thisRadioLabel = $multiOptionFieldValues[$pb_widget_form_loopi].'</label>';
             $thisRadioItem = '<span '.$multiFieldStyleAttr.' class="pbFormMultiLabel"> <label for="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'"> <input type="radio" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'[]"  id="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'" value="'.$multiOptionFieldValues[$pb_widget_form_loopi].'"  '.$fbFieldRequired.'> ' .$thisRadioLabel. ' </span>';
             
             $prevRadioFields = $allRadioItems;
             $allRadioItems = $prevRadioFields .  $thisRadioItem;
           }
           $pbThisFormField = $allRadioItems;

      break;
      case 'checkbox':
           $multiOptionFieldValues = explode("\n", $thisFieldOptions['multiOptionFieldValues']);
           $allRadioItems = '';

           for ($pb_widget_form_loopi =0; $pb_widget_form_loopi< count($multiOptionFieldValues); $pb_widget_form_loopi++) {
             $thisRadioLabel = $multiOptionFieldValues[$pb_widget_form_loopi].'</label>';
             $thisRadioItem = '<span '.$multiFieldStyleAttr.' class="pbFormMultiLabel"> <label for="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'"> <input type="checkbox" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'[]"  id="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'" value="'.$multiOptionFieldValues[$pb_widget_form_loopi].'"  '.$fbFieldRequired.'> ' .$thisRadioLabel. ' </span>';
             
             $prevRadioFields = $allRadioItems;
             $allRadioItems = $prevRadioFields .  $thisRadioItem;
           }
           $pbThisFormField = $allRadioItems;
      break;
      case 'select':
           $multiOptionFieldValues = explode("\n", $thisFieldOptions['multiOptionFieldValues']);
           $allRadioItems = '';

           for ($pb_widget_form_loopi =0; $pb_widget_form_loopi< count($multiOptionFieldValues); $pb_widget_form_loopi++) {

             $thisRadioItem = '<option  value="'.$multiOptionFieldValues[$pb_widget_form_loopi].'" > '.$multiOptionFieldValues[$pb_widget_form_loopi].' </option> ';
             
             $prevRadioFields = $allRadioItems;
             $allRadioItems = $prevRadioFields .  $thisRadioItem;
           }


           $pbThisFormField = '<select name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'"  id="fieldID-'.$widgetPbFbFormFeildsIndex.'"  '.$thisFieldAttr.' class="pbFormField  '.$formBuilderFieldSize.'"  '.$fbFieldRequired.' >'. $allRadioItems .'</select>';
      break;
      default: 
           $pbThisFormField = '<input type="'.$widgetPbFbFormFeild['fbFieldType'].'" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'"  '.$thisFieldAttr.' class="pbFormField  '.$formBuilderFieldSize.'" '.$fbFieldRequired.' >';
      break;
    } //switch end

      $hiddenField = '';
      if ($widgetPbFbFormFeild['fbFieldType'] == 'hidden') {
        $hiddenField = 'display:none !important;';
      }

      $pbThisFormFieldLabel = '<label for="fieldID-'.$widgetPbFbFormFeildsIndex.'" class="pbFormLabel"> '.$thisFieldOptions['fbFieldLabel'].' </label>';
      $pbThisFormFieldWrapped =  '<div class="pluginops_form_inp_wrapper" style=" '.$hiddenField.' width:'.( ($thisFieldOptions['fbFieldWidth']-(int)$formBuilderFieldHGap  ) - 0.25 ).'%; margin-right:'.$formBuilderFieldHGap.'%; margin-top:'.$formBuilderFieldVGap.'%; display:inline-block;">' . $pbThisFormFieldLabel.'  '.$pbThisFormField .'</div>';

      echo $pbThisFormFieldWrapped;

      $widgetPbFbFormFeildsIndex++;
  }; //each loop end

  $pbFormAllFields = ob_get_contents();
  ob_end_clean();

  $buttonMargin = '2% 2.5% 2% 0';
  if ($widgetPbFbFormSubmitOptions['formBuilderBtnAlignment'] == 'center') {
    $calcMargin = 50 - ($widgetPbFbFormSubmitOptions['formBuilderBtnWidth']/2);
    $buttonMargin = '2% 2.5% 2% '.$calcMargin.'%';
  } else if($widgetPbFbFormSubmitOptions['formBuilderBtnAlignment'] == 'right') {
    $calcMargin = 100 - ($widgetPbFbFormSubmitOptions['formBuilderBtnWidth']);
    $buttonMargin = '2% 2.5% 2% '.$calcMargin.'%';
  }

  $formBuilderbtnIconLoading = "<i class='fa fa-spinner fa-spin formIconLoader' style='display:none;' ></i>";
  $formBuilderbtnIconSuccess = "<i class='fa fa-check-circle formIconSuccess' style='display:none;' ></i>";

  $pbFormBuilderSubmitStyles = ' style=" width:100%; background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBgColor'].'; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnColor'].'; font-size:'.$widgetPbFbFormSubmitOptions['formBuilderBtnFontSize'].'px;  border:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderWidth'].'px solid '.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderColor'].'; border-radius:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderRadius'].'px; font-family:'.str_replace('+', ' ', $widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily'] ).', sans-serif; " ';

  $pbFormBuilderSubmit = '<div class="pluginops_form_inp_wrapper" style="text-align:'.$widgetPbFbFormSubmitOptions['formBuilderBtnAlignment'].'; width:'.(($widgetPbFbFormSubmitOptions['formBuilderBtnWidth']- (int)$formBuilderBtnHGap ) - 0.25 ).'%;  margin:'.$buttonMargin.';  margin-right:'.$formBuilderBtnHGap.'%; margin-top:'.$formBuilderBtnVGap.'%; display:inline-block;">  <button type="submit" '.$pbFormBuilderSubmitStyles.' class="pbField-'.$widgetPbFbFormSubmitOptions['formBuilderBtnSize'].' form-btn-'.$pbFormBuilderUniqueId.' ">'.$formBuilderbtnIconBefore.' '.$widgetPbFbFormSubmitOptions['formBuilderBtnText'].' '.$formBuilderbtnIconAfter.' </button> </div>';


  $pbFormBuilderExtraFields = " <input type='hidden' name='psID' value='$current_pageID'>       
                                <input type='hidden' name='pbFormTargetInfo' value='$rowCount \n  $Columni \n $j '>
                                <input type='text' id='yourMessageHP' name='messageFBHP'> 
                                $reCaptchaHtml
                                "; 

  $pbFormBuilderWrapper = '<form id="'.$pbFormBuilderUniqueId.'" action="'.admin_url('admin-ajax.php?action=smfb_formBuilderEmail_ajax').'"  method="post"  > '.$pbFormAllFields.'  '.$pbFormBuilderExtraFields.'  '.wp_nonce_field('POPB_send_form_data','POPB_Form_Nonce'). ' '. $pbFormBuilderSubmit.' </form>';

  $pbFormBuilderStylesID = '#'.$pbFormBuilderUniqueId;

  $pbThisFormBuilderStyles = '<style>  '.$pbFormBuilderStylesID.' .pbFormField {   background:'.$widgetPbFbFormFeildOptions['formBuilderFieldBgColor'].';  color:'.$widgetPbFbFormFeildOptions['formBuilderFieldColor'].'; border:'.$widgetPbFbFormFeildOptions['formBuilderFieldBorderWidth'].'px solid '.$widgetPbFbFormFeildOptions['formBuilderFieldBorderColor'].'; border-radius:'.$widgetPbFbFormFeildOptions['formBuilderFieldBorderRadius'].'px; font-family:'.str_replace('+', ' ', $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] ).', sans-serif;  }  '.$pbFormBuilderStylesID.' .pbFormField::placeholder {color:'.$widgetPbFbFormFeildOptions['formBuilderFieldColor'].';} 

    '.$pbFormBuilderStylesID.' .pbFormLabel{ font-size:'.$widgetPbFbFormFeildOptions['formBuilderLabelSize'].'px; color:'.$widgetPbFbFormFeildOptions['formBuilderLabelColor'].'; display:'.$widgetPbFbFormFeildOptions['formBuilderFieldLabelDisplay'].'; line-height:3em; }  '.$pbFormBuilderStylesID.' button:hover { background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverBgColor'].' !important; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverTextColor'].' !important; transition:all .5s; }

    '.$pbFormBuilderStylesID.' .pbFormMultiLabel label { font-size:'.$widgetPbFbFormFeildOptions['formBuilderLabelSize'].'px; color:'.$widgetPbFbFormFeildOptions['formBuilderLabelColor'].';   font-weight:200; }  '.$pbFormBuilderStylesID.' button:hover { background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverBgColor'].' !important; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverTextColor'].' !important; transition:all .5s; }
    </style>';

  if ($widgetSubscribeFormWidget !== true) {
    echo "<script src='".SMFB_PLUGIN_URL."/js/cookie.js'></script>";
    $widgetSubscribeFormWidget = true;
  }

  $formSuccessAction = ''; $formSuccessActionURL = '';
  if (isset($widgetPbFbFormEmailOptions['formSuccessAction']) ) {
    $formSuccessAction = $widgetPbFbFormEmailOptions['formSuccessAction'];
    $formSuccessActionURL = $widgetPbFbFormEmailOptions['formSuccessActionURL'];
  }

  ob_start();

  if ( !isset($widgetPbFbFormEmailOptions['formFailureMessage'] )) {
    $widgetPbFbFormEmailOptions['formFailureMessage'] = 'Some Error Occured while sending the request!';
  }else{
    if ($widgetPbFbFormEmailOptions['formFailureMessage'] == '') {
      $widgetPbFbFormEmailOptions['formFailureMessage'] = 'Some Error Occured while sending the request!';
    }
  }

  if (!isset($widgetPbFbFormEmailOptions['formSuccessCustomAction'] )) {
    $widgetPbFbFormEmailOptions['formSuccessCustomAction'] = '';
  }

  if (!isset($widgetPbFbFormEmailOptions['onSuccessOptin'] )) {
    $widgetPbFbFormEmailOptions['onSuccessOptin'] = '';
  }

  if (!isset($widgetPbFbFormEmailOptions['formDuplicateMessage'] )) {
    $widgetPbFbFormEmailOptions['formDuplicateMessage'] = 'Information Already Exisits!';
  }

  if (!isset($widgetPbFbFormEmailOptions['formDuplicateCustomAction'] )) {
    $widgetPbFbFormEmailOptions['formDuplicateCustomAction'] = '';
  }

  if (!isset($widgetPbFbFormEmailOptions['formFailureCustomAction'] )) {
    $widgetPbFbFormEmailOptions['formFailureCustomAction'] = '';
  }

  if (!isset($this_widget_form_builder['widgetPbFbFormMailChimp']['wfb_cWebHookSuccResponse'] )) {
    $this_widget_form_builder['widgetPbFbFormMailChimp']['wfb_cWebHookSuccResponse'] = 'success';
  }


  $widgetPbFbFormEmailOptions['formSuccessMessage'] = str_replace("'", "\'", $widgetPbFbFormEmailOptions['formSuccessMessage']);

  $widgetPbFbFormEmailOptions['formSuccessMessage'] = str_replace('"', '\"', $widgetPbFbFormEmailOptions['formSuccessMessage']);

  $widgetPbFbFormEmailOptions['formFailureMessage'] = str_replace("'", "\'", $widgetPbFbFormEmailOptions['formFailureMessage']);

  $widgetPbFbFormEmailOptions['formFailureMessage'] = str_replace('"', '\"', $widgetPbFbFormEmailOptions['formFailureMessage']);


  ?>
  <script type="text/javascript">
    (function($){
      $(document).ready(function() {
        $('#yourMessageHP').hide();
      $('#'+'<?php echo $pbFormBuilderUniqueId; ?>').on('submit', function()  {
        var successMessage = "<?php echo $widgetPbFbFormEmailOptions['formSuccessMessage']; ?>";
        var errorMessage = "<?php echo $widgetPbFbFormEmailOptions['formFailureMessage']; ?>";
        var successAction = "<?php echo $formSuccessAction; ?>";
        var successActionUrl = "<?php echo $formSuccessActionURL; ?>";

      var buttonText = $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html();

      $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html('');
      $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').append("<i class='fa fa-spinner fa-spin formIconLoader'></i>");

        var form = $(this);
        form.siblings('.pb_duplicate').hide();
        form.siblings('.pb_error').hide();
        form.siblings('.pb_success').hide();
        var result = " ";
          $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
              var result = JSON.parse(result);
              var emailResult = result['email'];
              var mcResult = result['mailchimp'];
              var grResult = result['getResponse'];
              var cmResult = result['campaignMonitor'];
              var acResult = result['activeCampaign'];
              var dripResult = result['drip'];
              var aweberResult = result['aweber'];
              var convertkitResult = result['convertkit'];
              var webHookResult = result['WebHook'];
              var gRecaptcha = result['gRecaptcha'];
              var cookieConversionTime = <?php echo $cookieConversionTime; ?>;
              var subAEStr = 'Subscriber Already Exists';
              var webHookResponse = '<?php echo $this_widget_form_builder['widgetPbFbFormMailChimp']['wfb_cWebHookSuccResponse']; ?>';

                if (emailResult == 'success' || mcResult == 'success' || grResult == 'success' || cmResult == 'success' || acResult == 'success' || dripResult == 'success' || aweberResult == 'success' || convertkitResult == 'success' || webHookResult == webHookResponse ) {
                  
                  form.siblings('.pb_success').children('p').html(successMessage);
                  form.siblings('.pb_success').show();
                  $.cookie("pluginops_user_subscribed_form<?php echo $current_pageID; ?>", 'yes', {path: '/', expires : cookieConversionTime });
                  $('#popb_popup_close_<?php echo($this_PopUpClose_unique_Id); ?>').trigger('click');
                  setTimeout(function(){
                    $('#POPB-modal-overlay_flyIn_popup_<?php echo $current_pageID; ?>').fadeOut();
                    $('#POPB-modal-overlay_popup_<?php echo($current_pageID); ?>').fadeOut();
                  } , 2000);
                  <?php echo $widgetPbFbFormEmailOptions['formSuccessCustomAction']; ?>

                  <?php if ($widgetPbFbFormEmailOptions['onSuccessOptin'] != '') {
                    echo "$('#POPB-modal-overlay_popup_".$widgetPbFbFormEmailOptions['onSuccessOptin']."').css('display','block');";
                    echo "$('#POPB-modal-overlay_popup_".$current_pageID."').css('display','none');";
                  } ?>
                  if (successAction == 'redirect') {
                    location.href = successActionUrl;
                  }
                } else if(emailResult == subAEStr || mcResult == subAEStr || grResult == subAEStr || cmResult == subAEStr || acResult == subAEStr || dripResult == subAEStr || aweberResult == subAEStr || convertkitResult == subAEStr){
                  form.siblings('.pb_duplicate').children('p').html("<?php echo $widgetPbFbFormEmailOptions['formDuplicateMessage']; ?>");
                  form.siblings('.pb_duplicate').show();

                  <?php echo $widgetPbFbFormEmailOptions['formDuplicateCustomAction']; ?>

                } else{
                   form.siblings('.pb_error').children('p').html(errorMessage);
                   form.siblings('.pb_error').show();

                   <?php echo $widgetPbFbFormEmailOptions['formFailureCustomAction']; ?>
                }

                if (gRecaptcha == 'Verification Failed') {
                  form.siblings('.pb_error').children('p').html('There was some problem in verification please reload the page and try again. ');
                  var greError =   result['gRecaptchaError'];
                  if (greError.includes('invalid-input-secret') ) {
                    form.siblings('.pb_error').children('p').html('Invalid ReCaptcha Sceret Key');
                  }
                  form.siblings('.pb_error').show();
                  <?php echo $widgetPbFbFormEmailOptions['formFailureCustomAction']; ?>
                }
                if (gRecaptcha == 'Captcha Not Set') {
                  form.siblings('.pb_error').children('p').html('Please click on I\'m not a robot checkbox.');
                  form.siblings('.pb_error').show();
                  <?php echo $widgetPbFbFormEmailOptions['formFailureCustomAction']; ?>
                }

                $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html(buttonText);
                console.log('Database Result  : ' + result['database']);
                console.log('MailChimp Result  : ' + result['mailchimp']);
                console.log('GetResponse Result  : ' + result['getResponse']);
                console.log('Campaign Monitor Result  : ' + result['campaignMonitor']);
                console.log('Active Campaign Result  : ' + result['activeCampaign']);
                console.log('Drip Result  : ' + result['drip']);
                console.log('Aweber Result  : ' + result['aweber']);
                console.log('ConvertKit Result  : ' + result['convertkit']);
            },
            error: function(xhr, ajaxOptions, thrownError){
              form.siblings('.pb_error').children('p').html(thrownError);
              form.siblings('.pb_error').show();
              $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html(buttonText);
            }
          });
                         
        // Prevents default submission of the form after clicking on the submit button. 
        return false;   
      });

    });

  })(jQuery);

  </script>
  <?php 

  echo $formBuilderbtnIconHoverAnimationScript;

  $thisFormScript = ob_get_contents();
  ob_end_clean();



  if (isset($this_widget_form_builder['formCustomHTML']) ) {
    if ($this_widget_form_builder['formCustomHTML'] != '') {
      $pbFormBuilderWrapper = '<div id="'.$pbFormBuilderUniqueId.'"  > '.$this_widget_form_builder['formCustomHTML'].' </div>';

      $pbThisFormBuilderStyles = '<style>  '.$pbFormBuilderStylesID.' input { margin-top:'.$formBuilderFieldVGap.'%;   background:'.$widgetPbFbFormFeildOptions['formBuilderFieldBgColor'].';  color:'.$widgetPbFbFormFeildOptions['formBuilderFieldColor'].'; border:'.$widgetPbFbFormFeildOptions['formBuilderFieldBorderWidth'].'px solid '.$widgetPbFbFormFeildOptions['formBuilderFieldBorderColor'].'; border-radius:'.$widgetPbFbFormFeildOptions['formBuilderFieldBorderRadius'].'px; font-family:'.str_replace('+', ' ', $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] ).', sans-serif;  }  '.$pbFormBuilderStylesID.' input::placeholder {color:'.$widgetPbFbFormFeildOptions['formBuilderFieldColor'].';} 

      '.$pbFormBuilderStylesID.' label{ font-size:'.$widgetPbFbFormFeildOptions['formBuilderLabelSize'].'px; color:'.$widgetPbFbFormFeildOptions['formBuilderLabelColor'].'; display:'.$widgetPbFbFormFeildOptions['formBuilderFieldLabelDisplay'].'; line-height:3em; }  '.$pbFormBuilderStylesID.' button:hover { background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverBgColor'].' !important; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverTextColor'].' !important; transition:all .5s; }

      '.$pbFormBuilderStylesID.' button , '.$pbFormBuilderStylesID.' input[type=submit] { margin-top:'.$formBuilderBtnVGap.'%; width:100%; background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBgColor'].'; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnColor'].'; font-size:'.$widgetPbFbFormSubmitOptions['formBuilderBtnFontSize'].'px;  border:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderWidth'].'px solid '.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderColor'].'; border-radius:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderRadius'].'px; font-family:'.str_replace('+', ' ', $widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily'] ).', sans-serif; " }
      </style>';

    }
  }

  $pb_On_complete_messages = '<div class="w3-panel w3-green pb_success" style="display:none;"><p></p></div>
  <div class="w3-panel w3-red pb_error" style="display:none;"><p></p></div>  <div class="w3-panel w3-orange pb_duplicate" style="display:none;"><p></p></div>
';

  if (isset($widgetPbFbFormFeildOptions['formBuilderLabelSizeTablet'])) {
    $thisWidgetResponsiveWidgetStylesTablet = "
      #$pbFormBuilderUniqueId label {
        font-size: ".$widgetPbFbFormFeildOptions['formBuilderLabelSizeTablet']."px !important;
      }
      #$pbFormBuilderUniqueId button , #$pbFormBuilderUniqueId input[type=submit] {
        font-size: ".$widgetPbFbFormSubmitOptions['formBuilderBtnFontSizeTablet']."px !important;
      }
    ";
    $thisWidgetResponsiveWidgetStylesMobile = "
      #$pbFormBuilderUniqueId label {
        font-size: ".$widgetPbFbFormFeildOptions['formBuilderLabelSizeMobile']."px !important;
      }
      #$pbFormBuilderUniqueId button , #$pbFormBuilderUniqueId input[type=submit]{
        font-size: ".$widgetPbFbFormSubmitOptions['formBuilderBtnFontSizeMobile']."px !important;
      }
    ";

    array_push($POPBNallRowStylesResponsiveTablet, $thisWidgetResponsiveWidgetStylesTablet);
    array_push($POPBNallRowStylesResponsiveMobile, $thisWidgetResponsiveWidgetStylesMobile);
  }


                



$widgetContent = $pbFormBuilderWrapper . " $pb_On_complete_messages  ". $pbThisFormBuilderStyles . '  '.$thisFormScript;


?>