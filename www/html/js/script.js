var res_btn = $('.res-btn'),
    wrap_form = $('.wrap-form');

// Show-hide form
function toggleForm(){
   // Show-hide
   wrap_form.slideToggle(200);
   // Active-noactive btn
   $(this).toggleClass('active-form-home');
   // Update btn caret
   if ($(this).children('i').hasClass('fa-caret-down')) {
      $(this).children('i')
         .removeClass('fa-caret-down')
         .addClass('fa-caret-up');
   } else {
      $(this).children('i')
         .removeClass('fa-caret-up')
         .addClass('fa-caret-down');
   } 
};

// Handler
res_btn.on('click', toggleForm);
