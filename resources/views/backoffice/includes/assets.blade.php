<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{ asset('backoffice/js/modernizr.2.5.3.min.js') }}"></script>
<script src="{{ asset('backoffice/js/app.js') }}"></script>
<script src="{{ asset('backoffice/js/javatm.js') }}"></script>
<!-- NOTIFIC8 -->
<script src="{{ asset('backoffice/vendor/notific8/jquery.notific8.min.js') }}"></script>

<script>
$(document).ready(function(){
    $(".menu-bt-hide").click(function(){
        //$(".header-menu").fadeIn();
        //$(".article-menu").animate({'margin-left': '-280px'});
        $(".article-menu").toggleClass("menu-hide");
        //$(".article-menu i").toggleClass("rodar180");
    });

	$(".header-menu").click(function(){
        $(".nav-menu").slideToggle();
    });
    /*$(".menuClick").click(function(){
        $(".menuClick").next().slideUp();
        if(!$(this).next().is(":visible")){$(this).next().slideDown();}
    });*/
    $(".menuClick").click(function(){
        var submenu=$(this).next();
        $(".menuOpen").not(submenu).slideUp();
        submenu.slideToggle();

        var seta=$(this).find('.fa-angle-down');
        $(".fa-angle-down").not(seta).removeClass('rodar180');
        seta.toggleClass('rodar180');

        $(".nav-sub-sub-menu").slideUp();
    });
    $(".menuClickSub").click(function(){
        var submenu=$(this).next();
        $(".menuOpenSub").not(submenu).slideUp();
        submenu.slideToggle();

        var seta=$(this).find('.fa-angle-down');
        $(".angle-sub").not(seta).removeClass('rodar180');
        seta.toggleClass('rodar180');
    });
});


// MENU FLUTUANTE
var aux_menu_account = 'nao';
function mostrarMenuAccount(){
    if(aux_menu_account == 'nao')
    {
        $("#menuAccount").css('opacity','0');
        $("#menuAccount").css('display','block');
        $("#menuAccount").animate({opacity:"1"}, 300);
        aux_menu_account='sim';
        //setTimeout(function(){ $("#mod_conta").css('display','none'); }, 500);
        // IDIOMA
        /*$("#IDIOMA").animate({opacity:"0"},400);
        setTimeout(function(){ $("#IDIOMA").css('display','none'); }, 500);
        aux_idioma = 'nao';*/
    }
    else
    {
        $("#menuAccount").animate({opacity:"0"},300);
        setTimeout(function(){ $("#menuAccount").css('display','none'); }, 400);
        aux_menu_account='nao';
    }
}

function mclose(){
    // IDIOMA
    /*$("#IDIOMA").animate({opacity:"0"},300);
    setTimeout(function(){ $("#IDIOMA").css('display','none'); }, 400);
    aux_idioma = 'nao';*/
    // CARRINHO
    $("#menuAccount").animate({opacity:"0"},300);
    setTimeout(function(){ $("#menuAccount").css('display','none'); }, 400);
    aux_menu_account = 'nao';
}

var timeout = 500;
var closetimer = 0;
function mcloset(){ closetimer = window.setTimeout(mclose, timeout); }
function mcancel(){ if(closetimer) {window.clearTimeout(closetimer); closetimer = null;} }


//Diagrama

function DiagramEditor(config, ui, done)
{
  this.config = (config != null) ? config : this.config;
  this.done = (done != null) ? done : this.done;
  this.ui = (ui != null) ? ui : this.ui;
  var self = this;

  this.handleMessageEvent = function(evt)
  {
    if (self.frame != null && evt.source == self.frame.contentWindow &&
      evt.data.length > 0)
    {
      try
      {
        var msg = JSON.parse(evt.data);

        if (msg != null)
        {
          self.handleMessage(msg);
        }
      }
      catch (e)
      {
        console.error(e);
      }
    }
  };
};

/**
 * Static method to edit the diagram in the given img or object.
 */
DiagramEditor.editElement = function(elt, config, ui, done)
{
  return new DiagramEditor(config, ui, done).editElement(elt);
};

/**
 * Global configuration.
 */
DiagramEditor.prototype.config = null;

/**
 * Protocol and domain to use.
 */
DiagramEditor.prototype.drawDomain = 'https://www.draw.io/';

/**
 * UI theme to be use.
 */
