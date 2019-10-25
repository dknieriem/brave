<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php 

  if (is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' )  || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
      $templatesOneExtLink = '<button class="ext_cta_installed">'.__( 'Installed', 'mailchimp-subscribe-sm' ).'  </button>';
  }else{
    $templatesOneExtLink = '<a href="https://pluginops.com/page-builder/?ref=Optin_extensionsTemplatesPack"> <button class="ext_cta"> '.__( 'Get All Features', 'mailchimp-subscribe-sm' ).'</button> </a>';
  }

  if (is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' )  || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
      $globalRowExtLink = '<button class="ext_cta_installed">'.__( 'Installed', 'mailchimp-subscribe-sm' ).'  </button>';
  }else{
    $globalRowExtLink = '<a href="https://pluginops.com/page-builder/?ref=Optin_extensionsGRow"> <button class="ext_cta"> '.__( 'Get All Features', 'mailchimp-subscribe-sm' ).'</button> </a>';
  }

  if (is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' )  || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
      $exportDuplicateExtLink = '<button class="ext_cta_installed">'.__( 'Installed', 'mailchimp-subscribe-sm' ).'  </button>';
  }else{
    $exportDuplicateExtLink = '<a href="https://pluginops.com/page-builder/?ref=Optin_extensionsExport"> <button class="ext_cta"> '.__( 'Get All Features', 'mailchimp-subscribe-sm' ).'</button> </a>';
  }

  if (is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' )  || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
      $mailchimpExtLink = '<button class="ext_cta_installed">'.__( 'Installed', 'mailchimp-subscribe-sm' ).'  </button>';
  }else{
    $mailchimpExtLink = '<a href="https://pluginops.com/page-builder/?ref=Optin_extensionsIntegrations"> <button class="ext_cta"> '.__( 'Get All Features', 'mailchimp-subscribe-sm' ).'</button> </a>';
  }

  if (is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' )  || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
      $anyWhereTemplateExt = '<button class="ext_cta_installed">'.__( 'Installed', 'mailchimp-subscribe-sm' ).'  </button>';
  }else{
    $anyWhereTemplateExt = '<button class="ext_cta_installed">'.__( 'Installed', 'mailchimp-subscribe-sm' ).'  </button>';
  }

  if (is_plugin_active( 'PluginOps-S-Builder-Extensions-Pack/extension-pack.php' )  || is_plugin_active('PluginOps-Optin-Builder-Extensions-Pack/extension-pack.php') ) {
      $formBuilderDBExt = '<button class="ext_cta_installed">'.__( 'Installed', 'mailchimp-subscribe-sm' ).'  </button>';
  }else{
    $formBuilderDBExt = '<a href="https://pluginops.com/page-builder/?ref=Optin_extensionsDatabase"> <button class="ext_cta"> '.__( 'Get All Features', 'mailchimp-subscribe-sm' ).'</button> </a>';
  }


?>

