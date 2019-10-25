<?php if ( ! defined( 'ABSPATH' ) ) exit;

if ( isset($_GET['popb_track_url']) && isset($_GET['popb_pID']) ) {
  $popb_track_url = sanitize_text_field( $_GET['popb_track_url'] );
  $post_id = sanitize_text_field( $_GET['popb_pID'] );

  if (!empty($popb_track_url) && !empty($post_id)) {
    $postStatus = get_post_status( $post_id );
    if ($postStatus == 'publish') {

      $allowed = array();
      $pluginOpsUserTimeZone = get_option('timezone_string');
      date_default_timezone_set($pluginOpsUserTimeZone);
      $todaysDate = date('d-m-Y');

      $ctnTotal = get_post_meta($post_id,'ctnTotal',true);
      $ctnTotal++;
      $updateResultConversionCount = update_post_meta( $post_id, 'ctnTotal', $ctnTotal, $unique = false);
      $ctrLinks = get_post_meta($post_id,'ctrTpLinks',true);

      if (! is_array($ctrLinks)) {
        $ctrLinks = array();
      }

      if (!isset($ctrLinks[$popb_track_url])) {
        $ctrLinks[$popb_track_url] = array();
      }

      if (!isset( $ctrLinks[$popb_track_url][$todaysDate] )) {
        $ctrLinks[$popb_track_url][$todaysDate] = 0;
      }

      $ctrLinks[$popb_track_url][$todaysDate]++;
      update_post_meta( $post_id, 'ctrTpLinks', $ctrLinks, $unique = false);

    }
    
    if ( headers_sent() ) {
      echo "<script> location.href = '$popb_track_url' </script>";
    }else{
      header('Location: ' . $popb_track_url, true, 302);
    }
    
    exit();
  }
}


if (isset($isShortCodeTemplate)) {
  if ($isShortCodeTemplate == true) {
    $current_pageID = $template_id;
  }
} else{ 
  $isShortCodeTemplate = '';
  $current_pageID = $post->ID;
}

$data = get_post_meta( $current_pageID, 'ULPB_DATA', true );

if ($data == '') {
}else{


if (isset($data['pageOptions']['MultiVariantTesting']) && $data['pageOptions']['MultiVariantTesting'] != null ) {

  $VariantB_ID = $data['pageOptions']['MultiVariantTesting']['VariantB'];
  $VariantC_ID = $data['pageOptions']['MultiVariantTesting']['VariantC'];
  $VariantD_ID = $data['pageOptions']['MultiVariantTesting']['VariantD'];
  
  if ( ($VariantB_ID != 'none' && $VariantB_ID != null && $VariantB_ID != '') || ($VariantC_ID != 'none' && $VariantC_ID != null && $VariantC_ID != '') || ($VariantD_ID != 'none' && $VariantD_ID != null && $VariantD_ID != '') ) {
    include 'ab-lib/phpab.php';
    $startAbTest = new phpab('AbTestOne');

    if ($VariantB_ID != 'none' && $VariantB_ID != null && $VariantB_ID != '') {
      $startAbTest->add_variation('variantb');
    }
    if ($VariantC_ID != 'none' && $VariantC_ID != null && $VariantC_ID != '') {
      $startAbTest->add_variation('variantc');
    }
    if ($VariantD_ID != 'none' && $VariantD_ID != null && $VariantD_ID != '') {
      $startAbTest->add_variation('variantd');
    }
   // var_dump($startAbTest->get_user_segment());

    if ($startAbTest->get_user_segment() == 'variantb') {
      $data = get_post_meta( $VariantB_ID, 'ULPB_DATA', true );
      $current_pageID = $VariantB_ID;
    }else if ($startAbTest->get_user_segment() == 'variantc') {
      $data = get_post_meta( $VariantC_ID, 'ULPB_DATA', true );
      $current_pageID = $VariantC_ID;
    }else if ($startAbTest->get_user_segment() == 'variantd') {
      $data = get_post_meta( $VariantD_ID, 'ULPB_DATA', true );
      $current_pageID = $VariantD_ID;
    }else{
      $data = get_post_meta( $current_pageID, 'ULPB_DATA', true );
    }
  }
}

$selecteOptinType = $data['optinType'];
$campaignPlacement = $data['campaignPlacement'];

$current_postType = get_post_type( $current_pageID );


$tablet_browser = 0;
$mobile_browser = 0;
 
if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $tablet_browser++;
}
 
if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $mobile_browser++;
}
 
if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
    $mobile_browser++;
}
 
$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
$mobile_agents = array(
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
    'newt','noki','palm','pana','pant','phil','play','port','prox',
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
    'wapr','webc','winw','winw','xda ','xda-');
 
if (in_array($mobile_ua,$mobile_agents)) {
    $mobile_browser++;
}
 
if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
    $mobile_browser++;
    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
      $tablet_browser++;
    }
}
 
