/*
 * All event based listeners should be placed
 * in the document.ready(function()) or else
 * they may have a chance of incorrectly or
 * not binding to the actual element.
 */
$(document).ready(function()
{
   $('#continue-btn').click(function()
   {
      $('#page-index').fadeOut(function()
      {
         $('#page-loading').fadeIn(function()
         {
            var data_obj = {};
            data_obj.essay = $('#essay-text').val();

            $.ajax({
               url: 'index.php/api',
               type: 'POST',
               async: true,
               dataType: 'json',
               timeout: 25000,
               data: {data: JSON.stringify(data_obj)},
               success: function(data)
               {
                  alert(data);
               }
            });

            // perform AJAX in here.
         });
      });
   });
});