DiagramEditor.prototype.ui = 'min';

/**
 * Format to use.
 */
DiagramEditor.prototype.format = 'png';

/**
 * Specifies if libraries should be enabled.
 */
DiagramEditor.prototype.libraries = true;

/**
 * CSS style for the iframe.
 */
DiagramEditor.prototype.frameStyle = 'position:absolute;border:0;width:100%;height:100%;';

/**
 * Adds the iframe and starts editing.
 */
DiagramEditor.prototype.editElement = function(elem)
{
  var src = this.getElementData(elem);
  this.startElement = elem;
  var fmt = this.format;
console.log(src.substring(0, 15));
  /*if (src.substring(0, 15) === 'data:image/png;')
  {
    
  }
  else if (src.substring(0, 19) === 'data:image/svg+xml;' ||
    elem.nodeName.toLowerCase() == 'svg')
  {
    fmt = 'xmlsvg';
  }*/
fmt = 'xmlpng';
  this.startEditing(src, fmt);

  return this;
};

/**
 * Adds the iframe and starts editing.
 */
DiagramEditor.prototype.getElementData = function(elem)
{
  var name = elem.nodeName.toLowerCase();

  return elem.getAttribute((name == 'svg') ? 'content' :
    ((name == 'img') ? 'src' : 'data'));
};

/**
 * Adds the iframe and starts editing.
 */
DiagramEditor.prototype.setElementData = function(elem, data)
{
  var name = elem.nodeName.toLowerCase();

  if (name == 'svg')
  {
    elem.outerHTML = atob(data.substring(data.indexOf(',') + 1));
  }
  else
  {
    elem.setAttribute((name == 'img') ? 'src' : 'data', data);
  }

  return elem;
};

/**
 * Starts the editor for the given data.
 */
DiagramEditor.prototype.startEditing = function(data, format, title)
{
  if (this.frame == null)
  {
    window.addEventListener('message', this.handleMessageEvent);
    this.format = (format != null) ? format : this.format;
    this.title = (title != null) ? title : this.title;
    this.data = data;

    this.frame = this.createFrame(
      this.getFrameUrl(),
      this.getFrameStyle());
    document.body.appendChild(this.frame);
    this.setWaiting(true);
  }
};

/**
 * Updates the waiting cursor.
 */
DiagramEditor.prototype.setWaiting = function(waiting)
{
  if (this.startElement != null)
  {
    // Redirect cursor to parent for SVG and object
    var elt = this.startElement;
    var name = elt.nodeName.toLowerCase();
    
    if (name == 'svg' || name == 'object')
    {
      elt = elt.parentNode;
    }
    
    if (elt != null)
    {
      if (waiting)
      {
        this.frame.style.pointerEvents = 'none';
        this.previousCursor = elt.style.cursor;
        elt.style.cursor = 'wait';
      }
      else
      {
        elt.style.cursor = this.previousCursor;
        this.frame.style.pointerEvents = '';
      }
    }
  }
};

/**
 * Updates the waiting cursor.
 */
DiagramEditor.prototype.setActive = function(active)
{
  if (active)
  {
    this.previousOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';
  }
  else
  {
    document.body.style.overflow = this.previousOverflow;
  }
};

/**
 * Removes the iframe.
 */
DiagramEditor.prototype.stopEditing = function()
{
  if (this.frame != null)
  {
    window.removeEventListener('message', this.handleMessageEvent);
    document.body.removeChild(this.frame);
    this.setActive(false);
    this.frame = null;
  }
};

/**
 * Send the given message to the iframe.
 */
DiagramEditor.prototype.postMessage = function(msg)
{
  if (this.frame != null)
  {
    this.frame.contentWindow.postMessage(JSON.stringify(msg), '*');
  }
};

/**
 * Returns the diagram data.
 */
DiagramEditor.prototype.getData = function()
{
  return this.data;
};

/**
 * Returns the title for the editor.
 */
DiagramEditor.prototype.getTitle = function()
{
  return this.title;
};

/**
 * Returns the CSS style for the iframe.
 */
DiagramEditor.prototype.getFrameStyle = function()
{
  return this.frameStyle + ';left:' +
    document.body.scrollLeft + 'px;top:' +
    document.body.scrollTop + 'px;';
};

/**
 * Returns the URL for the iframe.
 */