if ($tablet_browser > 0) {
   $ulpb_currentDevice = 'tablet';
}
else if ($mobile_browser > 0) {
   $ulpb_currentDevice = 'phone';
}
else {
   $ulpb_currentDevice = 'desktop';
}

  $widgetAnimationTriggerScript = '';
  if (!isset($widgetCounterLoadScripts)) {
    $widgetCounterLoadScripts = false;
    $widgetCountDownLoadScripts = false;
    $widgetSliderLoadScripts = false;
    $widgetFALoadScripts = false;
    $widgetVideoLoadScripts = false;
    $widgetOwlLoadScripts = false;
    $widgetWooCommLoadScripts = false;
    $widgetPostsSliderExternalScripts = false;
    $widgetSubscribeFormWidget = false;
    $shapesinluded = false;

    $SMFB_visitsCounted = false;
  }
  

  $POPBNallRowStyles = array();
  $POPBNallRowStylesResponsiveTablet = array();
  $POPBNallRowStylesResponsiveMobile = array();
  $POPBallColumnStylesArray = array();
  $POPBallWidgetsStylesArray = array();
  $thisColFontsToLoad = array('Allerta');
  $OptinCurrent_pageID = $current_pageID;
  $popupClosedVarUinqueName = "popupClosed_$current_pageID";

$hideOnDesktop = false; $hideOnTablet = false; $hideOnMobile = false;
if ( is_array($campaignPlacement['hideCampaignOn']) || is_object($campaignPlacement['hideCampaignOn']) ) {
  $hideCampaignOn = $campaignPlacement['hideCampaignOn'];
  foreach ($hideCampaignOn as $value) {
    if ($value['value'] == 'desktop') {
      $hideOnDesktop = true;
    }
    if ($value['value'] == 'tablet') {
      $hideOnTablet = true;
    }
    if ($value['value'] == 'phone') {
      $hideOnMobile = true;
    }
  }
}
  

$ulpb_blockedDevice = false;
if ($hideOnDesktop == true && $ulpb_currentDevice == 'desktop') {
  $ulpb_blockedDevice = true;
}
if ($hideOnTablet == true && $ulpb_currentDevice == 'tablet') {
  $ulpb_blockedDevice = true;
}
if ($hideOnMobile == true && $ulpb_currentDevice == 'phone') {
  $ulpb_blockedDevice = true;
}

if ($ulpb_blockedDevice == true) {
  $data = '';
}

if (!empty($data)) {

if (!function_exists('pluginops_forms_load_jquery')) {
  function pluginops_forms_load_jquery() {
    if ( !wp_script_is( 'jquery', 'enqueued' )) {
        echo '<script type="text/javascript" src="'.SMFB_PLUGIN_URL.'/js/jquery.min.js"></script>';
    }
  }
}
pluginops_forms_load_jquery();

$lp_thisPostType = get_post_type( $current_pageID );
if ($lp_thisPostType == 'pluginops_forms') {
  if ($SMFB_visitsCounted != true) {
    include 'counter.php';
  }
}


if (isset($hideOnDesktop)) {
  if ($hideOnDesktop == true || $hideOnTablet == true || $hideOnMobile == true) {
    echo "<div id='plugionops_form_uni_device_wrapper_$current_pageID'> <style>";
    
    
    if ($hideOnDesktop == true) {
      echo "@media only screen and (min-device-width : 1085px) and (max-device-width : 3924px) {
          #plugionops_form_uni_device_wrapper_".$current_pageID." {
            display: none;
          }
        }";
    }
    if ($hideOnTablet == true) {
      echo '@media only screen and (min-device-width : 768px) and (max-device-width : 1085px) {
          #plugionops_form_uni_device_wrapper_'.$current_pageID.' {
            display: none;
          }
        }';
    }
    if ($hideOnMobile == true) {
      echo '@media only screen and (min-device-width : 320px) and (max-device-width : 480px) {
          #plugionops_form_uni_device_wrapper_'.$current_pageID.' {
            display: none;
          }
        }
        </style>
        ';
    }
  }
}

include('header.php');


$popUpEntranceAnimation = '';
$popUpExitAnimation = 'SlideOutUp';
if (isset($campaignPlacement['popUpEntranceAnimation'])) {
  $popUpEntranceAnimation = $campaignPlacement['popUpEntranceAnimation'];
  $popUpExitAnimation = $campaignPlacement['popUpExitAnimation'];
}


if (isset($campaignPlacement['hideAfterCampaignClosed']) && !empty($campaignPlacement['hideAfterCampaignClosed']) ) {
  $hideAfterCampaignClosed = $campaignPlacement['hideAfterCampaignClosed'];
}else{
  $hideAfterCampaignClosed = 'true';
}

if (isset($campaignPlacement['cookieConversionTime']) ) {
  $cookieConversionTime = $campaignPlacement['cookieConversionTime'];
  if ($cookieConversionTime == '') {
    $cookieConversionTime = '0';
  }
}else{
  $cookieConversionTime = '30';
}

if (isset($campaignPlacement['cookieCloseTime']) ) {
  $cookieCloseTime = $campaignPlacement['cookieCloseTime'];
  if ($cookieCloseTime == '') {
    $cookieCloseTime = '0';
  }
}else{
  $cookieCloseTime = '1';
}