<div id="ulpb_dash_container">
  <h2 style="font-size:20px; font-weight: normal; text-align: center; background: #e1e1e1; padding:15px 10px; "><?php _e( 'Amazing features which will boost your conversions instantly', 'mailchimp-subscribe-sm' ); ?>  </h2>
  <div class="tabs">
    <div class="tab-content" style="min-height: 750px;">
      <div id="tab1" class="tab active" style="background: #f1f1f1; padding:10px 30px;">
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/1.png' ?>"> </div>
          <div style="height: 180px;">
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionsTemplates'"> Premium Templates & Blocks </a> </h3>
            <p>Get premium pre designed templates & blocks to speed up your design process. Build your optin campaigns faster & better.</p>

            <?php echo $templatesOneExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/6.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionsDatabase'"> Database Extension </a> </h3>
            <p>With database extension you can save the user data from your forms in database which can be viewed & exported to be used with other services.</p>
          
            <?php echo $formBuilderDBExt; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/2.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionsExport'"> Export & Duplicate</a> </h3>
            <p>Export & Duplicate your forms and reuse them on multiple sites or same site, Easy one click export & import.</p>
          
            <?php echo $exportDuplicateExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/5.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionsEmbed'"> Advanced Placement </a> </h3>
            <p>Advanced Placement lets you place your optins anywhere automatically or with just a shortcode.</p>
          
            <?php echo $anyWhereTemplateExt; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/13.png' ?>"> </div>
           <div style="height: 180px;"> 
              <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionsMCI'"> A/B Testing </a> </h3>
              <p>With A/B testing features test upto 4 different variants of optin forms to find out what and where it converts the most. A/B Testing is highly effective tool to increase leads.</p>
              <?php echo $mailchimpExtLink; ?>
            </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/3.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionsMCI'"> MailChimp Integration </a> </h3>
            <p>MailChimp Extension allows you to send your subscribe form and form builder submissions directly to your mailchimp account.</p>
          
            <?php echo $mailchimpExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/7.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionGRI'"> Get Response Integration </a> </h3>
            <p>GetResponse Extension allows you to send your subscribe form and form builder submissions directly to your mailchimp account.</p>
          
            <?php echo $mailchimpExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/9.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionACI'"> Active Campaign Integration </a> </h3>
            <p>Active Campaign Extension allows you to send your subscribe form and form builder submissions directly to your mailchimp account.</p>
          
            <?php echo $mailchimpExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/8.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_'extensionCMI'"> Campaign Monitor Integration </a> </h3>
            <p>Campaign Monitor Extension allows you to send your subscribe form and form builder submissions directly to your mailchimp account.</p>
          
            <?php echo $mailchimpExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/10.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_extensionsPage"> AWeber Integration </a> </h3>
            <p>AWeber Extension allows you to send your subscribe form and form builder submissions directly to your mailchimp account.</p>
          
            <?php echo $mailchimpExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/11.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_extensionsPage"> Drip Integration </a> </h3>
            <p>Drip Extension allows you to send your subscribe form and form builder submissions directly to your mailchimp account.</p>
          
            <?php echo $mailchimpExtLink; ?>
          </div>
        </div>
        <div class="pb_ext-card">
          <div class="pb_extImg_container"> <img src="<?php echo SMFB_PLUGIN_URL.'/images/extension-icons/12.png' ?>"> </div>
           <div style="height: 180px;"> 
            <h3> <a href="https://pluginops.com/page-builder/?ref=Optin_extensionsPage"> ConvertKit Integration </a> </h3>
            <p>ConvertKit Extension allows you to send your subscribe form and form builder submissions directly to your mailchimp account.</p>
          
            <?php echo $mailchimpExtLink; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style type="text/css">

  .pb_ext-card{
    display: inline-block;
    max-width:500px;
    max-height:500px;
    background: #fff;
    border:1px solid #ddd;
    text-align: center;
    margin-right: 25px;
    margin-bottom: 60px;
    padding-bottom: 30px;
  }

  .pb_ext-card a {
    text-decoration: none;
  }

  .pb_ext-card .ext_cta{
    border: none;
    padding: 10px 30px 10px 30px;
    font-size: 17px;
    color: #fff;
    background: #FF9800;
    cursor: pointer;
    margin: 10px 0 5px 0;
    border-radius: 5px;
    font-weight: 500;
    letter-spacing: 3px;
  }

  .pb_ext-card .ext_cta:hover{
    background: #ffb445;
  }

  .pb_ext-card img {
    max-width:40% !important;
  }

  .pb_ext-card p {
    margin: 5px;
  }

  .pb_extImg_container {
    width: 100%;
    background: rgba(109, 150, 255, 1);
  }
  .ext_cta_installed{
    border: 2px solid #FF9800;
    padding: 10px 30px 10px 30px;
    font-size: 17px;
    color: #FF9800;
    background: #ffffff;
    cursor: pointer;
    margin: 10px 0 5px 0;
    border-radius: 5px;
    font-weight: 500;
    letter-spacing: 3px;
  }
  body{
    background: #F3F6F8 !important;
  }
</style>

<script type="text/javascript">
    jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
 
        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });
</script>