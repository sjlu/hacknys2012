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
      <button class="btn btn-large btn-primary" id="continue-btn">Sizzle My Beef</button>
   </div>
</div>

<div id="page-loading" style="display: none;">
   <div class="centered">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" />
      <div id="loading-indicator">
         <div class="progress progress-striped progress-info active">
            <div class="bar" style="width: 100%;"></div>
         </div>
         <span id="fact"></span>
      </div>
   </div>
</div>

<div id="page-interface" style="display: none;">
   <div id="topbar">
      <div id="topbar-container">
         <div id="topbar-toolbar">
            <div id="toolbar-slider">
               <div id="beef-slider" class="slider"></div>
            </div>

            <div class="toolbar-statbox">
               <h4>Word Difference</h4>
               <h2 id="word-count" class="green"></h2>
            </div>

            <div class="toolbar-statbox">
               <h4>Sources Count</h4>
               <h2 id="source-count"></h2>
            </div>

            <div class="toolbar-statbox">
               <h4>Image Count</h4>
               <h2 id="image-count"></h2>
            </div>
         </div>

         <div id="topbar-logo">
            <img src="<?= base_url('assets/img/interface-logo.png') ?>" alt="topbar-logo" />
         </div>
      </div>
   </div>
  
   <div class="centered" id="loading-indicator-container" style="display: none">
      <div id="loading-indicator-small"></div>
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