if ( !isset($data['pageOptions']['hideCloseBtn']) ) {
  $data['pageOptions']['hideCloseBtn'] = 'block';
}
if ( !isset($data['pageOptions']['closeOnOverlay']) ) {
  $data['pageOptions']['closeOnOverlay'] = 'false';
}
if ( !isset($data['pageOptions']['overlayColor']) ) {
  $data['pageOptions']['overlayColor'] = 'rgba(0,0,0,0.6)';
}

if ($popUpExitAnimation == '' || empty($popUpExitAnimation)) {
  $popUpExitAnimation = 'slideOutUp';
}

include 'popup.php';
include 'flyin.php';
include 'bar.php';
include 'fullpage.php';




if (isset($isPopUpFlyInTemplate) || isset($isPopUpTemplate) || isset($isPopUpBarTemplate) || isset($isPopUpFullPageTemplate) || $selecteOptinType == 'PopUp' || $selecteOptinType == 'Full Page' || $selecteOptinType == 'Fly In' || $selecteOptinType == 'Bar') {

  $this_PopUpClose_unique_Id = 'popup_'.$current_pageID;

  $pluginOpsClosePopUpConfirm = wp_create_nonce( 'MSFB_close_confirmation' );
  $closeActionUrl = admin_url("admin-ajax.php?action=smfb_popup_closed&ps_ID=$current_pageID&smfb_close_confirm=$pluginOpsClosePopUpConfirm");
  ?>
  <script type="text/javascript">
    (function($){
      $(document).ready(function() {
        $('#popb_popup_close_<?php echo $this_PopUpClose_unique_Id ?>').on('click',function(){
          var cookieCloseTime = <?php echo "$cookieCloseTime"; ?>;
          if (cookieCloseTime > 0) {
            if ($.cookie) {
              $.cookie("pluginops_user_closed_form<?php echo $current_pageID; ?>", 'yes', {path: '/', expires : cookieCloseTime/24 });
            }
          }

          <?php echo "popupClosed_$current_pageID"; ?> = 'closed';

          var result = " ";
          $.ajax({
            url: '<?php echo $closeActionUrl; ?>',
            method: 'post',
            data: '',
            success: function (result) {
            }
          });
          return false;
        });

        <?php if($data['pageOptions']['closeOnOverlay'] == 'true'){ ?>
            $('#POPB-modal-overlay_<?php echo $this_PopUpClose_unique_Id; ?>').on('click',function(ev){
              if ($(ev.target).hasClass( 'pluginops-modal')) {
                $('#popb_popup_close_<?php echo($this_PopUpClose_unique_Id); ?>').trigger('click');
              }
            });
        <?php } ?>
      });
    })(jQuery);
  </script>
  <?php
}

?>


<?php 
$containerWidthStyles = '';
if ($selecteOptinType == "Inline") {

  $inline_pageMaxWidth = '';
  $inline_pageMaxWidthU = '';
  if ( isset( $data['pageOptions']['pageMaxWidth'] )) {
    if ($data['pageOptions']['pageMaxWidth'] != '' && $data['pageOptions']['pageMaxWidth'] != '0') {
      $inline_pageMaxWidth = $data['pageOptions']['pageMaxWidth'];
      $inline_pageMaxWidthU = $data['pageOptions']['pageMaxWidthU'];
    }
  }
  $inline_pageMaxWidthT = '';
  $inline_pageMaxWidthUT = '';
  if ( isset( $data['pageOptions']['pageMaxWidthT'] )) {
    if ($data['pageOptions']['pageMaxWidthT'] != '' && $data['pageOptions']['pageMaxWidthT'] != '0') {
      $inline_pageMaxWidthT = $data['pageOptions']['pageMaxWidthT'];
      $inline_pageMaxWidthUT = $data['pageOptions']['pageMaxWidthUT'];
    }
    
  }
  $inline_pageMaxWidthM = '';
  $inline_pageMaxWidthUM = '';
  if ( isset( $data['pageOptions']['pageMaxWidthM'] )) {
    if ($data['pageOptions']['pageMaxWidthM'] != '' && $data['pageOptions']['pageMaxWidthM'] != '0') {
      $inline_pageMaxWidthM = $data['pageOptions']['pageMaxWidthM'];
      $inline_pageMaxWidthUM = $data['pageOptions']['pageMaxWidthUM'];
    }
      
  }

  $containerWidthStyles = "
  <style>
    @media screen and (max-width: 2920px) and (min-width: 1280px) {
        .ulpb_PageBody$current_pageID {
          max-width: ".$inline_pageMaxWidth.$inline_pageMaxWidthU."  !important;
        }
      }
      @media screen and (max-width: 1275px) and (min-width: 668px)  {
        .ulpb_PageBody$current_pageID {
          max-width: ".$inline_pageMaxWidthT.$inline_pageMaxWidthUT."  !important;
        }
      }
      @media screen and (max-width: 667px) and (min-width: 280px)  {
        .ulpb_PageBody$current_pageID {
          max-width: ".$inline_pageMaxWidthM.$inline_pageMaxWidthUM."  !important;
        }
      }
  </style>
  ";
}

