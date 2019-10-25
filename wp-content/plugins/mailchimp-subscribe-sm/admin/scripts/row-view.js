( function( $ ) { 


const setUpdatedOptsObject = (obj, path, val) => { 
    const keys = path.split('.');
    const lastKey = keys.pop();
    const lastObj = keys.reduce((obj, key) => 
        obj[key] = obj[key] || {}, 
        obj); 
    lastObj[lastKey] = val;
};

function mergeNonsetObjectKeys(source, target) {
    Object.keys(target).forEach(function (k) {
      if (typeof source[k] === 'undefined') {
        source[k] = target[k];
      }
    });
}

function POPB_strip(html) {
        html = html.replace(/<br>/g, "$br$");
        
        html = html.replace(/<div>/g, "$br$");
        
        html = html.replace(/(?:\r\n|\r|\n)/g, '$br$');
        
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        html = tmp.textContent || tmp.innerText;
        
        html = html.replace(/\$br\$/g, "<br>");
        
        return html;
}

var WidgetDraggedAttr = '';
var isRenderDropLoop = false;
var checkIEUL = false;
pageBuilderApp.RowView = Backbone.View.extend({
    tagName: 'section',
    className: 'row',
    template: _.template($('#item-template').html()),
    events: {
      'click #rowDelete': 'deleteRow',
      'click #rowEdit': 'EditRow',
      'click #rowDuplicate': 'DuplicateRow',
      'click #editRowSave': 'updateRow',
      'click #setGlobalRow': 'setGlobalRow',
      'click #copySectionOps': 'copySectionOps',
      'click #pasteSectionOps': 'pasteSectionOps',
      'click #WidthSave': 'updateWidth',
      'click #RowHeightSave': 'updateRowHeight',
      'click div.editColumn': 'EditColumn',
      'click #copyColumnOps': 'copyColumnOps',
      'click #pasteColumnOps': 'pasteColumnOps',
      'click #flipColumns': 'flipColumns',
      'click #editColumnSave': 'updateColumn',
      'click #editColumnSaveWidget': 'updateColumnWidgetTrigger',
      'click .draggableWidgets': 'widgetDrag',
      'click .wdgt-dropped': 'widgetDropped',
      'click .wdgt-colChange': 'widgetColChange',
      'click .wdgt-dragRemove': 'widgetDragRemove',
      'click .widgetDeleteHandle': 'deleteWidget',
      'click .widgetDuplicateHandle': 'duplicateWidget',
      'click .widgetEditHandle' : 'editWidget',
      'click .widgetCopyHandle': 'copyWidgetOps',
      'click .widgetPasteHandle': 'pasteWidgetOps',
      'dblclick .po_widgets' : 'editWidgetTrigger',
      'click .addNewRow' : 'addNewRow',
      'click .addNewGlobalRow' : 'addNewGlobalRow',
      'click .addNewRowBlock' : 'addNewRowBlock',
      'click .setColbtn' : 'setColumnsOfThisModel',
      'click .inlineEditingSaveTrigger': 'widgetInlineEditorSave',
    },
    attributes: function() {
        if(this.model) {
            return {
                RowID: this.model.get('rowID')
            }
        }
        return {}
    },
    initialize: function(){
      _.bindAll(this, 'render','deleteRow','updateRow','EditRow','EditColumn','copyColumnOps','pasteColumnOps','flipColumns','updateColumn','updateWidth','DuplicateRow','widgetDrag','widgetDropped','widgetColChange','setGlobalRow','copySectionOps','pasteSectionOps','addNewRow','addNewGlobalRow','addNewRowBlock','setColumnsOfThisModel','widgetInlineEditorSave','updateRowHeight','deleteWidgetFromList','updateColumnWidgetTrigger','copyWidgetOps','pasteWidgetOps');
    },
    render: function(){
      this.$el.html(this.template(this.model.toJSON() )  );
      var rowCID = this.model.cid;

      if (thisPostType == 'ulpb_global_rows') {
        $('.newRowBtnContainerVisible').remove();
      }
      else{

        var ifIsGlobal = this.model.get('globalRow');
        if (typeof(ifIsGlobal) != 'undefined') {

          if (ifIsGlobal['isGlobalRow'] == true) {

            getGlobalRowDataFromDb(ifIsGlobal['globalRowPid']);

            retrievedGlobalRowAttributes = $('.globalRowRetrievedAttributes').val();
            
            if (retrievedGlobalRowAttributes != '') {
              this.model.set(JSON.parse(retrievedGlobalRowAttributes) );
            }
            

            rowHeight = this.model.get('rowHeight');
            rowHeightUnit = this.model.get('rowHeightUnit');

            if (typeof(rowHeightUnit) == 'undefined' || rowHeightUnit == '') {
              rowHeightUnit = 'px';
            }else{
              rowHeightUnit = this.model.get('rowHeightUnit');
            }

            $('li[data-model-cid="'+rowCID+'"] section').append('<div class="globalRowOverlay" style=" position:absolute; height:100%; z-index:9; "> <br><br><br> <h3 style="color:#fff;"> This is a Global Row </h3> <br> <a href="'+admURL+'/post.php?post='+ifIsGlobal['globalRowPid']+'&action=edit"  target="_blank" >You can edit it here </a> </div>');

            $('li[data-model-cid="'+rowCID+'"]').mouseenter(function(){
              $('li[data-model-cid="'+rowCID+'"] section .globalRowOverlay').css('display', 'block');
            });
            $('li[data-model-cid="'+rowCID+'"]').mouseleave(function(){
              $('li[data-model-cid="'+rowCID+'"] section .globalRowOverlay').css('display', 'none');
            });

          }
        }
      }

      var rowID = this.model.get('rowID');
      var rowCID = this.model.cid;
      rowColumns = this.model.get('columns');
      rowHeight = this.model.get('rowHeight');
      rowHeightUnit = this.model.get('rowHeightUnit');
      var rowData = this.model.get('rowData');
      var row_bg_img = rowData['bg_img'];
      var row_bg_color = rowData['bg_color'];
      var row_margin = rowData['margin'];
      var row_padding = rowData['padding'];
      var custom_styling = rowData['customStyling'];
      var custom_JS = rowData['customJS'];

      if (typeof(rowHeightUnit) == 'undefined' || rowHeightUnit == '') {
          rowHeightUnit = 'px';
      }else{
          rowHeightUnit = this.model.get('rowHeightUnit');
      }

      var rowHideOnDesktop ="'display':'block'", rowHideOnTablet = "'display':'block'", rowHideOnMobile = "'display':'block'";
      if (typeof(rowData['rowHideOnDesktop']) !== 'undefined' ) {
        if (rowData['rowHideOnDesktop'] == 'hide') {
          rowHideOnDesktop = "display:'none' ,";
        }

        if (rowData['rowHideOnTablet'] == 'hide') {
          rowHideOnTablet = "display:'none' ,";
        }
        if (rowData['rowHideOnMobile'] == 'hide') {
          rowHideOnMobile = "display:'none' ,";
        }
      }

      var currRowDefaultMarginPadding  = ''+
        '<script>'+
        "jQuery('.responsiveBtn').live('click',function(){"+
        " if (jQuery(this).hasClass('rbt-l') ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+row_margin['rowMarginTop']+"%', 'margin-bottom':'"+row_margin['rowMarginBottom']+"%', 'margin-left':'"+row_margin['rowMarginLeft']+"%', 'margin-right':'"+row_margin['rowMarginRight']+"%', });"+
        "  jQuery('#"+rowID+"').css({'padding-top':'"+row_padding['rowPaddingTop']+"%', 'padding-bottom':'"+row_padding['rowPaddingBottom']+"%', 'padding-left':'"+row_padding['rowPaddingLeft']+"%', 'padding-right':'"+row_padding['rowPaddingRight']+"%', "+rowHideOnDesktop+" });"+
        " }"+
        "});"+
        "var currentVPS = jQuery('.currentViewPortSize').val();"+
        "if ( currentVPS == 'rbt-l' ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+row_margin['rowMarginTop']+"%', 'margin-bottom':'"+row_margin['rowMarginBottom']+"%', 'margin-left':'"+row_margin['rowMarginLeft']+"%', 'margin-right':'"+row_margin['rowMarginRight']+"%', });"+
        "  jQuery('#"+rowID+"').css({'padding-top':'"+row_padding['rowPaddingTop']+"%', 'padding-bottom':'"+row_padding['rowPaddingBottom']+"%', 'padding-left':'"+row_padding['rowPaddingLeft']+"%', 'padding-right':'"+row_padding['rowPaddingRight']+"%', "+rowHideOnDesktop+" });"+
        "}"+
        " "+
        '</script> ';

      if (typeof(rowData['rowCustomClass']) !== 'undefined' ) {
        $('li[data-model-cid="'+rowCID+'"] section').addClass(rowData['rowCustomClass']);
      }

      if (typeof(this.model.get('rowHeightTablet')) !== 'undefined') {
        rowHeightTablet = this.model.get('rowHeightTablet');
        rowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
        rowHeightMobile = this.model.get('rowHeightMobile');
        rowHeightUnitMobile = this.model.get('rowHeightUnitMobile');
      }else{
        rowHeightTablet ='';
        rowHeightUnitTablet ='';
        rowHeightMobile ='';
        rowHeightUnitMobile ='';
      }


      var currRowMarginTablet = '';
      var currRowMarginMobile = '';
      var currRowMarginMobile = ''+
        '<script>'+
          "jQuery('.responsiveBtn').live('click',function(){"+
          " if (jQuery(this).hasClass('rbt-s') || jQuery(this).hasClass('rbt-m') ) { "+
          "  jQuery('#"+rowID+"').css({'margin':'0 auto', });"+

          "  jQuery('#"+rowID+"').css({'padding':'10px 0', });"+
          " }"+
          "});"+
          "var currentVPS = jQuery('.currentViewPortSize').val();"+
          "if ( currentVPS == 'rbt-s' || currentVPS == 'rbt-m' ) { "+
          "  jQuery('#"+rowID+"').css({'margin':'0 auto', });"+

          "  jQuery('#"+rowID+"').css({'padding':'10px 0', });"+
          "}"+
          " "+
        '</script> ';

      if (typeof(rowData['marginTablet']) !== 'undefined' ) {
        rowMarginTablet = rowData['marginTablet'];
        rowPaddingTablet = rowData['paddingTablet'];

        if (rowMarginTablet['rMTT'] == '') {  rowMarginTablet['rMTT'] = '0';  }
        if (rowMarginTablet['rMBT'] == '') {  rowMarginTablet['rMBT'] = '0';  }
        if (rowMarginTablet['rMLT'] == '') {  rowMarginTablet['rMLT'] = '0';  }
        if (rowMarginTablet['rMRT'] == '') {  rowMarginTablet['rMRT'] = '0';  }

        if (rowPaddingTablet['rPTT'] == '') {  rowPaddingTablet['rPTT'] = '1.5';  }
        if (rowPaddingTablet['rPBT'] == '') {  rowPaddingTablet['rPBT'] = '1.5';  }
        if (rowPaddingTablet['rPLT'] == '') {  rowPaddingTablet['rPLT'] = '1.5';  }
        if (rowPaddingTablet['rPRT'] == '') {  rowPaddingTablet['rPRT'] = '1.5';  }

        var currRowMarginTablet  = ''+
          '<script>'+
          "jQuery('.responsiveBtn').live('click',function(){"+
          " if (jQuery(this).hasClass('rbt-m') ) { "+
          "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginTablet['rMTT']+"%', 'margin-bottom':'"+rowMarginTablet['rMBT']+"%', 'margin-left':'"+rowMarginTablet['rMLT']+"%', 'margin-right':'"+rowMarginTablet['rMRT']+"%',  "+rowHideOnTablet+" });"+

          "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingTablet['rPTT']+"%', 'padding-bottom':'"+rowPaddingTablet['rPBT']+"%', 'padding-left':'"+rowPaddingTablet['rPLT']+"%', 'padding-right':'"+rowPaddingTablet['rPRT']+"%', });"+
          " }"+
          "});"+
          "var currentVPS = jQuery('.currentViewPortSize').val();"+
          "if ( currentVPS == 'rbt-m' ) { "+
          "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginTablet['rMTT']+"%', 'margin-bottom':'"+rowMarginTablet['rMBT']+"%', 'margin-left':'"+rowMarginTablet['rMLT']+"%', 'margin-right':'"+rowMarginTablet['rMRT']+"%', "+rowHideOnTablet+" });"+

          "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingTablet['rPTT']+"%', 'padding-bottom':'"+rowPaddingTablet['rPBT']+"%', 'padding-left':'"+rowPaddingTablet['rPLT']+"%', 'padding-right':'"+rowPaddingTablet['rPRT']+"%', });"+
          "}"+
          " "+
          '</script> ';
      }

      if (typeof(rowData['marginMobile']) !== 'undefined' ) {
        rowMarginMobile = rowData['marginMobile'];
        rowPaddingMobile = rowData['paddingMobile'];

        if (rowMarginMobile['rMTM'] == '') {  rowMarginMobile['rMTM'] = '0';  }
        if (rowMarginMobile['rMBM'] == '') {  rowMarginMobile['rMBM'] = '0';  }
        if (rowMarginMobile['rMLM'] == '') {  rowMarginMobile['rMLM'] = '0';  }
        if (rowMarginMobile['rMRM'] == '') {  rowMarginMobile['rMRM'] = '0';  }

        if (rowPaddingMobile['rPTM'] == '') {  rowPaddingMobile['rPTM'] = '1.5';  }
        if (rowPaddingMobile['rPBM'] == '') {  rowPaddingMobile['rPBM'] = '1.5';  }
        if (rowPaddingMobile['rPLM'] == '') {  rowPaddingMobile['rPLM'] = '1.5';  }
        if (rowPaddingMobile['rPRM'] == '') {  rowPaddingMobile['rPRM'] = '1.5';  }

        var currRowMarginMobile  = ''+
        '<script>'+
        "jQuery('.responsiveBtn').live('click',function(){"+
        " if (jQuery(this).hasClass('rbt-s') ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginMobile['rMTM']+"%', 'margin-bottom':'"+rowMarginMobile['rMBM']+"%', 'margin-left':'"+rowMarginMobile['rMLM']+"%', 'margin-right':'"+rowMarginMobile['rMRM']+"%', "+rowHideOnMobile+" });"+

        "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingMobile['rPTM']+"%', 'padding-bottom':'"+rowPaddingMobile['rPBM']+"%', 'padding-left':'"+rowPaddingMobile['rPLM']+"%', 'padding-right':'"+rowPaddingMobile['rPRM']+"%', });"+
        " }"+
        "});"+
        "var currentVPS = jQuery('.currentViewPortSize').val();"+
        "if ( currentVPS == 'rbt-s' ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginMobile['rMTM']+"%', 'margin-bottom':'"+rowMarginMobile['rMBM']+"%', 'margin-left':'"+rowMarginMobile['rMLM']+"%', 'margin-right':'"+rowMarginMobile['rMRM']+"%', "+rowHideOnMobile+" });"+

        "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingMobile['rPTM']+"%', 'padding-bottom':'"+rowPaddingMobile['rPBM']+"%', 'padding-left':'"+rowPaddingMobile['rPLM']+"%', 'padding-right':'"+rowPaddingMobile['rPRM']+"%', });"+
        "}"+
        " "+
        '</script> ';

      }


      var currentRowResponsiveTriggerScripts = '\n'+ currRowMarginTablet + '\n' + currRowMarginMobile + '\n' +currRowDefaultMarginPadding;

      var currRowPadding  = 'padding:'+row_padding['rowPaddingTop'] +'% '+row_padding['rowPaddingRight']+'% '+ row_padding['rowPaddingBottom'] +'% '+ row_padding['rowPaddingLeft']+'%; ';
      var currRowMargins  = 'margin:'+row_margin['rowMarginTop'] +'% '+row_margin['rowMarginRight']+'% '+ row_margin['rowMarginBottom'] +'% '+ row_margin['rowMarginLeft']+'%; ';

      var rowBackgroundOptions = 'background-color:'+row_bg_color+';';

      rowBackgroundParallax = '';
      if (typeof(rowData['rowBackgroundParallax']) !== 'undefined') {
        if (rowData['rowBackgroundParallax'] == 'true') {
          rowBackgroundParallax = 'background-attachment:fixed;';
        }
      }
      if (row_bg_img != '') {
        rowBackgroundOptions = 'background: url('+row_bg_img+') no-repeat center center; background-size: cover;';
      }

      if (typeof(rowData['rowBackgroundType']) !== 'undefined') {
        if (rowData['rowBackgroundType'] == 'gradient') {
          var rowGradient = rowData['rowGradient'];

          if (rowGradient['rowGradientType'] == 'linear') {
            rowBackgroundOptions = 'background: linear-gradient('+rowGradient['rowGradientAngle']+'deg, '+rowGradient['rowGradientColorFirst']+' '+rowGradient['rowGradientLocationFirst']+'%,'+rowGradient['rowGradientColorSecond']+' '+rowGradient['rowGradientLocationSecond']+'%);';
          }

          if (rowGradient['rowGradientType'] == 'radial') {
            rowBackgroundOptions = 'background: radial-gradient(at '+rowGradient['rowGradientPosition']+', '+rowGradient['rowGradientColorFirst']+' '+rowGradient['rowGradientLocationFirst']+'%,'+rowGradient['rowGradientColorSecond']+' '+rowGradient['rowGradientLocationSecond']+'%);';
          }
          
        }
      }

      var thisRowHoverStyleTag = '';
      var thisRowHoverOption = '';
      if (typeof(rowData['rowHoverOptions']) !== 'undefined') {
        var rowHoverOptions = rowData['rowHoverOptions'];
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'solid') {
          var thisRowHoverOption = ' #'+rowID+':hover { background:'+rowHoverOptions['rowBgColorHover']+' !important; transition: all '+rowHoverOptions['rowHoverTransitionDuration']+'s; }';
        }
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'gradient') {
          var rowGradientHover = rowHoverOptions['rowGradientHover'];

          if (rowGradientHover['rowGradientTypeHover'] == 'linear') {
            thisRowHoverOption = ' #'+rowID+':hover { background: linear-gradient('+rowGradientHover['rowGradientAngleHover']+'deg, '+rowGradientHover['rowGradientColorFirstHover']+' '+rowGradientHover['rowGradientLocationFirstHover']+'%,'+rowGradientHover['rowGradientColorSecondHover']+' '+rowGradientHover['rowGradientLocationSecondHover']+'%) !important; transition: all '+rowHoverOptions['rowHoverTransitionDuration']+'s; }';
          }

          if (rowGradientHover['rowGradientTypeHover'] == 'radial') {

            thisRowHoverOption = ' #'+rowID+':hover { background: radial-gradient(at '+rowGradientHover['rowGradientPositionHover']+', '+rowGradientHover['rowGradientColorFirstHover']+' '+rowGradientHover['rowGradientLocationFirstHover']+'%,'+rowGradientHover['rowGradientColorSecondHover']+' '+rowGradientHover['rowGradientLocationSecondHover']+'%) !important; transition: all '+rowHoverOptions['rowHoverTransitionDuration']+'s; }';
          }
        }

        thisRowHoverStyleTag = '<style> '+thisRowHoverOption+' </style>';
      }

      rowOverlayBackgroundOptions = '';
      if (typeof(rowData['rowBgOverlayColor']) !== 'undefined') {
        var rowOverlayBackgroundOptions = 'background:'+rowData['rowBgOverlayColor']+'; background-color:'+rowData['rowBgOverlayColor']+';';
      }
      
      if (typeof(rowData['rowOverlayBackgroundType']) !== 'undefined') {
        if (rowData['rowOverlayBackgroundType'] == 'gradient') {
          var rowOverlayGradient = rowData['rowOverlayGradient'];

          if (rowOverlayGradient['rowOverlayGradientType'] == 'linear') {
            rowOverlayBackgroundOptions = 'background: linear-gradient('+rowOverlayGradient['rowOverlayGradientAngle']+'deg, '+rowOverlayGradient['rowOverlayGradientColorFirst']+' '+rowOverlayGradient['rowOverlayGradientLocationFirst']+'%,'+rowOverlayGradient['rowOverlayGradientColorSecond']+' '+rowOverlayGradient['rowOverlayGradientLocationSecond']+'%);';
          }

          if (rowOverlayGradient['rowOverlayGradientType'] == 'radial') {
            rowOverlayBackgroundOptions = 'background: radial-gradient(at '+rowOverlayGradient['rowOverlayGradientPosition']+', '+rowOverlayGradient['rowOverlayGradientColorFirst']+' '+rowOverlayGradient['rowOverlayGradientLocationFirst']+'%,'+rowOverlayGradient['rowOverlayGradientColorSecond']+' '+rowOverlayGradient['rowOverlayGradientLocationSecond']+'%);';
          }
          
        }
      }

      $(this.el).attr('style','height:auto; overflow:visible; '+rowBackgroundOptions+' '+rowBackgroundParallax+' '+currRowMargins+' '+currRowPadding +custom_styling);

      $(this.el).attr('id',rowID);

      var rennderredShapeTopScripts = '';
      if (typeof(rowData['bgSTop']) != 'undefined') {
        var bgSTop = rowData['bgSTop'];
        var shapeType = bgSTop['rbgstType'];
        var rennderredShapeTop = bgshapessvgrender(rowID, shapeType, false, bgSTop, 'false' );

        jQuery(this.el).append(rennderredShapeTop['html']);
        rennderredShapeTopScripts =  rennderredShapeTop['scripts'];

      }

      var rennderredShapeBottomScripts = '';
      if (typeof(rowData['bgSBottom']) != 'undefined') {
        var bgSBottom = rowData['bgSBottom'];
        var shapeType = bgSBottom['rbgsbType'];
        bgSTop = 'false';
        var rennderredShapeBottom = bgshapessvgrender(rowID, shapeType, true, 'false', bgSBottom );

        jQuery(this.el).append(rennderredShapeBottom['html']);
        rennderredShapeBottomScripts = rennderredShapeBottom['scripts'];

      }




      var VideoBgHtml = '';
      var VideoBgStyling = '';
      if (  typeof(rowData['video']) != 'undefined' )  {
        var rowVideo = rowData['video'];
        if (typeof(rowVideo['rowBgVideoEnable']) == 'undefined') {
          rowVideo['rowBgVideoEnable'] = '';
        }
        rowBgVideoEnable = rowVideo['rowBgVideoEnable'];
        if (rowBgVideoEnable == 'true') {
          rowBgVideoLoop = rowVideo['rowBgVideoLoop'];
          rowVideoMpfour = rowVideo['rowVideoMpfour'];
          rowVideoWebM = rowVideo['rowVideoWebM'];
          rowVideoThumb = rowVideo['rowVideoThumb'];

          rowVideoID = 'bgVid-'+rowID;
          
          var VideoBgHtml = '<video poster="'+rowVideoThumb+'" id="'+rowVideoID+'" playsinline autoplay muted '+rowBgVideoLoop+' > <source src="'+rowVideoWebM+'" type="video/webm"> <source src="'+rowVideoMpfour+'" type="video/mp4"> </video>';

          var ifVideoMp4RowStyles = '#'+rowID+' {overflow:hidden !important; position:relative; }';

          if (typeof(rowVideo['rowVideoType']) != 'undefined') {
            if (rowVideo['rowVideoType'] == 'yt') {
              ytvidId = ytVidURLParser(rowVideo['rowVideoYtUrl']);
              var VideoBgHtml = '<iframe id="'+rowVideoID+'" width="100%" height="100%" src="https://www.youtube.com/embed/'+ytvidId+'?rel=0&amp;controls=0&amp;showinfo=0;mute=1;autoplay=1&loop=1&playlist='+ytvidId+'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>';
              ifVideoMp4RowStyles = '';
            }
          }

          var VideoBgStyling = '<div class="rowBgVidStyles"> <style type="text/css">#'+rowVideoID+' { position: absolute; min-width: 100%; min-height: 100%;background: url("'+rowVideoThumb+'") no-repeat;background-size: cover;transition: 1s opacity; left:0; right:0; top: 0; } '+ifVideoMp4RowStyles+' </style> </div>';

          $(this.el).prepend(VideoBgHtml + VideoBgStyling);

        }
        
      }


      $(this.el).append(' <div class="row-overlay" style="height:100%; '+rowOverlayBackgroundOptions+' top:0; left: 0; width: 100%; position:absolute;"></div> <div id="ulpb_row_controls" class="ulpb_row_controls" style="display:none;"> <div id="edit_form_close" class="btn-red btn" style="display:none;"><span class="dashicons dashicons-no-alt"></span></div><div id="editRowSave" style="display:none;"><span style="padding: 25px 0px 25px 0px;background: transparent;border-radius: 5px;display:unset;font-size: 35px;color: #585858;border-top-left-radius: 0;border-bottom-left-radius: 0;" class="dashicons dashicons-arrow-left"></span> </div>  <div id="editRowSaveVisible" class=""><span class="dashicons dashicons-arrow-left editSaveVisibleIcon"></span> </div>  </div> </div></div> <div id="thisRowScripts" style="display:none !important;">'+ currentRowResponsiveTriggerScripts + rennderredShapeTopScripts + rennderredShapeBottomScripts + thisRowHoverStyleTag +'</div>');

      

    
      
      colControlsArray = [];
      for(var i = 1; i <= rowColumns; i++){
        var this_column = 'column'+i;
        var thisColumnModelData = this.model.get(this_column);
        if (typeof(thisColumnModelData) == 'undefined' ) {
          continue;
        }
        var this_column_options = thisColumnModelData['columnOptions'];
        var this_column_bg_color = this_column_options['bg_color'];
        var this_column_margin = this_column_options['margin'];
        var this_column_padding = this_column_options['padding'];
        var colWidth = this_column_options['width'];
        var columnCSS = this_column_options['columnCSS'];
        //var colWidthInPx = Math.floor( (1268*colWidth)/100);
        var columnMarginTop = this_column_margin['columnMarginTop'];
        var columnMarginRight = this_column_margin['columnMarginRight'];
        var columnMarginBottom = this_column_margin['columnMarginBottom'];
        var columnMarginLeft = this_column_margin['columnMarginLeft'];

        var columnPaddingTop = this_column_padding['columnPaddingTop'];
        var columnPaddingRight = this_column_padding['columnPaddingRight'];
        var columnPaddingBottom = this_column_padding['columnPaddingBottom'];
        var columnPaddingLeft = this_column_padding['columnPaddingLeft'];

        var this_column_margins = "margin:"+columnMarginTop+"% "+columnMarginRight+"% "+columnMarginBottom+"% "+columnMarginLeft+"%;   padding:"+columnPaddingTop+"% "+columnPaddingRight+"% "+columnPaddingBottom+"% "+columnPaddingLeft+"%;";

        this_col_shadow = '';
        if (typeof(this_column_options['colBoxShadow']) !== 'undefined') {
          colBoxShadow = this_column_options['colBoxShadow'];
          var this_col_shadow = 'box-shadow: '+colBoxShadow['colBoxShadowH']+'px  '+colBoxShadow['colBoxShadowV']+'px  '+colBoxShadow['colBoxShadowBlur']+'px '+colBoxShadow['colBoxShadowColor']+' ;  ';
        }
        this_col_border = '';
        if (typeof(this_column_options['colBorder']) !== 'undefined') {
          colBorder = this_column_options['colBorder'];
          var this_col_border =' border:'+colBorder['colBorderWidth']+'px  '+colBorder['colBorderStyle']+'  '+colBorder['colBorderColor']+ '; border-radius:'+colBorder['colBorderRadius']+'px;';
        }
        this_col_shadow = this_col_shadow + this_col_border;

        var colWidthUnit = '%';
        if (colWidth == "" || colWidth == " ") {
          switch (rowColumns) {
          case '1':
            colWidth = 100;
            break;
          case '2':
            colWidth = 49;
            break;
          case '3':
            colWidth = 33;
            break;
          case '4':
            colWidth = 24;
            break;
          case '5':
            colWidth = 19;
            break;
          case '6':
            colWidth = 16.5;
            break;
          case '7':
            colWidth = 14.1;
            break;
          case '8':
            colWidth = 12;
            break;
          case '9':
            colWidth = 11;
            break;
          case '10':
            colWidth = 9.5;
            break;  
          default:
            colWidth = 99;
            break;
          }
        }

        if ( parseInt(columnPaddingLeft) > 0 ) {  colWidth = parseInt(colWidth) - parseInt(columnPaddingLeft) }
        if ( parseInt(columnPaddingRight) > 0 ) {  colWidth = parseInt(colWidth) - parseInt(columnPaddingRight) }

          columnCustomClass = '';
        if (typeof(this_column_options['columnCustomClass']) !== 'undefined') {
          columnCustomClass = this_column_options['columnCustomClass'];
        }

        var colHideOnDesktop ="'display':'inline-block' ,", colHideOnTablet = "'display':'inline-block' ,", colHideOnMobile = "'display':'inline-block' ,";
        if (typeof(this_column_options['colHideOnDesktop']) !== 'undefined' ) {
          if (this_column_options['colHideOnDesktop'] == 'hide') {
            colHideOnDesktop = "display:'none' ,";
          }

          if (this_column_options['colHideOnTablet'] == 'hide') {
            colHideOnTablet = "display:'none' ,";
          }
          if (this_column_options['colHideOnMobile'] == 'hide') {
            colHideOnMobile = "display:'none' ,";
          }
        }

        colContentAlignD = '' , colContentAlignT = '', colContentAlignM = '';
        if (typeof(this_column_options['colCAD']) != 'undefined' ) {

          if (this_column_options['colCAD'] != 'default' && this_column_options['colCAD'] != '') {
            if (colHideOnDesktop != "display:'none' ,") {
              colContentAlignD = "display: 'flex' , 'justify-content': '"+this_column_options['colCAD']+"' , 'flex-direction': 'column' ,";
            }
          }

          if (this_column_options['colCAT'] != 'default' && this_column_options['colCAT'] != '') {
            if (colHideOnTablet != "display:'none' ,") {
              colContentAlignT = "display: 'flex' , 'justify-content': '"+this_column_options['colCAT']+"' , 'flex-direction': 'column' ,";
            }
          }

          if (this_column_options['colCAM'] != 'default' && this_column_options['colCAM'] != '') {
            if (colHideOnMobile != "display:'none' ,") {
              colContentAlignM = "display: 'flex' , 'justify-content': '"+this_column_options['colCAM']+"' , 'flex-direction': 'column' ,";
            }
          }

        }
        

        currColmarginDefault = '';
        currColmarginTablet = '';
        currColmarginMobile = '';

        var currColmarginDefault  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-l') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+columnMarginTop+"%', 'margin-bottom':'"+columnMarginBottom+"%', 'margin-left':'"+columnMarginLeft+"%', 'margin-right':'"+columnMarginRight+"%', 'min-height':'"+rowHeight+rowHeightUnit+"',  "+colHideOnDesktop+colContentAlignD+"});"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+columnPaddingTop+"%', 'padding-bottom':'"+columnPaddingBottom+"%', 'padding-left':'"+columnPaddingLeft+"%', 'padding-right':'"+columnPaddingRight+"%', 'width':'"+colWidth+colWidthUnit+"', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-l' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+columnMarginTop+"%', 'margin-bottom':'"+columnMarginBottom+"%', 'margin-left':'"+columnMarginLeft+"%', 'margin-right':'"+columnMarginRight+"%', 'min-height':'"+rowHeight+rowHeightUnit+"', "+colHideOnDesktop+colContentAlignD+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+columnPaddingTop+"%', 'padding-bottom':'"+columnPaddingBottom+"%', 'padding-left':'"+columnPaddingLeft+"%', 'padding-right':'"+columnPaddingRight+"%', 'width':'"+colWidth+colWidthUnit+"', });"+
            "}"+
            " "+
            '</script> ';

        if (typeof(this_column_options['paddingTablet']) !== 'undefined') {

          colPaddingTablet = this_column_options['paddingTablet'];
          colMarginTablet = this_column_options['marginTablet'];
          colWidthTablet = this_column_options['widthTablet'];

          if (colWidthTablet == '') {
            colWidthTablet = '99.9';
          }

          if (colWidthTablet != '') {
            if ( parseInt(colPaddingTablet['rPLT']) > 0 ) {  colWidthTablet = parseInt(colWidthTablet) - parseInt(colPaddingTablet['rPLT']) }
            if ( parseInt(colPaddingTablet['rPRT']) > 0 ) {  colWidthTablet = parseInt(colWidthTablet) - parseInt(colPaddingTablet['rPRT']) }
          }

          var currColmarginTablet  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-m') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginTablet['rMTT']+"%', 'margin-bottom':'"+colMarginTablet['rMBT']+"%', 'margin-left':'"+colMarginTablet['rMLT']+"%', 'margin-right':'"+colMarginTablet['rMRT']+"%',  'min-height':'"+rowHeightTablet+rowHeightUnitTablet+"', "+colHideOnTablet+colContentAlignT+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingTablet['rPTT']+"%', 'padding-bottom':'"+colPaddingTablet['rPBT']+"%', 'padding-left':'"+colPaddingTablet['rPLT']+"%', 'padding-right':'"+colPaddingTablet['rPRT']+"%', 'width':'"+colWidthTablet+"%', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-m' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginTablet['rMTT']+"%', 'margin-bottom':'"+colMarginTablet['rMBT']+"%', 'margin-left':'"+colMarginTablet['rMLT']+"%', 'margin-right':'"+colMarginTablet['rMRT']+"%', 'min-height':'"+rowHeightTablet+rowHeightUnitTablet+"', "+colHideOnTablet+colContentAlignT+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingTablet['rPTT']+"%', 'padding-bottom':'"+colPaddingTablet['rPBT']+"%', 'padding-left':'"+colPaddingTablet['rPLT']+"%', 'padding-right':'"+colPaddingTablet['rPRT']+"%', 'width':'"+colWidthTablet+"%', });"+
            "}"+
            " "+
            '</script> ';
          
        }

        var currColmarginMobile  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-s') || jQuery(this).hasClass('rbt-m') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+''+"%', 'margin-bottom':'"+'30px'+"', 'margin-left':'"+''+"%', 'margin-right':'"+''+"%', 'min-height':'"+ rowHeightMobile+rowHeightUnitMobile+"',});"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+''+"%', 'padding-bottom':'"+''+"%', 'padding-left':'"+''+"%', 'padding-right':'"+''+"%', 'width':'"+'99.9'+"%', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-s' || currentVPS == 'rbt-m' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+''+"%', 'margin-bottom':'"+'30px'+"', 'margin-left':'"+''+"%', 'margin-right':'"+''+"%', 'min-height':'"+ rowHeightMobile+rowHeightUnitMobile+"',});"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+''+"%', 'padding-bottom':'"+''+"%', 'padding-left':'"+''+"%', 'padding-right':'"+''+"%', 'width':'"+'99.9'+"%', });"+
            "}"+
            " "+
            '</script> ';
        if (typeof(this_column_options['paddingMobile']) !== 'undefined') {
          colPaddingMobile = this_column_options['paddingMobile'];
          colMarginMobile = this_column_options['marginMobile'];
          colWidthMobile = this_column_options['widthMobile'];

          if (colWidthMobile == '') {
            colWidthMobile = '99.9';
          }

          if (colWidthMobile != '') {
            if ( parseInt(colPaddingMobile['rPLM']) > 0 ) {  colWidthMobile = parseInt(colWidthMobile) - parseInt(colPaddingMobile['rPLM']) }
            if ( parseInt(colMarginMobile['rMRM']) > 0 ) {  colWidthMobile = parseInt(colWidthMobile) - parseInt(colMarginMobile['rMRM']) }
          }

          colMarginMobileBottomUnit = '%';
          if (colMarginMobile['rMBM'] == '') {
            colMarginMobile['rMBM'] = '';
            colMarginMobileBottomUnit = 'px';
          }

          currColmarginMobile  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-s') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginMobile['rMTM']+"%', 'margin-bottom':'"+colMarginMobile['rMBM']+colMarginMobileBottomUnit+"', 'margin-left':'"+colMarginMobile['rMLM']+"%', 'margin-right':'"+colMarginMobile['rMRM']+"%', 'min-height':'"+rowHeightMobile+rowHeightUnitMobile+"', "+colHideOnMobile+colContentAlignM+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingMobile['rPTM']+"%', 'padding-bottom':'"+colPaddingMobile['rPBM']+"%', 'padding-left':'"+colPaddingMobile['rPLM']+"%', 'padding-right':'"+colPaddingMobile['rPRM']+"%', 'width':'"+colWidthMobile+"%', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-s' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginMobile['rMTM']+"%', 'margin-bottom':'"+colMarginMobile['rMBM']+colMarginMobileBottomUnit+"', 'margin-left':'"+colMarginMobile['rMLM']+"%', 'margin-right':'"+colMarginMobile['rMRM']+"%', 'min-height':'"+rowHeightMobile+rowHeightUnitMobile+"', "+colHideOnMobile+colContentAlignM+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingMobile['rPTM']+"%', 'padding-bottom':'"+colPaddingMobile['rPBM']+"%', 'padding-left':'"+colPaddingMobile['rPLM']+"%', 'padding-right':'"+colPaddingMobile['rPRM']+"%', 'width':'"+colWidthMobile+"%', });"+
            "}"+
            " "+
            '</script> ';
        }

        var currColResponsiveScriptsTrigger = ' <div id="columnResponsiveScripts" style="display:none;">  \n ' + currColmarginTablet + ' \n ' + currColmarginMobile + ' \n ' +currColmarginDefault + '</div>';

        var colBackgroundOptions = 'background:'+this_column_bg_color+';';

        this_column_bg_img = '';
        if (typeof(this_column_options['colBgImg']) !== 'undefined') {
          this_column_bg_img = this_column_options['colBgImg'];
          if (this_column_bg_img !== '') {
            colBackgroundOptions = 'background: url('+this_column_bg_img+') no-repeat center; background-size:cover;';
          }
        }
        

        if (typeof(this_column_options['colBackgroundType']) !== 'undefined') {

          if (this_column_options['colBackgroundType'] == 'gradient') {
            var colGradient = this_column_options['colGradient'];

            if (colGradient['colGradientType'] == 'linear') {
              colBackgroundOptions = 'background: linear-gradient('+colGradient['colGradientAngle']+'deg, '+colGradient['colGradientColorFirst']+' '+colGradient['colGradientLocationFirst']+'%,'+colGradient['colGradientColorSecond']+' '+colGradient['colGradientLocationSecond']+'%);';
            }

            if (colGradient['colGradientType'] == 'radial') {
              colBackgroundOptions = 'background: radial-gradient(at '+colGradient['colGradientPosition']+', '+colGradient['colGradientColorFirst']+' '+colGradient['colGradientLocationFirst']+'%,'+colGradient['colGradientColorSecond']+' '+colGradient['colGradientLocationSecond']+'%);';
            }
            
          }
        }

        var colID = rowID+'-'+this_column;
        var thisColHoverStyleTag = '';
        var thisColHoverOption = '';
        if (typeof(this_column_options['colHoverOptions']) !== 'undefined') {
          var colHoverOptions = this_column_options['colHoverOptions'];
          if (colHoverOptions['colBackgroundTypeHover'] == 'solid') {
            var thisColHoverOption = ' #'+colID+':hover { background:'+colHoverOptions['colBgColorHover']+' !important; transition: all '+colHoverOptions['colHoverTransitionDuration']+'s; }';
          }
          if (colHoverOptions['colBackgroundTypeHover'] == 'gradient') {
            var colGradientHover = colHoverOptions['colGradientHover'];

            if (colGradientHover['colGradientTypeHover'] == 'linear') {
              thisColHoverOption = ' #'+colID+':hover { background: linear-gradient('+colGradientHover['colGradientAngleHover']+'deg, '+colGradientHover['colGradientColorFirstHover']+' '+colGradientHover['colGradientLocationFirstHover']+'%,'+colGradientHover['colGradientColorSecondHover']+' '+colGradientHover['colGradientLocationSecondHover']+'%) !important; transition: all '+colHoverOptions['colHoverTransitionDuration']+'s; }';
            }

            if (colGradientHover['colGradientTypeHover'] == 'radial') {

              thisColHoverOption = ' #'+colID+':hover { background: radial-gradient(at '+colGradientHover['colGradientPositionHover']+', '+colGradientHover['colGradientColorFirstHover']+' '+colGradientHover['colGradientLocationFirstHover']+'%,'+colGradientHover['colGradientColorSecondHover']+' '+colGradientHover['colGradientLocationSecondHover']+'%) !important; transition: all '+colHoverOptions['colHoverTransitionDuration']+'s; }';
            }
          }

          thisColHoverStyleTag = '<div id="columnStyleTag" style="display:none;"> <style> '+thisColHoverOption+' </style> </div> ';
        }

        var colEditBtn = "<div class='editColumn editColBtnTop'  data-col_id="+this_column+" data-overlay_id="+this_column+" ><span class='dashicons dashicons-edit' style='color:#fff;' data-col_id="+this_column+" data-overlay_id="+this_column+"></span></div>  <div id='flipColumns' class='flipColumns editColBtnTop' style='margin-left: 40px;background:#607d8b !important; ' data-col_id="+this_column+" data-colIndex="+i+" data-overlay_id="+this_column+" title='Flip with Next Column' > <i class='fa fa-exchange' data-col_id="+this_column+" data-colIndex="+i+" ></i> </div>              <div id='copyColumnOps' class='copyColumnOps editColBtnTop' style='margin-left: 72px;background:#607d8b !important; ' data-col_id="+this_column+" data-overlay_id="+this_column+" title='Copy Column Options' > Copy </div>          <div id='pasteColumnOps' class='pasteColumnOps editColBtnTop' style='margin-left: 120px; background:#607d8b !important; display:none;' data-col_id="+this_column+" data-overlay_id="+this_column+" title='Paste Column Options'> Paste </div>  "+currColResponsiveScriptsTrigger + thisColHoverStyleTag +"</div>";

        var colControls = '<div id="ulpb_column_controls" class="ulpb_column_controls  ulpb_column_controls'+this_column+'" style="display:none;" ><div id="edit_form_closeCol" style="display:none;" class="btn-red btn"><span class="dashicons dashicons-no-alt"></span></div><div id="editColumnSave" style="display:none;" data-col_id='+this_column+' ></div>  <div id="editColumnSaveWidget" style="display:none;" data-col_id='+this_column+' ></div>      <div id="editColumnSaveVisible" data-col_id='+this_column+' ><span class="dashicons dashicons-arrow-left editSaveVisibleIcon"  data-col_id='+this_column+'></span></div><br></div> \n';
        
        $(this.el).append('<div class="column '+this_column+' '+columnCustomClass+' " id='+rowID+'-'+this_column+' data-col_id='+this_column+' style="width:' + colWidth +colWidthUnit+';  min-height:'+rowHeight+rowHeightUnit+'; '+colBackgroundOptions+' '+this_column_margins+'  '+this_col_shadow +'  '+columnCSS+'"> <div id="WidthSave" class="pb_hidden"></div> <div id="RowHeightSave" class="pb_hidden"></div>  <div class="wdgt-dropped" style="display:none;" data-this_col_id='+this_column+'></div>  <div class="wdgt-colChange" style="display:none;" data-this_col_id='+this_column+'></div> '+colEditBtn+'</div>');


        $('#'+rowID+'-'+this_column).mouseenter(function(ev){
          $(this).children('.editColBtnTop').css('display','block');
          $(this).css('border','2px solid #2B87DA');
          if (pageBuilderApp.copiedColOps == '') {
            $('.pasteColumnOps').css('display','none');
          }
        });
        $('#'+rowID+'-'+this_column).mouseleave(function(){
          $('.editColBtnTop').css('display','none');
          $(this).css('border','none');
        });

        // Column Widgets
        var this_column_widgets = thisColumnModelData['colWidgets'];
        var this_column_widgets_array = _.values(this_column_widgets);

        var thisColFontsToLoad = [];

        for (var j = 0; j < this_column_widgets_array.length; j++) {
          this_column_widgets_array_C = this_column_widgets_array[j];
          var thisRenderredWidget = completeWidgetRender(this_column_widgets_array_C, j, this_column, rowID, thisColFontsToLoad);

          $('section[RowID="'+rowID+'"] '+'.'+this_column).append(thisRenderredWidget['RenderredWidgetHtmlDefault']);

          $('#'+rowID+'-'+this_column + ' .widget-Draggable').mouseenter(function(ev){
            $(this).children('.widgetHandle').css('display','block');
            if (pageBuilderApp.copiedWidgOps == '') {
              jQuery('.widgetPasteHandle').css('display','none');
            }

          });
          $('#'+rowID+'-'+this_column + ' .widget-Draggable').mouseleave(function(){ 
            $('.widgetHandle').css('display','none'); 
          });

          $('.widget-Draggable').draggable({
                helper: function(event){
                  var thisELE  = $(event.currentTarget);
                  
                  var elMarigns  = thisELE.css('margin-top') + ' ' + thisELE.css('margin-right') + ' ' +thisELE.css('margin-bottom') + ' ' +thisELE.css('margin-left');

                  return $("<div class='widgetDragHelper' style='margin:"+elMarigns+"; padding: 15px; background: #333; border-radius: 100px;'> <span class='dashicons dashicons-move' style='color:#fff;' title='Move'></span> </div>");
                }, cursor: "move", appendTo: "#container", handle:'.widgetMoveHandle',
                start: function(event,ui){
                  $(event.target).attr('style','display:none;');
                  $('.isDroppedOnDroppable').val('false');
                  $(this).children('.draggableWidgets').click();
                },
                stop: function(event,ui){
                  $('.droppableBelowWidget').hide(250);

                  var isDroppedOnDroppable = $('.isDroppedOnDroppable').val();

                  if (isDroppedOnDroppable != 'true') {
                    $(event.target).attr('style','display:block;');
                  }
                },
          });

        } // Widget Loop

        if (this_column_widgets_array.length == 0) {
        $('section[RowID="'+rowID+'"] '+'.'+this_column).append("<div class='editColumn emptyColumnIcon' style='background: #dadada;padding: 18px 4px 18px 0px;cursor: pointer;position: relative;margin: 2% auto 0px;text-align: center;max-width: 85px;border-radius: 10px;display: block;' data-col_id="+this_column+" data-overlay_id="+this_column+" title='Add Widgets'><span class='dashicons dashicons-plus' style='color:#fff; font-size:2em;' data-col_id="+this_column+" data-overlay_id="+this_column+"></span></div></div> <div id='WidthSave' class='pb_hidden'></div>");
        }

        thisColFontsToLoadSeparatedValue = 'Allerta';
        for(var w = 0; w < thisColFontsToLoad.length; w++){
          thisColFontsToLoadSeparatedNewValue = thisColFontsToLoad[w];

          if (thisColFontsToLoadSeparatedValue !== 'Select' || thisColFontsToLoadSeparatedValue !== 'Arial' || thisColFontsToLoadSeparatedValue !== 'Arial Black' || thisColFontsToLoadSeparatedValue !== 'sans-serif' || thisColFontsToLoadSeparatedValue !== 'Helvetica' || thisColFontsToLoadSeparatedValue !== 'Serif' || thisColFontsToLoadSeparatedValue !== 'Arial' || thisColFontsToLoadSeparatedValue !== 'Tahoma' || thisColFontsToLoadSeparatedValue !== 'Verdana' || thisColFontsToLoadSeparatedValue !== 'Monaco' || thisColFontsToLoadSeparatedValue !== 'select') {
            filteredFontFamilyName = '|' +thisColFontsToLoadSeparatedNewValue;
          }else{
            filteredFontFamilyName = '';
          }

          thisColFontsToLoadSeparatedValue = thisColFontsToLoadSeparatedValue + filteredFontFamilyName;
        }

        $('section[RowID="'+rowID+'"] '+'.'+this_column).append('<link rel="stylesheet"href="https://fonts.googleapis.com/css?family='+thisColFontsToLoadSeparatedValue+'">');

        colControlsArray.push(colControls);

      } // column loop

      colControlsArrayAllValues = '';
      for(var w = 0; w < colControlsArray.length; w++){
          colControlsArraySeparatedNewValue = colControlsArray[w];

          colControlsArrayAllValues = colControlsArrayAllValues + '\n' +colControlsArraySeparatedNewValue;
        }

        $(this.el).append(colControlsArrayAllValues);


      if (rowColumns < 1) {
        rowWithNoColumnContainer = $('.rowWithNoColumnContainer').html();

        $(this.el).append(rowWithNoColumnContainer);

      }


      var pbContainer = $('#container');
      var pbWrapperWidth = $('#container').width();
      var prevDoppableBgColor = '';
        $('.column').droppable({
          accept: ".widget-Draggable",
          snap:'.column',
          drop: function(event,ui){
            //$(ui.draggable).click();
            // $(".widget-Draggable").trigger("dragstop");
            var curr_droppable = $(this).attr('id');
            $('.widgetDroppedAtIndex').val('');
            $('.isDroppedOnDroppable').val('true');
           $('#'+curr_droppable +' .wdgt-dropped').click();
          },
          over: function(){
            var curr_droppable = $(this).attr('id');
            prevDoppableBgColor = $('#'+curr_droppable).css('background');
            $(this).css('background','rgba(224, 241, 255, 0.85)');
          },
          out: function(){
            var curr_droppable = $(this).attr('id');
            $('#'+curr_droppable).css('background',prevDoppableBgColor);
          }

        } );

        $('.pb_widgetr').droppable({
          accept: ".widget-Draggable, .wdt-draggable",
          snap:'.droppableBelowWidget',
          greedy: true,
          drop: function(event,ui){
            //$(ui.draggable).click();
            // $(".widget-Draggable").trigger("dragstop");
            var curr_droppable = $(this).attr('data-targetColId');
            var thisDroppableWidgetIndex = $(this).attr('data-widgetIndex');
            $('.widgetDroppedAtIndex').val(thisDroppableWidgetIndex);
              $('.isDroppedOnDroppable').val('true');

           $('#'+curr_droppable +' .wdgt-dropped').click();
          },
          over: function(){
            $(this).children('.droppableBelowWidget').css('display','block');
            $(this).children('.droppableBelowWidget').css('background','rgba(213, 249, 215, 0.85)');
          },
          out: function(){
            $(this).children('.droppableBelowWidget').css('display','none');
          }

        } );

        var PbWrapperWidth = $('#container').width();
      $('.column').resizable({
        containment: '#container',
        handles: 'e,s',
        start: function (event, ui) {
          ui.element.css('min-height', 'auto');
          resizedAxis = ui.element.data('ui-resizable').axis;
          if (resizedAxis == 'e') {
            this.widthOfNextEl = ui.originalSize.width + ui.element.next().innerWidth();
            $(this).prepend('<div style="text-align:center; color:#fff; padding:5px;" id="thisElWidth"> '+$(this).width() +' </div>');

            $( ui.element.next() ).prepend('<div style="text-align:center; color:#fff; padding:5px;" id="nextElWidth"> '+$( ui.element.next() ).width() +' </div>');
          }
            
        },
        resize: function (event, ui) {
          resizedAxis = ui.element.data('ui-resizable').axis;
          if (resizedAxis == 'e') {
            var currentPbWrapperWidth = ui.element.parent().width();
            ui.element.next().width( (this.widthOfNextEl - ui.size.width) - 1 );
            ui.element.children('.overlay').width(ui.size.width);
            ui.element.next().children('.overlay').width(ui.element.next().width());

            $(this).children('#thisElWidth').html( Math.round(ui.element.outerWidth()/ currentPbWrapperWidth * 100) + '<span>%</span>'  );
            var nextElWidthOutput = ui.element.next().width()/ currentPbWrapperWidth * 100;
            ui.element.next().children('#nextElWidth').html( Math.round(nextElWidthOutput ) + '<span>%</span>' );
          }
          if (resizedAxis == 's') {

          }
            
        },
        stop: function(event, ui) {
          resizedAxis = ui.element.data('ui-resizable').axis;

          if (resizedAxis == 'e') {
            var currentPbWrapperWidth = ui.element.parent().width();
            var colPercentageWidth = ui.element.outerWidth()/ currentPbWrapperWidth * 100;
            ui.element.css('width', colPercentageWidth + '%');
            var nextCol = ui.element.next();
            var nextColPercentWidth= nextCol.outerWidth()/ currentPbWrapperWidth * 100;
            nextCol.css('width', nextColPercentWidth + '%');
            //console.log(currentPbWrapperWidth);
            ui.element.children('.overlay').css('width','100' + '%');
            ui.element.next().children('.overlay').css('width','100' + '%');
            var thisResizedColID = $(ui.element).attr('data-col_id');
            $('.currentResizedRowColTarget').val(thisResizedColID);
            nextResizedColID = thisResizedColID.replace(/\D/g,'');
            $('.currentResizedRowColTargetNext').val(nextResizedColID);
            $(ui.element).children('#WidthSave').trigger('click');
            
            var curreViewportS = $('.currentViewPortSize').val();

            jQuery('.edit_column').hide(0);
            jQuery('.ulpb_column_controls').hide();

            $(this).children('#thisElWidth').remove();
            ui.element.next().children('#nextElWidth').remove();
          }
          if (resizedAxis == 's') {
            var currentPbWrapperHeight = $(window).height();
            var elHeight = ui.element.height();
            var colPercentageHeight = ui.element.height()/ currentPbWrapperHeight * 100;

            if (rowHeightUnit == 'px') {}
              ui.element.css('height', elHeight + 'px');

            if (curreViewportS == 'rbt-l') {
                ui.element.css('height', elHeight + 'px');
            }
            if (curreViewportS == 'rbt-m') {
                ui.element.css('height', elHeight + 'px');
            }
            if (curreViewportS == 'rbt-s') {
                ui.element.css('height', elHeight + 'px');
            }
            $('.currentResizedRowHeight').val(elHeight);

            $(ui.element).children('#RowHeightSave').trigger('click');

          }
            
        }
      });

      // Row and column buttons
      displayGButton = 'none';
      if (isGlobalRowActive == "true") {
        displayGButton = 'inline-block';
      }

      $(this.el).append('<div style=" display:block;clear:left;position: relative;z-index:99;bottom: 4px;right: 5px; "><div id="rowDelete" class="row-btn btn-red btn" title="Delete Row" ><span class="dashicons dashicons-trash"></span></div> <div id="rowEdit" class="row-btn btn"  title="Edit Row"> <span class="dashicons dashicons-edit"></span></div> <div id="rowDuplicate" class="row-btn btn" title="Duplicate"> <span class="dashicons dashicons-admin-page"></span></div> <div id="setGlobalRow" style="background:#F0D53D;diplay:none;" class="row-btn btn globalRowBtn" title="Set Global"> <span class="dashicons dashicons-networking"></span></div> <div class="pbHandle row-btn btn" style="background: rgb(45, 60, 60) !important;"><span class="dashicons dashicons-move" title="Move"></span></div>    <div class="addNewRowSection row-btn btn" style="background:#2196f3 !important;"><span class="dashicons dashicons-plus" title="Add New Section Options"></span></div>   <div id="copySectionOps" class="row-btn btn" style="background:#607D8B  !important;" title="Copy Options"> <span>Copy</span> </div>  <div id="pasteSectionOps" class="row-btn btn pasteSectionOps" style="background:#607D8B  !important; display:none;" title="Paste Section Options"> <span>Paste</span> </div>  </div>');
      
        $(this.el).append('<div class="newRowBtnContainer" >  <div class="newRowBtnContainerSections"> <div class="addNewRow  row-section-btn" style="background:#5AB1F7;" > ADD NEW SECTION</div> </div>      <div class="newRowBtnContainerSections" style="display:'+displayGButton+';">    <div class="addNewGlobalRow  row-section-btn" style="background:#F1D204; " > INSERT GLOBAL ROW</div> </div> <div class="newRowBtnContainerSections"  > <div class="addNewRowBlock  row-section-btn" style="background:#4CAF50; " > INSERT ROW BLOCK</div>   </div>  </div>   </div>');


        $('li[data-model-cid="'+rowCID+'"] .addNewRowSection').click( function(){

          $('li[data-model-cid="'+rowCID+'"] .newRowBtnContainer').css('display','block');
        } );

      $('li[data-model-cid="'+rowCID+'"]').mouseenter(function(){
        $('li[data-model-cid="'+rowCID+'"] .row-btn').css('display','block');

        if (isGlobalRowActive !== 'true') {
          $('.globalRowBtn').css('display','none');
        }
        if (pageBuilderApp.copiedSecOps == '') {
          $('.pasteSectionOps').css('display','none');
        }

        if (thisPostType == 'ulpb_global_rows') {
          jQuery('.addNewRowSection').css('display','none');
          jQuery('#rowDuplicate').css('display','none');
          jQuery('#setGlobalRow').css('display','none');
        }
        
      });
      $('.row').mouseleave(function(){

       $('.row-btn').css('display','none');
       $('.newRowBtnContainer').css('display','none');
      });

      // Save the current model
      return this;
    },
    widgetDrag: function(ev){

      var this_row = $(ev.target).attr('data-widg_row_id');
      var this_column = $(ev.target).attr('data-wid_col_id');
      var this_widget = $(ev.target).attr('data-widget_id');
      var thisColumnData = this.model.get(this_column);
      //console.log($(ev.target) );
      var this_column_widgets = thisColumnData['colWidgets'];
      var WidgetDraggedAttr = this_column_widgets[this_widget];

      $('.widgetDraggedRowId').val(this_row);
      $('.widgetDraggedColIndex').val(this_column);
      $('.widgetDraggedIndex').val(this_widget);

      /*
     this_column_widgets.splice(this_widget, 1);
      
      var thisColumnModelData = this.model.get(this_column);
        var this_column_widgets = thisColumnModelData['colWidgets'];
        var this_column_options = thisColumnModelData['columnOptions'];

        this.model.set({
          [this_column] : {
            colWidgets: this_column_widgets,
            columnOptions: this_column_options,
          }
        });
        */
      
      //$(this.el).html('');
      //$('.edit_column').slideUp();
      //$('#ulpb_column_controls').remove();

      
      jQuery('.columnWidgetPopup').hide(0);
      jQuery('.edit_column').hide(0);
      
     // this.render();

      $('.draggedWidgetAttributes').val(JSON.stringify(WidgetDraggedAttr));
       thisColumnData = this.model.get(this_column);
      
    },
    widgetDropped: function(ev){

      var widgetDroppedAttributes = $('.draggedWidgetAttributes').val();
      var widgetDroppedAtIndex = $('.widgetDroppedAtIndex').val();
      var this_column = $(ev.target).attr('data-this_col_id');
      var thisColumnData = this.model.get(this_column);
      var this_column_widgets = thisColumnData['colWidgets'];
      if (widgetDroppedAttributes != '' && typeof(widgetDroppedAttributes) !='undefined' ) {
        if (this_column_widgets == 0) {
          this_column_widgets = [];
          this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
        } else if(typeof(this_column_widgets) == 'undefined' ) {
          this_column_widgets = [];
          this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
        } else{
            if (widgetDroppedAtIndex == '') {
              this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
            } else{
              this_column_widgets.splice(widgetDroppedAtIndex, 0, JSON.parse(widgetDroppedAttributes));
            }
          
         
         // this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
        }

        
      }

        $('.widgetDroppedRowId').val( this.model.get('rowID') );
        $('.widgetDroppedColIndex').val( this_column );
        $('.widgetDroppedIndex').val( widgetDroppedAtIndex );
        var dragged_widg_row = $('.widgetDraggedRowId').val();
        var dragged_widg_col = $('.widgetDraggedColIndex').val();
        var dragged_widg_widg = $('.widgetDraggedIndex').val();

        $(".draggedRemove_"+dragged_widg_row+"_"+dragged_widg_col+"_widg_"+dragged_widg_widg+" ").trigger('click');
      //console.log(this_column_widgets);
      //console.log(WidgetDraggedAttr);
      
      //var widgets = pageBuilderApp.widgetList.toJSON();
      var thisColumnModelData = this.model.get(this_column);
        var this_column_options = thisColumnModelData['columnOptions'];

        this.model.set({
          [this_column] : {
            colWidgets: this_column_widgets,
            columnOptions: this_column_options,
          }
        });
      
      
        pageBuilderApp.widgetList.reset();
        pageBuilderApp.widgetList.add(this_column_widgets);

      $(this.el).html('');
      //$('.edit_column').slideUp();
      //$('#ulpb_column_controls').remove();
      this.render();
      jQuery('.edit_column').hide(300);
      $('.isChagesMade').val('true');
    },
    widgetColChange: function(ev){
      $(this.el).html('');
      this.render();
    },
    widgetDragRemove: function(ev){
      var droppedOnRow = $('.widgetDroppedRowId').val();
      var widgetDroppedColIndex = $('.widgetDroppedColIndex').val();
      var widgetDroppedIndex = $('.widgetDroppedIndex').val();
      var this_row = $('.widgetDraggedRowId').val();
      var this_column = $('.widgetDraggedColIndex').val();
      var this_widget = parseInt($('.widgetDraggedIndex').val() );
      

      var thisColumnData = this.model.get(this_column);
        
      var this_column_widgets = thisColumnData['colWidgets'];
      if (widgetDroppedIndex == '') {
        widgetDroppedIndex = this_widget;
      }
      if (droppedOnRow == this_row && widgetDroppedColIndex == this_column) {
        if (widgetDroppedIndex < this_widget ) {
          this_column_widgets.splice((this_widget+1), 1);
        }
        if (widgetDroppedIndex > this_widget) {
          if (this_widget != 0) {
            this_column_widgets.splice(this_widget, 1);
          }else{
            this_column_widgets.splice(this_widget, 1);
          }
        }
        if (widgetDroppedIndex == this_widget ) {
          this_column_widgets.splice(this_widget, 1);
        }
        
      }else{
        this_column_widgets.splice(this_widget, 1);
      }
      
      
      var thisColumnModelData = this.model.get(this_column);
        var this_column_widgets = thisColumnModelData['colWidgets'];
        var this_column_options = thisColumnModelData['columnOptions'];

        this.model.set({
          [this_column] : {
            colWidgets: this_column_widgets,
            columnOptions: this_column_options,
          }
        });

          $('.widgetDraggedRowId').val('');
          $('.widgetDraggedColIndex').val('');
          $('.widgetDraggedIndex').val('');
          $('.widgetDroppedRowId').val( '' );
          $('.widgetDroppedColIndex').val( '' );
          $('.widgetDroppedIndex').val('')
        $(this.el).html('');
        //$('.edit_column').slideUp();
        //$('#ulpb_column_controls').remove();
        this.render();
    },
    deleteWidget: function(ev){

      thisWidgetEl = $(ev.target );

      thisWidgetParentColID = thisWidgetEl.attr('data-parentwidgetid');
      thisWidgetIndex = parseInt( thisWidgetEl.attr('data-widget_id') );

      $('#'+thisWidgetParentColID).children('.editColumn').click();
      jQuery('.edit_column').hide(300);
      jQuery('.ulpb_column_controls').hide();

      $('#widgets li:nth-child('+(thisWidgetIndex+1)+')').children().children('.wdt-edit-controls').children('#widgetDelete').click();

      jQuery('.edit_column').hide(300);
      jQuery('.ulpb_column_controls').hide();
      
      /*
      // var confDeletion = confirm('Do you want to delete this Widget ?');
      $('.popb_confirm_action_popup').css('display','block');

      $('.popb_confirm_subMessage').text("Do you want to delete this Widget ? Deleted Items can't be restored.");
        this_column = $(ev.target).attr('data-wid_col_id');
        this_widget = $(ev.target).attr('data-widget_id');
      $('.widgDeleteColIndex').val( this_column );
      $('.widgDeleteIndex').val( this_widget );

      var thatThis = this;

      $('.confirm_no').click(function(){
        $('.popb_confirm_action_popup').css('display','none');
        $('.widgDeleteColIndex').val('');
        $('.widgDeleteIndex').val('');
      });

      $('.confirm_yes').one('click',function(){
        var this_column = $('.widgDeleteColIndex').val();
        var this_widget = $('.widgDeleteIndex').val();
        if (this_column != '' && this_widget != '') {
          thatThis.deleteWidgetFromList();
        }else{
          $('.popb_confirm_action_popup').css('display','none');
          return;
        }
        $('.widgDeleteColIndex').val('');
        $('.widgDeleteIndex').val('');
        $('.popb_confirm_action_popup').css('display','none');
      });
      */
    },
    deleteWidgetFromList: function(){

          var this_column = $('.widgDeleteColIndex').val();
          var this_widget = $('.widgDeleteIndex').val();
          if (this_column != '' && this_widget != '') {
            var thisColumnData = this.model.get(this_column);
            var this_column_widgets = thisColumnData['colWidgets'];
            var WidgetDraggedAttr = this_column_widgets[this_widget];
            this_column_widgets.splice(this_widget, 1);

              var thisColumnModelData = this.model.get(this_column);
              var this_column_widgets = thisColumnModelData['colWidgets'];
              var this_column_options = thisColumnModelData['columnOptions'];

              this.model.set({
                [this_column] : {
                  colWidgets: this_column_widgets,
                  columnOptions: this_column_options,
                }
              });
              jQuery('.columnWidgetPopup').hide(0);
            jQuery('.edit_column').hide(0);
            
            $(this.el).html('');
            this.render();
            
            $('.isChagesMade').val('true');
          }
          return;
    },
    duplicateWidget: function(ev){
      var this_column = $(ev.target).attr('data-wid_col_id');
      var this_widget = $(ev.target).attr('data-widget_id');
      var thisColumnData = this.model.get(this_column);
      var this_column_widgets = thisColumnData['colWidgets'];
      var WidgetDraggedAttr = jQuery.extend(true, {}, this_column_widgets[this_widget]);
      var currentItemIndex = this_column_widgets.indexOf(this_column_widgets[this_widget]);
     this_column_widgets.splice(currentItemIndex, 0, WidgetDraggedAttr);

      var thisColumnModelData = this.model.get(this_column);
        var this_column_widgets = thisColumnModelData['colWidgets'];
        var this_column_options = thisColumnModelData['columnOptions'];

        this.model.set({
          [this_column] : {
            colWidgets: this_column_widgets,
            columnOptions: this_column_options,
          }
        });
      
      jQuery('.columnWidgetPopup').hide(0);
      jQuery('.edit_column').hide(0);
      pageBuilderApp.widgetList.reset();
      pageBuilderApp.widgetList.add(this_column_widgets);
      $(this.el).html('');
      this.render();
      $('.isChagesMade').val('true');
    },
    editWidgetTrigger: function(ev){
      thisWidgetElParent = $(ev.target).closest('.po_widgets');
      $(thisWidgetElParent).children('.widgetEditHandle').trigger('click');
    },
    editWidget: function(ev){
      thisWidgetEl = $(ev.target );

      thisWidgetParentColID = thisWidgetEl.attr('data-parentwidgetid');
      thisWidgetIndex = parseInt( thisWidgetEl.attr('data-widget_id') );

      pageBuilderApp.currentlyEditedColId = thisWidgetParentColID;
      pageBuilderApp.currentlyEditedWidgId = thisWidgetIndex;
      pageBuilderApp.currentlyEditedThisCol = thisWidgetEl.attr('data-wid_col_id');
      pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');

      $('#'+thisWidgetParentColID).children('.editColumn').click();

      $('#widgets li:nth-child('+(thisWidgetIndex+1)+')').children().children('.wdt-edit-controls').children('#widgetEdit').click();



      $('.animateWidgetId').val(thisWidgetIndex);
      $('.pageops_modal').hide(300);
      $('.insertRowBlock').hide(300);
    },
    copyWidgetOps: function(ev){
      var this_column = $(ev.target).attr('data-wid_col_id');
      var this_widget = $(ev.target).attr('data-widget_id');
      var thisColumnData = this.model.get(this_column);
      var this_column_widgets = thisColumnData['colWidgets'];
      var WidgetCopiedAttr = jQuery.extend(true, {}, this_column_widgets[this_widget]);

      pageBuilderApp.copiedWidgOps = JSON.stringify(WidgetCopiedAttr);
    },
    pasteWidgetOps: function(ev){

      thisWidgetEl = $(ev.target );

      thisWidgetParentColID = thisWidgetEl.attr('data-parentwidgetid');
      thisWidgetIndex = parseInt( thisWidgetEl.attr('data-widget_id') );

      pageBuilderApp.currentlyEditedColId = thisWidgetParentColID;
      pageBuilderApp.currentlyEditedWidgId = thisWidgetIndex;
      pageBuilderApp.currentlyEditedThisCol = thisWidgetEl.attr('data-wid_col_id');
      pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');

      $('#'+thisWidgetParentColID).children('.editColumn').click();
      jQuery('.edit_column').hide(5);
      jQuery('.ulpb_column_controls').hide();

      $('#widgets li:nth-child('+(thisWidgetIndex+1)+')').children().children('.wdt-edit-controls').children('#pasteCopiedOptions').click();

      jQuery('.edit_column').hide(5);
      jQuery('.ulpb_column_controls').hide();

    },
    deleteRow: function(){

      $('.deleteRowIndex').val( this.model.get('rowID') );
      var thatThis = this;

      $('.popb_confirm_action_popup').css('display','block');

      $('.popb_confirm_subMessage_row').text("Do you want to delete this Row ? Deleted Items can be restored by clicking on redo button at top bar.");

      $('.confirm_no').click(function(){
        $('.popb_confirm_action_popup').css('display','none');
        $('.deleteRowIndex').val('');
        return;
      });

      $('.confirm_yes').one('click',function(){
        var deleteRowIndex = $('.deleteRowIndex').val();
        thisRowIndex = thatThis.model.get('rowID');
        if (deleteRowIndex != '') {
          if (thisRowIndex == deleteRowIndex) {
            thatThis.model.destroy();
            $(thatThis.el).remove();
            $('.deleteRowIndex').val('');
          }
        }
        $('.edit_row').hide(300);
        $('.columnWidgetPopup').hide(300);
        $('.pageops_modal').hide(300);
        $('.insertRowBlock').hide(300);
        $('.edit_column').hide(300);
        $('.ulpb_row_controls').css('display','none');
        $('.popb_confirm_action_popup').css('display','none');
        return;
      });
      return;
    },
    EditRow: function(){
      //console.log(JSON.stringify(this.model) );
      $('.ulpb_column_controls').hide();
      $('.columnWidgetPopup').hide(300);
      $('.pageops_modal').hide(300);
      $('.edit_column').hide(300);
      $('.insertRowBlock').hide(300);

      var rowID = this.model.get('rowID');
      $('.currentEditingRow').val(rowID);
      var row_height = this.model.get('rowHeight');
      var row_height_unit = this.model.get('rowHeightUnit');
      var row_no_columns = this.model.get('columns');
      var rowData = this.model.get('rowData');
      var row_bg_img = rowData['bg_img'];
      var row_bg_color = rowData['bg_color'];

      var row_margin = rowData['margin'];
      var rowMarginTop = row_margin['rowMarginTop'];
      var rowMarginBottom = row_margin['rowMarginBottom'];
      var rowMarginLeft = row_margin['rowMarginLeft'];
      var rowMarginRight = row_margin['rowMarginRight'];

      var row_padding = rowData['padding'];
      var rowPaddingTop = row_padding['rowPaddingTop'];
      var rowPaddingBottom = row_padding['rowPaddingBottom'];
      var rowPaddingLeft = row_padding['rowPaddingLeft'];
      var rowPaddingRight = row_padding['rowPaddingRight'];

      var row_video = '';
      if (typeof(rowData['video']) != "undefined"){
        var row_video = rowData['video'];
        var rowBgVideoEnable = row_video['rowBgVideoEnable'];
        var rowBgVideoLoop = row_video['rowBgVideoLoop'];
        var rowVideoMpfour = row_video['rowVideoMpfour'];
        var rowVideoWebM = row_video['rowVideoWebM'];
        var rowVideoThumb = row_video['rowVideoThumb'];
      }

      if (typeof(rowData['rowGradient']) !== "undefined"){
        var rowGradient = rowData['rowGradient'];

        $.each(rowGradient, function(index,val){
          $('.'+index).val(val);

          if (index == 'rowGradientColorFirst') {
            $('.rowGradientColorFirst').spectrum( 'set', $('.rowGradientColorFirst').val() );
          }
          if (index == 'rowGradientColorSecond') {
            $('.rowGradientColorSecond').spectrum( 'set', $('.rowGradientColorSecond').val() );
          }

          if (index == 'rowGradientLocationFirst') {
            $( ".PoPbrangeSlider" ).slider({
              value:val,
            });
          }

        });

        if (rowGradient['rowGradientType'] == 'linear') {
          $('.radialInput').css('display','none');
          $('.linearInput').css('display','block');
        } else if (rowGradient['rowGradientType'] == 'radial') {
          $('.radialInput').css('display','block');
          $('.linearInput').css('display','none');
        }

      }else{
        $('.rowGradientColorFirst').val('');
        $('.rowGradientLocationFirst').val('');
        $('.rowGradientColorSecond').val('');
        $('.rowGradientLocationSecond').val('');
        $('.rowGradientType').val('');
        $('.rowGradientPosition').val('');
        $('.rowGradientAngle').val('');
      }

      if (typeof(rowData['rowBackgroundType']) !== "undefined"){
        if (rowData['rowBackgroundType'] == 'solid') {
          $("#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid").prop("checked", true);
          $('#defaultRowBgOptions .POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOptions .POPBInputNormalRow .popb_tab_content').css('display','none');
          $('#defaultRowBgOptions .POPBInputNormalRow .content_popb_tab_1').css('display','block');
        }
        if (rowData['rowBackgroundType'] == 'gradient') {
          $(".rowBackgroundTypeGradient").prop("checked", true);
          $('#defaultRowBgOptions .POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeGradient').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOptions .POPBInputNormalRow .popb_tab_content').css('display','none');
          $('#defaultRowBgOptions .POPBInputNormalRow .content_popb_tab_2').css('display','block');
        }
      }else{
          $("#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid").prop("checked", true);
          $('#defaultRowBgOptions .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOptions .popb_tab_content').css('display','none');
          $('#defaultRowBgOptions .content_popb_tab_1').css('display','block');
      }

      if (typeof(rowData['rowHoverOptions']) !== "undefined") {
        var rowHoverOptions = rowData['rowHoverOptions'];

        if (rowHoverOptions['rowBgColorHover'] != '' || typeof(rowHoverOptions['rowBgColorHover']) != 'undefined' ) {
          $('.rowBgColorHover').val(rowHoverOptions['rowBgColorHover']);
          $('.rowBgColorHover').spectrum( 'set', $('.rowBgColorHover').val() );
        }else{
          $('.rowBgColorHover').val('');
        }
        
        $('.rowHoverTransitionDuration').val(rowHoverOptions['rowHoverTransitionDuration']);
        
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'solid') {
          $(".rowBackgroundTypeSolidHover").prop("checked", true);
          $('.POPBInputHoverRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.rowBackgroundTypeSolidHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHoverRow .popb_tab_content').css('display','none');
          $('.POPBInputHoverRow .content_popb_tab_1').css('display','block');
        }
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'gradient') {
          $(".rowBackgroundTypeGradientHover").prop("checked", true);
          $('.POPBInputHoverRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.rowBackgroundTypeGradientHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHoverRow .popb_tab_content').css('display','none');
          $('.POPBInputHoverRow .content_popb_tab_2').css('display','block');
        }
        if (rowHoverOptions['rowBackgroundTypeHover'] == '' || typeof(rowHoverOptions['rowBackgroundTypeHover']) == 'undefined' ) {
          $(".rowBackgroundTypeSolidHover").prop("checked", false);
          $(".rowBackgroundTypeGradientHover").prop("checked", false);
          $('.POPBInputHoverRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
        }

        var rowGradientHover = rowHoverOptions['rowGradientHover'];
        $.each(rowGradientHover, function(index,val){
          $('.'+index).val(val);

          if (index == 'rowGradientColorFirstHover') {
            $('.rowGradientColorFirstHover').spectrum( 'set', $('.rowGradientColorFirstHover').val() );
          }
          if (index == 'rowGradientColorSecondHover') {
            $('.rowGradientColorSecondHover').spectrum( 'set', $('.rowGradientColorSecondHover').val() );
          }
        });

        if (rowGradientHover['rowGradientTypeHover'] == 'linear') {
          $('.radialInputHover').css('display','none');
          $('.linearInputHover').css('display','block');
        } else if (rowGradientHover['rowGradientTypeHover'] == 'radial') {
          $('.radialInputHover').css('display','block');
          $('.linearInputHover').css('display','none');
        }

      }else{
        $('.rowBgColorHover').val('');
        $('.rowGradientColorFirstHover').val('');
        $('.rowGradientLocationFirstHover').val('');
        $('.rowGradientColorSecondHover').val('');
        $('.rowGradientLocationSecondHover').val('');
        $('.rowGradientTypeHover').val('');
        $('.rowGradientPositionHover').val('');
        $('.rowGradientAngleHover').val('');
        $(".rowBackgroundTypeSolidHover").prop("checked", false);
        $(".rowBackgroundTypeGradientHover").prop("checked", false);
      }


      if (typeof(rowData['rowBgOverlayColor']) !== "undefined"){
        $('.rowBgOverlayColor').val(rowData['rowBgOverlayColor']);
        $('.rowBgOverlayColor').spectrum( 'set', $('.rowBgOverlayColor').val() );
      }else{
        $('.rowBgOverlayColor').val('');
      }
      if (typeof(rowData['rowOverlayGradient']) !== "undefined"){
        var rowOverlayGradient = rowData['rowOverlayGradient'];

        $.each(rowOverlayGradient, function(index,val){
          $('.'+index).val(val);

          if (index == 'rowOverlayGradientColorFirst') {
            $('.rowOverlayGradientColorFirst').spectrum( 'set', $('.rowOverlayGradientColorFirst').val() );
          }
          if (index == 'rowOverlayGradientColorSecond') {
            $('.rowOverlayGradientColorSecond').spectrum( 'set', $('.rowOverlayGradientColorSecond').val() );
          }

        });

        if (rowOverlayGradient['rowOverlayGradientType'] == 'linear') {
          $('.radialInput').css('display','none');
          $('.linearInput').css('display','block');
        } else if (rowOverlayGradient['rowOverlayGradientType'] == 'radial') {
          $('.radialInput').css('display','block');
          $('.linearInput').css('display','none');
        }

      }else{
        $('.rowOverlayGradientColorFirst').val('');
        $('.rowOverlayGradientLocationFirst').val('');
        $('.rowOverlayGradientColorSecond').val('');
        $('.rowOverlayGradientLocationSecond').val('');
        $('.rowOverlayGradientType').val('');
        $('.rowOverlayGradientPosition').val('');
        $('.rowOverlayGradientAngle').val('');
      }

      if (typeof(rowData['rowOverlayBackgroundType']) !== "undefined"){
        if (rowData['rowOverlayBackgroundType'] == 'solid') {
          $("#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeSolid").prop("checked", true);
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popb_tab_content').css('display','none');
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .content_popb_tab_1').css('display','block');
        }
        if (rowData['rowOverlayBackgroundType'] == 'gradient') {
          $("#defaultRowBgOverlayOptions .rowOverlayBackgroundTypeGradient").prop("checked", true);
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOverlayOptions .rowOverlayBackgroundTypeGradient').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popb_tab_content').css('display','none');
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .content_popb_tab_2').css('display','block');
        }
      }else{
          $("#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeSolid").prop("checked", false);
          $("#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeGradient").prop("checked", false);
          $('#defaultRowBgOverlayOptions .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOverlayOptions .popb_tab_content').css('display','none');
          $('#defaultRowBgOverlayOptions .content_popb_tab_1').css('display','block');
      }

      if (typeof(rowData['rowBackgroundParallax']) !== 'undefined') {
        if (rowData['rowBackgroundParallax'] == 'true') {
          $('.rowBackgroundParallax').val('true');
        }else{
          $('.rowBackgroundParallax').val('false');
        }
      }else{
        $('.rowBackgroundParallax').val('');
      }


      if (typeof(rowData['rowCustomClass']) !== "undefined"){
        $('.rowCustomClass').val(rowData['rowCustomClass']);
      }else{
        $('.rowCustomClass').val('');
      }

      if (typeof(rowData['rowHideOnDesktop']) !== "undefined"){
        $('.rowHideOnDesktop').val(rowData['rowHideOnDesktop']);
        $('.rowHideOnTablet').val(rowData['rowHideOnTablet']);
        $('.rowHideOnMobile').val(rowData['rowHideOnMobile']);
      }else{
        $('.rowHideOnDesktop').val('');
        $('.rowHideOnTablet').val('');
        $('.rowHideOnMobile').val('');
      }

      if (typeof(this.model.get('rowHeightTablet')) !== "undefined"){

        $('.rowHeightTablet').val(this.model.get('rowHeightTablet'));
        $('.rowHeightUnitTablet').val(this.model.get('rowHeightUnitTablet'));
        $('.rowHeightMobile').val(this.model.get('rowHeightMobile'));
        $('.rowHeightUnitMobile').val(this.model.get('rowHeightUnitMobile'));
      }else{
        $('.rowHeightTablet').val('');
        $('.rowHeightUnitTablet').val('');
        $('.rowHeightMobile').val('');
        $('.rowHeightUnitMobile').val('');
      }

      if (typeof(rowData['marginTablet']) !== "undefined"){
        var marginTablet = rowData['marginTablet'];
        $('.rowMarginTopTablet').val(marginTablet['rMTT']);
        $('.rowMarginBottomTablet').val(marginTablet['rMBT']);
        $('.rowMarginLeftTablet').val(marginTablet['rMLT']);
        $('.rowMarginRightTablet').val(marginTablet['rMRT']);
      }else{
        $('.rowMarginTopTablet').val('');
        $('.rowMarginBottomTablet').val('');
        $('.rowMarginLeftTablet').val('');
        $('.rowMarginRightTablet').val('');
      }

      if (typeof(rowData['marginMobile']) !== "undefined"){
        var marginMobile = rowData['marginMobile'];
        $('.rowMarginTopMobile').val(marginMobile['rMTM']);
        $('.rowMarginBottomMobile').val(marginMobile['rMBM']);
        $('.rowMarginLeftMobile').val(marginMobile['rMLM']);
        $('.rowMarginRightMobile').val(marginMobile['rMRM']);
      } else{
        $('.rowMarginTopMobile').val('');
        $('.rowMarginBottomMobile').val('');
        $('.rowMarginLeftMobile').val('');
        $('.rowMarginRightMobile').val('');
      }

      if (typeof(rowData['paddingTablet']) !== "undefined"){
        var paddingTablet = rowData['paddingTablet'];
        $('.rowPaddingTopTablet').val(paddingTablet['rPTT']);
        $('.rowPaddingBottomTablet').val(paddingTablet['rPBT']);
        $('.rowPaddingLeftTablet').val(paddingTablet['rPLT']);
        $('.rowPaddingRightTablet').val(paddingTablet['rPRT']);
      } else{
        $('.rowPaddingTopTablet').val(' ');
        $('.rowPaddingBottomTablet').val(' ');
        $('.rowPaddingLeftTablet').val(' ');
        $('.rowPaddingRightTablet').val(' ');
      }

      if (typeof(rowData['paddingMobile']) !== "undefined"){
        var paddingMobile = rowData['paddingMobile'];
        $('.rowPaddingTopMobile').val(paddingMobile['rPTM']);
        $('.rowPaddingBottomMobile').val(paddingMobile['rPBM']);
        $('.rowPaddingLeftMobile').val(paddingMobile['rPLM']);
        $('.rowPaddingRightMobile').val(paddingMobile['rPRM']);
      } else{
        $('.rowPaddingTopMobile').val('');
        $('.rowPaddingBottomMobile').val('');
        $('.rowPaddingLeftMobile').val('');
        $('.rowPaddingRightMobile').val('');
      }

      if (typeof(rowData['bgSTop']) !== "undefined"){
        var bgSTop = rowData['bgSTop'];
        $('.rbgstType').val(bgSTop['rbgstType']);
        $('.rbgstColor').val(bgSTop['rbgstColor']);
        $('.rbgstWidth').val(bgSTop['rbgstWidth']);
        $('.rbgstWidtht').val(bgSTop['rbgstWidtht']);
        $('.rbgstWidthm').val(bgSTop['rbgstWidthm']);
        $('.rbgstHeight').val(bgSTop['rbgstHeight']);
        $('.rbgstHeightt').val(bgSTop['rbgstHeightt']);
        $('.rbgstHeightm').val(bgSTop['rbgstHeightm']);
        $('.rbgstFlipped').val(bgSTop['rbgstFlipped']);
        $('.rbgstFront').val(bgSTop['rbgstFront']);

        var bgSBottom = rowData['bgSBottom'];
        $('.rbgsbType').val(bgSBottom['rbgsbType']);
        $('.rbgsbColor').val(bgSBottom['rbgsbColor']);
        $('.rbgsbWidth').val(bgSBottom['rbgsbWidth']);
        $('.rbgsbWidtht').val(bgSBottom['rbgsbWidtht']);
        $('.rbgsbWidthm').val(bgSBottom['rbgsbWidthm']);
        $('.rbgsbHeight').val(bgSBottom['rbgsbHeight']);
        $('.rbgsbHeightt').val(bgSBottom['rbgsbHeightt']);
        $('.rbgsbHeightm').val(bgSBottom['rbgsbHeightm']);
        $('.rbgsbFlipped').val(bgSBottom['rbgsbFlipped']);
        $('.rbgsbFront').val(bgSBottom['rbgsbFront']);

        $('.rbgstColor').spectrum( 'set', $('.rbgstColor').val() );
        $('.rbgsbColor').spectrum( 'set', $('.rbgsbColor').val() );
      }else{
        $('.rbgstType').val('none');
        $('.rbgstWidth').val('100');
        $('.rbgstHeight').val('200');

        $('.rbgsbType').val('none');
        $('.rbgsbWidth').val('100');
        $('.rbgsbHeight').val('200');
      }

      var customStyling = rowData['customStyling'];
      var customJS = rowData['customJS'];

      $('#row_height').val(row_height);
      $('.row_height_unit').val(row_height_unit);
      $('#number_of_columns').val(row_no_columns);
      $('.rowBgImg').val(row_bg_img);
      $('.rowBgColor').val(row_bg_color);
      $('.rowMarginTop').val(rowMarginTop);
      $('.rowMarginBottom').val(rowMarginBottom);
      $('.rowMarginLeft').val(rowMarginLeft);
      $('.rowMarginRight').val(rowMarginRight);
      $('.rowPaddingTop').val(rowPaddingTop);
      $('.rowPaddingBottom').val(rowPaddingBottom);
      $('.rowPaddingLeft').val(rowPaddingLeft);
      $('.rowPaddingRight').val(rowPaddingRight);
      $('.rowCustomStyling').val(customStyling);
      $('.rowCustomJS').val(customJS);


      $('.rowBgVideoEnable').val(rowBgVideoEnable);
      $('.rowBgVideoLoop').val(rowBgVideoLoop);
      $('.rowVideoMpfour').val(rowVideoMpfour);
      $('.rowVideoWebM').val(rowVideoWebM);
      $('.rowVideoThumb').val(rowVideoThumb);

      if (typeof(row_video['rowVideoType']) != 'undefined') {
        $('.rowVideoType').val(row_video['rowVideoType']);
        $('.rowVideoYtUrl').val(row_video['rowVideoYtUrl']);
        if (row_video['rowVideoType'] == 'mp4') {
          $('.bgrowmp4').css("display",'block');
          $('.bgrowyt').css("display",'none');
        }
        if (row_video['rowVideoType'] == 'yt') {
          $('.bgrowyt').css("display",'block');
          $('.bgrowmp4').css("display",'none');
        }
      }else{
        $('.rowVideoType').val('mp4');
        $('.rowVideoYtUrl').val('');
      }

      $('.rowBgColor').spectrum( 'set', $('.rowBgColor').val() );

      $('.edit_options_right').append('<div class="column rules"></div>');

      
        
        

      $('.edit_row').show(300);
      jQuery('section[rowid="'+rowID+'"]').children('#ulpb_row_controls').show();
      $('#edit_form_close').live('click',function(){
        $('.edit_row').hide(300);
        $('#ulpb_row_controls').hide();
      });
    },
    updateRow: function(){
      //console.log( 'updateRow triggered');

      //var tua0 = performance.now();

      var updatedRowOpName = pageBuilderApp.changedRowOpName;

      if (typeof(updatedRowOpName) == 'undefined') {
        updatedRowOpName = ' ';
        return;
      }

      var thischangedValue = $('[data-optname="'+updatedRowOpName+'"]').val();

      var thisModelDataAttr = _.clone(this.model.attributes);

      var defaultModelDataAttr = {
        rowData: {
          rowCustomClass:'',
          bg_color: '#fff',
          bg_img: '',
          margin: {
            rowMarginTop: 0,
            rowMarginBottom: 0,
            rowMarginLeft: 0,
            rowMarginRight: 0,
          },
          marginTablet:{
            rMTT:'',
            rMBT:'',
            rMLT:'',
            rMRT:'',
          },
          marginMobile:{
            rMTM:'',
            rMBM:'',
            rMLM:'',
            rMRM:'',
          },
          padding:{
            rowPaddingTop: 0,
            rowPaddingBottom: 0,
            rowPaddingLeft: 0,
            rowPaddingRight: 0,
          },
          paddingTablet:{
            rPTT:'',
            rPBT:'',
            rPLT:'',
            rPRT:'',
          },
          paddingMobile:{
            rPTM:'',
            rPBM:'',
            rPLM:'',
            rPRM:'',
          },
          video:{
            rowBgVideoEnable: 'false',
            rowBgVideoLoop: 'loop',
            rowVideoMpfour: '',
            rowVideoWebM: '',
            rowVideoThumb: '',
          },
          customStyling: '',
          customJS: '',
          rowBackgroundType:'solid',
          rowGradient:{
            rowGradientColorFirst: '#dd9933',
            rowGradientLocationFirst:'55',
            rowGradientColorSecond:'#eeee22',
            rowGradientLocationSecond:'50',
            rowGradientType:'linear',
            rowGradientPosition:'top left',
            rowGradientAngle:'135',
          },
          rowHoverOptions: {
            rowBgColorHover:'',
            rowBackgroundTypeHover:'',
            rowHoverTransitionDuration:'',
            rowGradientHover:{
              rowGradientColorFirstHover: '',
              rowGradientLocationFirstHover:'',
              rowGradientColorSecondHover:'',
              rowGradientLocationSecondHover:'',
              rowGradientTypeHover:'linear',
              rowGradientPositionHover:'top left',
              rowGradientAngleHover:'',
            }
          },
          rowOverlayBackgroundType: '',
          rowOverlayGradient:{
            rowOverlayGradientColorFirst:  '',
            rowOverlayGradientLocationFirst: '',
            rowOverlayGradientColorSecond: '',
            rowOverlayGradientLocationSecond: '',
            rowOverlayGradientType: '',
            rowOverlayGradientPosition: '',
            rowOverlayGradientAngle: '',
          },
          rowHideOnDesktop:'',
          rowHideOnTablet:'',
          rowHideOnMobile:'',
          bgSTop: {
            rbgstType: 'none',
            rbgstColor:'#e3e3e3',
            rbgstWidth:'100',
            rbgstWidtht:'',
            rbgstWidthm:'',
            rbgstHeight:'200',
            rbgstHeightt:'',
            rbgstHeightm:'',
            rbgstFlipped:'none',
            rbgstFront:'back',
          },
          bgSBottom: {
            rbgsbType: 'none',
            rbgsbColor:'#e3e3e3',
            rbgsbWidth:'100',
            rbgsbWidtht:'',
            rbgsbWidthm:'',
            rbgsbHeight:'200',
            rbgsbHeightt:'',
            rbgsbHeightm:'',
            rbgsbFlipped:'none',
            rbgsbFront:'back',
          },
        }
      }


      var prevRowCols = this.model.get('columns');
      var prevRowHeight = this.model.get('rowHeight');
      var prevRowHeightTablet = this.model.get('rowHeightTablet');
      var prevRowHeightMobile = this.model.get('rowHeightMobile');
      var prevRowHeightUnit = this.model.get('rowHeightUnit');
      var prevRowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
      var prevRowHeightUnitMobile = this.model.get('rowHeightUnitMobile');

      mergeNonsetObjectKeys(thisModelDataAttr['rowData'], defaultModelDataAttr['rowData'] );

      if (updatedRowOpName == 'rowData.rowBackgroundType' || updatedRowOpName == 'rowData.rowHoverOptions.rowBackgroundTypeHover' || updatedRowOpName == 'rowData.rowOverlayBackgroundType') {
        thischangedValue = $('[data-optname="'+updatedRowOpName+'"]:checked').val();
      }

      setUpdatedOptsObject(thisModelDataAttr, updatedRowOpName, thischangedValue);

      
      if (updatedRowOpName) {
        this.model.set(thisModelDataAttr);
      }

      rowColumns = this.model.get('columns');

      var numberOfColumnsSelected = rowColumns;
      if ( typeof(this.model.get('column'+numberOfColumnsSelected)) == 'undefined') {
        for( var i = 0; i <= numberOfColumnsSelected; i++){
          if ( typeof(this.model.get('column'+i)) == 'undefined') {
            this_column = 'column'+i;
            this.model.set({

              [this_column] :  {
                colWidgets: [],
                columnOptions:{
                bg_color: '',
                margin: {
                  columnMarginTop: 0,
                  columnMarginBottom: 0,
                  columnMarginLeft: 0,
                  columnMarginRight: 0,
                },
                padding:{
                  columnPaddingTop: 0,
                  columnPaddingBottom: 0,
                  columnPaddingLeft: 0,
                  columnPaddingRight: 0,
                },
                paddingTablet:{
                  rPTT:'',
                  rPBT:'',
                  rPLT:'',
                  rPRT:'',
                },
                paddingMobile:{
                  rPTM:'',
                  rPBM:'',
                  rPLM:'',
                  rPRM:'',
                },
                marginTablet:{
                  rMTT:'',
                  rMBT:'',
                  rMLT:'',
                  rMRT:'',
                },
                marginMobile:{
                  rMTM:'',
                  rMBM:'',
                  rMLM:'',
                  rMRM:'',
                },
                width: '',
                widthTablet: '',
                widthMobile: '',
                columnCSS:'/* Add column custom styling here */',
                columnCustomClass:'',
                colHideOnDesktop:'',
                colHideOnTablet:'',
                colHideOnMobile:'',
                colBoxShadow: {
                  colBoxShadowH:'',
                  colBoxShadowV:'',
                  colBoxShadowBlur:'',
                  colBoxShadowColor:'',
                },
                colBgImg:'',
                colBackgroundType:'solid',
                colGradient:{
                  colGradientColorFirst: '#dd9933',
                  colGradientLocationFirst:'55',
                  colGradientColorSecond:'#eeee22',
                  colGradientLocationSecond:'50',
                  colGradientType:'linear',
                  colGradientPosition:'top left',
                  colGradientAngle:'135',
                },
                colHoverOptions: {
                  colBgColorHover:'',
                  colBackgroundTypeHover:'',
                  colHoverTransitionDuration:'',
                  colGradientHover:{
                    colGradientColorFirstHover: '',
                    colGradientLocationFirstHover:'',
                    colGradientColorSecondHover:'',
                    colGradientLocationSecondHover:'',
                    colGradientTypeHover:'linear',
                    colGradientPositionHover:'top left',
                    colGradientAngleHover:'',
                  },
                },
                }
              },

            });
          }
        }
      }

      
      rowID = this.model.get('rowID');
      rowCID = this.model.cid;
      rowColumns = this.model.get('columns');
      rowHeightUnit = this.model.get('rowHeightUnit');
      rowData = this.model.get('rowData');
      rowHeightTablet = this.model.get('rowHeightTablet');
      rowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
      rowHeightMobile = this.model.get('rowHeightMobile');
      rowHeightUnitMobile = this.model.get('rowHeightUnitMobile');
      
      
      rowOptionsRender(rowID,rowCID,rowColumns,rowHeightUnit,rowData, rowHeightTablet,rowHeightUnitTablet,rowHeightMobile,rowHeightUnitMobile);

      var aftRowCols = this.model.get('columns');
      var aftRowHeight = this.model.get('rowHeight');
      var aftRowHeightTablet = this.model.get('rowHeightTablet');
      var aftRowHeightMobile = this.model.get('rowHeightMobile');
      var aftRowHeightUnit = this.model.get('rowHeightUnit');
      var aftRowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
      var aftRowHeightUnitMobile = this.model.get('rowHeightUnitMobile');
      var aftrowVideo = this.model.attributes['rowData']['video'];
      
      if ( aftRowCols != prevRowCols || aftRowHeight != prevRowHeight || aftRowHeightTablet != prevRowHeightTablet || aftRowHeightMobile != prevRowHeightMobile || aftRowHeightUnit != prevRowHeightUnit || aftRowHeightUnitTablet != prevRowHeightUnitTablet || aftRowHeightUnitMobile != prevRowHeightUnitMobile ) {
          $(this.el).html('');
          this.render();
      }

      if ( updatedRowOpName.includes("video") ) {
          $(this.el).html('');
          this.render();
      }

      //var tua1 = performance.now();
      //console.log("Call to updateRowTrigger took " + (tua1 - tua0) + " milliseconds.");

      //$('.isChagesMade').val('true');
      jQuery('section[rowid="'+rowID+'"]').children('#ulpb_row_controls').show();
    },
    setGlobalRow: function(){

      if (isGlobalRowActive !== 'true') {
          alert('Global Row Extension is not active.');
        }else{
          var askGlobalRowName = prompt('Name of the row ?');
          var globalRowAttrToSet  = this.model.attributes;

          if (askGlobalRowName != null) {
            if (askGlobalRowName == '') {
              askGlobalRowName = "Global Row";
            }
            var ifIsGlobal = this.model.get('globalRow');

            if (typeof(ifIsGlobal) != 'undefined') {
            } else {

              var retrievedPostID = sendGlobalRowDataToDb(globalRowAttrToSet,askGlobalRowName);

                this.model.set({
                  globalRow:{
                    isGlobalRow: true,
                    globalRowPid: $('.globalRowRetrievedPostID').val()
                  }
                });
                
            } 
            $('.isChagesMade').val('true');
            $(this.el).html('');
            this.render();
          }
        }
    },
    DuplicateRow: function(){
      newRowModel = this.model.clone();
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
      newRowModel.set({
        rowID: 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100),
        globalRow: 'unset'
      });
      newRowModel.unset('globalRow');
      var stuffedModel = JSON.stringify(newRowModel.attributes);
      var unStuffedModel = JSON.parse(stuffedModel);
      pageBuilderApp.rowList.add(unStuffedModel, {at: modelIndex+1} );
      $('.isChagesMade').val('true');
      $(this.el).html('');
      this.render();
    },
    addNewRow: function(){
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);

      pageBuilderApp.rowList.add({
      rowID: 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100),
      rowHeight: 100,
      columns: 0,
      rowData: {
          rowCustomClass:'',
          bg_color: 'transparent',
          bg_img: '',
          margin: {
            rowMarginTop: 0,
            rowMarginBottom: 0,
            rowMarginLeft: 0,
            rowMarginRight: 0,
          },
          marginTablet:{
            rMTT:'',
            rMBT:'',
            rMLT:'',
            rMRT:'',
          },
          marginMobile:{
            rMTM:'',
            rMBM:'',
            rMLM:'',
            rMRM:'',
          },
          padding:{
            rowPaddingTop: 0,
            rowPaddingBottom: 0,
            rowPaddingLeft: 0,
            rowPaddingRight: 0,
          },
          paddingTablet:{
            rPTT:'',
            rPBT:'',
            rPLT:'',
            rPRT:'',
          },
          paddingMobile:{
            rPTM:'',
            rPBM:'',
            rPLM:'',
            rPRM:'',
          },
          video:{
            rowBgVideoEnable: 'false',
            rowBgVideoLoop: 'loop',
            rowVideoMpfour: ' ',
            rowVideoWebM: ' ',
            rowVideoThumb: ' ',
          },
          customStyling: '',
          customJS: ' ',
          rowBackgroundType:'solid',
          rowGradient:{
            rowGradientColorFirst: '#dd9933',
            rowGradientLocationFirst:'40',
            rowGradientColorSecond:'#eeee22',
            rowGradientLocationSecond:'60',
            rowGradientType:'linear',
            rowGradientPosition:'top left',
            rowGradientAngle:'135',
          }
        } }, {at: modelIndex+1} );
      $('.isChagesMade').val('true');
    },
    copySectionOps: function(){
      var thisSectionOps = this.model.get('rowData');
      pageBuilderApp.copiedSecOps = JSON.stringify(thisSectionOps);
    },
    pasteSectionOps: function(){

      if (pageBuilderApp.copiedSecOps != '') {
        var sectionOpsToPaste =  JSON.parse(pageBuilderApp.copiedSecOps);
        this.model.set('rowData',sectionOpsToPaste );
      }

      pageBuilderApp.copiedSecOps = '';
      $(this.el).html('');
      this.render();
    },
    addNewRowBlock: function(ev){
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
      $('.insertRowBlockAtIndex').val(modelIndex);

      $('.ulpb_column_controls').hide();
      $('.columnWidgetPopup').hide(300);
      $('.pageops_modal').hide(300);
      $('.edit_column').hide(300);

      $('.insertRowBlock').show(300);
    },
    addNewGlobalRow: function(){
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
      
      $('.insert_Global_row').show(300);
        
      $('.addNewGlobalRowClosebutton').one('click',function(){
                $('.globalRowRetrievedAttributes').val('');
                selectGlobalRowToInsert = $('.selectGlobalRowToInsert').val();

                if (selectGlobalRowToInsert != '') {
                  getGlobalRowDataFromDb(selectGlobalRowToInsert);
                }
                
                retrievedGlobalRowAttributes = $('.globalRowRetrievedAttributes').val();
                
                if (retrievedGlobalRowAttributes != '') {
                  pageBuilderApp.rowList.add(  JSON.parse(retrievedGlobalRowAttributes), {at: modelIndex+1} );
                }

          $('.insert_Global_row').hide(300);
      });
      $('.isChagesMade').val('true');
    },
    EditColumn: function(ev){

      $('.pbSearchWidget').val('');
      jQuery('.widget').show();
      $('.checkIfWidgetsAreLoadedInColumn').val('false');
      $('.columnWidgetPopup, .edit_row').hide(300);
      $('.ulpb_row_controls, .edit_row').hide(300);
      $('.pageops_modal').hide(300);
      $('.insertRowBlock').hide(300);

      var this_column = $(ev.target).attr('data-col_id');
      var rowID = this.model.get('rowID');
      $('.ColcurrentEditableRowID').val(rowID);
      $('.currentEditableColId').val(this_column);

      pageBuilderApp.currentlyEditedColId = rowID+'-'+this_column;
      pageBuilderApp.currentlyEditedThisCol = this_column;
      pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');


      var pbWrapperWidth = $('section[RowID="'+rowID+'"]').width();
      var thisColumnData = this.model.get(this_column);
      var this_column_widgets = thisColumnData['colWidgets'];
      var this_column_content = thisColumnData['columnContent'];
      var this_column_type = thisColumnData['columnType'];
      var this_column_options = thisColumnData['columnOptions'];
      var this_column_bg_color = this_column_options['bg_color'];
      var this_column_width = this_column_options['width'];
      var columnCSS = this_column_options['columnCSS'];
      var this_column_margin = this_column_options['margin'];
      var columnMarginTop = this_column_margin['columnMarginTop'];
      var columnMarginBottom = this_column_margin['columnMarginBottom'];
      var columnMarginLeft = this_column_margin['columnMarginLeft'];
      var columnMarginRight = this_column_margin['columnMarginRight'];

      var this_column_padding = this_column_options['padding'];
      var columnPaddingTop = this_column_padding['columnPaddingTop'];
      var columnPaddingBottom = this_column_padding['columnPaddingBottom'];
      var columnPaddingLeft = this_column_padding['columnPaddingLeft'];
      var columnPaddingRight = this_column_padding['columnPaddingRight'];
      
      var colWidth = $('section[RowID="'+rowID+'"]'+' .'+this_column).width();
      var colWidthPercentage  = ( (colWidth/pbWrapperWidth) * 100);
      colWidthPercentage = colWidthPercentage.toFixed(2);
      $('#columnBgColor').val(this_column_bg_color);
      $('#columnMargin').val(this_column_margin);
      $('#columnPadding').val(this_column_padding);
      $('#columnWidth').val(this_column_width);
      $('.widget-type-field').val(this_column_type);

      $('#columnBgColor').spectrum( 'set', $('.columnBgColor').val() );

      if (typeof(this_column_options['columnCSS']) == 'undefined') { columnCSS = '/* Add your custom CSS for this column here.*/'}
      $('.columnCustomStyling').val(columnCSS);

      $('.columnMarginTop').val(columnMarginTop);
      $('.columnMarginBottom').val(columnMarginBottom);
      $('.columnMarginLeft').val(columnMarginLeft);
      $('.columnMarginRight').val(columnMarginRight);
      $('.columnPaddingTop').val(columnPaddingTop);
      $('.columnPaddingBottom').val(columnPaddingBottom);
      $('.columnPaddingLeft').val(columnPaddingLeft);
      $('.columnPaddingRight').val(columnPaddingRight);

      if (typeof(this_column_options['columnCustomClass']) !== 'undefined') {
        $('.columnCustomClass').val(this_column_options['columnCustomClass']);
      }else{
        $('.columnCustomClass').val('');
      }

      if (typeof(this_column_options['colCAD']) != 'undefined') {
        $('.colCAD').val(this_column_options['colCAD']);
        $('.colCAT').val(this_column_options['colCAT']);
        $('.colCAM').val(this_column_options['colCAM']);
      }else{
        $('.colCAD').val('default');
        $('.colCAT').val('default');
        $('.colCAM').val('default');
      }

      if (typeof(this_column_options['colHideOnDesktop']) !== 'undefined') {
        $('.colHideOnDesktop').val(this_column_options['colHideOnDesktop']);
        $('.colHideOnTablet').val(this_column_options['colHideOnTablet']);
        $('.colHideOnMobile').val(this_column_options['colHideOnMobile']);
      }else{
        $('.colHideOnDesktop').val('');
        $('.colHideOnTablet').val('');
        $('.colHideOnMobile').val('');
      }

      if (typeof(this_column_options['colBoxShadow']) !== 'undefined') {
        colBoxShadow = this_column_options['colBoxShadow'];
        $('.colBoxShadowH').val(colBoxShadow['colBoxShadowH']);
        $('.colBoxShadowV').val(colBoxShadow['colBoxShadowV']);
        $('.colBoxShadowBlur').val(colBoxShadow['colBoxShadowBlur']);
        $('.colBoxShadowColor').val(colBoxShadow['colBoxShadowColor']);
        $(".colBoxShadowColor").spectrum( 'set', $('.colBoxShadowColor').val() );
      }else{
        $('.colBoxShadowH').val('');
        $('.colBoxShadowV').val('');
        $('.colBoxShadowBlur').val('');
        $('.colBoxShadowColor').val('');
      }

      if (typeof(this_column_options['colBorder']) !== 'undefined') {
        colBorder = this_column_options['colBorder'];
        $('.colBorderWidth').val(colBorder['colBorderWidth']);
        $('.colBorderStyle').val(colBorder['colBorderStyle']);
        $('.colBorderColor').val(colBorder['colBorderColor']);
        $(".colBorderColor").spectrum( 'set', $('.colBorderColor').val() );
        $('.colBorderRadius').val(colBorder['colBorderRadius']);
      }else{
        $('.colBorderWidth').val('');
        $('.colBorderStyle').val('');
        $('.colBorderColor').val('');
        $('.colBorderRadius').val('');
      }

      if (typeof(this_column_options['paddingTablet']) !== 'undefined'){
        colMarginTablet = this_column_options['marginTablet'];
        colPaddingTablet = this_column_options['paddingTablet'];

        colMarginMobile = this_column_options['marginMobile'];
        colPaddingMobile = this_column_options['paddingMobile'];

        $('.columnWidthTablet').val(this_column_options['widthTablet']);
        $('.columnWidthMobile').val(this_column_options['widthMobile']);

        $('.columnMarginTopTablet').val(colMarginTablet['rMTT']);
        $('.columnMarginBottomTablet').val(colMarginTablet['rMBT']);
        $('.columnMarginLeftTablet').val(colMarginTablet['rMLT']);
        $('.columnMarginRightTablet').val(colMarginTablet['rMRT']);

        $('.columnPaddingTopTablet').val(colPaddingTablet['rPTT']);
        $('.columnPaddingBottomTablet').val(colPaddingTablet['rPBT']);
        $('.columnPaddingLeftTablet').val(colPaddingTablet['rPLT']);
        $('.columnPaddingRightTablet').val(colPaddingTablet['rPRT']);

        $('.columnMarginTopMobile').val(colMarginMobile['rMTM']);
        $('.columnMarginBottomMobile').val(colMarginMobile['rMBM']);
        $('.columnMarginLeftMobile').val(colMarginMobile['rMLM']);
        $('.columnMarginRightMobile').val(colMarginMobile['rMRM']);

        $('.columnPaddingTopMobile').val(colPaddingMobile['rPTM']);
        $('.columnPaddingBottomMobile').val(colPaddingMobile['rPBM']);
        $('.columnPaddingLeftMobile').val(colPaddingMobile['rPLM']);
        $('.columnPaddingRightMobile').val(colPaddingMobile['rPRM']);

      } else{
        $('.columnWidthTablet').val('');
        $('.columnWidthMobile').val('');

        $('.columnMarginTopTablet').val('');
        $('.columnMarginBottomTablet').val('');
        $('.columnMarginLeftTablet').val('');
        $('.columnMarginRightTablet').val('');

        $('.columnPaddingTopTablet').val('');
        $('.columnPaddingBottomTablet').val('');
        $('.columnPaddingLeftTablet').val('');
        $('.columnPaddingRightTablet').val('');

        $('.columnMarginTopMobile').val('');
        $('.columnMarginBottomMobile').val('');
        $('.columnMarginLeftMobile').val('');
        $('.columnMarginRightMobile').val('');

        $('.columnPaddingTopMobile').val('');
        $('.columnPaddingBottomMobile').val('');
        $('.columnPaddingLeftMobile').val('');
        $('.columnPaddingRightMobile').val('');
      }

      if (typeof(this_column_options['colBgImg']) !== "undefined"){
        $('.colBgImg').val(this_column_options['colBgImg']);
      }else{
        $('.colBgImg').val('');
      }

      if (typeof(this_column_options['colGradient']) !== "undefined"){
        var colGradient = this_column_options['colGradient'];

        $.each(colGradient, function(index,val){
          $('.'+index).val(val);

          if (index == 'colGradientColorFirst') {
            $('.colGradientColorFirst').spectrum( 'set', $('.colGradientColorFirst').val() );
          }
          if (index == 'colGradientColorSecond') {
            $('.colGradientColorSecond').spectrum( 'set', $('.colGradientColorSecond').val() );
          }

        });

        if (colGradient['colGradientType'] == 'linear') {
          $('.radialInput').css('display','none');
          $('.linearInput').css('display','block');
        } else if (colGradient['colGradientType'] == 'radial') {
          $('.radialInput').css('display','block');
          $('.linearInput').css('display','none');
        }

      }else{
        $('.colGradientColorFirst').val('');
        $('.colGradientLocationFirst').val('');
        $('.colGradientColorSecond').val('');
        $('.colGradientLocationSecond').val('');
        $('.colGradientType').val('');
        $('.colGradientPosition').val('');
        $('.colGradientAngle').val('');
      }



      if (typeof(this_column_options['colBackgroundType']) !== "undefined"){
        if (this_column_options['colBackgroundType'] == 'solid') {
          $(".POPBInputNormalRow .colBackgroundTypeSolid").prop("checked", true);
          $('.POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.POPBInputNormalRow .colBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputNormalRow .popb_tab_content').css('display','none');
          $('.POPBInputNormalRow .content_popb_tab_1').css('display','block');
        }
        if (this_column_options['colBackgroundType'] == 'gradient') {
          $(".colBackgroundTypeGradient").prop("checked", true);
          $('.POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.colBackgroundTypeGradient').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputNormalRow .popb_tab_content').css('display','none');
          $('.POPBInputNormalRow .content_popb_tab_2').css('display','block');
        }
      }else{
          $(".POPBInputNormalRow .colBackgroundTypeSolid").prop("checked", true);
          $('.popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.POPBInputNormalRow .colBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.popb_tab_content').css('display','none');
          $('.content_popb_tab_1').css('display','block');
      }



      if (typeof(this_column_options['colHoverOptions']) !== "undefined") {
        var colHoverOptions = this_column_options['colHoverOptions'];
        if (colHoverOptions['colBgColorHover'] != '') {
          $('.colBgColorHover').val(colHoverOptions['colBgColorHover']);
          $('.colBgColorHover').spectrum( 'set', $('.colBgColorHover').val() );
        }else{
          $('.colBgColorHover').val('');
        }

        $('.colHoverTransitionDuration').val(colHoverOptions['colHoverTransitionDuration']);

        

        if (colHoverOptions['colBackgroundTypeHover'] == 'solid') {
          $(".colBackgroundTypeSolidHover").prop("checked", true);
          $(".colBackgroundTypeGradientHover").prop("checked", false);
          $('.POPBInputHovercol .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.colBackgroundTypeSolidHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHovercol .popb_tab_content').css('display','none');
          $('.POPBInputHovercol .content_popb_tab_1').css('display','block');
        }
        if (colHoverOptions['colBackgroundTypeHover'] == 'gradient') {
          $(".colBackgroundTypeGradientHover").prop("checked", true);
          $(".colBackgroundTypeSolidHover").prop("checked", false);
          $('.POPBInputHovercol .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.colBackgroundTypeGradientHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHovercol .popb_tab_content').css('display','none');
          $('.POPBInputHovercol .content_popb_tab_2').css('display','block');
        }
        if (colHoverOptions['colBackgroundTypeHover'] == '' || typeof(colHoverOptions['colBackgroundTypeHover']) == 'undefined' ) {
          $(".colBackgroundTypeSolidHover").prop("checked", false);
          $(".colBackgroundTypeGradientHover").prop("checked", false);
          $('.POPBInputHovercol .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
        }

        var colGradientHover = colHoverOptions['colGradientHover'];
        $.each(colGradientHover, function(index,val){
          $('.'+index).val(val);

          if (index == 'colGradientColorFirstHover') {
            $('.colGradientColorFirstHover').spectrum( 'set', $('.colGradientColorFirstHover').val() );
          }
          if (index == 'colGradientColorSecondHover') {
            $('.colGradientColorSecondHover').spectrum( 'set', $('.colGradientColorSecondHover').val() );
          }
        });

        if (colGradientHover['colGradientTypeHover'] == 'linear') {
          $('.radialInputHover').css('display','none');
          $('.linearInputHover').css('display','block');
        } else if (colGradientHover['colGradientTypeHover'] == 'radial') {
          $('.radialInputHover').css('display','block');
          $('.linearInputHover').css('display','none');
        }

      }else{
        $('.colBgColorHover').val('');
        $('.colHoverTransitionDuration').val('');
        $('.colGradientColorFirstHover').val('');
        $('.colGradientLocationFirstHover').val('');
        $('.colGradientColorSecondHover').val('');
        $('.colGradientLocationSecondHover').val('');
        $('.colGradientTypeHover').val('');
        $('.colGradientPositionHover').val('');
        $('.colGradientAngleHover').val('');
      }







      $('.columnBgColor').css('background-color',this_column_bg_color);
      $('.columnBgColor').spectrum( 'set', $('.columnBgColor').val() );
      

      pageBuilderApp.widgetList.reset();
      if (this_column_widgets) {
        pageBuilderApp.widgetList.add(this_column_widgets);
        }

        $('.checkIfWidgetsAreLoadedInColumn').val('true');
        
        jQuery('.edit_column').show(300);
        var rowID = this.model.get('rowID');
        jQuery('section[rowid="'+rowID+'"]').children('.ulpb_column_controls'+this_column).show();
        $('#edit_form_closeCol').click(function(){
          jQuery('.edit_column').hide(300);
          $('.ulpb_column_controls').hide();
        });

        if($(ev.target).hasClass('emptyColumnIcon') ){
          $('.colNewWidgetTabBtn').click();
          var currentAttrValue = jQuery('.colNewWidgetTabBtn').children('a').attr('href');
          jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
          jQuery('.colNewWidgetTabBtn').addClass('active').siblings().removeClass('active');
        }else{
          $('.colOpsTabBtn').click();
   
          var currentAttrValue = jQuery('.colOpsTabBtn').children('a').attr('href');    
     
          jQuery('.tabs ' + currentAttrValue).show().siblings().hide();      
          jQuery('.colOpsTabBtn').addClass('active').siblings().removeClass('active');

        } 
    },
    copyColumnOps: function(ev){
      var this_column = $(ev.target).attr('data-col_id');
      var thisColToCopyData = this.model.get(this_column);
      var this_column_options = thisColToCopyData['columnOptions'];
      pageBuilderApp.copiedColOps = JSON.stringify(this_column_options);
      return;
    },
    pasteColumnOps: function(ev){

      if (pageBuilderApp.copiedColOps != '') {

        var pc0this_column = $(ev.target).attr('data-col_id');
        var thisColDefaultData = this.model.get(pc0this_column);
        var copiedColOps =  JSON.parse(pageBuilderApp.copiedColOps);

        this.model.set({
          [pc0this_column] : {
            colWidgets: thisColDefaultData['colWidgets'],
            columnOptions:copiedColOps
          }
        });

      var rowID = this.model.get('rowID');
      var rowColumns = this.model.get('columns');
      var thisColumnModelData = this.model.get(pc0this_column);

      var rowHeightData = {
        rowHeight : this.model.get('rowHeight'),
        rowHeightUnit : this.model.get('rowHeightUnit'),
        rowHeightTablet : this.model.get('rowHeightTablet'),
        rowHeightUnitTablet : this.model.get('rowHeightUnitTablet'),
        rowHeightMobile : this.model.get('rowHeightMobile'),
        rowHeightUnitMobile : this.model.get('rowHeightUnitMobile'),
      }

      rowColumnSingleRender(thisColumnModelData, rowID ,pc0this_column,rowColumns ,rowHeightData);

        pageBuilderApp.copiedColOps = '';
      }
    },
    flipColumns: function(ev){
      var rowID = this.model.get('rowID');
      var rowColumns = this.model.get('columns');
      var rowColumns = parseInt(rowColumns);
      var this_column = $(ev.target).attr('data-col_id');
      var this_column_index = $(ev.target).attr('data-colIndex');
      var thisColToCopyData = this.model.get(this_column);

      var nxtColIndex = parseInt(this_column_index)+1;
      var nxtColName = 'column'+ nxtColIndex;
      var nextColToCopyData = this.model.get( nxtColName );

      if (nxtColIndex <= rowColumns) {
        this.model.set({
          [this_column] : {
            colWidgets: nextColToCopyData['colWidgets'],
            columnOptions:thisColToCopyData['columnOptions'],
          },        
        });

        this.model.set({
          [nxtColName] : {
            colWidgets: thisColToCopyData['colWidgets'],
            columnOptions:nextColToCopyData['columnOptions'],
          },
        });

        $(this.el).html('');
        this.render();
      }else{
        $('#'+rowID+'-'+this_column).css('border','2px solid #F44336 ');
      }

    },
    updateColumn: function(ev){
      //console.log('updateColumn triggered');

      //var tua0 = performance.now();
      
      var columnToUpdate =  $(ev.target).attr('data-col_id');

      var updatedColOpName = pageBuilderApp.changedColOpName;
      var thischangedValue = $('[data-optname="'+updatedColOpName+'"]').val();

      if (typeof(updatedColOpName) == 'undefined') {
        updatedColOpName = ' ';
      }

      var thisModelDataAttr = _.clone(this.model.attributes[columnToUpdate]['columnOptions']);

      var thisModelDefaultColOps = {
        columnOptions:{
          bg_color: 'transparent',
          margin: {
            columnMarginTop: 0,
            columnMarginBottom: 0,
            columnMarginLeft: 0,
            columnMarginRight: 0,
          },
          padding:{
            columnPaddingTop: 0,
            columnPaddingBottom: 0,
            columnPaddingLeft: 0,
            columnPaddingRight: 0,
          },
          paddingTablet:{
            rPTT:'',
            rPBT:'',
            rPLT:'',
            rPRT:'',
          },
          paddingMobile:{
            rPTM:'',
            rPBM:'',
            rPLM:'',
            rPRM:'',
          },
          marginTablet:{
            rMTT:'',
            rMBT:'',
            rMLT:'',
            rMRT:'',
          },
          marginMobile:{
            rMTM:'',
            rMBM:'',
            rMLM:'',
            rMRM:'',
          },
          width: '',
          widthTablet: '',
          widthMobile: '',
          columnCSS:'/* Add column custom styling here */',
          columnCustomClass:'',
          colHideOnDesktop:'',
          colHideOnTablet:'',
          colHideOnMobile:'',
          colBoxShadow: {
            colBoxShadowH:'',
            colBoxShadowV:'',
            colBoxShadowBlur:'',
            colBoxShadowColor:'',
          },
          colBgImg:'',
          colBackgroundType:'solid',
          colGradient:{
            colGradientColorFirst: '#dd9933',
            colGradientLocationFirst:'55',
            colGradientColorSecond:'#eeee22',
            colGradientLocationSecond:'50',
            colGradientType:'linear',
            colGradientPosition:'top left',
            colGradientAngle:'135',
          },
          colHoverOptions: {
            colBgColorHover:'',
            colBackgroundTypeHover:'',
            colHoverTransitionDuration:'',
            colGradientHover:{
              colGradientColorFirstHover: '',
              colGradientLocationFirstHover:'',
              colGradientColorSecondHover:'',
              colGradientLocationSecondHover:'',
              colGradientTypeHover:'linear',
              colGradientPositionHover:'top left',
              colGradientAngleHover:'',
            },
          },
        }
      }

      mergeNonsetObjectKeys(thisModelDataAttr, thisModelDefaultColOps.columnOptions );


      if (updatedColOpName == 'colBackgroundType' || updatedColOpName == 'colHoverOptions.colBackgroundTypeHover' ) {
        thischangedValue = $('[data-optname="'+updatedColOpName+'"]:checked').val();
      }

      setUpdatedOptsObject(thisModelDataAttr, updatedColOpName, thischangedValue);

      var widgets = pageBuilderApp.widgetList.toJSON();

      this.model.set({
        [columnToUpdate] : {
          colWidgets: widgets,
          columnOptions:thisModelDataAttr
        }
      });

      var rowID = this.model.get('rowID');
      var rowColumns = this.model.get('columns');
      var thisColumnModelData = this.model.get(columnToUpdate);

      var rowHeightData = {
        rowHeight : this.model.get('rowHeight'),
        rowHeightUnit : this.model.get('rowHeightUnit'),
        rowHeightTablet : this.model.get('rowHeightTablet'),
        rowHeightUnitTablet : this.model.get('rowHeightUnitTablet'),
        rowHeightMobile : this.model.get('rowHeightMobile'),
        rowHeightUnitMobile : this.model.get('rowHeightUnitMobile'),
      }

      rowHeightTablet = this.model.get('rowHeightTablet');
      rowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
      rowHeightMobile = this.model.get('rowHeightMobile');
      rowHeightUnitMobile = this.model.get('rowHeightUnitMobile');

      rowColumnSingleRender(thisColumnModelData, rowID ,columnToUpdate,rowColumns , rowHeightData);

        //$('section[rowid="'+rowID+'"]').children('.ulpb_column_controls'+columnToUpdate).show();
        //$('.isChagesMade').val('true');
      //var tua1 = performance.now();
      //console.log("Call to updateColumn took " + (tua1 - tua0) + " milliseconds.");

      
    }, // prev took 95-115ms now 5-15ms
    updateColumnWidgetTrigger: function(ev){
      //console.log('updateColumnWidgetTrigger triggered');

      // var tua0 = performance.now();

      var columnToUpdate =  $(ev.target).attr('data-col_id');
      
      var thisModelColDataAttr = this.model.attributes[columnToUpdate];
      
      var widgets = pageBuilderApp.widgetList.toJSON();

      this.model.set({
        [columnToUpdate] : {
          colWidgets: widgets,
          columnOptions:thisModelColDataAttr['columnOptions']
        }
      });
      
      //$('.isChagesMade').val('true');
      // var tua1 = performance.now();
      //console.log("Call to updateColumnWidgetTrigger took " + (tua1 - tua0) + " milliseconds.");
    },
    updateWidth: function() {
        rowColumns = this.model.get('columns');
        var this_column = $('.currentResizedRowColTarget').val();
        var thisColumnModelData = this.model.get(this_column);
        var this_column_widgets = thisColumnModelData['colWidgets'];
        var this_column_options = thisColumnModelData['columnOptions'];
        var this_column_bg_color = this_column_options['bg_color'];
        var this_column_margin = this_column_options['margin'];
        var this_column_padding = this_column_options['padding'];
        var columnCSS = this_column_options['columnCSS'];

        var rowID = this.model.get('rowID');
        var colWidth = $('section[RowID="'+rowID+'"]'+' .'+this_column).width();
        var pbWrapperWidth = $('section[RowID="'+rowID+'"]').width();
        var colWidthPercentage  = ( (colWidth/pbWrapperWidth) * 100);
        colWidthPercentage = colWidthPercentage.toFixed(2);
        var savedColWidth = this_column_options['width'];

        if (typeof(this_column_options['paddingTablet']) !== 'undefined') {
          var ColpaddingMobile = this_column_options['paddingMobile'];
          var ColmarginTablet = this_column_options['marginTablet'];
          var ColmarginMobile = this_column_options['marginMobile'];
          var ColpaddingTablet = this_column_options['paddingTablet'];
          var ColwidthTablet = this_column_options['widthTablet'];
          var ColwidthMobile = this_column_options['widthMobile'];
        }else{

          ColpaddingTablet = {
            rPTT:'',
            rPBT:'',
            rPLT:'',
            rPRT:'',
          }
          ColmarginTablet = {
            rMTT:'',
            rMBT:'',
            rMLT:'',
            rMRT:'',
          }
          ColpaddingMobile = {
            rPTM:'',
            rPBM:'',
            rPLM:'',
            rPRM:'',
          }

          ColmarginMobile = {
            rMTM:'',
            rMBM:'',
            rMLM:'',
            rMRM:'',
          }

          ColwidthTablet = '';
          ColwidthMobile = '';

        }

        var currentViewPortColOps = $('.currentViewPortSize').val();
        if (currentViewPortColOps == 'rbt-l') {
          savedColWidth = colWidthPercentage;
        }
        if (currentViewPortColOps == 'rbt-m') {
          ColwidthTablet = colWidthPercentage;
        }
        if (currentViewPortColOps == 'rbt-s') {
          ColwidthMobile = colWidthPercentage;
        }

       var widgets = this_column_widgets;
        this.model.set({
          [this_column] : {
            colWidgets: widgets,
            columnOptions:{
              bg_color: this_column_options['bg_color'],
              margin: this_column_options['margin'],
              padding:this_column_options['padding'],
              paddingTablet:this_column_options['paddingTablet'],
              paddingMobile:this_column_options['paddingMobile'],
              marginTablet:this_column_options['marginTablet'],
              marginMobile:this_column_options['marginMobile'],
              width: savedColWidth,
              widthTablet: ColwidthTablet,
              widthMobile: ColwidthMobile,
              columnCSS: this_column_options['columnCSS'],
              columnCustomClass: this_column_options['columnCustomClass'],
              colHideOnDesktop:this_column_options['colHideOnDesktop'],
              colHideOnTablet:this_column_options['colHideOnTablet'],
              colHideOnMobile:this_column_options['colHideOnMobile'],
              colBoxShadow: this_column_options['colBoxShadow'],
              colBorder:this_column_options['colBorder'],
              colBgImg:this_column_options['colBgImg'],
              colBackgroundType:this_column_options['colBackgroundType'],
              colGradient:this_column_options['colGradient'],
              colHoverOptions: this_column_options['colHoverOptions'],
            }
          }
        });


        currentResizedRowColTargetNext = $('.currentResizedRowColTargetNext').val();
        if (currentResizedRowColTargetNext != 'false') {
          currentResizedRowColTargetNext++;
          nextColumn = 'column'+currentResizedRowColTargetNext;
          $('.currentResizedRowColTarget').val(nextColumn);
          $('.currentResizedRowColTargetNext').val('false');
          $('section[RowID="'+rowID+'"]'+' .'+nextColumn).children('#WidthSave').trigger('click');
        }
      $('.isChagesMade').val('true');
    },
    updateRowHeight: function() {
      var rowID = this.model.get('rowID');
      var currentResizedRowHeight = $('.currentResizedRowHeight').val();
      var curreViewportS = $('.currentViewPortSize').val();
      if (curreViewportS == 'rbt-l') {
        this.model.set('rowHeight',currentResizedRowHeight);
        this.model.set('rowHeightUnit','px');
      } else if (curreViewportS == 'rbt-m') {
        this.model.set('rowHeightTablet',currentResizedRowHeight);
        this.model.set('rowHeightUnitTablet','px');
      } else if (curreViewportS == 'rbt-s') {
        this.model.set('rowHeightMobile',currentResizedRowHeight);
        this.model.set('rowHeightUnitMobile','px')
      } else{
        this.model.set('rowHeight',currentResizedRowHeight);
        this.model.set('rowHeightUnit','px');
      }
      
    },
    setColumnsOfThisModel: function(ev){
      var numberOfColumnsSelected =  $(ev.target).parent().attr('data-colNumber');
      this.model.set({columns:numberOfColumnsSelected });


      if ( typeof(this.model.get('column'+numberOfColumnsSelected)) == 'undefined') {
        for( var i = 0; i <= numberOfColumnsSelected; i++){
          if ( typeof(this.model.get('column'+i)) == 'undefined') {
            this_column = 'column'+i;
            this.model.set({

              [this_column] :  {
                colWidgets: [],
                columnOptions:{
                bg_color: 'transparent',
                margin: {
                  columnMarginTop: 0,
                  columnMarginBottom: 0,
                  columnMarginLeft: 0,
                  columnMarginRight: 0,
                },
                padding:{
                  columnPaddingTop: 0,
                  columnPaddingBottom: 0,
                  columnPaddingLeft: 0,
                  columnPaddingRight: 0,
                },
                paddingTablet:{
                  rPTT:'',
                  rPBT:'',
                  rPLT:'',
                  rPRT:'',
                },
                paddingMobile:{
                  rPTM:'',
                  rPBM:'',
                  rPLM:'',
                  rPRM:'',
                },
                marginTablet:{
                  rMTT:'',
                  rMBT:'',
                  rMLT:'',
                  rMRT:'',
                },
                marginMobile:{
                  rMTM:'',
                  rMBM:'',
                  rMLM:'',
                  rMRM:'',
                },
                width: '',
                widthTablet: '',
                widthMobile: '',
                columnCSS:'/* Add column custom styling here */',
                columnCustomClass:'',
                colHideOnDesktop:'',
                colHideOnTablet:'',
                colHideOnMobile:'',
                colBoxShadow: {
                  colBoxShadowH:'',
                  colBoxShadowV:'',
                  colBoxShadowBlur:'',
                  colBoxShadowColor:'',
                },
                colBgImg:'',
                colBackgroundType:'solid',
                colGradient:{
                  colGradientColorFirst: '#dd9933',
                  colGradientLocationFirst:'55',
                  colGradientColorSecond:'#eeee22',
                  colGradientLocationSecond:'50',
                  colGradientType:'linear',
                  colGradientPosition:'top left',
                  colGradientAngle:'135',
                },
                colHoverOptions: {
                  colBgColorHover:'',
                  colBackgroundTypeHover:'',
                  colHoverTransitionDuration:'',
                  colGradientHover:{
                    colGradientColorFirstHover: '',
                    colGradientLocationFirstHover:'',
                    colGradientColorSecondHover:'',
                    colGradientLocationSecondHover:'',
                    colGradientTypeHover:'linear',
                    colGradientPositionHover:'top left',
                    colGradientAngleHover:'',
                  },
                },
                }
              },

            });
          }
        }
      }

      $(this.el).html('');
      this.render();
      $('.isChagesMade').val('true');
    },
    widgetInlineEditorSave: function(ev){

      var this_column = $(ev.currentTarget).attr('data-wid_col_id');
      var this_widget = $(ev.currentTarget).attr('data-widget_id');
      var this_widget_type = $(ev.currentTarget).attr('data-widgettype');
      var thisColumnData = this.model.get(this_column);
      var this_widget = parseInt(this_widget);

      thisWidgetParentColID = $(ev.currentTarget).attr('data-parentwidgetid');
      $("body").unbind("click");

      $('#'+thisWidgetParentColID).children('.editColumn').click();
      jQuery('.edit_column').hide(0);
      jQuery('.ulpb_column_controls').hide(0);

      var EditedData = ' ';
      if (this_widget_type == 'wigt-pb-text') {
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();
        var parentElType = thisColumnData['colWidgets'][this_widget]['widgetText']['widgetTextTag'];
        if (parentElType == 'p') {
          if ( EditedData.includes('ltwFontScript') == false ) {
            var ltwFontScript = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').siblings('.ltwFontScript').html();
            ltwFontScript = '<div class="ltwFontScript" style="display:none;"> '+ltwFontScript+' </div>';
            EditedData = EditedData+ltwFontScript;
          }
        }
          //console.log(EditedData);
        
        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          widgetTextContent : EditedData,
        }
      }else if(this_widget_type == 'wigt-WYSIWYG'){

        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').children('.defaultELt').html();
        var liveTextFontScripts = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').children('.ltwFontScript').html();

        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          widgetContent : EditedData,
          widgetContentFonts : liveTextFontScripts
        }

      } else if(this_widget_type == 'wigt-btn-gen' || this_widget_type == 'wigt-pb-formBuilder'){
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();
        
        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          btnText : EditedData,
        }
      } else if(this_widget_type == 'wigt-pb-pricing'){
        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();

        var pricingbtnText = thisColumnData['colWidgets'][this_widget]['widgetPricing']['pricingbtnText'];
        var pbPricingHeaderText = thisColumnData['colWidgets'][this_widget]['widgetPricing']['pbPricingHeaderText'];
        var pbPricingContent = thisColumnData['colWidgets'][this_widget]['widgetPricing']['pbPricingContent'];

        if (thisFieldName == 'pricingbtnText') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            pricingbtnText : EditedData,
            pbPricingHeaderText: pbPricingHeaderText,
            pbPricingContent: pbPricingContent,
          }
        }
        if (thisFieldName == 'pbPricingHeaderText') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            pricingbtnText : pricingbtnText,
            pbPricingHeaderText: EditedData,
            pbPricingContent: pbPricingContent,
          }
        }
        if (thisFieldName == 'pbPricingContent') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            pricingbtnText : pricingbtnText,
            pbPricingHeaderText: pbPricingHeaderText,
            pbPricingContent: EditedData,
          }

        }

        //thisColumnData['colWidgets'][this_widget]['widgetPricing'][thisFieldName] = EditedData;

      }else if(this_widget_type == 'wigt-pb-navmenu'){

        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var thisFieldIndex = $(ev.currentTarget).attr('data-fieldIndex'); 
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();

        if (thisFieldName == 'cnilab') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            editedFieldName : thisFieldName,
            editedFieldIndex : thisFieldIndex,
            widgetContent : EditedData,

          }
        }

      }else if(this_widget_type == 'wigt-pb-cards'){

        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var thisFieldIndex = $(ev.currentTarget).attr('data-fieldIndex'); 
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();

        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          editedFieldName : thisFieldName,
          editedFieldIndex : thisFieldIndex,
          widgetContent : EditedData,
        }

      }else if(this_widget_type == 'wigt-pb-testimonial'){

        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var EditedData = $(ev.currentTarget).prev('#widgetInlineEditor').html();
        thisColumnData['colWidgets'][this_widget]['widgetTestimonial'][thisFieldName] = POPB_strip(EditedData);
      } else if (this_widget_type == 'wigt-pb-liveText') {
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').children('.defaultELt').html();
        var liveTextFontScripts = $(ev.currentTarget).siblings('.liveTextWrap').children('.ltwFontScript').html();

        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          wltc : EditedData,
          wltfs : liveTextFontScripts
        }
      }


      $('#widgets li:nth-child('+(this_widget+1)+')').children().children('.wdt-edit-controls').children('#updateInlineTextChanges').click();
      /*
      var this_column_widgets = thisColumnData['colWidgets'];
      var this_column_options = thisColumnData['columnOptions'];

        this.model.set({
          [this_column] : {
            colWidgets: this_column_widgets,
            columnOptions: this_column_options,
          }
        });
        */

      checkIEUL = true;

      jQuery('.edit_column').hide(0);
      jQuery('.ulpb_column_controls').hide(0);

      $("body").unbind("click");
      //$('.isChagesMade').val('true');

      //jQuery('.columnWidgetPopup').hide(300);
      //jQuery('.edit_column').hide(300);

      return;
    },


});

}( jQuery ) );