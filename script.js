document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('ticket-form');
    
    form.onsubmit = function(e) {
        e.preventDefault(); 

        var formData = new FormData(form); 

        fetch('your-server-endpoint', {
            method: 'POST', 
            body: formData 
        })
        .then(response => {
            if (response.ok) {
                return response.json(); 
            }
            throw new Error('Network response was not ok.'); 
        })
        .then(data => {
            console.log('Success:', data); 
            form.reset(); 
            window.location.href = 'outra-pagina.html';
        })
        .catch((error) => {
            console.error('Error:', error); 
        });
    };
});