echo $containerWidthStyles;
if ($current_postType == 'post' || $current_postType == 'pluginops_forms' || $current_postType == 'page' || $isShortCodeTemplate == true ){ echo "<div class='ulpb_PageBody ulpb_PageBody$current_pageID'>";} else{ echo "<body class='ulpb_PageBody ulpb_PageBody$current_pageID''>"; }
if ($selecteOptinType != 'Full Page') {
  echo "<div id='fullPageBgOverlay_$current_pageID' style='height: 100%; top: 0; left: 0; width: 100%; position: absolute;'></div>";
}

?>

  <?php
  $rows = $data['Rows'];

  $rowCount = 0;

  

  foreach ($rows as $row) {

    if (isset($row['globalRow'])) {

      if (isset($row['globalRow']['globalRowPid'])) {
        $isGlobalRow = $row['globalRow']['isGlobalRow'];
        if ($isGlobalRow == true) {
          $globalRowPostData = get_post_meta( $row['globalRow']['globalRowPid'], ULPB_DATA, true );
          $row = $globalRowPostData['Rows'][0]; 
        }
      }
    }
    $row["rowID"] = 'ulpb_Row'.(rand(10,99)*120+200);
    $rowID = $row["rowID"];
    $columns = $row['columns'];
    $rowHeight = $row['rowHeight'];
    $rowData = $row['rowData'];
    $rowMargins = $rowData['margin'];
    $rowPadding = $rowData['padding'];
    $rowBgColor = $rowData['bg_color'];
    $rowBgImg = $rowData['bg_img'];
    $currentRowcustomCss = $rowData['customStyling'];
    $currentRowcustomJS = $rowData['customJS'];

    $rowMarginTop = $rowMargins['rowMarginTop'];
    $rowMarginBottom = $rowMargins['rowMarginBottom'];
    $rowMarginLeft = $rowMargins['rowMarginLeft'];
    $rowMarginRight = $rowMargins['rowMarginRight'];

    $rowPaddingTop = $rowPadding['rowPaddingTop'];
    $rowPaddingBottom = $rowPadding['rowPaddingBottom'];
    $rowPaddingLeft = $rowPadding['rowPaddingLeft'];
    $rowPaddingRight = $rowPadding['rowPaddingRight'];

    if (!isset($row['rowHeightUnit']) ) {
      $rowHeightUnit = 'px';
    }else{  
      if ($row['rowHeightUnit'] == '') {
        $rowHeightUnit = 'px';
      } else{
        $rowHeightUnit = $row['rowHeightUnit'];
      }
    }

    if (isset($row['rowHeightTablet']) ) {
      $rowHeightTablet = $row['rowHeightTablet'];
      $rowHeightUnitTablet = $row['rowHeightUnitTablet'];
      $rowHeightMobile = $row['rowHeightMobile'];
      $rowHeightUnitMobile = $row['rowHeightUnitMobile'];
    }else{
      $rowHeightTablet = '';
      $rowHeightUnitTablet = '';
      $rowHeightMobile = '';
      $rowHeightUnitMobile = '';
    }

    $rowBackgroundOptions  = "background:url($rowBgImg)no-repeat center center; background-size:cover; background-color:$rowBgColor ; ";
    if (isset($rowData['rowBackgroundType'])) {
      if ($rowData['rowBackgroundType'] == 'gradient') {
        $rowGradient = $rowData['rowGradient'];
        if ($rowGradient['rowGradientType'] == 'linear') {
          $rowBackgroundOptions = 'background: linear-gradient('.$rowGradient['rowGradientAngle'].'deg, '.$rowGradient['rowGradientColorFirst'].' '.$rowGradient['rowGradientLocationFirst'].'%,'.$rowGradient['rowGradientColorSecond'].' '.$rowGradient['rowGradientLocationSecond'].'%) ;';
        }
        if ($rowGradient['rowGradientType'] == 'radial') {
          $rowBackgroundOptions = 'background: radial-gradient(at '.$rowGradient['rowGradientPosition'].', '.$rowGradient['rowGradientColorFirst'].' '.$rowGradient['rowGradientLocationFirst'].'%,'.$rowGradient['rowGradientColorSecond'].' '.$rowGradient['rowGradientLocationSecond'].'%) ;';
        }
      }
    }

    $rowOverlayBackgroundOptions = '';
    if (isset($rowData['rowBgOverlayColor'])) {
      $rowOverlayBackgroundOptions  = " background:".$rowData['rowBgOverlayColor']." ; background-color:".$rowData['rowBgOverlayColor']." ;";
    }
    if (isset($rowData['rowOverlayBackgroundType'])) {
      if ($rowData['rowOverlayBackgroundType'] == 'gradient') {
        $rowOverlayGradient = $rowData['rowOverlayGradient'];
        if ($rowOverlayGradient['rowOverlayGradientType'] == 'linear') {
          $rowOverlayBackgroundOptions = 'background: linear-gradient('.$rowOverlayGradient['rowOverlayGradientAngle'].'deg, '.$rowOverlayGradient['rowOverlayGradientColorFirst'].' '.$rowOverlayGradient['rowOverlayGradientLocationFirst'].'%,'.$rowOverlayGradient['rowOverlayGradientColorSecond'].' '.$rowOverlayGradient['rowOverlayGradientLocationSecond'].'%) ;';
        }
        if ($rowOverlayGradient['rowOverlayGradientType'] == 'radial') {
          $rowOverlayBackgroundOptions = 'background: radial-gradient(at '.$rowOverlayGradient['rowOverlayGradientPosition'].', '.$rowOverlayGradient['rowOverlayGradientColorFirst'].' '.$rowOverlayGradient['rowOverlayGradientLocationFirst'].'%,'.$rowOverlayGradient['rowOverlayGradientColorSecond'].' '.$rowOverlayGradient['rowOverlayGradientLocationSecond'].'%) ;';
        }
      }
    }


    $thisRowHoverOption = '';
    if (isset($rowData['rowHoverOptions'])) {
        $rowHoverOptions = $rowData['rowHoverOptions'];
        if (isset($rowHoverOptions['rowBackgroundTypeHover'])) {
          if ($rowHoverOptions['rowBackgroundTypeHover'] == 'solid') {
            $thisRowHoverOption = ' #'.$row['rowID'].':hover { background:'.$rowHoverOptions['rowBgColorHover'].' !important; transition: all '.$rowHoverOptions['rowHoverTransitionDuration'].'s; }';
          }
          if ($rowHoverOptions['rowBackgroundTypeHover'] == 'gradient') {
            $rowGradientHover = $rowHoverOptions['rowGradientHover'];

            if ($rowGradientHover['rowGradientTypeHover'] == 'linear') {
              $thisRowHoverOption = ' #'.$row['rowID'].':hover { background: linear-gradient('.$rowGradientHover['rowGradientAngleHover'].'deg, '.$rowGradientHover['rowGradientColorFirstHover'].' '.$rowGradientHover['rowGradientLocationFirstHover'].'%,'.$rowGradientHover['rowGradientColorSecondHover'].' '.$rowGradientHover['rowGradientLocationSecondHover'].'%) !important; transition: all '.$rowHoverOptions['rowHoverTransitionDuration'].'s; }';
            }

            if ($rowGradientHover['rowGradientTypeHover'] == 'radial') {

              $thisRowHoverOption = ' #'.$row['rowID'].':hover { background: radial-gradient(at '.$rowGradientHover['rowGradientPositionHover'].', '.$rowGradientHover['rowGradientColorFirstHover'].' '.$rowGradientHover['rowGradientLocationFirstHover'].'%,'.$rowGradientHover['rowGradientColorSecondHover'].' '.$rowGradientHover['rowGradientLocationSecondHover'].'%) !important; transition: all '.$rowHoverOptions['rowHoverTransitionDuration'].'s; }';
            }
          }
        }
          

    }


    $rowCustomClass ='';
    if (isset($rowData['rowCustomClass'])) {
      $rowCustomClass = $rowData['rowCustomClass'];
    }

    $rowHideOnDesktop = "display:block"; $rowHideOnTablet = "display:block"; $rowHideOnMobile = "display:block";
    if (isset($rowData['rowHideOnDesktop']) ) {
      if ($rowData['rowHideOnDesktop'] == 'hide') {
        $rowHideOnDesktop ="display:none";
      }
      if ($rowData['rowHideOnTablet'] == 'hide') {
        $rowHideOnTablet ="display:none !important;";
      }
      if ($rowData['rowHideOnMobile'] == 'hide') {
        $rowHideOnMobile ="display:none !important;";
      }
    }


    if (isset($rowData['marginTablet'])) {

      $rowMarginTablet = $rowData['marginTablet'];
      $rowMarginMobile = $rowData['marginMobile'];
      $rowPaddingTablet = $rowData['paddingTablet'];
      $rowPaddingMobile = $rowData['paddingMobile'];
      
      $thisRowResponsiveRowStylesTablet = "
        #".$row["rowID"]." {
         margin-top: ".$rowMarginTablet['rMTT']."% !important;
         margin-bottom: ".$rowMarginTablet['rMBT']."% !important;
         margin-left: ".$rowMarginTablet['rMLT']."% !important;
         margin-right: ".$rowMarginTablet['rMRT']."% !important;

         padding-top: ".$rowPaddingTablet['rPTT']."% !important;
         padding-bottom: ".$rowPaddingTablet['rPBT']."% !important;
         padding-left: ".$rowPaddingTablet['rPLT']."% !important;
         padding-right: ".$rowPaddingTablet['rPRT']."% !important;

         min-height:".$rowHeightTablet."$rowHeightUnitTablet !important;
         $rowHideOnTablet
        }
      
      ";
      $thisRowResponsiveRowStylesMobile = "
      
        #".$row["rowID"]." {
         margin-top: ".$rowMarginMobile['rMTM']."% !important;
         margin-bottom: ".$rowMarginMobile['rMBM']."% !important;
         margin-left: ".$rowMarginMobile['rMLM']."% !important;
         margin-right: ".$rowMarginMobile['rMRM']."% !important;

         padding-top: ".$rowPaddingMobile['rPTM']."% !important;
         padding-bottom: ".$rowPaddingMobile['rPBM']."% !important;
         padding-left: ".$rowPaddingMobile['rPLM']."% !important;
         padding-right: ".$rowPaddingMobile['rPRM']."% !important;

         min-height:".$rowHeightMobile."$rowHeightUnitMobile !important;
         $rowHideOnMobile
        }
      ";

      array_push($POPBNallRowStylesResponsiveTablet, $thisRowResponsiveRowStylesTablet);
      array_push($POPBNallRowStylesResponsiveMobile, $thisRowResponsiveRowStylesMobile);
    }

    /*
    $rowMarginTop = floor( ($rowMarginTop/1268)*100);
    $rowMarginBottom = floor( ($rowMarginBottom/1268)*100);
    $rowMarginLeft = floor( ($rowMarginLeft/1268)*100);
    $rowMarginRight = floor( ($rowMarginRight/1268)*100);

    $rowPaddingTop = floor( ($rowPaddingTop/1268)*100);
    $rowPaddingBottom = floor( ($rowPaddingBottom/1268)*100);
    $rowPaddingLeft = floor( ($rowPaddingLeft/1268)*100);
    $rowPaddingRight = floor( ($rowPaddingRight/1268)*100);
    */

    $rowMarginStyle = "margin:$rowMarginTop"."% $rowMarginRight"."% $rowMarginBottom"."% $rowMarginLeft"."%;";

    $rowPaddingStyle = "padding:$rowPaddingTop"."% $rowPaddingRight"."% $rowPaddingBottom"."% $rowPaddingLeft"."%;";

    $currentRowStyles = "#".$row["rowID"]."{   min-height:$rowHeight"."$rowHeightUnit; $rowPaddingStyle  $rowMarginStyle  $rowBackgroundOptions   $currentRowcustomCss  $rowHideOnDesktop }     $thisRowHoverOption ";

    array_push($POPBNallRowStyles, $currentRowStyles);

    //echo($row['rowID']."<br>");
    include_once 'column-width-resize.php';

    ?>

    <script type="text/javascript">
      <?php echo $currentRowcustomJS; ?>
    </script>
    <div class='pluginops-optinRow w3-row  <?php echo $rowCustomClass ?>' data-row_id='<?php echo $row["rowID"]; ?>' id='<?php echo $row["rowID"]; ?>'>
      <div class="overlay-row" style="<?php echo "$rowOverlayBackgroundOptions"; ?>"></div>


      <?php

      if (isset($rowData['bgSTop']) ) {
        $bgSTop = $rowData['bgSTop'];
        $bgShapeTop = '';
        $rowID = $row["rowID"];
        $positionID  = 'top';
        $shapeType = $bgSTop['rbgstType'];
        if ($shapesinluded == false) {
          include_once 'svgShapes.php';
          $shapesinluded = true;
        }

        $invertTransform = '';
        if ($shapeType == 'random' ) {
          $invertTransform = 'transform:rotate(180deg);';
        }

        if (function_exists('bgSvgShapesRenderCode') ) {
          $bgShapesArray = bgSvgShapesRenderCode($rowID, $positionID, $shapeType);
          $bgShapeTop = $bgShapesArray['shape'];
          $vieBoxAttr = $bgShapesArray['shapeAttr'];
        }

        $renderredHTML = '';
        $returnScripts = '';

        
        if ($bgSTop != 'false') {
          $isFlipped = '';
          if ($bgSTop['rbgstFlipped'] == 'true') {
            $isFlipped = 'transform:rotateY(180deg);';
          }

          if ($bgSTop['rbgstType'] == 'none') {
            $bgShapeTop = '';
          }
          $onFront = '';
          if ($bgSTop['rbgstFront'] == 'true') {
            $onFront = 'z-index:2;'; 
          }

          if ($bgShapeTop != '') {

            $renderredShapeHTML = 
            '<div class="bgShapes bgShapeTop-'.$row["rowID"].'"  style="overflow: hidden; position: absolute; left: 0; width: 100%; direction: ltr; top: -1px; text-align:center; '.$onFront.' '.$invertTransform.' ">'.
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="'.$vieBoxAttr.'" preserveAspectRatio="none" style="width: calc('.$bgSTop['rbgstWidth'].'% + 1.5px); height: '.$bgSTop['rbgstHeight'].'px;  position: relative; '.$isFlipped.'" >'.
                $bgShapeTop.
              '</svg>'.
            ' <style>  .po-top-path-'.$row["rowID"].' { fill:'.$bgSTop['rbgstColor'].'; } </style> </div>  ';

            echo "$renderredShapeHTML";

            $thisShapeResponsiveTablet = "
              .bgShapeTop-".$row["rowID"]." svg {
                width: calc(".$bgSTop['rbgstWidtht']."% + 1.5px) !important;
                height:".$bgSTop['rbgstHeightt']."px !important;
              }
            ";

            $thisShapeResponsiveMobile = "
              .bgShapeTop-".$row["rowID"]." svg {
                width: calc(".$bgSTop['rbgstWidthm']."% + 1.5px) !important;
                height:".$bgSTop['rbgstHeightm']."px !important;
              }
            ";
            array_push($POPBNallRowStylesResponsiveTablet, $thisShapeResponsiveTablet);
            array_push($POPBNallRowStylesResponsiveMobile, $thisShapeResponsiveMobile);


          }
        }

      }

      if (isset($rowData['bgSBottom']) ) {
        $bgSBottom = $rowData['bgSBottom'];
        $bgShapeBottom = '';
        $rowID = $row["rowID"];
        $positionID  = 'bottom';
        $shapeType = $bgSBottom['rbgsbType'];
        if ($shapesinluded == false) {
          include_once 'svgShapes.php';
          $shapesinluded = true;
        }

        $invertTransform = '';
        if ($shapeType == 'random' ) {
          $invertTransform = 'transform:rotate(0deg);';
        }

        if (function_exists('bgSvgShapesRenderCode') ) {
          $bgShapesArray = bgSvgShapesRenderCode($rowID, $positionID, $shapeType);
          $bgShapeBottom = $bgShapesArray['shape'];
          $vieBoxAttr = $bgShapesArray['shapeAttr'];
        }

        $renderredHTML = '';
        $returnScripts = '';

        
        if ($bgSBottom != 'false') {
          $isFlipped = '';
          if ($bgSBottom['rbgsbFlipped'] == 'true') {
            $isFlipped = 'transform:rotateY(180deg);';
          }

          if ($bgSBottom['rbgsbType'] == 'none') {
            $bgShapeBottom = '';
          }
          $onFront = '';
          if ($bgSBottom['rbgsbFront'] == 'true') {
            $onFront = 'z-index:2;'; 
          }

          if ($bgShapeBottom != '') {

            $renderredShapeHTML = 
            '<div class="bgShapes bgShapeBottom-'.$row["rowID"].'"  style="overflow: hidden; position: absolute; left: 0; width: 100%; direction: ltr;  bottom: -1px; transform: rotate(180deg); text-align:center; '.$onFront.' '.$invertTransform.' ">'.
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="'.$vieBoxAttr.'" preserveAspectRatio="none" style="width: calc('.$bgSBottom['rbgsbWidth'].'% + 1.5px); height: '.$bgSBottom['rbgsbHeight'].'px;  position: relative; '.$isFlipped.'" >'.
                $bgShapeBottom.
              '</svg>'.
            ' <style>  .po-bottom-path-'.$row["rowID"].' { fill:'.$bgSBottom['rbgsbColor'].'; } </style> </div>  ';

            echo "$renderredShapeHTML";

            $thisShapeResponsiveTablet = "
              .bgShapeBottom-".$row["rowID"]." svg {
                width: calc(".$bgSBottom['rbgsbWidtht']."% + 1.5px) !important;
                height:".$bgSBottom['rbgsbHeightt']."px !important;
              }
            ";

            $thisShapeResponsiveMobile = "
              .bgShapeBottom-".$row["rowID"]." svg {
                width: calc(".$bgSBottom['rbgsbWidthm']."% + 1.5px) !important;
                height:".$bgSBottom['rbgsbHeightm']."px !important;
              }
            ";
            array_push($POPBNallRowStylesResponsiveTablet, $thisShapeResponsiveTablet);
            array_push($POPBNallRowStylesResponsiveMobile, $thisShapeResponsiveMobile);


          }
        }

      }

      ?>
      

      <?php
      if (isset($rowData['video'])) {
        $rowVideo = $rowData['video'];
        $rowBgVideoEnable = $rowVideo['rowBgVideoEnable'];
        if ($rowBgVideoEnable == 'true') {
          $rowBgVideoLoop = $rowVideo['rowBgVideoLoop'];
          $rowVideoMpfour = $rowVideo['rowVideoMpfour'];
          $rowVideoWebM = $rowVideo['rowVideoWebM'];
          $rowVideoThumb = $rowVideo['rowVideoThumb'];
          ?>
          <video poster="<?php echo $rowVideoThumb; ?>" id="bgVid-<?php echo $row["rowID"]; ?>" playsinline autoplay muted <?php echo $rowBgVideoLoop; ?> >
            <source src="<?php echo $rowVideoWebM; ?>" type="video/webm">
            <source src="<?php echo $rowVideoMpfour; ?>" type="video/mp4">
            </video>
            <style type="text/css">
            #bgVid-<?php echo $row["rowID"]; ?> { 
              position: absolute;
              min-width: 100%;
              min-height: 100%;
              width: auto;
              height: auto;
              z-index: -100;
              background: url('<?php echo $rowVideoThumb; ?>') no-repeat;
              background-size: cover;
              transition: 1s opacity;
          }
          </style>

          <?php
        }
        
      }
      ?>
      
    <?php include('columns.php'); ?>

    </div>
    <?php 
    $rowCount++;
  } // ForEach loop for rows ends here

  echo '<style type="text/css">';
  foreach ($POPBNallRowStyles as $value) {
    echo $value . "  ";
  }

  foreach ($POPBallColumnStylesArray as $value) {
    echo $value . "  ";
  }

  foreach ($POPBallWidgetsStylesArray as $value) {
    echo $value . "  ";
  }


  echo " \n @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) { ";
  foreach ($POPBNallRowStylesResponsiveTablet as $value) {
    echo $value . "  ";
  }
  echo " } ";

  echo " \n @media only screen and (min-device-width : 320px) and (max-device-width : 480px) { ";
  foreach ($POPBNallRowStylesResponsiveMobile as $value) {
    echo $value . "  ";
  }
  echo " } ";

  echo '</style>';

  $thisColFontsToLoadSeparatedValue = 'Allerta';
      foreach ($thisColFontsToLoad as $value) {

        if ($value !== '') {
          $aller = strpos($thisColFontsToLoadSeparatedValue, $value);
        }
        
        if ($value == 'Select' || $value == 'select' || $value == 'Arial' || $value == 'sans-serif' || $value == 'Arial Black' || $value == 'sans' || $value == 'Helvetica' || $value == 'Serif' || $value == 'Tahoma' || $value == 'Verdana' || $value == 'Monaco' || $aller !== false) {
        }else{
          if ($value != '') {
            $thisColFontsToLoadSeparatedValue = $thisColFontsToLoadSeparatedValue . '|' .$value;
          }
        }
        
      }

      echo '<link rel="stylesheet"href="https://fonts.googleapis.com/css?family='.$thisColFontsToLoadSeparatedValue.'">';



  ?>

