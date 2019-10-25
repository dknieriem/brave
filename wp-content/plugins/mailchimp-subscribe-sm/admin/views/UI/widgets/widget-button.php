<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<br><br>
<div class="tabs" style="width: 107%;">
                  <ul class="tab-links">
                    <li class="active"><a href="#btntab1" class="tab_link">Button</a></li>
                    <li><a href="#btntab2" class="tab_link">Icon</a></li>
                  </ul>
                  <div class="tab-content" style="box-shadow:none;">
                    <div id="btntab1" class="tab active">
                      <div id="btn-gen" class="pbp_form" style="margin:0 0 0 0; background: #fff; padding:20px 10px 20px 25px; width: 99%;">
                        <br>
                        <br>
                        <label>Button Text :</label>
                        <input type="text" class="btnText" style="width: 250px;" placeholder="Button Text">
                        <!-- 
                        <br><br><br><br><hr><br><br>
                        <label>On Click Action :</label>
                        <select class="btnClickAction">
                          <option value="openLink"> Open Link </option>
                          <option value="openPopUp"> Open PopUp </option>
                        </select>
                        -->
                        <select class="btnClickAction" style="display: none;">
                          <option value="openLink"> Open Link </option>
                          <option value="openPopUp"> Open PopUp </option>
                        </select>
                        <br><br><br><hr><br><br>
                        <div class="btnLinkOpsContainer">
                          <label>Button Link :</label>
                          <input type="URL" class="btnLink" placeholder="Link URL">
                          <br><br><hr><br>
                          <label>Open Link :</label>
                          <select class="btnBlankAttr" id="btnBlankAttr">
                            <option value="_self">Same Tab</option>
                            <option value="_blank">New Tab</option>
                          </select>
                          <br><br><hr><br>
                          <label>Custom Link Actions : <br> <span style="font-size: 11px;">(For developers)</span> </label>
                          <input type="text" class="btnCAction" style="width: 250px;" placeholder="Button Actions">
                          <br><br><br><br><hr><br>
                        </div>
                        <div class="openPopUpOpsContainer" style="display: none;">

                          <?php
                            if ( post_type_exists('pluginops_forms') ) { ?>

                            <label>Select PopUp : </label>
                            <select class="btnWidgetPopUpId" id="btnWidgetPopUpId">
                                <option value="Select">Choose...</option>
                                <?php 
                                  $ULP_pluginOps_Optins = array(
                                    'post_type' => 'pluginops_forms',
                                    'orderby' => 'date',
                                    'post_status'   => 'any',
                                    'posts_per_page'    => 100,
                                  );
                                  $ULP_pluginOps_Optins_posts = get_posts( $ULP_pluginOps_Optins );
                                  if (!is_array($ULP_pluginOps_Optins_posts)) {
                                    $ULP_pluginOps_Optins_posts = array();
                                  }
                                  foreach ($ULP_pluginOps_Optins_posts as  $thisPost) {
                                    $currentPostId = $thisPost->ID;
                                    $currentPostName = get_the_title($currentPostId);
                                    $currentPostLink = get_permalink($currentPostId);
                                    echo "<option value='$currentPostId' > $currentPostName </option>";
                                  }
                                ?>
                            </select>
                              
                            <?php } else { ?> 
                              <p style="background: #f0f0f0; color:#333; padding: 10px; max-width: 90%; font-size: 17px;">Please install the Optin Builder plugin to access PluginOps Optins and to add them in your Landing Page.<br> You can install it by clicking here : <a target="_blank" href="<?php echo admin_url('plugin-install.php?s=pluginops+&tab=search&type=term'); ?>"> Install Optin Builder</a></p>
                            <?php } ?>
                          <br><br><hr><br>
                        </div>
                        
                        <div>
                          <h4>Height 
                            <span class="responsiveBtn rbt-l " > <i class="fa fa-desktop"></i> </span>   
                            <span class="responsiveBtn rbt-m " > <i class="fa fa-tablet"></i> </span>
                            <span class="responsiveBtn rbt-s " > <i class="fa fa-mobile-phone"></i> </span>
                          </h4>
                          <div class="responsiveOps responsiveOptionsContainterLarge">
                            <label></label>
                            <input type="number" class="btnHeight">px
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterMedium" style="display: none;">
                              <label></label>
                              <input type="number" class="btnHeightTablet">px
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterSmall" style="display: none;">
                            <label></label>
                            <input type="number" class="btnHeightMobile">px
                          </div>
                        </div>
                        <br><br><hr><br>
                        <div>
                          <h4>Width 
                            <span class="responsiveBtn rbt-l " > <i class="fa fa-desktop"></i> </span>   
                            <span class="responsiveBtn rbt-m " > <i class="fa fa-tablet"></i> </span>
                            <span class="responsiveBtn rbt-s " > <i class="fa fa-mobile-phone"></i> </span>
                          </h4>
                          <div class="responsiveOps responsiveOptionsContainterLarge">
                            <label></label>
                            <input type="number" class="btnWidthPercent" style="width:70px;">
                            <select style="width:70px;" class="btnWidthUnit">
                              <option value='%'>Percent</option>
                              <option value="px">Pixel</option>
                            </select>
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterMedium" style="display: none;">
                              <label></label>
                              <input type="number" class="btnWidthPercentTablet" style="width:70px;">
                              <select style="width:70px;" class="btnWidthUnitTablet">
                                <option value='%'>Percent</option>
                                <option value="px">Pixel</option>
                              </select>
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterSmall" style="display: none;">
                            <label></label>
                            <input type="number" class="btnWidthPercentMobile" style="width:70px;">
                            <select style="width:70px;" class="btnWidthUnitMobile">
                              <option value='%'>Percent</option>
                              <option value="px">Pixel</option>
                            </select>
                          </div>
                        </div>
                        <br><br><hr><br>
                        <label>Background Color :</label>
                        <input type="text" class="color-picker_btn_two btnBgColor" id="btnBgColor" data-alpha='true'>
                        <br><br><hr><br>
                        <label>Hover Background Color :</label>
                        <input type="text" class="color-picker_btn_two btnHoverBgColor" id="btnHoverBgColor" data-alpha='true'>
                        <br><br><hr><br>
                        <label>Button Text Color :</label>
                        <input type="text" class="color-picker_btn_two btnColor" id="btnColor">
                        <br><br><hr><br>
                        <label>Hover Text Color :</label>
                        <input type="text" class="color-picker_btn_two btnHoverTextColor" id="btnHoverTextColor" data-alpha='true'>
                        <br><br><hr><br> 
                        <div>
                          <h4>Font size 
                            <span class="responsiveBtn rbt-l " > <i class="fa fa-desktop"></i> </span>   
                            <span class="responsiveBtn rbt-m " > <i class="fa fa-tablet"></i> </span>
                            <span class="responsiveBtn rbt-s " > <i class="fa fa-mobile-phone"></i> </span>
                          </h4>
                          <div class="responsiveOps responsiveOptionsContainterLarge">
                            <label></label>
                            <input type="number" class="btnFontSize">px
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterMedium" style="display: none;">
                              <label></label>
                              <input type="number" class="btnFontSizeTablet">px
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterSmall" style="display: none;">
                            <label></label>
                            <input type="number" class="btnFontSizeMobile">px
                          </div>
                        </div>
                        <br><br><hr><br>
                        <label>Border Width: </label>
                        <input type="number" class="btnBorderWidth">
                        <br><br><hr><br>
                        <label>Border Color: </label>
                        <input type="text" class="color-picker_btn_two btnBorderColor" id="btnBorderColor" >
                        <br><br><hr><br>
                        <label>Corner Radius: </label>
                        <input type="number" class="btnBorderRadius" max="100" min="0">
                        <br><br><hr><br>
                        <div>
                          <h4>Button Alignment 
                            <span class="responsiveBtn rbt-l " > <i class="fa fa-desktop"></i> </span>   
                            <span class="responsiveBtn rbt-m " > <i class="fa fa-tablet"></i> </span>
                            <span class="responsiveBtn rbt-s " > <i class="fa fa-mobile-phone"></i> </span>
                          </h4>
                          <div class="responsiveOps responsiveOptionsContainterLarge">
                            <label></label>
                            <select class="btnButtonAlignment">
                              <option value="default">Default</option>
                              <option value="left">Left</option>
                              <option value="right">Right</option>
                              <option value="center">Center</option>
                            </select>
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterMedium" style="display: none;">
                            <label></label>
                            <select class="btnButtonAlignmentTablet">
                              <option value="default">Default</option>
                              <option value="left">Left</option>
                              <option value="right">Right</option>
                              <option value="center">Center</option>
                            </select>
                          </div>
                          <div class="responsiveOps responsiveOptionsContainterSmall" style="display: none;">
                            <label></label>
                            <select class="btnButtonAlignmentMobile">
                              <option value="default">Default</option>
                              <option value="left">Left</option>
                              <option value="right">Right</option>
                              <option value="center">Center</option>
                            </select>
                          </div>
                        </div>
                        <br><br><hr><br>
                        <label>Button Font :</label>
                        <input class="btnButtonFontFamily gFontSelectorulpb" id="btnButtonFontFamily">
                        
                        <br><br><hr><br><br><br><br><br><br><br><br><br><br><br>
                      </div>
                    </div>
                    <div id="btntab2" class="tab">
                      <div id="btn-gen" class="pbp_form" style="margin:0 0 0 0; background: #fff; padding:20px 10px 20px 25px; width: 99%;">
                        <label>Select Icon </label>
                        <input  data-placement="bottomRight" class="icp pbicp-auto btnSelectedIconpbicp-auto" value="" type="text" />
                        <span class="input-group-addon btnSelectedIcon" style="font-size: 16px;"></span>
                        <br><br><hr><br>
                        <label>Icon Position </label>
                        <select class="btnIconPosition">
                          <option value="before">Before Text</option>
                          <option value="after">After Text</option>
                        </select>
                        <br><br><hr><br>
                        <label>Icon Gap </label>
                        <input type="number" class="btnIconGap">px
                        <br><br><hr><br>
                        <label>Icon Hover Animation </label>
                        <select class="btnIconAnimation">
                          <option value="">None</option>
                          <optgroup label="Attention Seekers">
                            <option value="bounce">bounce</option>
                            <option value="flash">flash</option>
                            <option value="pulse">pulse</option>
                            <option value="rubberBand">rubberBand</option>
                            <option value="shake">shake</option>
                            <option value="swing">swing</option>
                            <option value="tada">tada</option>
                            <option value="wobble">wobble</option>
                            <option value="jello">jello</option>
                            <option value="flip">flip</option>
                          </optgroup>
                        </select>
                      </div>
                    </div>
                  </div>
</div>

<script type="text/javascript">
  (function($){
    $('.btnLinkOpsContainer').css('display','block');
    $('.btnClickAction').on('change',function(){
      if ( $(this).val() == 'openPopUp' ) {
        $('.btnLinkOpsContainer').css('display','none');
        $('.openPopUpOpsContainer').css('display','block');
      }else{
        $('.btnLinkOpsContainer').css('display','block');
        $('.openPopUpOpsContainer').css('display','none');
      }
    });
  })(jQuery);
</script>