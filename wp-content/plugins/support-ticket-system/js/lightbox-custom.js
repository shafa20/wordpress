document.querySelectorAll('.lightbox-trigger').forEach(function(element) {
    element.addEventListener('click', function() {
        var imageSrc = element.getAttribute('data-image');
        var tempLink = document.createElement('a');
        tempLink.href = imageSrc;
        tempLink.setAttribute('data-lightbox', 'mygallery');
        tempLink.style.display = 'none';
        document.body.appendChild(tempLink);
        tempLink.click();
        document.body.removeChild(tempLink);
    });
});