DiagramEditor.prototype.getFrameUrl = function()
{
  var url = this.drawDomain + '?embed=1&proto=json&spin=1';

  if (this.ui != null)
  {
    url += '&ui=' + this.ui;
  }

  if (this.libraries != null)
  {
    url += '&libraries=1';
  }

  if (this.config != null)
  {
    url += '&configure=1';
  }

  return url;
};

/**
 * Creates the iframe.
 */
DiagramEditor.prototype.createFrame = function(url, style)
{
  var frame = document.createElement('iframe');
  frame.setAttribute('frameborder', '0');
  frame.setAttribute('style', style);
  frame.setAttribute('src', url);

  return frame;
};

/**
 * Sets the status of the editor.
 */
DiagramEditor.prototype.setStatus = function(messageKey, modified)
{
  this.postMessage({action: 'status', messageKey: messageKey, modified: modified});
};

/**
 * Handles the given message.
 */
DiagramEditor.prototype.handleMessage = function(msg)
{
  if (msg.event == 'configure')
  {
    this.configureEditor();
  }
  else if (msg.event == 'init')
  {
    this.initializeEditor();
  }
  else if (msg.event == 'autosave')
  {
    this.save(msg.xml, true, this.startElement);
  }
  else if (msg.event == 'export')
  {
    this.save(msg.data, false, this.startElement);
    this.stopEditing();
  }
  else if (msg.event == 'save')
  {
    
    if (msg.exit)
    {
      msg.event = 'exit';
    }
    else
    {
      this.setStatus('allChangesSaved', false);
    }
  }

  if (msg.event == 'exit')
  {
    
    if (this.format != 'xml' && !msg.modified)
    {
    
      this.postMessage({action: 'export', format: this.format,
        xml: msg.xml, spinKey: 'export'});
    }
    else
    {
    
      this.save(msg.xml, false, this.startElement);
      this.stopEditing(msg);
    }
  }
};

/**
 * Posts configure message to editor.
 */
DiagramEditor.prototype.configureEditor = function()
{
  this.postMessage({action: 'configure', config: this.config});
};

/**
 * Posts load message to editor.
 */
DiagramEditor.prototype.initializeEditor = function()
{
  this.postMessage({action: 'load',autosave: 1, saveAndExit: '1',
    modified: 'unsavedChanges', xml: this.getData(),
    title: this.getTitle()});
  this.setWaiting(false);
  this.setActive(true);
};

/**
 * Saves the given data.
 */
DiagramEditor.prototype.save = function(data, draft, elt)
{
 
  if (elt != null && !draft)
  {
    var file_ip_name = $('#files_aux_ip').val();

    var cache = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var length = 3;
    for (var i = 0; i < length; i++){
      cache += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    

    var html_file = '<a href="'+data+'" download="diagrama_'+cache+'"> diagrama_'+cache+'</a>';
    $('#doc_upload').html(html_file);
    //this.done(data, draft, elt);
    this.setElementData(elt, data);
    $('.select2-selection').show();

    if(file_ip_name){ $('#div_'+file_ip_name).remove(); }

    var html_aux ='<div id="div_'+cache+'" class="row"><div class="col-md-12"><div><div class="div-50"><div class="div-40"><label class="a-dotted-white cursor-pointer" id="'+cache+'_upload"><label onclick="showDiagramExist(\''+cache+'\');">diagrama_'+cache+'.png</label></label></div></div><label class="lb-40 bt-azul float-right" onclick="limparFicheiros(\''+cache+'\');"><i class="fa fa-trash-alt"></i></label></div></div></div>';

    var aux_img = '<img id="aux_img'+cache+'" onclick="DiagramEditor.editElement(this);" src="'+data+'" class="cursor-pointer display-none" style="">';
    var aux_img_ip = '<input id="aux_ip'+cache+'" name="ficheiros_aux_diagrama[]" type="hidden" value="'+data+'"></input>';

    $('#files_aux').append(html_aux);
    $('#files_aux').append(aux_img);
    $('#files_aux').append(aux_img_ip);
  
  }
  
};

/**
 * Invoked after save.
 */
DiagramEditor.prototype.done = function()
{
  // hook for subclassers
};


function showDiagramExist(cache){
  console.log(cache);
  $('.select2-selection').hide();
  $('#files_aux_ip').val(cache);
  $('#aux_img'+cache).click();
}

</script>

