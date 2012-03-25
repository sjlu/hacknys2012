/*
 * Essay Beefer: custom.js
 * @author: sjlu
 */

/*
 * Contains all functions and actions relevant
 * to the frontpage in a nice substanciated function
 */
function Front_page () {
   var exports = {};

   function continue_click () {
      $('#page-index').fadeOut(function()
      {
         loading_page.load();
      });
   }
   exports.continue_click = continue_click;

   return exports;
}

/*
 * All relevant functions to loading_page
 */
var Loading_page = function () {

   var interval_id;

   function Loading_page () {
      var internal = {};

      function run_ajax (data) {
         $.ajax({
            url: 'index.php/api',
            type: 'POST',
            async: true,
            dataType: 'json',
            timeout: 25000,
            data: {data: JSON.stringify(data)},
            success: function(data)
            {
               // parsing response from api
               //alert(data.essay);
               $('#page-loading').fadeOut(function()
               {
                  clearInterval(interval_id);
                  interface_page.load(data);
               });
            }
         });
      }
      
      var exports = {};

      function load() 
      {
         $('#page-loading').fadeIn(function()
         {
            // creating an object to send to api
            var data_obj = {};
            data_obj.essay = $('#essay-text').val();

            // ajax request
            run_ajax(data_obj);
            rotate_fact();
            interval_id = setInterval(loading_page.rotate_fact, 5500);
         });
      }
      exports.load = load;

      function rotate_fact()
      {
         var facts = [
            "A cow that weighs 1000 pounds will make a carcass weighing about 615 pounds. The carcass makes about 432 pounds of meat.",
            "Beef fat, called tallow, is an ingredient in soaps, cosmetics, candles, shortenings, and chewing gum.",
            "Many medicines, including insulin and estrogen, are made from the glands of the cow.",
            "Consumer beef spending has grown $14 billion compared to the 1990s according to CattleFax.",
            "It was decreed by law in the Roman Empire that all young maidens be fed rabbit meat because it would make them more beautiful and more willing.",
            "In 2012, over 32.5 billions tons of beef were shipped to the Courant Institute at NYU during Start-up Week. This fact is also completely false."
         ];
      
         var rand = Math.floor(Math.random() * facts.length);
         $('#fact').hide().html(facts[rand]).fadeIn();
      }
      exports.rotate_fact = rotate_fact;

      return exports;
   }
   
   return Loading_page;

}();
/*
 * All relevant functions to interface_page
 */
function Interface_page () {
   
   function run_ajax (data) {
      $.ajax({
         url: 'index.php/api',
         type: 'POST',
         async: true,
         dataType: 'json',
         timeout: 25000,
         data: {data: JSON.stringify(data)},
         success: function(data)
         {
            //parsing response from api
            //alert(data.essay);
            $('#loading-indicator-container').fadeOut(function()
            {
               interface_page.load(data);
            });
         }
      });
   }

   function change_background()
   {
      $('body').css('background','#eaeaea');
      //$('#container').animate({ backgroundColor: "#eaeaea" }, 1000);
   }

   function write_essay(html)
   {
      /*
      var output = "";
      
      for (var line in html)
      {
         output += html[line] + " ";
      }

      $('#content').html(output);
      */

      $('#content').html(html);
   }

   function write_biblio(data_array)
   {
      var output = "";
      
      for (var inline in data_array)
      {
         if (data_array[inline].citation != undefined)
         {
            output += data_array[inline].citation;
            output += "<br />";
         }
      }

      $('#content-biblio').html(output);
   }

   function write_images(data_array)
   {
      var output = "";

      for (var image in data_array)
      {
         if (data_array[image][0] != undefined)
         {
            output += '<div class="image-holder"><img src="'+data_array[image][0]+'" class="image" alt="inline-image" onerror="this.parentNode.removeChild(this);" /></div>';
         }
      }

      $('#content-images').html(output);
   }

   function write_statboxes(data)
   {
      // Get source count
      var source_count = 0;
      for (var source in data.bibliography)
         source_count++;

      // Get word count (hacked up)
      var word_count = 0;
      var words = $('#content').html().split(" ");
      for (var i = 0; i < words.length; i++)
      {
         if (words[i] != "")
            word_count++;
      }

      var old_word_count = 0;
      var words = data.essay.split(" ");
      for (var i = 0; i < words.length; i++)
      {
         if (words[i] != "")
            old_word_count++;
      }

      word_count = word_count - old_word_count;

      // Get loaded image count.
      var image_count = document.images.length;
      
      $('#word-count').html("+"+word_count);
      $('#source-count').html(source_count);
      $('#image-count').html(image_count);
   }

   var exports = {};
   
   function change_tooltip(beeflevel)
   {
      var color = "label-warning";
      if (beeflevel < 2)
         color = "label-important";
      else if (beeflevel > 2)
         color = "label-success";

      var text;
      if (beeflevel == 0)
         text = "Rare";
      else if (beeflevel == 1)
         text = "Medium Rare";
      else if (beeflevel == 2)
         text = "Medium";
      else if (beeflevel == 3)
         text = "Medium Well";
      else if (beeflevel == 4)
         text = "Well Done";

      $('#beef-label').removeClass('label-warning');
      $('#beef-label').removeClass('label-important');
      $('#beef-label').removeClass('label-success');
      
      $('#beef-label').addClass(color);
      $('#beef-label').html(text);
   }
   exports.change_tooltip = change_tooltip;

   function load(data)
   {
      /*
       * Stuff passed to me:
       * essay, bibliography(Array), beefed_essay, images(Array)
       */
      
      // Writing HTML shit now
      write_essay(data.beefed_essay);
      write_biblio(data.bibliography);
      write_images(data.images);
      write_statboxes(data);
      //alert(data.bibliography);

      change_background();
      $('#page-interface').fadeIn();
      $('#content-container').fadeIn(); 
   }
   exports.load = load;
   
   function do_reload(beeflevel)
   {
      var data_obj = {};
      data_obj.essay = $('#essay-text').val();
      data_obj.cooked = beeflevel;
      
      $('#content-container').fadeOut();
      $('#loading-indicator-container').fadeIn();
      run_ajax(data_obj);
   }
   exports.do_reload = do_reload;

   return exports;
}

/*
 * Load all the class/function here.
 */
var front_page = Front_page();
var loading_page = Loading_page();
var interface_page = Interface_page();

/*
 * All event based listeners should be placed
 * in the document.ready(function()) or else
 * they may have a chance of incorrectly or
 * not binding to the actual element.
 */
$(document).ready(function()
{
   // This the click event handler javascript.
   $('#continue-btn').click(function()
   {
      front_page.continue_click();
   });

   $('.slider').slider({
      animate: true,
      range: "min",
      value: 2,
      min: 0,
      max: 4,
      //step: 1,
      slide: function(event, ui) {
         //$("#slider-result").html(ui.value);
         // change tooltip here.
         interface_page.change_tooltip(ui.value);
      },
      change: function(event, ui) {
         //$("#hidden").attr('value', ui.value);
         interface_page.do_reload(ui.value);
         // do post values here.
      }
   });

   $('#loading-indicator-small').activity({
      segments: 12, 
      width: 5.5, 
      space: 6, 
      length: 13, 
      color: '#252525', 
      speed: 1.5
   });

   $('.ui-slider-handle').tooltip({
      animation: true,
      placement: 'bottom',
      title: 'Slide for Tenderness',
      delay: { show: 500, hide: 1000 }
   });
});
