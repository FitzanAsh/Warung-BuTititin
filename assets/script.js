// Jika JavaScript tidak terkait dengan library lain, baris-baris berikut bisa langsung dimasukkan pada file HTML.
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.querySelector('.scrollable-container');
    const leftButton = document.querySelector('.left-button');
    const rightButton = document.querySelector('.right-button');
  
    leftButton.addEventListener('click', function() {
      scrollContainer.scrollLeft -= 100;
    });
  
    rightButton.addEventListener('click', function() {
      scrollContainer.scrollLeft += 100;
    });
  });
  


