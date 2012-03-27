<div id="page-index" style="">
   <div id="header">
      <div id="logo">
         <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" /> 
      </div>

      <div class="infobox" id="infobox-1">
         <h3>Beef-ify your essay!</h3>
         Paste your essay into the box to beef it up. Our specially trained cows will automagically find sources, insert quotes, citations, and filler statements as well as building a bibliography and finding images!
      </div>

      <div class="infobox" id="infobox-2">
         <h3>A HackNY Spring 2012 Project</h3>
         On <a href="https://github.com/sjlu/hacknys2012">Github</a>, developed by: 
         <ul>
            <li>Steven Lu (<a href="https://twitter.com/stevenlu">@stevenlu</a>) 
            <li>Russell Frank (<a href="https://twitter.com/russjf">@russjf</a>) 
            <li>Wayne Sun (<a href="https://twitter.com/_waynesun">@_waynesun</a>)
            <li>Jarek Sedlacek
         </ul>
         Made under the <a href="http://parse.ly">parse.ly</a> API. Much thanks to them for helping us out!
      </div>
   </div>

   <div id="input">
      <textarea id="essay-text">Finally, the end game is almost in  sight. (Not talking about the Final Four.) The topics the two major  presidential candidates will be debating this autumn are lining up  nicely, promising an informative and fascinating process. Consider high gas prices. President  Barack Obama now has his talking points in order most of the time. He  makes the case that since he became president the country is doing more  drilling for oil and gas onshore and offshore. He notes the country is  importing less oil and becoming more fuel-efficient. Occasionally, Obama messes up and says  that when 55-mpg vehicles are mandated in a few years, the average  family will save $8,000 a year – he means it will save that over the  life of the vehicle. He also forgets to mention that such an impressive  mpg will cost at least $3,000 more per car. But overall, Obama makes a compelling  argument that drilling alone won’t reduce the price of gas – it takes a  decade before drilling affects pump prices, if ever – and that there are  far more drilling rigs operating in the United States than there were  when he took office. His insistence that we have to develop solar,  biofuels, wind and other alternatives to fossil fuel makes sense, as is  his demand that America be more innovative to be energy independent. Obama’s bottom line is that there is no  quick fix because America consumes 20 percent of the world’s oil output  but has only 2 percent of world oil reserves.

So, what are Mitt Romney’s talking  points about high gas prices as they soar past $4.28 a gallon? Wisely –  unlike the bombastic Newt Gingrich who blithely and without merit says  he could guarantee $2.50-a-gallon gas – Romney does not promise a  specific cap.  'No one can guarantee what the price of oil’s going to be,' he concedes. Nonetheless, despite the reality of  tight global oil markets, Romney blames Obama for high gas prices. That  is campaign meat thrown to partisan audiences.  As for policy, Romney wants to drill in  the Arctic National Wildlife Refuge, continue $4 billion in annual  subsidies to the oil industry despite its large profits and immediately  approve a pipeline extension from Canada to Texas. 

The White House  insists more research is necessary to ensure such an extension is safe.  Romney also would not mandate higher gas mileage standards for vehicles. This stacks up to be an interesting debate, with strikingly different options. Consider Afghanistan. In addition to the  dreary war for GOP delegates, the United States is involved in a real  war in which Americans are being killed and maimed. Obama, who  authorized a surge in troops even as he was ending the conflict in Iraq,  says the United States will pull out of Afghanistan by the end of 2014. Romney says Obama is wrong to set  timetables for withdrawal. He seeks continuing reevaluation of the war  but says we must see it through.  Because nobody has ever successfully  governed Afghanistan, and invading foreign powers have routinely been  brought to their knees, it is not clear how America will get out or how  we’ll know who 'won.'  The Obama administration put an end to  Osama bin Laden, but Romney says Obama’s foreign policy displays  weakness. Romney wants to threaten all-out war against Iran to prevent  it from getting nuclear weapons. Obama prefers diplomacy but does not  rule out military action. Romney insists Obama’s policies have  stretched the military to the breaking point. The Romney campaign argues  that even though Congress signed off on cutting defense, the country  needs 100,000 more troops and six more costly ships, possibly at the  expense of Social Security benefits. 

The soul-wearying GOP nomination process  will grind to a halt in a few months with Romney eking out the bare  minimum of delegates. Then we’ll see the real Romney emerge, and we’ll  start deciding whose vision will best move the country forward. Let the final debates begin. Please.</textarea>
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
  
   <div class="centered" id="loading-indicator-container" style="display: none;">
      <div id="loading-indicator-small"></div><br /><br />
      <span id="beef-label" class="label label-warning">Medium Rare</span>
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
      <td colspan="2" style="padding-top: 20px">
         <h3>Bibliography</h3>
         <div id="content-biblio"></div>
      </td>
   </tr> 
   </table>
</div>
