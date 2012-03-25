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
function Loading_page () {

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
            alert(data.essay);
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
      });
   }
   exports.load = load;

   return exports;
}

/*
 * All relevant functions to interface_page
 */
function Interface_page () {
   
   function priv()
   {

   }

   var exports = {};
   
   function pub()
   {

   }
   exports.pub = pub;

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
});
