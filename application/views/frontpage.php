<div id="page-index" style="">
   <div id="header">
      <div id="logo">
         <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" /> 
      </div>

      <div class="infobox" id="infobox-1">
         <h3>What we provide</h3>
         This box contains a bunch of text which has a bunch of text and more text blah blah blah blah blah blah blah 
      </div>

      <div class="infobox" id="infobox-2">
         <h3>A HackNY Spring 2012 Project</h3>
         On <a href="https://github.com/sjlu/hacknys2012">Github</a>, developed by: 
         <ul>
            <li>Steven Lu (<a href="https://twitter.com/stevenlu">@stevenlu</a>) 
            <li>Russell Frank (<a href="https://twitter.com/russjf">@russjf</a>) 
            <li>Wayne Sun (<a href="https://twitter.com/_waynesun">@_waynesun</a>)
            <li>Jarek Sedlacek
      </div>
   </div>

   <div id="input">
      <textarea id="essay-text"></textarea>
   </div>

   <div id="actions">
      <button class="btn btn-large btn-primary" id="continue-btn">Beef Up My Essay</button>
   </div>
</div>

<div id="page-loading" style="display: none;">
   <div class="centered">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" />
      <div id="loading-indicator">
         <div class="progress progress-striped progress-info active">
            <div class="bar" style="width: 100%;"></div>
         </div>
         Grounding your beef...
      </div>
   </div>
</div>

<div id="page-interface" style="display: none;">
   <div id="topbar">
      <div id="topbar-container">
         <div id="topbar-logo">
            <img src="<?= base_url('assets/img/interface-logo.png') ?>" alt="topbar-logo" />
         </div>
      </div>
   </div>
   
   <table border="0" id="content-container">
   <tr>
      <td valign="top" width="70%">
         <div id="content"></div>
      </td>
      <td valign="top" width="30%">
         <div id="content-images"></div>
      </td>
   </tr>
   <tr>
      <td colspan="2">
         <div id="content-biblio"></div>
      </td>
   </tr> 
   </table>
</div>
