window.addEventListener('load', function() {
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        
        if (e.target.files && e.target.files[0]) {
            var img = document.querySelector('img');
            img.src = URL.createObjectURL(e.target.files[0]); 

            const formData = new FormData();
            formData.append('imagen', e.target.files[0]);


                const options = {
                method: 'POST',
                body: formData,
                };


            fetch("ApiRes/imagen.php", options)
            .then(function(res){ return res.json(); })
            .then(function(data){ 
                console.log(data)
                alert( JSON.stringify( data ) ) })
    }
    });

  });