<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.pb_img_thumbnail').on('click',function(){
    var clikedElID = jQuery(this).attr('id');
    jQuery('#pb_lightbox'+clikedElID).css('display','block');
  });

  jQuery('.pb_single_img_lightbox').on('click',function(){
    jQuery(this).css('display','none');
  });
});
  
</script>

<?php  echo  '<script type="text/javascript">
        (function($){
          jQuery(window).scroll(function(event) { 
             '.   $widgetAnimationTriggerScript  . "  
          }); 
        })(jQuery);
      </script>  \n" ; ?>

<?php


if ($widgetSliderLoadScripts == true) {
  echo "<script src='".SMFB_PLUGIN_URL."/js/slider.min.js'></script>";
}

if ($widgetFALoadScripts == true) {
  echo "<script src='".SMFB_PLUGIN_URL."/js/fa.js'></script>";
} 

if ($widgetVideoLoadScripts == true) {
  echo "<link href='".SMFB_PLUGIN_URL."/js/videoJS/video-js.css' rel='stylesheet'>";
  echo "<script src='".SMFB_PLUGIN_URL."/js/videoJS/video.js'></script>";
} 

if ($widgetOwlLoadScripts == true) {
  echo "
  <link rel='stylesheet' type='text/css' href='".SMFB_PLUGIN_URL."/public/scripts/owl-carousel/owl.carousel.css'>
  <link rel='stylesheet' type='text/css' href='".SMFB_PLUGIN_URL."/public/scripts/owl-carousel/owl.theme.css'>
  <link rel='stylesheet' type='text/css' href='".SMFB_PLUGIN_URL."/public/scripts/owl-carousel/owl.transitions.css'>";
} 

