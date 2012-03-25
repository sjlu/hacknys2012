/*
 * Essay Beefer: custom.js
 * @author: sjlu
 */

/*
 * Contains all functions and actions relevant
 * to the frontpage in a nice substanciated function
 */
function Frontpage () {
   var exports = {};

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

   function continue_click () {
      $('#page-index').fadeOut(function()
      {
         $('#page-loading').fadeIn(function()
         {
            // creating an object to send to api
            var data_obj = {};
            data_obj.essay = $('#essay-text').val();

            // ajax request
            run_ajax(data_obj);
         });
      });
   }
   exports.continue_click = continue_click;

   return exports;
}

/*
 * Load all the class/function here.
 */
var frontpage = Frontpage();

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
      frontpage.continue_click();
   });
});