if ($widgetWooCommLoadScripts == true) {
  echo "<link href='".SMFB_PLUGIN_URL."/styles/wooStyles.css' rel='stylesheet'>";
} 


?>


<link rel="stylesheet" type="text/css" href="<?php echo SMFB_PLUGIN_URL."/public/templates/animate.min.css"; ?>">

<?php     ?>


<?php if ($current_postType == 'post' || $current_postType == 'page' || $isShortCodeTemplate == true ){ echo "</div>";} else{ echo "</body>"; }   ?>

<?php
} else{
}

  if (!isset($isPopUpTemplate)) {
    $isPopUpTemplate = false;
  }
  if (!isset($isPopUpFullPageTemplate)) {
    $isPopUpFullPageTemplate = false;
  }
  if (!isset($isPopUpFlyInTemplate)) {
    $isPopUpFlyInTemplate = false;
  }
  if (!isset($isPopUpBarTemplate)) {
    $isPopUpBarTemplate = false;
  }

  if ($isPopUpTemplate == true || $selecteOptinType == 'PopUp') {
    ?>
      </div></div>
    <?php
  }
  if ($isPopUpFullPageTemplate == true || $selecteOptinType == 'Full Page') {
    ?>
      </div></div>
    <?php
  }

  if ( $isPopUpFlyInTemplate == true || $selecteOptinType == 'Fly In') {
    ?>
      </div></div>
    <?php
  }
  if ( $isPopUpBarTemplate == true || $selecteOptinType == 'Bar') {
    ?>
      </div></div>
    <?php
  }

if (isset($hideOnDesktop)) {
  if ( $hideOnDesktop == true || $hideOnTablet == true || $hideOnMobile == true ) {
    echo "</div>";
  }
}


}
?>